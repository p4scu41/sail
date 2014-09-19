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
});
//-->
</script>

<?PHP
require_once('include/clasesLepra.php');
require_once('include/fecha_hora.php');

$objHTML = new HTML();
$objSelects = new Select();
$listado = new ListGeneric();
$help = new Helpers();
$diagnostico = NULL;


$objHTML->startForm('form_busca', '?mod=labBus', 'POST');

$objHTML->startFieldset();

	$objHTML->inputText('Clave del Paciente: ', 'cvePaciente');
	$objHTML->inputText('Folio Laboratorio: ', 'folio_laboratorio');
	echo '<br />';
	
	$objHTML->inputText('Nombre: ', 'nombre');
	$objHTML->inputText('Apellido Paterno: ', 'apPaterno');
	$objHTML->inputText('Apellido Materno: ', 'apMaterno');
	echo '<br />';
	
	$objSelects->selectEstado('edoCaso', 7);
	$objSelects->selectJurisdiccion('jurisCaso', 7);
	$objSelects->selectMunicipio('muniCaso', 7);
	echo '<br />';
	
	$objSelects->selectUnidad('uniTratado', 7);

$objHTML->endFieldset();

$objHTML->endForm('buscar', 'Buscar', 'limpiar', 'Limpiar');

if(isset($_POST['buscar'])) {
	// para prubea
	$_GET['id'] = 51;
	
	$objHTML->startFieldset();
	
	$listado->getProcesadosBacPaciente($_GET['id']);
	$listado->getProcesadosHisPaciente($_GET['id']);
	
	echo '<div class="datagrid">
			<table>
			<thead>
			<tr align="center">
				<th>Clave LESP</th>	
				<th>Nombre</th>
				<th>Solicitante</th>
				<th>Fecha Muestreo</th>
				<th>Fecha Recepci&oacute;n</th>
				<th>Fecha Resultado</th>
				<th>Tipo</th>
				<th>Estudio</th>
				<th>Ver</th>
			</tr>
			</thead>
			<tbody>';
	
		foreach($listado->arrEstudiosBac as $procesadoBac){
			echo '<tr>
				<td>'.$procesadoBac->folioLaboratorio.'</td>		
				<td>'.$help->getNamePaciente($help->getIdPacienteFromDiagnostico($procesadoBac->idDiagnostico)).'</td>
				<td>'.$procesadoBac->idCatSolicitante.' '.$help->getNameUnidad($procesadoBac->idCatSolicitante).'</td>
				<td>'.formatFechaObj($procesadoBac->fechaTomaMuestra).'</td>
				<td>'.formatFechaObj($procesadoBac->fechaRecepcion).'</td>
				<td>'.formatFechaObj($procesadoBac->fechaResultado).'</td>
				<td>'.htmlentities($help->getDescripTipoEstudio($procesadoBac->idCatTipoEstudio)).'</td>
				<td>Basilosc&oacute;pia</td>
				<td align="center"><a href="?mod=labSoli&tipo=bacilos&id='.$procesadoBac->idEstudioBac.'"><img src="images/verLab.gif" border="0"/></a></td>
			</tr>';
		}
		
		foreach($listado->arrEstudiosHis as $procesadoHis){
			echo '<tr>
				<td>'.$procesadoHis->folioLaboratorio.'</td>	
				<td>'.$help->getNamePaciente($help->getIdPacienteFromDiagnostico($procesadoHis->idDiagnostico)).'</td>
				<td>'.$procesadoHis->idCatSolicitante.' '.$help->getNameUnidad($procesadoHis->idCatSolicitante).'</td>
				<td>'.formatFechaObj($procesadoHis->fechaTomaMuestra).'</td>
				<td>'.formatFechaObj($procesadoHis->fechaRecepcion).'</td>
				<td>'.formatFechaObj($procesadoHis->fechaResultado).'</td>
				<td>'.htmlentities($help->getDescripTipoEstudio($procesadoHis->idCatTipoEstudio)).'</td>
				<td>Histopatol&oacute;gia</td>
				<td align="center"><a href="?mod=labSoli&tipo=histo&id='.$procesadoHis->idEstudioHis.'"><img src="images/verLab.gif" border="0"/></a></td>
			</tr>';
		}
	
	echo '</tbdy></table></div>';
	$objHTML->endFieldset();
}
?>