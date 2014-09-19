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
		
		$query = 'INSERT INTO [control]
					([idDiagnostico]
					,[fecha]
					,[reingreso]
					,[idCatEstadoPaciente]
					,[idCatTratamientoPreescrito]
					,[vigilanciaPostratamiento]
					,[observaciones])
			  VALUES
					('.$_POST['diagnostico'].'
					,\''.formatFechaObj($_POST['fecha']).'\'
					,'.$_POST['reingreso'].'
					,'.$_POST['evolucion'].'
					,'.$_POST['tratamiento'].'
					,'.$_POST['vigilancia'].'
					,\''.utf8_decode($_POST['observaciones']).'\')';
		$result = ejecutaQuery($query);
		
		if(!$result)
			echo json_encode(array('result'=>false));
		else
			echo json_encode(array('result'=>true));
		
	}
	else
		echo json_encode(array('result'=>false));
?>