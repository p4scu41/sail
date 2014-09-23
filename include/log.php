<?PHP
	/* Registra los errores en un archivo
	 --------------------------------------------------------------------------------
	|  Fecha  |  ID_Usuario  |  Usuario  |  Archivo  |  IP  |  HOST  |  Descripcion  |
	 --------------------------------------------------------------------------------*/
	// Habilitar en el servidor 
	// HostnameLookups On -> Obtiene el nombre de la maquina cliente (httpd.conf)
	// track_errors true  -> Obtiene el ultimo error en php (php.ini), guardar el mensaje en la variable $php_errormsg
	function registra_log($archivo, $descripcion)
	{
		$archivo_log = fopen("log/log_sistema.log",'a');
		/*$codigo_errores = NULL;
		$codigo_errores[1] = E_ERROR;
		$codigo_errores[2] = "E_WARNING";
		$codigo_errores[4] = "E_PARSE";
		$codigo_errores[8] = "E_NOTICE";
		$codigo_errores[16] = "E_CORE_ERROR";
		$codigo_errores[32] = "E_CORE_WARNING";
		$codigo_errores[64] = "E_COMPILE_ERROR";
		$codigo_errores[128] = "E_COMPILE_WARNING";
		$codigo_errores[256] = "E_USER_ERROR";
		$codigo_errores[512] = "E_USER_WARNING";
		$codigo_errores[1024] = "E_USER_NOTICE";
		$codigo_errores[2048] = "E_STRICT";
		$codigo_errores[4096] = "E_RECOVERABLE_ERROR";
		$codigo_errores[8191] = "E_ALL";

		$ultimo_error = error_get_last();

		 // Registra el error agregando el ultimo error que tiene guardado PHP
		$registro = "[*] ".date('d-M-Y H:i:s')." | ".$_SESSION['id_user']." | ".$_SESSION['user']." | ".
						$archivo." | ".$_SERVER['REMOTE_ADDR']." | ".gethostbyaddr($_SERVER['REMOTE_ADDR'])." | ".$descripcion.
						" | ".$codigo_errores[$ultimo_error['type']].": ".$ultimo_error['message']." in ".$ultimo_error['file'].
						" on line ".$ultimo_error['line']."\r\n";*/
		
		$exrg_retorno_tab = array('/[\n\r\t]/', '/[ \t\n\r]+/', '(\r\n\t\t+)');
		
		$registro = "[ ".date('d-M-Y H:i:s')." ] | ".$_SESSION[ID_USR_SESSION]." | ".$_SESSION[NAME_USR_SESSION]." | ".
						$archivo." | ".$_SERVER['REMOTE_ADDR']." | ".gethostbyaddr($_SERVER['REMOTE_ADDR'])." | ".preg_replace($exrg_retorno_tab, array('',' ',''), $descripcion)."\r\n";
		 
		if (fwrite($archivo_log, $registro) === FALSE)
			echo "<br />ERROR: No se puede escribir al archivo de error log_sistema.log<br />";
			
		fclose($archivo_log);
	}
	
	/* Existe una funcion para control de un log de errores
	error_log -- Enviar un mensaje de error a alguna parte
	bool error_log ( string mensaje [, int tipo_mensaje [, string destino [, string cabeceras_extra]]] )

	mensaje
	El mensaje de error a ser registrado. 
	
	tipo_mensaje
	Indica a d�nde debe ir el mensaje. Los tipos de mensaje posibles son los siguientes: 	
	
	Tipos de registro de error_log()	
	0 mensaje es enviado al registro de sistema de PHP, usando el mecanismo de registro del Sistema Operativo o un archivo, dependiendo del valor de la directiva de configuraci�n error_log. Esta es la opci�n predeterminada.  
	1 mensaje es enviado por correo electr�nico a la direcci�n en el par�metro destino. Este es el �nico tipo de mensaje en donde el cuarto par�metro, cabeceras_extra, es usado.  
	2 mensaje es enviado a trav�s de la conexi�n de depuraci�n de PHP. Esta opci�n est� disponible �nicamente si la depuraci�n remota ha sido habilitada. En este caso el par�metro destino indica el nombre de host o direcci�n IP y, opcionalmente, el n�mero de puerto del socket que recibe la informaci�n de depuraci�n. Esta opci�n s�lo est� disponible en PHP 3.  
	3 mensaje es agregado al final del archivo destino. Un salto de l�nea no es agregado autom�ticamente al final de la cadena mensaje.  
	
	destino
	El destino. Su significado depende del par�metro tipo_mensaje como se describi� anteriormente. 
	
	cabeceras_extra
	Las cabeceras adicionales. Es usado cuando el par�metro tipo_mensaje es definido a 1. Este tipo de mensaje usa la misma funci�n interna que usa mail(). 
	*/

?>