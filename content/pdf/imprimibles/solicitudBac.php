<?php
	require_once('../../../include/var_global.php');
	require_once('../../../include/bdatos.php');
	require_once('../../../include/log.php');
	require_once('../../../include/fecha_hora.php');
	require_once('../../../include/clasesLepra.php');
	require_once('tcpdf_include.php');

	class MYPDF extends TCPDF {

		public function Header() {
			
			$titulo="";
			$titulo = '<table border="0" width="100%">
				<tr>
					<th align="center"><h2>&nbsp;</h2></th>
				</tr>
				<tr>
					<th align="center"><h2>SOLICITUD DE ESTUDIO BACILOSCÓPICO PARA LEPRA</h2></th>
				</tr>
			</table>';
			
			$this->writeHTML($titulo, true, false, true, false, '');
			$this->Ln(10);
		}

		public function Footer() {
			$this->SetY(-15);
			$this->SetFont('helvetica', '', 8);
			$this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('http://www.ariepi.org');
	$pdf->SetTitle('Reporte');
	$pdf->SetSubject('Reporte General');
	$pdf->SetKeywords('reporte, general, ariepi');
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'ARIEPI', 'Reporte General');
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}
	$pdf->SetFont('helvetica', '', 9);
	$pdf->AddPage('P', 'A4');
	ob_end_clean();

	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	// BD //////////////////////////////////////	
	$idTipoEstudioControl = 2;
	$idCatTipoll = 1;	$idCatTipolt = 3;	$idCatTipold = 2;	$idCatTipoli = 4;	$idCatTipolc = 5;		// idCatTipoLepra
	/* idCatClasificacionLepra	descripcion
	1	Lepromatosa (MB)
	2	Dimorfa (MB)
	3	Tuberculoide (PB)
	4	Indeterminado (PB)
	5	No Especificado */
	$idCatTratMT = 1;	$idCatTratPQ = 2;	$idCatTratOE = 3;	$idCatTratST = 4;
	/* idCatTratamiento	descripcion
	1	Monoterapia
	2	PQT
	3	Otro Esquema
	4	Sin Tratamiento */
	// BD //////////////////////////////////////

	$idEstudioBac = $_GET["idEstudioBac"];	
	$connectionBD = conectaBD();

	$help = new helpers();
	$estudioBac = new EstudioBac();
	$estudioBac->obtenerBD($idEstudioBac);
	$arrResBac = $help->getArrayCatBaciloscopia();	
	
	$diag = 1;	$control = 0;
	$tipoll = 0;	$tipolt = 0;	$tipold = 0;	$tipoli = 0;	$tipolc = 0;
	$mono = 0;		$pqt = 0;		$oe = 0;		$st = 0;
	
	if (is_null($estudioBac->idContacto) && ($estudioBac->idDiagnostico == 0)) {		// SOSPECHOSOS
		$nombre = $help->getNamePaciente($estudioBac->idPaciente);
		$edad = $help->getEdadPaciente($estudioBac->idPaciente);
		$sex = $help->getSexoPaciente($estudioBac->idPaciente);
		$arrUbic = $help->getArrDomicilioMunicipioEstado($estudioBac->idPaciente);
		$domicilio = $arrUbic["domicilio"];
		$municipio = $arrUbic["municipio"];
		$estado = $arrUbic["estado"];
		$localidad = $arrUbic["localidad"];
		$arrUnid = $help->getArrDatosUnidadTratante($estudioBac->idPaciente);
		$unidad_sal = $arrUnid["nombre"];
		$insitucion = $arrUnid["institucion"];
		$localidad_sol = $arrUnid["localidad"];
		$municipio_sol = $arrUnid["municipio"];
		$estado_sol = $arrUnid["estado"];
		$tiempo =  'No confirmado';
		$antecedentes = '';
		if ($sex == "Masculino") $sex = 1; else $sex = 0;
	} elseif (is_null($estudioBac->idContacto) && is_null($estudioBac->idPaciente)) {		// CONFIRMANDO
		$diagnostico = new Diagnostico();
		$diagnostico->obtenerBD($estudioBac->idDiagnostico);
		$nombre = $help->getNamePaciente($diagnostico->idPaciente);
		$edad = $help->getEdadPaciente($diagnostico->idPaciente);
		$sex = $help->getSexoPaciente($diagnostico->idPaciente);
		$arrUbic = $help->getArrDomicilioMunicipioEstado($diagnostico->idPaciente);
		$domicilio = $arrUbic["domicilio"];
		$municipio = $arrUbic["municipio"];
		$estado = $arrUbic["estado"];
		$localidad = $arrUbic["localidad"];
		$arrUnid = $help->getArrDatosUnidadTratante($diagnostico->idPaciente);
		$unidad_sal = $arrUnid["nombre"];
		$insitucion = $arrUnid["institucion"];
		$localidad_sol = $arrUnid["localidad"];
		$municipio_sol = $arrUnid["municipio"];
		$estado_sol = $arrUnid["estado"];
		$tiempo =  $help->getTiempoDeTratamiento($diagnostico->idPaciente, $estudioBac->fechaSolicitud);
		$antecedentes = $help->getOtrosPadecimientos($diagnostico->idPaciente);

		$tipoLepra = $diagnostico->idCatClasificacionLepra;
		$tipoTratamiento = $diagnostico->idCatTratamiento;
		
		if ($sex == "Masculino") $sex = 1; else $sex = 0;
		if ($tipoLepra == $idCatTipoll) $tipoll = 1;
		elseif ($tipoLepra == $idCatTipolt) $tipolt = 1;
		elseif ($tipoLepra == $idCatTipold) $tipold = 1;
		elseif ($tipoLepra == $idCatTipoli) $tipoli = 1;
		elseif ($tipoLepra == $idCatTipolc) $tipolc = 1;
		if ($tipoTratamiento == $idCatTratMT) $mono = 1;
		elseif ($tipoTratamiento == $idCatTratPQ) $pqt = 1;
		elseif ($tipoTratamiento == $idCatTratOE) $oe = 1;
		elseif ($tipoTratamiento == $idCatTratST) $st = 1;
	} else {																				// CONTACTO
		$contacto = new Contacto();
		$diagnostico = new Diagnostico();
		$contacto->obtenerBD($estudioBac->idContacto);
		$diagnostico->obtenerBD($estudioBac->idDiagnostico);
		$nombre = $contacto->nombre;
		$edad = $contacto->edad;
		$sex = $contacto->sexo;
		$arrUbic = $help->getArrDomicilioMunicipioEstado($diagnostico->idPaciente);
		$domicilio = "*" . $arrUbic["domicilio"];
		$municipio = "*" . $arrUbic["municipio"];
		$estado = "*" . $arrUbic["estado"];
		$localidad = "*" . $arrUbic["localidad"];
		$arrUnid = $help->getArrDatosUnidadTratante($diagnostico->idPaciente);
		$unidad_sal = "*" . $arrUnid["nombre"];
		$insitucion = "*" . $arrUnid["institucion"];
		$localidad_sol = "*" . $arrUnid["localidad"];
		$municipio_sol = "*" . $arrUnid["municipio"];
		$estado_sol = "*" . $arrUnid["estado"];
		$tiempo = "*" . $help->getTiempoDeTratamiento($diagnostico->idPaciente, $estudioBac->fechaSolicitud);
		$antecedentes = '';		
	}
	

	$PersonaTomaMuestra = $estudioBac->personaTomaMuestra;
	$FechaTomaMuestra = formatFechaObj($estudioBac->fechaTomaMuestra, 'Y-m-d');
	$solicitante = $estudioBac->personaSolicitudEstudio;
	$FechaSolicitud = formatFechaObj($estudioBac->fechaSolicitud, 'Y-m-d');				
	$loOr = $estudioBac->tomMueFrotis1;
	$leCu = $estudioBac->tomMueFrotis2;
	$coCe = $estudioBac->tomMueFrotis3;	
	if ($estudioBac->idCatTipoEstudio == $idTipoEstudioControl) { $diag = 0;	$control = 1; }
	$connectionBD = closeConexion();
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	$html="";
	$html = $html.'<table border="0" width="100%">
							<tr>
								<th align="center"><strong>DATOS DEL CASO</strong></th>
							</tr>
						</table>';
					
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="4%">&nbsp;</td>
							<td width="22%">NOMBRE DEL PACIENTE:</td>
							<td width="35%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html . (utf8_encode($nombre));
				$html = $html.'</td>
							<td width="7%" align="right">EDAD:</td>
							<td width="6%"  style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. $edad; 						
				$html = $html.'</td>
							<td width="8%" align="right">SEXO</td>
							<td width="4%" align="right">M</td>
							<td width="4%" ';
							if ($sex == 1){
				$html = $html.'bgcolor="#666666"';
							}
				$html = $html.'style="border:#000 solid 2px">&nbsp;</td>
							<td width="2%" align="right">F</td>
							<td width="4%" ';
							if ($sex == 0){
				$html = $html.'bgcolor="#666666"';
							}
				$html = $html.'style="border:#000 solid 2px">&nbsp;</td>
							<td width="4%">&nbsp;</td>
						  </tr>
						</table>';
						
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="4%">&nbsp;</td>
							<td width="10%">DOMICILIO:</td>
							<td style="border-bottom:#000 solid 2px" width="82%">&nbsp;';
				$html = $html. (utf8_encode($domicilio));
				$html = $html.'</td>
							<td width="4%">&nbsp;</td>
						  </tr>
						</table>';	
				
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td width="4%">&nbsp;</td>
								<td width="11%">LOCALIDAD:</td>
								<td width="21%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($localidad));
				$html = $html.'</td>
								<td width="10%">MUNICIPIO:</td>
								<td width="24%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($municipio));
				$html = $html.'</td>
								<td width="9%">ESTADO:</td>
								<td width="17%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($estado));
				$html = $html.'</td>
								<td width="4%">&nbsp;</td>
							  </tr>
							</table>';	
							
				//Datos del Solicitante			
				
				$html = $html.'<table border="0" width="100%">
							<tr><th>&nbsp;</th></tr>
							<tr>
								<th align="center"><strong>DATOS DEL SOLICITANTE</strong></th>
							</tr>
						</table>';	
				
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td width="4%">&nbsp;</td>
								<td width="18%">UNIDAD DE SALUD:</td>
								<td width="31%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($unidad_sal));
				$html = $html.'</td>
								<td width="10%">INSTITUCION:</td>
								<td width="33%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($insitucion));
				$html = $html.'</td>
								<td width="4%">&nbsp;</td>
							  </tr>
							</table>';	
						
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td width="4%">&nbsp;</td>
								<td width="11%">LOCALIDAD:</td>
								<td width="21%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($localidad_sol));
				$html = $html.'</td>
								<td width="10%">MUNICIPIO:</td>
								<td width="24%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($municipio_sol));	
				$html = $html.'</td>
								<td width="9%">ESTADO:</td>
								<td width="17%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($estado_sol));
				$html = $html.'</td>
								<td width="4%">&nbsp;</td>
							  </tr>
							</table>';
							
							
				//Diagnostico clínico			
				
				$html = $html.'<table border="0" width="100%">
							<tr><th>&nbsp;</th></tr>
							<tr>
								<th align="center"><strong>DIAGNOSTICO CLÍNICO (MARQUE CON UNA X)</strong></th>
							</tr>
						</table>';
						
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="4%">&nbsp;</td>
							<td width="27%" align="center">MB</td>
							<td width="5%" align="center">&nbsp;</td>
							<td width="25%" align="center">PB</td>
							<td width="5%" align="center">&nbsp;</td>
							<td width="24%" align="center">ESTUDIO PARA:</td>
							<td width="6%" align="center">&nbsp;</td>
							<td width="4%">&nbsp;</td>
						  </tr>
						  <tr>
							<td>&nbsp;</td>
							<td>LEPRA LEPROMATOSA</td>
							<td align="center">(';
							if($tipoll == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
							<td>LEPRA TUBERCULOIDE</td>
							<td align="center">(';
							if($tipolt == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
							<td>DIAGN&Oacute;STICO</td>
							<td align="center">(';
							if($diag == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
							<td>&nbsp;</td>
						  </tr>
						  <tr>
							<td>&nbsp;</td>
							<td>LEPRA DIMORGA</td>
							<td align="center">(';
							if($tipold == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
							<td>LEPRA INDETERMINADA</td>
							<td align="center">(';
							if($tipoli == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
							<td>CONTROL</td>
							<td align="center">(';
							if($control == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
							<td>&nbsp;</td>
						  </tr>
						  <tr>
							<td>&nbsp;</td>
							<td>LEPRA SIN CLASIFICAR</td>
							<td align="center">(';
							if($tipolc == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
							<td>&nbsp;</td>
							<td align="center">&nbsp;</td>
							<td>&nbsp;</td>
							<td align="center">&nbsp;</td>
							<td>&nbsp;</td>
						  </tr>
						</table>';	
						
				//Datos clínicos			
				
				$html = $html.'<table border="0" width="100%">
							<tr><th>&nbsp;</th></tr>
							<tr>
								<th align="center"><strong>DATOS CLÍNICOS</strong></th>
							</tr>
						</table>';						
				
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="4%">&nbsp;</td>
							<td width="40%">TIEMPO DE EVOLUCI&Oacute;N DEL PADECIMIENTO:</td>
							<td width="52%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($tiempo));
				$html = $html.'</td>
							<td width="4%">&nbsp;</td>
						  </tr>
						</table>';
				
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="4%">&nbsp;</td>
							<td width="30%">ANTECEDENTES IMPORTANTES:</td>
							<td width="62%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($antecedentes));
				$html = $html.'</td>
							<td width="4%">&nbsp;</td>
						  </tr>
						</table>';	
						
				//Tratamiento			
				
				$html = $html.'<table border="0" width="100%">
							<tr><th>&nbsp;</th></tr>
							<tr>
								<th align="center"><strong>TRATAMIENTO</strong></th>
							</tr>
						</table>';
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td width="4%">&nbsp;</td>
								<td width="23%">MONOTERAPIA (';
							if($mono == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
								<td width="23%">PQT (';
							if($pqt == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
								<td width="23%">OTRO ESQUEMA (';
							if($oe == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
								<td width="23%">SIN TRATAMIENTO (';
							if($st == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
								<td width="4%">&nbsp;</td>
							  </tr>
							</table>';		
						
				//Sitio			
				
				$html = $html.'<table border="0" width="100%">
							<tr><th>&nbsp;</th></tr>
							<tr>
								<th align="center"><strong>SITIO DE TOMA DE MUESTRA</strong></th>
							</tr>
						</table>';	
						
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td width="4%">&nbsp;</td>
								<td width="32%">1.- LÓBULO DE LA OREJA (';
							if($loOr=1) {
								$html = $html.'X';
							}
				$html = $html.')</td>
								<td width="30%">2.- LESION CUTANEA (';
							if($leCu == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
								<td width="30%">3.- Cola de Ceja (';
							if($coCe == 1){
				$html = $html.'X';
							}
				$html = $html.')</td>
								<td width="4%">&nbsp;</td>
							  </tr>
							</table>';
							
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="4%">&nbsp;</td>
							<td width="43%">NOMBRE Y FIRMA DE QUIEN TOMO LA MUESTRA:</td>
							<td width="49%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($PersonaTomaMuestra));
				$html = $html.'</td>
							<td width="4%">&nbsp;</td>
						  </tr>
						</table>';
						
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td width="4%">&nbsp;</td>
								<td width="32%">&nbsp;</td>
								<td width="30%" align="right">FECHA&nbsp;</td>
								<td width="30%" style="border-bottom:#000 solid 2px">';
				$html = $html. $FechaTomaMuestra;
				$html = $html.'</td>
								<td width="4%">&nbsp;</td>
							  </tr>
							</table>';
				
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="4%">&nbsp;</td>
							<td width="45%">NOMBRE Y FIRMA DE QUIEN SOLICITA EL ESTUDIO:</td>
							<td width="47%" style="border-bottom:#000 solid 2px">&nbsp;';
				$html = $html. (utf8_encode($solicitante));
				$html = $html.'</td>
							<td width="4%">&nbsp;</td>
						  </tr>
						</table>';
						
				$html = $html.'<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td width="4%">&nbsp;</td>
								<td width="32%">&nbsp;</td>
								<td width="30%" align="right">FECHA&nbsp;</td>
								<td width="30%" style="border-bottom:#000 solid 2px">';
				$html = $html. $FechaSolicitud;
				$html = $html.'</td>
								<td width="4%">&nbsp;</td>
							  </tr>
							  <tr>
								<td colspan="5">* Estos datos no hacen referencia a los del paciente analizado; Si aparece un * en los datos del caso, significa que este es un analisis para uno de los contactos de un paciente ya confirmado. </td>
							  </tr>
							</table>';																	
												


	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('solicitud_estudio_baciloscopico'. $idEstudioBac .'.pdf', 'I');
?>