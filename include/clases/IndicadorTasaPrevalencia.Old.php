<?php
class IndicadorTasaPrevalencia {

	// VALORES DE ENTRADA
	public $idCatEstado;
	public $idCatJurisdiccion;
	public $fechaInicio;
	public $fechaFin;
	// VALORES DE ENTRADA
	
	// VALORES FIJOS
	public $estandar = "< 1 caso x cada 10,000 habs.";
	public $ponderacion = 1;
	public $nombre = "Tasa de Prevalencia Puntual";
	// VALORES FIJOS

	// VALORES CALCULADOS
	public $resultado;
	public $indice;
	public $numeroCasosTratamiento;
	public $poblacionTotal;
	// VALORES CALCULADOS
	
	public $error = false;
	public $msgError;

	// BD ******************************************************************************************
	static $idEstadoPacienteAplicableAEnTratamiento = "2, 5, 9";	 // TABLA catEstadoPaciente
	// 2	Prevalente con Tratamiento
	// 5	Reingreso PQT
	// 9	Recaida
	// RELATIVO A LA TABLA catMunicipio (Que contiene a la poblacion)
	static $anoFinPoblacionBD = 2010;
	static $anoInicioPoblacionBD = 2030;
	// BD ******************************************************************************************

	public function calcular() {

		if (is_null($this->idCatEstado) || is_null($this->idCatJurisdiccion) || is_null($this->fechaInicio) || is_null($this->fechaFin)) {
			$this->error = true;
			$this->msgError = "El indicador requiere del identificador de estado y jurisdiccion, asi como de una fecha de inicio y fin.";
		} else {		
			$sql = "SELECT COUNT(DISTINCT d.idPaciente) as numeroCasosTratamiento " .
				"FROM diagnostico d, pacientes p, control c " .
				"WHERE d.idPaciente = p.idPaciente " .
				"AND d.idDiagnostico = c.idDiagnostico " .
				"AND (c.idCatEstadoPaciente IN (" . self::$idEstadoPacienteAplicableAEnTratamiento . ")) " . 
				"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
				"AND p.idCatEstado = " . $this->idCatEstado . ";";
			
			if ($this->idCatJurisdiccion != 0)
				$sql = "SELECT COUNT(DISTINCT d.idPaciente) as numeroCasosTratamiento " .
					"FROM diagnostico d, pacientes p, control c, catMunicipio m " .
					"WHERE d.idPaciente = p.idPaciente " .
					"AND d.idDiagnostico = c.idDiagnostico " .
					"AND (c.idCatEstadoPaciente IN (" . self::$idEstadoPacienteAplicableAEnTratamiento . ")) " . 
					"AND c.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					"AND m.idCatEstado = p.idCatEstado " .
					"AND p.idCatMunicipio = m.idCatMunicipio " .
					"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
					"AND p.idCatEstado = " . $this->idCatEstado . ";";				
			
			$consulta = ejecutaQueryClases($sql);
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$ano = formatFechaObj($this->fechaFin, 'Y');
				if ($ano >= self::$anoInicioPoblacionBD && $ano <= self::$anoFinPoblacionBD) {
					$this->error = true;
					$this->msgError = " La base de datos no tiene informacion poblacional del año que está consultando." . $sql;
				} else {
					$tabla = devuelveRowAssoc($consulta);
					$this->numeroCasosTratamiento = $tabla["numeroCasosTratamiento"];

					$sql = "SELECT SUM (pob" . $ano . ") AS totalPoblacion " .
						" FROM catMunicipio m " .
						" WHERE m.idCatEstado = " . $this->idCatEstado . ";";

					if ($this->idCatJurisdiccion != 0)
						$sql = "SELECT SUM (pob" . $ano . ") AS totalPoblacion " .
						" FROM catMunicipio m " .
						" WHERE m.idCatEstado = " . $this->idCatEstado . 
						" AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . ";";
					
					$consulta = ejecutaQueryClases($sql);
					//echo $sql;
					if (is_string($consulta)) {
						$this->error = true;
						$this->msgError = $consulta . " SQL:" . $sql;
					} else {
						$tabla = devuelveRowAssoc($consulta);
						$this->totalPoblacion = $tabla["totalPoblacion"];
						if ($this->totalPoblacion != 0) { 
							$this->resultado = ($this->numeroCasosTratamiento / $this->totalPoblacion) * 10000;
							$this->indice = ($this->resultado * $this->ponderacion);
						} else { 
							$this->resultado = "-";
							$this->indice = "No Aplica";
						}					
					}
				}				
			}
		}
	} 

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
			'</TD><TD>' . $this->estandar . 
			'</TD><TD>' . $this->resultado .
			'</TD><TD>' . $this->ponderacion .
			'</TD><TD>' . $this->indice .
			'</TD></TR></TABLE></DIV>';
	}
}
?>