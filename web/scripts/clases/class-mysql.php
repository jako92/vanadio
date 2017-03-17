<?php
class MySQL
{
    var $conexion;
    function MySQL()
    {
        if(!isset($this->conexion))
        {
            $link = mysqli_connect("localhost", "root", "1152689427");
//            $this->conexion = (new mysqli('localhost', 'root', '1152689427','bdseracis') or die(mysqli_error()));
//         mysqli_select_db($this->conexion,"bdseracis" ) or die (mysqli_error());
        }
    }
    function consulta($consulta){
//           $em = $this->getDoctrine()->getManager();
//        $link =  $em->getConnection();
        $link = mysqli_connect("localhost", "root", "1152689427", "bdsogercol");
        $resultado = mysqli_query($link, $consulta);
        if(!$resultado){
            echo'Mysql Error:'.mysqli_error();
            exit();
        }
        return $resultado;
    }
    function fetch_array($consulta)
    {
        return mysqli_fetch_array($consulta);
    }
    
     function num_rows($consulta)
    {
        return mysqli_num_rows($consulta);
    }
    
     function fetch_row($consulta)
    {
        return mysqli_fetch_row($consulta);
    }
    
     function fetch_assoc($consulta)
    {
        return mysqli_fetch_assoc($consulta);
    }
}
?>

