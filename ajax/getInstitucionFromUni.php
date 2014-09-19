<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/fecha_hora.php');
	require_once('../include/log.php');
	require_once('../include/funciones.php');
	
	if(isset($_SESSION[ID_USR_SESSION]) && $_POST['uni']!='')
	{
		$connectionBD = conectaBD();
		
        $rsInsti = ejecutaQuery('SELECT [institucion] FROM [lepra].[dbo].[catUnidad] WHERE [idCatUnidad]=\''.$_POST['uni'].'\'');
        
        if(!$rsInsti) {
            echo '11';
            return;
        }
        $insti = devuelveRowAssoc($rsInsti);
        
        $rsIDinsti = ejecutaQuery('SELECT [idCatInstituciones] FROM [catInstituciones] WHERE [nombreCompleto]=\''.$insti['institucion'].'\'');
		
        if(!$rsIDinsti) {
            echo '11';
            return;
        }
        $IDinsti = devuelveRowAssoc($rsIDinsti);
		
		if(!$IDinsti['idCatInstituciones'])
			echo '11';
		else
			echo $IDinsti['idCatInstituciones'];
	}
	else
		echo '0';
?>