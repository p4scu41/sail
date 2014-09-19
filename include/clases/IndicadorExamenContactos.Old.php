<?php
class IndicadorExamenContactos {

	// VALORES DE ENTRADA
	public $idCatEstado;
	public $idCatJurisdiccion;
	public $fechaInicio;
	public $fechaFin;
	// VALORES DE ENTRADA
	
	// VALORES FIJOS
	public $estandar = 90;
	public $ponderacion = 20;
	public $nombre = "Exámen de Contactos";
	// VALORES FIJOS

	// VALORES CALCULADOS
	public $resultado;
	public $indice;
	public $numeroContactosExaminados;
	public $totalContactosRegistrados;
	// VALORES CALCULADOS
	
	public $error = false;
	public $msgError;

	// BD ******************************************************************************************
	static $idEstadoPacienteAplicableATotalCasos = "1, 2, 5";	 // TABLA catEstadoPaciente
	// 1	Prevalente sin Tratamiento
	// 2	Prevalente con Tratamiento	
	// 5	Reingreso PQT
	// BD ******************************************************************************************

	public function calcular() {

		if (is_null($this->idCatEstado) || is_null($this->idCatJurisdiccion) || is_null($this->fechaInicio) || is_null($this->fechaFin)) {
			$this->error = true;
			$this->msgError = "El indicador requiere del identificador de estado y jurisdiccion, asi como de una fecha de inicio y fin.";
		} else {		
			$sql = "SELECT COUNT (DISTINCT c.idContacto) AS numeroContactosExaminados " .
				" FROM pacientes p, contactos c, diagnostico d, estudiosBac b, estudiosHis h " .
				" WHERE d.idPaciente = p.idPaciente " .
				" AND c.idDiagnostico = d.idDiagnostico " .
				" AND p.idCatEstado = " . $this->idCatEstado .
				" AND b.idContacto = c.idContacto " .
				" AND b.fechaSolicitud BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
				" AND h.idContacto = c.idContacto " .
				" AND h.fechaSolicitud BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
				" GROUP BY c.idContacto;";

			if ($this->idCatJurisdiccion != 0)
				$sql = "SELECT COUNT (DISTINCT c.idContacto) AS numeroContactosExaminados " .
					" FROM pacientes p, contactos c, diagnostico d, estudiosBac b, estudiosHis h, catMunicipio m " .
					" WHERE d.idPaciente = p.idPaciente " .
					" AND m.idCatEstado = p.idCatEstado " .
					" AND p.idCatMunicipio = m.idCatMunicipio " .
					" AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
					" AND c.idDiagnostico = d.idDiagnostico " .
					" AND p.idCatEstado = " . $this->idCatEstado .
					" AND b.idContacto = c.idContacto " .
					" AND b.fechaSolicitud BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					" AND h.idContacto = c.idContacto " .
					" AND h.fechaSolicitud BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					" GROUP BY c.idContacto;";
			
			$consulta = ejecutaQueryClases($sql);
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$tabla = devuelveRowAssoc($consulta);
				$this->numeroContactosExaminados = $tabla["numeroContactosExaminados"];

				$sql = "SELECT COUNT (DISTINCT c.idContacto) AS totalContactosRegistrados " .
					" FROM pacientes p, contactos c, diagnostico d, control co " .
					" WHERE d.idPaciente = p.idPaciente" .
					" AND p.idCatEstado = " . $this->idCatEstado .
					" AND co.idDiagnostico = d.idDiagnostico" .
					" AND co.idCatEstadoPaciente IN (" . self::$idEstadoPacienteAplicableATotalCasos . ")" .
					" AND co.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					" AND c.idDiagnostico = d.idDiagnostico;";

				if ($this->idCatJurisdiccion != 0)
					$sql = "SELECT COUNT (DISTINCT c.idContacto) AS totalContactosRegistrados " .
					" FROM pacientes p, contactos c, diagnostico d, control co, catMunicipio m  " .
					" WHERE d.idPaciente = p.idPaciente" .
					" AND p.idCatEstado = " . $this->idCatEstado .
					" AND co.idDiagnostico = d.idDiagnostico" .
					" AND p.idCatMunicipio = m.idCatMunicipio" .
					" AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion .
					" AND co.idCatEstadoPaciente IN (" . self::$idEstadoPacienteAplicableATotalCasos . ")" .
					" AND co.fecha BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					" AND c.idDiagnostico = d.idDiagnostico;";

				$consulta = ejecutaQueryClases($sql);				
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql;
				} else {
					$tabla = devuelveRowAssoc($consulta);
					$this->totalContactosRegistrados = $tabla["totalContactosRegistrados"];
					if ($this->totalContactosRegistrados != 0) { 
						$this->resultado = ($this->numeroContactosExaminados / $this->totalContactosRegistrados) * 100;
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