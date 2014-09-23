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
    $('span#folio_solicitud').text(folio);
    
    $('#formProcesarMuestra #folio_laboratorio').val('');
    $('#fecha_recepcion').val('');
    $('#rechazo_muestra').removeAttr('checked');
    $('#rechazo_muestra').parent().removeClass('checked'); // Workless
    $('#criterio_rechazo option:first').attr("selected",true);
    $('#criterio_rechazo').parent().find('span').text("Elegir"); // Workless
    $('#otro_criterio_rechazo').val('');
    
    getDatosMuestra(id, tipo);
    
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

function getDatosMuestra(id, tipo){
    $.ajax({
		type: "POST",
        dataType: 'json',
		url: 'ajax/getDatosMuestra.php',
		data: 'id='+id+'&tipo='+tipo,
		success: function(respuesta)
		{
            if(!respuesta.error) {
                $('#formProcesarMuestra #folio_laboratorio').val(respuesta.folioLaboratorio);
                $('#fecha_recepcion').val(respuesta.fechaRecepcion);
                
                if(respuesta.muestraRechazada && respuesta.idCatMotivoRechazo != 0) {
                    $('#rechazo_muestra').attr('checked', true);
                    $('#rechazo_muestra').parent().addClass('checked');
                }
                
                if(respuesta.idCatMotivoRechazo) {
                    $('#criterio_rechazo option[value='+respuesta.idCatMotivoRechazo+']').attr("selected",true);
                    $('#criterio_rechazo').parent().find('span').text( $('#criterio_rechazo option[value='+respuesta.idCatMotivoRechazo+']').text() ); // Workless
                }
                
                $('#otro_criterio_rechazo').val(respuesta.otroMotivoRechazo);
            }
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
require_once('include/clases/Helpers.php');
require_once('include/clases/BusquedaEstudios.php');

$listado = new ListGeneric();
$objHTML = new HTML();
$objSelects = new Select();
$help = new Helpers();

if(isset($_GET['p']))
	$busqueda->page = $_GET['p'];
	
if(empty($_POST['edoCaso'])) 
    $_POST['edoCaso'] = $_SESSION[EDO_USR_SESSION];

echo '<div id="winRecepcionMuestra" title="Recepci&oacute;n de Muestra">';

    $objHTML->startForm('formProcesarMuestra', '#', 'POST');
        
        $objHTML->inputHidden('id_estudio');
        $objHTML->inputHidden('tipo_estudio');
        echo '<label><strong>Folio Solicitud: <u> &nbsp; <span id="folio_solicitud"></span> &nbsp; </u><strong></label><br>';
        $objHTML->inputText('Folio Laboratorio: ', 'folio_laboratorio', '', array('placeholder'=>'Clave LESP', 'maxlength'=>'10'));
        echo '<br>';
        $objHTML->inputText('Fecha Recepción: ', 'fecha_recepcion');
        echo '<br>';
        $objHTML->inputCheckbox('Rechazo Muestra', 'rechazo_muestra', 1);
        echo '<br>';
        $objSelects->SelectCatalogo('Criterio Rechazo', 'criterio_rechazo', 'catMotivoRechazo');
        echo '<br>';
        $objHTML->inputText('Otro Criterio de Rechazo', 'otro_criterio_rechazo', '', array('size'=>40));
        echo '<br><br><div align="center">';
        $objHTML->inputButton('btnRecibeMuestra', 'Procesar Muestra', array('onClick'=>'procesarMuestra()'));
        echo '</div>';

    $objHTML->endFormOnly();
    
echo '</div>';

$objHTML->startForm('form_busca', '?mod=recepBus', 'POST');

$objHTML->startFieldset();

    $objSelects->selectEstado('edoCaso', isset($_POST['edoCaso']) ? $_POST['edoCaso'] : $_SESSION[EDO_USR_SESSION], $_SESSION[EDO_USR_SESSION]==0 ? array() : array('disabled'=>'disabled') );

	$objHTML->inputText('Folio Solicitud: ', 'folio_solicitud');
	$objHTML->inputText('Folio Laboratorio: ', 'folio_laboratorio');

$objHTML->endFieldset();

$objHTML->endForm('buscar', 'Buscar', 'limpiar', 'Limpiar');

if(isset($_POST['buscar'])) {
    $busqueda = new BusquedaEstudios();
    
    $busqueda->idCatEstado = $_POST['edoCaso'];
	$busqueda->folioLaboratorio = $_POST['folio_laboratorio'];
	$busqueda->folioSolicitud = $_POST['folio_solicitud'];
    
    $busqueda->buscar();
    
    if(!empty($busqueda->resultado)) {
        echo '<br><br><div class="datagrid">
        <table>
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

        foreach($busqueda->resultado as $estudio){
            echo '<tr>
                <td>'.$estudio->folioSolicitud.'</td>	
                <td>'.$estudio->clavePaciente.'</td>	
                <td>'.$estudio->nombre.'</td>
                <td>'.$estudio->solicitante.'</td>
                <td>'.formatFechaObj($estudio->fechaMuestreo).'</td>
                <td>'.formatFechaObj($estudio->fechaSolicitud).'</td>
                <td>'.htmlentities($help->getDescripTipoEstudio($estudio->idCatTipoEstudio)).'</td>
                <td>'.$estudio->estudio.'</td>
                <td align="center"><a href="javascript:recibirMuestra('.$estudio->idEstudio.',\''.$estudio->estudio.'\', \''.$estudio->folioSolicitud.'\')"><img src="images/verLab.gif" border="0"/></a></td>
            </tr>';
        }
		/*echo '
		<tfoot>
			<tr>
				<td colspan="10">
					<div id="paging">
						<ul>';
							//<li><a href="#"><span>Inicio</span></a></li>';
							for($ii = 0; $ii < $busqueda->maxPages; $ii++)
							{
								echo '<li><a href="index.php?mod=bus&p='.($ii+1).'"'; if($_GET['p'] == $ii+1){echo ' class="active" ';} echo '><span>'.($ii+1).'</span></a></li>';
							}
					//echo '  <li><a href="#"><span>Fin</span></a></li>
					echo '	</ul>
					</div>
				</td>
			</tr>
		</tfoot>';*/
    } else {
        echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
    }

    echo '</tbdy></table></div>';
}
?>