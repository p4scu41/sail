<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/funciones.php');
	require_once('../include/log.php');
	require_once('../include/fecha_hora.php');
	require_once('../include/HTML.class.php');

	$connectionBD = conectaBD();

	$objHTML = new HTML();
	
	if(isset($_SESSION[ID_USR_SESSION]))
	{
		// Siempre se recibe el idPaciente 
		if(!empty($_POST['lesionId']))
		{
			$idLesion = explode("_",$_POST['lesionId']);

			$rsTipoLesionDiagrama = ejecutaQuery('SELECT [idCatTipoLesionDiagrama],[descripcion] FROM [catTipoLesionDiagrama]');
	
			while($tipo = devuelveRowAssoc($rsTipoLesionDiagrama))
				$tipoLesionDiagrama[$tipo['idCatTipoLesionDiagrama']] = $tipo['descripcion'];
	
			$query = 'SELECT [idLesion],[idCatTipoLesion],[imgUrl] FROM [diagramaDermatologico] WHERE [idLesion]='.$idLesion[1];
			$result = ejecutaQuery($query);
			
			$imagenes = array();
			
			while($lesion = devuelveRowAssoc($result))
			{
				$imagenes = explode(";;;",$lesion['imgUrl']);
			}

			echo '<table width="100%" align="center"><tr><td align="center">';
			
			if(count($imagenes) > 0 && $imagenes[0] != NULL && $imagenes[0] != "")
			{
				echo '<div id="my-slideshow_'.$idLesion[1].'"><ul class="bjqs">';
				foreach($imagenes as $key => $imagen)
				{
					if($imagen != NULL && $imagen != "")
					{
						echo '<li>';
						echo '<img src="pacienteImg/'.$imagen.'" />';
						echo '</li>';
					}
				}
				echo '</ul></div>';
			}else
			{
				echo "No se encontro ninguna imagen";
			}
			echo '</td></tr>';
			
			echo '<tr><td align="center"><br /><br />';
			echo '<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>';
			$objHTML->startForm("uploadPhoto", "ajax/uploadPhoto.php", "POST", array("target"=>"upload_target", "enctype" => "multipart/form-data"));
			echo '<input type="file" name="nuevaFoto" id="nuevaFoto" />';
			$objHTML->inputHidden('idLesion',$idLesion[1]);
			$objHTML->inputSubmit("cargaFoto","Cargar Foto");
			$objHTML->endFormOnly();
			echo "</td></tr></table>";
		}
	}
?>