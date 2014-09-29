<style>
.shell {
    width: 100% !important;
    margin: 0 auto;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('#estado [value=0]').remove();
    
    $('#downloadBtn').click(function(){
        $('#imprimeExcel #estado').remove();
        estado = $('#estado').val();
        $('#imprimeExcel').append($('<input type="hidden">').attr('name','estado').attr('id','estado').val(estado));
        $('#imprimeExcel').submit();
    });
});
</script>
<?PHP 
require_once('include/clases/Helpers.php');
require_once('include/clases/ReporteListadoGeneral.php');

echo '<h2 align="center">LISTADO GENERAL ( FORMATO DGEpi )</h2>';

$objHTML = new HTML();
$objSelects = new Select();

if($_SESSION[EDO_USR_SESSION] == 0) {
    $objHTML->startForm('formReporte', '?mod=repDGEpi', 'POST', array('style'=>'display:inline;'));
    $objSelects->selectEstado('estado', $_POST['estado'] ? $_POST['estado'] : $_SESSION[EDO_USR_SESSION], array('required'=>'required'));
    $objHTML->inputSubmit('generarReporte', 'Generar Reporte');
    $objHTML->endFormOnly();
}

echo '<div align="left" style="display:inline">';
    $objHTML->startForm('imprimeExcel', 'docs/creaExcel.php', 'post', array('target'=>'_blank', 'style'=>'display:inline;'));
    $objHTML->inputHidden('type', 'repDGEpi');
    $objHTML->inputHidden('export', 'xls');
    $objHTML->inputButton('downloadBtn', 'Descargar Excel');
    $objHTML->endFormOnly();
echo '</div>';

$objHTML->startFieldset();

    $reporteListadoGeneral = new ReporteListadoGeneral();
    if($_SESSION[EDO_USR_SESSION] == 0 && !empty($_POST['estado']))
		$reporteListadoGeneral->idCatEstado = $_POST['estado'];
    else 
        $reporteListadoGeneral->idCatEstado = $_SESSION[EDO_USR_SESSION];
	
    if(!empty($reporteListadoGeneral->idCatEstado)){
        $reporteListadoGeneral->generarReporte();
        $reporteListadoGeneral->imprimirReporte();
    }
$objHTML->endFieldset();
?>