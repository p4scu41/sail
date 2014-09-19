<h2 align="center">SOLICITUD DE ESTUDIO AL LABORATORIO</h2>

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

	$('#datos_solicitud').hide();
	
	$("#datosResultadoLaboratorio").dialog({
		autoOpen: false,
        height: 720,
		width: 680,
		modal: true,
		resizable: true
	});

	setValidacion('frmSolicitudEstudio');
	
	setupCalendario("fecha_toma");
	setupCalendario("fecha_solicitud");
    
    camposSolicitud = new Array('clave_expediente',
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
                'tiempoEvolucion',
                'otros_padecimientos',
                'topografia',
                'segAfeCab',
                'segAfeTro',
                'segAfeMSD',
                'segAfeMSI',
                'segAfeMID',
                'segAfeMII',
                'morfoLesiones',
                'topo_morfo_lesiones',
                'ultimaBacilo',
                'tratamiento',
                'observaciones'
            );
    
    for(campo in camposSolicitud) {
        $('*[name='+camposSolicitud[campo]+']').each(function(){
            $(this).attr('disabled',true);
        });
    }
    
    if(getQuerystring('saved') == 'true') {
        jAlert('<img src="images/ok.gif" > <strong>Datos guardados exitosamente</strong>', 'Datos guardados correctamente');
    }
});

function showResultLab(tipo, id) {
	url = '';

	if(tipo == 'histo') {
		url = 'content/estudioHisto.php';
	}
	if(tipo == 'bacilos') {
		url = 'content/estudioBacilo.php';
	}
	
	$.ajax({
		type: "POST",
		url: url,
		data: 'id='+id,
		success: function(datos)
		{
			$('#datosResultadoLaboratorio').html(datos);
			$("#datosResultadoLaboratorio").dialog('open');
			// funcion de workless
			$("#datosResultadoLaboratorio select, #datosResultadoLaboratorio input:checkbox, #datosResultadoLaboratorio input:radio").uniform();
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
				'o Notifiquelo con el administrador', 'Error al procesar los datos...');
		}
	});
}

function agregarEstudio(tipo) {
	if(tipo == 'histo') {
        $('#datosClinicosHisto').show();
        $('#datosMuestraHisto').show();
        $('#datosClinicosBacilo').hide();
		$('#baciloscopico').val(0);
		$('#histopatologico').val(1);
		$('#titulo_solicitud').html('Solicitud de estudio Histopatol&oacute;gico');
	}
    
	if(tipo == 'bacilos') {
        $('#datosClinicosHisto').hide();
        $('#datosMuestraHisto').hide();
        $('#datosClinicosBacilo').show();
		$('#baciloscopico').val(1);
		$('#histopatologico').val(0);
		$('#titulo_solicitud').html('Solicitud de estudio Bacilosc&oacute;pico');
	}
    
	$('#datos_solicitud').show();
	$('#guardaSolicitud').show();
}

function imprimirResultLab(tipo, id) {
	if(tipo == 'histo') {
         $('#formPrintPDF').attr('action','content/pdf/imprimibles/respuestaHis.php?idEstudioHis='+id);
	}
	if(tipo == 'bacilos') {
		$('#formPrintPDF').attr('action','content/pdf/imprimibles/respuestaBac.php?idEstudioBac='+id);
	}
    $('#formPrintPDF').submit();
}

function imprimirSolicitudLab(tipo, id) {
	if(tipo == 'histo') {
        $('#formPrintPDF').attr('action','content/pdf/imprimibles/solicitudHis.php?idEstudioHis='+id);
	}
	if(tipo == 'bacilos') {
		$('#formPrintPDF').attr('action','content/pdf/imprimibles/solicitudBac.php?idEstudioBac='+id);
	}
    $('#formPrintPDF').submit();
}
</script>

<form name="formPrintPDF" id="formPrintPDF" action="#" target="_blank" method="POST"></form>

