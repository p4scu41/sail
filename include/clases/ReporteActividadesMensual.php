<?php

class ReporteActividadesMensual {
	
	// VARIABLES DE ENTRADA **********************************************************************************
	public $idCatEstado;
	public $idCatJurisdiccionLaboratorio;
	public $fechaInicio;
	public $fechaFin;

	// BD ******************************************************************************************
	static $idCatTipoEstudioDia = 1;				// TABLA catTipoEstudio
	static $idCatTipoEstudioCon = 2;
	static $idCatHistoLL = 1;						// TABLA catHistopatologia
	static $idCatHistoLT = 2;
	static $idCatHistoLD = 3;
	static $idCatHistoLI = 4;
	static $idCatHistoOtr = 5;
	static $idCatHistoNeg = 6;
	// BD ******************************************************************************************
	
	// bac = baciloscopia		/		his = Histopatologia
	// Dia = Diagnostico		/		Con = Control
	// Pos = Positivas			/		Neg = Negativas			/		Pen = Pendientes 
	// CAc = Con Actividad		/		SAc = Sin Actividad
	// VARIABLES DEL REPORTE ******************************************************************************************
	public $arrNuevosPacientes = array();
	public $totalMuestras;
	public $muestrasAdecuadas;
	public $bacDiaPos;
	public $bacDiaNeg;
	public $bacDiaPen;
	public $bacConPos;
	public $bacConNeg;
	public $bacConPen;
	public $hisDiaPen;
	public $hisDiaLL;
	public $hisDiaLT;
	public $hisDiaLD;
	public $hisDiaLI;
	public $hisDiaOtr;
	public $hisDiaNeg;
	public $hisConPen;
	public $hisConCAc;
	public $hisConSAc;
	// VARIABLES DEL REPORTE ******************************************************************************************
	public $error = false;
	public $msgError;

	public function imprimirReporte() {

		echo '<BR><strong>1. Total de muestras tomadas para diagn&oacute;stico:</strong> ' . $this->totalMuestras;
		echo '<BR> &nbsp; &nbsp; &nbsp;<strong>No de Muestras adecuadas:</strong> ' . $this->muestrasAdecuadas;
		
		echo '<BR><BR><strong>2. Baciloscop&iacute;as:</strong>';
        
		echo '<div class="datagrid"><TABLE><THEAD><TR align="center"><TH> </TH><TH>Positivas</TH><TH>Negativas</TH><TH>Pendientes</TH><TH>Total</TH></TR></THEAD>';
		echo '<TR align="center"><TD>Diagn&oacute;stico</TD><TD>'.$this->bacDiaPos.'</TD><TD>'.$this->bacDiaNeg.'</TD><TD>'.$this->bacDiaPen.'</TD><TD>'. ($this->bacDiaPos + $this->bacDiaNeg + $this->bacDiaPen) .'</TD></TR>';
		echo '<TR align="center"><TD>Control</TD><TD>'.$this->bacConPos.'</TD><TD>'.$this->bacConNeg.'</TD><TD>'.$this->bacConPen.'</TD><TD>'. ($this->bacConPos + $this->bacConNeg + $this->bacConPen) .'</TD></TR></TABLE></div>';
		
		echo '<BR><BR><strong>3. Histopatolog&iacute;a:</strong>';
        
		echo '<div class="datagrid"><TABLE><THEAD><TR align="center"><TH> </TH><TH>LL</TH><TH>LT</TH><TH>LD</TH><TH>LI</TH><TH>Otros</TH><TH>Pendientes</TH><TH>Total</TH></TR></THEAD>';
		echo '<TR align="center"><TD>Diagn&oacute;stico</TD><TD>'.$this->hisDiaLL.'</TD><TD>'.$this->hisDiaLT.'</TD><TD>'.$this->hisDiaLD.'</TD><TD>'.$this->hisDiaLI.'</TD><TD>'.$this->hisDiaOtr.'</TD><TD>'.$this->hisDiaPen.'</TD><TD>'.($this->hisDiaLL + $this->hisDiaLT + $this->hisDiaLD + $this->hisDiaLI + $this->hisDiaOtr + $this->hisDiaPen).'</TD></TR></TABLE></div>';
        
		echo '<BR ><div class="datagrid"><TABLE><THEAD><TR align="center"><TH> </TH><TH>Con Actividad</TH><TH>Sin Actividad</TH><TH>Pendientes</TH><TH>Total</TH></TR></THEAD>';
		echo '<TR align="center"><TD>Control</TD><TD>'.$this->hisConCAc.'</TD><TD>'.$this->hisConSAc.'</TD><TD>'.$this->hisConPen.'</TD><TD>'. ($this->hisConCAc + $this->hisConSAc + $this->hisConPen) .'</TD></TR></TABLE></div>';

		echo '<BR><BR><strong>4. Relaci&oacute;n de casos nuevos confirmados:</strong>';
        
		echo '<div class="datagrid"><TABLE><THEAD><TR align="center"><TH>Folio Lab</TH><TH>Nombre</TH><TH>Edad</TH><TH>Sexo</TH><TH>Domicilio</TH><TH>Fecha de Diagn&oacute;stico</TH><TH>Baciloscop&iacute;a</TH><TH>Histopatolog&iacute;a</TH><TH>Unidad M&eacute;dica</TH></TR></THEAD>';

		$longitud = count($this->arrNuevosPacientes);
		for ($i = 0; $i < $longitud; $i++) {
			$objTemp = $this->arrNuevosPacientes[$i];
			echo '<TR><TD>'.$objTemp->folioLaboratorio.'</TD><TD>'.$objTemp->nombreCompleto.'</TD><TD>'.$objTemp->edad.'</TD><TD>'.$objTemp->sexo.'</TD><TD>'.$objTemp->domicilio.'</TD><TD>'.$objTemp->fechaDiagnostico.'</TD><TD align="center">'.$objTemp->Baciloscopia.'</TD><TD align="center">'.$objTemp->histopatologia.'</TD><TD>'.$objTemp->localizacionUnidadMedica.'</TD></TR>';
		}		
		echo '</TABLE></div>';
		
	}
	
