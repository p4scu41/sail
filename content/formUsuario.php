<script type="text/javascript">
<!--
$(document).ready(function(){
	$('#btnCancelar').click(function(){ 
        location.href = 'index.php?mod=usrs';
    });
    
    $('#edoUsuario').change(function(){ 
		actualiza_select( { destino:'jurisUsuario', edo:'edoUsuario', tipo:'juris'} );
	});
    
    setValidacion('form_usrs');
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

if(isset($_GET['id'])){
    $objUsuario->obtenerBD($_GET['id']);
    
    if($objUsuario->error) {
        echo msj_error('Ocurri&oacute; un error al obtener los datos del usuario. ');
        echo '<br>';
    }
    
    if(is_null($objUsuario->nombreUsuario)) {
        echo msj_error('No se encontraron datos del usuario especificado');
        echo '<br>';
    }
}


if(!empty($_POST['nombre'])){
    $objUsuario->nombre = $_POST['nombre'];
    $objUsuario->apellidoPaterno = $_POST['apellidoPaterno'];
    $objUsuario->apellidoMaterno = $_POST['apellidoMaterno'];
    
    $objUsuario->nombreUsuario = $_POST['usuario'];
    $objUsuario->password = $_POST['password'];
    $objUsuario->idCatTipoUsuario = $_POST['tipoUsuario'];
    $objUsuario->correo = $_POST['correo'];
    $objUsuario->habilitado = $_POST['habilitado'];
    
    $objUsuario->idCatEstado = $_POST['edoUsuario'];
    $objUsuario->idCatJurisdiccion = $_POST['jurisUsuario'];
    
    if($_POST['password'] != $_POST['password2']) {
        echo msj_error('Debe confirmar la contrase&ntilde;a correctamente. Las contrase&ntilde;as no coinciden, verifique ese dato').'<br>';
    } else {
        if($_POST['guardar']){
            if(!$objUsuario->validNombreUsuario()) {
                echo msj_error('El nombre de usuario ya esta en uso, debe establecer un nombre de usuario diferente.').'<br>';
            } else {
                $objUsuario->insertarBD();

                if($objUsuario->error) {
                    echo msj_error('Ocurri&oacute; un error al guardar los datos del usuario.').'<br>';
                } else {
                    echo msj_ok('Usuario registrado exitosamente.').'<br>';
                }
            }
        }

        if($_POST['actualizar']){
            $objUsuario->modificarBD();

            if($objUsuario->error) {
                echo  msj_error('Ocurri&oacute; un error al actualizar los datos del usuario. ').'<br>';
            } else {
                echo msj_ok('Usuario actualizado exitosamente.').'<br>';
            }
        }
    }
}


if(isset($_GET['id']))
    echo '<h2 align="center">Actualizaci&oacute;n del usuario</h2>';
else
    echo '<h2 align="center">Registro de nuevo usuario</h2>';

$objHTMl->startForm('form_usrs', '?mod=formUsr'.(isset($_GET['id']) ? '&id='.$_GET['id'] : ''), 'POST');

$objHTMl->startFieldset();

    if(isset($_GET['id']))
        $objHTMl->inputHidden('actualizar', 1);
    else
        $objHTMl->inputHidden('guardar', 1);

	$objHTMl->inputText('Nombre:', 'nombre', $objUsuario->nombre, array('class'=>'validate[required]') );
    $objHTMl->inputText('Apellido Paterno:', 'apellidoPaterno', $objUsuario->apellidoPaterno, array('class'=>'validate[required]') );
    $objHTMl->inputText('Apellido Materno:', 'apellidoMaterno', $objUsuario->apellidoMaterno, array('class'=>'validate[required]') );
    
    echo '<br>';
    
    $objSelects->selectEstado('edoUsuario', $objUsuario->idCatEstado ? $objUsuario->idCatEstado : $_SESSION[EDO_USR_SESSION]);
    $objSelects->selectJurisdiccion('jurisUsuario', $objUsuario->idCatEstado ? $objUsuario->idCatEstado : $_SESSION[EDO_USR_SESSION], $objUsuario->idCatJurisdiccion);
	
    echo '<br>';
    
    $objHTMl->inputText('Nombre de usuario:', 'usuario', $objUsuario->nombreUsuario, array('class'=>'validate[required,minSize[6]]', 'data-mayus'=>'false') );
    echo $objHTMl->makeInput('password', 'Contraseña:', 'password', $objUsuario->password, array('class'=>'validate[required,minSize[6]]', 'data-mayus'=>'false') );
    echo $objHTMl->makeInput('password', 'Confirmar Contraseña:', 'password2', $objUsuario->password, array('class'=>'validate[required,minSize[6]]', 'data-mayus'=>'false') );
    
    echo '<br>';
    
    $objSelects->SelectCatalogo('Tipo:', 'tipoUsuario', 'catTipoUsuario', $objUsuario->idCatTipoUsuario, array('class'=>'validate[required]') );
    
    $objHTMl->inputText('Correo electrónico:', 'correo', $objUsuario->correo, array('class'=>'validate[required,custom[email]]', 'data-mayus'=>'false') );
    $objHTMl->inputCheckbox('Habilitado', 'habilitado', '1', (is_null($objUsuario->habilitado) ? 1 : $objUsuario->habilitado));
    
$objHTMl->endFieldset();

echo '<div align="center">';
$objHTMl->inputSubmit('btnGuardar', 'Guardar');
$objHTMl->inputButton('btnCancelar', 'Cancelar');
echo '</div>';

$objHTMl->endFormOnly();

