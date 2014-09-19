<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/funciones.php');
	require_once('../include/log.php');
	require_once('../include/fecha_hora.php');
	
	if(isset($_SESSION[ID_USR_SESSION]))
	{
		$connectionBD = conectaBD();
		
		if($connectionBD === FALSE)
			die('<br /><div align="center" class="error_sql"><strong>ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion" 
				<u>var_global.php</u>."</strong></div>');
		
		$query = 'SELECT * FROM [controlContacto] WHERE idControlContacto='.(int)$_POST['idContactoRev'];
		$result = ejecutaQuery($query);
		
		if(!$result)
			echo json_encode(array('error'=>true, 'msj'=>'Error al procesar los datos, intentelo nuevamente'));
		else{
            $registro = devuelveRowAssoc($result);
            $nombre = devuelveRowAssoc(ejecutaQuery('SELECT [nombre] FROM [contactos] WHERE [idContacto]='.$registro['idContacto']));
            $registro['fecha'] = formatFechaObj($registro['fecha']);
			echo json_encode(array_merge(array('error'=>false, 'msj'=>'Datos procesados correctamente'), $registro, $nombre));
        }
	}
	else
		echo json_encode(array('error'=>true, 'msj'=>'Error al procesar los datos, intentelo nuevamente'));
?>