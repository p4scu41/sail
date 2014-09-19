<?php
// Si el archivo se manda a llamar desde el archivo index.php se ejecuta
if($SEGURO == TRUE)
{
	// Varibales globales de configuracion de todo el sistema
	require_once(__DIR__.'\var_global.php');
		
	// Cargamos las funciones globales
	require_once(__DIR__.'\funciones.php');
	require_once(__DIR__.'\bdatos.php');
	require_once(__DIR__.'\sesion.php');
	require_once(__DIR__.'\fecha_hora.php');
	require_once(__DIR__.'\log.php');
	
	require_once(__DIR__.'\HTML.class.php');
	require_once(__DIR__.'\Catalogo.class.php');
	require_once(__DIR__.'\Select.class.php');
	
	//-------- Control de errores---------//
	//error_reporting(0); // No mostrar los mensajes de errores
	//ini_set("display_errors","Off"); // No Imprimir a pantalla los mensajes
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED); // Mostrar todos los mensajes de error excepto los E_NOTICE y los E_WARNING
	// Otra opcion para inicializarlo ini_set('error_reporting', E_ALL);
	ini_set("display_errors","On"); // Imprimir a pantalla los mensajes
	ini_set("error_append_string","<br /><div class='error' align='center'><h2>OCURRIO UN ERROR, CONTACTE CON EL ADMINISTRADOR DEL SISTEMA</h2></div>"); // Mensaje a mostrar despues de un error
	ini_set("error_log",$_SERVER['DOCUMENT_ROOT'].CARPETA_RAIZ."log/php_log_sistema.log"); // Archivo donde se guarda los mensajes de error, NOTA: el archivo dene tener permisos de escritura y lectura
	
	// Conecta a la Base de Datos
	$connectionBD = conectaBD();
	
	if($connectionBD === FALSE)
		die('<br /><div align="center"><h2>ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion" 
			<u>var_global.php</u>."</h2></div>');
}
?>