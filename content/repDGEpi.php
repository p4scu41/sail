<style>
.shell {
    width: 100% !important;
    margin: 0 auto;
}
</style>

<?PHP 
require_once('include/clases/Helpers.php');
require_once('include/clases/ReporteListadoGeneral.php');

echo '<h2 align="center">LISTADO GENERAL ( FORMATO DGEpi )</h2>';

$objHTML = new HTML();

echo '<div style="float:left;">';
$objHTML->startForm('imprimeExcel', 'docs/creaExcel.php', 'post', array('target'=>'_blank'));
$objHTML->inputHidden('type', 'repDGEpi');
$objHTML->inputSubmit('downloadBtn', 'Descargar Excel');
$objHTML->endFormOnly();
echo '</div>';

$objHTML->startFieldset();

    $reporteListadoGeneral = new ReporteListadoGeneral();
    $reporteListadoGeneral->idCatEstado = $_SESSION[EDO_USR_SESSION];
	//echo $_SESSION[EDO_USR_SESSION];
	//echo $reporteListadoGeneral->idCatEstado;
	$reporteListadoGeneral->generarReporte();
	$reporteListadoGeneral->imprimirReporte();

$objHTML->endFieldset();
?>