<script type="text/javascript">
if(getQuerystring('saved') == 'true') {
    jAlert('<img src="images/ok.gif" > <strong>Comentarios guardados exitosamente</strong>', 'Datos guardados correctamente');
}
</script>
<?PHP 
include_once 'include/clases/Incidencia.php';

$objHTMl = new HTML();

if(isset($_POST['asunto'])) {
    $ayuda = new Incidencia();
    
    $ayuda->contenido = $_POST['asunto'].' - '.$_POST['cuerpo'];
    
    $ayuda->insertarBD();
    
    redirect('?mod=help&saved=true');
}

echo '<h2 align="center">ENVIA TUS COMENTARIOS</h2>';

$objHTMl->startForm('form_busca', '?mod=help', 'POST');

$objHTMl->startFieldset('', array('style'=>'text-align: center;'));
	$objHTMl->inputText('Asunto: ', 'asunto', '', array('size'=>'60', 'required'=>'required'));
    echo '<br />';
    $objHTMl->label('Contenido:');
    echo '<br />';
    $objHTMl->inputTextarea('', 'cuerpo', '', array('rows'=>'10', 'cols'=>'60', 'required'=>'required'));
	
$objHTMl->endFieldset();

$objHTMl->endForm('enviar', 'Enviar', 'limpiar', 'Limpiar');

if($_POST['enviar']) {
	echo '<br><br><h3 align="center">Comentarios enviados exitosamente</h3>';
}
?>