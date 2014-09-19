<link rel="stylesheet" href="include/jquery-ui-1.8.14.custom/development-bundle/themes/base/jquery.ui.all.css">

<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/js/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.position.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.dialog.js"></script>

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
        valores['seed'] = 'null';
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
                 'estadopaciente='+valores['estadopaciente']+'&'+
				 'evolucion='+valores['evolucion']+'&'+
				 'tratamiento='+valores['tratamiento']+'&'+
				 'vigilancia='+valores['vigilancia']+'&'+
                 'baja='+valores['baja']+'&'+
                 'seed='+valores['seed']+'&'+
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
                      ', #estadopaciente_'+i+
					  ', #evolucion_'+i+
					  ', #tratamiento_'+i+
					  ', #vigilancia_'+i+
                      ', #baja_'+i+
                      ', #seed_'+i+
					  ', #observaciones_'+i+'').attr('disabled',true);
                      $('#buscarSEED').remove();
                      $('#btnCal-fecha_'+i).remove();
				}
				else {
					jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
					'o Notifiquelo con el administrador', 'Error al procesar los datos...');
                    $(boton).attr('disabled',false);
                }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
					'o Notifiquelo con el administrador', 'Error al procesar los datos...');
			}
		});
	}
    
    function actualizaControl(){
		boton = this;
		$registro = $(this).parent().parent();
		var valores = new Array();
        valores['seed'] = 'null';
        idControl = 0;
		//i = idControl;
		
		//$(boton).attr('disabled',true);
		
		$registro.find('input, select').each(function(){
			campo = $(this).attr('id').split('_');
			idControl = campo[1];
			valores[campo[0]] = $(this).val();
		});
        
        console.log(valores);
		
		if(!$('#reingreso_'+idControl).is(':checked'))
			valores['reingreso'] = 0;
		
		if(!$('#vigilancia_'+idControl).is(':checked'))
			valores['vigilancia'] = 0;
		
		enviar = 'diagnostico='+$('#idDiagnostico').val()+'&'+
				 'fecha='+valores['fecha']+'&'+
				 'reingreso='+valores['reingreso']+'&'+
                 'estadopaciente='+valores['estadopaciente']+'&'+
				 'evolucion='+valores['evolucion']+'&'+
				 'tratamiento='+valores['tratamiento']+'&'+
				 'vigilancia='+valores['vigilancia']+'&'+
                 'baja='+valores['baja']+'&'+
                 'seed='+valores['seed']+'&'+
				 'observaciones='+valores['observaciones'];
		
		$.ajax({
			type: "POST",
			url: "ajax/updateControl.php?idControl="+idControl,
			dataType: 'json',
			data: enviar,
			success: function(respuesta) 
			{
				if(respuesta.result == true) {
					/*$(boton).replaceWith('<img src="images/ok.gif" border="0">');
					$('  #fecha_'+idControl+
					  ', #reingreso_'+idControl+
                      ', #estadopaciente_'+idControl+
					  ', #evolucion_'+idControl+
					  ', #tratamiento_'+idControl+
					  ', #vigilancia_'+idControl+
                      ', #baja_'+idControl+
                      ', #seed_'+idControl+
					  ', #observaciones_'+idControl+'').attr('disabled',true);
                      $('#buscarSEED').remove();
                      $('#btnCal-fecha_'+idControl).remove();*/
                    alert('Datos Guardados Correctamente...');
				}
				else {
					jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
					'o Notifiquelo con el administrador', 'Error al procesar los datos...');
                    //$(boton).attr('disabled',false);
                }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
					'o Notifiquelo con el administrador', 'Error al procesar los datos...');
			}
		});
	}
	
    function agregaRegistroControl() {
        ultimoTR = $('#tarjeta_control tr:last');
        
        /*if(ultimoTR.has('img[src="images/ok.gif"]').length == 0){
            alert('Error: Debe guardar el ultimo registro de control para poder agregar uno nuevo.');
            return false;
        }*/
    
        $(registroControl(++noControles)).appendTo("#tarjeta_control tbody");
        setupCalendario('fecha_'+noControles);
        
        // agrega evento para detectar la seleccion defuncion y mostrar el campo del CED
        $('#baja_'+noControles).change(function(){
            if($(this).val() == 2) {
                $(this).parent().parent().parent()
                        .append('<input type="text" name="seed_'+noControles+'" id="seed_'+noControles+'" size="12" maxlength="9" placeholder="Folio Cer. Defuncion" autofocus><input type="button" name="buscarSEED" id="buscarSEED" value="Buscar">');
            }
            else {
                $(this).parent().parent().parent().find('input:text, input:button').remove();
            }
        });
        
        $("#registro_control_"+noControles+" input:text").change(function(){
            $(this).val( normalize($(this).val()).toUpperCase() );
        });
        
        // Validar fechas de los controles mayor o igual a 28 dias
        /*$("#fecha_"+noControles).change(function(){
            if(!validateFecha($(this).val(), '>=', $('#tarjeta_control tr:last').prev().find('input[id^=fecha_]').val(), '28')) {
                alert('ERROR: La fecha del siguiente registro de control debe ser por lo menos 28 dias despues del registro anterior');
                $(this).val('');
                $(this).focus();
            }
        });*/
        
        // funcion de workless
		$("#registro_control_"+noControles+" select, "+
		  "#registro_control_"+noControles+" input:checkbox, "+
		  "#registro_control_"+noControles+" input:radio").uniform();
		
		$('#btnGuardaControl_'+noControles).click(guardaControl);
    }
	
    function revisionContacto(idContacto, nombre) {
        $('#nombre_contacto').text(nombre);
        $('#idContactoRev').val(idContacto);
        $('#fecha_revision').val('');
        $('#revision_clinica option[value=0').attr('selected',true);
        $('#uniform-revision_clinica span').text('Elegir');
        $('#observaciones_revContacto').text('');
        
        $('#btnProcesarRevisionContacto').show();
        
        $("#winRevisionContacto").dialog('open');
    }
    
    function procesarRevisionContacto(){
        //$("#winRevisionContacto").dialog('close');
        if(confirm('esta seguro que los datos son correctos')) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: 'ajax/saveControlContacto.php',
                data: $('#formRevisionContacto').serialize(),
                success: function(respuesta)
                {
                    alert(respuesta.msj);

                    if(!respuesta.error) {
                        if($('#revision_clinica').val() != '' && $('#revision_clinica').val() != 1) {
                            jConfirm('<font color="#FF0000" style="font-weight:bold">ADVERTENCIA</font>. El Diagn&oacute;stico Cl&iacute;nico del contacto '+
                                    $('#nombre_contacto').text()+' indica que es un caso probable, ser&aacute; redireccionado automaticamente   '+
                                    'a la c&eacute;dula de registro para capturar este nuevo caso.', 'Caso Probable', function(r) {
                                        location.href = 'index.php?mod=cap';
                                    });
                        } else {
                            location.reload();
                        }
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
                        'o Notifiquelo con el administrador', 'Error al procesar los datos...');
                }
            });
        }
    }
    
    function showRevisionContacto(idControlContacto){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'ajax/getControlContacto.php',
            data: 'idContactoRev='+idControlContacto,
            success: function(respuesta)
            {
                if(!respuesta.error) {
                    $('#nombre_contacto').text(respuesta.nombre);
                    $('#fecha_revision').val(respuesta.fecha);
                    $('#revision_clinica option[value='+respuesta.idCatRevisionContacto+']').attr('selected',true);
                    $('#uniform-revision_clinica span').text( $('#revision_clinica option[value='+respuesta.idCatRevisionContacto+']').text() );
                    $('#observaciones_revContacto').text(respuesta.observaciones);
                    
                    $('#btnProcesarRevisionContacto').hide();
                    
                    $("#winRevisionContacto").dialog('open');
                } else {
                    alert(respuesta.msj);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
                    'o Notifiquelo con el administrador', 'Error al procesar los datos...');
            }
        });
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
		/*$('#tarjeta_control').find('input, select').each(function(){
			campo = $(this).attr('disabled',true);
		});*/
        
        $("#winRevisionContacto").dialog({
            autoOpen: false,
            height: 380,
            width: 300,
            modal: true,
            resizable: true
        });
        
        $('.showRevision').click(function(){
            showRevisionContacto($(this).data('idrev'));
        });
        
        setupCalendario("fecha_revision");
		
        $('#tarjeta_control').find('input[name^="fecha_"]').each(function(){
			setupCalendario($(this).attr('id'));
		});
        
        $('input[id^="btnActualizaControl_"]').click(actualizaControl);
        
        $('#revision_clinica').change(function(){
            if($(this).val() != '' && $(this).val() != 1) {
                jAlert('<font color="#FF0000" style="font-weight:bold">ADVERTENCIA</font>. El Diagn&oacute;stico Cl&iacute;nico del contacto '+
                        $('#nombre_contacto').text()+' indica que es un caso probable, despu&eacute;s de guardar la Revisi&oacute;n Cl&iacute;nica '+
                        'capture una nueva c&eacute;dula de registro para notificar este nuevo caso.', 'Caso Probable');
            }
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
	//print_r($diagnostico->arrEstudiosHis);
	$key1 = 0;
	foreach($diagnostico->arrEstudiosHis as $key => $resDiagnostico)
	{
		if($resDiagnostico->idCatTipoEstudio == 1)
		{
			$key1 = $key;
			break;
		}
	}
	
	
	$objHTML->inputText('Resultado Histopatológico:', 'result_histo', $diagnostico->arrEstudiosHis[$key1]->hisResultado, array('size'=>50));
	echo '<br />';
	
	$objHTML->label('Resultado Bacteriológico ');
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
        '<tr id="registro_control_{0}" align="center">
            <td align="center">'.$objHTML->inputText('', 'fecha_{0}', '', array('size'=>8, 'class'=>'fecha'), true).'</td>
            <td align="center">'.$objHTML->inputCheckbox('Si', 'reingreso_{0}', 1, '', NULL, true).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'estadopaciente_{0}', 'catEstadoPaciente', NULL, NULL, TRUE, TRUE).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'evolucion_{0}', 'catEvolucionClinica', NULL, NULL, TRUE, TRUE).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'tratamiento_{0}', 'catTratamientoPreescrito', NULL, NULL, TRUE, TRUE).'</td>
            <td align="center">'.$objHTML->inputCheckbox('Si', 'vigilancia_{0}', 1, '', NULL, true).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'baja_{0}', 'catBaja', NULL, NULL, TRUE, TRUE).'</td>
            <td align="center">'.$objHTML->inputText('', 'observaciones_{0}', '', array('size'=>40), true).'</td>
			<td align="center">'.$objHTML->inputButton('btnGuardaControl_{0}', 'Guardar', null, true).'</td>
        </tr>', array('style'=>'display:none;'), false, true );

//$objHTML->startForm('frmResultadoEstudio', '?'.$_SERVER['QUERY_STRING'], 'POST');

    $objHTML->startFieldset('Control');
	
    echo '<div class="datagrid">
            <table id="tarjeta_control">
            <thead>
            <tr align="center">
                <th>Fecha</th>
                <th>Reingreso</th>
                <th>Estado Paciente</th>
                <th>Evoluci&oacute;n<br />Cl&iacute;nica</th>
                <th>Tratamiento<br />Preescrito</th>
                <th>Vigilancia<br />Postratamiento</th>
                <th>Baja</th>
                <th>Observaciones</th>
				<th></th>
            </tr>
            </thead>
            <tbody>';
	
    //$i = 0;
	foreach($diagnostico->arrControles as $control){
		//$i++;
		echo '<tr id="registro_control_'.$control->idControl.'" align="center">
            <td align="center">'.$objHTML->inputText('', 'fecha_'.$control->idControl, formatFechaObj($control->fecha), array('size'=>8, 'class'=>'fecha'), true).'</td>
            <td align="center">'.$objHTML->inputCheckbox('Si', 'reingreso_'.$control->idControl, 1, $control->reingreso, NULL, true).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'estadopaciente_'.$control->idControl, 'catEstadoPaciente', $control->idCatEstadoPaciente, NULL, TRUE, TRUE).'</td>            
            <td>'.$objSelects->SelectCatalogo('', 'evolucion_'.$control->idControl, 'catEvolucionClinica', $control->idCatEvolucionClinica, NULL, TRUE, TRUE).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'tratamiento_'.$control->idControl, 'catTratamientoPreescrito', $control->idCatTratamientoPreescrito, NULL, TRUE, TRUE).'</td>
            <td align="center">'.$objHTML->inputCheckbox('Si', 'vigilancia_'.$control->idControl, 1, $control->vigilanciaPostratamiento, NULL, true).'</td>
            <td>'.$objSelects->SelectCatalogo('', 'baja_'.$control->idControl, 'catBaja', $control->idCatBaja, NULL, TRUE, TRUE);
        if($control->seed) {
            echo '<input type="text" name="seed_'.$control->idControl.'" id="seed_'.$control->idControl.'" size="12" maxlength="9" placeholder="Folio Cer. Defuncion" value="'.$control->seed.'"><br>
                <input type="button" name="buscarSEED" id="buscarSEED" value="Buscar">';
        }
            echo '</td><td align="center">'.$objHTML->inputText('', 'observaciones_'.$control->idControl, $control->observaciones, array('size'=>40), true).'</td>';
            echo '<td align="center">'.$objHTML->inputButton('btnActualizaControl_'.$control->idControl, 'Actualizar', null, true).'</td>';
			//<td align="center"><img src="images/ok.gif" border="0"></td>
        echo '</tr>';
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
                <th>Revisi&oacuten Cl&iacute;nica</th>
            </tr>
            </thead>
            <tbody>';
			$i = 1;
			foreach($diagnostico->arrContactos as $contacto) {
            echo '<tr id="'.$contacto->idContacto.'">
                <td align="center">'.($i++).'</td>
                <td>'.$contacto->nombre.'</td>
                <td align="center">'.$contacto->edad.' a&ntilde;os</td>
                <td align="center">'.$help->getDescripcionSexo($contacto->sexo).'</td>
                <td align="center"><a href="javascript:revisionContacto('.$contacto->idContacto.',\''.$contacto->nombre.'\')"><img src="images/revision_contacto.png" border="0"/></a></td>
            </tr>';
        }
    echo '</tbody></table></div><br /><br />';
    

    echo '<div id="winRevisionContacto" title="Revisi&oacute;n de Contacto">';

        $objHTML->startForm('formRevisionContacto', '#', 'POST');

            $objHTML->inputHidden('idContactoRev');
            echo '<label><strong>Contacto: <u> &nbsp; <span id="nombre_contacto"></span> &nbsp; </u></strong></label><br>';
            $objHTML->inputText('Fecha: ', 'fecha_revision');
            echo '<br><label>Diagn&oacute;stico Cl&iacute;nico: </label><br>';
            $objSelects->SelectCatalogo('', 'revision_clinica', 'catRevisionContacto');
            echo '<br><label>Observaciones:</label><br>';
            $objHTML->inputTextarea('', 'observaciones_revContacto', '', array('rows'=>8, 'cols'=>35));
            echo '<br><br><div align="center">';
            $objHTML->inputButton('btnProcesarRevisionContacto', 'Guardar', array('onClick'=>'procesarRevisionContacto()'));
            echo '</div>';

        $objHTML->endFormOnly();

    echo '</div>';
    
    /***************************************************************************/
    
    echo '<h3>Revisi&oacute;n Cl&iacute;nica</h3>';
    
    $maxRevision = 0;
	$arrRevisionContactos = NULL;
	$i = 1;
    
    $objCatalogo = new Catalogo('catRevisionContacto');
    $catRevisionContacto = $objCatalogo->getValores();
    
    foreach($catRevisionContacto as $key => $val) {
        $catRevisionContacto[$key] = str_replace('lesiones','lesiones<br>', htmlentities($val));
        
    }
    
    foreach($diagnostico->arrContactos as $contacto) {
        $objControlContacto = new ControlContacto();
        $objControlContacto->obtenerBD($contacto->idContacto);
        
        $arrRevisiones = null;
		
		$maxRevContacto = count($objControlContacto->arrRevisionContacto);
		$maxRevision = max($maxRevision, $maxRevContacto);
		
        for($i=0; $i<$maxRevContacto; $i++) {
				$arrRevisiones[$i] = array( 'id'            => $objControlContacto->arrRevisionContacto[$i]->idControlContacto,
                                            'fecha'         => $objControlContacto->arrRevisionContacto[$i]->fecha,
                                            'resultado'     => $catRevisionContacto[$objControlContacto->arrRevisionContacto[$i]->idCatRevisionContacto],
                                            'observaciones' => htmlentities($objControlContacto->arrRevisionContacto[$i]->observaciones));
		}
		$arrRevisionContactos[$contacto->idContacto] = $arrRevisiones;
    }
    
    $encabezadoRevision = '';
	$encabezadoFechaResultadoObserv = '';
	
	// construye los encabezados de la tabla
	for($i=0; $i<$maxRevision; $i++) {
		$encabezadoRevision .= '<th colspan="2">Revisi&oacute;n '.($i+1).'</th>';//'<th colspan="3">Revisi&oacute;n '.($i+1).'</th>';
		$encabezadoFechaResultadoObserv .= '<th>Fecha</th><th>Resultado</th>';//'<th>Fecha</th><th>Resultado</th><th>Observaciones</th>';
	}
	
	// construye las filas de la tabla
	$filasRevisionesContactos = '';
	$j=0;
	
	foreach ($arrRevisionContactos as $keyContacto => $valueRevision) {
		$filasRevisionesContactos .= '<tr id="'.$keyContacto.'"><td align="center">'.($j+1).'</td>';
		
		for($i=0; $i<$maxRevision; $i++) {	
			$filasRevisionesContactos .= '<td align="center"><span class="showRevision" data-idrev="'.$valueRevision[$i]['id'].'">'.$valueRevision[$i]['fecha'].'</span></td>
									   <td align="center"><span class="showRevision" data-idrev="'.$valueRevision[$i]['id'].'">'.$valueRevision[$i]['resultado'].'</span></td>';
									   //<td align="center">'.$valueRevision[$i]['observaciones'].'</td>';
		}
		
		$filasRevisionesContactos .= '</tr>';
		$j++;
	}
    
    $arrRevisionContactos = array_filter($arrRevisionContactos);
    
    if(!empty($arrRevisionContactos))
    {
        echo '<div class="datagrid">
                <table>
                    <thead>
                        <tr align="center"><th rowspan="2">No.</th>'.$encabezadoRevision.'</tr>';

        if(!empty($encabezadoFechaResultadoObserv))
                        echo '<tr align="center">'.$encabezadoFechaResultadoObserv.'</tr>';

                    echo '</thead>
                    <tbody>'.$filasRevisionesContactos.'</tbody>
                </table>
            </div>';
    } else
        echo 'No se encontraron revisiones clinicas de los contactos';
    
    echo '<br />';
    
    /***************************************************************************/
    
    echo '<br><br><h3>Estudios de laboratorio</h3>';
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
    
	$arrEstudiosContactos = array_filter($arrEstudiosContactos);
    
    if(!empty($arrEstudiosContactos))
    {
        echo '<div class="datagrid">
                <table>
                    <thead>
                        <tr align="center"><th rowspan="3">No.</th>'.$encabezadoExamen.'</tr>';

        if(!empty($encabezadoTipoEstudios))
                        echo '<tr align="center">'.$encabezadoTipoEstudios.'</tr>';

        if(!empty($encabezadoFechaResultado))
                        echo'<tr align="center">'.$encabezadoFechaResultado.'</tr>';

                    echo '</thead>
                    <tbody>'.$filasEstudioContactos.'</tbody>
                </table>
            </div>';
    } else
        echo 'No se encontraron estudios de laboratorio de los contactos';
    
    echo '<br />';
    
    $objHTML->endFieldset();

//$objHTML->endFormOnly();

?>