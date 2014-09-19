<script type="text/javascript">
<!--
$(document).ready(function(){
	$('#registrar').click(function(){ 
        location.href = 'index.php?mod=formUsr';
    });
});
//-->
</script>

<?PHP 
require_once('/include/bdatos.php');
require_once('/include/clases/Helpers.php');
require_once('/include/clases/Usuario.php');

$objHTMl = new HTML();
$objSelects = new Select();
$help = new Helpers();
$objUsuario = new Usuario();
$filtro = null;

echo '<h2 align="center">Administraci&oacute;n de usuarios</h2>';

$objHTMl->startForm('form_busca_usrs', '?mod=usrs', 'POST');

$objHTMl->startFieldset();

	$objHTMl->inputText('Nombre:', 'nombre', isset($_POST['nombre']) ? $_POST['nombre'] : '' );
    $objSelects->SelectCatalogo('Tipo:', 'tipoUsuario', 'catTipoUsuario', isset($_POST['tipoUsuario']) ? $_POST['tipoUsuario'] : '' );
    $objSelects->selectEstado('edoUsuario', isset($_POST['edoUsuario']) ? $_POST['edoUsuario'] : $_SESSION[EDO_USR_SESSION]);
	
$objHTMl->endFieldset();

echo '<div align="center">';
$objHTMl->inputSubmit('buscar', 'Buscar');
$objHTMl->inputButton('registrar', 'Registrar');
echo '</div>';

$objHTMl->endFormOnly();


echo '<br /><div class="datagrid" align="center">
			<table align="center">
			<thead>
			<tr align="center">
				<th>Nombre Completo</th>
				<th>Nombre de usuario</th>
				<th>correo</th>
				<th>Tipo</th>
				<th>Estado</th>
				<th>Habilitado</th>
				<th>Editar</th>
			</tr>
			</thead>
			<tbody>';

if(!empty($_POST['nombre'])) {
    $filtro['nombre'] = $_POST['nombre'];
}

if(!empty($_POST['tipoUsuario'])) {
    $filtro['idCatTipoUsuario'] = $_POST['tipoUsuario'];
}

if(!empty($_POST['edoUsuario'])) {
    $filtro['idCatEstado'] = $_POST['edoUsuario'];
}

$listUsuarios = $objUsuario->obtenerTodos($filtro);

foreach($listUsuarios as $usr) {
    echo '<tr>
        <td>'.$usr['nombre'].' '.$usr['apellidoPaterno'].' '.$usr['apellidoMaterno'].'</td>
        <td>'.$usr['nombreUsuario'].'</td>
        <td>'.$usr['correo'].'</td>
        <td>'.$usr['tipo_usuario'].'</td>
        <td>'.$usr['estado'].'</td>
        <td align="center">'.($usr['habilitado'] ? 'Si' : 'No').'</td>
        <td align="center"><a href="?mod=formUsr&id='.$usr['idUsuario'].'"><img src="images/ver.jpg" border="0"/></a></td>
    </tr>';
}

echo '</tbody>
		<tfoot>
		</tfoot>
		</table>
		</div><br />';