<?PHP
//echo '<pre>'.print_r($_POST,true).'</pre>';

require_once('include/clasesLepra.php');
require_once('include/fecha_hora.php');

$objHTML = new HTML();
$objSelects = new Select();
$listado = new ListGeneric();
$help = new Helpers();
$paciente = NULL;
$diagnostico = NULL;
$sospechoso = new Sospechoso();
$infUni = NULL;
$query = '';

// Obtener datos del Pacientes
if(!empty($_GET['id'])){
	$paciente = new Paciente();
	$paciente->obtenerBD($_GET['id']);
	
	
	if(empty($paciente->idPaciente))
		echo msj_error('Paciente no encontrado');
	else {
        // Paciente Sospechoso(5) o Descartado(6), no tienen un diagnostico asociado
        if($paciente->idCatTipoPaciente!=5 && $paciente->idCatTipoPaciente!=6){
            $diagnostico = new Diagnostico();
            $diagnostico->obtenerBD($diagnostico->obtieneIdDiagnostico($paciente->idPaciente));
            $diagnostico->cargarArreglosDiagnosticoContactos();
        } else {
            $sospechoso->obtenerBD($paciente->idPaciente);
            $diagnostico = $sospechoso;
        }
	
        $infUni = $help->getDatosUnidad($paciente->idCatUnidadTratante);
	}
}
else
	echo msj_error('Paciente no encontrado');


// Guardar la solicitud en la BD
if(!empty($_GET['id']) && !empty($_POST['paciente'])){
    // Los datos de estado y jurisdiccion de la unidad tratante estan en $infUni
	if($_POST['baciloscopico'] == 1) {
		$solicitudBacilos = new EstudioBac();
        
        if(empty($_POST['diagnostico'])) {
            $solicitudBacilos->idDiagnostico = 0;
            $solicitudBacilos->idPaciente = $_POST['paciente'];
        }
        else {
            $solicitudBacilos->idDiagnostico = $_POST['diagnostico'];
        }
        
		if($_POST['contacto'] != '') $solicitudBacilos->idContacto = $_POST['contacto'];
		$solicitudBacilos->fechaSolicitud = date('Y-m-d');
		$solicitudBacilos->folioLaboratorio = $_POST['folio_laboratorio'];
		$solicitudBacilos->idCatSolicitante = $_POST['uniTratado'];
		$solicitudBacilos->idCatTipoEstudio = $_POST['tipoEstudio'];
		$solicitudBacilos->lesionTomoMuestra = $_POST['lesion_muestra'];
		$solicitudBacilos->regionTomoMuestra = $_POST['region_muestra'];
		$solicitudBacilos->fechaTomaMuestra = formatFecha($_POST['fecha_toma']);
		$solicitudBacilos->personaTomaMuestra = $_POST['tomo_muestra'];
		$solicitudBacilos->fechaSolicitudEstudio = formatFecha($_POST['fecha_solicitud']);
		$solicitudBacilos->personaSolicitudEstudio = $_POST['solicita_estudio'];
        $solicitudBacilos->tomMueFrotis1 = $_POST['tomMueFrotis1'];
        $solicitudBacilos->tomMueFrotis2 = $_POST['tomMueFrotis2'];
        $solicitudBacilos->tomMueFrotis3 = $_POST['tomMueFrotis3'];
        $solicitudBacilos->idCatEstadoTratante = $infUni['idCatEstado'];
        $solicitudBacilos->IdCatJurisdiccionTratante = $infUni['idCatJurisdiccion'];
		
		$solicitudBacilos->insertarBD();
		
		if($solicitudBacilos->error){
			echo msj_error('Ocurri&ocute; un ERROR al guardar los datos');
			echo $solicitudBacilos->msgError;
		}
		else 
			redirect('?mod=lab&id='.$_GET['id'].'&saved=true');//echo msj_ok('Datos Guardados Exitosamente!!!');
	}
	
	if($_POST['histopatologico'] == 1) {
		
		$solicitudHisto = new EstudioHis();
		
        if(empty($_POST['diagnostico'])) {
            $solicitudHisto->idDiagnostico = 0;
            $solicitudHisto->idPaciente = $_POST['paciente'];
        }
        else {
            $solicitudHisto->idDiagnostico = $_POST['diagnostico'];
        }
        
		if($_POST['contacto'] != '') $solicitudHisto->idContacto = $_POST['contacto'];
		$solicitudHisto->fechaSolicitud = date('Y-m-d');
		$solicitudHisto->folioLaboratorio = $_POST['folio_laboratorio'];
		$solicitudHisto->idCatSolicitante = $_POST['uniTratado'];
		$solicitudHisto->idCatTipoEstudio = $_POST['tipoEstudio'];
		$solicitudHisto->lesionTomoMuestra = $_POST['lesion_muestra'];
		$solicitudHisto->regionTomoMuestra = $_POST['region_muestra'];
		$solicitudHisto->fechaTomaMuestra = formatFecha($_POST['fecha_toma']);
		$solicitudHisto->personaTomaMuestra = $_POST['tomo_muestra'];
		$solicitudHisto->fechaSolicitudEstudio = formatFecha($_POST['fecha_solicitud']);
		$solicitudHisto->personaSolicitudEstudio = $_POST['solicita_estudio'];
        $solicitudHisto->idCatEstadoTratante = $infUni['idCatEstado'];
        $solicitudHisto->IdCatJurisdiccionTratante = $infUni['idCatJurisdiccion'];
		
		$solicitudHisto->insertarBD();
		
		if($solicitudHisto->error){
			echo msj_error('Ocurri&oacute; un ERROR al guardar los datos');
			echo $solicitudHisto->msgError;
		}
		else 
			redirect('?mod=lab&id='.$_GET['id'].'&saved=true');//echo msj_ok('Datos Guardados Exitosamente!!!');
	}
}


