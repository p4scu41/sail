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
		
		$index = 0;
		$rs_clave = ejecutaQuery('SELECT [cveExpediente] FROM [pacientes] WHERE [cveExpediente] LIKE \''.$_GET['cve'].'%\'');
		$clave = devuelveRowAssoc($rs_clave);
		
		if($clave['cveExpediente'] == '')
			$index++;
		else {
			$index = (int)substr($clave['cveExpediente'], -2);
			$index++;
		}
			
		
		echo str_pad($index,2,'0',STR_PAD_LEFT);
	}
	else
		return '';
?>