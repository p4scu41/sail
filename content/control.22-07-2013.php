<h2 align="center">TARJETA DE REGISTRO Y CONTROL DE LEPRA</h2>

<style>
.shell {
    width: 100% !important;
    margin: 0 auto;
}
</style>

<script type="text/javascript">
    var noControles=0;
    var registroControl = '';
    
	function guardaControl(){
		boton = this;
		$registro = $(this).parent().parent();
		var valores = new Array();
		i = 0;
		
		$(boton).attr('disabled',true);
		
		$registro.find('input, select').each(function(){
			campo = $(this).attr('id').split('_');
			i = campo[1];
			valores[campo[0]] = $(this).val();
		});
		
		if(!$('#reingreso_'+i).is(':checked'))
			valores['reingreso'] = 0;
		
		if(!$('#vigilancia_'+i).is(':checked'))
			valores['vigilancia'] = 0;
		
		enviar = 'diagnostico='+$('#idDiagnostico').val()+'&'+
				 'fecha='+valores['fecha']+'&'+
				 'reingreso='+valores['reingreso']+'&'+
				 'evolucion='+valores['evolucion']+'&'+
				 'tratamiento='+valores['tratamiento']+'&'+
				 'vigilancia='+valores['vigilancia']+'&'+
				 'observaciones='+valores['observaciones'];
		
		$.ajax({
			type: "POST",
			url: "ajax/saveControl.php",
			dataType: 'json',
			data: enviar,
			success: function(respuesta) 
			{
				if(respuesta.result == true) {
					$(boton).replaceWith('<img src="images/ok.gif" border="0">');
					$('  #fecha_'+i+
					  ', #reingreso_'+i+
					  ', #evolucion_'+i+
					  ', #tratamiento_'+i+
					  ', #vigilancia_'+i+
					  ', #observaciones_'+i+'').attr('disabled',true);
				}
				else
					jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
					'o Notifiquelo con el administrador', 'Error al procesar los datos...');
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
					'o Notifiquelo con el administrador', 'Error al procesar los datos...');
			}
		});
	}
	
    function agregaRegistroControl() {
        $(registroControl(++noControles)).appendTo("#tarjeta_control tbody");
        setupCalendario('fecha_'+noControles);
        
        // agrega evento para detectar la seleccion defuncion y mostrar el campo del CED
        $('#evolucion_'+noControles).change(function(){
            if($(this).val() == 8) {
                $(this).parent().parent().parent()
                        .append(' &nbsp; &nbsp; &nbsp; <input type="text" name="seed_'+noControles+'" id="seed_'+noControles+'" size="12" maxlength="9" placeholder="Folio Cer. Defuncion" autofocus><input type="button" name="buscarSEED" id="buscarSEED" value="Buscar">');
            }
        });
        
        // funcion de workless
		$("#registro_control_"+noControles+" select, "+
		  "#registro_control_"+noControles+" input:checkbox, "+
		  "#registro_control_"+noControles+" input:radio").uniform();
		
		$('#btnGuardaControl_'+noControles).click(guardaControl);
    }
	
    $(document).ready(function(){
        noControles = $("#tarjeta_control tbody tr").size();
        registroControl = jQuery.format($.trim($("#tmpl_control").val()));
		
		deshabilitarCampos = new Array('edoCaso'
								,'jurisCaso'
								,'muniCaso'
								,'uniTratado'
								,'clave_expediente'
								,'nombre'
								,'edad'
								,'sexo'
								,'ocupacion_paciente'
								,'domicilio'
								,'colonia'
								,'telefono'
								,'edoDomicilio'
								,'muniDomicilio'
								,'localiDomicilio'
								,'fecha_diagnostico'
								,'forma_lepra'
								,'result_histo'
								,'ib'
								,'im'
								,'discGeneral'
								,'deteccion');

		for(campo in deshabilitarCampos) 
			$('#'+deshabilitarCampos[campo]).attr('disabled',true);
		
		// deshabilita los campos de la targeta control
		$('#tarjeta_control').find('input, select').each(function(){
			campo = $(this).attr('disabled',true);
		});
		
    });
</script>

<?php
require_once('include/clasesLepra.php');
require_once('include/fecha_hora.php');

