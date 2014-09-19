<?PHP
function errorQuery($query)
{
	$array_error = sqlsrv_errors();
	
	if(count($array_error)!=0)
		echo '<div class="error" align="center">';
	
	foreach($array_error as $error)
	{
		// Registra los errores en un archivo log
		registra_log(str_replace('/'.CARPETA_RAIZ.'?','',$_SERVER['REQUEST_URI']), 'SQLSTATE: '.$error['SQLSTATE'].', code: '.$error['code'].', message: '.str_replace('[Microsoft][SQL Server Native Client 10.0][SQL Server]','',$error['message']).', Query: '.$query);
		
		// Si esta deshabilitado mostrar errores de php, tampoco debemos mostrar los errores
		if(ini_get('display_errors') == "On" || ini_get('display_errors') == 1)
			echo '<strong><u>SQLSTATE</u>: </strong> '.$error['SQLSTATE'].', <strong><u>CODE</u>: </strong> '.$error['code'].', <strong><u>MESSAGE</u>: </strong> '. str_replace('[Microsoft][SQL Server Native Client 10.0][SQL Server]','',$error['message']).', <br /><strong><u>QUERY</u>: </strong>'.$query.'<br /><br />';
	}
	
	if(count($array_error)!=0)
		echo '</div>';
}

function ejecutaQuery($query)
{
	global $connectionBD;
	
	$result = sqlsrv_query( $connectionBD, $query, array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET) ) ; // OJO: Revisar el funcionamiento de Scrollable
	
	if($result === FALSE)
	{
		echo msj_error('ERROR: No se puede ejecutar la consulta. Notifique al administrador del sistema del error encontrado.');
		errorQuery($query);
		return FALSE;
	}
	else
		return $result;
}

// OJO: Se duplica para hacer uso de esta funcion desde las clases internas de la plataforma
function ejecutaQueryClases($query)
{
	global $connectionBD;
	
    //echo $query.'<br>';
	$result = sqlsrv_query( $connectionBD, $query);
	//Eliminar
	//registra_log(str_replace('/'.CARPETA_RAIZ.'?','',$_SERVER['REQUEST_URI']), 'SQLSTATE: '.$error['SQLSTATE'].', code: , message: , Query: '.$query);
	
	if($result === FALSE)
	{
		errorQuery($query);
		return "ERROR: No se puede ejecutar la consulta. Notifique al administrador del sistema del error encontrado.";
	}
	else
		return $result;
}

function conectaBD()
{
	$connectionInfo = array( "UID"=>USER,"PWD"=>PASS,"Database"=>DBNAME );
	$connectionBD = sqlsrv_connect( SERVER, $connectionInfo );
	
	if($connectionBD === FALSE)
	{
		echo msj_error('ERROR: No se pudo conectar con la Base de Datos.');		
		errorQuery('Conexion con la Base de Datos');
		return FALSE;
	}
	else
	{
		// Configuramos el lenguaje de la sesion en us_english,
		// de lo contrario se presentan conflictos con la fecha
		/* Otros comandos utiles
			SP_CONFIGURE 'default language'
			SP_HELPLANGUAGE
			SET LANGUAGE 'español'
		*/
		//ejecutaQuery('SET LANGUAGE \'us_english\'');
		
		return $connectionBD;
	}
}

function closeConexion(){
	global $connectionBD;
	
	@sqlsrv_close( $connectionBD );
}

function devuelveRowAssoc($result)
{
	$row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC );
	
	if ($row == NULL) // Si ya termino de recorrer todas las fila
		sqlsrv_free_stmt( $result ); // Se libera de memoria el resultado
	
	return $row;
}

function devuelveRowArray($result)
{
	$row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_NUMERIC );
	
	if ($row == NULL) // Si ya termino de recorrer todas las fila
		sqlsrv_free_stmt( $result ); // Se libera de memoria el resultado
	
	return $row;
}

function devuelveNumRows($result)
{
	return sqlsrv_num_rows($result);
}

function beginTransaccion()
{
	global $connectionBD;
	
	if (sqlsrv_begin_transaction( $connectionBD ) === false)
		errorQuery('BEGIN TRANSACTION');
}

function commitTransaccion()
{
	global $connectionBD;
	
	sqlsrv_commit( $connectionBD );
}

function rollbackTransaccion()
{
	global $connectionBD;
	
	sqlsrv_rollback( $connectionBD );
}

// Obtener el ultimo ID que se inserto
function get_last_id()
{
	// SELECT id = @@IDENTITY;
	// SELECT IDENT_CURRENT('tabla') as id;
	// SELECT SCOPE_IDENTITY() as id
	$queryID = "SELECT @@IDENTITY AS ID";
	$result = ejecutaQuery($queryID);
	$id = devuelveRowAssoc($result);
	
	return $id['ID'];
}
?>