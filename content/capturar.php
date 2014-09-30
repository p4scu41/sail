<?PHP 
if( !isset($_SESSION[ID_USR_SESSION]) )
	die();

if( !isset($SEGURO) )
	die();

require_once('include/clasesLepra.php');
require_once('include/enviaCorreo.php');
require_once('content/procesaCapturar.php');

?>

<link rel="stylesheet" href="include/Jquery-Photo-Tag-master/libraries/jquery-ui-1.8.17.custom.css" type="text/css" media="screen" />
<link rel="stylesheet" href="include/Jquery-Photo-Tag-master/tests/photo_tags/styles2.css" type="text/css" media="screen" />
<link rel="stylesheet" href="include/jQuery.validationEngine_v2.0/css/validationEngine.jquery.css" type="text/css"/>
<link rel="stylesheet" href="include/css/jquery-ui/redmond/jquery-ui.css" />
<link rel="Stylesheet" href="include/SlidesJS/bjqs.css" />
<link rel="Stylesheet" href="include/SlidesJS/demo.css" />

<script type="text/javascript" src="include/Jquery-Photo-Tag-master/libraries/jquery.dev.1.7.1.js"></script>
<script type="text/javascript" src="include/Jquery-Photo-Tag-master/libraries/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="include/Jquery-Photo-Tag-master/js/jquery.phototag.js"></script>
<script type="text/javascript" src="js/procesaCapturar.js"></script>
<script type="text/javascript" src="include/jQuery.validationEngine_v2.0/js/languages/jquery.validationEngine-es.js" charset="utf-8"></script>
<script type="text/javascript" src="include/jQuery.validationEngine_v2.0/js/jquery.validationEngine.js" charset="utf-8"></script>
<script src="include/SlidesJS/js/bjqs-1.3.min.js"></script>
<script src="js/moment.js"></script>

