<?php
if(isset($_GET['id']))
{
    include 'configRodo.php';
	$connection=mysql_connect("$bdservidor","$bdunombre","$bdpass") or die("Error conectando a la base de datos");
	$db=mysql_select_db("$bdnombre",$connection) or die ("Error seleccionando la base de datos");

    $id      = $_GET['id'];
    $query   = "SELECT name, type, size, content FROM upload WHERE id = '$id'";
    $result  = mysql_query($query) or die('Error, query failed');
    list($name, $type, $size, $content) = mysql_fetch_array($result);

    header("Content-Disposition: attachment; filename=$name");
    header("Content-length: $size");
    header("Content-type: $type");
	
    echo $content;

    exit;
}
?>
