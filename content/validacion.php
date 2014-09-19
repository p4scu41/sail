<?PHP 
echo '<h2 align="center">VALIDACI&Oacute;N PLATAFORMAS</h2>';

$objHTML = new HTML();

$objHTML->startFieldset();

echo '<br />
    <div class="img_validacion">
        <a href="?mod=valSIS">
        <img src="images/SIS.png"></a></div>
    <div class="img_validacion">
        <a href="?mod=valSUAVE">
        <img src="images/SUAVE.png"></a></div>
<br />';

$objHTML->endFieldset();
?>