$objHTML->startFieldset('Solicitudes Pendientes');
if(!empty($_GET['id'])){
	$listado->getPendientesBacPaciente($_GET['id']);
	$listado->getPendientesHisPaciente($_GET['id']);
}
echo '<div class="datagrid">
		<table>
		<thead>
		<tr align="center">
			<th>Folio Solicitud</th>	
			<th>Clave</th>
			<th>Nombre</th>
			<th>Solicitante</th>
			<th>Fecha Muestreo</th>
			<th>Fecha Solicitud</th>
			<th>Tipo</th>
			<th>Estudio</th>
			<th>Ver</th>
            <th>Imprimir</th>
		</tr>
		</thead>
		<tbody>';

	foreach($listado->arrEstudiosBac as $pendienteBac){
		echo '<tr>
			<td align="center">'.$pendienteBac->folioSolicitud.'</td>	
			<td>'.( !empty($pendienteBac->idContacto )
				? 'Estudio de Contacto'
				: $help->getClavePaciente(
                        $pendienteBac->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($pendienteBac->idDiagnostico)
                        : $pendienteBac->idPaciente
                  )
			).'</td>	
			<td>'.( !empty($pendienteBac->idContacto )
				? $help->getNombreContacto($pendienteBac->idContacto)
				: $help->getNamePaciente(
                        $pendienteBac->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($pendienteBac->idDiagnostico)
                        : $pendienteBac->idPaciente
                  )
			).'</td>
			<td>'.$pendienteBac->idCatSolicitante.' '.$help->getNameUnidad($pendienteBac->idCatSolicitante).'</td>
			<td>'.formatFechaObj($pendienteBac->fechaTomaMuestra).'</td>
			<td>'.formatFechaObj($pendienteBac->fechaSolicitudEstudio).'</td>
			<td>'.htmlentities($help->getDescripTipoEstudio($pendienteBac->idCatTipoEstudio)).'</td>
			<td>Bacilosc&oacute;pia</td>
			<td align="center"><a href="javascript:showResultLab(\'bacilos\','.$pendienteBac->idEstudioBac.')"><img src="images/verLab.gif" border="0"/></a></td>
            <td align="center"><a href="javascript:imprimirSolicitudLab(\'bacilos\','.$pendienteBac->idEstudioBac.')"><img src="images/imprimir.jpg" border="0"/></a></td>
		</tr>';
	}
	
	foreach($listado->arrEstudiosHis as $pendienteHis){
		echo '<tr>
			<td align="center">'.$pendienteHis->folioSolicitud.'</td>	
			<td>'.( $pendienteHis->idContacto 
				? 'Estudio de Contacto'
				: $help->getClavePaciente(
                        $pendienteHis->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($pendienteHis->idDiagnostico)
                        : $pendienteHis->idPaciente
                  )
			).'</td>	
			<td>'.( $pendienteHis->idContacto 
				? $help->getNombreContacto($pendienteHis->idContacto)
				: $help->getNamePaciente(
                        $pendienteHis->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($pendienteHis->idDiagnostico)
                        : $pendienteHis->idPaciente
                  )
			).'</td>
			<td>'.$pendienteHis->idCatSolicitante.' '.$help->getNameUnidad($pendienteHis->idCatSolicitante).'</td>
			<td>'.formatFechaObj($pendienteHis->fechaTomaMuestra).'</td>
			<td>'.formatFechaObj($pendienteHis->fechaSolicitudEstudio).'</td>
			<td>'.htmlentities($help->getDescripTipoEstudio($pendienteHis->idCatTipoEstudio)).'</td>
			<td>Histopatol&oacute;gia</td>
			<td align="center"><a href="javascript:showResultLab(\'histo\','.$pendienteHis->idEstudioHis.')"><img src="images/verLab.gif" border="0"/></a></td>
            <td align="center"><a href="javascript:imprimirSolicitudLab(\'histo\','.$pendienteHis->idEstudioHis.')"><img src="images/imprimir.jpg" border="0"/></a></td>
		</tr>';
	}

