function calcular_edad()
{
	$.ajax({
	  	type: "POST",
	  	url: "ajax/edad.php",
	  	data: "edad="+$("#fecha_nacimiento").val(),
	  	success: function(response) {
			$("#edad").val(response);
		},
		error:function(){
			  alert("Something went wrong...");
		}
    });
}

function verFotos(id)
{
	$.ajax({
	  	type: "POST",
	  	url: "ajax/verFotos.php",
	  	data: "lesionId="+id,
	  	success: function(response) {
			$("#dialog_form").html(response);
			
			$("#dialog_form").dialog("option", "title", "Fotos");				
			$("#dialog_form").dialog("open");

            $("#uploadPhoto").submit(function(){
				$("#upload_target").load(function(e) {
					$("#dialog_form").dialog("close");
                    jAlert('<img src="images/ok.gif" > <strong>Imagen Registrada</strong>', 'Imagen registrada exitosamente');
					//verFotos(id); // Revisar es recursivo??
				});
				//return false;
			});
			
		},
		error:function(){
			  alert("Something went wrong...");
		}
    });
}

no_caso_relacionado = 0;

function agregaCasoRelacionado() {
	$casoRelacionado = $('#tmpl_caso_relacionado').clone();
	
	$casoRelacionado.css('display','block');
	$casoRelacionado.attr('id','caso_relacionado_'+no_caso_relacionado);
	$casoRelacionado.find('#idCasoRelacionado_').attr('name','idCasoRelacionado_'+no_caso_relacionado).attr('id','idCasoRelacionado_'+no_caso_relacionado);
	$casoRelacionado.find('#nombre_caso_relacionado_').attr('name','nombre_caso_relacionado_'+no_caso_relacionado).attr('id','nombre_caso_relacionado_'+no_caso_relacionado).
            change(function() { $(this).val( normalize($(this).val()).toUpperCase() ); });
	$casoRelacionado.find('#parentesco_caso_relacionado_').
            attr('name','parentesco_caso_relacionado_'+no_caso_relacionado).
            attr('id','parentesco_caso_relacionado_'+no_caso_relacionado).
            change(function(){ $(this).parent().find('span').text($(this).find('option:selected').text()); });
    $casoRelacionado.find('#uniform-parentesco_caso_relacionado_').attr('id','uniform-parentesco_caso_relacionado_'+no_caso_relacionado);
	$casoRelacionado.find('#ano_caso_relacionado_').attr('name','ano_caso_relacionado_'+no_caso_relacionado).attr('id','ano_caso_relacionado_'+no_caso_relacionado);
	$casoRelacionado.find('#meses_caso_relacionado_').attr('name','meses_caso_relacionado_'+no_caso_relacionado).attr('id','meses_caso_relacionado_'+no_caso_relacionado);
	$casoRelacionado.find('#situacion_caso_relacionado_').
            attr('name','situacion_caso_relacionado_'+no_caso_relacionado).
            attr('id','situacion_caso_relacionado_'+no_caso_relacionado).
            change(function(){ $(this).parent().find('span').text($(this).find('option:selected').text()); });
    $casoRelacionado.find('#uniform-situacion_caso_relacionado_').attr('id','uniform-situacion_caso_relacionado_'+no_caso_relacionado);
	$casoRelacionado.find('.delCasoRelacionado').click(function(){ 
		$('#del_casos_relacionados').val( $(this).parent().find('input[type=hidden]').val() +','+ $('#del_casos_relacionados').val() );
		$(this).parent().remove(); 
	});
	
	$('#no_caso_relacionado').val(no_caso_relacionado) ;
	no_caso_relacionado++;
	
	$('#casoRelacionados').append($casoRelacionado);
    // funcion de workless
    $("#caso_relacionado_"+no_caso_relacionado+" select, #caso_relacionado_"+no_caso_relacionado+" input:checkbox, #caso_relacionado_"+no_caso_relacionado+" input:radio").uniform();
}

no_contactos = 0;
		
