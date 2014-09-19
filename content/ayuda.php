<?PHP 
include_once 'include/clases/indencia.php';

$objHTMl = new HTML();

echo '<h2 align="center">ENVIA TUS COMENTARIOS</h2>';

$objHTMl->startForm('form_busca', '?mod=help', 'POST');

$objHTMl->startFieldset('', array('style'=>'text-align: center;'));
	$objHTMl->inputText('Asunto: ', 'asunto', '', array('size'=>'60'));
    echo '<br />';
    $objHTMl->label('Contenido:');
    echo '<br />';
    $objHTMl->inputTextarea('', 'cuerpo', '', array('rows'=>'10', 'cols'=>'60'));
	
$objHTMl->endFieldset();

$objHTMl->endForm('enviar', 'Enviar', 'limpiar', 'Limpiar');

if($_POST['enviar']) {
	echo '<br><br><h3 align="center">Comentarios enviados exitosamente</h3>';
}
?>