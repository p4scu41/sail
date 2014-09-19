<?php
	require_once('tcpdf_include.php');

	$pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	ob_clean();
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

	$idEstudio = $_GET["idEstudio"];
	$tipo =  $_GET["tipo"];

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

	<p>Estudio de tipo ' . $tipo . ' con el id ' . $idEstudio . '</p>
	
	<p><strong>Anexo I: </strong>Formato de notificación de rechazo de muestra</p>
	<p>&nbsp;</p>
	<table width="100%" border="0">
	  <tr>
		<td width="83%">&nbsp;</td>
		<td width="17%">RMC-F-02<br />
		<br /></td>
	  </tr>
	</table>
	<table width="100%" border="0">
	  <tr>
		<td width="19%" align="right"><img src="images/LogoInstitucional.png" width="100" height="50" alt="test alt attribute" /></td>
		<td width="64%"><p class="centrado">LABORATORIO ESTATAL DE SALUD PÚBLICA <br />
	  RECEPCION DE MUESTRAS<br />
	  TEL.: 01 961 60 4 27 95<br /></p>
		<p class="centrado"><strong>NOTIFICACION DE RECHAZO DE MUESTRA </strong></p></td>
		<td width="17%"><img src="images/logo_lesp_chis.png" width="65" height="55" alt="test alt attribute" /></td>
	  </tr>
	</table>

	<p>&nbsp;</p>
	<table width="100%" border="0">
	  <tr>
		<td align="right"><p>No. DE OFICIO_________________</p>
		<p>FECHA________________________</p></td>
	  </tr>
	</table>
	<p>&nbsp;</p>
	<table width="100%" border="1">
	  <tr>
		<td width="100%"><p><strong>1.- DATOS DEL DESTINATARIO</strong></p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p></td>
	  </tr>
	</table>

	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<table width="100%" border="1">
	  <tr>
		<td colspan="5"><strong>2. RELACION DE MUESTRA (s) RECHAZADA (S).</strong></td>
	  </tr>
	  <tr>
		<td align="center">CLAVE<br />
		LESP</td>
		<td align="center">Nombre o clave del paciente o producto</td>
		<td align="center">Tipo de muestra </td>
		<td align="center">Estudio solicitado </td>
		<td align="center">Criterios de rechazo</td>
	  </tr>
	  <tr>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	  </tr>
	  <tr>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	  </tr>
	  <tr>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	  </tr>
	</table>


	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<table width="100%" border="0">
	  <tr>
		<td width="5%">&nbsp;</td>
		<td width="95%"><strong>Observaciones:</strong></td>
	  </tr>
	</table>
	<table width="100%" border="0">
	  <tr>
		<td width="5%">&nbsp;</td>
		<td width="5%">1._</td>
		<td width="50%">&nbsp;</td>
		<td width="5%">9._</td>
		<td width="35%">&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>2._</td>
		<td>&nbsp;</td>
		<td>10._</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>3._</td>
		<td>&nbsp;</td>
		<td>11._</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>4._</td>
		<td>&nbsp;</td>
		<td>12._</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>5._</td>
		<td>&nbsp;</td>
		<td>13._</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>6._</td>
		<td>&nbsp;</td>
		<td>14._</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>7._</td>
		<td>&nbsp;</td>
		<td>15._</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>8._</td>
		<td>&nbsp;</td>
		<td>16._</td>
		<td>&nbsp;</td>
	  </tr>
	</table>


	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p><strong>A t e n t a m e n t e</strong></p>
	<p>&nbsp;</p>
	<p><strong>Nombre </strong></p>
	<p><strong>Jefe del Laboratorio Estatal de Salud Pública.</strong></p>
	<p>&nbsp;</p>
	<table width="100%" border="0">
	  <tr>
		<td width="6%">C.c.p. </td>
		<td width="94%" rowspan="3">Nombre.- director de Salud Públia.- Ciudad<br />
	Nombre.- Director de Protección contra Riesgo Sanitario.- Ciudad <br />
	Archivo</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
	  </tr>
	</table>
	<p>Rúbricas de la Subdirección Técnica/Jefe de Recepción de Muestras y Emisión de Resultado.</p>';

	$pdf->writeHTML($html, true, 0, true, 0);
	$pdf->lastPage();
	$pdf->Output('rechazo_muestra'. $idEstudio .'.pdf', 'I');
?>
