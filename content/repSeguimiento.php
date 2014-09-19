<style>
.shell {
    width: 100% !important;
    margin: 0 auto;
}
</style>

<?PHP 
require_once('include/clases/ReporteTrimestral.php');
require_once('include/Select.class.php');

echo '<h2 align="center">REGISTRO Y SEGUIMIENTO DE CASOS</h2>';
$objSelects = new Select();
$objHTML = new HTML();
$catTipoPaciente = array( 0 => 'Todos',
                          1 => 'Prevalente Con Tratamiento',
                          2 => 'Prevalente Sin Tratamiento',
                          3 => 'Alta',
                          4 => 'Vigilancia Post Tratamiento' );

$objHTML->startForm('formReporte', '?mod=repSeg', 'POST');

    $objHTML->startFieldset();
    echo '<div align="left">';
        
        if($_SESSION[EDO_USR_SESSION] == 0)
			$objSelects->selectEstado('edoNac', $_SESSION[EDO_USR_SESSION] );
        
        $objHTML->inputSelect('Tipo Paciente: ', 'tipo_paciente', $catTipoPaciente, $_POST['tipo_paciente']);
        $objHTML->inputSubmit('generarReporte', ' Generar Reporte');
    echo '</div>';
    $objHTML->endFieldset();
    
$objHTML->endFormOnly();

echo '<br><div align="left">';
$objHTML->startForm('imprimeExcel', 'docs/creaExcel.php', 'post', array('target'=>'_blank'));
$objHTML->inputHidden('type', 'repSeg');
$objHTML->inputSubmit('downloadBtn', 'Descargar Excel');
$objHTML->endFormOnly();
echo '</div>';

$objHTML->startFieldset();
	/*echo '
		<table width="100%">
			<tr>
				<td width="100%" align="left">';
				$objHTML->startForm("exportaExcel", "?mod=repSeg", "post");
				$objHTML->inputSubmit("exportExcel", "Exportar a Excel");
				$objHTML->inputHidden("type", "repSeg");
				$objHTML->endFormOnly();
	echo '		</td>
			</tr>
		</table>';*/
    $reporteTrimestral = new ReporteTrimestral();
    if($_SESSION[EDO_USR_SESSION] == 0)
		$reporteTrimestral->idCatEstado = $_POST['edoNac'];
    else
		$reporteTrimestral->idCatEstado = $_SESSION[EDO_USR_SESSION];
    $reporteTrimestral->filtro = $_POST['tipo_paciente']; // REVISAR: Filtro que definira jaime
	$reporteTrimestral->generarReporte();
    //$reporteTrimestral->imprimirReporte();
    $reporteTrimestral->imprimirReporteUnitabla();

$objHTML->endFieldset();
?>
