<?PHP
//-------- Variables para la conexion con la Base de Datos ---------//
define("SERVER","");
define("USER","");
define("PASS","");
define("DBNAME","");
$connectionBD = FALSE; // Variable GLOBAL de conexion a la Base de Datos

//-------- Nombre de las constantes de sesion ---------//
define("ID_USR_SESSION","id_usr_lepra");
define("NAME_USR_SESSION","usr_lepra");
define("TIPO_USR_SESSION","tipo_usr_lepra");
define("EDO_USR_SESSION","edo_usr_lepra");
define("JUR_USR_SESSION","jur_usr_lepra");
define("SEGURO","true");
define("ID_ADMIN_NACIONAL",3);

define('TOKEN','mod');

define("TITULO_SISTEMA","Programa de Prevención y Control de la Lepra");
define("TITULO_SISTEMA_2","Sistema Automatizado de Información en Lepra<br>(SAIL)");

define("CARPETA_RAIZ","lepra.chiapas/");

define("MAX_PER_PAGE",25); //Numero de registros por pagina en busqueda

// Definir la zona horaria predeterminada a usar
date_default_timezone_set('America/Mexico_City');
?>
