<?php

class validacionSIS {

	
	// VALORES DE ENTRADA
	public $idCatEstado;
	public $idCatJurisdiccion;
	public $idCatMunicipio;
	public $idCatUnidad;
	public $fechaInicio;
	public $fechaFin;

	// VALORES CALCULADOS
	public $ingresosControl;
	public $reingresosControl;
	public $casosConTratamiento;
	public $casosSinTratamiento;
	public $casosVigPosTratamiento;
	public $bacDiagnosticoPos;
	public $bacDiagnosticoNeg;
	public $bacDiagnosticoCon;
	public $bacControlPos;
	public $bacControlNeg;
	public $bacControlCon;

	// BD ******************************************************************************************
	// Tabla idCatEstadoPaciente
	static $restriccionMBL02 = "5";			// Reingresos al control
	static $restriccionMBL03 = "2";			// En Tratamiento
	static $restriccionMBL04 = "1";			// Sin Tratamiento
	static $restriccionMBL05 = "3, 6";		// Vigilancia Post Tratamiento
	// Tabla idCatTipoEstudio
	static $estudiosDiagnostico = "1";		// Diagnostico
	static $estudiosControl = "2";			// Control
	// Tabla idCatBaciloscopia
	static $baciloscopiasNegativas = "1";	// ID Baciloscopias Negativas
	// BD ******************************************************************************************

	public $error = false;
	public $msgError;

	// IMPORTANTE: LAS VALIDACIONES APLICAN PARA ESTADO, JURISDICCION, MUNICIPIO Y UNIDAD MEDICA (CUATRO NIVELES DISTINTOS)	
	// CASOS: E, EJ, EJM, EJMU

