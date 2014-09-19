<?php
	require_once('../../../include/var_global.php');
	require_once('../../../include/bdatos.php');
	require_once('../../../include/log.php');
	require_once('../../../include/fecha_hora.php');
	require_once('../../../include/clasesLepra.php');
	require_once('tcpdf_include.php');

	$idEstudioBac = $_GET["idEstudioBac"];

	// BD //////////////////////////////////////
	$idTipoEstudioControl = 2;
	// BD //////////////////////////////////////

	$connectionBD = conectaBD();
	$help = new helpers();
	$estudioBac = new EstudioBac();
	$estudioBac->obtenerBD($idEstudioBac);
	$arrResBac = $help->getArrayCatBaciloscopia();
	var_dump($estudioBac);
	echo is_null($estudioBac->idContacto);
	echo $estudioBac->idDiagnostico == 0;

	// VARIABLES QUE SE IMPRIMEN
	if ( is_null($estudioBac->idContacto) && $estudioBac->idDiagnostico == 0) {		// SOSPECHOSO
		$claveLesp = $estudioBac->folioLaboratorio;
		$nombre = $help->getNamePaciente($estudioBac->idPaciente);
		$edad = $help->getEdadPaciente($estudioBac->idPaciente);
		$sexo = $help->getSexoPaciente($estudioBac->idPaciente);	
	} elseif ( is_null($estudioBac->idContacto) && is_null($estudioBac->idPaciente) ) {	// CONFIRMADO
		$diagnostico = new Diagnostico();
		$diagnostico->obtenerBD($estudioBac->idDiagnostico);
		$claveLesp = $estudioBac->folioLaboratorio;
		$nombre = $help->getNamePaciente($diagnostico->idPaciente);
		$edad = $help->getEdadPaciente($diagnostico->idPaciente);
		$sexo = $help->getSexoPaciente($diagnostico->idPaciente);			
	} else {																			// CONTACTO
		$contacto = new Contacto();
		$contacto->obtenerBD($estudioBac->idContacto);
		$claveLesp = $estudioBac->folioLaboratorio;
		$nombre = $contacto->nombre;
		$edad = $contacto->edad;			
		if ($contacto->sexo == 1) $sexo = "Masculino"; else $sexo = "Femenino"; 
	}
	$fechaRec = formatFechaObj($estudioBac->fechaRecepcion, 'Y-m-d');
	$fechaRea = formatFechaObj($estudioBac->fechaTomaMuestra, 'Y-m-d');
	$indBac1 = $arrResBac[$estudioBac->idCatBacFrotis1];
	$indBac2 = $arrResBac[$estudioBac->idCatBacFrotis2];
	$indBac3 = $arrResBac[$estudioBac->idCatBacFrotis3];
	$indBacProm = $arrResBac[$estudioBac->idCatBac];
	$indMorPor1 = $estudioBac->bacPorcViaFrotis1;
	$indMorPor2 = $estudioBac->bacPorcViaFrotis2;
	$indMorPor3 = $estudioBac->bacPorcViaFrotis3;
	$indMorProm = $estudioBac->bacIM;
	if ($estudioBac->idTipoEstudio == $idTipoEstudioControl) { $diagnostico = ""; $control = "X"; } else { 	$diagnostico = "X"; $control = ""; }
	if ($estudioBac->bacCalidadAdecFrotis1 == 1) { $calAdcFro1 = "X"; $calInaFro1 = "";  }else { $calAdcFro1 = ""; $calInaFro1 = "X"; }
	if ($estudioBac->bacCalidadAdecFrotis2 == 1) { $calAdcFro2 = "X"; $calInaFro2 = "";  }else { $calAdcFro2 = ""; $calInaFro2 = "X"; }
	if ($estudioBac->bacCalidadAdecFrotis3 == 1) { $calAdcFro3 = "X"; $calInaFro3 = "";  }else { $calAdcFro3 = ""; $calInaFro3 = "X"; }
	$observaciones = $estudioBac->bacObservaciones;
	$fechaRes = formatFechaObj($estudioBac->fechaResultado, 'Y-m-d');
	if (!is_null($estudioBac->idCatSupervisorLab)) $supervisor = $help->getSupervisorLab($estudioBac->idCatSupervisorLab);
	if (!is_null($estudioBac->idCatAnalistaLab)) $analista = $help->getAnalistaLab($estudioBac->idCatAnalistaLab);
	$connectionBD = closeConexion();
	//////// PROCESO PDF
	
	$pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	ob_clean();
	$pdf->SetPrintHeader(false);
	$pdf->SetPrintFooter(false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(10, 10, 10, true);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}

	$pdf->SetFont('helvetica', '', 8);
	$pdf->AddPage();
	
	$html = '
		<style type="text/css">
		.centrado {
			text-align: center;
		}
		.centrado {
			text-align: center;
		}
		.centrado {
			text-align: center;
		}
		</style>


		<table width="100%" border="0">
		  <tr>
			<td width="15%"><img src="images/LogoInstitucional.png" width="100" height="50" alt="test alt attribute" /></td>
			<td width="65%" class="centrado"><p>SECRETARIA DE SALUD <br />
			  LABORATORIO ESTATAL DE SALUD PUBLICA<br />
			  PROGRAMA DE PREVENCION Y CONTROL DE TUBERCULOSIS Y LEPRA <br />
			  LABORATORIO DE MICOBACTERIAS<br />
			  INFORME DEL RESULTADO BACILOSCÓPICO DE LEPRA 
			</p></td>
			<td width="20%"><img src="images/logo_lesp_chis.png" width="65" height="55" alt="test alt attribute"/> </td>
		  </tr>
		</table>
		<p>&nbsp;</p>
		<p>JURISDICCION SANITARIA:</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<table width="100%" border="0">
		  <tr>
			<td width="32%">CLAVE LESP:</td>
			<td width="19%">' . (utf8_encode($claveLesp)) . '</td>
			<td width="10%">&nbsp;</td>
			<td width="15%">EDAD:</td>
			<td width="24%">' . $edad . '</td>
		  </tr>
		  <tr>
			<td>NOMBRE DEL PACIENTE:</td>
			<td>' . (utf8_encode($nombre)) . '</td>
			<td>&nbsp;</td>
			<td>SEXO:</td>
			<td>' . $sexo . '</td>
		  </tr>
		  <tr>
			<td>FECHA DE RECEPCIÓN:</td>
			<td>' . $fechaRec . '</td>
			<td>&nbsp;</td>
			<td>DIAGNOSTICO</td>
			<td>' . (utf8_encode($diagostico)) . '</td>
		  </tr>
		  <tr>
			<td>FECHA DE REALIZACION DE LA PRUENA:</td>
			<td>' . $fechaRea . '</td>
			<td>&nbsp;</td>
			<td>CONTROL:</td>
			<td>' . (utf8_encode($control)) . '</td>
		  </tr>
		</table>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<table width="100%" border="1">
		  <tr>
			<td rowspan="2" class="centrado">SITIO DE LA MUESTRA</td>
			<td rowspan="2"><p class="centrado">ÍNDICE <br /> BACILOSCOPICO</p></td>
			<td rowspan="2" class="centrado">ÍNDICE MORFOLÓGICO <br />
			% DE BACILOSVIABLES </td>
			<td colspan="2" class="centrado">CALIDAD DE LA MUESTRA</td>
		  </tr>
		  <tr>
			<td class="centrado">ADECUADA</td>
			<td class="centrado">INADECUADA</td>
		  </tr>
		  <tr>
			<td>LÓBULO DE LA OREJA</td>
			<td>' . $indBac1 . '</td>
			<td>' . $indMorPor1 . '</td>
			<td>' . $calAdcFro1 . '</td>
			<td>' . $calInaFro1 . '</td>
		  </tr>
		  <tr>
			<td>LESIÓN CUTÁNEA</td>
			<td>' . $indBac2 . '</td>
			<td>' . $indMorPor2 . '</td>
			<td>' . $calAdcFro2 . '</td>
			<td>' . $calInaFro2 . '</td>
		  </tr>
		  <tr>
			<td>MUCOSA NASAL</td>
			<td>' . $indBac3 . '</td>
			<td>' . $indMorPor3 . '</td>
			<td>' . $calAdcFro3 . '</td>
			<td>' . $calInaFro3 . '</td>
		  </tr>
		  <tr>
			<td>PROMEDIO</td>
			<td>' . $indBacProm . '</td>
			<td>' . $indMorProm . '</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		</table>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<br />
		<br />
		<br />
		<br />
		<table width="100%" border="0">
		  <tr>
			<td width="17%">OBSERVACIONES:</td>
			<td width="83%">' . (utf8_encode($observaciones)) . '</td>
		  </tr>
		</table>
		<br />
		<br />
		<br />
		<br />
		<br />
		<p>&nbsp;</p>
		<table width="100%" border="0">
		  <tr>
			<td width="53%" class="centrado">ÍNDICE BACILOSCOPICO</td>
			<td colspan="2" class="centrado">BACILOS POR CAMPO</td>
		  </tr>
		  <tr>
			<td class="centrado">Negativo</td>
			<td width="24%">NO HAY </td>
			<td width="23%">bacilos en 100 campos </td>
		  </tr>
		  <tr>
			<td class="centrado">1+</td>
			<td>1 - 10</td>
			<td>bacilos en 100 campos </td>
		  </tr>
		  <tr>
			<td class="centrado">2+</td>
			<td>1 - 10</td>
			<td>bacilos en 10 campos </td>
		  </tr>
		  <tr>
			<td class="centrado">3+</td>
			<td>1 - 10</td>
			<td>bacilos en cada campos </td>
		  </tr>
		  <tr>
			<td class="centrado">4+</td>
			<td>10 - 100</td>
			<td>bacilos en cada campos </td>
		  </tr>
		  <tr>
			<td class="centrado">5+</td>
			<td>100 - 1000</td>
			<td>bacilos en cada campos </td>
		  </tr>
		  <tr>
			<td class="centrado">6+</td>
			<td>+ DE 1000 </td>
			<td>bacilos en cada campos </td>
		  </tr>
		  <tr>
			<td class="centrado">+</td>
			<td colspan="2" class="centrado">bacilos aislados</td>
		  </tr>
		  <tr>
			<td class="centrado"><img src="images/bacilos_globias.png" alt="Bacilos Globias"></td>
			<td colspan="2" class="centrado">bacilos en globias</td>
		  </tr>
		</table>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<table width="100%" border="0">
		  <tr>
			<td class="centrado"><u>' . (utf8_encode($analista)) . '</u><br />
			  ANALISTA
			</p></td>
			<td class="centrado"><u>' . $fechaRes . '</u><br />
			FECHA</td>
			<td class="centrado"><u>' . (utf8_encode($supervisor)) . '</u><br />
			JEFE DEL ÁREA DE MICOBACTERIAS</td>
		  </tr>
		</table>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p class="centrado">ESTE INFORME NO PODRÁ SER REPRODUCIDO PARCIAL NI TOTALMENTE SIN LA PREVIA AUTORIZACIÓN DEL LABORATORIO ESTATAL DE SALUD PÚBLICA. </p>
		<p class="centrado">ESTE RESULTADO SE REFIERE ÚNICAMENTE A LA MUESTRA RECIBIDA </p>';

	$pdf->writeHTML($html, true, 0, true, 0);
	$pdf->lastPage();
	$pdf->Output('respuesta_estudio_baciloscopico'. $idEstudioBac .'.pdf', 'I');
	//echo $html;
?>
