<script type="text/javascript">
<!--
$(document).ready(function(){
	$('#edoCaso').change(function(){ 
		actualiza_select( { destino:'jurisCaso', edo:'edoCaso', tipo:'juris'} );
		reset_select('muniCaso');
		reset_select('uniTratado');
	});
	
	$('#jurisCaso').change(function(){ 
		actualiza_select( { destino:'muniCaso', edo:'edoCaso', juris:'jurisCaso', tipo:'muni'} );
		actualiza_select( { destino:'uniTratado', edo:'edoCaso', juris:'jurisCaso', tipo:'uni'} );
	});
	
	$('#muniCaso').change(function(){ 
		actualiza_select( { destino:'uniTratado', edo:'edoCaso', juris:'jurisCaso', muni:'muniCaso', tipo:'uni'} );
	});

    $('#form_busca').submit(function() { remDisabled('form_busca'); } );
});
//-->
</script>

<?PHP 
require_once('/include/clasesLepra.php');
require_once('/include/bdatos.php');
require_once('/include/clases/Helpers.php');
require_once('/include/clases/BusquedaPaciente.php');

$objHTMl = new HTML();
$objSelects = new Select();
$help = new Helpers();
$query = '';
$heightTR = '50';

echo '<h2 align="center">B&uacute;squeda de Pacientes</h2>';

$objHTMl->startForm('form_busca', '?mod=bus', 'POST');

$objHTMl->startFieldset();



echo '<table>';
echo '<tr style="height:'.$heightTR.'px;">';
    echo '<td align="right">';
    $objHTMl->label('Clave del Paciente:', array('for'=>'cvePaciente'));
    echo '</td><td>';
	$objHTMl->inputText('', 'cvePaciente', isset($_POST['cvePaciente']) ? $_POST['cvePaciente'] : $_SESSION['buscar']['cvePaciente'] );
    echo '</td><td align="right">';
    $objHTMl->label('Tipo de Paciente:', array('for'=>'tipo_paciente'));
    echo '</td><td>';
    $objSelects->SelectCatalogo('', 'tipo_paciente', 'catTipoPaciente', isset($_POST['tipo_paciente']) ? $_POST['tipo_paciente'] : $_SESSION['buscar']['tipo_paciente'] );
    echo '</td><td align="right">';
    $objHTMl->label('Status:', array('for'=>'statusPaciente'));
    echo '</td><td>';
    $objSelects->SelectCatalogo('', 'statusPaciente', 'catEstadoPaciente', isset($_POST['statusPaciente']) ? $_POST['statusPaciente'] : $_SESSION['buscar']['statusPaciente']);
    echo '</td>';
echo '</tr><tr style="height:'.$heightTR.'px;">';
    echo '<td align="right">';
    $objHTMl->label('Nombre:', array('for'=>'nombre'));
	echo '</td><td>';
    $objHTMl->inputText('', 'nombre', isset($_POST['nombre']) ? $_POST['nombre'] : $_SESSION['buscar']['nombre']);
    echo '</td><td align="right">';
    $objHTMl->label('Apellido Paterno:', array('for'=>'apPaterno'));
	echo '</td><td>';
    $objHTMl->inputText('', 'apPaterno', isset($_POST['apPaterno']) ? $_POST['apPaterno'] : $_SESSION['buscar']['apPaterno']);
	echo '</td><td align="right">';
    $objHTMl->label('Apellido Materno:', array('for'=>'apMaterno'));
    echo '</td><td>';
    $objHTMl->inputText('', 'apMaterno', isset($_POST['apMaterno']) ? $_POST['apMaterno'] : $_SESSION['buscar']['apMaterno']);
    echo '</td>';
echo '</tr><tr style="height:'.$heightTR.'px;">';
	echo '<td align="right">';
    $objHTMl->label('Estado:', array('for'=>'edoCaso'));
	echo '</td><td>';
    $objSelects->selectEstado('edoCaso', isset($_POST['edoCaso']) ? $_POST['edoCaso'] : ($_SESSION['buscar']['edoCaso'] ? $_SESSION['buscar']['edoCaso'] : $_SESSION[EDO_USR_SESSION]), $_SESSION[EDO_USR_SESSION]==0 ? array() : array('disabled'=>'disabled'), false);
	echo '</td><td align="right">';
    $objHTMl->label('Jurisdicción:', array('for'=>'jurisCaso'));
    echo '</td><td>';
    $objSelects->selectJurisdiccion('jurisCaso', 
					isset($_POST['edoCaso']) ? $_POST['edoCaso'] : ($_SESSION['buscar']['edoCaso'] ? $_SESSION['buscar']['edoCaso'] : $_SESSION[EDO_USR_SESSION]),
					isset($_POST['jurisCaso']) ? $_POST['jurisCaso'] : $_SESSION['buscar']['jurisCaso']
				, null, false);
    echo '</td><td align="right">';
    $objHTMl->label('Municipio:', array('for'=>'muniCaso'));
	echo '</td><td>';
    $objSelects->selectMunicipio('muniCaso', isset($_POST['edoCaso']) ? $_POST['edoCaso'] : ($_SESSION['buscar']['edoCaso'] ? $_SESSION['buscar']['edoCaso'] : $_SESSION[EDO_USR_SESSION]),
					isset($_POST['jurisCaso']) ? $_POST['jurisCaso'] : $_SESSION['buscar']['jurisCaso'],
					isset($_POST['muniCaso']) ? $_POST['muniCaso'] : $_SESSION['buscar']['muniCaso']
				, null, false);
    echo '</td>';
