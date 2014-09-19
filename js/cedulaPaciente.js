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
                    'edoReferido'
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
                    'actualizar',
                    'guardar',
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
    //if($('#tipo_paciente').val() != 5 && $('#tipo_paciente').val() != 6){
        for(campo in camposCapturaFase2) {
            $('*[name^='+camposCapturaFase2[campo]+']').each(function(){
                $(this).attr('disabled',true);
            });
    //    }
        
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
        
        if( !$('#fecha_pqt').hasClass('validate[required]') )
            $('#fecha_pqt').addClass('validate[required]');
			
		///////////////////////
		$('#reaccional_anterior').attr('disabled',true);
		$('#reaccional_actual').attr('disabled',true);
		$('#fecha_notificacion').attr('disabled',true);
		$('#fecha_pqt').attr('disabled',true);
    }
}


$tagsBody = null;

$(document).ready(function(){
		
	$tagsBody = $('.body').photoTag({
		requestTagsUrl: 'ajax/load-tag.php',
		parametersForRequest: ['id'],
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
		allTags: 'tagLesiones'
	});
	
	deshabilitarCamposCaptura("capturaPaciente");
	
	/*$('#nombre_paciente').change(setClaveExpediente);
	$('#ap_paterno_paciente').change(setClaveExpediente);
	$('#ap_materno_paciente').change(setClaveExpediente);
	$('#fecha_nacimiento').change(setClaveExpediente);
	$('#fecha_nacimiento').change(calcular_edad);
	$('#sexo').change(setClaveExpediente);
	$('#edoNac').change(setClaveExpediente);*/
	
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
	
	//agregaCasoRelacionado();
	
	
	no_contactos = $('#contactos div').size();
	no_contactos++;

	$('#contactos div').each(function() {
		$(this).find('.delContacto').click(function(){ 
			$('#del_contactos').val( $(this).parent().find('input[type=hidden]').val() +','+ $('#del_contactos').val() );
			$(this).parent().remove(); 
		});
	});
		
	//agregaContacto();
	
	/*$('#ojo_izq').change(setGradoDiscGeneral);
	$('#mano_izq').change(setGradoDiscGeneral);
	$('#pie_izq').change(setGradoDiscGeneral);
	$('#ojo_der').change(setGradoDiscGeneral);
	$('#mano_der').change(setGradoDiscGeneral);
	$('#pie_der').change(setGradoDiscGeneral);*/
	
	//setGradoDiscGeneral();
	
	//setValidacion('capturaPaciente');
	
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
    if(!getQuerystring('id')) {
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
        
        if( !$('#fecha_pqt').hasClass('validate[required]') )
            $('#fecha_pqt').addClass('validate[required]');
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

            if( !$('#fecha_pqt').hasClass('validate[required]') )
                $('#fecha_pqt').addClass('validate[required]');
        }
        
    });
    
    /*fecha_nacimiento
    fecha_padecimiento
    fecha_notificacion
    fecha_diagnostico
    fecha_pqt*/
    
    $('#fecha_padecimiento').change(function(){
        if(!validateFecha('fecha_padecimiento', '>', 'fecha_nacimiento')) {
            alert('ERROR: La fecha de inicio de padecimientos debe ser mayor a la fecha de nacimiento');
            $(this).val('');
            $(this).focus();
        }
    });
    
    $('#fecha_diagnostico').change(function(){
        if(!validateFecha('fecha_diagnostico', '>', 'fecha_padecimiento')) {
            alert('ERROR: La fecha de Dx clinico debe ser mayor a la fecha de inicio de padecimientos');
            $(this).val('');
            $(this).focus();
        }
    });
    
    $('#fecha_notificacion').change(function(){
        if(!validateFecha('fecha_notificacion', '>=', 'fecha_diagnostico')) {
            alert('ERROR: La fecha de notificacion debe ser igual o mayor a la fecha de Dx clinico');
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
    
});
