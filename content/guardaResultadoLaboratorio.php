<?php
//$objCalidad = new controlCalidad();

$errorSql = false;
beginTransaccion();

if($_POST['guarda_resultado_bacilos'])
{
	$resultBacilos = new EstudioBac();
    $resultBacilos->obtenerBD($_GET['id']);
    //$objCalidad->obtenerByBacilos($_GET['id']);
	
	$resultBacilos->idEstudioBac = $_GET['id'];
    //$resultBacilos->idDiagnostico = 0;
	$resultBacilos->fechaRecepcion = $_POST['fecha_recepcion_bacilos'];
	$resultBacilos->muestraRechazada = $_POST['rechazo_muestra_bacilos'];
	$resultBacilos->idCatMotivoRechazo = $_POST['criterio_rechazo_bacilos'];
	$resultBacilos->otroMotivoRechazo = $_POST['otro_criterio_rechazo_bacilos'];
	$resultBacilos->fechaResultado = $_POST['fecha_resultado_bacilos'];
	$resultBacilos->idCatBacFrotis1 = $_POST['ind_baci_ft1'];
	$resultBacilos->idCatBacFrotis2 = $_POST['ind_baci_ft2'];
	$resultBacilos->idCatBacFrotis3 = $_POST['ind_baci_ft3'];
	$resultBacilos->bacPorcViaFrotis1 = $_POST['bacilos_ft1'];
	$resultBacilos->bacPorcViaFrotis2 = $_POST['bacilos_ft2'];
	$resultBacilos->bacPorcViaFrotis3 = $_POST['bacilos_ft3'];
	$resultBacilos->bacCalidadAdecFrotis1 = $_POST['calidad_muestra_ft1'];
	$resultBacilos->bacCalidadAdecFrotis2 = $_POST['calidad_muestra_ft2'];
	$resultBacilos->bacCalidadAdecFrotis3 = $_POST['calidad_muestra_ft3'];
	$resultBacilos->bacIdCatTiposBacilosFrotis1 = $_POST['tipo_bacilo_ft1'];
	$resultBacilos->bacIdCatTiposBacilosFrotis2 = $_POST['tipo_bacilo_ft2'];
	$resultBacilos->bacIdCatTiposBacilosFrotis3 = $_POST['tipo_bacilo_ft3'];	
	$resultBacilos->idCatBac = $_POST['ib_promedio'];
	$resultBacilos->bacIM = $_POST['im_promedio'];
	$resultBacilos->bacObservaciones = $_POST['obser_bacilos'];	
	$resultBacilos->idCatEstadoLaboratorio = $_POST['edoLab'];
	$resultBacilos->idCatJurisdiccionLaboratorio = $_POST['jurisLab'];
	$resultBacilos->idCatAnalistaLab = $_POST['analista'];
	$resultBacilos->idCatSupervisorLab = $_POST['supervisor'];
	
	$resultBacilos->modificarBD();
	
	if($resultBacilos->error){
		//echo $resultBacilos->msgError;
        $errorSql = true;
	}
}
else if($_POST['guarda_resultado_histo'])
{
	$resultHisto = new EstudioHis();
    $resultHisto->obtenerBD($_GET['id']);
    //$objCalidad->obtenerByHisto($_GET['id']);

	if($resultHisto->idPaciente == "" && $resultHisto->idPaciente == NULL)
	{
		$newDiagnostico = new Diagnostico();
		$newDiagnostico->obtenerBD($resultHisto->idDiagnostico);

        if($newDiagnostico->error){
            echo msj_error('Ocurri&oacute; un ERROR al recuperar los datos del diagnostico a partir del estudio');
            //echo $newDiagnostico->msgError;
            $errorSql = true;
        }
		$resultHisto->idPaciente = $newDiagnostico->idPaciente;
	}

	$pacienteLepra = new Paciente();
	$pacienteLepra->obtenerBD($resultHisto->idPaciente);

    if($pacienteLepra->error){
        echo msj_error('Ocurri&oacute; un ERROR al recuperar los datos del paciente a partir del estudio');
        echo $pacienteLepra->msgError;
        $errorSql = true;
    }

	$resultHisto->idEstudioHis = $_GET['id'];
    //$resultHisto->idDiagnostico = 0;
	$resultHisto->fechaRecepcion = $_POST['fecha_recepcion_histo'];
	$resultHisto->muestraRechazada = $_POST['rechazo_muestra_histo'];
	$resultHisto->idCatMotivoRechazo = $_POST['criterio_rechazo_histo'];
	$resultHisto->otroMotivoRechazo = $_POST['otro_criterio_rechazo_histo'];
	$resultHisto->fechaResultado = $_POST['fecha_resultado_histo'];
	$resultHisto->hisDescMacro = $_POST['macroscopica'];
	$resultHisto->hisDescMicro = $_POST['microscopica'];
	$resultHisto->hisResultado = $_POST['resultado_histo'];
	$resultHisto->idCatHisto = $_POST['tipo_resultado'];
	$resultHisto->idCatEstadoLaboratorio = $_POST['edoLab'];
	$resultHisto->idCatJurisdiccionLaboratorio = $_POST['jurisLab'];
	$resultHisto->idCatAnalistaLab = $_POST['analista'];
	$resultHisto->idCatSupervisorLab = $_POST['supervisor'];
	
	$resultHisto->modificarBD();
	
	if($resultHisto->error){
		//echo $resultHisto->msgError;
        $errorSql = true;
	}

    if($errorSql == false)
    {
        // Si el resultado es positivo
        if($_POST['tipo_resultado'] != 5 && $_POST['tipo_resultado'] != 6)
        {
            // y si es de diagnostico
            if($resultHisto->idCatTipoEstudio == 1)
            {
                /*$help = new Helpers();
                
                // Obtenemos los datos del sospechoso para que este sea insertado como datos del diagnostico
                $sospechoso = new Sospechoso();

                if($pacienteLepra->idPaciente)
                    $sospechoso->obtenerBD($pacienteLepra->idPaciente);

                if($sospechoso->error){
                    echo msj_error(__LINE__.' Ocurri&oacute; un error al recuperar los datos');
                    echo $sospechoso->msgError;
                    $errorSql = true;
                }

                if($sospechoso->idPaciente) {
                    $diagnostico = new Diagnostico();

                    $diagnostico->idPaciente = $pacienteLepra->idPaciente;
                    $diagnostico->idUsuario = $_SESSION[ID_USR_SESSION];
                    $diagnostico->idCatTopografia = $sospechoso->idCatTopografia;
                    $diagnostico->descripcionTopografica = $sospechoso->descripcionTopografica;
                    $diagnostico->idCatNumeroLesiones = $sospechoso->idCatNumeroLesiones;
                    $diagnostico->segAfeCab = $sospechoso->segAfeCab;
                    $diagnostico->segAfeTro = $sospechoso->segAfeTro;
                    $diagnostico->segAfeMSD = $sospechoso->segAfeMSD;
                    $diagnostico->segAfeMSI = $sospechoso->segAfeMSI;
                    $diagnostico->segAfeMID = $sospechoso->segAfeMID;
                    $diagnostico->segAfeMII = $sospechoso->segAfeMII;

                    $diagnostico->insertarBD();

                    if($diagnostico->error){
                        $errorSql = true;
                        echo msj_error(__LINE__.' Ocurri&oacute; un error al recuperar los datos');
                        echo $diagnostico->msgError;
                    }

                    // Actualizamos todos los estudios para que pasen a ser de un diagnostico
                    $estudios = $help->getAllEstudiosBacFromPaciente($pacienteLepra->idPaciente);

                    if($help->error){
                        $errorSql = true;
                        echo msj_error(__LINE__.' Ocurri&oacute; un error al recuperar los datos');
                        echo $help->msgError;
                    }

                    foreach($estudios as $estudio){
                        if(!empty($estudio)) {
                            $objEstudio = new EstudioBac();
                            $objEstudio->idEstudioBac = $estudio;
                            $objEstudio->setNullIdPacienteBD($diagnostico->idDiagnostico);

                            if($objEstudio->error){
                                $errorSql = true;
                                echo msj_error(__LINE__.' Ocurri&oacute; un error al recuperar los datos');
                                echo $objEstudio->msgError;
                            }
                        }
                    }

                    $estudios = $help->getAllEstudiosHisFromPaciente($pacienteLepra->idPaciente);

                    if($help->error){
                        $errorSql = true;
                        echo msj_error(__LINE__.' Ocurri&oacute; un error al recuperar los datos');
                        echo $help->msgError;
                    }

                    foreach($estudios as $estudio){
                        if(!empty($estudio)) {
                            $objEstudio = new EstudioHis();
                            $objEstudio->idEstudioHis = $estudio;
                            $objEstudio->setNullIdPacienteBD($diagnostico->idDiagnostico);

                            if($objEstudio->error){
                                $errorSql = true;
                                echo msj_error(__LINE__.' Ocurri&oacute; un error al recuperar los datos');
                                echo $objEstudio->msgError;
                            }
                        }
                    }

                    // Insertar el control inical
                    $control = new Control();
                    $control->idDiagnostico = $diagnostico->idDiagnostico;
                    $control->fecha = $pacienteLepra->fechaDiagnostico;
                    $control->reingreso = 0;
                    //$control->idCatEstadoPaciente = 1; // Prevalente sin Tratamiento
                    //$control->idCatTratamientoPreescrito = 5; // Sin Tratamiento
                    $control->vigilanciaPostratamiento = 0;
                    $control->observaciones = 'Registro del paciente';

                    $control->insertarBD();

                    if($control->error){
                        $errorSql = true;
                        echo msj_error(__LINE__.' Ocurri&oacute; un error al recuperar los datos');
                        echo $control->msgError;
                    }

                    $sql = "DELETE FROM sospechoso WHERE idPaciente = ".$pacienteLepra->idPaciente;
                    $resv = ejecutaQuery($sql);
                }*/

                registra_log('Mensaje enviado');

                $infoVivienda = array();

                $sql = 'SELECT juris.nombre FROM [catJurisdiccion] as juris, [catMunicipio] as muni where juris.idCatJurisdiccion = muni.idCatJurisdiccion and juris.idCatEstado=muni.idCatEstado and muni.idCatEstado='.$pacienteLepra->idCatEstado.' and muni.idCatMunicipio='.$pacienteLepra->idCatMunicipio.'';
                //$sql = 'SELECT nombre FROM catLocalidad WHERE idCatLocalidad = '.$pacienteLepra->idCatLocalidad.' AND idCatMunicipio = '.$pacienteLepra->idCatMunicipio.' AND idCatEstado = '.$pacienteLepra->idCatEstado;
                $resv = ejecutaQuery($sql);
                $infov = devuelveRowAssoc($resv);
                $infoVivienda['localidad'] = $infov['nombre'];

                $sql = 'SELECT nombre FROM catMunicipio WHERE idCatMunicipio = '.$pacienteLepra->idCatMunicipio.' AND idCatEstado = '.$pacienteLepra->idCatEstado;
                $resv = ejecutaQuery($sql);
                $infov = devuelveRowAssoc($resv);
                $infoVivienda['municipio'] = $infov['nombre'];

                $sql = 'SELECT nombre FROM catEstado WHERE idCatEstado = '.$pacienteLepra->idCatEstado;
                $resv = ejecutaQuery($sql);
                $infov = devuelveRowAssoc($resv);
                $infoVivienda['estado'] = $infov['nombre'];

                //$sql = "UPDATE pacientes SET idCatTipoPaciente = 1 WHERE idPaciente = ".$pacienteLepra->idPaciente;
                //$resv = ejecutaQuery($sql);

                $infoUnidadNotificante = array();

                $sql = "SELECT * FROM
                            catJurisdiccion,
                            catUnidad,
                            catMunicipio,
                            catEstado
                        WHERE
                            catMunicipio.idCatMunicipio = catUnidad.idCatMunicipio AND
                            catUnidad.idCatEstado = catEstado.idCatEstado AND
                            catMunicipio.idCatJurisdiccion = catJurisdiccion.idCatJurisdiccion AND
                            catEstado.idCatEstado = catJurisdiccion.idCatEstado AND
                            catMunicipio.idCatEstado = catEstado.idCatEstado AND
                            catUnidad.idCatUnidad = '".$pacienteLepra->idCatUnidadNotificante."'";
                $resv = ejecutaQuery($sql);
                $infov = devuelveRowAssoc($resv);
                $infoUnidadNotificante['jurisdiccion'] = $infov['nombre'];

                $infoUnidadNotificante['municipio'] = $infov['nombreMunicipio'];
                $infoUnidadNotificante['unidad'] = $infov['nombreUnidad'];

                $sql = 'SELECT nombre FROM catEstado WHERE idCatEstado = '.$infov['idCatEstado'];
                $resv = ejecutaQuery($sql);
                $infov = devuelveRowAssoc($resv);
                $infoUnidadNotificante['estado'] = $infov['nombre'];

                if($pacienteLepra->sexo == 1)
                    $sexoPaciente = 'Masculino';
                if($pacienteLepra->sexo == 2)
                    $sexoPaciente = 'Femenino';

                $htmlBodyMail = '
                <table>
                    <tr>
                        <th colspan="3" bgcolor="#666666"><font color="#FFFFFF">Se ha confirmado caso.</font></th>
                        <td colspan="3" bgcolor="#666666"><font color="#FFFFFF">Fecha: '.$_POST['fecha_resultado_histo'].' .</font></td>
                    </tr>
                    <tr>
                        <th>Clave del Paciente:</th>
                        <td>'.$pacienteLepra->cveExpediente.'</td>
                        <th>Nombre:</th>
                        <td>'.$pacienteLepra->nombre.' '.$pacienteLepra->apellidoPaterno.' '.$pacienteLepra->apellidoMaterno.'</td>
                        <th>Edad:</th>
                        <td>'.CalculaEdad(formatFechaObj($pacienteLepra->fechaNacimiento)).'</td>
                    </tr>
                    <tr>
                        <th>Sexo:</th>
                        <td>'.$sexoPaciente.'</td>
                        <th>Jurisdiccion:</th>
                        <td>'.$infoVivienda['jurisdiccion'].'</td>
                        <th>Municipio:</th>
                        <td>'.$infoVivienda['municipio'].'</td>
                    </tr>
                </table>
                ';

                sendMail("cie.central@gmail.com", $htmlBodyMail);
                sendMail("cie.provac@gmail.com", $htmlBodyMail);
                sendMail("fzero_69@hotmail.com", $htmlBodyMail);
                sendMail("lepra.chiapas@gmail.com", $htmlBodyMail);

                //sendMail("irais.lizbeth@gmail.com", $htmlBodyMail);

                $smsMessage = "CASO CONFIRMADO.\nClave del Paciente:\n".$pacienteLepra->cveExpediente."\nJurisdiccion: ".$infoVivienda['localidad']."\nMunicipio: ".$infoVivienda['municipio'];
                sendSMS("9611078474", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
                sendSMS("9616576145", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
                sendSMS("9612337886", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');

                //sendSMS("5541932463", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
                /*
                sendSMS("5585301233", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
                sendSMS("5539214946", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
                sendSMS("5541932463", $smsMessage, date("Y-m-d"), date("H:i"), 'Notificacion Caso Confirmado');
                */
            }
        }

        // Si el resultado es Negativo
        if($_POST['tipo_resultado'] == 6)
        {
            // y el tipo de estudio es Diagnóstico
            if($resultHisto->idCatTipoEstudio == 1)
            {
                // Actualizamos el tipo de paciente a Descartado
                $sql = "UPDATE pacientes SET idCatTipoPaciente = 6 WHERE idPaciente = ".$pacienteLepra->idPaciente;
                $resv = ejecutaQuery($sql);
            }
        }
    }
}


if ($errorSql == true) {
    rollbackTransaccion();
    $resultadoGuardado = false;
    echo msj_error('Ocurri&oacute; un ERROR al guardar los datos');
    echo '<br /><br />';
} else {
    commitTransaccion();
    $resultadoGuardado = true;
    echo msj_ok('Datos Guardados Exitosamente!!!');
}
/*$objCalidad->calidadMuestra = $_POST['calidadMuestra'];
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
    $objCalidad->modificarBD();*/
?>