echo '</tr><tr style="height:'.$heightTR.'px;">';
    echo '<td align="right">';
    $objHTMl->label('Tratado(a) en:', array('for'=>'uniTratado'));
    echo '</td><td>';
	$objSelects->selectUnidad('uniTratado', isset($_POST['edoCaso']) ? $_POST['edoCaso'] : ($_SESSION['buscar']['edoCaso'] ? $_SESSION['buscar']['edoCaso'] : $_SESSION[EDO_USR_SESSION]),
					isset($_POST['jurisCaso']) ? $_POST['jurisCaso'] : $_SESSION['buscar']['jurisCaso'],
					isset($_POST['muniCaso']) ? $_POST['muniCaso'] : $_SESSION['buscar']['muniCaso'], null,
					isset($_POST['uniTratado']) ? $_POST['uniTratado'] : $_SESSION['buscar']['uniTratado']
				, null, false);
    echo '</td>';
echo '</tr></table>';

$objHTMl->endFieldset();

$objHTMl->endForm('buscar', 'Buscar', 'limpiar', 'Limpiar');


$busqueda = new BusquedaPaciente();

if(isset($_GET['p']))
	$busqueda->page = $_GET['p'];

if($_POST['buscar']) {
	// Guardamos los parametros de la ultima busqueda
	$_SESSION['buscar'] = $_POST;
	unset($_SESSION['buscar']['buscar']);
	if($_POST['edoCaso'] == 0)
		$busqueda->idCatEstado = '';
	else
		$busqueda->idCatEstado = $_POST['edoCaso'];
	$busqueda->idCatJurisdiccion = $_POST['jurisCaso'];
	$busqueda->idCatMunicipio = $_POST['muniCaso'];
	$busqueda->idCatUnidad = $_POST['uniTratado'];
	$busqueda->idCatTipoPaciente = $_POST['tipo_paciente'];
	$busqueda->clavePaciente = $_POST['cvePaciente'];
	$busqueda->nombre = $_POST['nombre'];
	$busqueda->apellidoP = $_POST['apPaterno'];
	$busqueda->apellidoM = $_POST['apMaterno'];
	$busqueda->statusPaciente = $_POST['statusPaciente'];
    
    $busqueda->buscar();
    
	/*$query = 'SELECT [idPaciente]
			      ,[nombre]
			      ,[apellidoPaterno]
			      ,[apellidoMaterno]
			      ,[sexo]
			      ,[cveExpediente]
			      ,[idCatTipoPaciente]
			      ,[idCatUnidadNotificante]
			      ,[idCatUnidadTratante]
			  FROM [pacientes]';*/
} else if (count($_SESSION['buscar']) != 0) {
    $busqueda->idCatEstado = $_SESSION['buscar']['edoCaso'];
	$busqueda->idCatJurisdiccion = $_SESSION['buscar']['jurisCaso'];
	$busqueda->idCatMunicipio = $_SESSION['buscar']['muniCaso'];
	$busqueda->idCatUnidad = $_SESSION['buscar']['uniTratado'];
	$busqueda->idCatTipoPaciente = $_SESSION['buscar']['tipo_paciente'];
	$busqueda->clavePaciente = $_SESSION['buscar']['cvePaciente'];
	$busqueda->nombre = $_SESSION['buscar']['nombre'];
	$busqueda->apellidoP = $_SESSION['buscar']['apPaterno'];
	$busqueda->apellidoM = $_SESSION['buscar']['apMaterno'];
	$busqueda->statusPaciente = $_SESSION['buscar']['statusPaciente'];
    
    $busqueda->buscar();
	/*$query = 'SELECT [idPaciente]
			      ,[nombre]
			      ,[apellidoPaterno]
			      ,[apellidoMaterno]
			      ,[sexo]
			      ,[cveExpediente]
			      ,[idCatTipoPaciente]
			      ,[idCatUnidadNotificante]
			      ,[idCatUnidadTratante]
			  FROM [pacientes]';*/
}

