$(function() {
	$(document).on('focusin', '.field, textarea', function() {
		if(this.title==this.value) {
			this.value = '';
		}
	}).on('focusout', '.field, textarea', function(){
		if(this.value=='') {
			this.value = this.title;
		}
	});

	$('#navigation ul li:first-child').addClass('first');
	$('.footer-nav ul li:first-child').addClass('first');

	$('#navigation a.nav-btn').click(function(){
		$(this).closest('#navigation').find('ul').slideToggle();
		$(this).find('span').toggleClass('active');
		return false;
	});
});

$(document).ready(function(){
	$('form').on('reset', function() { 
		$('.selector span').text('Elegir');
		$('.checker span').removeClass('checked');
		$('.radio span').removeClass('checked');
	});
    
    // Convertir a mayusculas
    $('input, textarea').change(function() { 
        if($(this).attr('id')=='usr' || $(this).attr('id')=='pass' || $(this).data('mayus')==false)
            return false;
        
		$(this).val( normalize($(this).val()).toUpperCase() );
	});
});

/*************************************************************/
/* array parametros {tipo:'', destino:'', edo:'', juris:'', muni:'', locali:'', insti:''} */
function actualiza_select(parametros)
{
    reset_select(parametros.destino);
	$('#'+parametros.destino).css('background', 'url(images/loading.gif) no-repeat center');
	// Parche para el style de los selects
	$('#uniform-'+parametros.destino+' span').css('background', 'url(images/loading.gif) no-repeat center');
	
	$('select#'+parametros.destino+' option:first').text('Espere... Cargando Datos...!!!');
	
	parametro_envia='tipo='+parametros.tipo+
					'&edo='+$('#'+parametros.edo).val()+
					'&juris='+$('#'+parametros.juris).val()+
					'&muni='+$('#'+parametros.muni).val();
	$.ajax({
		async: true,
		type: "POST",
		url: "ajax/select.php",
		data: parametro_envia,
		success: function(respuesta) 
		{
			// Elimina todas las opciones del select destino
			$('#'+parametros.destino).find('option').remove().end();
			
			// Recibe una cadena de texto con los pares de datos valor1=texto1@valor2=texto2@...@valorN=textoN@
			valores = respuesta.split('@');
			
			for(indice in valores)
			{
				option = valores[indice];
				option = option.split('=');
				
				// Agrega option al select destino
				$('#'+parametros.destino).append('<option value="'+option[0]+'">'+option[1]+'</option>');
			}
			
			$('#'+parametros.destino).prepend('<option value="">Elegir</option>');
			$('#'+parametros.destino).removeAttr('style'); // Eliminamos la imagen gif de cargando
			// Parche para el style de los selects
			$('#uniform-'+parametros.destino+' span').removeAttr('style');
			$('select#'+parametros.destino+' option:first').attr("selected",true);//$('#'+parametros.destino).get(0).selectedIndex = 0; // Seleccionamos la primera opcion
			$('select#'+parametros.destino+' option:last').remove(); // Eliminamos el ultimo option devido a que es NULL
			
			// Parche para el style de los selects
			$('#uniform-'+parametros.destino+' span').text('Elegir');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
				'o Notifiquelo con el administrador', 'Error al procesar los datos...');
		}
	});
}

/*************************************************************/
function reset_select(id_select)
{
	$('#'+id_select).find('option').remove().end();
	$('#'+id_select).prepend('<option value="">Elegir</option>');
	// Parche para el style de los selects
	$('#uniform-'+id_select+' span').text('Elegir');
}

/*************************************************************/
/*function validateForm(idForm) {
	$("#"+idForm).validationEngine({
		onValidationComplete: function(form, status) {
			if(status == true) {
				jConfirm('&iquest;Esta seguro que todos los datos son correctos?', 'Enviar Datos', function(respuesta) {
					if(respuesta == true) document.getElementById(idForm).submit();
				});
			}
		}
	});
	
	$("#"+idForm).bind("jqv.form.result", function(event, errorFound) {
		if(errorFound) 
			jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Tiene un error en los datos de entrada, porfavor verifique su informaci&oacute;n',
			'Error al procesar los datos...');
	});
}*/

