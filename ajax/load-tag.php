<?php 

/*$image['Image'] = array('id'=>1, 'Tags'=>array());

$image['Image']['Tags'][] = array('id'=>1,
								'text'=>'Nodulos Aislados',
								'left'=>150,
								'top'=>50,
								'isDeleteEnable'=>true,
								'tipoLesion'=>1);

echo json_encode($image);*/
session_start();
	
require_once('../include/var_global.php');
require_once('../include/bdatos.php');
require_once('../include/fecha_hora.php');
require_once('../include/log.php');
require_once('../include/funciones.php');

$connectionBD = conectaBD();

echo '{
	"Image" : [
		{
		"id":1,
		"Tags":[';

$strLesiones = '';
$tipoLesionDiagrama = NULL;


if(isset($_SESSION[ID_USR_SESSION]))
{
    // Siempre se recibe el idPaciente 
    if(!empty($_GET['id']))
    {
        $rsTipoLesionDiagrama = ejecutaQuery('SELECT [idCatTipoLesionDiagrama],[descripcion] FROM [catTipoLesionDiagrama]');

        while($tipo = devuelveRowAssoc($rsTipoLesionDiagrama))
            $tipoLesionDiagrama[$tipo['idCatTipoLesionDiagrama']] = $tipo['descripcion'];

        $query = 'SELECT [idLesion],[idCatTipoLesion],[x],[y],[w],[h] FROM [diagramaDermatologico] WHERE [idPaciente]='.$_GET['id'];
		//$query = 'SELECT [idLesion],[idCatTipoLesion],[x],[y],[w],[h] FROM [diagramaDermatologico] WHERE [idPaciente]=0';
        $result = ejecutaQuery($query);

        if(devuelveNumRows($result) == 0) {
            $query = 'SELECT [idLesion],[idCatTipoLesion],[x],[y],[w],[h] FROM [diagramaDermatologico] 
                         WHERE [idDiagnostico] IN (SELECT  TOP 1 idDiagnostico FROM diagnostico WHERE idPaciente = '.$_GET['id'].' ORDER BY idDiagnostico ASC)';
            $result = ejecutaQuery($query);
        }
		
		$hola = "";
		foreach($_GET as $key => $valu)
			$hola .= $key."  ".$valu.",";
		
		$hola = trim($hola,",");

        while($lesion = devuelveRowAssoc($result)){
            $strLesiones .= '{
                    "id":'.$lesion['idLesion'].',
                    "text":"'.$tipoLesionDiagrama[$lesion['idCatTipoLesion']].'",
                    "left":'.$lesion['x'].',
                    "top":'.$lesion['y'].',
                    "width":'.$lesion['w'].',
                    "height":'.$lesion['h'].',
                    "isDeleteEnable": true,
                    "tipoLesion": '.$lesion['idCatTipoLesion'].'
                    },';
        }

        echo substr($strLesiones, 0, -1);  
    }
}
echo '		]
		}
	]
}';
?>