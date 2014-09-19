<?PHP 
if( !isset($_SESSION[ID_USR_SESSION]) )
	die();

if( !isset($SEGURO) )
	die();

$paciente = NULL;
$diagnostico = NULL;
$caso = NULL;
$contacto = NULL;
$lesionDiagrama = NULL;
$errorSql = false;
$infUni = NULL;
$sospechoso = new Sospechoso();
$help = new Helpers();

/*echo '<pre>'.print_r($_FILES,true).'</pre>';
echo '<pre>'.print_r($_POST,true).'</pre>';
exit(0);*/
if(!empty($_POST['clave_expediente'])){
	//print_r($_POST); exit(0);
	beginTransaccion();
	
	$paciente = new Paciente();
	
	$paciente->idPaciente = $_GET['id'];
	$paciente->nombre = $_POST['nombre_paciente'];
	$paciente->apellidoPaterno = $_POST['ap_paterno_paciente'];
	$paciente->apellidoMaterno = $_POST['ap_materno_paciente'];
	$paciente->sexo = $_POST['sexo'];
	$paciente->fechaNacimiento = formatFecha($_POST['fecha_nacimiento']);
	$paciente->cveExpediente = $_POST['clave_expediente'];
	$paciente->idCatTipoPaciente = $_POST['tipo_paciente'];
	$paciente->idCatMunicipioNacimiento = $_POST['muniNac'];
	$paciente->idCatEstadoNacimiento = $_POST['edoNac'];
	$paciente->idCatLocalidad = $_POST['localiDomicilio'];
	$paciente->idCatMunicipio = $_POST['muniDomicilio'];
	$paciente->idCatEstado = $_POST['edoDomicilio'];
	$paciente->idCatUnidadNotificante = $_POST['uniNotificante'];
	$paciente->idCatFormaDeteccion = $_POST['deteccion'];
	$paciente->fechaInicioPadecimiento = formatFecha($_POST['fecha_padecimiento']);
	$paciente->fechaDiagnostico = formatFecha($_POST['fecha_diagnostico']);
	$paciente->ocupacion = $_POST['ocupacion_paciente'];
	$paciente->calle = $_POST['calle'];
	$paciente->celularContacto = $_POST['celularContacto'];
	$paciente->noExterior = $_POST['num_externo'];
	$paciente->noInterior = $_POST['num_interno'];
	$paciente->colonia = $_POST['colonia'];
	$paciente->anosRadicando = $_POST['radica_anos'];
	$paciente->mesesRadicando = $_POST['radica_meses'];
	$paciente->telefono = $_POST['telefono'];
	$paciente->idCatInstitucionUnidadNotificante = $_POST['institucion'];
	$paciente->otraInstitucionUnidadNotificante = $_POST['otraInstitucion'];
	$paciente->idCatInstitucionDerechohabiencia = $_POST['derechohabiencia'];
	$paciente->otraDerechohabiencia = $_POST['otraDerechohabiencia'];
    $paciente->idCatUnidadTratante = $_POST['uniTratado'];
    $paciente->idCatUnidadReferido = $_POST['uniReferido'];
    $paciente->idCatInstitucionTratante = $_POST['institucion_caso'];
    $paciente->otraInstitucionTratante = $_POST['otra_institutcion_caso'];
	$paciente->campoExtrangero = $_POST['campoExtrangero'];
	$paciente->folioRegistro = $_POST['folioRegistro'];
	$paciente->medicoElaboro = $_POST['medicoElaboro'];
	$paciente->medicoValido = $_POST['medicoValido'];
    
    // Notificar via correo al coordinador estatal del estado referico
    if(!empty($_POST['edoReferido'])) {
        $paciente->idCatEstadoReferido = $_POST['edoReferido'];
    }
    
    // Fase 2 de la captura
    // Si el paciente es distinto de Sospechoso(5) o Descartado(6)
    if($_POST['tipo_paciente']!=5 && $_POST['tipo_paciente']!=6) 
    {
        $paciente->fechaNotificacion = formatFecha($_POST['fecha_notificacion']);
        $paciente->semanaEpidemiologica = $_POST['semana_notificacion'];
        $paciente->fechaInicioPQT = formatFecha($_POST['fecha_pqt']);
    }
	
	if($_POST['guardar'])
	{
		$paciente->insertarBD();
		
		$infoVivienda = array();
		
		$sql = 'SELECT nombre FROM catLocalidad WHERE idCatLocalidad = '.$_POST['localiDomicilio'].' AND idCatMunicipio = '.$_POST['muniDomicilio'].' AND idCatEstado = '.$_POST['edoDomicilio'];
		$resv = ejecutaQuery($sql);
		$infov = devuelveRowAssoc($resv);
		$infoVivienda['localidad'] = $infov['nombre'];
		
		$sql = 'SELECT nombre FROM catMunicipio WHERE idCatMunicipio = '.$_POST['muniDomicilio'].' AND idCatEstado = '.$_POST['edoDomicilio'];
		$resv = ejecutaQuery($sql);
		$infov = devuelveRowAssoc($resv);
		$infoVivienda['municipio'] = $infov['nombre'];
		
		$sql = 'SELECT nombre FROM catEstado WHERE idCatEstado = '.$_POST['edoDomicilio'];
		$resv = ejecutaQuery($sql);
		$infov = devuelveRowAssoc($resv);
		$infoVivienda['estado'] = $infov['nombre'];
		
		$infoUnidadNotificante = array();
		
		$sql = 'SELECT nombre FROM catJurisdiccion WHERE idCatJurisdiccion = '.$_POST['jurisUnidad'].' AND idCatEstado = '.$_POST['edoUnidad'];
		$resv = ejecutaQuery($sql);
		$infov = devuelveRowAssoc($resv);
		$infoUnidadNotificante['jurisdiccion'] = $infov['nombre'];
		
		$sql = 'SELECT nombre FROM catMunicipio WHERE idCatMunicipio = '.$_POST['muniUnidad'].' AND idCatEstado = '.$_POST['edoUnidad'];
		$resv = ejecutaQuery($sql);
		$infov = devuelveRowAssoc($resv);
		$infoUnidadNotificante['municipio'] = $infov['nombre'];
		
		$sql = 'SELECT nombre FROM catEstado WHERE idCatEstado = '.$_POST['edoUnidad'];
		$resv = ejecutaQuery($sql);
		$infov = devuelveRowAssoc($resv);
		$infoUnidadNotificante['estado'] = $infov['nombre'];
		
		$sql = "SELECT nombreUnidad FROM catUnidad WHERE idCatUnidad = '".$_POST['uniNotificante']."'";
		$resv = ejecutaQuery($sql);
		$infov = devuelveRowAssoc($resv);
		$infoUnidadNotificante['unidad'] = $infov['nombreUnidad'];
		
		if($_POST['sexo'] == 1)
			$sexoPaciente = 'Masculino';
		if($_POST['sexo'] == 2)
			$sexoPaciente = 'Femenino';
		
		$htmlBodyMail = '
		<table>
			<tr>
				<th colspan="3" bgcolor="#666666"><font color="#FFFFFF">Se ha ingresado caso probable.</font></th>
				<td colspan="3" bgcolor="#666666"><font color="#FFFFFF">Fecha: '.$_POST['fecha_diagnostico'].' .</font></td>
			</tr>
			<tr>
				<th>Clave del Paciente:</th>
				<td>'.$_POST['clave_expediente'].'</td>
				<th>Nombre:</th>
				<td>'.utf8_encode($_POST['nombre_paciente']).' '.utf8_encode($_POST['ap_paterno_paciente']).' '.utf8_encode($_POST['ap_materno_paciente']).'</td>
				<th>Edad:</th>
				<td>'.CalculaEdad(formatFecha($paciente->fechaNacimiento)).'</td>
			</tr>
			<tr>
				<th>Localidad:</th>
				<td>'.utf8_encode($infoVivienda['localidad']).'</td>
				<th>Municipio:</th>
				<td>'.utf8_encode($infoVivienda['municipio']).'</td>
				<th>Estado:</th>
				<td>'.utf8_encode($infoVivienda['estado']).'</td>
			</tr>
			<tr>
				<th colspan="6" bgcolor="#666666"><font color="#FFFFFF">Unidad Notificante.</font></th>
			</tr>
			<tr>
				<th>Jurisdiccion:</th>
				<td>'.utf8_encode($infoUnidadNotificante['jurisdiccion']).'</td>
				<th>Municipio:</th>
				<td>'.utf8_encode($infoUnidadNotificante['municipio']).'</td>
				<th>Unidad:</th>
				<td>'.utf8_encode($infoUnidadNotificante['unidad']).'</td>
			</tr>
		</table>
		';
		
		sendMail("cie.central@gmail.com", $htmlBodyMail);
		sendMail("fzero_69@hotmail.com", $htmlBodyMail);
		sendMail("lepra.chiapas@gmail.com", $htmlBodyMail);
	}
	
	if(isset($_POST['actualizar']))
		$paciente->modificarBD();
	
	if($paciente->error) {
		$errorSql = true;
		echo $paciente->msgError;
	}
	
    // Relacion de Sospechoso con Paciente
    $sospechoso->idPaciente = $paciente->idPaciente;
    
    // Fase 2 de la captura
    // Si el paciente es distinto de Sospechoso(5) o Descartado(6)
    if($_POST['tipo_paciente']!=5 && $_POST['tipo_paciente']!=6) 
    {
        $diagnostico = new Diagnostico();

        $diagnostico->idDiagnostico = $diagnostico->obtieneIdDiagnostico($paciente->idPaciente);
        $diagnostico->idPaciente = $paciente->idPaciente;
        $diagnostico->discOjoIzq = $_POST['ojo_izq'];
        $diagnostico->discOjoDer = $_POST['ojo_der'];
        $diagnostico->discManoIzq = $_POST['mano_izq'];
        $diagnostico->discManoDer = $_POST['mano_der'];
        $diagnostico->discPieIzq = $_POST['pie_izq'];
        $diagnostico->discPieDer = $_POST['pie_der'];
        $diagnostico->idCatEstadoReaccionalAct = $_POST['reaccional_actual'];
        if($_POST['reaccional_actual'] == 3) {
            $diagnostico->estReaActEriNud = $_POST['edoRecActTipo2Nud'];
            $diagnostico->estReaActEriPol = $_POST['edoRecActTipo2Poli'];
            $diagnostico->estReaActEriNec = $_POST['edoRecActTipo2Necro'];
        } else {
            $diagnostico->estReaActEriNud = 0;
            $diagnostico->estReaActEriPol = 0;
            $diagnostico->estReaActEriNec = 0;
        }
        $diagnostico->idCatClasificacionLepra = $_POST['diagnostico'];
        $diagnostico->idCatEstadoPaciente = $_POST['estado_paciente'];
        $diagnostico->idUsuario = $_SESSION[ID_USR_SESSION];
        $diagnostico->fechaCaptura = date('Y-m-d H:m:s');
        $diagnostico->observaciones = $_POST['observaciones'];
        $diagnostico->idCatTratamiento = $_POST['tratamiento'];
        
        $diagnostico->idCatTopografia = $_POST['topografia'];
        $diagnostico->descripcionTopografica = $_POST['topo_morfo_lesiones'];
        $diagnostico->idCatNumeroLesiones = $_POST['noLesiones'];
        $diagnostico->segAfeCab = $_POST['segAfeCab'];
        $diagnostico->segAfeTro = $_POST['segAfeTro'];
        $diagnostico->segAfeMSD = $_POST['segAfeMSD'];
        $diagnostico->segAfeMSI = $_POST['segAfeMSI'];
        $diagnostico->segAfeMID = $_POST['segAfeMID'];
        $diagnostico->segAfeMII = $_POST['segAfeMII'];

        $diagnostico->otrosPadecimientos = $_POST['otros_padecimientos'];
        $diagnostico->idCatEstadoReaccionalAnt = $_POST['reaccional_anterior'];
        if($_POST['reaccional_anterior']==3){
            $diagnostico->estReaAntEriNud = $_POST['edoRecAntTipo2Nud'];
            $diagnostico->estReaAntEriPol = $_POST['edoRecAntTipo2Poli'];
            $diagnostico->estReaAntEriNec = $_POST['edoRecAntTipo2Necro'];
        } else {
            $diagnostico->estReaAntEriNud = 0;
            $diagnostico->estReaAntEriPol = 0;
            $diagnostico->estReaAntEriNec = 0;
        }
        $diagnostico->fechaReaccionAnteriorTipI = $_POST['tipo_uno'];
        $diagnostico->fechaReaccionAnteriorTipII = $_POST['tipo_dos'];
        $diagnostico->idCatLocalidadAdqEnf = $_POST['localiAquirioEnfermedad'];
        $diagnostico->idCatMunicipioAdqEnf = $_POST['muniAquirioEnfermedad'];
        $diagnostico->idCatEstadoAdqEnf = $_POST['edoAquirioEnfermedad'];

        if($_POST['guardar']){
            $diagnostico->insertarBD();
        }
        if(!empty($_POST['actualizar']) && empty($diagnostico->idDiagnostico)) {
            $diagnostico->insertarBD();
        }
        else if(!empty($_POST['actualizar'])) {
            $diagnostico->modificarBD();
        }

        if($diagnostico->error){
            $errorSql = true;
            echo $diagnostico->msgError;
        }
        
		/* Actualizamos todos los estudios para que pasen a ser de un diagnostico */
		$estudios = $help->getAllEstudiosBacFromPaciente($paciente->idPaciente);
		
		foreach($estudios as $estudio){
            if(!empty($estudio)) {
                $objEstudio = new EstudioBac();
                $objEstudio->idEstudioBac = $estudio;
                $objEstudio->setNullIdPacienteBD($diagnostico->idDiagnostico);

                if($objEstudio->error){
                    $errorSql = true;
                    echo $objEstudio->msgError;
                }
            }
		}
		
		$estudios = $help->getAllEstudiosHisFromPaciente($paciente->idPaciente);
		
		foreach($estudios as $estudio){
            if(!empty($estudio)) {
                $objEstudio = new EstudioHis();
                $objEstudio->idEstudioHis = $estudio;
                $objEstudio->setNullIdPacienteBD($diagnostico->idDiagnostico);

                if($objEstudio->error){
                    $errorSql = true;
                    echo $objEstudio->msgError;
                }
            }
		}
		
        // Insertar el control inical
        if(!empty($_POST['actualizar']) && empty($diagnostico->idDiagnostico)) {
            $control = new Control();
            $control->idDiagnostico = $diagnostico->idDiagnostico;
            $control->fecha = formatFecha($_POST['fecha_diagnostico']);
            $control->reingreso = 0;
            $control->idCatEstadoPaciente = $_POST['estado_paciente'];
            $control->idCatTratamientoPreescrito = $_POST['tratamiento'];
            $control->vigilanciaPostratamiento = 0;
            $control->observaciones = 'Registro del paciente';

            $control->insertarBD();

            if($control->error){
                $errorSql = true;
                echo $control->msgError;
            }
        }


        $delCasosRelacionados = explode(',', $_POST['del_casos_relacionados']);
        $caso = new CasoRelacionado();

        foreach ($delCasosRelacionados as $id) {
            $caso->eliminarBD($id);

            if($caso->error){
                $errorSql = true;
                echo $caso->msgError;
            }
        }

        for($i=1; $i<=$_POST['no_caso_relacionado']; $i++) {
            if(!empty($_POST['nombre_caso_relacionado_'.$i])) {
                $caso = new CasoRelacionado();

                $caso->idCasoRelacionado = $_POST['idCasoRelacionado_'.$i];
                $caso->idDiagnostico = $diagnostico->idDiagnostico;
                $caso->nombre = $_POST['nombre_caso_relacionado_'.$i];
                $caso->idCatParentesco = $_POST['parentesco_caso_relacionado_'.$i];
                $caso->idCatSituacionCasoRelacionado = $_POST['situacion_caso_relacionado_'.$i];
                $caso->tiempoConvivenciaMeses = $_POST['meses_caso_relacionado_'.$i];
                $caso->tiempoConvivenciaAnos = $_POST['ano_caso_relacionado_'.$i];

                $caso->replaceDB($caso->idCasoRelacionado);

                if($caso->error){
                    $errorSql = true;
                    echo $caso->msgError;
                }
            }
        }

        $delContactos = explode(',', $_POST['del_contactos']);
        $contacto = new Contacto();

        foreach ($delContactos as $id) {
            $contacto->eliminarBD($id);

            if($contacto->error){
                $errorSql = true;
                echo $contacto->msgError;
            }
        }

        for($i=1; $i<=$_POST['no_contactos']; $i++) {
            if(!empty($_POST['nombre_contacto_'.$i])) {
                $contacto = new Contacto();

                $contacto->idContacto = $_POST['idContacto_'.$i];
                $contacto->idDiagnostico = $diagnostico->idDiagnostico;
                $contacto->nombre = $_POST['nombre_contacto_'.$i];
                $contacto->sexo = $_POST['sexo_contacto_'.$i];
                $contacto->edad = $_POST['edad_contacto_'.$i];
                $contacto->idCatParentesco = $_POST['parentesco_contacto_'.$i];
                $contacto->tiempoConvivenciaAnos = $_POST['ano_contacto_'.$i];
                $contacto->tiempoConvivenciaMeses = $_POST['mes_contacto_'.$i];

                $contacto->replaceDB($contacto->idContacto);

                if($contacto->error){
                    $errorSql = true;
                    echo $contacto->msgError;
                }
            }
        }
    }
    else {
        $sospechoso->idCatTopografia = $_POST['topografia'];
        $sospechoso->descripcionTopografica = $_POST['topo_morfo_lesiones'];
        $sospechoso->idCatNumeroLesiones = $_POST['noLesiones'];
        $sospechoso->segAfeCab = $_POST['segAfeCab'];
        $sospechoso->segAfeTro = $_POST['segAfeTro'];
        $sospechoso->segAfeMSD = $_POST['segAfeMSD'];
        $sospechoso->segAfeMSI = $_POST['segAfeMSI'];
        $sospechoso->segAfeMID = $_POST['segAfeMID'];
        $sospechoso->segAfeMII = $_POST['segAfeMII'];
        
        if($_POST['guardar'])
            $sospechoso->insertarBD();

        if($_POST['actualizar'])
            $sospechoso->modificarBD();
    }
    
    
    $delTagLesiones = explode(',', $_POST['delTagLesiones']);
    $lesionDiagrama = new DiagramaDermatologico();

    foreach ($delTagLesiones as $id) {
        $lesionDiagrama->eliminarBD($id);

        if($lesionDiagrama->error){
            $errorSql = true;
            echo $lesionDiagrama->msgError;
        }
    }

    $lesiones = json_decode($_POST['tagLesiones']);

    $tipoLesionDiagrama = NULL;
    $rsTipoLesionDiagrama = ejecutaQuery('SELECT [idCatTipoLesionDiagrama],[descripcion] FROM [catTipoLesionDiagrama]');

    while($tipo = devuelveRowAssoc($rsTipoLesionDiagrama))
        $tipoLesionDiagrama[$tipo['descripcion']] = $tipo['idCatTipoLesionDiagrama'];
	
    foreach($lesiones as $temp) {
        $lesionDiagrama = new DiagramaDermatologico();
        
        $lesionDiagrama->idDiagnostico = $diagnostico->idDiagnostico;
        $lesionDiagrama->idPaciente = $paciente->idPaciente;
        $lesionDiagrama->idLesion = $temp->{'id'};
		$tempId = $temp->{'id'};
        $lesionDiagrama->idCatTipoLesion = $tipoLesionDiagrama[$temp->{'text'}];
        $lesionDiagrama->x = $temp->{'left'};
        $lesionDiagrama->y = $temp->{'top'};
        $lesionDiagrama->w = $temp->{'width'};
        $lesionDiagrama->h = $temp->{'height'};

        $lesionDiagrama->replaceDB($lesionDiagrama->idLesion);
		if($_POST['guardar'] || $_POST['actualizar'])
		{
			$new_name = "photo_1_".$lesionDiagrama->idLesion;
			$new_name2 = "file_photoTag-tag_".$tempId;
			//echo '<script type="text/javascript" language="javascript">alert("'.$new_name2.'")<script>';
			$ext = explode("/",$_FILES[$new_name2]['type']);
			$extencion = $ext[1];
			
			if($_FILES[$new_name2]["size"] > 0)
			{
				$dir = "C:/wamp/www/lepra.chiapas/pacienteImg/";
				
				$new_name .= ".".$extencion;
				
				move_uploaded_file($_FILES[$new_name2]['tmp_name'], $dir.$new_name);
				
				$lesionDiagrama->imgUrl = $new_name.";;;";
				$lesionDiagrama->updateImgUrl();
			}
		}
		
        if($lesionDiagrama->error){
            $errorSql = true;
            echo $lesionDiagrama->msgError;
        }
    }
    
	
	if($errorSql == true) {
		rollbackTransaccion();
		echo msj_error('Ocurri&oacute; un ERROR al guardar los datos');
		echo '<br /><br />';
	}
	else {
		commitTransaccion();
		
        // Un paciente Sospechoso(5) o Descartado(6) no tiene diagnostico asociado
        /*if(!empty($diagnostico)){
            $diagnostico->cargarArreglosDiagnosticoCasosRelacionados();
            $diagnostico->cargarArreglosDiagnosticoContactos();
            //$diagnostico->cargarArreglosDiagnosticoDiagramaDermatologico();
        }*/
        redirect('?mod=cap&id='.$paciente->idPaciente.'&saved=true');
		//echo msj_ok('Los datos se guardaron exitosamente');
	}
}

if(!empty($_GET['id'])) {
	$paciente = new Paciente();
	$paciente->obtenerBD($_GET['id']);
	
    if(empty($paciente->idPaciente)) {
        echo msj_error('ERROR el paciente solicitado no existe.');
        echo '<br />';
    }
    else {
        $paciente->cargarArreglosPaciente();

        $diagnostico = $paciente->arrDiagnosticos[0];
        
        // Un paciente Sospechoso(5) o Descartado(6) no tiene diagnostico asociado
        if(!empty($diagnostico)){
            $diagnostico->cargarArreglosDiagnosticoCasosRelacionados();
            $diagnostico->cargarArreglosDiagnosticoContactos();
            //$diagnostico->cargarArreglosDiagnosticoDiagramaDermatologico();
        } else {
            $sospechoso->obtenerBD($paciente->idPaciente);
            $diagnostico = $sospechoso;
        }
    }
}
?>