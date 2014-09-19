<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/fecha_hora.php');
	require_once('../include/log.php');
	require_once('../include/funciones.php');
	
	if(isset($_SESSION[ID_USR_SESSION]) && $_POST['fecha']!='')
	{
		$connectionBD = conectaBD();
		
        if(!isDate($_POST['fecha']))
            return '';
        
		$query = 'SELECT [no_semana] FROM [catSemanaEpidemiologica] WHERE \''.formatFecha($_POST['fecha']).'\'>=[fecha_inicio] AND \''.formatFecha($_POST['fecha']).'\'<=[fecha_fin]';
		$result = ejecutaQuery($query);
		$datos = devuelveRowAssoc($result);
		
		if(!$datos['no_semana'])
			echo '0';
		else
			echo $datos['no_semana'];
	}
	else
		echo '0';
?>