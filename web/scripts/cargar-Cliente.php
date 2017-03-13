<?php
include ("clases/class-mysql.php");
include ("clases/class-combos.php");
$selects = new selects();
$clientes = $selects->cargar1();
foreach ($clientes as $key=>$value)
{
    echo "<option value=\"$key\">$value</option>";
}
?>
