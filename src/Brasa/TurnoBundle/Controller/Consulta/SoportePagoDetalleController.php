<?php
namespace Brasa\TurnoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SoportePagoDetalleController extends Controller
{
    var $strListaDql = "";
    var $codigoSoportePago = "";
    var $codigoRecurso = "";
    
    /**
     * @Route("/tur/consulta/soporte/pago/detalle", name="brs_tur_consulta_soporte_pago_detalle")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 43)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        if ($form->isValid()) {    
            $arrControles = $request->request->All();          
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();                
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }            
        }
        $arSoportePagoDetalles = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Consultas/SoportePago:detalle.html.twig', array(
            'arSoportePagoDetalles' => $arSoportePagoDetalles,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();        
        $strFechaHasta = "";                
        $strFechaHasta = $session->get('filtroSoportePagoDetalleFechaHasta');                         
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->listaConsultaDql(
                $this->codigoSoportePago, 
                $session->get('filtroCodigoRecurso'),
                $session->get('filtroSoportePagoDetalleEstadoCerrado'),
                $strFechaHasta,
                $session->get('filtroCodigoTurno')                               
                );
    }

    private function filtrar ($form) {   
        $session = new session;                
        $session->set('filtroCodigoRecurso', $form->get('TxtCodigoRecurso')->getData());
        $session->set('filtroSoportePagoDetalleEstadoCerrado', $form->get('estadoCerrado')->getData());        
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroSoportePagoDetalleFechaHasta', $dateFechaHasta->format('Y/m/d'));        
        $session->set('filtroCodigoTurno', $form->get('TxtCodigoTurno')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreRecurso = "";
        if($session->get('filtroCodigoRecurso')) {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($session->get('filtroCodigoRecurso'));
            if($arRecurso) {                
                $strNombreRecurso = $arRecurso->getNombreCorto();
            }  else {
                $session->set('filtroCodigoRecurso', null);
            }          
        }
        /*$strNombreTurno = "";
        if($session->get('filtroCodigoTurno')) {
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($session->get('filtroCodigoTurno'));
            if($arTurno) {                
                $strNombreTurno = $arTurno->getNombreCorto();
            }  else {
                $session->set('filtroCodigoTurno', null);
            }          
        }*/        
        $dateFecha = new \DateTime('now');
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroSoportePagoDetalleFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroSoportePagoDetalleFechaHasta');
        }    
        $dateFechaHasta = date_create($strFechaHasta);        
        $form = $this->createFormBuilder()
            
            ->add('TxtCodigoRecurso', TextType::class, array('data' => $session->get('filtroCodigoRecurso')))
            ->add('TxtNombreRecurso', TextType::class, array('data' => $strNombreRecurso))
            ->add('TxtCodigoTurno', TextType::class, array('data' => $session->get('filtroCodigoTurno')))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta)) 
            ->add('estadoCerrado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'CERRADO' => '1', 'SIN CERRAR' => '0'), 'data' => $session->get('filtroServicioDetalleEstadoCerrado')))                                                
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);        
        $em = $this->getDoctrine()->getManager();        
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AB'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }         
        $objPHPExcel->setActiveSheetIndex(0)       
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'RECURSO')
                    ->setCellValue('C1', 'TURNO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'DIAS')
                    ->setCellValue('F1', 'DESCANSO')
                    ->setCellValue('G1', 'HDS')    
                    ->setCellValue('H1', 'HD')
                    ->setCellValue('I1', 'HN')
                    ->setCellValue('J1', 'HFD')
                    ->setCellValue('K1', 'HFN')                
                    ->setCellValue('L1', 'HEOD')
                    ->setCellValue('M1', 'HEON')
                    ->setCellValue('N1', 'HEFD')
                    ->setCellValue('O1', 'HEFN');
        
        $i = 2;
        
        $query = $em->createQuery($this->strListaDql);
        $arSoportesPagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportesPagoDetalle = $query->getResult();

        foreach ($arSoportesPagoDetalle as $arSoportePagoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSoportePagoDetalle->getCodigoSoportePagoDetallePk())
                    ->setCellValue('B' . $i, $arSoportePagoDetalle->getRecursoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arSoportePagoDetalle->getCodigoTurnoFk())
                    ->setCellValue('D' . $i, $arSoportePagoDetalle->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arSoportePagoDetalle->getDias())
                    ->setCellValue('F' . $i, $arSoportePagoDetalle->getDescanso())
                    ->setCellValue('G' . $i, $arSoportePagoDetalle->getHorasDescanso())
                    ->setCellValue('H' . $i, $arSoportePagoDetalle->getHorasDiurnas())
                    ->setCellValue('I' . $i, $arSoportePagoDetalle->getHorasNocturnas())
                    ->setCellValue('J' . $i, $arSoportePagoDetalle->getHorasFestivasDiurnas())
                    ->setCellValue('K' . $i, $arSoportePagoDetalle->getHorasFestivasNocturnas())                    
                    ->setCellValue('L' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('M' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('N' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('O' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasNocturnas());

            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('SoportePagoDetalle');                
        $objPHPExcel->setActiveSheetIndex(0);                        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SoportePagoDetalle.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }     

}