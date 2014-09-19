<?php
$objHTML->startFieldset('Datos del Caso');

	$objHTML->inputText('Clave del Paciente', 'clave_expediente', $paciente->cveExpediente, array( 
	'size'=>'25','style'=>'text-align:center;font-weight:bold;text-decoration:underline','title'=>'Se genera automaticamente'));
	//$objHTML->inputText('Folio Laboratorio', 'folio_laboratorio', $estudio->folioLaboratorio, array('class'=>'validate[required]', 'maxlength'=>'10'));
	echo '<br />';
	
	$objHTML->inputText('Nombre Completo: ', 'nombre', $paciente->apellidoPaterno.' '.$paciente->apellidoMaterno.' '.$paciente->nombre, array('size'=>40));
	$objHTML->inputText('Edad: ', 'edad', calEdad(formatFechaObj($paciente->fechaNacimiento, 'Y-m-d')).' a&ntilde;os', array('size'=>8));
	$objSelects->SelectCatalogo('Sexo', 'sexo', 'catSexo', $paciente->sexo, array('class'=>'validate[required]'));
	echo '<br />';
	
	$objHTML->label('Domicilio:  ');
	$objHTML->inputText('', 'calle', $paciente->calle, array('size'=>'30'));
	$objHTML->inputText('No. Exterior', 'num_externo', $paciente->noExterior, array('size'=>'8'));
	$objHTML->inputText('No. Interior', 'num_interno', $paciente->noInterior, array('size'=>'8'));
	$objHTML->inputText('Colonia:', 'colonia', $paciente->colonia, array('placeholder'=>'Colonia', 'size'=>'30'));
	echo '<br />';
	
	$objSelects->selectEstado('edoDomicilio', $paciente->idCatEstado ? $paciente->idCatEstado : 7);
	$objSelects->selectMunicipio('muniDomicilio', $paciente->idCatEstado ? $paciente->idCatEstado : 7, NULL, $paciente->idCatMunicipio);
	$objSelects->selectLocalidad('localiDomicilio', $paciente->idCatEstado, $paciente->idCatMunicipio, $paciente->idCatLocalidad, array('class'=>'validate[required]'));
	echo '<br />';
	
$objHTML->endFieldset();