	public function calcular() {

		if (is_null($this->idCatEstado) || is_null($this->idCatJurisdiccion) || is_null($this->idCatMunicipio) || is_null($this->idCatUnidad) || is_null($this->fechaInicio) || is_null($this->fechaFin)) {
			$this->error = true;
			$this->msgError = "El indicador requiere del identificador de estado y jurisdiccion, asi como de una fecha de inicio y fin.";
		} else {
			$caso = "E";
			if ($this->idCatJurisdiccion != 0) $caso = "EJ";
			elseif ($this->idCatMunicipio != 0) $caso = "EJM";
			elseif ($this->idCatUnidad != 0) $caso = "EJMU";

			switch ($caso) {				
				case "EJ":
					$sql = "SELECT ( " .
						"SELECT COUNT (idPaciente) " .
						"FROM pacientes p, catMunicipio m " .
						"WHERE p.idCatEstado = " . $this->idCatEstado . " " .
						"AND p.fechaInicioPQT BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND p.idCatMunicipio = m.idCatMunicipio ". 
						"AND m.idCatEstado = p.idCatEstado ".
						"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " . 
					") AS ingresosControl, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c, catMunicipio m " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL02 . ") " .
						"AND p.idCatMunicipio = m.idCatMunicipio ". 
						"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " . 
						"AND m.idCatEstado = p.idCatEstado ".
					") AS reingresosControl, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c, catMunicipio m " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL03 . ") " .
						"AND p.idCatMunicipio = m.idCatMunicipio ". 
						"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " . 
						"AND m.idCatEstado = p.idCatEstado ".
					") AS casosConTratamiento, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c, catMunicipio m " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL04 . ") " .
						"AND p.idCatMunicipio = m.idCatMunicipio ". 
						"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " . 
						"AND m.idCatEstado = p.idCatEstado ".
					") AS casosSinTratamiento, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c, catMunicipio m " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL05 . ") " .
						"AND p.idCatMunicipio = m.idCatMunicipio ". 
						"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " . 
						"AND m.idCatEstado = p.idCatEstado ".
					") AS casosVigPosTratamiento;";
					break;
				case "EJM":
					$sql = "SELECT ( " .
						"SELECT COUNT (idPaciente) " .
						"FROM pacientes p, catMunicipio m " .
						"WHERE p.idCatEstado = " . $this->idCatEstado . " " .
						"AND p.fechaInicioPQT BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND p.idCatMunicipio = m.idCatMunicipio " .
						"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " . 
						"AND m.idCatEstado = p.idCatEstado ".
					") AS ingresosControl, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c, catMunicipio m " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL02 . ") " .
						"AND p.idCatMunicipio = m.idCatMunicipio ". 
						"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " . 
						"AND m.idCatEstado = p.idCatEstado ".
					") AS reingresosControl, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c, catMunicipio m " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL03 . ") " .
						"AND p.idCatMunicipio = m.idCatMunicipio ". 
						"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " . 
						"AND m.idCatEstado = p.idCatEstado ".
					") AS casosConTratamiento, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c, catMunicipio m " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL04 . ") " .
						"AND p.idCatMunicipio = m.idCatMunicipio ". 
						"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " . 
						"AND m.idCatEstado = p.idCatEstado ".
					") AS casosSinTratamiento, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c, catMunicipio m " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL05 . ") " .
						"AND p.idCatMunicipio = m.idCatMunicipio ". 
						"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " . 
						"AND m.idCatEstado = p.idCatEstado ".
					") AS casosVigPosTratamiento;";
					break;
				case "EJMU":
					$sql = "SELECT ( " .
						"SELECT COUNT (idPaciente) " .
						"FROM pacientes p " .
						"WHERE p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						"AND p.fechaInicioPQT BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					") AS ingresosControl, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL02 . ") " .
					") AS reingresosControl, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL03 . ") " .
					") AS casosConTratamiento, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL04 . ") " .
					") AS casosSinTratamiento, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL05 . ") " .
					") AS casosVigPosTratamiento;";
					break;
				default:
					$sql = "SELECT ( " .
						"SELECT COUNT (idPaciente) " .
						"FROM pacientes p " .
						"WHERE p.idCatEstado = " . $this->idCatEstado . " " .
						"AND p.fechaInicioPQT BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					") AS ingresosControl, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL02 . ") " .
					") AS reingresosControl, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL03 . ") " .
					") AS casosConTratamiento, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL04 . ") " .
					") AS casosSinTratamiento, ( " .
						"SELECT COUNT (DISTINCT p.idPaciente) " .
						"FROM pacientes p, diagnostico d, control c " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND c.idDiagnostico = d.idDiagnostico " .
						"AND p.idCatEstado = " . $this->idCatEstado . " " .
						"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND c.idCatEstadoPaciente IN (" . self::$restriccionMBL05 . ") " .
					") AS casosVigPosTratamiento;";
					break;
			}

			$consulta = ejecutaQueryClases($sql);
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$tabla = devuelveRowAssoc($consulta);				
				$this->ingresosControl = $tabla["ingresosControl"];
				$this->reingresosControl = $tabla["reingresosControl"];
				$this->casosConTratamiento = $tabla["casosConTratamiento"];
				$this->casosSinTratamiento = $tabla["casosSinTratamiento"];
				$this->casosVigPosTratamiento = $tabla["casosVigPosTratamiento"];

				switch ($caso) {
					case "EJ":
						$sql = "SELECT ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND b.idCatBac NOT IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
						") AS bacDiagnosticoPos, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND b.idCatBac IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
						") AS bacDiagnosticoNeg, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 1 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
						") AS bacDiagnosticoCon, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . " " .
							"AND b.idCatBac NOT IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
						") AS bacControlPos, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . " " .
							"AND b.idCatBac IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
						") AS bacControlNeg, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 1 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . "	" .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
						") AS bacControlCon";
						break;
					case "EJM":
						$sql = "SELECT ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND b.idCatBac NOT IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " .
						") AS bacDiagnosticoPos, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND b.idCatBac IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " .
						") AS bacDiagnosticoNeg, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 1 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " .
						") AS bacDiagnosticoCon, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . " " .
							"AND b.idCatBac NOT IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " .
						") AS bacControlPos, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . " " .
							"AND b.idCatBac IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " .
						") AS bacControlNeg, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b, catMunicipio m  " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 1 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . "	" .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
							"AND p.idCatEstado = m.idCatEstado " .
							"AND p.idCatMunicipio = m.idCatMunicipio " .
							"AND m.idCatMunicipio = " . $this->idCatMunicipio . " " .
						") AS bacControlCon";
						break;
					case "EJMU":
						$sql = "SELECT ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND b.idCatBac NOT IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						") AS bacDiagnosticoPos, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND b.idCatBac IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						") AS bacDiagnosticoNeg, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 1 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						") AS bacDiagnosticoCon, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN'" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . " " .
							"AND b.idCatBac NOT IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						") AS bacControlPos, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . " " .
							"AND b.idCatBac IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						") AS bacControlNeg, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 1 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . "	" .
							"AND p.idCatUnidadTratante = " . $this->idCatUnidad . " " .
						") AS bacControlCon";
						break;
					default:
						$sql = "SELECT ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND b.idCatBac NOT IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
						") AS bacDiagnosticoPos, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND b.idCatBac IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
						") AS bacDiagnosticoNeg, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 1 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosDiagnostico . " " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
						") AS bacDiagnosticoCon, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN'" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . " " .
							"AND b.idCatBac NOT IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
						") AS bacControlPos, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 0 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . " " .
							"AND b.idCatBac IN (" . self::$baciloscopiasNegativas . ") " .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
						") AS bacControlNeg, ( " .
							"SELECT COUNT (b.idEstudioBac) " .
							"FROM pacientes p, diagnostico d, estudiosBac b " .
							"WHERE d.idPaciente = p.idPaciente " .
							"AND b.idDiagnostico = d.idDiagnostico " .
							"AND b.fechaRecepcion BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
							"AND b.muestraRechazada = 1 " .
							"AND b.idCatTipoEstudio = " . self::$estudiosControl . "	" .
							"AND p.idCatEstado = " . $this->idCatEstado . " " .
						") AS bacControlCon";
						break;
				}

				$consulta = ejecutaQueryClases($sql);				
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql;
				} else {
					$tabla = devuelveRowAssoc($consulta);				
					$this->bacDiagnosticoPos = $tabla["bacDiagnosticoPos"];
					$this->bacDiagnosticoNeg = $tabla["bacDiagnosticoNeg"];
					$this->bacDiagnosticoCon = $tabla["bacDiagnosticoCon"];
					$this->bacControlPos = $tabla["bacControlPos"];
					$this->bacControlNeg = $tabla["bacControlNeg"];
					$this->bacControlCon = $tabla["bacControlCon"];
				}
			}
		}
	}

	function imprimir(){

		
		echo '<DIV CLASS="datagrid"><TABLE><THEAD>' .
			'<TR align="center"><TH>Ingresos a Control</TH><TH>Reingresos a <br>Control</TH><TH>Casos Registrados <br>en Tratamiento</TH><TH>Casos Registrados <br>sin Tratamiento</TH><TH>Casos Registrados en <br>Vigilancia post Tratamiento</TH></TR></THEAD>' .
			'<TR align="center"><TD>' . $this->ingresosControl .
			'</TD><TD>' . $this->reingresosControl . 
			'</TD><TD>' . $this->casosConTratamiento .
			'</TD><TD>' . $this->casosSinTratamiento .
			'</TD><TD>' . $this->casosVigPosTratamiento .
			'</TD></TR></TABLE></DIV>';

		echo '<BR><DIV CLASS="datagrid"><TABLE><THEAD><TR align="center"><TH COLSPAN="3">Baciloscopias</TH></TR>' .
			'<TR align="center"><TH>Tipo</TH><TH>Diagn&oacute;stico</TH><TH>Control</TH></TR></THEAD>' .
			'<TR><TD>Positivo</TD><TD align="center">' . $this->bacDiagnosticoPos . '</TD><TD align="center">' . $this->bacControlPos . '</TD></TR>' .
			'<TR><TD>Negativo</TD><TD align="center">' . $this->bacDiagnosticoNeg . '</TD><TD align="center">' . $this->bacControlNeg . '</TD></TR>' .
			'<TR><TD>Rechazadas</TD><TD align="center">' . $this->bacDiagnosticoCon . '</TD><TD align="center">' . $this->bacControlCon . '</TD></TR></TABLE></DIV>';	
	
	}
}

?>