echo '</tbdy></table></div>';
$objHTML->endFieldset();


$objHTML->startFieldset('Solicitudes Procesadas');
if(!empty($_GET['id'])){
	$listado->getProcesadosBacPaciente($_GET['id']);
	$listado->getProcesadosHisPaciente($_GET['id']);
}
echo '<div class="datagrid">
		<table>
		<thead>
		<tr align="center">
			<th>Clave LESP</th>
			<th>Persona</th>
			<th>Nombre</th>
			<th>Fecha Muestreo</th>
			<th>Fecha Recepci&oacute;n</th>
			<th>Fecha Resultado</th>
			<th>Diagn&oacute;stico</th>
			<th>Tipo</th>
			<th>Estudio</th>
			<th>Ver</th>
			<th>Imprimir</th>
		</tr>
		</thead>
		<tbody>';

	foreach($listado->arrEstudiosBac as $procesadoBac){
		echo '<tr>
			<td align="center">'.$procesadoBac->folioLaboratorio.'</td>
			<td>'.( $procesadoBac->idContacto 
				? 'Contacto'
				: 'Paciente'
			).'</td>
			<td>'.( $procesadoBac->idContacto 
				? $help->getNombreContacto($procesadoBac->idContacto)
				: $help->getNamePaciente(
                        $procesadoBac->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($procesadoBac->idDiagnostico)
                        : $procesadoBac->idPaciente
                  )
			).'</td>
			<td>'.formatFechaObj($procesadoBac->fechaTomaMuestra).'</td>
			<td>'.formatFechaObj($procesadoBac->fechaRecepcion).'</td>
			<td>'.formatFechaObj($procesadoBac->fechaResultado ? $procesadoBac->fechaResultado : $procesadoBac->fechaRecepcion).'</td>
			<td>'.($procesadoBac->muestraRechazada ? 'Muestra Rechazada' : ('IB: '.$help->getDescripBaciloscopia($procesadoBac->idCatBac)).' &nbsp; IM: '.$procesadoBac->bacIM).'</td>
			<td>'.htmlentities($help->getDescripTipoEstudio($procesadoBac->idCatTipoEstudio)).'</td>
			<td>Bacilosc&oacute;pia</td>
			<td align="center"><a href="javascript:showResultLab(\'bacilos\','.$procesadoBac->idEstudioBac.')"><img src="images/verLab.gif" border="0"/></a></td>
			<td align="center"><a href="javascript:imprimirResultLab(\'bacilos\','.$procesadoBac->idEstudioBac.')"><img src="images/imprimir.jpg" border="0"/></a></td>
		</tr>';
	}
	
	foreach($listado->arrEstudiosHis as $procesadoHis){
		echo '<tr>
			<td align="center">'.$procesadoHis->folioLaboratorio.'</td>
			<td>'.( $procesadoHis->idContacto 
				? 'Contacto'
				: 'Paciente'
			).'</td>
			<td>'.( $procesadoHis->idContacto 
				? $help->getNombreContacto($procesadoHis->idContacto)
				: $help->getNamePaciente(
                        $procesadoHis->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($procesadoHis->idDiagnostico)
                        : $procesadoHis->idPaciente
                  )
			).'</td>
			<td>'.formatFechaObj($procesadoHis->fechaTomaMuestra).'</td>
			<td>'.formatFechaObj($procesadoHis->fechaRecepcion).'</td>
			<td>'.formatFechaObj($procesadoHis->fechaResultado ? $procesadoHis->fechaResultado : $procesadoHis->fechaRecepcion).'</td>
			<td>'.($procesadoHis->muestraRechazada ? 'Muestra Rechazada' : $help->getDescripcionHistopatologia($procesadoHis->idCatHisto)).'</td>
			<td>'.htmlentities($help->getDescripTipoEstudio($procesadoHis->idCatTipoEstudio)).'</td>
			<td>Histopatol&oacute;gia</td>
			<td align="center"><a href="javascript:showResultLab(\'histo\','.$procesadoHis->idEstudioHis.')"><img src="images/verLab.gif" border="0"/></a></td>
				<td align="center"><a href="javascript:imprimirResultLab(\'histo\','.$procesadoHis->idEstudioHis.')"><img src="images/imprimir.jpg" border="0"/></a></td>
		</tr>';
	}

