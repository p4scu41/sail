<script type="text/javascript">
    $(document).ready(function() {
        setupCalendario('fecha_inicio');
        setupCalendario('fecha_fin');
        
        $('#jurisdiccion option:first').text('Nivel Estatal');
        
        $('#jurisdiccion').change(function(){ 
            actualiza_select( { destino:'municipio', edo:'estado', juris:'jurisdiccion', tipo:'muni'} );
            reset_select('unidad');
        });

        $('#municipio').change(function(){ 
            actualiza_select( { destino:'unidad', edo:'estado', juris:'jurisdiccion', muni:'municipio', tipo:'uni'} );
        });
    
    });
</script>
<?PHP 

require_once('include/clases/Helpers.php');
require_once('include/clases/validacionSis.php');

echo '<h2 align="center">VALIDACI&Oacute;N PLATAFORMA SIS</h2>';

$objHTML = new HTML();
$objSelects = new Select();
$objHelp = new Helpers();

$objHTML->startForm('formReporte', '?mod=valSIS', 'POST');

    $objHTML->startFieldset();
    echo '<div align="center">';
            $objHTML->inputHidden('estado', $_SESSION[EDO_USR_SESSION]);
            $objSelects->selectJurisdiccion('jurisdiccion', $_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion']);
            $objSelects->selectMunicipio('municipio', $_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion'], $_POST['municipio']);
			$objSelects->selectUnidad('unidad', $_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion'], $_POST['municipio'], NULL, $_POST['unidad']);
			
            echo '<br>';
            $objHTML->label('Fecha: ');
            $objHTML->inputText('', 'fecha_inicio', $_POST['fecha_inicio'] ? $_POST['fecha_inicio'] : '01-'.date('m-Y'), array('placeholder'=>'Inicio'));
            $objHTML->inputText('', 'fecha_fin', $_POST['fecha_fin'] ? $_POST['fecha_fin'] : date("d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1)).'-'.date('m-Y'), array('placeholder'=>'Fin'));
            
            echo '<br><br>';
            $objHTML->inputSubmit('generarReporte', 'Generar Reporte');
    echo '</div>';
    $objHTML->endFieldset();
    
$objHTML->endFormOnly();


$objHTML->startFieldset();

    if(!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio'])){
        
        $estado = $objHelp->getNombreEstado($_SESSION[EDO_USR_SESSION]);
        
        $jurisdiccion = $objHelp->getNombreJurisdiccion($_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion']);
        $jurisdiccion = $jurisdiccion ? ', Jurisdicci&oacute;n: '.htmlentities($jurisdiccion) : '';
        
        $municipio = $objHelp->getNombreMunicipio($_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion'], $_POST['municipio']);
        $municipio = $municipio ? ', Municipio: '.htmlentities($municipio) : '';
                
        $unidad = $objHelp->getNombreUnidad($_POST['unidad']);
        $unidad = $unidad ? ', Unidad: '.htmlentities($unidad) : '';
        
        echo '<br><h3>Estado: '.$estado.' '.$jurisdiccion.' '.$municipio.' '.$unidad.'</h3><br>';
        
        $valSis = new validacionSIS();
        
        $valSis->idCatEstado = $_SESSION[EDO_USR_SESSION];
        $valSis->idCatJurisdiccion = $_POST['jurisdiccion'];
        $valSis->idCatMunicipio = $_POST['municipio'];
        $valSis->idCatUnidad = $_POST['unidad'];
        $valSis->fechaInicio = formatFechaObj($_POST['fecha_inicio'],'Y-m-d');
        $valSis->fechaFin = formatFechaObj($_POST['fecha_fin'],'Y-m-d');
        
        $valSis->calcular();
		$valSis->imprimir();
    }
    
$objHTML->endFieldset();
?>