$objHTML->startFieldset('Datos del Solicitante');

	$objSelects->selectUnidad('uniTratado', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio'], NULL, $paciente->idCatUnidadTratante, array('class'=>'validate[required]'));
	$objSelects->SelectCatalogo('Institución', 'institucion_caso', 'catInstituciones', $paciente->idCatInstitucionTratante);
	echo '<br />';
	
	$objSelects->selectEstado('edoCaso', $infUni['idCatEstado']);			
	$objSelects->selectJurisdiccion('jurisCaso', $infUni['idCatEstado'], $infUni['idCatJurisdiccion']);
	$objSelects->selectMunicipio('muniCaso', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio']);

$objHTML->endFieldset();


$objHTML->startFieldset('Datos Clinicos');

	$objSelects->SelectCatalogo('Diágnostico clínico: ', 'clasficicacion', 'catClasificacionLepra', $diagnostico->idCatClasificacionLepra);
	$objSelects->SelectCatalogo('Estudio para: ', 'tipoEstudio', 'catTipoEstudio', $estudio->idCatTipoEstudio, array('class'=>'validate[required]'));
    echo '<br />';
    
    if(!$paciente->fechaDiagnostico)
        $paciente->fechaDiagnostico =  new DateTime();
   
    $tiempoEvolucion = date_diff($paciente->fechaDiagnostico, new DateTime());
    
    $objHTML->inputText('Tiempo de evolución del padecimiento', 'tiempoEvolucion', $tiempoEvolucion->format('%y a&ntilde;o(s) %m mese(s)'));
    echo '<br />';
    $objHTML->label('Antecedentes Importantes:');
    echo '<div align="center">';
        $objHTML->inputTextarea('', 'otros_padecimientos', $diagnostico->otrosPadecimientos, array('cols'=>'85', 'rows'=>'7'));
    echo '</div>';
    
    echo '<div id="datosClinicosBacilo">';
        $objSelects->SelectCatalogo('Tratamiento', 'tratamiento', 'catTratamientoPreescrito', $diagnostico->idCatTratamiento);
        echo '<br />';
        $objHTML->label('Sitio de Toma de la muestra:');
        $objHTML->inputCheckbox('Lóbulo de la oreja', 'tomMueFrotis1', 1, $estudio->tomMueFrotis1);
        $objHTML->inputCheckbox('Lesión cutánea', 'tomMueFrotis2', 1, $estudio->tomMueFrotis2);
        $objHTML->inputCheckbox('Mucosa Nasal', 'tomMueFrotis3', 1, $estudio->tomMueFrotis3);
    echo '</div>';
    
    echo '<div id="datosClinicosHisto">';
    
        $objSelects->SelectCatalogo('Topografía', 'topografia', 'catTopografia', $diagnostico->idCatTopografia);
        echo '<br />';

        $objHTML->label('Segmentos Afectados: ');
        $objHTML->inputCheckbox('Cabeza', 'segAfeCab', 1, $diagnostico->segAfeCab);
        $objHTML->inputCheckbox('Tronco', 'segAfeTro', 1, $diagnostico->segAfeTro);
        $objHTML->label('Miembros:');
        $objHTML->label('Superiores');
        $objHTML->inputCheckbox('I', 'segAfeMSI', 1, $diagnostico->segAfeMSI);
        $objHTML->inputCheckbox('D', 'segAfeMSD', 1, $diagnostico->segAfeMSD);
        $objHTML->label('Inferiores');
        $objHTML->inputCheckbox('I', 'segAfeMII', 1, $diagnostico->segAfeMII);
        $objHTML->inputCheckbox('D', 'segAfeMID', 1, $diagnostico->segAfeMID);
        echo '<br />';

        $objHTML->label('Morfología de lesiones:');
        echo '<br />';
        $objCatalogo = new Catalogo('catTipoLesionDiagrama');

        $morfoLesiones = $objCatalogo->getValores();
        if(empty($diagnostico->idDiagnostico))
            $lesionesDiagnostico = $help->getArrayLesionesDiagramaSospechoso($paciente->idPaciente);
        else
            $lesionesDiagnostico = $help->getAllTipoLesion($diagnostico->idDiagnostico);
        
        foreach ($morfoLesiones as $key => $value) {
            if($key == 4)
                echo '<br />';

            $objHTML->inputCheckbox($value, 'morfoLesiones', 1, in_array($key, $lesionesDiagnostico));
        }

        echo '<br />';
        $objHTML->label('Descripción Complementaria:');
        echo '<div align="center">';
                $objHTML->inputTextarea('', 'topo_morfo_lesiones', $diagnostico->descripcionTopografica, array('cols'=>'85', 'rows'=>'10'));
        echo '</div>';

        $ultimaBacilo = $help->getLastBaciloscopia($diagnostico->idDiagnostico, $paciente->idPaciente);
        if($ultimaBacilo) {
            $ultimaBacilo['idCatBac'] = ' IB: '.$help->getDescripBaciloscopia($ultimaBacilo['idCatBac']);
            $ultimaBacilo['bacIM'] = ' IM: '.$ultimaBacilo['bacIM'].'%';
        }

        $objHTML->inputText('Fecha y resultado de la última Baciloscopia', 'ultimaBacilo',
                            (formatFechaObj($ultimaBacilo['fechaResultado']).$ultimaBacilo['idCatBac'].$ultimaBacilo['bacIM']), array('size'=>'30'));
        $objSelects->SelectCatalogo('Tratamiento', 'tratamiento', 'catTratamientoPreescrito', $diagnostico->idCatTratamiento);
        echo '<br />';
        
    echo '</div>';
    
    $objHTML->label('Observaciones:');
    echo '<div align="center">';
        $objHTML->inputTextarea('', 'observaciones', $diagnostico->observaciones, array('cols'=>'85', 'rows'=>'7'));
    echo '</div>';
$objHTML->endFieldset();


$objHTML->startFieldset('Muestra');
	
    echo '<div id="datosMuestraHisto">';
    
        $objHTML->inputText('Lesión de la que se tomó la muestra del tejido:', 'lesion_muestra', $estudio->lesionTomoMuestra, array('size'=>'35'));
        echo '<br />';
        $objHTML->inputText('Región de donde se tomó la muestra del tejido:', 'region_muestra', $estudio->regionTomoMuestra, array('size'=>'35'));
        echo '<br />';
    
    echo '</div>';
    
	$objHTML->inputText('Nombre de quien tomó la muestra', 'tomo_muestra', $estudio->personaTomaMuestra, array('size'=>'35'));
	$objHTML->inputText('Fecha de Toma', 'fecha_toma', formatFechaObj($estudio->fechaTomaMuestra), array('class'=>'validate[required]'));
	echo '<br />';
	$objHTML->inputText('Nombre de quien solicita el estudio', 'solicita_estudio', $estudio->personaSolicitudEstudio, array('size'=>'35'));
	$objHTML->inputText('Fecha de Solicitud', 'fecha_solicitud', formatFechaObj($estudio->fechaSolicitudEstudio), array('class'=>'validate[required]'));

$objHTML->endFieldset();


$objHTML->startFieldset('Contacto');
	
    $listContactos = NULL;
	$indexContacto = 1;
	
	foreach($diagnostico->arrContactos as $contacto) {
		$listContactos[$contacto->idContacto] = $indexContacto.'. '.$contacto->nombre;
		$indexContacto++;
	}
	echo '*';
	$objHTML->inputSelect('Contacto:', 'contacto', $listContactos, $estudio->idContacto);
	
	echo '<br />*Seleccionar solo en caso de que el estudio corresponda a un contacto.';
    
$objHTML->endFieldset();

?>