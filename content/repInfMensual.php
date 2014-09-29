<script type="text/javascript">
$(document).ready(function() {
    setupCalendario('fecha_inicio');
    setupCalendario('fecha_fin');

    $('#jurisdiccion option:first').text('Nivel Estatal');
    $('#jurisdiccion option:first').val('0');

    $('#estado [value=0]').remove();
});
</script>
<?PHP 
require_once('include/clases/ReporteActividadesMensual.php');

echo '<h2 align="center">INFORME MENSUAL DE ACTIVIDADES</h2>';

$objHTML = new HTML();
$objSelects = new Select();

$objHTML->startForm('formReporte', '?mod=repMen', 'POST');

    $objHTML->startFieldset();
    echo '<div align="center">';
        if($_SESSION[EDO_USR_SESSION] == 0)
			$objSelects->selectEstado('estado', $_POST['estado'] ? $_POST['estado'] : $_SESSION[EDO_USR_SESSION], array('required'=>'required'));
        else 
            $objSelects->selectJurisdiccion('jurisdiccion', $_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion']);
        $objHTML->label('Fecha: ');
        $objHTML->inputText('', 'fecha_inicio', $_POST['fecha_inicio'] ? $_POST['fecha_inicio'] : '01-'.date('m-Y'), array('placeholder'=>'Inicio'));
        $objHTML->inputText('', 'fecha_fin', $_POST['fecha_fin'] ? $_POST['fecha_fin'] : date("d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1)).'-'.date('m-Y'), array('placeholder'=>'Fin'));
        echo ' &nbsp; &nbsp; &nbsp; ';
        $objHTML->inputSubmit('generarReporte', 'Generar Reporte');
    echo '</div>';
    $objHTML->endFieldset();
    
$objHTML->endFormOnly();

$objHTML->startFieldset();

    if(!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio'])){
        $reporteActividadesMensual = new ReporteActividadesMensual();
        
        if(!empty($_POST['estado']))
            $reporteActividadesMensual->idCatEstado = $_POST['estado'];
        else
            $reporteActividadesMensual->idCatEstado = $_SESSION[EDO_USR_SESSION];
        
        if(!empty($_POST['jurisdiccion']))
            $reporteActividadesMensual->idCatJurisdiccionLaboratorio = $_POST['jurisdiccion'];
        else
            $reporteActividadesMensual->idCatJurisdiccionLaboratorio = 0;
        
        $reporteActividadesMensual->fechaInicio = formatFechaObj($_POST['fecha_inicio'], 'Y-m-d');
        $reporteActividadesMensual->fechaFin = formatFechaObj($_POST['fecha_fin'], 'Y-m-d');
        $reporteActividadesMensual->generarReporte();
        $reporteActividadesMensual->imprimirReporte();
    }
    
$objHTML->endFieldset();
?>