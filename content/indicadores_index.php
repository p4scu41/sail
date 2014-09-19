<?PHP 
echo '<h2 align="center">INDICADORES</h2>';

$objHTML = new HTML();

$objHTML->startFieldset();

echo '
    <div class="img_reporte">
        <a href="?mod=ind"><h3>&Iacute;ndice B&aacute;sico de Desempe&ntilde;o</h3>
        <img src="images/reg.png"></a>
	</div>
    <div class="img_reporte">
        <a href="?mod=camEx"><h3>Caminando a la Excelencia</h3>
        <img src="images/tarjetar.png"></a>
	</div>
<br />';

$objHTML->endFieldset();
/*
echo '<br><br>';

$objHTML->startFieldset();

echo '<div class="img_reporte_row">
        <a href="#"><h3>Caminando a la Excelencia</h3>
        <img src="images/tarjetar.png"></a></div>
    <div class="img_reporte">';

$objHTML->endFieldset();*/
?>