function agregaContacto() {
	$contacto = $('#tmpl_contacto').clone();
	
	$contacto.css('display','block');
	$contacto.attr('id','contacto_'+no_contactos);
	$contacto.find('#idContacto_').attr('name','idContacto_'+no_contactos).attr('id','idContacto_'+no_contactos);
	$contacto.find('#nombre_contacto_').attr('name','nombre_contacto_'+no_contactos).attr('id','nombre_contacto_'+no_contactos).
            change(function() { $(this).val( normalize($(this).val()).toUpperCase() ); });
	$contacto.find('#sexo_contacto_').
            attr('name','sexo_contacto_'+no_contactos).
            attr('id','sexo_contacto_'+no_contactos).
            change(function(){ $(this).parent().find('span').text($(this).find('option:selected').text()); });
    $contacto.find('#uniform-sexo_contacto_').attr('id','uniform-sexo_contacto_'+no_contactos);
	$contacto.find('#edad_contacto_').attr('name','edad_contacto_'+no_contactos).attr('id','edad_contacto_'+no_contactos);
	$contacto.find('#parentesco_contacto_').
            attr('name','parentesco_contacto_'+no_contactos).
            attr('id','parentesco_contacto_'+no_contactos).
            change(function(){ $(this).parent().find('span').text($(this).find('option:selected').text()); });
    $contacto.find('#uniform-parentesco_contacto_').attr('id','uniform-parentesco_contacto_'+no_contactos);
	$contacto.find('#ano_contacto_').attr('name','ano_contacto_'+no_contactos).attr('id','ano_contacto_'+no_contactos);
	$contacto.find('#mes_contacto_').attr('name','mes_contacto_'+no_contactos).attr('id','mes_contacto_'+no_contactos);
	$contacto.find('.delContacto').click(function(){ 
		$('#del_contactos').val( $(this).parent().find('input[type=hidden]').val() +','+ $('#del_contactos').val() );
		$(this).parent().remove(); 
	});
		
	$('#no_contactos').val(no_contactos);
	no_contactos++;
	
	$('#contactos').append($contacto);
    // funcion de workless
    $("#contacto_"+no_contactos+" select, #contacto_"+no_contactos+" input:checkbox, #contacto_"+no_contactos+" input:radio").uniform();
}

function setClaveExpediente() {
	doClaveExpediente( {destino:'clave_expediente', 
					nombre:'nombre_paciente', 
					apePaterno:'ap_paterno_paciente', 
					apeMaterno:'ap_materno_paciente', 
					fechaNac:'fecha_nacimiento', 
					sexo:'sexo', 
					edoNac:'edoNac'});
}
	
/*function validaForm() {
	return true;
}*/

function setGradoDiscGeneral() {
	gradoDisc = new Array();
	gradoDisc[0] = ($('#ojo_izq').val());
	gradoDisc[1] = ($('#mano_izq').val());
	gradoDisc[2] = ($('#pie_izq').val());
	gradoDisc[3] = ($('#ojo_der').val());
	gradoDisc[4] = ($('#mano_der').val());
	gradoDisc[5] = ($('#pie_der').val());
	
	gradoDisc.sort();
	
	$('#discGeneral option[value='+gradoDisc[5]+']').attr("selected",true);
	$('#uniform-discGeneral span').text(gradoDisc[5]);
}

function showEdoRecTipo2(valor, div) {
	if(valor == 3)
        $('#'+div).css('display', 'inline');
    else
        $('#'+div).css('display', 'none');
}

