
<?PHP
	session_start();
	
	require_once('../include/var_global.php');
	require_once('../include/bdatos.php');
	require_once('../include/funciones.php');
	require_once('../include/log.php');
	require_once('../include/fecha_hora.php');
    require_once('../include/clases/controlCalidad.php');
	
	if(isset($_SESSION[ID_USR_SESSION]))
	{
		$connectionBD = conectaBD();
		
		if($connectionBD === FALSE)
            die(json_encode(array('error'=>true, 'msj'=>'ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion')));
		
        beginTransaccion();
        
		$query = '';
        $objCalidad = new controlCalidad();
        
        switch ($_POST['tipo_estudio']) {
            case 'bacilos':
                $objCalidad->obtenerByBacilos($_POST['id_estudio']);
                $objCalidad->idEstudioBac = $_POST['id_estudio'];
            break;
            case 'histo';
                $objCalidad->obtenerByHisto($_POST['id_estudio']);
                $objCalidad->idEstudioHis = $_POST['id_estudio'];
            break;
        }
        
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
        
        if(empty($objCalidad->idcontrolCalidad))
            $objCalidad->insertarBD();
        else
            $objCalidad->modificarBD();

        if($objCalidad->error) {
            rollbackTransaccion();
            echo json_encode(array('error'=>true, 'msj'=>'Error al guardar los datos de la calidad de la muestra. '.$objCalidad->msgError));
        } else {
            commitTransaccion();
            echo json_encode(array('error'=>false, 'msj'=>'Datos procesados correctamente'));
        }
	}
	else
		echo json_encode(array('error'=>true, 'msj'=>'Error al procesar los datos, intentelo nuevamente'));
?>