//if($query != '')
if(!empty($busqueda->resultado))
{
	$tipoPaciente = NULL;
	$rsTipoPaciente = ejecutaQuery('SELECT [idCatTipoPaciente],[descripcion] FROM [catTipoPaciente]');
	
	while ($tipo = devuelveRowAssoc($rsTipoPaciente))
		$tipoPaciente[$tipo['idCatTipoPaciente']] = $tipo['descripcion'];
	
	$rsTipoPaciente = ejecutaQuery('SELECT [idCatEstadoPaciente],[descipcion] FROM [catEstadoPaciente]');
	
	while ($tipo = devuelveRowAssoc($rsTipoPaciente))
		$estadoPaciente[$tipo['idCatEstadoPaciente']] = $tipo['descipcion'];
	
	echo '<br /><div class="datagrid">
			<table>
			<thead>
			<tr align="center">
				<th>Clave del Paciente</th>
				<th>Nombre</th>
				<th>Sexo</th>
				<th>Tipo</th>
				<th>Status del Paciente</th>
				<th>Unidad Tratante</th>
				<th>C&eacute;dula de Registro</th>
				<th>Laboratorio</th>
				<th>Control</th>
			</tr>
			</thead>
			<tbody>';
	
	//$result = ejecutaQuery($query);
	//while($paciente = devuelveRowAssoc($result)) {
    foreach($busqueda->resultado as $paciente) {
		
		/****************************************/
		$pPaciente = new Paciente();
		
		$pPaciente->idPaciente = $paciente->idPaciente;
		
		$pPaciente->cargarArreglosPaciente();

        $diagnostico = $pPaciente->arrDiagnosticos[0];
        
		//"","#","#","#","#"
		
        // Un paciente Sospechoso(5) o Descartado(6) no tiene diagnostico asociado
        if(!empty($diagnostico)){
            //$diagnostico->cargarArreglosDiagnosticoCasosRelacionados();
            //$diagnostico->cargarArreglosDiagnosticoContactos();
            //$diagnostico->cargarArreglosDiagnosticoDiagramaDermatologico();
			$estadoP = $estadoPaciente[$diagnostico->idCatEstadoPaciente];
        } else {
            /*$sospechoso->obtenerBD($pPaciente->idPaciente);
            $diagnostico = $sospechoso;*/
			if($paciente->idCatTipoPaciente == 6)
				$estadoP = "Descartado";
			else
				$estadoP = "Caso Probable";
        }

		/****************************************/
        $colorCelda = "";
        $colorLetra = "";
        
		if($diagnostico->idCatEstadoPaciente == 1 || $diagnostico->idCatEstadoPaciente == 2 || $diagnostico->idCatEstadoPaciente == 5 || $diagnostico->idCatEstadoPaciente == 9)
		{
			$colorCelda = "#FF0000";
			$colorLetra = "#FFFFFF";
		}
		else if($diagnostico->idCatEstadoPaciente == 6 || $diagnostico->idCatEstadoPaciente == 3)
		{
			$colorCelda = "#FFFF00";
			$colorLetra = "";
		}
		else if($diagnostico->idCatEstadoPaciente == 4)
		{
			$colorCelda = "#00FF00";
			$colorLetra = "";
		}
		else if($diagnostico->idCatEstadoPaciente == 8)
		{
			$colorCelda = "#000000";
			$colorLetra = "#FFFFFF";
		}
		else if($diagnostico->idCatEstadoPaciente == 12)
		{
			$colorCelda = "#FF8000";
			$colorLetra = "";
		}
		else if($diagnostico->idCatEstadoPaciente == 7 || $diagnostico->idCatEstadoPaciente == 10 || $diagnostico->idCatEstadoPaciente == 11)
		{
			$colorCelda = "";
			$colorLetra = "";
		}
		else if($paciente->idCatTipoPaciente == 6)
		{
			$colorCelda = "#00FF00";
			$colorLetra = "";
		}
		
		if($diagnostico->idCatEstadoPaciente == $_POST['tipoPaciente'] || $_POST['tipoPaciente'] == "")
		{
			echo '<tr>
					<td>'.$paciente->cveExpediente.'</td>
					<td>'.$paciente->nombre.' '.$paciente->apellidoPaterno.' '.$paciente->apellidoMaterno.'</td>
					<td>'.$help->getDescripcionSexo($paciente->sexo).'</td>
					<td>'.$tipoPaciente[$paciente->idCatTipoPaciente].'</td>
					<td bgcolor="'.$colorCelda.'"><font color="'.$colorLetra.'">'.$estadoP.'</font></td>
					<td>'.$paciente->idCatUnidadTratante.'</td>
					<td align="center"><a href="?mod=cap&id='.$paciente->idPaciente.'"><img src="images/ver.jpg" border="0"/></a></td>
					<td align="center"><a href="?mod=lab&id='.$paciente->idPaciente.'"><img src="images/laboratorio.jpg" border="0"/></a></td>
					<td align="center"><a href="?mod=con&id='.$paciente->idPaciente.'"><img src="images/control.png" border="0"/></a></td>
				</tr>';
		}
	}

	echo '</tbody>
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
		</tfoot>
		</table>
		</div><br />';	
}

if(!empty($_POST) && empty($busqueda->resultado))
    echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
?>
