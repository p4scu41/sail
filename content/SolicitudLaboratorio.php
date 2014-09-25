<h2 align="center">INFORME DE RESULTADOS 
<?php 
switch ($_GET['tipo']) {
	case 'bacilos':
	 	echo 'BACTERIOL&Oacute;GICO';
	break;
	case 'histo':
		echo 'HISTOPATOL&Oacute;GICO';
	break;
}
?>
</h2>

<script type="text/javascript">
$(document).ready(function() {
	camposSolicitud = new Array('clave_expediente',
				//'folio_laboratorio',
                'fecha_recepcion_histo',
                'fecha_recepcion_bacilos',
				'nombre',
				'edad',
				'sexo',
				'calle',
				'num_externo',
				'num_interno',
				'colonia',
				'edoDomicilio',
				'muniDomicilio',
				'localiDomicilio',
				'uniTratado',
				'institucion_caso',
				'edoCaso',
				'jurisCaso',
				'muniCaso',
				'clasficicacion',
				'tipoEstudio',
				'lesion_muestra',
				'region_muestra',
				'tomo_muestra',
				'fecha_toma',
				'solicita_estudio',
				'fecha_solicitud',
				'contacto',
				'cve_lesp_histo',
				'cve_lesp_bacilos',
                'tiempoEvolucion',
                'otros_padecimientos',
                'topografia',
                'segAfeCab',
                'segAfeTro',
                'segAfeMSD',
                'segAfeMSI',
                'segAfeMID',
                'segAfeMII',
                'tomMueFrotis1',
                'tomMueFrotis2',
                'tomMueFrotis3',
                'morfoLesiones',
                'topo_morfo_lesiones',
                'ultimaBacilo',
                'tratamiento',
                'observaciones');

	for(campo in camposSolicitud) {
        $('*[name='+camposSolicitud[campo]+']').each(function(){
            $(this).attr('disabled',true);
        });
    }
    
    if(getQuerystring('tipo')=='bacilos') {
        $('#datosClinicosHisto').hide();
        $('#datosMuestraHisto').hide();
        $('#datosClinicosBacilo').show();
        //$('#btnCal-fecha_recepcion_bacilos').hide();
    }
    
    if(getQuerystring('tipo')=='histo') {
        $('#datosClinicosHisto').show();
        $('#datosMuestraHisto').show();
        $('#datosClinicosBacilo').hide();
        //$('#btnCal-fecha_recepcion_histo').hide();
    }
});
</script>
<?php
require_once('include/clasesLepra.php');
require_once('include/fecha_hora.php');
require_once('include/enviaCorreo.php');
require_once('include/commandSMS.php');
require_once('include/clases/controlCalidad.php');

if(count($_POST) != 0)
{
	require_once('content/guardaResultadoLaboratorio.php');
}

$includeEstudio = '';
$estudio = null;
$objHTML = new HTML();
$objSelects = new Select();
$paciente = new Paciente();
$diagnostico = new Diagnostico();
$sospechoso = new Sospechoso();
$infUni = NULL;
$help = new Helpers();
$resultadoGuardado = false;
$objCalidad = new controlCalidad();

switch ($_GET['tipo']) {
	case 'bacilos':
		$includeEstudio = 'content/estudioBacilo.php';
		$estudio = new EstudioBac();
		$estudio->obtenerBD($_GET['id']);
        
		if(empty($estudio->idEstudioBac))
			echo msj_error('Solicitud no encontrada');
		else {
            // Paciente Sospechoso(5) o Descartado(6), no tienen un diagnostico asociado
            if($estudio->idPaciente){
                $paciente->obtenerBD( $estudio->idPaciente );
                $sospechoso->obtenerBD($estudio->idPaciente);
                $diagnostico = $sospechoso;
            } else {
                $paciente->obtenerBD( $help->getIdPacienteFromDiagnostico($estudio->idDiagnostico) );
                $diagnostico->obtenerBD($estudio->idDiagnostico); 
            }
            
            $objCalidad->obtenerByBacilos($_GET['id']);
		}
	break;
	
	case 'histo':
		$includeEstudio = 'content/estudioHisto.php';
		$estudio = new EstudioHis();
		$estudio->obtenerBD($_GET['id']);
        //var_dump($estudio);
		if(empty($estudio->idEstudioHis))
			echo msj_error('Solicitud no encontrada');
		else {
			// Paciente Sospechoso(5) o Descartado(6), no tienen un diagnostico asociado
            if($estudio->idPaciente){
                $paciente->obtenerBD( $estudio->idPaciente );
                $sospechoso->obtenerBD($estudio->idPaciente);
                $diagnostico = $sospechoso;
            } else {
                $paciente->obtenerBD( $help->getIdPacienteFromDiagnostico($estudio->idDiagnostico) );
                $diagnostico->obtenerBD($estudio->idDiagnostico); 
            }
            
            $objCalidad->obtenerByHisto($_GET['id']);
		}
	break;
	
	default:
		echo 'ERROR: Tipo no especificado';
	break;
}

