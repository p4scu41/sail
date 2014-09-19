<link rel="stylesheet" href="include/jquery-ui-1.8.14.custom/development-bundle/themes/base/jquery.ui.all.css">

<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/js/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.position.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.dialog.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$("#winExportBD").dialog({
		autoOpen: false,
		height: 150,
		width: 250,
		modal: true,
		resizable: true
	});
    
    $('#btnExportarBD').click(function(event){
        // Se el usuario que inicio sesion es de un estado
        if($('#edo_session').val() != 0) {
            // exportar informacion solo de su estado
            $('#edoExport> option[value="'+$('#edo_session').val()+'"]').attr('selected', 'selected');
            
            $('#formExportBD').submit();
        } else {
            // si es un usuario nacional, le permitimos seleccionar que estado 
            // esportará su información
            $("#winExportBD").dialog('open');
        }
        
        event.preventDefault();
        return false;
    });
    
});
</script>

<?PHP 
echo '<h2 align="center">REPORTES</h2>';

$objHTML = new HTML();
$objSelects = new Select();

$objHTML->startFieldset();

echo '
    <div class="img_reporte">
        <a href="?mod=repSeg"><h3>Registro y Seguimiento de Casos</h3>
        <img src="images/reg.png"></a></div>
    <div class="img_reporte">
        <a href="?mod=repMen"><h3>Informe Mensual de Actividades</h3>
        <img src="images/mes.png"></a></div>
    <div class="img_reporte">
        <a href="?mod=repDGEpi"><h3>Listado General ( Formato DGEpi )</h3>
        <img src="images/lis.png"></a></div>
<br />';

$objHTML->endFieldset();

echo '<br><br>';

$objHTML->startFieldset();

echo '
    <div class="img_reporte">
        <a href="?mod=edadGen"><h3>Gr&aacute;ficos</h3>
        <img src="images/genero.png"></a></div>
    <div class="img_reporte">
        <a href="content/exportBDtoExcel.php" target="_blank" id="btnExportarBD"><h3>Exportar BD a Excel</h3>
        <img src="images/informemens.png"></div>
        ';
 /*   <div class="img_reporte">
        <a href="?mod=locGr"><h3>Localidades</h3>
        <img src="images/localidades.png"></a></div>
    <div class="img_reporte">
        <a href="?mod=lesionGr"><h3>Tipo de Lesi&oacute;n</h3>
        <img src="images/lesiones.png"></a></div>*/
echo '<br />';

$objHTML->endFieldset();

echo '<br><br>';

$objHTML->startFieldset();

echo '<div class="img_reporte_row">
        <a href="?mod=map"><h3>Geoposicionamiento de Casos</h3>
        <img src="images/georeferencia.png"></a></div>
    <div class="img_reporte">';

$objHTML->endFieldset();

echo '<div id="winExportBD" title="Exportar Base de Datos a Excel">';

    $objHTML->startForm('formExportBD', 'content/exportBDtoExcel.php', 'POST');
        
        echo '<br>';
        $objHTML->inputHidden('edo_session', $_SESSION[EDO_USR_SESSION]);
        $objSelects->selectEstado('edoExport');
        echo '<br><br><div align="center">';
        $objHTML->inputSubmit('btnExportarBD', 'Exportar');
        echo '</div>';

    $objHTML->endFormOnly();
    
echo '</div>';

?>