/*************************************************************/
function set_reloj()
{
	objHora = null;
	
	// Obtiene la hora del servidor
	$.ajax({
		async: false,
		type: "POST",
		url: "ajax/hora.php",
		success: function(respuesta) {
			date_server = respuesta.split(':');
			//new Date(aÃ±o, mes, dÃ­a, horas, minutos, segundos);
			objHora = new Date(date_server[0], date_server[1], date_server[2], date_server[3], date_server[4], date_server[5]);
		}
	});
}

/*************************************************************/
function reloj()
{
	var am_pm = '';
	
	objHora.setTime(objHora.getTime()+1000);

	var horas = objHora.getHours();
	var minutos = objHora.getMinutes();
	var segundos = objHora.getSeconds();
	
	if(horas > 12) 
	{
		horas = horas-12;
		am_pm = 'pm';
	}
	else
		am_pm = 'am';
	
	if(horas < 10) { horas = '0' + horas; }
	if(minutos < 10) { minutos = '0' + minutos; }
	if(segundos < 10) { segundos = '0' + segundos; }
	
	document.getElementById("reloj").innerHTML = horas+':'+minutos+':'+segundos+' '+am_pm;
}

/*************************************************************/
function getQuerystring(key, default_)
{
    if (default_==null)
    {
        default_="";
    }
    var search = unescape(location.search);
    if (search == "")
    {
        return default_;
    }
    search = search.substr(1);
    var params = search.split("&");
    for (var i=0; i<params.length; i++)
    {
        var pairs = params[i].split("=");
        if(pairs[0] == key)
        {
            return pairs[1];
        }
    }
    return default_;
}

/*************************************************************/
function onlyNumber(clase) {
	var numeros = /[0-9]*/;
	
	$('.'+clase).keyup(function(event) {
		$(this).val( $(this).val().match(numeros) );
	});
}

/*************************************************************/
function setupPaginador(idTable) {
	var options = {
	  firstArrow : (new Image()).src="./images/firstBlue.gif",
	  prevArrow : (new Image()).src="./images/prevBlue.gif",
	  lastArrow : (new Image()).src="./images/lastBlue.gif",
	  nextArrow : (new Image()).src="./images/nextBlue.gif",
	  rowsPerPage : 20
	};
	
	$('#'+idTable).tablePagination(options);
}

/*************************************************************/
function setupCalendario(campo)
{
    if($('#'+campo).length != 0) {
        $('#'+campo).parent().after('<a name="btnCal-'+campo+'" id="btnCal-'+campo+'" style="cursor:pointer;"><img align="absmiddle" src="images/icon_calendario.jpg"></a>');

        var myCalendar = Calendar.setup({
            inputField : campo,
            trigger    : 'btnCal-'+campo,
            weekNumbers: true,
            fdow       : 7,
            showTime   : false,
            onSelect   : function() { this.hide(); document.getElementById(campo).focus(); $('#'+campo).change();},
            dateFormat : "%d-%m-%Y"
        });

        if(!$('#'+campo).hasClass('fecha'))
            $('#'+campo).addClass('fecha');

        $('#'+campo).attr('placeholder','DD-MM-AAAA');

        $('#'+campo).keyup(function(event){
            tipo_fecha(this,event);
        });
    }
}

