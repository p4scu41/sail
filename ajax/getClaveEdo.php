<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/log.php');
	
	if(isset($_SESSION[ID_USR_SESSION]))
	{
		$connectionBD = conectaBD();
		
		if($connectionBD === FALSE)
			die('<br /><div align="center" class="error_sql"><strong>ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion" 
				<u>var_global.php</u>."</strong></div>');
		
		$rs_clave = ejecutaQuery('SELECT [claveAbreviada] FROM [catEstado] WHERE [idCatEstado]='.(int)$_GET['edo']);
		$clave = devuelveRowAssoc($rs_clave);
		
		echo $clave['claveAbreviada'];
	}
	else
		return '';
?>