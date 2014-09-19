<?PHP 
if(empty($_GET['sis'])) {
	$objHTML = new HTML();

	echo '<h2 align="center">HERRAMIENTAS</h2>';
	
	$objHTML->startFieldset();

	echo '<br />
		<div class="img_validacion"><h3>PIRAMIDE POBLACIONAL</h3>
			<a href="?mod=her&sis=piramide">
			<img src="images/piramide.png"></a></div>
		<div class="img_validacion">
			<a href="?mod=her&sis=canal"><h3>CANALES END&Eacute;MICOS</h3>
			<img src="images/canales.png"></a></div>
	<br />';

	$objHTML->endFieldset();
}

if($_GET['sis'] == 'canal')
{
    echo '<h2 align="center">CANALES ENDEMICOS</h2>';
	echo '<iframe width="100%" height="600px" frameborder="0" src="../canales_endemicos_10/index.php?conexion=foranea"></iframe>';
}
else if($_GET['sis'] == 'piramide'){
    echo '<h2 align="center">PIRAMIDE POBLACIONAL</h2>';
	echo '<iframe width="100%" height="600px" frameborder="0" src="../piramide_poblacional/index.php?conexion=foranea"></iframe>';	
}
?>