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
		
		$etiquetas = NULL;
		
		$rs_etiquetas = ejecutaQuery('SELECT [idCatTipoLesionDiagrama],[descripcion] FROM [catTipoLesionDiagrama]');
		
		while($etiqueta = devuelveRowAssoc($rs_etiquetas))
			$etiquetas[] = array('id'=>$etiqueta['idCatTipoLesionDiagrama'], 'label'=>utf8_encode($etiqueta['descripcion']), 'value'=>utf8_encode($etiqueta['descripcion']));
		
		echo json_encode($etiquetas);
	}
?>