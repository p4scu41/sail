<?php
    @session_start();
    
    require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/log.php');
    require_once('../include/fecha_hora.php');
    require_once('../include/funciones.php');
    require_once('../include/clases/KML.php');
    
    
    $connectionBD = conectaBD();
    
    $archivoMKL = new KML();
    
    $archivoMKL->queryKML($_POST['tipo_paciente'], $_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['estado']);
    $archivoMKL->doKML();
    $archivoMKL->getKML();
    
    $connectionBD = closeConexion();
?>