/*************************************************************/
function tipo_fecha(obj,key)
{ //tipo fecha formato DD-MM-AAAA
	var expresion = /[0-9-]*/;
	var evt = key ? key : event;
    var tecla = window.Event ? evt.which : evt.keyCode; // 8 = tecla Del
    
    //console.clear();
    //console.log(tecla+' - '+obj.value);
    
    if(tecla==37 || tecla==38 || tecla==39 || tecla==40 || tecla==27 || tecla==46) { //las teclas de direccion, esc, del y sup
        return false;
    }
    
    if(tecla==109) { //si escribe un '-'
        obj.value = obj.value.substr(0, obj.value.length-1);
        return false;
    }
        
    
	if( (obj.value.length==2 || obj.value.length==5) && tecla!=8 && tecla!=109) // para agregar el '-'
		obj.value = obj.value.match(expresion)+"-";
	else if (tecla!=8) // para diferenciar la tecla de retroceso
		obj.value = obj.value.match(expresion);
	
	obj.value = obj.value.substr(0, 10);
    
    if (obj.value.length == 10) {
        if( !isDate(obj.value) ) {
            alert('ERROR: La fecha porporcionada no es valida...');
            obj.focus();
        }
    }
        
}

/*************************************************************/
function testConexion() {
	$.ajax({
		url: "ajax/testConexion.php",
		success: function(respuesta)
		{
			if(respuesta != 'ok')
				jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. No hay conexion con el servidor. Notifiquelo con el administrador', 'Error. No hay conexion con el servidor...');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. No hay conexion con el servidor. Notifiquelo con el administrador', 'Error. No hay conexion con el servidor...');
		}
	});
}

/*************************************************************/
function removeLoading() {
	$('#div_loading').fadeOut(1000);
}

/*************************************************************/
function testIE() {
	if(jQuery.browser.msie == true) {
		jAlert( 'Esta utilizando el navegador <u>Internet Explorer</u> y <strong>NO es compatible</strong> con el sistema, se recomienda utilizar: '+
				'<div align="center"><br /><a href="https://www.google.com/chrome?hl=es" target="_blank" style="text-decoration:none"><img src="images/chrome.png" style="vertical-align:middle;border:0px;"/> Google Chrome</a> <br /> &oacute;'+
				'<br /><a href="http://www.mozilla.org/es-MX/firefox/new/" target="_blank" style="text-decoration:none"><img src="images/firefox.png" style="vertical-align:middle;border:0px;"/> Mozilla Firefox</a> <br /></div>', 'Navegador NO compatible...');
	}
}

/*************************************************************/
normalize = (function() {
  var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑ'\"",
      to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuuÑ",
      mapping = {};
 
  for(var i = 0, j = from.length; i < j; i++ )
      mapping[ from.charAt( i ) ] = to.charAt( i );
 
  return function( str ) {
      if(typeof(str) != "undefined"){
		  var ret = [];
		  for( var i = 0, j = str.length; i < j; i++ ) {
			  var c = str.charAt( i );
			  if( mapping.hasOwnProperty( str.charAt( i ) ) )
				  ret.push( mapping[ c ] );
			  else
				  ret.push( c );
		  }
		  return ret.join( '' );
	  }
	  return '';
  };
  
})();

