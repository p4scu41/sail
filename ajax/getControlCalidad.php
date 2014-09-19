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
		
		$query = 'SELECT * FROM [controlCalidad] WHERE ';
        
        switch ($_POST['tipo_estudio']) {
            case 'bacilos':
                $query .= ' idEstudioBac='.(int)$_POST['id_estudio'];
            break;
            case 'histo';
                $query .= ' idEstudioHis='.(int)$_POST['id_estudio'];
            break;
        }
        
		$result = ejecutaQuery($query);
		
		if(!$result)
			echo json_encode(array('error'=>true, 'msj'=>'Error al procesar los datos, intentelo nuevamente'));
		else{
            $registro = devuelveRowAssoc($result);
            $registro['error'] = false;
			echo json_encode($registro);
        }
	}
	else
		echo json_encode(array('error'=>true, 'msj'=>'Error al procesar los datos, intentelo nuevamente'));
?>