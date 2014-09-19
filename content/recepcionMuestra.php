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
	
	$("#winRecepcionMuestra").dialog({
		autoOpen: false,
		height: 300,
		width: 380,
		modal: true,
		resizable: true
	});
    
    setupCalendario("fecha_recepcion");
});

function recibirMuestra(id, tipo, folio){
    $('#id_estudio').val(id);
    $('#tipo_estudio').val(tipo);
    $('#folio_solicitud').text(folio);
    
    $('#folio_laboratorio').val('');
    $('#fecha_recepcion').val('');
    $('#rechazo_muestra').removeAttr('checked');
    $('#rechazo_muestra').parent().removeClass('checked'); // Workless
    $('#criterio_rechazo option:first').attr("selected",true);
    $('#criterio_rechazo').parent().find('span').text("Elegir"); // Workless
    $('#otro_criterio_rechazo').val('');
    
    $("#winRecepcionMuestra").dialog('open');
}

function procesarMuestra(){
    $.ajax({
		type: "POST",
        dataType: 'json',
		url: 'ajax/recepcionMuestra.php',
		data: $('#formProcesarMuestra').serialize(),
		success: function(respuesta)
		{
            alert(respuesta.msj);
            
            if(!respuesta.error)
                location.reload();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
				'o Notifiquelo con el administrador', 'Error al procesar los datos...');
		}
	});
}
</script>

<?php
require_once('include/clasesLepra.php');
require_once('include/fecha_hora.php');

$listado = new ListGeneric();
$objHTML = new HTML();
$objSelects = new Select();

echo '<div id="winRecepcionMuestra" title="Recepci&oacute;n de Muestra">';

    $objHTML->startForm('formProcesarMuestra', '#', 'POST');
        
        $objHTML->inputHidden('id_estudio');
        $objHTML->inputHidden('tipo_estudio');
        echo '<label><strong>Folio Solicitud: <u> &nbsp; <span id="folio_solicitud"></span> &nbsp; </u></strong></label><br>';
        $objHTML->inputText('Folio Laboratorio: ', 'folio_laboratorio', '', array('placeholder'=>'Clave LESP', 'maxlength'=>'10'));
        echo '<br>';
        $objHTML->inputText('Fecha Recepción: ', 'fecha_recepcion');
        echo '<br>';
        $objHTML->inputCheckbox('Rechazo Muestra', 'rechazo_muestra', 1);
        echo '<br>';
        $objSelects->SelectCatalogo('Criterio Rechazo', 'criterio_rechazo', 'catMotivoRechazo');
        echo '<br>';
        $objHTML->inputText('Otro Criterio de Rechazo', 'otro_criterio_rechazo', '', array('size'=>40));
        
        //include_once('content/controlCalidadMuestra.php');
        
        echo '<br><br><div align="center">';
        $objHTML->inputButton('btnRecibeMuestra', 'Procesar Muestra', array('onClick'=>'procesarMuestra()'));
        echo '</div>';

    $objHTML->endFormOnly();
    
echo '</div>';


$objHTML->startFieldset('Solicitudes Pendientes');

$recepcionMuestra = array_merge($listado->getRecepMuestraBac(), $listado->getRecepMuestraHis());

echo '<div class="datagrid">
	<table align="center">
	<thead>
            <tr align="center">
                <th>Folio Solicitud</th>	
                <th>Clave Del Paciente</th>
                <th>Nombre</th>
                <th>Solicitante</th>
                <th>Fecha Muestreo</th>
                <th>Fecha Solicitud</th>
                <th>Tipo</th>
                <th>Estudio</th>
                <th>Recepci&oacute;n</th>
            </tr>
            </thead>
            <tbody>';

	foreach($recepcionMuestra as $pendiente){
            echo '<tr>
                    <td align="center">'.$pendiente['folio_solicitud'].'</td>	
                    <td>'.$pendiente['clave_paciente'].'</td>	
                    <td>'.htmlentities($pendiente['nombre']).'</td>
                    <td>'.htmlentities($pendiente['solicitante']).'</td>
                    <td>'.$pendiente['fecha_muestreo'].'</td>
                    <td>'.$pendiente['fecha_solicitud'].'</td>
                    <td>'.htmlentities(utf8_decode($pendiente['tipo_analisis'])).'</td>
                    <td>'.htmlentities($pendiente['estudio']).'</td>
                    <td align="center"><a href="javascript:recibirMuestra('.$pendiente['id'].',\''.$pendiente['tipo'].'\', \''.$pendiente['folio_solicitud'].'\')"><img src="images/verLab.gif" border="0"/></a></td>
            </tr>';
	}
    
echo '</tbdy></table></div>';

$objHTML->endFieldset();

?>