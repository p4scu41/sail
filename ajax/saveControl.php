<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/funciones.php');
	require_once('../include/log.php');
	require_once('../include/fecha_hora.php');
    require_once('../include/clasesLepra.php');
	
	if(isset($_SESSION[ID_USR_SESSION]))
	{
		$connectionBD = conectaBD();
		
		if($connectionBD === FALSE)
			die('<br /><div align="center" class="error_sql"><strong>ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion" 
				<u>var_global.php</u>."</strong></div>');
		
        $control = new Control();
        
        $control->idDiagnostico = $_POST['diagnostico'];
        $control->fecha = formatFecha($_POST['fecha']);
        $control->reingreso = (int)$_POST['reingreso'];
        $control->idCatEstadoPaciente = (int)$_POST['estadopaciente'];
        $control->idCatTratamientoPreescrito = (int)$_POST['tratamiento'];
        $control->vigilanciaPostratamiento = (int)$_POST['vigilancia'];
        $control->observaciones = utf8_decode($_POST['observaciones']);
        $control->idCatEvolucionClinica = (int)$_POST['evolucion'];
        $control->idCatBaja = (int)$_POST['baja'];
        $control->seed = (int)$_POST['seed'];
    
        $control->insertarBD();

        /*$query = 'INSERT INTO [control]
					([idDiagnostico]
					,[fecha]
					,[reingreso]
					,[idCatEstadoPaciente]
					,[idCatTratamientoPreescrito]
					,[vigilanciaPostratamiento]
					,[observaciones]
                    ,[idCatEvolucionClinica]
                    ,[idCatBaja]
                    ,[seed])
			  VALUES
					('.$_POST['diagnostico'].'
					,\''.formatFechaObj($_POST['fecha']).'\'
					,'.(int)$_POST['reingreso'].'
					,'.(int)$_POST['estado_paciente'].'
					,'.(int)$_POST['tratamiento'].'
					,'.(int)$_POST['vigilancia'].'
					,\''.utf8_decode($_POST['observaciones']).'\'
                    ,'.(int)$_POST['evolucion'].'
                    ,'.(int)$_POST['baja'].'
                    ,'.(int)$_POST['seed'].')';
		$result = ejecutaQuery($query);*/
		
		if($control->error)
			echo json_encode(array('result'=>false));
		else
			echo json_encode(array('result'=>true));
	}
	else
		echo json_encode(array('result'=>false));
?>