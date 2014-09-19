<?php
class IndicadorCoberturaTx {

	// VALORES DE ENTRADA
	public $idCatEstado;
	public $idCatJurisdiccion;
	public $fechaInicio;
	public $fechaFin;
	// VALORES DE ENTRADA
	
	// VALORES FIJOS
	public $estandar = 95;
	public $ponderacion = 30;
	public $nombre = "Cobertura del Tx";
	// VALORES FIJOS

	// VALORES CALCULADOS
	public $resultado;
	public $indice;
	public $casosPQT;
	public $totalCasos;
	// VALORES CALCULADOS
	
	public $error = false;
	public $msgError;

	// BD ******************************************************************************************
	static $idEstadoPacienteAplicableATotalCasos =	"1, 2, 5, 6, 9";		//"1, 2, 5";	 // TABLA catEstadoPaciente
	// 1	Prevalente sin Tratamiento
	// 2	Prevalente con Tratamiento
	// 5	Reingreso PQT
	static $idTratamientoAplicableAPQT = "1, 2";	 // TABLA catTratamientoPreescrito
	// 1 PQT (PB)
	// 2 PQT (MB)
	// BD ******************************************************************************************

	public function calcular() {

		if (is_null($this->idCatEstado) || is_null($this->idCatJurisdiccion) || is_null($this->fechaInicio) || is_null($this->fechaFin)) {
			$this->error = true;
			$this->msgError = "El indicador requiere del identificador de estado y jurisdiccion, asi como de una fecha de inicio y fin.";
		} else {		
			$sql = "SELECT COUNT(DISTINCT d.idPaciente) as casosPQT " .
				"FROM diagnostico d, pacientes p, control c " .
				"WHERE d.idPaciente = p.idPaciente " .
				"AND d.idDiagnostico = c.idDiagnostico " .
				"AND (c.idCatTratamientoPreescrito IN (" . self::$idTratamientoAplicableAPQT . ")) " . 
				"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
				"AND p.idCatEstado = " . $this->idCatEstado . ";";

			if ($this->idCatJurisdiccion != 0)
				$sql = "SELECT COUNT(DISTINCT d.idPaciente) as casosPQT " .
					"FROM diagnostico d, pacientes p, control c, catMunicipio m " .
					"WHERE d.idPaciente = p.idPaciente " .
					"AND d.idDiagnostico = c.idDiagnostico " .
					"AND (c.idCatTratamientoPreescrito IN (" . self::$idTratamientoAplicableAPQT . ")) " . 
					"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					"AND m.idCatEstado = p.idCatEstado " .
					"AND p.idCatMunicipio = m.idCatMunicipio " .
					"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
					"AND p.idCatEstado = " . $this->idCatEstado . ";";			
			
			if ($this->idCatEstado == 0)
				$sql = "SELECT COUNT(DISTINCT d.idPaciente) as casosPQT " .
				"FROM diagnostico d, pacientes p, control c " .
				"WHERE d.idPaciente = p.idPaciente " .
				"AND d.idDiagnostico = c.idDiagnostico " .
				"AND (c.idCatTratamientoPreescrito IN (" . self::$idTratamientoAplicableAPQT . ")) " . 
				"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "';";

			
			
			$consulta = ejecutaQueryClases($sql);
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$tabla = devuelveRowAssoc($consulta);
				$this->casosPQT = $tabla["casosPQT"];

				$sql = "SELECT COUNT(DISTINCT d.idPaciente) as casosPQT " .
					"FROM diagnostico d, pacientes p, control c " .
					"WHERE d.idPaciente = p.idPaciente " .
					"AND d.idDiagnostico = c.idDiagnostico " .
					"AND (c.idCatEstadoPaciente IN (" . self::$idEstadoPacienteAplicableATotalCasos . ")) " . 
					"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					"AND p.idCatEstado = " . $this->idCatEstado . ";";
					
				if ($this->idCatJurisdiccion != 0)
					$sql = "SELECT COUNT(DISTINCT d.idPaciente) as casosPQT " .
					"FROM diagnostico d, pacientes p, control c, catMunicipio m " .
					"WHERE d.idPaciente = p.idPaciente " .
					"AND d.idDiagnostico = c.idDiagnostico " .
					"AND (c.idCatEstadoPaciente IN (" . self::$idEstadoPacienteAplicableATotalCasos . ")) " . 
					"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					"AND m.idCatEstado = p.idCatEstado " .
					"AND p.idCatMunicipio = m.idCatMunicipio " .
					"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
					"AND p.idCatEstado = " . $this->idCatEstado . ";";			

				if ($this->idCatEstado == 0)
					 $sql = "SELECT COUNT(DISTINCT d.idPaciente) as casosPQT " .
					 "FROM diagnostico d, pacientes p, control c " .
					 "WHERE d.idPaciente = p.idPaciente " .
					 "AND d.idDiagnostico = c.idDiagnostico " .
					 "AND (c.idCatEstadoPaciente IN (" . self::$idEstadoPacienteAplicableATotalCasos . ")) " . 
					 "AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "';";
				
				$consulta = ejecutaQueryClases($sql);
				$sql;
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql;
				} else {
					$tabla = devuelveRowAssoc($consulta);
					$this->totalCasos = $tabla["casosPQT"];
					if ($this->totalCasos != 0) { 
						$this->resultado = ($this->casosPQT / $this->totalCasos) * 100;
						$this->indice = ($this->resultado * $this->ponderacion) / 100;
					} else { 
						$this->resultado = "-";
						$this->indice = "No Aplica";
					}					
				}
			}
		}
	} // Calcular

	function imprimir() {

		$sql = "SELECT e.nombre AS estado, j.nombre AS jurisdiccion FROM catJurisdiccion j, catEstado e WHERE j.idCatEstado = e.idCatEstado AND e.idCatEstado = " .  $this->idCatEstado . " AND j.idCatJurisdiccion = " . $this->idCatJurisdiccion . ";";
		if ($this->idCatJurisdiccion == 0) $sql = "SELECT e.nombre AS estado FROM catEstado e WHERE e.idCatEstado = " .  $this->idCatEstado . ";";
		$jurisdiccion = "";
		$estado = "";

		$consulta = ejecutaQueryClases($sql);
		if (!is_string($consulta)) {
			$tabla = devuelveRowAssoc($consulta);
			$estado = $tabla["estado"];
			$jurisdiccion = "Estatal";
			if ($this->idCatJurisdiccion != 0) $jurisdiccion = "Jurisdicción #" . $this->idCatJurisdiccion . " " . $tabla["jurisdiccion"];
		}

		echo '<DIV CLASS="datagrid"><TABLE><THEAD><TR><TH COLSPAN="5">' . $estado . "<BR>" . $jurisdiccion . '</TH></TR>' .
			'<TR><TH>Indicador</TH><TH>Estándar</TH><TH>Resultado</TH><TH>Ponderacion</TH><TH>Índice</TH></TR></THEAD>' .
			'<TR><TD>' . $this->nombre . 
			'</TD><TD>' . $this->estandar . "%" .
			'</TD><TD>' . $this->resultado .
			'</TD><TD>' . $this->ponderacion .
			'</TD><TD>' . $this->indice .
			'</TD></TR></TABLE></DIV>';
	}
}
?>
