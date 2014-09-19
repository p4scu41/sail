<link rel="stylesheet" href="include/jquery-ui-1.8.14.custom/development-bundle/themes/base/jquery.ui.all.css">

<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/js/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.position.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.14.custom/development-bundle/ui/jquery.ui.dialog.js"></script>

<style type="text/css">div.selector span { max-width: none !important; }</style>

<script type="text/javascript">
    $(document).ready(function() {
        setupCalendario('fecha_inicio');
        setupCalendario('fecha_fin');
        
        $("#winControlCalidad").dialog({
            autoOpen: false,
            height: 800,
            width: 650,
            modal: true,
            resizable: true
        });
    });
    
    function showControlCalidad(tipo, id){
        $('#id_estudio').val(id);
        $('#tipo_estudio').val(tipo);
        
        getControlCalidad(tipo, id);
        
        $("#winControlCalidad").dialog('open');
    }
    
    function getControlCalidad(tipo, id){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'ajax/getControlCalidad.php',
            data: 'id_estudio='+id+'&tipo_estudio='+tipo,
            success: function(respuesta)
            {
                if(!respuesta.error) {                    
                        $('input:radio').each(function(){
                            $(this).removeAttr('checked');
                            $(this).parent().removeClass('checked');
                        });
                        
                        setChecked('calidadMuestra', respuesta.calidadMuestra);
                        setChecked('sinMuestra', respuesta.sinMuestra);
                        setChecked('sinElemeCelu', respuesta.sinElemeCelu);
                        setChecked('abunEritro', respuesta.abunEritro);
                        $('#otrosCalidadMuestra').val(respuesta.otrosCalidadMuestra);

                        setChecked('calidadFrotis', respuesta.calidadFrotis);
                        setSelected('calidadFrotisTipo', respuesta.calidadFrotisTipo);
                        $('#otrosCalidadFrotis').val(respuesta.otrosCalidadFrotis);

                        setChecked('calidadTincion', respuesta.calidadTincion);
                        setChecked('crisFucsi', respuesta.crisFucsi);
                        setChecked('preciFucsi', respuesta.preciFucsi);
                        setChecked('calenExce', respuesta.calenExce);
                        setChecked('decoInsufi', respuesta.decoInsufi);
                        $('#otrosCalidadTincion').val(respuesta.otrosCalidadTincion);

                        setChecked('calidadLectura', respuesta.calidadLectura);
                        setChecked('falPosi', respuesta.falPosi);
                        setChecked('falNega', respuesta.falNega);
                        setChecked('difMas2IB', respuesta.difMas2IB);
                        setChecked('difMas25IM', respuesta.difMas25IM);
                        $('#otrosCalidadLectura').val(respuesta.otrosCalidadLectura);

                        setChecked('calidadResultado', respuesta.calidadResultado);
                        setChecked('soloSimbCruz', respuesta.soloSimbCruz);
                        setChecked('soloPosiNega', respuesta.soloPosiNega);
                        setChecked('noEmiteIM', respuesta.noEmiteIM);
                        $('#otrosCalidadResultado').val(respuesta.otrosCalidadResultado);
                        
                        setSelected('recomendacion', respuesta.recomendacion);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
                    'o Notifiquelo con el administrador', 'Error al procesar los datos...');
            }
        });
    }
    
    function setChecked(id, valor) {
        $('#'+id).each(function(){
            $(this).removeAttr('checked');
            $(this).parent().removeClass('checked');
        });
        
        if(valor) {
            $('#'+id+'[value="'+valor+'"]').attr('checked', true);
            $('#'+id+'[value="'+valor+'"]').parent().addClass('checked');
        }
    }
    
    function setSelected(id, valor) {
        if(valor) {
            $('#'+id+' option[value='+valor+']').attr("selected",true);
            $('#'+id).parent().find('span').text( $('#'+id+' option[value='+valor+']').text() ); // Workless
        } else {
            $('#'+id+' option:first').attr("selected",true);
            $('#'+id).parent().find('span').text("Elegir"); // Workless
        }
    }
    
    function procesarControlCalidad(){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'ajax/controlCalidad.php',
            data: $('#formControlCalidad').serialize(),
            success: function(respuesta)
            {
                if(!respuesta.error) {
                    jAlert('<img src="images/ok.gif" /> Los datos se guardaron exitosamente. ', 'Datos procesados correctamente...');
                } else {
                    jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
                    'o Notifiquelo con el administrador', 'Error al procesar los datos...');
                }
                
                $("#winControlCalidad").dialog('close');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                jAlert('<img src="images/error.gif" /> <font color="#FF0000" style="font-weight:bold">ERROR</font>. Ocurri&oacute; un error al procesar los datos, Intentelo de nuevo. '+
                    'o Notifiquelo con el administrador', 'Error al procesar los datos...');
            }
        });
    }