/*************************************************************/
function doClaveExpediente(parametros){
	cveExpediente = '';
	
	dlApePaterno = normalize($('#'+parametros.apePaterno).val()).replace(/\s/g,'').substring(0,2).toUpperCase();
	plApeMaterno = normalize($('#'+parametros.apeMaterno).val()).replace(/\s/g,'').substr(0,1).toUpperCase();
	plNombre = normalize($('#'+parametros.nombre).val()).replace(/\s/g,'').substr(0,1).toUpperCase();
	
	strFechaNac = $('#'+parametros.fechaNac).val().split('-');
	
	if(strFechaNac.length == 3)
		strFechaNac = strFechaNac[2].substring(2,4) + strFechaNac[1] + strFechaNac[0];
	
	// Eliminar primera letra
	vocales = /[a|e|i|o|u]/gi;
	pConsApePaterno = normalize($('#'+parametros.apePaterno).val()).substr(1).replace(/\s/g,'').replace(vocales,'').substr(0,1).toUpperCase();
	pConsApeMaterno = normalize($('#'+parametros.apeMaterno).val()).substr(1).replace(/\s/g,'').replace(vocales,'').substr(0,1).toUpperCase();
	pConsNombre = normalize($('#'+parametros.nombre).val()).replace(/\s/g,'').substr(1).replace(vocales,'').substr(0,1).toUpperCase();
	
	arraySexo = new Array('');
	arraySexo[''] = '';
	arraySexo[1] = 'M';
	arraySexo[2] = 'F';
	
	lSexo = arraySexo[$('#'+parametros.sexo).val()];
	
	cveAlfaEdoNac = $('#'+parametros.edoNac).val();
	
	$.ajax({
		async: false,
		type: "POST",
		url: "ajax/getClaveEdo.php?edo="+cveAlfaEdoNac,
		success: function(respuesta) {
			cveAlfaEdoNac = respuesta;
		}
	});
	
	index = 1;
	
	cveExpediente = dlApePaterno + plApeMaterno + plNombre + strFechaNac + lSexo + cveAlfaEdoNac + pConsApePaterno + pConsApeMaterno + pConsNombre;
	
	$.ajax({
		async: false,
		type: "POST",
		url: "ajax/getIndexCveExpediente.php?cve="+cveExpediente,
		success: function(respuesta) {
			index = respuesta;
		}
	});
	
	cveExpediente = cveExpediente + index;
	
	$('#'+parametros.destino).val(cveExpediente);
}

/*************************************************************/
function getSemanaEpidemiologica(campo_fecha,campo_semana){
	$.ajax({
		type: "POST",
		url: "ajax/getSemanaEpidemiologica.php",
		data: 'fecha='+$('#'+campo_fecha).val(),
		success: function(datos)
		{
			$('#'+campo_semana).val(datos);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
				'o Notifiquelo con el administrador', 'Error al procesar los datos...');
		}
	});
}

function setValidacion(formulario) {
	$("#"+formulario).validationEngine( { validationEventTrigger: 'submit', onValidationComplete: function(form, status) {
        
        //console.log($("#"+formulario).validationEngine().InvalidFields);
		if(status){
            //$("#"+formulario).validationEngine();
			if(confirm('Esta seguro que todos los datos son correctos')) {
				//console.log("#"+formulario+" :submit");
				//console.log(form);
				//$("#"+formulario+" :submit").click();
                
                // eliminar el artibuto disabled de todos los campos del formulario
                $('#'+formulario+' input, '+
                  '#'+formulario+' textarea, '+
                  '#'+formulario+' select, '+
                  '#'+formulario+' radio, '+
                  '#'+formulario+' checkbox').each(function(){
                      $(this).removeAttr('disabled');
                });

                $('body').prepend('<div id="div_loading"> &nbsp; </div>');
                $('html, body').animate({scrollTop : 0},800);
				document.getElementById(formulario).submit();
			}
		}
		else{
			alert('ERROR revise sus datos... Datos Incompletos');
        }
    }, "onFailure": function() {
        alert('ERROR revise sus datos... Datos Incompletos');//console.log(this);
    }});
}


// Fuente: http://jquerybyexample.blogspot.com/2011/12/validate-date-using-jquery.html
function isDate(txtDate)
{
  //console.log(txtDate);
  var currVal = txtDate;
  if(currVal == '')
    return false;
  
  //Declare Regex  
  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; 
  var dtArray = currVal.match(rxDatePattern); // is format OK?

  if (dtArray == null)
     return false;
 
 //console.log(dtArray);
 
  //Checks for dd/mm/yyyy format.
  dtDay = dtArray[1];
  dtMonth = dtArray[3];
  dtYear = dtArray[5];

  if (dtMonth < 1 || dtMonth > 12)
      return false;
  else if (dtDay < 1 || dtDay> 31)
      return false;
  else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return false;
  else if (dtMonth == 2)
  {
     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
     if (dtDay> 29 || (dtDay ==29 && !isleap))
          return false;
  }
  return true;
}