function deshabilitarCamposCaptura(formulario){
    camposCapturaFase1 = new Array(
                    'clave_expediente',
                    'ap_paterno_paciente',
                    'ap_materno_paciente',
                    'nombre_paciente',
                    'fecha_nacimiento',
                    'sexo',
                    'ocupacion_paciente',
                    'edoNac',
                    'muniNac',
                    'calle',
                    'num_externo',
                    'num_interno',
                    'colonia',
                    'edoDomicilio',
                    'muniDomicilio',
                    'localiDomicilio',
                    'radica_anos',
                    'radica_meses',
                    'telefono',
                    'celularContacto',
                    'edoUnidad',
                    'jurisUnidad',
                    'muniUnidad',
                    'uniNotificante',
                    'institucion',
                    'otraInstitucion',
                    'derechohabiencia',
                    'otraDerechohabiencia',
                    'deteccion',
                    'fecha_padecimiento',
                    'fecha_diagnostico',
                    
                    'tagLesiones',
                    'delTagLesiones',
                    
                    'topografia',
                    'topo_morfo_lesiones',
                    'segAfeCab',
                    'segAfeTro',
                    'segAfeMSI',
                    'segAfeMSD',
                    'segAfeMII',
                    'segAfeMID',
                    'noLesiones',
                    'edoCaso',
                    'jurisCaso',
                    'muniCaso',
                    'uniTratado',
                    'uniReferido',
                    'institucion_caso',
                    'otra_institutcion_caso',
                    'edoReferido',
                    'medicoElaboro',
                    'medicoValido'
                    );
                        
    camposCapturaFase2 = new Array(
                    'tipo_paciente',
                    'fecha_notificacion',
                    'semana_notificacion',
					'fecha_histo',
					'fecha_bacil',
                    'fecha_pqt',
                    'ojo_izq',
                    'mano_izq',
                    'pie_izq',
                    'ojo_der',
                    'mano_der',
                    'pie_der',
                    'discGeneral',
                    'reaccional_anterior',
                    'edoRecAntTipo2Nud',
                    'edoRecAntTipo2Poli',
                    'edoRecAntTipo2Necro',
                    'reaccional_actual',
                    'edoRecActTipo2Nud',
                    'edoRecActTipo2Poli',
                    'edoRecActTipo2Necro',
                    'tipo_uno',
                    'tipo_dos',
                    'diagnostico',
                    'estado_paciente',
                    'tratamiento',
                    'edoAquirioEnfermedad',
                    'muniAquirioEnfermedad',
                    'localiAquirioEnfermedad',
                    'idCasoRelacionado_',
                    'nombre_caso_relacionado_',
                    'parentesco_caso_relacionado_',
                    'ano_caso_relacionado_',
                    'meses_caso_relacionado_',
                    'situacion_caso_relacionado_',
                    'no_caso_relacionado',
                    'del_casos_relacionados',
                    'agrega_caso_relacionado',
                    'idContacto_',
                    'nombre_contacto_',
                    'sexo_contacto_',
                    'edad_contacto_',
                    'parentesco_contacto_',
                    'ano_contacto_',
                    'mes_contacto_',
                    'agrega_contacto',
                    'edoCaso',
                    'jurisCaso',
                    'muniCaso',
                    'otros_padecimientos',
                    'observaciones',
                    'actualizarSbmt',
                    'guardarSbmt',
                    'limpiar'); 
    
    for(campo in camposCapturaFase1) {
        $('*[name^='+camposCapturaFase1[campo]+']').each(function(){
            $(this).attr('disabled',true);
        });
    }
    // para la captura del contacto
    $('*[name^=sexo_]').each(function(){
        $(this).removeAttr('disabled');
    });
    
    $('#btnCal-fecha_nacimiento').hide();
    $('#btnCal-fecha_padecimiento').hide();
    $('#btnCal-fecha_diagnostico').hide();
    
    // Fase 2 de captura
    if($('#tipo_paciente').val() != 5 && $('#tipo_paciente').val() != 6){
        for(campo in camposCapturaFase2) {
            $('*[name^='+camposCapturaFase2[campo]+']').each(function(){
                $(this).attr('disabled',true);
            });
        }
        
        $('#'+formulario).attr('action','#');
        $('input[type=submit]').parent().remove();
        $('a[id^=btnCal-]').remove();
        $('.delCasoRelacionado').remove();
        $('.delContacto').remove()
        $('#agrega_contacto').remove();
        $('#agrega_caso_relacionado').remove();
        $('#contactos div:last').remove();
        $('#casoRelacionados div:last').remove();
        
        
        //Si es la fase 2 de captura, deben de proporcionar los siguientes campos obligatorios
        if( !$('#reaccional_anterior').hasClass('validate[required]') )
            $('#reaccional_anterior').addClass('validate[required]');
        
        if( !$('#reaccional_actual').hasClass('validate[required]') )
            $('#reaccional_actual').addClass('validate[required]');
        
        if( !$('#fecha_notificacion').hasClass('validate[required]') )
            $('#fecha_notificacion').addClass('validate[required]');
        
        /*if( !$('#fecha_pqt').hasClass('validate[required]') )
            $('#fecha_pqt').addClass('validate[required]');*/
    }
}


