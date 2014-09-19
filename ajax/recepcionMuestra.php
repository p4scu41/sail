
<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/funciones.php');
	require_once('../include/log.php');
	require_once('../include/fecha_hora.php');
    //require_once('../include/clases/controlCalidad.php');
	
	if(isset($_SESSION[ID_USR_SESSION]))
	{
		$connectionBD = conectaBD();
		
		if($connectionBD === FALSE)
            die(json_encode(array('error'=>true, 'msj'=>'ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion')));
		
        beginTransaccion();
        
		$query = '';
        //$objCalidad = new controlCalidad();
        
        switch ($_POST['tipo_estudio']) {
            case 'bacilos':
                $query = "UPDATE [estudiosBac]
                    SET [fechaRecepcion] = '".formatFechaObj($_POST['fecha_recepcion'], 'Y-m-d')."'
                       ,[folioLaboratorio] = '".$_POST['folio_laboratorio']."'";
                if(!empty($_POST['rechazo_muestra'])){
                       $query .= " ,[muestraRechazada] = '".$_POST['rechazo_muestra']."'
                       ,[idCatMotivoRechazo] = '".$_POST['criterio_rechazo']."'
                       ,[otroMotivoRechazo] = '".utf8_decode($_POST['otro_criterio_rechazo'])."'";
                }
                
                $query .= " WHERE idEstudioBac = ".$_POST['id_estudio'];
                //$objCalidad->idEstudioBac = $_POST['id_estudio'];
            break;
            case 'histo';
                 $query = "UPDATE [estudiosHis]
                    SET [fechaRecepcion] = '".formatFechaObj($_POST['fecha_recepcion'], 'Y-m-d')."'
                       ,[folioLaboratorio] = '".$_POST['folio_laboratorio']."'";
                if(!empty($_POST['rechazo_muestra'])){
                       $query .= " ,[muestraRechazada] = '".$_POST['rechazo_muestra']."'
                       ,[idCatMotivoRechazo] = '".$_POST['criterio_rechazo']."'
                       ,[otroMotivoRechazo] = '".utf8_decode($_POST['otro_criterio_rechazo'])."'";
                }
                
                $query .= " WHERE idEstudioHis = ".$_POST['id_estudio'];
                //$objCalidad->idEstudioHis = $_POST['id_estudio'];
            break;
        }
        
		$result = ejecutaQuery($query);
		
		if(!$result) {
            rollbackTransaccion();
			echo json_encode(array('error'=>true, 'msj'=>'Error al procesar los datos, intentelo nuevamente'));
        }
		/*else {
            $objCalidad->calidadMuestra = $_POST['calidadMuestra'];
            $objCalidad->sinMuestra = $_POST['sinMuestra'];
            $objCalidad->sinElemeCelu = $_POST['sinElemeCelu'];
            $objCalidad->abunEritro = $_POST['abunEritro'];
            $objCalidad->otrosCalidadMuestra = $_POST['otrosCalidadMuestra'];

            $objCalidad->calidadFrotis = $_POST['calidadFrotis'];
            $objCalidad->calidadFrotisTipo = $_POST['calidadFrotisTipo'];
            $objCalidad->otrosCalidadFrotis = $_POST['otrosCalidadFrotis'];

            $objCalidad->calidadTincion = $_POST['calidadTincion'];
            $objCalidad->crisFucsi = $_POST['crisFucsi'];
            $objCalidad->preciFucsi = $_POST['preciFucsi'];
            $objCalidad->calenExce = $_POST['calenExce'];
            $objCalidad->decoInsufi = $_POST['decoInsufi'];
            $objCalidad->otrosCalidadTincion = $_POST['otrosCalidadTincion'];

            $objCalidad->calidadLectura = $_POST['calidadLectura'];
            $objCalidad->falPosi = $_POST['falPosi'];
            $objCalidad->falNega = $_POST['falNega'];
            $objCalidad->difMas2IB = $_POST['difMas2IB'];
            $objCalidad->difMas25IM = $_POST['difMas25IM'];
            $objCalidad->otrosCalidadLectura = $_POST['otrosCalidadLectura'];

            $objCalidad->calidadResultado = $_POST['calidadResultado'];
            $objCalidad->soloSimbCruz = $_POST['soloSimbCruz'];
            $objCalidad->soloPosiNega = $_POST['soloPosiNega'];
            $objCalidad->noEmiteIM = $_POST['noEmiteIM'];
            $objCalidad->otrosCalidadResultado = $_POST['otrosCalidadResultado'];
            $objCalidad->recomendacion = $_POST['recomendacion'];
            
            $objCalidad->insertarBD();
            
            if($objCalidad->error) {
                rollbackTransaccion();
                echo json_encode(array('error'=>true, 'msj'=>'Error al guardar los datos de la calidad de la muestra. '.$objCalidad->msgError));
            } else {
                commitTransaccion();
                echo json_encode(array('error'=>false, 'msj'=>'Datos procesados correctamente'));
            }
        }*/
        else {
            commitTransaccion();
            echo json_encode(array('error'=>false, 'msj'=>'Datos procesados correctamente'));
        }
	}
	else
		echo json_encode(array('error'=>true, 'msj'=>'Error al procesar los datos, intentelo nuevamente'));
?>