/*************************************************************/
function getInstitucionFromUni(uni,insti){
	$.ajax({
		type: "POST",
		url: "ajax/getInstitucionFromUni.php",
		data: 'uni='+$('#'+uni).val(),
		success: function(datos)
		{
			$('#'+insti+' option[value='+datos+']').attr('selected',true);
            $('#'+insti).parent().find('span').text( $('#'+insti).find('option:selected').text() );
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
				'o Notifiquelo con el administrador', 'Error al procesar los datos...');
		}
	});
}

/*************************************************************/
function validateFecha(fechaX, operador, fechaY, diferencia){
    var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; 
    var diferencia = diferencia || null;
    
    // format dd-mm-yyyy
    if(rxDatePattern.test(fechaX)) valFechaX = fechaX;
    else                           valFechaX = $('#'+fechaX).val();
    
    if(rxDatePattern.test(fechaY)) valFechaY = fechaY;
    else                           valFechaY = $('#'+fechaY).val();
    
    if(!isDate(valFechaX)) {
        console.error('La fecha "'+fechaX+': '+valFechaX+'" no es válida.');
        //$('#'+fechaX).focus();
        return false;
    }
    
    if(!isDate(valFechaY)) {
        console.error('La fecha "'+fechaY+': '+valFechaY+'" no es válida.');
        //$('#'+fechaY).focus();
        return false;
    }
    valFechaX = valFechaX.split("-");
    valFechaY = valFechaY.split("-");
    
    // Ajuste, Enero es considerado como el mes 0
    objFechaX = new Date(valFechaX[2],valFechaX[1]-1,valFechaX[0]);
    objFechaY = new Date(valFechaY[2],valFechaY[1]-1,valFechaY[0]);
    
    /* daysDiff
     * objFechaX > objFechaY = +
     * objFechaX < objFechaY = -
     */
    daysDiff = parseInt( (objFechaX.getTime()-objFechaY.getTime()) / (24*60*60*1000) );
    //console.log(objFechaX.toLocaleDateString()+' - '+objFechaY.toLocaleDateString()+' - '+daysDiff);
    
    switch(operador) {
        case '=': // Cero
            if(daysDiff==0) return true;
            else            return false;
            break;
        
        case '>': // Positivo
            if(diferencia != null){
                if(daysDiff == diferencia) return true;
                else                       return false;
            } else {
                if(daysDiff > 0) return true;
                else             return false;
            }
            break;
        
        case '<': // Negativo
            if(diferencia != null){
                if(daysDiff == (-1*diferencia)) return true;
                else                            return false;
            } else {
                if(daysDiff < 0) return true;
                else             return false;
            }
            break;
        
        case '!=': // Positivo/Negativo
            if(daysDiff != 0) return true;
            else              return false;
            break;
        
        case '<=': // Negativo
            if(diferencia != null){
                if(daysDiff <= (-1*diferencia)) return true;
                else                            return false;
            } else {
                if(daysDiff <= 0) return true;
                else              return false;
            }
            break;
            
        case '>=': // Positivo
            if(diferencia != null){
                if(daysDiff >= diferencia) return true;
                else                       return false;
            } else {
                if(daysDiff >= 0) return true;
                else              return false;
            }
            break;
    }
}

function exportToExcel(){
    
}

// eliminar el artibuto disabled de todos los campos del formulario
function remDisabled(formulario) {
    $('#'+formulario+' input, '+
      '#'+formulario+' textarea, '+
      '#'+formulario+' select, '+
      '#'+formulario+' radio, '+
      '#'+formulario+' checkbox').each(function(){
          $(this).removeAttr('disabled');
    });
}

// Recibe una fecha con formato dd-mm-yy y la convierte a formato yy-mm-dd
function formatFecha(fecha) {
    valFecha = fecha.split("-");

    return valFecha[2]+'-'+valFecha[1]+'-'+valFecha[0];
}