</script>

<?PHP
require_once ('/include/clases/Helpers.php');
require_once ('/include/clases/BusquedaEstudios.php');

$objHTML = new HTML();
$help = new Helpers();
$objSelects = new Select();

if(empty($_POST['edoCaso'])) 
    $_POST['edoCaso'] = $_SESSION[EDO_USR_SESSION];

echo '<div id="winControlCalidad" title="Control de Calidad de la Muestra">';

    $objHTML->startForm('formControlCalidad', '#', 'POST');
        
        $objHTML->inputHidden('id_estudio');
        $objHTML->inputHidden('tipo_estudio');
        
        include_once('content/controlCalidadMuestra.php');
        
        echo '<div align="center">';
        $objHTML->inputButton('btnControlCalidad', 'Guardar', array('onClick'=>'procesarControlCalidad()'));
        echo '</div>';

    $objHTML->endFormOnly();
    
echo '</div>';

$objHTML->startForm('form_busca', '?'.$_SERVER['QUERY_STRING'], 'POST');

$objHTML->startFieldset();

    $objSelects->selectEstado('edoCaso', isset($_POST['edoCaso']) ? $_POST['edoCaso'] : $_SESSION[EDO_USR_SESSION] );
	//echo $objHTML->makeInput('number', 'Folio Solicitud: ', 'folio_solicitud', $_POST['folio_solicitud'], array('size'=>10) );
	$objHTML->inputText('Folio Laboratorio: ', 'folio_laboratorio', $_POST['folio_laboratorio'], array('size'=>10));
    $objHTML->label('Fecha de Resultado: ');
    $objHTML->inputText('de', 'fecha_inicio', $_POST['fecha_inicio']);
    $objHTML->inputText('hasta', 'fecha_fin', $_POST['fecha_fin']);

$objHTML->endFieldset();

$objHTML->endForm('buscar', 'Buscar', 'limpiar', 'Limpiar');


if(isset($_POST['buscar'])) {
    $busqueda = new BusquedaEstudios();
    
    $busqueda->idCatEstado = $_POST['edoCaso'];
	$busqueda->folioLaboratorio = $_POST['folio_laboratorio'];
	$busqueda->folioSolicitud = $_POST['folio_solicitud'];
	$busqueda->fechaInicio = $_POST['fecha_inicio'];
	$busqueda->fechaFin = $_POST['fecha_fin'];
    
    $busqueda->buscarCalidad();
    
    if(!empty($busqueda->resultado)) {
        echo '<br><br><div class="datagrid">
        <table>
        <thead>
                <tr align="center">
                    <th>Clave LESP</th>	
                    <th>Clave Del Paciente</th>
                    <th>Nombre</th>
                    <th>Solicitante</th>
                    <th>Fecha Muestreo</th>
                    <th>Fecha Solicitud</th>
                    <th>Fecha Resultado</th>
                    <th>Estudio</th>
					<th>C&eacute;dula Registro</th>
                    <th>Control Calidad</th>
                    <th>Ficha Laboratorio</th>
                </tr>
                </thead>
                <tbody>';

        foreach($busqueda->resultado as $estudio){
            echo '<tr>
                <td>'.$estudio->folioLaboratorio.'</td>	
                <td>'.$estudio->clavePaciente.'</td>	
                <td>'.$estudio->nombre.'</td>
                <td>'.$estudio->solicitante.'</td>
                <td>'.formatFechaObj($estudio->fechaMuestreo).'</td>
                <td>'.formatFechaObj($estudio->fechaSolicitud).'</td>
                <td>'.formatFechaObj($estudio->fechaResultado).'</td>
                <td>'.$estudio->estudio.'</td>
				<td align="center">';
				if($estudio->idPaciente != NULL || $estudio->idPaciente != "")
				{
					echo '
					<a href="?mod=labCedu&id='.$estudio->idPaciente.'">
						<img src="images/ver.jpg" border="0"/>
					</a>';
				}
                echo '</td>
                <td align="center">
                    <a href="javascript:showControlCalidad(\''.$estudio->estudio.'\',\''.$estudio->idEstudio.'\')">
						<img src="images/revision_contacto.png" border="0"/>
					</a>
                </td>
                <td align="center">
					<a href="?mod=labSoli&tipo='.$estudio->estudio.'&id='.$estudio->idEstudio.'">
						<img src="images/verLab.gif" border="0"/>
					</a>
				</td>
            </tr>';
        }
    } else {
        echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
    }

    echo '</tbdy></table></div>';
}
?>