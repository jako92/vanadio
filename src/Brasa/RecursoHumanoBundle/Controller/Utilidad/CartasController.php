<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCartaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CartasController extends Controller
{
    /**
     * @Route("/rhu/utilidades/cartas/generar", name="brs_rhu_utilidades_cartas_generar")
     */
    public function generarAction(Request $request) {        
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 82)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $arUsuario = $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
        $arUsuario = $arUsuario->getUserName();
        $codigoCartaTipo = $arCartaTipo->getCodigoCartaTipoPk();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('cartaTipoRel', EntityType::class,
                array('class' => 'BrasaRecursoHumanoBundle:RhuCartaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                    ->where('ct.especial = 0')
                            ->orderBy('ct.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true,
                ))    
            ->add('fecha', DateType::class, array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => new \DateTime('now') , 'attr' => array('class' => 'date')))    
            ->add('fechaOpcional', DateType::class, array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))    
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                $codigoContrato = $arEmpleado->getCodigoContratoActivoFk();
                if ($codigoContrato == null){
                    $codigoContrato = $arEmpleado->getCodigoContratoUltimoFk();
                }
                if(count($arEmpleado) > 0) {
                    //if($arEmpleado->getCodigoContratoActivoFk() != '') {                        
                        $arCartaTipo = $form->get('cartaTipoRel')->getData();
                        $codigoCartaTipo = $arCartaTipo->getCodigoCartaTipoPk();
                        $fechaProceso = $form->get('fecha')->getData()->format('Y-m-d');
                        $fechaOpcional = $form->get('fechaOpcional')->getData();
                        //$codigoContrato = $arEmpleado->getCodigoContratoActivoFk();
                        $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta();
                        $objFormatoCarta->Generar($em, $arUsuario, $codigoTipoCarta,$fechaProceso,$fechaOpcional,$codigoContrato,$booleamSalario,$booleamPromedioIbp,$booleamPromedioNoPrestacional,$salarioSugerido,$promedioIbpSugerido,$promedioNoPrestacionalSugerido);
                    /*}// else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo");
                    }*/
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe");
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Cartas:nuevo.html.twig', array(
            'form' => $form->createView()));
    }

}
