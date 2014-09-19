
<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/funciones.php');
	require_once('../include/log.php');
	require_once('../include/fecha_hora.php');
	
	if(isset($_SESSION[ID_USR_SESSION]))
	{
		$connectionBD = conectaBD();
		
		if($connectionBD === FALSE)
            die(json_encode(array('error'=>true, 'msj'=>'ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion')));
		        
		$query = '';
        
        switch ($_POST['tipo']) {
            case 'histo':
                $query = 'SELECT [idEstudioHis] as idMuestra
                        ,[fechaRecepcion]
                        ,[folioLaboratorio]
                        ,[muestraRechazada]
                        ,[idCatMotivoRechazo]
                        ,[otroMotivoRechazo]
                        FROM [estudiosHis]
                        WHERE [idEstudioHis] = '.$_POST['id'];
            break;
            case 'bacilos':
                $query = 'SELECT [idEstudioBac] as idMuestra
                    ,[fechaRecepcion]
                    ,[folioLaboratorio]
                    ,[muestraRechazada]
                    ,[idCatMotivoRechazo]
                    ,[otroMotivoRechazo]
                    FROM [estudiosBac]
                    WHERE [idEstudioBac] = '.$_POST['id'];       
        }
        
		$result = ejecutaQuery($query);
		
		if(!$result) {
			echo json_encode(array('error'=>true, 'msj'=>'Error al obtener los datos, intentelo nuevamente'));
        } else {
            $registro = devuelveRowAssoc($result);
            $registro['error'] = false;
            $registro['fechaRecepcion'] = formatFechaObj($registro['fechaRecepcion']);
            
            echo json_encode($registro);
        }
	}
	else
		echo json_encode(array('error'=>true, 'msj'=>'Error al procesar los datos, intentelo nuevamente'));
?>