	public function generarReporte() {
		if (is_null($this->idCatEstado) || is_null($this->idCatJurisdiccionLaboratorio) || is_null($this->fechaFin) || is_null($this->fechaInicio)) {
			$this->error = true;
			$this->msgError = "El reporte requiere del identificador de estado, de jurisdiccion y la fecha de inicio y fin del reporte.";
		} else {
			$fIni = formatFechaObj($this->fechaInicio, 'Y-m-d');
			$fFin = formatFechaObj($this->fechaFin, 'Y-m-d');
			
			if ($this->idCatJurisdiccionLaboratorio == 0) {
				$sql = "SELECT " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " ) AS totBac, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . ") AS estBacDiaPen, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . ") AS estBacConPen, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND bacIM IS NOT NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS estBacDiaPos, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND bacIM IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS estBacDiaNeg, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND bacIM IS NOT NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . " ) AS estBacConPos, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND bacIM IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . " ) AS estBacConNeg, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " ) AS totHis, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . ") AS estHisDiaPen, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . ") AS estHisConPen, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoLL . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaLL, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoLT . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaLT, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoLD . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaLD, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoLI . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaLI, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoOtr . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaOtr, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoNeg . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaNeg, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND idCatHisto != " . self::$idCatHistoNeg . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . " ) AS esthisConPos, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoNeg . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . " ) AS esthisConNeg";
			} else {
				$sql = "SELECT " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . ") AS totBac, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . ") AS estBacDiaPen, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . ") AS estBacConPen, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND bacIM IS NOT NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS estBacDiaPos, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND bacIM IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS estBacDiaNeg, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND bacIM IS NOT NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . " ) AS estBacConPos, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosBac b WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND bacIM IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . " ) AS estBacConNeg, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . ") AS totHis, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . ") AS estHisDiaPen, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NULL AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . ") AS estHisConPen, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoLL . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaLL, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoLT . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaLT, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoLD . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaLD, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoLI . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaLI, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoOtr . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaOtr, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoNeg . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " ) AS esthisDiaNeg, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND idCatHisto != " . self::$idCatHistoNeg . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . " ) AS esthisConPos, " .
				"(SELECT COUNT(idDiagnostico) FROM estudiosHis h WHERE fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' AND idCatEstadoTratante = " . $this->idCatEstado . " AND idCatJurisdiccionTratante = " . $this->idCatJurisdiccionLaboratorio . " AND fechaResultado IS NOT NULL AND idCatHisto = " . self::$idCatHistoNeg . " AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon . " ) AS esthisConNeg";				
			}			

			$consulta = ejecutaQueryClases($sql);
			//echo $sql.'<br><br>';
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
			} else {		
				$tabla = devuelveRowAssoc($consulta);
				$this->bacDiaPos = $tabla["estBacDiaPos"];
				$this->bacDiaNeg = $tabla["estBacDiaNeg"];
				$this->bacDiaPen = $tabla["estBacDiaPen"];
				$this->bacConPos = $tabla["estBacConPos"];
				$this->bacConNeg = $tabla["estBacConNeg"];
				$this->bacConPen = $tabla["estBacConPen"];
				$this->hisDiaPen = $tabla["estHisDiaPen"];;
				$this->hisDiaLL = $tabla["esthisDiaLL"];
				$this->hisDiaLT = $tabla["esthisDiaLT"];
				$this->hisDiaLD = $tabla["esthisDiaLD"];
				$this->hisDiaLI = $tabla["esthisDiaLI"];
				$this->hisDiaOtr = $tabla["esthisDiaOtr"];
				$this->hisDiaNeg = $tabla["esthisDiaNeg"];
				$this->hisConPen = $tabla["estHisConPen"];
				$this->hisConCAc = $tabla["esthisConPos"];
				$this->hisConSAc = $tabla["esthisConNeg"];
				$totBac = $tabla["totBac"];
				$totHis = $tabla["totHis"];
				$this->totalMuestras = $totBac + $totHis;
				$this->muestrasAdecuadas = $this->totalMuestras - $this->bacDiaPen - $this->bacConPen - $this->hisDiaPen - $this->hisConPen;
				
				if ($this->idCatJurisdiccionLaboratorio == 0) {
					//$sql = "SELECT p.idPaciente, b.idEstudioBac " .
					$sql = "SELECT DISTINCT(p.idPaciente) " .
					"FROM pacientes p, catUnidad u, catMunicipio m, diagnostico d, estudiosBac b  " .
					"WHERE p.idCatUnidadTratante = u.idCatUnidad " .
					"AND p.idPaciente = d.idPaciente AND b.idDiagnostico = d.idDiagnostico " .
					"AND u.idCatMunicipio = m.idCatMunicipio " .
					"AND u.idCatEstado = m.idCatEstado " .
					"AND u.idCatEstado = " . $this->idCatEstado . " " .
					"AND p.fechaNotificacion BETWEEN '" . $fIni . "' AND '" . $fFin . "' " .
					"AND b.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . ";";
				} else {
					//$sql = "SELECT p.idPaciente, b.idEstudioBac " .
					$sql = "SELECT DISTINCT(p.idPaciente) " .
					"FROM pacientes p, catUnidad u, catMunicipio m, diagnostico d, estudiosBac b  " .
					"WHERE p.idCatUnidadTratante = u.idCatUnidad " .
					"AND p.idPaciente = d.idPaciente AND b.idDiagnostico = d.idDiagnostico " .
					"AND u.idCatMunicipio = m.idCatMunicipio " .
					"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccionLaboratorio . " " .
					"AND u.idCatEstado = m.idCatEstado " .
					"AND u.idCatEstado = " . $this->idCatEstado . " " .
					"AND p.fechaNotificacion BETWEEN '" . $fIni . "' AND '" . $fFin . "' " .
					"AND b.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . ";";
				}
				
					
				$consulta = ejecutaQueryClases($sql);
				//echo $sql.'<br><br>';
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
				} else {
					while ($tabla = devuelveRowAssoc($consulta)) {
						$idPacTemp = $tabla["idPaciente"];
						//$idEstTemp = $tabla["idEstudioBac"];
						$objTemp = new NuevosPacientes();
						$objTemp->obtenerBD($idPacTemp, $fIni, $fFin, self::$idCatTipoEstudioDia, $this->idCatEstado, $this->idCatJurisdiccionLaboratorio);
						array_push($this->arrNuevosPacientes, $objTemp);
					}
				}
			}
		}				
	}
}