<?PHP
    $objHTML = new HTML();
    
    // Deshabilitar todos los campos
    if(($_SESSION[TIPO_USR_SESSION]!=1 || empty($_SESSION[EDO_USR_SESSION])) && !empty($_GET['id'])) {
        echo '<script type="text/javascript">
            $(document).ready(function() {
                deshabilitarCamposCaptura("capturaPaciente");
            });
            </script>';
        $objHTML->inputHidden("showAddNewLink", "false");
    } else {
        $objHTML->inputHidden("showAddNewLink", "true");
    }

	echo '<h2 align="center">C&Eacute;DULA DE REGISTRO - ESTUDIO EPIDEMIOL&Oacute;GICO</h2>';

    if($alerta) {
        echo '<div style="color: #F00; font-weight: bold;" align="center">El caso ha sido confirmado por histopatolog&iacute;a en el LESP como
            caso nuevo. Favor de completar la c&eacute;dula de registro e iniciar el estudio de contactos.</div>';
    }

	echo '<div id="dialog_form" style="display:none;"></div>';
    $objHTML->startForm('capturaPaciente', '?mod=cap&id='.$_GET['id'], 'POST', array("enctype" => "multipart/form-data"));
	
		$objHTML->startFieldset();
			
			$objSelects = new Select();
			$objSelects->SelectCatalogo('Tipo de Paciente', 'tipo_paciente', 'catTipoPaciente', $paciente->idCatTipoPaciente ? $paciente->idCatTipoPaciente : 5, array('class'=>'validate[required]'));
			$objHTML->inputText('Clave del Paciente', 'clave_expediente', $paciente->cveExpediente, array('readonly'=>true, 'class'=>'validate[required]', 
						'size'=>'25','style'=>'text-align:center;font-weight:bold;text-decoration:underline','title'=>'Se genera automaticamente'));
            $objHTML->inputText('Folio de registro', 'folio_registro', $paciente->folioRegistro, array('readonly'=>true, 
						'size'=>'25','style'=>'text-align:center;font-weight:bold;text-decoration:underline','title'=>'Este valor sera automaticamente asignado por el sistema al momento de guardar el registro',
                        'alt'=>'Este valor sera automaticamente asignado por el sistema al momento de guardar el registro'));
			echo '<br />';
			
			$objHTML->label('Nombre Completo: ');
			$objHTML->inputText('', 'ap_paterno_paciente', $paciente->apellidoPaterno, array('placeholder'=>'Apellido Paterno', 'size'=>'30', 'maxlength'=>'20', 'class'=>'validate[required]'));
			$objHTML->inputText('', 'ap_materno_paciente', $paciente->apellidoMaterno, array('placeholder'=>'Apellido Materno', 'size'=>'30', 'maxlength'=>'20', 'class'=>'validate[required]'));
			$objHTML->inputText('', 'nombre_paciente', $paciente->nombre, array('placeholder'=>'Nombre', 'size'=>'30', 'maxlength'=>'20', 'class'=>'validate[required]'));
			
			echo '<br />';
			
			$objHTML->inputText('Fecha de Nacimiento', 'fecha_nacimiento', formatFechaObj($paciente->fechaNacimiento), array('placeholder'=>'Fecha Nacimiento', 'class'=>'validate[required]'));
			$objHTML->inputText('Edad', 'edad', CalculaEdad(formatFechaObj($paciente->fechaNacimiento)), array('placeholder'=>'Edad', 'disabled'=>'disabled'));
			$objSelects->SelectCatalogo('Sexo', 'sexo', 'catSexo', $paciente->sexo, array('class'=>'validate[required]'));
			echo '<br />';
			$objHTML->inputText('Ocupación', 'ocupacion_paciente', $paciente->ocupacion, array('placeholder'=>'Ocupaci&oacute;n', 'size'=>'40', 'maxlength'=>'20', 'class'=>'validate[required]'));
			echo '<br />';
		
		$objHTML->endFieldset();
		
		
		$objHTML->startFieldset();
			if($paciente->idCatEstadoNacimiento == 33)
			{
				$classReq = "";
				$display = "";
			}
			else
			{
				$classReq = "validate[required]";
				$display = 'display:none; ';
			}
			echo '<div style="float: left;">';
			$objHTML->label('Lugar de Nacimiento: ');
			$objSelects = new Select();
			$objSelects->selectEstado('edoNac', $paciente->idCatEstadoNacimiento ? $paciente->idCatEstadoNacimiento : $_SESSION[EDO_USR_SESSION] );
			$objSelects->selectMunicipio('muniNac', $paciente->idCatEstadoNacimiento ? $paciente->idCatEstadoNacimiento : $_SESSION[EDO_USR_SESSION], NULL, $paciente->idCatMunicipioNacimiento, array('class'=>$classReq));
			echo '</div>';
			echo '<div style="'.$display.'float: left;" id="nombreExtranjero">';
			$objHTML->inputText('Nombre Estado: ', 'campoExtrangero',  $paciente->campoExtrangero, array('placeholder'=>'Extranjero', 'size'=>'40', 'maxlength'=>'40'));
			echo '</div>';
		$objHTML->endFieldset();
		
		
		$objHTML->startFieldset();
		
			$objHTML->label('Domicilio Actual: ');
			$objHTML->inputText('', 'calle', $paciente->calle, array('placeholder'=>'Calle', 'size'=>'40', 'maxlength'=>'40', 'class'=>'validate[required]'));
			$objHTML->inputText('', 'num_externo', $paciente->noExterior, array('placeholder'=>'No. Externo', 'size'=>'10', 'maxlength'=>'6', 'class'=>'validate[required]'));
			$objHTML->inputText('', 'num_interno', $paciente->noInterior, array('placeholder'=>'No. Interno', 'maxlength'=>'6', 'size'=>'10'));
			echo '<br />';
			$objHTML->inputText('Colonia:', 'colonia', $paciente->colonia, array('placeholder'=>'Colonia', 'size'=>'30', 'maxlength'=>'30', 'class'=>'validate[required]'));
			echo '<br />';
			
			$objSelects->selectEstado('edoDomicilio', $paciente->idCatEstado ? $paciente->idCatEstado : $_SESSION[EDO_USR_SESSION], array('disabled'=>'disabled'));
			$objSelects->selectMunicipio('muniDomicilio', $paciente->idCatEstado ? $paciente->idCatEstado : $_SESSION[EDO_USR_SESSION], NULL, $paciente->idCatMunicipio);
			$objSelects->selectLocalidad('localiDomicilio', $paciente->idCatEstado, $paciente->idCatMunicipio, $paciente->idCatLocalidad, array('class'=>'validate[required]'));
			echo '<br />';
			
			$objHTML->inputText('Tiempo de radicar en el domicilio actual', 'radica_anos', $paciente->anosRadicando, array('placeholder'=>'A&ntilde;os', 'size'=>'10', 'maxlength'=>'3', 'class'=>'validate[required,custom[integer]]'));
			$objHTML->inputText('', 'radica_meses', $paciente->mesesRadicando, array('placeholder'=>'Meses', 'size'=>'10', 'maxlength'=>'3'));
			$objHTML->inputText('Teléfono', 'telefono', $paciente->telefono, array('size'=>'11', 'maxlength'=>'13'));
			$objHTML->inputText('Numero Celular', 'celularContacto', $paciente->celularContacto, array('placeholder'=>'Numero celular para contacto', 'size'=>'10', 'maxlength'=>'10'));
			
		$objHTML->endFieldset();
		
		
		if(!empty($paciente->idCatUnidadNotificante)){
			$infUni = $help->getDatosUnidad($paciente->idCatUnidadNotificante);
		}
		
		$objHTML->startFieldset();
			$objHTML->label('Unidad Notificante: ');
			$objSelects->selectEstado('edoUnidad', $infUni['idCatEstado'] ? $infUni['idCatEstado'] : $_SESSION[EDO_USR_SESSION], array('disabled'=>'disabled'));			
			$objSelects->selectJurisdiccion('jurisUnidad', $infUni['idCatEstado'] ? $infUni['idCatEstado'] : $_SESSION[EDO_USR_SESSION], $infUni['idCatJurisdiccion']);
			$objSelects->selectMunicipio('muniUnidad', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio']);
			echo '<br />';
			$objSelects->selectUnidad('uniNotificante', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio'], NULL, $paciente->idCatUnidadNotificante, array('class'=>'validate[required]'));
            $objSelects->SelectCatalogo('Institución', 'institucion', 'catInstituciones', $paciente->idCatInstitucionUnidadNotificante, array('class'=>'validate[required]'));
			$objHTML->inputText('', 'otraInstitucion', $paciente->otraInstitucionUnidadNotificante, array('placeholder'=>'Especifique', 'maxlength'=>'15'));
			echo '<br />';
			$objSelects->SelectCatalogo('Derechohabiencia del paciente', 'derechohabiencia', 'catInstituciones', $paciente->idCatInstitucionDerechohabiencia, array('class'=>'validate[required]'));
			$objHTML->inputText('', 'otraDerechohabiencia', $paciente->otraDerechohabiencia, array('placeholder'=>'Especifique', 'maxlength'=>'12'));

        $objHTML->endFieldset();
		
		echo '<a name="segundaFaseCaptura" id="segundaFaseCaptura"></a>';
        
		$objHTML->startFieldset();
		
			$objSelects->SelectCatalogo('Forma de detección del caso: ', 'deteccion', 'catFormaDeteccion', $paciente->idCatFormaDeteccion, array('class'=>'validate[required]'));
			echo '<br />';
			$objHTML->inputText('Fecha de inicio del padecimiento', 'fecha_padecimiento', formatFechaObj($paciente->fechaInicioPadecimiento), array('placeholder'=>'Fecha de Padecimiento', 'class'=>'validate[required]'));
			$objHTML->inputText('Fecha de notificación', 'fecha_notificacion', ($paciente->fechaNotificacion ? formatFechaObj($paciente->fechaNotificacion) : date('d-m-Y')), array('placeholder'=>'Fecha de Notificacion', 'class'=>'validate[required]', 'disabled'=>'disabled'));
			$objHTML->inputText('Semana de notificación', 'semana_notificacion', ($paciente->semanaEpidemiologica ? $paciente->semanaEpidemiologica : $semanaEpidemiologica), array('placeholder'=>'Semana', 'size'=>'3', 'disabled'=>'disabled'));
			echo '<br />';
			$objHTML->inputText('Fecha de Dx Clínico', 'fecha_diagnostico',formatFechaObj($paciente->fechaDiagnostico), array('placeholder'=>'Fecha de Diagnostico', 'class'=>'validate[required]'));
			$objHTML->inputText('Fecha de Dx Bacteriológico', 'fecha_bacil', formatFechaObj($paciente->fechaDxBacil), array('placeholder'=>'Fecha Estudio Baciloscopico', 'disabled'=>'disabled'));
			$objHTML->inputText('Fecha de Dx Histopatológico', 'fecha_histo', formatFechaObj($paciente->fechaDxHisto), array('placeholder'=>'Fecha Histopatologico', 'disabled'=>'disabled'));
			echo "<br />";
			$objHTML->inputText('Fecha de inicio de la PQT', 'fecha_pqt', formatFechaObj($paciente->fechaInicioPQT), array('placeholder'=>'Fecha PQT'));
        
		$objHTML->endFieldset();
	
	
		$objHTML->startFieldset('Topografía y morfología de las lesiones');
			echo '<div align="center">';
            $objSelects->SelectCatalogo('Topografía', 'topografia', 'catTopografia', $diagnostico->idCatTopografia, array('class'=>'validate[required]'));
			echo '<br />';
			$objHTML->inputTextarea('', 'topo_morfo_lesiones', $diagnostico->descripcionTopografica, 
					array('placeholder'=>'Describir tipo de lesiones dermatol&oacute;gicas y/&oacute; neurol&oacute;gicas, los sitios que afectan, asi como el n&uacute;mero, '.
					'extensi&oacute;n y caracter&iacute;sticas', 'cols'=>'85', 'rows'=>'10', 'class'=>'validate[required]'));
			
            
            
			$objHTML->inputHidden('tagLesiones', '');
			$objHTML->inputHidden('delTagLesiones');
			$objHTML->inputHidden('editTagLesiones');
			echo '<table width="100%" align="center"><tr align="center" border="1"><td>';
			echo 'Nodulos Aislados <span class="caja_etiqueta nodulos_aislados"></span>
				Nodulos Agrupados <span class="caja_etiqueta nodulos_agrupados"></span>
				Manchas Hipopigmantadas <span class="caja_etiqueta manchas_hipopigmantadas"></span>
				Manchas Eritematosas <span class="caja_etiqueta manchas_eritematosas"></span>
				Placas Infiltradas <span class="caja_etiqueta placas_infiltradas"></span>
				Zonas de Anestesia <span class="caja_etiqueta zonas_anestesia"></span>
				Nudosidades y Otras <span class="caja_etiqueta nudosidades_otras"></span>
			</td><td>';
            
			//echo '<img src="images/body.png" class="body" data-id="'.($diagnostico->idDiagnostico ? $diagnostico->idDiagnostico : $paciente->idPaciente).'" data-image-id="1" data-album-id="1" align="middle" />';
			$srcImg = "images/male_body_ok.png";
			if($paciente->sexo == 2)
				$srcImg = "images/female_body_ok.png";
			echo '<img id="imagenCuerpo" src="'.$srcImg.'" class="body" data-id="'.$paciente->idPaciente.'" data-image-id="1" data-album-id="1" align="middle" />';
			echo '</td></tr></table>';
			echo '<br />';
            $objHTML->label('Segmentos Afectados: ');
            echo '<br />';
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
			
            $objCatalogo = new Catalogo('catNumeroLesiones');
            $catNumeroLesiones = $objCatalogo->getValores();
            foreach ($catNumeroLesiones as $key => $value) {
                $catNumeroLesiones[$key] = ($key-1).'. '.$value;
            }
            
            $objHTML->inputSelect('Número de lesiones', 'noLesiones', $catNumeroLesiones, $diagnostico->idCatNumeroLesiones, array('class'=>'validate[required]'));
            
			echo '</div>';
		$objHTML->endFieldset();
		
        
		$objHTML->startFieldset('', array('id'=>'fs_grado_discapacidad'));
			
			echo '<br />';
			$objHTML->label('Grado de discapacidad:');
			echo '<br /><br />';
			$discapacidad = array('0'=>'0', '1'=>'1', '2'=>'2');
			echo '<table><tr align="left"><td><strong>Ojo Izquierdo</strong> &nbsp; </td><td>';
			$objHTML->inputSelect('', 'ojo_izq', $discapacidad, $diagnostico->discOjoIzq, array('class'=>'validate[required]'));
			echo '</td><td><strong>Mano Izquierda</strong> &nbsp; </td><td>';
			$objHTML->inputSelect('', 'mano_izq', $discapacidad, $diagnostico->discManoIzq, array('class'=>'validate[required]'));
			echo '</td><td><strong>Pie Izquierdo</strong> &nbsp; </td><td>';
			$objHTML->inputSelect('', 'pie_izq', $discapacidad, $diagnostico->discPieIzq, array('class'=>'validate[required]'));
			echo '</td></tr><tr align="left"><td><strong>Ojo Derecho</strong> &nbsp; </td><td>';
			$objHTML->inputSelect('', 'ojo_der', $discapacidad, $diagnostico->discOjoDer, array('class'=>'validate[required]'));
			echo '</td><td><strong>Mano Derecha</strong> &nbsp; </td><td>';
			$objHTML->inputSelect('', 'mano_der', $discapacidad, $diagnostico->discManoDer, array('class'=>'validate[required]'));
			echo '</td><td><strong>Pie Derecho</strong> &nbsp; </td><td>';
			$objHTML->inputSelect('', 'pie_der', $discapacidad, $diagnostico->discPieDer, array('class'=>'validate[required]'));
			echo '</td></tr><tr><td><strong>General</strong> &nbsp; </td><td>';
			$objHTML->inputSelect('', 'discGeneral', $discapacidad);
			echo '</td><td></td><td></td><td></td><td></td></tr></table><br /><br />';
			
            $obligatorio = array('class'=>'validate[required]');
            
			$objHTML->label('Estado Reaccional:');
            echo '<br />';
			$objSelects->SelectCatalogo('Anterior', 'reaccional_anterior', 'catEstadoReaccional', $diagnostico->idCatEstadoReaccionalAnt);
            
            echo '<div id="edoRecAntTipo2" style="display:'.($diagnostico->idCatEstadoReaccionalAnt==3 ? 'inline' : 'none').';">';
            $objHTML->inputCheckbox('Eritema Nudoso', 'edoRecAntTipo2Nud', 1, $diagnostico->estReaAntEriNud);
            $objHTML->inputCheckbox('Eritema Polimorfo', 'edoRecAntTipo2Poli', 1, $diagnostico->estReaAntEriPol);
            $objHTML->inputCheckbox('Eritema Necrosante', 'edoRecAntTipo2Necro', 1, $diagnostico->estReaAntEriNec);
            echo '</div><br />';
            
			$objSelects->SelectCatalogo('Actual', 'reaccional_actual', 'catEstadoReaccional', $diagnostico->idCatEstadoReaccionalAct);
            echo '<div id="edoRecActTipo2" style="display:'.($diagnostico->idCatEstadoReaccionalAct==3 ? 'inline' : 'none').';">';
            $objHTML->inputCheckbox('Eritema Nudoso', 'edoRecActTipo2Nud', 1, $diagnostico->estReaActEriNud);
            $objHTML->inputCheckbox('Eritema Polimorfo', 'edoRecActTipo2Poli', 1, $diagnostico->estReaActEriPol);
            $objHTML->inputCheckbox('Eritema Necrosante', 'edoRecActTipo2Necro', 1, $diagnostico->estReaActEriNec);
            echo '</div>';
            
			echo '<br /><br />';
			
			$objHTML->label('Si hubo reacción anterior. ¿Desde Cuando?:  ');
			$objHTML->inputText('Tipo I ', 'tipo_uno', formatFechaObj($diagnostico->fechaReaccionAnteriorTipI),array('placeholder'=>'Fecha'));
			$objHTML->inputText('Tipo II ', 'tipo_dos', formatFechaObj($diagnostico->fechaReaccionAnteriorTipII),array('placeholder'=>'Fecha'));
			
			echo '<br /><br />';
            // Observacion: Ordenar la lista
			$objSelects->SelectCatalogo('Diagnóstico/Clasificación : ', 'diagnostico', 'catClasificacionLepra', $diagnostico->idCatClasificacionLepra, NULL, TRUE, FALSE, FALSE);
			$objSelects->SelectCatalogo('Estado del paciente : ', 'estado_paciente', 'catEstadoPaciente', $diagnostico->idCatEstadoPaciente);
			echo "<br />";
			$objSelects->SelectCatalogo('Tratamiento', 'tratamiento', 'catTratamientoPreescrito', $diagnostico->idCatTratamiento);
            
		$objHTML->endFieldset();
		
		
		$objHTML->startFieldset('', array('id'=>'fs_aquirio_enfermedad'));
		
			$objHTML->label('Lugar probable donde adquirió la enfermedad: ');
			echo '<br /><br />';
			$objSelects->selectEstado('edoAquirioEnfermedad', $diagnostico->idCatEstadoAdqEnf ? $diagnostico->idCatEstadoAdqEnf : $_SESSION[EDO_USR_SESSION]);
			$objSelects->selectMunicipio('muniAquirioEnfermedad', $diagnostico->idCatEstadoAdqEnf ? $diagnostico->idCatEstadoAdqEnf : $_SESSION[EDO_USR_SESSION], NULL, $diagnostico->idCatMunicipioAdqEnf );
			$objSelects->selectLocalidad('localiAquirioEnfermedad', $diagnostico->idCatEstadoAdqEnf, $diagnostico->idCatMunicipioAdqEnf, $diagnostico->idCatLocalidadAdqEnf);
		
		$objHTML->endFieldset();	
		
		
		$objHTML->startFieldset('', array('id'=>'fs_casos_relacionados'));
			
			$objHTML->label('Otros casos de Lepra relacionados con el presente (Antecedentes, colaterales o consecuentes)');
			echo '<br /><br />';
			echo '<div id="tmpl_caso_relacionado" style="display:none; border:#999 dotted 1px;">';
				$objHTML->inputHidden('idCasoRelacionado_', 0);
				$objHTML->inputText('', 'nombre_caso_relacionado_','',array('placeholder'=>'Nombre'));
				$objSelects->SelectCatalogo('Parentesco', 'parentesco_caso_relacionado_', 'catParentesco');
				$objHTML->inputText('Convivencia', 'ano_caso_relacionado_','',array('placeholder'=>'A&ntilde;os','size'=>'5'));
				$objHTML->inputText("", 'meses_caso_relacionado_','',array('placeholder'=>'Meses','size'=>'5'));
				$objSelects->SelectCatalogo('Situación', 'situacion_caso_relacionado_', 'catSituacionCasoRelacionado');
				echo '<img src="images/error.gif" title="Eliminar Caso Relacionado" class="delCasoRelacionado" align="absmiddle" />';
			echo '</div>';
			
			$objHTML->inputHidden('no_caso_relacionado', (int)count($diagnostico->arrCasosRelacionados));
			$objHTML->inputHidden('del_casos_relacionados');
			
			echo '<div id="casoRelacionados">';
			for($i=0; $i<count($diagnostico->arrCasosRelacionados); $i++){
				echo '<div id="caso_relacionado_'.($i+1).'" style="border:#999 dotted 1px;">';
					$objHTML->inputHidden('idCasoRelacionado_'.($i+1), $diagnostico->arrCasosRelacionados[$i]->idCasoRelacionado);
					$objHTML->inputText('', 'nombre_caso_relacionado_'.($i+1), $diagnostico->arrCasosRelacionados[$i]->nombre, array('placeholder'=>'Nombre'));
					$objSelects->SelectCatalogo('Parentesco', 'parentesco_caso_relacionado_'.($i+1), 'catParentesco', $diagnostico->arrCasosRelacionados[$i]->idCatParentesco);
					$objHTML->inputText('Convivencia', 'ano_caso_relacionado_'.($i+1), $diagnostico->arrCasosRelacionados[$i]->tiempoConvivenciaAnos, array('placeholder'=>'A&ntilde;os','size'=>'5'));
					$objHTML->inputText("", 'meses_caso_relacionado_'.($i+1), $diagnostico->arrCasosRelacionados[$i]->tiempoConvivenciaMeses, array('placeholder'=>'Meses','size'=>'5'));
					$objSelects->SelectCatalogo('Situación', 'situacion_caso_relacionado_'.($i+1), 'catSituacionCasoRelacionado', $diagnostico->arrCasosRelacionados[$i]->idCatSituacionCasoRelacionado);
					echo '<img src="images/error.gif" title="Eliminar Caso Relacionado" class="delCasoRelacionado" align="absmiddle" />';
				echo '</div>';
			}
			echo '</div><br />';
			
			$objHTML->inputButton('agrega_caso_relacionado', 'Agregar nuevo caso relacionado', array('onClick'=>'agregaCasoRelacionado()'));
		
		$objHTML->endFieldset();
		
		
		$objHTML->startFieldset('', array('id'=>'fs_contactos'));
		
			$objHTML->label('Cuadro de contactos y/o convivientes');
			echo '<br /><br />';
			echo '<div id="tmpl_contacto" style="display:none; border:#999 dotted 1px;">';
				$objHTML->inputHidden('idContacto_', 0);
				$objHTML->inputText('', 'nombre_contacto_','',array('placeholder'=>'Nombre'));
				$objSelects->SelectCatalogo('Sexo', 'sexo_contacto_', 'catSexo', '', array('style'=>'width:20px;'));
				$objHTML->inputText('', 'edad_contacto_','',array('placeholder'=>'Edad', 'size'=>'2'));
				$objSelects->SelectCatalogo('Parentesco', 'parentesco_contacto_', 'catParentesco');
				$objHTML->inputText('Convivencia', 'ano_contacto_','',array('placeholder'=>'A&ntilde;os','size'=>'3'));
				$objHTML->inputText('', 'mes_contacto_','',array('placeholder'=>'Mes','size'=>'3'));
				echo '<img src="images/error.gif" title="Eliminar contacto" class="delContacto" align="absmiddle" />';
			echo '</div>';
			
			$objHTML->inputHidden('del_contactos');
			$objHTML->inputHidden('no_contactos', (int)count($diagnostico->arrContactos));
			echo '<div id="contactos">';
			for($i=0; $i<count($diagnostico->arrContactos); $i++){
				echo '<div id="contacto_'.($i+1).'" style="border:#999 dotted 1px;">';
					$objHTML->inputHidden('idContacto_'.($i+1), $diagnostico->arrContactos[$i]->idContacto);
					$objHTML->inputText('', 'nombre_contacto_'.($i+1), $diagnostico->arrContactos[$i]->nombre,array('placeholder'=>'Nombre'));
					$objSelects->SelectCatalogo('Sexo', 'sexo_contacto_'.($i+1), 'catSexo', $diagnostico->arrContactos[$i]->sexo);
					$objHTML->inputText('', 'edad_contacto_'.($i+1), $diagnostico->arrContactos[$i]->edad, array('placeholder'=>'Edad', 'size'=>'2'));
					$objSelects->SelectCatalogo('Parentesco', 'parentesco_contacto_'.($i+1), 'catParentesco', $diagnostico->arrContactos[$i]->idCatParentesco);
					$objHTML->inputText('Convivencia', 'ano_contacto_'.($i+1), $diagnostico->arrContactos[$i]->tiempoConvivenciaAnos, array('placeholder'=>'A&ntilde;o','size'=>'3'));
					$objHTML->inputText('', 'mes_contacto_'.($i+1), $diagnostico->arrContactos[$i]->tiempoConvivenciaMeses, array('placeholder'=>'Mes','size'=>'3'));
					echo '<img src="images/error.gif" title="Eliminar contacto" class="delContacto" align="absmiddle" />';
				echo '</div>';
			}
			echo '</div><br />';
			$objHTML->inputButton('agrega_contacto', 'Agregar nuevo contacto', array('onClick'=>'agregaContacto()'));
		
		$objHTML->endFieldset();
		
		
		$objHTML->startFieldset('Antecedentes Importantes', array('id'=>'fs_antecedentes'));
			echo '<div align="center">';
			$objHTML->inputTextarea('', 'otros_padecimientos', $diagnostico->otrosPadecimientos, array('placeholder'=>'Describir otras enfermedades','cols'=>'85', 'rows'=>'7'));
			echo '</div>';
		$objHTML->endFieldset();
        
        
        $objHTML->startFieldset('Observaciones', array('id'=>'fs_observaciones'));
			echo '<div align="center">';
			$objHTML->inputTextarea('', 'observaciones', $diagnostico->observaciones, array('cols'=>'85', 'rows'=>'7'));
			echo '</div>';
		$objHTML->endFieldset();
        
        
        if(!empty($paciente->idCatUnidadTratante)){
			$infUni = $help->getDatosUnidad($paciente->idCatUnidadTratante);
		}
		else
			$infUni = NULL;
		
		$objHTML->startFieldset('', array('id'=>'fs_manejo_caso'));
		
			$objHTML->label('Manejo de caso:');
			echo '<br /><br />';
			
            $objHTML->label('Unidad Tratante: ');
            echo '<br />';
			$objSelects->selectEstado('edoCaso', $infUni['idCatEstado'] ? $infUni['idCatEstado'] : $_SESSION[EDO_USR_SESSION], array('disabled'=>'disabled'));			
			$objSelects->selectJurisdiccion('jurisCaso', $infUni['idCatEstado'] ? $infUni['idCatEstado'] : $_SESSION[EDO_USR_SESSION], $infUni['idCatJurisdiccion']);
			$objSelects->selectMunicipio('muniCaso', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio']);
			echo '<br />';
			
			$objSelects->selectUnidad('uniTratado', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio'], NULL, $paciente->idCatUnidadTratante, array('class'=>'validate[required]'));
            $objSelects->SelectCatalogo('Institución', 'institucion_caso', 'catInstituciones', $paciente->idCatInstitucionTratante);
			$objHTML->inputText('', 'otra_institutcion_caso', $paciente->otraInstitucionTratante, array('placeholder'=>'Especifique', 'maxlength'=>'12')); //depende de institutos 
            
            echo '<br /><br />';
			
            if(!empty($paciente->idCatUnidadTratante)){
                $infUni = $help->getDatosUnidad($paciente->idCatUnidadTratante);
            }
            else
                $infUni = NULL;
            
            $objHTML->label('Caso Referido a:');
            $objSelects->selectEstadoGeneral('edoReferido', $paciente->idCatEstadoReferido);
			/*$objSelects->selectJurisdiccion('jurisReferido', $infUni['idCatEstado'] ? $infUni['idCatEstado'] : $_SESSION[EDO_USR_SESSION], $infUni['idCatJurisdiccion']);
			$objSelects->selectMunicipio('muniReferido', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio']);
            echo '<br />';
			$objSelects->selectUnidad('uniReferido', $infUni['idCatEstado'], $infUni['idCatJurisdiccion'], $infUni['idCatMunicipio'], NULL, $paciente->idCatUnidadReferido);
			echo '<br />';*/
		
		$objHTML->endFieldset();
        
        
        $objHTML->startFieldset('');
			$objHTML->inputText('Nombre del médico que elaboró la cédula', 'medicoElaboro', $paciente->medicoElaboro, array('size'=>'40', 'maxlength'=>'40', 'class'=>'validate[required]'));
			echo '<br />';
            $objHTML->inputText('Nombre del coordinador que supervisó/validó la cédula', 'medicoValido', $paciente->medicoValido, array('size'=>'40', 'maxlength'=>'40', 'class'=>'validate[required]'));
		$objHTML->endFieldset();
		
    // El usuario NACIONAL no debe registrar o actualizar registros de pacientes
    if($_SESSION[EDO_USR_SESSION] != 0) {
        if (!empty($_GET['id'])){
            $objHTML->inputHidden('actualizar', 1);
            $objHTML->endForm('actualizarSbmt', 'Actualizar', 'limpiar', 'Limpiar');
        }
        else {
            $objHTML->inputHidden('guardar', 1);
            $objHTML->endForm('guardarSbmt', 'Guardar', 'limpiar', 'Limpiar');
        }
    }
?>
