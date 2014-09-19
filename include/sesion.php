<?PHP	
/*************************************************/
function inicia_sesion($user, $pass)
{
	$query = 'SELECT [idUsuario],[idCatTipoUsuario],[idCatEstado],[idCatJurisdiccion] FROM [usuarios] WHERE [nombreUsuario]=\''.addslashes(htmlspecialchars(trim($user))).'\' 
				AND [password]=\''.addslashes(htmlspecialchars(trim(md5($pass)))).'\' AND [habilitado]=1';
	
	$result = ejecutaQuery($query);
	
	// Si el usuario es encontrado, procedemos a crear la sesion
	if(devuelveNumRows($result) == 1)
	{
		$tipo = devuelveRowAssoc($result);
		$_SESSION[ID_USR_SESSION] = $tipo['idUsuario'];
		$_SESSION[NAME_USR_SESSION] = $user;
		$_SESSION[TIPO_USR_SESSION] = $tipo['idCatTipoUsuario'];
		$_SESSION[EDO_USR_SESSION] = $tipo['idCatEstado'];
		$_SESSION[JUR_USR_SESSION] = $tipo['idCatJurisdiccion'];
		
		$redirect = 'ini';
		
		switch ($_SESSION[TIPO_USR_SESSION]) {
			case 1: // Administrador
				$redirect = 'ini';
			break;
			case 2: // Medico
				$redirect = 'medIni';
			break;
			case 3: // Laboratorista
				$redirect = 'labIni';
			break;
            case 4: // Recepcion Muestra
				$redirect = 'recepIni';
			break;
            case 5: // Control de calidad
				$redirect = 'calIni';
			break;
			default:
				echo 'ERROR: Tipo de Usuario no definido';
			break;
		}
		
		echo '<br /><br /><center><b>Iniciando sesi&oacute;n . . . </b><br /><br /><img src="images/barra_animada.gif" /></center>
				<br /><meta http-equiv="refresh" content="1;url=?mod='.$redirect.'" />';
	}
	else
	{
		unset($_SESSION[ID_USR_SESSION],$_SESSION[NAME_USR_SESSION],$_SESSION[TIPO_USR_SESSION]);
		session_unset();
		echo msj_error('Usuario o contrase&ntilde;a incorrecta');
	}
}

/*************************************************/

function check_user($user)
{
	$var = false;
	$query = 'SELECT [idUsuario],[idCatTipoUsuario],[idCatEstado],[idCatJurisdiccion] FROM [usuarios] WHERE [nombreUsuario]=\''.addslashes(htmlspecialchars(trim($user))).'\'';
	
	$result = ejecutaQuery($query);
	
	// Si el usuario es encontrado, procedemos a crear la sesion
	if(devuelveNumRows($result) == 1)
	{
		$var = true;
	}
	
	return $var;
}

/*************************************************/

function cerrar_sesion()
{
	unset($_SESSION[ID_USR_SESSION],$_SESSION[NAME_USR_SESSION],$_SESSION[TIPO_USR_SESSION]);
	session_unset();
	
	echo "<br /><br /><center><b>Cerrando sesi&oacute;n . . . </b><br /><br /><img src='images/barra_animada.gif' /></center>
	<br /><meta http-equiv='refresh' content='1;url=?mod=ini' />";
}

/*************************************************/
function form_sesion()
{
	?><br><br><br>
	<div align="center">
	<form name="form_inicia_sesion" id="form_inicia_sesion" method="post" action="index.php">
	<table align="center" cellspacing="10" border="0">
		<tr><td height="5" colspan="2"><h3 align="center">Introduzca Usuario y Contrase&ntilde;a</h3></td></tr>
		<tr><td height="5" colspan="2" bgcolor="#060" style="background-color:#060;"></td></tr>
		<tr>
			<td align="right"><label for="usr">Usuario</label></td>
			<td align="left"><input type="text" name="usr" id="usr" size="25" style="background:url(images/usuario.gif) no-repeat, url(images/bg_input.jpg) top repeat-x; padding:0px 0px 0px 20px; height:21px;" autofocus /></td>
		</tr>
		<tr>
			<td align="right"><label for="pass">Contrase&ntilde;a</label></td>
			<td align="left"><input type="password" name="pass" id="pass" size="25" style="background:url(images/pass.gif) no-repeat, url(images/bg_input.jpg) top repeat-x; padding:0px 0px 0px 20px;  height:21px;" /></td>
		</tr>
		<tr><td height="5" colspan="2" bgcolor="#F00" style="background-color:#F00;"></td></tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="btn_inicia_sesion" id="btn_inicia_sesion" value="Iniciar Sesi&oacute;n" />
			</td>
		</tr>
	</table>
</form>
</div>
	<?PHP
	if($_POST['btn_inicia_sesion'])
		inicia_sesion($_POST['usr'], $_POST['pass']);
}
?>
