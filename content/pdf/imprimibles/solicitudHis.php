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
				<th align="center"><h2>SOLICITUD DE ESTUDIO HISTOPATOLÓGICO PARA LEPRA</h2></th>
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

	// BD //////////////////////////////////////
	$idTipoEstudioControl = 2;
	$idCatTipoll = 1;	$idCatTipolt = 3;	$idCatTipold = 2;	$idCatTipoli = 4;	$idCatTipolc = 5;		// idCatTipoLepra
	/* idCatClasificacionLepra	1	Lepromatosa (MB)	2	Dimorfa (MB)	3	Tuberculoide (PB)	4	Indeterminado (PB)	5	No Especificado */
	$idCatTopLoc = 1;	$idCatTopGen = 2;	$idCatTopDis = 3;
	/*idCatTopografia	1	Localizada	2	Diseminada	3	Generalizada*/
	$idCatTratMT = 1;	$idCatTratPQ = 2;	$idCatTratOE = 3;	$idCatTratST = 4;
	/* idCatTratamiento	1	Monoterapia		2	PQT		3	Otro Esquema	4	Sin Tratamiento */
	$catLesAis = 1;	$catLesAgr = 2;	$catLesHip = 3;	$catLesEri = 4;	$catLesInf = 5;	$catLesAne = 6;	$catLesNud = 7;
	/* idCatTipoLesionDiagrama	1	Nodulos Aislados	2	Nodulos Agrupados	3	Manchas Hipopigmentadas		4	Manchas Eritematosas		5	Placas Infiltradas		6	Zonas de Anestesia		7	Nudosidades y Otras */
	// BD //////////////////////////////////////

	$idEstudioHis = $_GET["idEstudioHis"];
	$connectionBD = conectaBD();
	$help = new helpers();
	$estudioHis = new EstudioHis();
	$estudioHis->obtenerBD($idEstudioHis);

	$fem = '';			$mas = '';
	$tipoLepraLL = '';	$tipoLepraLT = '';	$tipoLepraLI = '';	$tipoLepraLD = '';	$tipoLepraSC = '';
	$tipoDia = 'X';		$tipoCon = '';		$topLoc = '';		$topGen = '';		$topDis = '';
	$saC = '';			$saT = '';			$saSI = '';			$saSD = '';			$saII = '';			$saID = '';
	$nodAis = '';		$nodAgr = '';		$manHip = '';		$manEri = '';	
	$plaInf = '';		$zonAne = '';		$nudOtr	 = '';
	$mon = '';			$pqt = '';			$otrEsq = '';		$sinTrat = '';
	$iniMon = '';		$iniPQT = '';		$iniOtr = '';
	$terMon = '';		$terPQT = '';		$terOtr = '';
	
	$fechaSol = formatFechaObj($estudioHis->fechaSolicitud, 'd-m-Y');
	$fechaRec = formatFechaObj($estudioHis->fechaRecepcion, 'd-m-Y');
	$lesTomMue = $estudioHis->lesionTomoMuestra;
	$regTomMue = $estudioHis->regionTomoMuestra;
	$fecTomMue = formatFechaObj($estudioHis->fechaTomaMuestra, 'd-m-Y');
	$perSolEst = $estudioHis->personaSolicitudEstudio;


	if ( is_null($estudioHis->idContacto) && $estudioHis->idDiagnostico == 0) {			// SOSPECHOSOS
		
		$sospechoso = new Sospechoso($estudioHis->idPaciente);
		
		$nombre = $help->getNamePaciente($estudioHis->idPaciente);
		$expediente = $help->getClavePaciente($estudioHis->idPaciente);
		$sexo = $help->getSexoPaciente($estudioHis->idPaciente);
		$edad = $help->getEdadPaciente($estudioHis->idPaciente);		
		$arrUbic = $help->getArrDomicilioMunicipioEstado($estudioHis->idPaciente);
		$domicilio = $arrUbic["domicilio"];
		$municipio = $arrUbic["municipio"];
		$estado = $arrUbic["estado"];
		$localidad = $arrUbic["localidad"];		
		$tiempo = "No Confirmado";
		$antecedentes = "";
		$noLesiones = $sospechoso->idCatNumeroLesiones;
		$descripcionComp = $sospechoso->descripcionTopografica;
		$fechaUltBac = $help->getUltimaBaciloscopia($estudioHis->idEstudioHis);
		$observaciones = "";
	
		if ($sospechoso->idCatTopografia == $idCatTopLoc) $topLoc = 'X';
		if ($sospechoso->idCatTopografia == $idCatTopGen) $topGen = 'X';
		if ($sospechoso->idCatTopografia == $idCatTopDis) $topDis = 'X';

		if ($sospechoso->segAfeCab == 1)	$saC = 'X';
		if ($sospechoso->segAfeTro == 1)	$saT = 'X';
		if ($sospechoso->segAfeMSD == 1)	$saSD = 'X';
		if ($sospechoso->segAfeMSI == 1)	$saSI = 'X';
		if ($sospechoso->segAfeMID == 1)	$saID = 'X';
		if ($sospechoso->segAfeMII == 1)	$saII = 'X';				

		$arrLes = $help->getArrayLesionesDiagramaSospechoso($estudioHis->idPaciente);
		foreach($arrLes as $les) {
			if ($les == $catLesAis) $nodAis = 'X';
			if ($les == $catLesAgr) $nodAgr = 'X';
			if ($les == $catLesHip) $manHip = 'X';
			if ($les == $catLesEri) $manEri = 'X';
			if ($les == $catLesInf) $plaInf = 'X';
			if ($les == $catLesAne) $zonAne = 'X';
			if ($les == $catLesNud) $nudOtr	 = 'X';
		}

	} elseif (is_null($estudioHis->idContacto) && is_null($estudioHis->idPaciente)) {		// CONFIRMANDO

		$diagnostico = new Diagnostico();
		$diagnostico->obtenerBD($estudioHis->idDiagnostico);
		$nombre = $help->getNamePaciente($diagnostico->idPaciente);
		$expediente = $help->getClavePaciente($diagnostico->idPaciente);
		$sexo = $help->getSexoPaciente($diagnostico->idPaciente);
		$edad = $help->getEdadPaciente($diagnostico->idPaciente);		
		$arrUbic = $help->getArrDomicilioMunicipioEstado($diagnostico->idPaciente);
		$domicilio = $arrUbic["domicilio"];
		$municipio = $arrUbic["municipio"];
		$estado = $arrUbic["estado"];
		$localidad = $arrUbic["localidad"];
		$tiempo =  $help->getTiempoDeTratamiento($diagnostico->idPaciente, $estudioHis->fechaSolicitud);
		$antecedentes = $diagnostico->otrosPadecimientos;
		$noLesiones = $diagnostico->idCatNumeroLesiones;
		$descripcionComp = $diagnostico->descripcionTopografica;
		$fechaUltBac = $help->getUltimaBaciloscopia($estudioHis->idEstudioHis);
		$observaciones = $diagnostico->observaciones;
		
		if ($diagnostico->idCatClasificacionLepra == $idCatTipoll) $tipoLepraLL = 'X';
		if ($diagnostico->idCatClasificacionLepra == $idCatTipolt) $tipoLepraLT = 'X';
		if ($diagnostico->idCatClasificacionLepra == $idCatTipold) $tipoLepraLI = 'X';
		if ($diagnostico->idCatClasificacionLepra == $idCatTipoli) $tipoLepraLD = 'X';
		if ($diagnostico->idCatClasificacionLepra == $idCatTipolc) $tipoLepraSC = 'X';

		if ($diagnostico->idCatTopografia == $idCatTopLoc) $topLoc = 'X';
		if ($diagnostico->idCatTopografia == $idCatTopGen) $topGen = 'X';
		if ($diagnostico->idCatTopografia == $idCatTopDis) $topDis = 'X';

		if ($diagnostico->segAfeCab == 1)	$saC = 'X';
		if ($diagnostico->segAfeTro == 1)	$saT = 'X';
		if ($diagnostico->segAfeMSD == 1)	$saSD = 'X';
		if ($diagnostico->segAfeMSI == 1)	$saSI = 'X';
		if ($diagnostico->segAfeMID == 1)	$saID = 'X';
		if ($diagnostico->segAfeMII == 1)	$saII = 'X';		

		if ($diagnostico->idCatTratamiento == $idCatTratMT)	{ $man = 'X'; $iniMon = $help->getFechaInicioTratamiento($diagnostico->idPaciente);  }
		if ($diagnostico->idCatTratamiento == $idCatTratPQ) { $pqt = 'X'; $iniPQT = $help->getFechaInicioTratamiento($diagnostico->idPaciente);  }
		if ($diagnostico->idCatTratamiento == $idCatTratOE)	{ $otrEsq = 'X'; $iniOtr = $help->getFechaInicioTratamiento($diagnostico->idPaciente); }
		if ($diagnostico->idCatTratamiento == $idCatTratST)	$sinTrat = 'X';

		$arrLes = $help->getArrayLesionesDiagramaDiagnosticado($estudioHis->idDiagnostico);
		foreach($arrLes as $les) {
			if ($les == $catLesAis) $nodAis = 'X';
			if ($les == $catLesAgr) $nodAgr = 'X';
			if ($les == $catLesHip) $manHip = 'X';
			if ($les == $catLesEri) $manEri = 'X';
			if ($les == $catLesInf) $plaInf = 'X';
			if ($les == $catLesAne) $zonAne = 'X';
			if ($les == $catLesNud) $nudOtr	 = 'X';
		}
		
	} else {
		$contacto = new Contacto();
		$contacto->obtenerBD($estudioHis->idContacto);
		$diagnostico = new Diagnostico();
		$diagnostico->obtenerBD($estudioHis->idDiagnostico);

		//var_dump($contacto);
		$nombre = $contacto->nombre;
		$expediente = "*" . $help->getClavePaciente($diagnostico->idPaciente);
		$edad = $contacto->edad;
		$sexo = $contacto->sexo;
		$arrUbic = $help->getArrDomicilioMunicipioEstado($diagnostico->idPaciente);
		$domicilio = "*" . $arrUbic["domicilio"];
		$municipio = "*" . $arrUbic["municipio"];
		$estado = "*" . $arrUbic["estado"];
		$localidad = "*" . $arrUbic["localidad"];		
		$tiempo = "*" . $help->getTiempoDeTratamiento($diagnostico->idPaciente, $estudioHis->fechaSolicitud);
		$antecedentes = '';
		$noLesiones = '';
		$descripcionComp = '';
		$observaciones = "";

		$fechaUltBac = $help->getUltimaBaciloscopia($estudioHis->idEstudioHis);
	}
	if (($sexo == 1) || ($sexo == "Masculino"))	$mas = "X"; else $fem = "X";
	if ($estudioHis->idCatTipoEstudio == $idTipoEstudioControl) { $tipoCon = "X"; $tipoDia = ""; }
	$connectionBD = closeConexion();

	$pdf->SetFont('helvetica', '', 9);
	$pdf->AddPage('P', 'A4');
	ob_end_clean();

	$html = "
		<table width='100' border='1' >
			<tr>
				<td align='center'><b>DATOS DEL CASO</b></td>
				<td align='center'><b>DATOS DEL LABORATORIO</b></td>
			</tr>
			<tr>
				<td>NOMBRE: <U>" . $nombre . "</U>_______</td>
				<td>EXPEDIENTE: <U>" . $expediente . "</U>_______</td>
			</tr>
			<tr>
				<td>EDAD: <U>" . $edad . "</U>_______</td>
				<td></td>
			</tr>
			<tr>
				<td>SEXO: F ( " . $fem. ")  M ( " . $mas. ")</td>
				<td></td>
			</tr>
			<tr>
				<td>DOMICILIO: <U>" . $domicilio . "</U>_______</td>
				<td></td>
			</tr>
			<tr>
				<td>LOCALIDAD: <U>" . $localidad . "</U>_______</td>
				<td></td>
			</tr>
			<tr>
				<td>MUNICIPIO: <U>" . $municipio . "</U>_______</td>
				<td>FECHA DE SOLICITUD: <U>" . $fechaSol . "</U>_______</td>
			</tr>
			<tr>
				<td>ESTADO: <U>" . $estado . "</U>_______</td>
				<td>FECHA DE RECEPCI&Oacute;N: <U>" . $fechaRec . "</U>_______</td>
			</tr>
		</table> 
		<br /><br />
		<table width='100' border='1' >
			<tr>
				<td align='center'><b>DIAGN&Oacute;STICO CL&Iacute;NICO</b></td>
			</tr>
		</table>
		<table width='100' border='1' >
			<tr>
				<td>LEPRA LEPROMATOSA:  ( ". $tipoLepraLL .")</td>
				<td>LEPRA TUBERCULOIDE: ( ". $tipoLepraLT .")</td>
				<td>ESTUDIO PARA:</td>
			</tr>
			<tr>
				<td>LEPRA INDETERMINADA:( ". $tipoLepraLI .")</td>
				<td>LEPRA DIMORFA:      ( ". $tipoLepraLD .")</td>
				<td>DIAGN&Oacute;STICO( ". $tipoDia .")</td>
			</tr>
			<tr>
				<td>LEPRA SIN CLASIFICAR:( ". $tipoLepraSC .")</td>
				<td>&nbsp;</td>
				<td>CONTROL:             ( ". $tipoCon .")</td>
			</tr>
		</table>
		<br /><br />
		<table width='100' border='1' >
			<tr>
				<td align='center'><b>DATOS CLIN&Iacute;COS</b></td>
			</tr>
			<tr>
				<td>TIEMPO DE EVOLUCI&Oacute;N DEL PADECIMIENTO: <U>" . $tiempo . "</U>_______</td>
			</tr>
			<tr>
				<td>ANTECEDENTES IMPORTANTES: <U>" . $antecedentes . "</U>_______</td>
			</tr>
			<tr>
				<td><br /><br /><b>TOPOGRAF&Iacute;A</b></td>
			</tr>
			<tr>
				<td> Localizada ( ". $topLoc .") &nbsp; Diseminada ( ". $topDis .") &nbsp; Generalizada ( ". $topGen .") &nbsp; N&uacute;mero de lesiones ( ". $noLesiones .")</td>
			</tr>
			<tr>
				<td><br /><br /><b>SEGMENTOS AFECTADOS</b></td>
			</tr>
			<tr>
				<td>Cabeza ( ". $saC .") &nbsp; Tronco ( ". $saT .") &nbsp; Miembros: Superiores I( ". $saSI .") D( ". $saSD .") &nbsp; Inferiores I ( ". $saII .") D( ". $saID .")</td>
			</tr>
			<tr>
				<td><br /><br /><b>MORFOLOG&Iacute;A DE LAS LESIONES</b></td>
			</tr>
			<tr>
				<td> Nodulos Aislados ( ". $nodAis .") &nbsp; Nodulos Agrupados ( ". $nodAgr .") &nbsp; Manchas Hipopigmentadas ( ". $manHip .") &nbsp; Manchas Eritematosas ( ". $manEri .") &nbsp; </td>
			</tr>
			<tr>
				<td> Placas Infiltradas ( ". $plaInf .") &nbsp; Zonas de Anestesia ( ". $zonAne .") &nbsp; Nudosidades y Otras ( ". $nudOtr .") &nbsp; </td>
			</tr>
			<tr>
				<td><br /><br />DESCRIPCI&Oacute;N COMPLEMENTARIA: <U>" . $descripcionComp . "</U>_______</td>
			</tr>
			<tr>
				<td><br /><br />FECHA Y RESULTADO DE LA &Uacute;LTIMA BACILOSCOPIA: <U>" . $fechaUltBac . "</U>_______</td>
			</tr>
		</table>
		<br /><br />
		<table width='100' border='1' >
			<tr>
				<td align='center'><b>TRATAMIENTO</b></td>
			</tr>
		</table>
		<table width='100' border='1' >
			<tr>						
				<td>MONOTERAPIA: ( ". $mon .")</td>
				<td>Inicio <U>" . $iniMon . "</U>_____</td>        
				<td>T&eacute;rmino <U>" . $terMon . "</U>_____</td>        
			</tr>
			<tr>
				<td>PQT: (  ". $pqt .")</td>
				<td>Inicio <U>" . $iniPQT . "</U>_____</td>        
				<td>T&eacute;rmino <U>" . $terPQT . "</U>_____</td>        
			</tr>
			<tr>
				<td>OTRO ESQUEMA: ( ". $otrEsq .")</td>
				<td>Inicio <U>" . $iniOtr . "</U>_____</td>        
				<td>T&eacute;rmino <U>" . $terOtr . "</U>_____</td>        
			</tr>
			<tr>
				<td><br>SIN TRATAMIENTO ( ". $sinTrat .")</td>
			</tr>
		</table>
		<br /><br />
		<table width='100' border='1' >
			<tr>
				<td>OBSERVACIONES: <U>" . $observaciones . "</U>_______</td>
			</tr>
			<tr>
				<td><br /></td>
			</tr>
			<tr>
				<td><br /><br /> Lesi&oacute;n de la que se tom&oacute; la muestra de tejido: <U>" . $lesTomMue . "</U>_______</td>
			</tr>
			<tr>
				<td><br /><br /> Regi&oacute;n de donde se tom&oacute; la muestra de tejido: <U>" . $regTomMue . "</U>_______</td>
			</tr>
			<tr>
				<td><br /><br /> Fecha de Toma: <U>" . $fecTomMue . "</U>_______</td>
			</tr>
			<tr>
				<td><br /><br /> Nombre y firma del m&eacute;dico que solicita el estudio: <U>" . $perSolEst . "</U>_______</td>
			</tr>
			<tr>
				<td><BR /> * Estos datos no hacen referencia a los del paciente analizado; Si aparece un * en los datos del caso, significa que este es un analisis para uno de los contactos de un paciente ya confirmado. </td>
		    </tr>
		</table>";
	
	//echo $html;
	$pdf->writeHTML($html, true, false, true, false, '');						
	$pdf->Output('solicitud_estudio_hitopatologico'. $idEstudioHis .'.pdf', 'I');

?>