$objHTML = new HTML();
$objSelects = new Select();
$paciente = new Paciente();
$help = new Helpers();
$diagnostico = NULL;
$infUni = NULL;

if(!isset($_GET['id']))
	echo msj_error('No se encontraron datos del paciente');
else {
	$paciente->obtenerBD($_GET['id']);
	
	if(empty($paciente->idPaciente))
		echo msj_error('No se encontraron datos del paciente');
	else {
		$paciente->cargarArreglosPaciente();
		$diagnostico = $paciente->arrDiagnosticos[0];
		$diagnostico->cargarArreglosDiagnosticoEstudiosBac();
		$diagnostico->cargarArreglosDiagnosticoEstudiosHis();
		$diagnostico->cargarArreglosDiagnosticoContactos();
		$diagnostico->cargarArreglosDiagnosticoControl();
	}
}
$objHTML->startFieldset();

	if(!empty($paciente->idCatUnidadTratante)){
		$infUni = $help->getDatosUnidad($paciente->idCatUnidadTratante);
	}
	
	$objSelects->selectEstado('edoCaso', $infUni['idCatEstado']);			
	$objSelects->selectJurisdiccion('jurisCaso', $infUni['idCatEstado'], $infUni['idCatJurisdiccion']);
	$objSelects->selectMunicipio('muniCaso', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio']);
	$objSelects->selectUnidad('uniTratado', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio'], NULL, $paciente->idCatUnidadTratante);
	

$objHTML->endFieldset();


$objHTML->startFieldset('Datos de identificación');

	$objHTML->inputText('Clave del Paciente', 'clave_expediente', $paciente->cveExpediente, array( 
	'size'=>'25','style'=>'text-align:center;font-weight:bold;text-decoration:underline','title'=>'Se genera automaticamente'));
	$objHTML->inputText('Nombre: ', 'nombre', $paciente->apellidoPaterno.' '.$paciente->apellidoMaterno.' '.$paciente->nombre, array('size'=>40));
	$objHTML->inputText('Edad: ', 'edad', calEdad(formatFechaObj($paciente->fechaNacimiento, 'Y-m-d')).' a&ntilde;os', array('size'=>8));
	echo '<br />';
	
	$objSelects->SelectCatalogo('Sexo', 'sexo', 'catSexo', $paciente->sexo);
	$objHTML->inputText('Ocupación', 'ocupacion_paciente', $paciente->ocupacion, array('size'=>'40'));
	
	echo '<br />';
	$objHTML->inputText('Domicilio:', 'domicilio', trim($paciente->calle).', No. '.$paciente->noExterior, array('size'=>'40'));
	$objHTML->inputText('Colonia:', 'colonia', $paciente->colonia, array('size'=>'20'));
	$objHTML->inputText('Teléfono:', 'telefono', $paciente->telefono, array('size'=>'12'));
	echo '<br />';
	
	$objSelects->selectEstado('edoDomicilio', $paciente->idCatEstado ? $paciente->idCatEstado : 7);
	$objSelects->selectMunicipio('muniDomicilio', $paciente->idCatEstado ? $paciente->idCatEstado : 7, NULL, $paciente->idCatMunicipio);
	$objSelects->selectLocalidad('localiDomicilio', $paciente->idCatEstado, $paciente->idCatMunicipio, $paciente->idCatLocalidad, array('class'=>'validate[required]'));
	echo '<br />';
	
$objHTML->endFieldset();


$objHTML->startFieldset('Datos del diagnóstico');
	
	$objHTML->inputText('Fecha de diagnóstico', 'fecha_diagnostico',formatFechaObj($paciente->fechaDiagnostico));
	$objSelects->SelectCatalogo('Forma de Lepra: ', 'forma_lepra', 'catClasificacionLepra', $diagnostico->idCatClasificacionLepra);
	echo '<br />';
	
	$objHTML->inputText('Resultado Histopatológico:', 'result_histo', $diagnostico->arrEstudiosHis[0]->hisResultado, array('size'=>50));
	echo '<br />';
	
	$objHTML->label('Resultado Baciloscópico ');
	$objSelects->SelectCatalogo('IB:', 'ib', 'catBaciloscopia', $diagnostico->arrEstudiosBac[0]->idCatBac,NULL,false);
	$objHTML->inputText('IM:', 'im', $diagnostico->arrEstudiosBac[0]->bacIM, array('size'=>'10'));
	echo '<br />';
	
	$gradoDiscapacidad = array('0'=>'0', '1'=>'1', '2'=>'2');
	$discapacidadGeneral = array(
					$diagnostico->discOjoIzq,
					$diagnostico->discManoIzq,
					$diagnostico->discPieIzq,
					$diagnostico->discOjoDer,
					$diagnostico->discManoDer,
					$diagnostico->discPieDer);
	rsort($discapacidadGeneral);
	
	$objHTML->inputSelect('Grado de discapacidad:', 'discGeneral', $gradoDiscapacidad, array_shift($discapacidadGeneral));
	$objSelects->SelectCatalogo('Detección:', 'deteccion', 'catFormaDeteccion', $paciente->idCatFormaDeteccion, array('class'=>'validate[required]'));
	
$objHTML->endFieldset();

$objHTML->inputHidden('idDiagnostico', $diagnostico->idDiagnostico);

$objHTML->inputTextarea('', 'tmpl_control', 
        '<tr id="registro_control_{0}">
            <td align="center">'.$objHTML->inputText('', 'fecha_{0}', '', array('size'=>8, 'class'=>'fecha'), true).'</td>
            <td align="center">'.$objHTML->inputCheckbox('Si', 'reingreso_{0}', 1, '', NULL, true).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'evolucion_{0}', 'catEstadoPaciente', NULL, NULL, TRUE, TRUE).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'tratamiento_{0}', 'catTratamientoPreescrito', NULL, NULL, TRUE, TRUE).'</td>
            <td align="center">'.$objHTML->inputCheckbox('Si', 'vigilancia_{0}', 1, '', NULL, true).'</td>
            <td align="center">'.$objHTML->inputText('', 'observaciones_{0}', '', array('size'=>40), true).'</td>
			<td align="center">'.$objHTML->inputButton('btnGuardaControl_{0}', 'Guardar', null, true).'</td>
        </tr>', array('style'=>'display:none;'), false, true );

$objHTML->startForm('frmResultadoEstudio', '?'.$_SERVER['QUERY_STRING'], 'POST');

    $objHTML->startFieldset('Control');
	
    echo '<div class="datagrid">
            <table id="tarjeta_control">
            <thead>
            <tr align="center">
                <th>Fecha</th>
                <th>Reingreso</th>
                <th>Evoluci&oacute;n Cl&iacute;nica</th>
                <th>Tratamiento Preescrito</th>
                <th>Vigilancia Postratamiento</th>
                <th>Observaciones</th>
				<th></th>
            </tr>
            </thead>
            <tbody>';
	
    $i = 0;
	foreach($diagnostico->arrControles as $control){
		$i++;
		echo '<tr id="registro_control_'.$i.'">
            <td align="center">'.$objHTML->inputText('', 'fecha_'.$i, formatFechaObj($control->fecha), array('size'=>8, 'class'=>'fecha'), true).'</td>
            <td align="center">'.$objHTML->inputCheckbox('Si', 'reingreso_'.$i, 1, $control->reingreso, NULL, true).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'evolucion_'.$i, 'catEstadoPaciente', $control->idCatEstadoPaciente, NULL, TRUE, TRUE).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'tratamiento_'.$i, 'catTratamientoPreescrito', $control->idCatTratamientoPreescrito, NULL, TRUE, TRUE).'</td>
            <td align="center">'.$objHTML->inputCheckbox('Si', 'vigilancia_'.$i, 1, $control->vigilanciaPostratamiento, NULL, true).'</td>
            <td align="center">'.$objHTML->inputText('', 'observaciones_'.$i, $control->observaciones, array('size'=>40), true).'</td>
			<td align="center"><img src="images/ok.gif" border="0"></td>
        </tr>';
	}
	
    echo '</tbody></table></div>';

    echo '<br /><div align="center">';
    $objHTML->inputButton('agregar', 'Agregar Nuevo Registro', array('onClick'=>'agregaRegistroControl()'));
    echo '</div><br />';
    
    $objHTML->endFieldset();
    
    
    $objHTML->startFieldset('Control de Contactos');
    
    echo '<div class="datagrid">
            <table>
            <thead>
            <tr align="center">
                <th>No.</th>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Sexo</th>
            </tr>
            </thead>
            <tbody>';
			$i = 1;
			foreach($diagnostico->arrContactos as $contacto) {
            echo '<tr id="'.$contacto->idContacto.'">
                <td align="center">'.($i++).'</td>
                <td>'.$contacto->nombre.'</td>
                <td align="center">'.$contacto->edad.' a&ntilde;os</td>
                <td align="center">'.$help->getDescripcionSexo($contacto->sexo).' </td>
            </tr>';
        }
    echo '</tbody></table></div><br /><br />';
    
    $maxExamen = 0;
	$arrEstudiosContactos = NULL;
	$i = 1;
	
	foreach($diagnostico->arrContactos as $contacto) {
		$contacto->cargarEstudiosBac();
		$contacto->cargarEstudiosHis();
		$arrEstudios = null;
		
		// obtiene el numero maximo de estudios del contacto actual
		$maxEstudios = max(count($contacto->arrEstudiosBac), count($contacto->arrEstudiosHis));
		// obtiene el numero maximo de estudios de todos los contactos
		$maxExamen = max($maxExamen, $maxEstudios);
		
		for($i=0; $i<$maxEstudios; $i++) {
			
			if ( !empty($contacto->arrEstudiosBac[$i]->fechaResultado) ) {
				$arrEstudios[$i]['bacilo'] = array( 'fecha'    => formatFechaObj($contacto->arrEstudiosBac[$i]->fechaResultado), 
													'resultado'=> $help->getDescripBaciloscopia($contacto->arrEstudiosBac[$i]->idCatBac).' IM: '.$contacto->arrEstudiosBac[$i]->bacIM );
			}
			else {
				$arrEstudios[$i]['bacilo'] = array( 'fecha'    => '', 
													'resultado'=> '');
			}
			
			if( !empty($contacto->arrEstudiosHis[$i]->fechaResultado) ) {
				$arrEstudios[$i]['histo']  = array( 'fecha'    => formatFechaObj($contacto->arrEstudiosHis[$i]->fechaResultado), 
													'resultado'=> $help->getDescripcionHistopatologia($contacto->arrEstudiosHis[$i]->idCatHisto) );
			}
			else{
				$arrEstudios[$i]['histo']  = array( 'fecha'    => '', 
													'resultado'=> '');
			}
		}
		$arrEstudiosContactos[$contacto->idContacto] = $arrEstudios;
	}
	
	$encabezadoExamen = '';
	$encabezadoTipoEstudios = '';
	$encabezadoFechaResultado = '';
	
	// construye los encabezados de la tabla
	for($i=0; $i<$maxExamen; $i++) {
		$encabezadoExamen .= '<th colspan="4">Examen '.($i+1).'</th>';
		$encabezadoTipoEstudios .= '<th colspan="2">Baciloscop&iacute;a</th><th colspan="2">Histopatolog&iacute;a</th>';
		$encabezadoFechaResultado .= '<th>Fecha</th><th>Resultado</th><th>Fecha</th><th>Resultado</th>';
	}
	
	// construye las filas de la tabla
	$filasEstudioContactos = '';
	$j=0;
	
	foreach ($arrEstudiosContactos as $keyContacto => $valueEstudios) {
		$filasEstudioContactos .= '<tr id="'.$keyContacto.'"><td align="center">'.($j+1).'</td>';
		
		for($i=0; $i<$maxExamen; $i++) {	
			$filasEstudioContactos .= '<td align="center">'.$valueEstudios[$i]['bacilo']['fecha'].'</td>
									   <td align="center">'.$valueEstudios[$i]['bacilo']['resultado'].'</td>
									   <td align="center">'.$valueEstudios[$i]['histo']['fecha'].'</td>
									   <td align="center"'.$valueEstudios[$i]['histo']['resultado'].'></td>';
		}
		
		$filasEstudioContactos .= '</tr>';
		$j++;
	}
	
	echo '<div class="datagrid">
            <table>
				<thead>
					<tr align="center"><th rowspan="3">No.</th>'.$encabezadoExamen.'</tr>
					<tr align="center">'.$encabezadoTipoEstudios.'</tr>
					<tr align="center">'.$encabezadoFechaResultado.'</tr>
				</thead>
				<tbody>'.$filasEstudioContactos.'</tbody>
			</table>
		</div><br />';
	
    /*echo '<div class="datagrid">
            <table>
            <thead>
            <tr align="center">
                <th rowspan="3">No.</th>
                <th colspan="4">Examen 1</th>
                <th colspan="4">Examen 2</th>
                <th colspan="4">Examen 3</th>
                <th colspan="4">Examen 4</th>
                <th colspan="4">Examen 5</th>
                <th colspan="4">Examen 6</th>
                <th colspan="4">Examen 7</th>
            </tr>
            <tr align="center">
                <th colspan="2">Baciloscop&iacute;a</th>
                <th colspan="2">Histopatolog&iacute;a</th>
				
                <th colspan="2">Baciloscop&iacute;a</th>
                <th colspan="2">Histopatolog&iacute;a</th>
				
                <th colspan="2">Baciloscop&iacute;a</th>
                <th colspan="2">Histopatolog&iacute;a</th>
				
                <th colspan="2">Baciloscop&iacute;a</th>
                <th colspan="2">Histopatolog&iacute;a</th>
				
                <th colspan="2">Baciloscop&iacute;a</th>
                <th colspan="2">Histopatolog&iacute;a</th>
				
                <th colspan="2">Baciloscop&iacute;a</th>
                <th colspan="2">Histopatolog&iacute;a</th>
				
                <th colspan="2">Baciloscop&iacute;a</th>
                <th colspan="2">Histopatolog&iacute;a</th>
            </tr>
            <tr align="center">
                <th>Fecha</th>
                <th>Resultado</th>
                <th>Fecha</th>
                <th>Resultado</th>
				
                <th>Fecha</th>
                <th>Resultado</th>
                <th>Fecha</th>
                <th>Resultado</th>
				
                <th>Fecha</th>
                <th>Resultado</th>
                <th>Fecha</th>
                <th>Resultado</th>
				
                <th>Fecha</th>
                <th>Resultado</th>
                <th>Fecha</th>
                <th>Resultado</th>
				
                <th>Fecha</th>
                <th>Resultado</th>
                <th>Fecha</th>
                <th>Resultado</th>
				
                <th>Fecha</th>
                <th>Resultado</th>
                <th>Fecha</th>
                <th>Resultado</th>
				
                <th>Fecha</th>
                <th>Resultado</th>
                <th>Fecha</th>
                <th>Resultado</th>
            </tr>
            </thead>
            <tbody>';
	
	
        for($i=0; $i<count($diagnostico->arrContactos); $i++) {
            echo '<tr>
                <td align="center">'.($i+1).'</td>
				
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                
				<td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
				
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
				
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
				
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
				
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
				
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
            </tr>';
        }
    echo '</tbody></table></div><br />';*/
    
	
    /*echo '<div class="datagrid">
            <table>
            <thead>
            <tr align="center">
                <th>Examen</th>
				<th>Estudio</th>
                <th>Resultado</th>
                <th>Contacto 1</th>
                <th>Contacto 2</th>
                <th>Contacto 3</th>
            </tr>
            </thead>
            <tbody>';
	
	for($i=1; $i<=7; $i++) {
        echo '<tr align="center">
                <td rowspan="4">'.$i.'</td>
                <td rowspan="2">Baciloscop&iacute;a</td>
                <td>Fecha</td>
                <td>1</td>                
                <td>2</td>
                <td>3</td>
            </tr>
            <tr align="center">
                <td>Resultado</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
            </tr>
            <tr align="center">
                <td rowspan="2">Histopatolog&iacute;a</td>
                <td>Fecha</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
            </tr>
            <tr align="center">
                <td>Resultado</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
            </tr>';
	}
    echo '</tbody></table></div><br />';*/
    $objHTML->endFieldset();

$objHTML->endFormOnly();

?>