$tagsBody = null;

$(document).ready(function(){
	
	$("#sexo").change(function(e) {
		if(this.value == 1)
			$("#imagenCuerpo").attr("src","images/male_body_ok.png");
		if(this.value == 2)
			$("#imagenCuerpo").attr("src","images/female_body_ok.png");
    });
	
	$('#edoNac').change(function(){ 
		actualiza_select( { destino:'muniNac', edo:'edoNac', tipo:'muni'} );
		if(this.value == 33)
		{
			$("#campoExtrangero").addClass("validate[required]");
			$("#nombreExtranjero").show();
			$("#muniNac").removeClass("validate[required]");
		}
		else
		{
			$("#campoExtrangero").removeClass("validate[required]");
			$("#nombreExtranjero").hide();
			$("#muniNac").addClass("validate[required]");
		}
	});
	
	$('#edoDomicilio').change(function(){ 
		actualiza_select( { destino:'muniDomicilio', edo:'edoDomicilio', tipo:'muni'} );
		reset_select('localiDomicilio');
	});
	
	$('#muniDomicilio').change(function(){ 
		actualiza_select( { destino:'localiDomicilio', edo:'edoDomicilio', muni:'muniDomicilio', tipo:'locali'} );
	});
	
	$('#edoUnidad').change(function(){ 
		actualiza_select( { destino:'jurisUnidad', edo:'edoUnidad', tipo:'juris'} );
		reset_select('muniUnidad');
		reset_select('uniNotificante');
	});
	
	$('#jurisUnidad').change(function(){ 
		actualiza_select( { destino:'muniUnidad', edo:'edoUnidad', juris:'jurisUnidad', tipo:'muni'} );
		reset_select('uniNotificante');//actualiza_select( { destino:'uniNotificante', edo:'edoUnidad', juris:'jurisUnidad', tipo:'uni'} );
	});
    
    $('#muniUnidad').change(function(){ 
		actualiza_select( { destino:'uniNotificante', edo:'edoUnidad', juris:'jurisUnidad', muni:'muniUnidad', tipo:'uni'} );
	});
    
    $('#uniNotificante').change(function(){ 
        getInstitucionFromUni('uniNotificante','institucion');
    });
	
	$('#edoAquirioEnfermedad').change(function(){ 
		actualiza_select( { destino:'muniAquirioEnfermedad', edo:'edoAquirioEnfermedad', tipo:'muni'} );
		reset_select('localiAquirioEnfermedad');
	});
	
	$('#muniAquirioEnfermedad').change(function(){ 
		actualiza_select( { destino:'localiAquirioEnfermedad', edo:'edoAquirioEnfermedad', muni:'muniAquirioEnfermedad', tipo:'locali'} );
	});
	
	$('#edoCaso').change(function(){ 
		actualiza_select( { destino:'jurisCaso', edo:'edoCaso', tipo:'juris'} );
		reset_select('muniCaso');
		reset_select('uniTratado');
		//reset_select('uniReferido');
	});
    
	$('#jurisCaso').change(function(){ 
		actualiza_select( { destino:'muniCaso', edo:'edoCaso', juris:'jurisCaso', tipo:'muni'} );
		//actualiza_select( { destino:'uniTratado', edo:'edoCaso', juris:'jurisCaso', tipo:'uni'} );
		//actualiza_select( { destino:'uniReferido', edo:'edoCaso', juris:'jurisCaso', tipo:'uni'} );
        reset_select('uniTratado');
		//reset_select('uniReferido');
	});
    
    $('#muniCaso').change(function(){ 
		actualiza_select( { destino:'uniTratado', edo:'edoCaso', juris:'jurisCaso', muni:'muniCaso', tipo:'uni'} );
		//actualiza_select( { destino:'uniReferido', edo:'edoCaso', juris:'jurisCaso', muni:'muniCaso', tipo:'uni'} );
	});
    
    $('#uniTratado').change(function(){ 
        getInstitucionFromUni('uniTratado','institucion_caso');
    });
    
    /*$('#uniReferido').change(function(){ 
        getInstitucionFromUni('uniReferido','institucion_caso');
    });*/
	
	$tagsBody = $('.body').photoTag({
		requestTagsUrl: 'ajax/load-tag.php',
		deleteTagsUrl: 'ajax/delete-tag.php',
		addTagUrl: 'ajax/add-tag.php',
		parametersForRequest: ['id'],
        showAddNewLink: $('#showAddNewLink').val(), //p4scu41
		parametersForNewTag: {
			name: {
				parameterKey: 'tipoLesion',
				isAutocomplete: true,
				autocompleteUrl: 'ajax/name-tag.php',
				label: 'Seleccion el tipo de lesi&oacute;n'
			}
		},
		imageWrapBox: {
			showTagList: false
		},
		allTags: 'tagLesiones',
		delTags: 'delTagLesiones',
		edTags:  'editTagLesiones'
	});
	
	$('#nombre_paciente').change(setClaveExpediente);
	$('#ap_paterno_paciente').change(setClaveExpediente);
	$('#ap_materno_paciente').change(setClaveExpediente);
	$('#fecha_nacimiento').change(setClaveExpediente);
	$('#fecha_nacimiento').change(calcular_edad);
	$('#sexo').change(setClaveExpediente);
	$('#edoNac').change(setClaveExpediente);
	
	$('#fecha_notificacion').change(function(e) {
		getSemanaEpidemiologica('fecha_notificacion','semana_notificacion');
	});
	
	
	no_caso_relacionado = $('#casoRelacionados div').size();
	no_caso_relacionado++;

	$('#casoRelacionados div').each(function() {
		$(this).find('.delCasoRelacionado').click(function(){ 
			$('#del_casos_relacionados').val( $(this).parent().find('input[type=hidden]').val() +','+ $('#del_casos_relacionados').val() );
			$(this).parent().remove(); 
		});
	});
	
	agregaCasoRelacionado();
	
	
	no_contactos = $('#contactos div').size();
	no_contactos++;

	$('#contactos div').each(function() {
		$(this).find('.delContacto').click(function(){ 
			$('#del_contactos').val( $(this).parent().find('input[type=hidden]').val() +','+ $('#del_contactos').val() );
			$(this).parent().remove(); 
		});
	});
		
	agregaContacto();
	
	$('#ojo_izq').change(setGradoDiscGeneral);
	$('#mano_izq').change(setGradoDiscGeneral);
	$('#pie_izq').change(setGradoDiscGeneral);
	$('#ojo_der').change(setGradoDiscGeneral);
	$('#mano_der').change(setGradoDiscGeneral);
	$('#pie_der').change(setGradoDiscGeneral);
	
	setGradoDiscGeneral();
	
	setValidacion('capturaPaciente');
	
	setupCalendario("fecha_nacimiento");
	setupCalendario("fecha_padecimiento");
	setupCalendario("fecha_notificacion");
	setupCalendario("fecha_diagnostico");
	setupCalendario("fecha_pqt");
	setupCalendario("tipo_uno");
	setupCalendario("tipo_dos");
    
    
    $('#reaccional_anterior').change(function(){
        showEdoRecTipo2($(this).val(), 'edoRecAntTipo2');
    });
    
    $('#reaccional_actual').change(function(){
        showEdoRecTipo2($(this).val(), 'edoRecActTipo2');
    });
    
    if(getQuerystring('saved') == 'true') {
        jAlert('<img src="images/ok.gif" > <strong>Datos guardados exitosamente</strong>', 'Datos guardados correctamente');
    }
    
    // en caso de ser una primera captura o el caso es sospechoso|descartado 
    // solo se debe mostrar la primera fase del proceso
    if(!getQuerystring('id') || getQuerystring('id')=='') {
        $('#fs_grado_discapacidad').hide();
        $('#fs_aquirio_enfermedad').hide();
        $('#fs_casos_relacionados').hide();
        $('#fs_contactos').hide();
        $('#fs_antecedentes').hide();
        $('#fs_observaciones').hide();
        $('#fecha_notificacion').parent().hide();
        $('#btnCal-fecha_notificacion').hide();
        $('#semana_notificacion').parent().hide();
        $('#fecha_pqt').parent().hide();
        $('#btnCal-fecha_pqt').hide();
        $('#reaccional_anterior').removeClass('validate[required]');
        $('#reaccional_actual').removeClass('validate[required]');
        $('#fecha_notificacion').removeClass('validate[required]');
        $('#fecha_pqt').removeClass('validate[required]');
    } else if($('#tipo_paciente').val()!=5 && $('#tipo_paciente').val()!=6) {
        $('#fs_grado_discapacidad').show();
        $('#fs_aquirio_enfermedad').show();
        $('#fs_casos_relacionados').show();
        $('#fs_contactos').show();
        $('#fs_antecedentes').show();
        $('#fs_observaciones').show();
        $('#fecha_notificacion').parent().show();
        $('#btnCal-fecha_notificacion').show();
        $('#semana_notificacion').parent().show();
        $('#fecha_pqt').parent().show();
        $('#btnCal-fecha_pqt').show();
        
        //Si es la fase 2 de captura, estado reaccional anterior y actual deben ser obligatorios
        if( !$('#reaccional_anterior').hasClass('validate[required]') )
            $('#reaccional_anterior').addClass('validate[required]');
        
        if( !$('#reaccional_actual').hasClass('validate[required]') )
            $('#reaccional_actual').addClass('validate[required]');
        
        if( !$('#fecha_notificacion').hasClass('validate[required]') )
            $('#fecha_notificacion').addClass('validate[required]');
        
        /*if( !$('#fecha_pqt').hasClass('validate[required]') )
            $('#fecha_pqt').addClass('validate[required]');*/
    } else {
        $('#fs_grado_discapacidad').hide();
        $('#fs_aquirio_enfermedad').hide();
        $('#fs_casos_relacionados').hide();
        $('#fs_contactos').hide();
        $('#fs_antecedentes').hide();
        $('#fs_observaciones').hide();
        $('#fecha_notificacion').parent().hide();
        $('#btnCal-fecha_notificacion').hide();
        $('#semana_notificacion').parent().hide();
        $('#fecha_pqt').parent().hide();
        $('#btnCal-fecha_pqt').hide();
        $('#reaccional_anterior').removeClass('validate[required]');
        $('#reaccional_actual').removeClass('validate[required]');
        $('#fecha_notificacion').removeClass('validate[required]');
        $('#fecha_pqt').removeClass('validate[required]');
    }
    
    // Si el paciente es distinto de sospechoso o descartado, captura el resto de datos
    $('#tipo_paciente').change(function(){
        if($(this).val() == 5 || $(this).val() == 6 || $(this).val() == 0){
            $('#fs_grado_discapacidad').hide();
            $('#fs_aquirio_enfermedad').hide();
            $('#fs_casos_relacionados').hide();
            $('#fs_contactos').hide();
            $('#fs_antecedentes').hide();
            $('#fs_observaciones').hide();
            $('#fecha_notificacion').parent().hide();
            $('#btnCal-fecha_notificacion').hide();
            $('#semana_notificacion').parent().hide();
            $('#fecha_pqt').parent().hide();
            $('#btnCal-fecha_pqt').hide();
            $('#reaccional_anterior').removeClass('validate[required]');
            $('#reaccional_actual').removeClass('validate[required]');
            $('#fecha_notificacion').removeClass('validate[required]');
            $('#fecha_pqt').removeClass('validate[required]');
        } else {
            $('#fs_grado_discapacidad').show();
            $('#fs_aquirio_enfermedad').show();
            $('#fs_casos_relacionados').show();
            $('#fs_contactos').show();
            $('#fs_antecedentes').show();
            $('#fs_observaciones').show();
            $('#fecha_notificacion').parent().show();
            $('#btnCal-fecha_notificacion').show();
            $('#semana_notificacion').parent().show();
            $('#fecha_pqt').parent().show();
            $('#btnCal-fecha_pqt').show();
            
            // si ya esta capturado, al cambiar el estado del paciente a nuevo,
            // envia a la captura de fecha notificacion
            if($('#clave_expediente').val().length >= 17) {
                $('#fecha_notificacion').focus();
                $('html, body').stop().animate({ scrollTop: $('#segundaFaseCaptura').offset().top }, 1000);  
            }
            
            //Si es la fase 2 de captura, estado reaccional anterior y actual deben ser obligatorios
            if( !$('#reaccional_anterior').hasClass('validate[required]') )
                $('#reaccional_anterior').addClass('validate[required]');

            if( !$('#reaccional_actual').hasClass('validate[required]') )
                $('#reaccional_actual').addClass('validate[required]');
            
            if( !$('#fecha_notificacion').hasClass('validate[required]') )
                $('#fecha_notificacion').addClass('validate[required]');

            /*if( !$('#fecha_pqt').hasClass('validate[required]') )
                $('#fecha_pqt').addClass('validate[required]');*/
        }
        
    });
    
    /*fecha_nacimiento
    fecha_padecimiento
    fecha_notificacion
    fecha_diagnostico
    fecha_pqt*/
    
    $('#fecha_padecimiento').change(function(){
		var curDate = new Date();
		var dd = curDate.getDate();
		var mm = curDate.getMonth()+1; //January is 0!
		var yyyy = curDate.getFullYear();
		var todayDate = dd+'-'+mm+'-'+yyyy;
		
        if(!validateFecha('fecha_padecimiento', '>', 'fecha_nacimiento')) {
            alert('ERROR: La fecha de inicio de padecimientos debe ser mayor a la fecha de nacimiento');
            $(this).val('');
            $(this).focus();
        }
        
        if(!validateFecha('fecha_padecimiento', '<=', todayDate)) {
            alert('ERROR: La fecha de inicio de padecimientos no debe ser posterior a la fecha actual');
            $(this).val('');
            $(this).focus();
        }
    });
    
    $('#fecha_diagnostico').change(function(){
        if(!validateFecha('fecha_diagnostico', '>=', 'fecha_padecimiento')) {
            alert('ERROR: La fecha de Dx clinico debe ser mayor a la fecha de inicio de padecimientos');
            $(this).val('');
            $(this).focus();
        }
    });
    
    $('#fecha_notificacion').change(function(){
        if(!validateFecha('fecha_notificacion', '>=', 'fecha_diagnostico')) {
            alert('ERROR: La fecha de notificacion debe ser mayor o igual a la fecha de Dx clinico');
            $(this).val('');
            $(this).focus();
        }
    });
    
    $('#fecha_pqt').change(function(){
        if(!validateFecha('fecha_pqt', '>', 'fecha_notificacion')) {
            alert('ERROR: La fecha de inicio de la PQT debe ser mayor a la fecha de notificacion');
            $(this).val('');
            $(this).focus();
        }
    });

    // Funcion para mostrar las fotos
    $("#dialog_form").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 600,
        hide: "fadeOut",
        open: function()
        {
            var id= $("#idLesion").val();
            //alert(foo);
            $(this).parent().css("overflow", "visible");

            // Solo mostrar si se han registrado fotos
            if($('#my-slideshow_'+id+' .bjqs li').length > 0) {
                $('#my-slideshow_'+id).bjqs({
                    'height' : 320,
                    'width' : 620,
                    'responsive' : true
                });
            }
        },
        close: function()
        {
            $("#dialog_form").empty();
        }
    });
    
});
