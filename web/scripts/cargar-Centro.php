<?php
include ("clases/class-mysql.php");
include ("clases/class-combos.php");
$ciudades = new selects();
$ciudades->code = $_GET["code"];
$ciudades = $ciudades->cargarCentro();
foreach ($ciudades as $key=>$value)
{
    echo "<option value=\"$key\">$value</option>";

}
?>
