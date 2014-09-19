<?php
class IndicadorDiagnosticoOportuno {

	// VALORES DE ENTRADA
	public $idCatEstado;
	public $idCatJurisdiccion;
	public $fechaInicio;
	public $fechaFin;
	// VALORES DE ENTRADA
	
	// VALORES FIJOS
	public $estandar = 100;
	public $ponderacion = 30;
	public $nombre = "Diagn&oacute;stico Oportuno";
	// VALORES FIJOS

	// VALORES CALCULADOS
	public $resultado;
	public $indice;
	public $CasosNuevosSinDiscapacidad;
	public $totalCasosNuevosDiagnosticados;
	// VALORES CALCULADOS
	
	public $error = false;
	public $msgError;

	public function calcular() {

		if (is_null($this->idCatEstado) || is_null($this->idCatJurisdiccion) || is_null($this->fechaInicio) || is_null($this->fechaFin)) {
			$this->error = true;
			$this->msgError = "El indicador requiere del identificador de estado y jurisdiccion, asi como de una fecha de inicio y fin.";
		} else {		
			$sql = "SELECT count(DISTINCT d.idPaciente) AS CasosNuevosSinDiscapacidad " .
				"FROM diagnostico d, pacientes p " .
				"WHERE d.idPaciente = p.idPaciente " .
				"AND d.discOjoIzq = 0 " .
				"AND d.discOjoDer = 0 " .
				"AND d.discManoIzq = 0 " .
				"AND d.discManoDer = 0 " .
				"AND d.discPieIzq = 0 " .
				"AND d.discPieDer = 0 " .
				"AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
				"AND p.idCatEstado = " . $this->idCatEstado . ";";

			if ($this->idCatJurisdiccion != 0)
				$sql = "SELECT count(DISTINCT d.idPaciente) AS CasosNuevosSinDiscapacidad " .
					"FROM diagnostico d, pacientes p, catMunicipio m  " .
					"WHERE d.idPaciente = p.idPaciente " .
					"AND m.idCatEstado = p.idCatEstado " .
					"AND p.idCatMunicipio = m.idCatMunicipio " .
					"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
					"AND d.discOjoIzq = 0 " .
					"AND d.discOjoDer = 0 " .
					"AND d.discManoIzq = 0 " .
					"AND d.discManoDer = 0 " .
					"AND d.discPieIzq = 0 " .
					"AND d.discPieDer = 0 " .
					"AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					"AND p.idCatEstado = " . $this->idCatEstado . ";";
			
			
			$consulta = ejecutaQueryClases($sql);
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$tabla = devuelveRowAssoc($consulta);
				$this->CasosNuevosSinDiscapacidad = $tabla["CasosNuevosSinDiscapacidad"];

				$sql = "SELECT count(DISTINCT d.idPaciente) AS totalCasosNuevosDiagnosticados " .
					"FROM diagnostico d, pacientes p " .
					"WHERE d.idPaciente = p.idPaciente " .
					"AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					"AND p.idCatEstado = " . $this->idCatEstado . ";";

				if ($this->idCatJurisdiccion != 0)
					$sql = "SELECT count(DISTINCT d.idPaciente) AS totalCasosNuevosDiagnosticados " .
						"FROM diagnostico d, pacientes p, catMunicipio m  " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND m.idCatEstado = p.idCatEstado " .
						"AND p.idCatMunicipio = m.idCatMunicipio " .
						"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
						"AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND p.idCatEstado = " . $this->idCatEstado . ";";

				$consulta = ejecutaQueryClases($sql);				
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql;
				} else {
					$tabla = devuelveRowAssoc($consulta);
					$this->totalCasosNuevosDiagnosticados = $tabla["totalCasosNuevosDiagnosticados"];
					if ($this->totalCasosNuevosDiagnosticados != 0) { 
						$this->resultado = ($this->CasosNuevosSinDiscapacidad / $this->totalCasosNuevosDiagnosticados) * 100;
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
			if ($this->idCatJurisdiccion != 0) $jurisdiccion = "Jurisdicci�n #" . $this->idCatJurisdiccion . " " . $tabla["jurisdiccion"];
		}

		echo '<DIV CLASS="datagrid"><TABLE><THEAD><TR><TH COLSPAN="5">' . $estado . "<BR>" . $jurisdiccion . '</TH></TR>' .
			'<TR><TH>Indicador</TH><TH>Est&aacute;ndar</TH><TH>Resultado</TH><TH>Ponderaci&oacute;n</TH><TH>&Iacute;ndice</TH></TR></THEAD>' .
			'<TR><TD>' . $this->nombre .
			'</TD><TD>' . $this->estandar . "%" .
			'</TD><TD>' . $this->resultado .
			'</TD><TD>' . $this->ponderacion .
			'</TD><TD>' . $this->indice .
			'</TD></TR></TABLE></DIV>';
	}
}
?>