echo '</tbdy></table></div>';
$objHTML->endFieldset();


echo '<br /><div align="center">';
$objHTML->inputButton('agregaHisto', 'Agregar solicitud de estudio Histopatol&oacute;gico', array('onClick'=>'agregarEstudio(\'histo\')'));
$objHTML->inputButton('agregaBacilos', 'Agregar solicitud de estudio Bacilosc&oacute;pico', array('onClick'=>'agregarEstudio(\'bacilos\')'));
echo '</div><br /><br />';


$objHTML->startForm('frmSolicitudEstudio', '?mod=lab&id='.$_GET['id'], 'POST');

echo '<h2 align="center" id="titulo_solicitud"></h2>';
echo '<div id="datos_solicitud">';
	include_once 'content/solicitudEstudio.php';
echo '</div>';

$objHTML->inputHidden('baciloscopico', 0);
$objHTML->inputHidden('histopatologico', 0);
$objHTML->inputHidden('diagnostico', $diagnostico->idDiagnostico);
$objHTML->inputHidden('paciente', $paciente->idPaciente);

echo '<div align="center">';
$objHTML->inputSubmit('guardaSolicitud', 'Guardar Solicitud', array('style'=>'display:none'));
echo '</div><br />';

$objHTML->endFormOnly();

echo '<div id="datosResultadoLaboratorio" title="Resultado del Laboratorio"></div>';
?>