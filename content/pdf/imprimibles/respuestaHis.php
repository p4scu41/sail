<?php

	require_once('../../../include/var_global.php');
	require_once('../../../include/bdatos.php');
	require_once('../../../include/log.php');
	require_once('../../../include/fecha_hora.php');
	require_once('../../../include/clasesLepra.php');
	require_once('tcpdf_include.php');
	
	$idEstudioHis = $_GET["idEstudioHis"];

	$connectionBD = conectaBD();
	$help = new helpers();
	$estudioHis = new EstudioHis();
	$estudioHis->obtenerBD($idEstudioHis);
	$arrHis = $help->getArrayCatHistopatologia();
	$estudioSolicitado = "Histopatologia";
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// VARIABLES QUE SE IMPRIMEN
	if ( (is_null($estudioHis->idContacto) && $estudioHis->idDiagnostico == 0)) {		// SOSPECHOSOS			
		$nombre = $help->getNamePaciente($estudioHis->idPaciente);
		$arrUbic = $help->getArrDomicilioMunicipioEstado($estudioHis->idPaciente);
		$domicilio = $arrUbic["domicilio"];
		$municipio = $arrUbic["municipio"];
		$estado = $arrUbic["estado"];
	} elseif (is_null($estudioHis->idContacto) && is_null($estudioHis->idPaciente)) {	// CONFIRMADO
		$diagnostico = new Diagnostico();
		$diagnostico->obtenerBD($estudioHis->idDiagnostico);
		$nombre = $help->getNamePaciente($diagnostico->idPaciente);
		$arrUbic = $help->getArrDomicilioMunicipioEstado($diagnostico->idPaciente);
		$domicilio = $arrUbic["domicilio"];
		$municipio = $arrUbic["municipio"];
		$estado = $arrUbic["estado"];
	} else {																			// CONTACTO
		$contacto = new Contacto();
		$contacto->obtenerBD($estudioHis->idContacto);
		$diagnostico = new Diagnostico();
		$diagnostico->obtenerBD($estudioHis->idDiagnostico);
		$nombre = $contacto->nombre;
		$arrUbic = $help->getArrDomicilioMunicipioEstado($diagnostico->idPaciente);
		$domicilio = "* " . $arrUbic["domicilio"];
		$municipio = "* " . $arrUbic["municipio"];
		$estado = "* " . $arrUbic["estado"];		
	}

	$claveLesp = $estudioHis->folioLaboratorio;
	$fechaRec = formatFechaObj($estudioHis->fechaRecepcion, 'd-m-Y');;
	$fechaRea = formatFechaObj($estudioHis->fechaTomaMuestra, 'd-m-Y');;
	$personaR = $estudioHis->personaSolicitudEstudio;
	$estudioSoliciatdo = "Histopatologia";
	$tipoMuestra = $estudioHis->lesionTomoMuestra;
	$macro = $estudioHis->hisDescMacro;
	$micro = $estudioHis->hisDescMicro;
	$resul = $estudioHis->hisResultado . " " . $arrHis[$estudioHis->idCatHisto];
	$supervisor = $help->getSupervisorLab($estudioHis->idCatSupervisorLab);
	$analista = $help->getAnalistaLab($estudioHis->idCatAnalistaLab);
	$connectionBD = closeConexion();
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	$pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetPrintHeader(false);
	$pdf->SetPrintFooter(false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(10, 10, 10, true); // set the margins 
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
	ob_clean();





// create new PDF document
$pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
ob_clean();
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

// set document information
$pdf->SetCreator(PDF_CREATOR);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(10, 10, 10, true); // set the margins 
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content


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
    <td width="19%" align="right"><img src="images/LogoInstitucional.png" width="100" height="50" alt="test alt attribute" /></td>
    <td width="64%"><p class="centrado">LABORATORIO ESTATAL DE SALUD PÚBLICA <br />
  PROGRAMA DE PREVENCION Y CONTROL DE TURBECULOSIS Y LEPRA<br />
  LABORATORIO DE MICOBACTERIAS<br />
  INFORMES DE RESULTADO HISTOPATOLÓGICO DE LEPRA</p></td>
    <td width="17%"><img src="images/logo_lesp_chis.png" width="65" height="55" alt="test alt attribute" /></td>
  </tr>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="100%" border="0">
  <tr>
    <td><strong>1. Identificación de caso.</strong></td>
  </tr>
</table>
<table width="100%" border="1">
  <tr>
    <td width="50%">Clave LESP:'. utf8_encode($claveLesp).'</td>
    <td width="50%">Fecha de recepción:'. $fechaRec .'</td>
  </tr>
  <tr>
    <td>Institución remitente: ISECH</td>
    <td>Fecha de realización de la prueba: '. $fechaRea . '</td>
  </tr>
  <tr>
    <td colspan="2">Persona responsable del caso: ' . utf8_encode($personaR) . '</td>
  </tr>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>


<table width="100%" border="0">
  <tr>
    <td><strong>2. Datos del paciente.</strong></td>
  </tr>
</table>
<table width="100%" border="1">
  <tr>
    <td colspan="2">Nombre del paciente: ' . utf8_encode($nombre) . '</td>
  </tr>
  <tr>
    <td colspan="2">Domicilio: '. utf8_encode($domicilio) .'</td>
  </tr>
  <tr>
    <td>Municipio: '. utf8_encode($municipio) .'</td>
    <td>Estado: '. utf8_encode($estado) .'</td>
  </tr>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<table width="100%" border="0">
  <tr>
    <td><strong>3. Resultado del estudio.</strong></td>
  </tr>
</table>
<table width="100%" border="1">
  <tr>
    <td colspan="2">Estudio Solicitado: '. utf8_encode($estudioSolicitado) .'</td>
  </tr>
  <tr>
    <td colspan="2">Tipo de Muestra: '. utf8_encode($tipoMuestra) .'</td>
  </tr>
  <tr>
    <td width="24%">Descripción macroscópica:<br />
    <br />
    <br /></td>
    <td width="76%">'. utf8_encode($macro) .'</td>
  </tr>
  <tr>
    <td>Descripción microscópica:<br />
    <br />
    <br /></td>
    <td>'. utf8_encode($micro) .'</td>
  </tr>
  <tr>
    <td>Resultado:</td>
    <td>' . utf8_encode($resul) .'</td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>



<table width="100%" border="0">
  <tr>
    <td width="50%"><p class="centrado">__________________________________<br />
      '. (utf8_encode($analista)) . ' <br />
      Analista
    </p></td>
    <td width="50%" class="centrado">____________________________________<br />
      '. (utf8_encode($supervisor)) .'<br />
      Anatomopatólogo</td>
  </tr>
</table>';

	$pdf->writeHTML($html, true, 0, true, 0);
	$pdf->lastPage();
	$pdf->Output('respuesta_estudio_histipatologico'. $idEstudioHis .'.pdf', 'I');
	//echo $html;
	//echo $html;
?>
