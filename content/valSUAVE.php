<style>
.shell {
    width: 100% !important;
    margin: 0 auto;
}
</style>

<script type="text/javascript">
    $(document).ready(function() {
        setupCalendario('fecha_inicio');
        setupCalendario('fecha_fin');
        
        $('#jurisdiccion option:first').text('Estatal');
        
        $('#fecha_inicio').change(function(e) {
            getSemanaEpidemiologica('fecha_inicio','semana_inicio');
        });
        
        $('#fecha_fin').change(function(e) {
            getSemanaEpidemiologica('fecha_fin','semana_fin');
        });
        
         getSemanaEpidemiologica('fecha_inicio','semana_inicio');
         getSemanaEpidemiologica('fecha_fin','semana_fin');
    });
</script>
<?PHP 

require_once('include/clases/Helpers.php');
require_once('include/clases/validacionSuave.php');

echo '<h2 align="center">VALIDACI&Oacute;N PLATAFORMA SUAVE</h2>';

$objHTML = new HTML();
$objSelects = new Select();
$objHelp = new Helpers();

$objHTML->startForm('formReporte', '?mod=valSUAVE', 'POST');

    $objHTML->startFieldset();
    echo '<div align="center">';
            $objSelects->selectJurisdiccion('jurisdiccion', $_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion']);
            
            $objHTML->label('Fecha: ');
            $objHTML->inputText('', 'fecha_inicio', $_POST['fecha_inicio'] ? $_POST['fecha_inicio'] : '01-'.date('m-Y'), array('placeholder'=>'Inicio'));
            $objHTML->inputText('', 'semana_inicio', $_POST['semana_inicio'] ? $_POST['semana_inicio'] : '', 
                                    array('placeholder'=>'Semana', 'readonly'=>'true', 'size'=>'4', 'style'=>'text-align:center;', 'title'=>'Semana Epidemiologica', 'alt'=>'Semana Epidemiologica'));
            $objHTML->inputText('', 'fecha_fin', $_POST['fecha_fin'] ? $_POST['fecha_fin'] : date("d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1)).'-'.date('m-Y'), array('placeholder'=>'Fin'));
            $objHTML->inputText('', 'semana_fin', $_POST['semana_fin'] ? $_POST['semana_fin'] : '', 
                                    array('placeholder'=>'Semana', 'readonly'=>'true', 'size'=>'4', 'style'=>'text-align:center;', 'title'=>'Semana Epidemiologica', 'alt'=>'Semana Epidemiologica'));
            
            echo '<br><br>';
            $objHTML->inputSubmit('generarReporte', 'Generar Reporte');
    echo '</div>';
    $objHTML->endFieldset();
    
$objHTML->endFormOnly();


$objHTML->startFieldset();

    if(!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio'])){
        
        $valSua = new validacionSUAVE();
        
        $valSua->idCatEstado = $_SESSION[EDO_USR_SESSION];
        $valSua->idCatJurisdiccion = $_POST['jurisdiccion'];
        $valSua->fechaInicio = formatFechaObj($_POST['fecha_inicio'],'Y-m-d');
        $valSua->fechaFin = formatFechaObj($_POST['fecha_fin'],'Y-m-d');
        
        $valSua->calcular();
		$valSua->imprimir();
    }
    
$objHTML->endFieldset();
?>