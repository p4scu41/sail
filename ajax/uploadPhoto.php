<?php
session_start();
	
require_once('../include/var_global.php');
require_once('../include/bdatos.php');
require_once('../include/fecha_hora.php');
require_once('../include/log.php');
require_once('../include/funciones.php');
require_once('../include/clasesLepra.php');
$connectionBD = conectaBD();

if($_POST['cargaFoto'])
{
	$query = 'SELECT [idLesion],[idCatTipoLesion],[imgUrl] FROM [diagramaDermatologico] WHERE [idLesion]='.$_POST['idLesion'];
	$result = ejecutaQuery($query);
	
	$imagenes = array();
	
	while($lesion = devuelveRowAssoc($result))
	{
		$imagenes = explode(";;;",$lesion['imgUrl']);
		$imgUrlOld = $lesion['imgUrl'];
	}
	$numeroFoto = count($imagenes)+1;
	
	$new_name = "photo_".$numeroFoto."_".$_POST['idLesion'];
	$new_name2 = "nuevaFoto";
	
	$ext = explode("/",$_FILES[$new_name2]['type']);
	$extencion = $ext[1];
	
	if($_FILES[$new_name2]["size"] > 0)
	{
		$dir = "../pacienteImg/";
		
		$new_name .= ".".$extencion;
		$lesionDiagrama = new DiagramaDermatologico();
		
		move_uploaded_file($_FILES[$new_name2]['tmp_name'], $dir.$new_name);
		
		$lesionDiagrama->idLesion = $_POST['idLesion'];
		$lesionDiagrama->imgUrl = $imgUrlOld.$new_name.";;;";
		$lesionDiagrama->updateImgUrl();
	}
}
?>