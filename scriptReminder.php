<?PHP 
	require_once('include/var_global.php');
	require_once('include/bdatos.php');
	require_once('include/log.php');
	require_once('include/fecha_hora.php');
	require_once('include/commandSMS.php');
	
	$smsMessage = "TEST REMINDER";
	sendSMS("9611078474", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
	
	$connectionBD = conectaBD();
	
	$query = "SELECT idDiagnostico FROM diagnostico WHERE idCatEstadoPaciente = 2";
	$result = ejecutaQuery($query);
	
	$today = date("Y-m-d");	
	while($diagnostico = devuelveRowAssoc($result))
	{
		$query = "SELECT DATEDIFF(DAY,DATEADD(DAY,28,MAX(fecha)),'".$today."') AS dias, MAX(fecha) AS fecha FROM control WHERE idDiagnostico = ".$diagnostico['idDiagnostico'];
		$diasSql = ejecutaQuery($query);
		$diasFaltantes = devuelveRowAssoc($diasSql);
		$diasFaltantes['dias'];
		
		if($diasFaltantes['dias'] == -3)
		{
			//echo "FALTAN 18 dias para la toma <br>";
			$query = "SELECT idControl, notificado FROM control WHERE fecha = '".formatFecha(formatFechaObj($diasFaltantes['fecha']))."' AND idDiagnostico = ".$diagnostico['idDiagnostico'];
			$controlResult = ejecutaQuery($query);
			$control = devuelveRowAssoc($controlResult);
			
			$query = "SELECT celularContacto, nombre FROM pacientes WHERE idPaciente IN(SELECT idPaciente FROM diagnostico WHERE idDiagnostico = ".$diagnostico['idDiagnostico'].")";
			
			$contactoResutl = ejecutaQuery($query);
			$contacto = devuelveRowAssoc($contactoResutl);
			
			if($contacto['celularContacto'] != null && $contacto['celularContacto'] != "" && $control['notificado'] != 1)
			{
				$smsMessage = "Estimado(a) ".$contacto['nombre']." te recordamos que en 3 dias debes asistir a tu centro de salud por tu medicamento";
				//sendSMS($contacto['celularContacto'], $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
				/*
				sendSMS("5585301233", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
				sendSMS("5539214946", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
				sendSMS("5541932463", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
				*/
			}
			
			$query = "UPDATE control SET notificado = 1 WHERE idControl = ".$control['idControl'];
			//ejecutaQuery($query);
			
		}
	}
	/*
	$smsMessage = "TEST REMINDER";
	sendSMS("9611078474", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
	*/
?>