if(empty($paciente->idPaciente))
	echo msj_error('Paciente no encontrado');

$infUni = devuelveRowAssoc(ejecutaQuery('SELECT [catUnidad].[idCatEstado],[catMunicipio].[idCatJurisdiccion],[catUnidad].[idCatMunicipio] 
			FROM [catUnidad],[catMunicipio] 
			WHERE [catMunicipio].[idCatEstado] = [catUnidad].[idCatEstado] AND 
				[catMunicipio].[idCatMunicipio] = [catUnidad].[idCatMunicipio] AND 
				[idCatUnidad]=\''.$paciente->idCatUnidadTratante.'\''));

include('content/solicitudEstudio.php');

$objHTML->startForm('frmResultadoEstudio', ( ($_SESSION[TIPO_USR_SESSION] != 5) ? '?'.$_SERVER['QUERY_STRING'] : '#' ), 'POST');

include($includeEstudio);

if($objCalidad->idcontrolCalidad) {
    echo "<style type='text/css'>div.selector span { max-width: none !important; }</style>
    <script type='text/javascript'>
    $(document).ready(function() {
		deshabilitarCamposCalidad = new Array(
                'calidadMuestra',
                'sinMuestra',
                'sinElemeCelu',
                'abunEritro',
                'otrosCalidadMuestra',
                'calidadFrotis',
                'calidadFrotisTipo',
                'otrosCalidadFrotis',
                'calidadTincion',
                'crisFucsi',
                'preciFucsi',
                'calenExce',
                'decoInsufi',
                'otrosCalidadTincion',
                'calidadLectura',
                'falPosi',
                'falNega',
                'difMas2IB',
                'difMas25IM',
                'otrosCalidadLectura',
                'calidadResultado',
                'soloSimbCruz',
                'soloPosiNega',
                'noEmiteIM',
                'otrosCalidadResultado',
                'recomendacion');
	
		for(campo in deshabilitarCamposCalidad) {
			$('*[name='+deshabilitarCamposCalidad[campo]+']').each(function(){
                $(this).attr('disabled',true);
            });
		}
    });
    </script>";
    include_once('content/controlCalidadMuestra.php');
}

echo '<div align="center">';

// Ocultar los botones de guardar para el usuario de control de calidad
if($_SESSION[TIPO_USR_SESSION] != 5) {
    switch ($_GET['tipo']) {
        case 'bacilos':
        $objHTML->inputSubmit('guarda_resultado_bacilos', 'Guardar Resultado Bacteriol&oacute;gico');
        $objHTML->inputHidden('guarda_resultado_bacilos', 1);
        break;
        case 'histo':
        $objHTML->inputSubmit('guarda_resultado_histo', 'Guardar Resultado Histopatol&oacute;gico');
        $objHTML->inputHidden('guarda_resultado_histo', 1);
        break;
    }
} else {
    echo "<script type='text/javascript'>
    $(document).ready(function() {
		deshabilitarCamposLaboratorio = new Array(";
        
    switch ($_GET['tipo']) {
        case 'bacilos':
            echo "'cve_lesp_bacilos',
                'fecha_recepcion_bacilos',
                'rechazo_muestra_bacilos',
                'criterio_rechazo_bacilos',
                'otro_criterio_rechazo_bacilos',
                'fecha_resultado_bacilos',
                'ind_baci_ft1',
                'ind_baci_ft2',
                'ind_baci_ft3',
                'bacilos_ft1',
                'bacilos_ft2',
                'bacilos_ft3',
                'calidad_muestra_ft1',
                'calidad_muestra_ft2',
                'calidad_muestra_ft3',
                'tipo_bacilo_ft1',
                'tipo_bacilo_ft2',
                'tipo_bacilo_ft3',
                'ib_promedio',
                'im_promedio',
                'obser_bacilos',
                'edoLab',
                'jurisLab',
                'analista',
                'supervisor'";
        break;
        case 'histo':
            echo "'cve_lesp_histo',
                'fecha_recepcion_histo',
                'rechazo_muestra_histo',
                'criterio_rechazo_histo',
                'otro_criterio_rechazo_histo',
                'fecha_resultado_histo',
                'macroscopica',
                'microscopica',
                'resultado_histo',
                'tipo_resultado',
                'edoLab',
                'jurisLab',
                'analista',
                'supervisor'";
        break;
    }
        echo ");
	
		for(campo in deshabilitarCamposLaboratorio) {
			$('*[name='+deshabilitarCamposLaboratorio[campo]+']').each(function(){
                $(this).attr('disabled',true);
            });
		}";

    switch ($_GET['tipo']) {
        case 'bacilos':
            echo '$("#btnCal-fecha_resultado_bacilos").remove();';
        break;
        case 'histo':
            echo '$("#btnCal-fecha_resultado_histo").remove();';
        break;
    }

    echo "});
    </script>";
}
echo '</div><br />';

$objHTML->endFormOnly();

?>