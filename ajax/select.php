<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/funciones.php');
	require_once('../include/log.php');
	
	if(isset($_SESSION[ID_USR_SESSION]))
	{
		$connectionBD = conectaBD();
		
		if($connectionBD === FALSE)
			die('<br /><div align="center" class="error_sql"><strong>ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion" 
				<u>var_global.php</u>."</strong></div>');
		
		// Debido a que los objetos vacios en JavaScript los establece como undefined
		foreach( $_POST as $key => $value )
			if($_POST[$key] == 'undefined')
				$_POST[$key] = NULL;
		
		$resultado = '';
		// Recibe
		//tipo:'', edo:'', juris:'', muni:'', locali:'', insti:''
		
		switch($_POST['tipo'])
		{
			/*********************************************************************************************/
			case 'juris':
				$qr_juris = 'SELECT idCatJurisdiccion, nombre FROM catJurisdiccion WHERE idCatEstado = '.$_POST['edo'];
				$rs_juris = ejecutaQuery($qr_juris);
				
				while($juris = devuelveRowAssoc($rs_juris))
					$resultado .= $juris['idCatJurisdiccion'].'='.htmlentities(ucwords(mb_strtolower($juris['nombre']))).' ['.str_pad($juris['idCatJurisdiccion'],2,'0',STR_PAD_LEFT).']@';
				
				echo $resultado;
			break;
			
			/*********************************************************************************************/
			case 'muni':
				$qr_muni = 'SELECT idCatMunicipio, nombre FROM catMunicipio WHERE idCatEstado = '.$_POST['edo'];
				
				if($_POST['juris']!='')
					$qr_muni .= ' AND idCatJurisdiccion = '.$_POST['juris'];
				
				$qr_muni .= ' ORDER BY nombre';
				$rs_muni = ejecutaQuery($qr_muni);
				
				while($muni = devuelveRowAssoc($rs_muni))
					$resultado .= $muni['idCatMunicipio'].'='.htmlentities(ucwords(mb_strtolower($muni['nombre']))).' ['.str_pad($muni['idCatMunicipio'],3,'0',STR_PAD_LEFT).']@';
				
				echo $resultado;
			break;
			
			/*********************************************************************************************/
			case 'locali':
				$qr_locali = 'SELECT idCatLocalidad, nombre FROM catLocalidad WHERE idCatEstado = '.$_POST['edo'].' AND idCatMunicipio = '.$_POST['muni'].' ORDER BY nombre';
				$rs_locali = ejecutaQuery($qr_locali);
				
				while($locali = devuelveRowAssoc($rs_locali))
					$resultado .= $locali['idCatLocalidad'].'='.htmlentities(ucwords(mb_strtolower($locali['nombre']))).' ['.str_pad($locali['idCatLocalidad'],4,'0',STR_PAD_LEFT).']@';
				
				echo $resultado;
			break;
			
			/*********************************************************************************************/
			case 'uni':
				$qr_uni = 'SELECT idCatUnidad, nombreUnidad, institucion FROM catUnidad WHERE idCatEstado='.$_POST['edo'];
		
				if($_POST['juris']!='')
					$qr_uni .= ' AND idCatMunicipio IN (SELECT idCatMunicipio FROM catMunicipio WHERE idCatEstado='.$_POST['edo'].' AND idCatJurisdiccion = '.$_POST['juris'].')';
				if($_POST['muni']!='')
					$qr_uni .= ' AND idCatMunicipio = '.$_POST['muni'];
				if($_POST['locali']!='')
					$qr_uni .= ' AND idCatLocalidad = '.$_POST['locali'];
				
				$qr_uni .= ' ORDER BY nombreUnidad';
				
				$rs_uni = ejecutaQuery($qr_uni);
				
				while($uni = devuelveRowAssoc($rs_uni))
					$resultado .= $uni['idCatUnidad'].'='.htmlentities(ucwords(mb_strtolower($uni['nombreUnidad']))).' ['.$uni['idCatUnidad'].']@';
				
				echo $resultado;
			break;
		}
	}
	else
		return FALSE;
?>