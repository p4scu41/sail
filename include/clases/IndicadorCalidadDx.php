<?php
class IndicadorCalidadDx {

	// VALORES DE ENTRADA
	public $idCatEstado;
	public $idCatJurisdiccion;
	public $fechaInicio;
	public $fechaFin;
	// VALORES DE ENTRADA
	
	// VALORES FIJOS
	public $estandar = 100;
	public $ponderacion = 20;
	public $nombre = "Calidad en el Dx";
	// VALORES FIJOS

	// VALORES CALCULADOS
	public $resultado;
	public $indice;
	public $casosNuevosConBkyHp;
	public $totalCasosNuevos;
	// VALORES CALCULADOS
	
	public $error = false;
	public $msgError;

	// BD ******************************************************************************************
	static $idCatTipoEstudioDia = 1;				// TABLA catTipoEstudio
	static $idCatTipoEstudioCon = 2;
	// BD ******************************************************************************************

	public function calcular() {

		if (is_null($this->idCatEstado) || is_null($this->idCatJurisdiccion) || is_null($this->fechaInicio) || is_null($this->fechaFin)) {
			$this->error = true;
			$this->msgError = "El indicador requiere del identificador de estado y jurisdiccion, asi como de una fecha de inicio y fin.";
		} else {		
			$sql = "SELECT count(DISTINCT d.idPaciente) AS casosNuevosConBkyHp " .
				"FROM diagnostico d, pacientes p, estudiosBac b, estudiosHis h " .
				"WHERE d.idPaciente = p.idPaciente " .
				"AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
				"AND d.idDiagnostico = h.idDiagnostico " .
				"AND d.idDiagnostico = b.idDiagnostico " .
				"AND h.idContacto IS NULL " .
				"AND h.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " " .
				"AND b.idContacto IS NULL " .
				"AND b.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " " .
				"AND p.idCatEstado = " . $this->idCatEstado . ";";

			if ($this->idCatJurisdiccion != 0)
				$sql = "SELECT count(DISTINCT d.idPaciente) AS casosNuevosConBkyHp " .
					"FROM diagnostico d, pacientes p, estudiosBac b, estudiosHis h, catMunicipio m   " .
					"WHERE d.idPaciente = p.idPaciente " .
					"AND m.idCatEstado = p.idCatEstado " .
					"AND p.idCatMunicipio = m.idCatMunicipio " .
					"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
					"AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					"AND d.idDiagnostico = h.idDiagnostico " .
					"AND d.idDiagnostico = b.idDiagnostico " .
					"AND h.idContacto IS NULL " .
					"AND h.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " " .
					"AND b.idContacto IS NULL " .
					"AND b.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " " .
					"AND p.idCatEstado = " . $this->idCatEstado . ";";
			
			//echo $sql."<BR><BR>";
			
			if ($this->idCatEstado == 0)
				$sql = "SELECT count(DISTINCT d.idPaciente) AS casosNuevosConBkyHp " .
				"FROM diagnostico d, pacientes p, estudiosBac b, estudiosHis h " .
				"WHERE d.idPaciente = p.idPaciente " .
				"AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
				"AND d.idDiagnostico = h.idDiagnostico " .
				"AND d.idDiagnostico = b.idDiagnostico " .
				"AND h.idContacto IS NULL " .
				"AND h.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " " .
				"AND b.idContacto IS NULL " .
				"AND b.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . ";";
			
			$consulta = ejecutaQueryClases($sql);
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$tabla = devuelveRowAssoc($consulta);
				$this->casosNuevosConBkyHp = $tabla["casosNuevosConBkyHp"];

				$sql = "SELECT count(DISTINCT d.idPaciente) AS totalCasosNuevos " .
					"FROM diagnostico d, pacientes p " .
					"WHERE d.idPaciente = p.idPaciente " .
					"AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
					"AND p.idCatEstado = " . $this->idCatEstado . ";";

				if ($this->idCatJurisdiccion != 0)
					$sql = "SELECT count(DISTINCT d.idPaciente) AS totalCasosNuevos " .
						"FROM diagnostico d, pacientes p, catMunicipio m  " .
						"WHERE d.idPaciente = p.idPaciente " .
						"AND m.idCatEstado = p.idCatEstado " .
						"AND p.idCatMunicipio = m.idCatMunicipio " .
						"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
						"AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "'" .
						"AND p.idCatEstado = " . $this->idCatEstado . ";";

				if ($this->idCatEstado == 0)
					 $sql = "SELECT count(DISTINCT d.idPaciente) AS totalCasosNuevos " .
					  "FROM diagnostico d, pacientes p " .
					  "WHERE d.idPaciente = p.idPaciente " .
					  "AND p.fechaDiagnostico BETWEEN '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "';";
				
				$consulta = ejecutaQueryClases($sql);				
				//echo $sql."<BR><BR>";
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql;
				} else {
					$tabla = devuelveRowAssoc($consulta);
					$this->totalCasosNuevos = $tabla["totalCasosNuevos"];
					if ($this->totalCasosNuevos != 0) { 
						$this->resultado = ($this->casosNuevosConBkyHp / $this->totalCasosNuevos) * 100;
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
			'</TD><TD>' . $this->indice. 
			'</TD></TR></TABLE></DIV>';
	}
}
?>
