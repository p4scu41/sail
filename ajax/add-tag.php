<?PHP 
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/log.php');
	
    $etiqueta = NULL;
    
	if(isset($_SESSION[ID_USR_SESSION]))
	{
		$connectionBD = conectaBD();
		
		if($connectionBD === FALSE)
			die('<br /><div align="center" class="error_sql"><strong>ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion" 
				<u>var_global.php</u>."</strong></div>');
		
		$rs_etiquetas = ejecutaQuery('SELECT [descripcion] FROM [catTipoLesionDiagrama] WHERE [idCatTipoLesionDiagrama]='.$_REQUEST['tipoLesion']);
		$etiqueta = devuelveRowAssoc($rs_etiquetas);
	}
?>

{
	"result":true,
	"tag": {
		"id":<?php echo rand(); ?>,
		"text": "<?php echo $etiqueta['descripcion'] ?>",
		"left": <?php echo $_REQUEST['left'] ?>,
		"top": <?php echo $_REQUEST['top'] ?>,
		"width": <?php echo $_REQUEST['width'] ?>,
		"height": <?php echo $_REQUEST['height'] ?>,
		"isDeleteEnable": true
	}
}