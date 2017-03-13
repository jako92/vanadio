<?php
include ("clases/class-mysql.php");
include ("clases/class-combos.php");
$sucursales = new selects();
$sucursales->code = $_GET["code"];
$sucursales = $sucursales->cargarSucursal();
foreach ($sucursales as $key=>$value)
{
      echo "<option value=\"$key\">$value</option>";
}
?>