class NuevosPacientes {

	public $idPaciente;							// idPaciente de la BD
	public $idDiagnostico;						// idDiagnostico de la BD
	// VARIABLES DEL REPORTE ******************************************************************************************	
	public $folioLaboratorio = 0;
	public $nombreCompleto;
	public $edad;
	public $sexo;
	public $domicilio;
	public $fechaDiagnostico;
	public $Baciloscopia;
	public $histopatologia;
	public $localizacionUnidadMedica;
	// VARIABLES DEL REPORTE ******************************************************************************************	
	public $error = false;
	public $msgError;

	public function obtenerBD($idPaciente, $fIni, $fFin, $idCatTipoEstudioDia, $idCatEstado, $idCatJurisdiccionLaboratorio) {

		$sql = "SELECT p.idPaciente, d.idDiagnostico, p.nombre, p.apellidoPaterno, p.apellidoMaterno, s.sexo, p.fechaNacimiento, 
					b.folioLaboratorio, p.calle, p.noExterior, p.noInterior, p.colonia, l.nombre AS localidad, p.fechaDiagnostico, cb.descripcion as bac, b.bacIM, 
					ll.nombre AS LocalidadUnidad " .
			"FROM pacientes p, diagnostico d, estudiosBac b, catLocalidad l, catUnidad u, catLocalidad ll, catSexo s, catBaciloscopia cb " .
			"WHERE p.idPaciente = d.idPaciente " .
            "AND p.sexo = s.idSexo " .
            "AND cb.idCatBaciloscopia = b.idCatBac " .
			"AND l.idCatLocalidad = p.idCatLocalidad " .
			"AND l.idCatEstado = p.idCatEstado " .
			"AND l.idCatMunicipio = p.idCatMunicipio " .
			"AND u.idCatUnidad = p.idCatUnidadNotificante " .
			"AND u.idCatLocalidad = ll.idCatLocalidad " .
			"AND u.idCatEstado = ll.idCatEstado " .
			"AND u.idCatMunicipio = ll.idCatMunicipio " .
			"AND b.idDiagnostico = d.idDiagnostico " .
			"AND b.fechaSolicitud BETWEEN '" . $fIni . "' AND '" . $fFin . "' " .
			"AND b.idCatTipoEstudio = " . $idCatTipoEstudioDia . " " .
			"AND b.bacIM IS NOT NULL " .
			"AND b.idCatEstadoLaboratorio = " . $idCatEstado . " " .
			"AND p.idPaciente = " . $idPaciente . " ";
			//"AND b.idCatJurisdiccionLaboratorio = " . $idCatJurisdiccionLaboratorio . ";";
		
		
		$consulta = ejecutaQueryClases($sql);
		//echo $sql.'<br><br>';
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
		} else {		
			$tabla = devuelveRowAssoc($consulta);
			$this->idPaciente = $tabla["idPaciente"];
			$this->idDiagnostico = $tabla["idDiagnostico"];
			
			$this->folioLaboratorio = $tabla["folioLaboratorio"];
			$this->nombreCompleto = $tabla["nombre"] . " " . $tabla["apellidoPaterno"] . " " . $tabla["apellidoMaterno"];
			$this->edad = calEdad(formatFechaObj($tabla["fechaNacimiento"], 'Y-m-d'));
			$this->sexo = $tabla["sexo"];
			$this->domicilio = $tabla["calle"] . " " . $tabla["noExterior"] . " " . $tabla["noInterior"] . " " . $tabla["colonia"] . " " . $tabla["localidad"];
			$this->fechaDiagnostico = formatFechaObj($tabla["fechaDiagnostico"]);
            $this->Baciloscopia = 'IB: ' . $tabla["bac"] . ' <br>IM: ' . $tabla['bacIM'] . '%' ;
            
            
			$this->localizacionUnidadMedica = $tabla["LocalidadUnidad"];
						
			$sql = "SELECT TOP 1 h.idCatHisto, ch.descripcion FROM estudiosHis h, catHistopatologia ch, diagnostico d WHERE h.idCatHisto = ch.idCatHisto " .
				"AND d.idPaciente = " . $idPaciente . " AND h.idCatTipoEstudio = " . $idCatTipoEstudioDia . " AND d.idDiagnostico = h.idDiagnostico ORDER BY h.fechaSolicitud desc;";
			$consulta = ejecutaQueryClases($sql);
			//echo $sql.'<br><br>';
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
			} else {
				$tabla = devuelveRowAssoc($consulta);
				$this->histopatologia = $tabla["descripcion"];
			}
		}
	}
}


?>
