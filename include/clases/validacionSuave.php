<?php

class validacionSUAVE {
	
	// VALORES DE ENTRADA
	public $idCatEstado;
	public $idCatJurisdiccion;
	public $fechaInicio;
	public $fechaFin;

	// VALORES CALCULADOS
	public $arrUnidades = array();

	// VARIABLES DE CLASE
	public $error = false;
	public $msgError;

	// VALORES ESTATICOS
	static $sexMasc = 1;
	static $sexFeme = 2;

	public function calcular() {

		if (is_null($this->idCatEstado) || is_null($this->idCatJurisdiccion) || is_null($this->fechaInicio) || is_null($this->fechaFin)) {
			$this->error = true;
			$this->msgError = "El indicador requiere del identificador de estado y jurisdiccion, asi como de una fecha de inicio y fin.";
		} else {
			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c0, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](p.fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') = 0 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c0, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](p.fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') = 0 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}			
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m0 += $tabla["c0"];
					else $uniVal->f0 += $tabla["c0"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}				
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c1_4, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 1 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 4 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c1_4, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 1 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 4 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m1_4 += $tabla["c1_4"];
					else $uniVal->f1_4 += $tabla["c1_4"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c5_9, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 5 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 9 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c5_9, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 5 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 9 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m5_9 += $tabla["c5_9"];
					else $uniVal->f5_9 += $tabla["c5_9"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c10_14, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 10 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 14 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c10_14, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 10 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 14 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m10_14 += $tabla["c10_14"];
					else $uniVal->f10_14 += $tabla["c10_14"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c15_19, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 15 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 19 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c15_19, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 15 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 19 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m15_19 += $tabla["c15_19"];
					else $uniVal->f15_19 += $tabla["c15_19"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c20_24, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 20 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 24 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c20_24, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 20 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 24 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m20_24 += $tabla["c20_24"];
					else $uniVal->f20_24 += $tabla["c20_24"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c25_44, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 25 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 44 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c25_44, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 25 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 44 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m25_44 += $tabla["c25_44"];
					else $uniVal->f25_44 += $tabla["c25_44"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c45_49, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 45 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 49 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c45_49, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 45 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 49 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m45_49 += $tabla["c45_49"];
					else $uniVal->f45_49 += $tabla["c45_49"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c50_59, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 50 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 59 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c50_59, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 50 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 59 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m50_59 += $tabla["c50_59"];
					else $uniVal->f50_59 += $tabla["c50_59"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c60_64, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 60 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 64 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c60_64, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 60 " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') <= 64 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m60_64 += $tabla["c60_64"];
					else $uniVal->f60_64 += $tabla["c60_64"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}

			$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c65, p.sexo " .
				"FROM pacientes p " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 65 " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			if ($this->idCatJurisdiccion != 0) {
				$sql = "SELECT p.idCatUnidadTratante, COUNT(p.idPaciente) AS c65, p.sexo " .
				"FROM pacientes p, catMunicipio m " .
				"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
				"AND p.idCatEstado = " . $this->idCatEstado . " " .
				"AND [dbo].[diferenciaAnos](fechaNacimiento, '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "') >= 65 " .
				"AND m.idCatEstado = p.idCatEstado " .
				"AND m.idCatMunicipio = p.idCatMunicipio " .
				"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
				"GROUP BY p.idCatUnidadTratante, p.sexo;";
			}
			$consulta = ejecutaQueryClases($sql);
			//echo '<BR>'. $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {					
					$uniVal = null;
					if (array_key_exists($tabla["idCatUnidadTratante"], $this->arrUnidades)) $uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
					else $uniVal = new unidadValidacionSuave();
					if ($tabla["sexo"] == self::$sexMasc) $uniVal->m65 += $tabla["c65"];
					else $uniVal->f65 += $tabla["c65"];
					$this->arrUnidades[$tabla["idCatUnidadTratante"]] = $uniVal;
				}
			}
		}
	}

	function imprimir(){

		$sql = "SELECT p.idCatUnidadTratante, u.nombreUnidad, u.nombreLocalidad " .
			"FROM pacientes p, catUnidad u " .
			"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
			"AND p.idCatEstado = " . $this->idCatEstado . " " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"GROUP BY p.idCatUnidadTratante, u.nombreUnidad, u.nombreLocalidad " .
			"ORDER BY u.nombreUnidad ";

		if ($this->idCatJurisdiccion != 0) 
			$sql = "SELECT p.idCatUnidadTratante, u.nombreUnidad, u.nombreLocalidad " .
			"FROM pacientes p, catUnidad u, catMunicipio m " .
			"WHERE p.fechaDiagnostico BETWEEN '" .  formatFechaObj($this->fechaInicio, 'Y-m-d') . "' AND '" .  formatFechaObj($this->fechaFin, 'Y-m-d') . "' " .
			"AND p.idCatEstado = " . $this->idCatEstado . " " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"AND p.idCatEstado = m.idCatEstado " .
			"AND p.idCatMunicipio = m.idCatMunicipio " .
			"AND m.idCatJurisdiccion = " . $this->idCatJurisdiccion . " " .
			"GROUP BY p.idCatUnidadTratante, u.nombreUnidad, u.nombreLocalidad, m.idCatJurisdiccion  " .
			"ORDER BY m.idCatJurisdiccion, u.nombreUnidad ";		

		$consulta = ejecutaQueryClases($sql);
		//echo '<BR>'. $sql;
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			echo '<DIV CLASS="datagrid"><TABLE>';
			echo '<THEAD><TR><TH COLSPAN="3">Unidad</TH><TH COLSPAN="2">< 1 a&ntilde;o</TH><TH COLSPAN="2">1-4</TH><TH COLSPAN="2">5-9</TH><TH COLSPAN="2">10-14</TH><TH COLSPAN="2">15-19</TH><TH COLSPAN="2">20-24</TH><TH COLSPAN="2">25-44</TH><TH COLSPAN="2">45-49</TH><TH COLSPAN="2">50-59</TH><TH COLSPAN="2">60-64</TH><TH COLSPAN="2">65 y ></TH><TH COLSPAN="2">Ign</TH><TH COLSPAN="2">total</TH><TH></TH></TR>';
			echo '<TR><TH>Clave</TH><TH>Nombre</TH><TH>Localidad</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>Total</TH></TR></THEAD>';
			$m1 = 0;
			$f1 = 0;
			$m1_4 = 0;
			$f1_4 = 0;
			$m5_9 = 0;
			$f5_9 = 0;
			$m10_14 = 0;
			$f10_14 = 0;
			$m15_19 = 0;
			$f15_19 = 0;
			$m20_24 = 0;
			$f20_24 = 0;
			$m25_44 = 0;
			$f25_44 = 0;
			$m45_49 = 0;
			$f45_49 = 0;
			$m50_59 = 0;
			$f50_59 = 0;
			$m60_64 = 0;
			$f60_64 = 0;
			$m65 = 0;
			$f65 = 0;

			while ($tabla = devuelveRowAssoc($consulta)) {
				$uniVal = $this->arrUnidades[$tabla["idCatUnidadTratante"]];
				echo '<TR><TD>' . $tabla["idCatUnidadTratante"] . '</TD><TD>' . $tabla["nombreUnidad"] . '</TD><TD>' . $tabla["nombreLocalidad"] . 
					'</TD><TD>' . $uniVal->m1 . '</TD><TD>' . $uniVal->f1 . 
					'</TD><TD>' . $uniVal->m1_4 . '</TD><TD>' . $uniVal->f1_4 . 
					'</TD><TD>' . $uniVal->m5_9 . '</TD><TD>' . $uniVal->f5_9 . 
					'</TD><TD>' . $uniVal->m10_14 . '</TD><TD>' . $uniVal->f10_14 . 
					'</TD><TD>' . $uniVal->m15_19 . '</TD><TD>' . $uniVal->f15_19 . 
					'</TD><TD>' . $uniVal->m20_24 . '</TD><TD>' . $uniVal->f20_24 . 
					'</TD><TD>' . $uniVal->m25_44 . '</TD><TD>' . $uniVal->f25_44 . 
					'</TD><TD>' . $uniVal->m45_49 . '</TD><TD>' . $uniVal->f45_49 . 
					'</TD><TD>' . $uniVal->m50_59 . '</TD><TD>' . $uniVal->f50_59 . 
					'</TD><TD>' . $uniVal->m60_64 . '</TD><TD>' . $uniVal->f60_64 . 
					'</TD><TD>' . $uniVal->m65 . '</TD><TD>' . $uniVal->f65 . 
					'</TD><TD>0</TD><TD>0' . 
					'</TD><TD>' . ($uniVal->m1 + $uniVal->m1_4 + $uniVal->m5_9 + $uniVal->m10_14 + $uniVal->m15_19 + $uniVal->m20_24 + $uniVal->m25_44 + $uniVal->m45_49 + $uniVal->m50_59 + $uniVal->m60_64 + $uniVal->m65) . '</TD><TD>' . ($uniVal->f1 + $uniVal->f1_4 + $uniVal->f5_9 + $uniVal->f10_14 + $uniVal->f15_19 + $uniVal->f20_24 + $uniVal->f25_44 + $uniVal->f45_49 + $uniVal->f50_59 + $uniVal->f60_64 + $uniVal->f65) . 
					'</TD><TD>' . ($uniVal->m1 + $uniVal->m1_4 + $uniVal->m5_9 + $uniVal->m10_14 + $uniVal->m15_19 + $uniVal->m20_24 + $uniVal->m25_44 + $uniVal->m45_49 + $uniVal->m50_59 + $uniVal->m60_64 + $uniVal->m65 + $uniVal->f1 + $uniVal->f1_4 + $uniVal->f5_9 + $uniVal->f10_14 + $uniVal->f15_19 + $uniVal->f20_24 + $uniVal->f25_44 + $uniVal->f45_49 + $uniVal->f50_59 + $uniVal->f60_64 + $uniVal->f65) .
					'</TR>';
				$m1 += $uniVal->m1;			$f1 += $uniVal->f1;			$m1_4 += $uniVal->m1_4;		$f1_4 += $uniVal->f1_4;		$m5_9 += $uniVal->m5_9;		$f5_9 += $uniVal->f5_9;	
				$m10_14 += $uniVal->m10_14;	$f10_14 += $uniVal->f10_14;	$m15_19 += $uniVal->m15_19;	$f15_19 += $uniVal->f15_19;	$m20_24 += $uniVal->m20_24;	$f20_24 += $uniVal->f20_24;
				$m25_44 += $uniVal->m25_44;	$f25_44 += $uniVal->f25_44;	$m45_49 += $uniVal->m45_49;	$f45_49 += $uniVal->f45_49;	$m50_59 += $uniVal->m50_59;	$f50_59 += $uniVal->f50_59;
				$m60_64 += $uniVal->m60_64;	$f60_64 += $uniVal->f60_64;	$m65 += $uniVal->m65;		$f65 += $uniVal->f65;
			}

			echo '<TFOOT><TR><TD COLSPAN="3">TOTAL</TD>' . 
					'</TD><TD>' . $m1 . '</TD><TD>' . $f1 . 
					'</TD><TD>' . $m1_4 . '</TD><TD>' . $f1_4 . 
					'</TD><TD>' . $m5_9 . '</TD><TD>' . $f5_9 . 
					'</TD><TD>' . $m10_14 . '</TD><TD>' . $f10_14 . 
					'</TD><TD>' . $m15_19 . '</TD><TD>' . $f15_19 . 
					'</TD><TD>' . $m20_24 . '</TD><TD>' . $f20_24 . 
					'</TD><TD>' . $m25_44 . '</TD><TD>' . $f25_44 . 
					'</TD><TD>' . $m45_49 . '</TD><TD>' . $f45_49 . 
					'</TD><TD>' . $m50_59 . '</TD><TD>' . $f50_59 . 
					'</TD><TD>' . $m60_64 . '</TD><TD>' . $f60_64 . 
					'</TD><TD>' . $m65 . '</TD><TD>' . $f65 . 
					'</TD><TD>0</TD><TD>0' . 
					'</TD><TD>' . ($m1 + $m1_4 + $m5_9 + $m10_14 + $m15_19 + $m20_24 + $m25_44 + $m45_49 + $m50_59 + $m60_64 + $m65) . '</TD><TD>' . ($f1 + $f1_4 + $f5_9 + $f10_14 + $f15_19 + $f20_24 + $f25_44 + $f45_49 + $f50_59 + $f60_64 + $f65) . 
					'</TD><TD>' . ($m1 + $m1_4 + $m5_9 + $m10_14 + $m15_19 + $m20_24 + $m25_44 + $m45_49 + $m50_59 + $m60_64 + $m65 + $f1 + $f1_4 + $f5_9 + $f10_14 + $f15_19 + $f20_24 + $f25_44 + $f45_49 + $f50_59 + $f60_64 + $f65) .
					'</TR></TFOOT>';

			echo '</TABLE></DIV>';
		}	
	}
}

class unidadValidacionSuave {

	public $idCatUnidad;
	
	public $f1 = 0;	
	public $f1_4 = 0;
	public $f5_9 = 0;
	public $f10_14 = 0;
	public $f15_19 = 0;
	public $f20_24 = 0;
	public $f25_44 = 0;
	public $f45_49 = 0;
	public $f50_59 = 0;
	public $f60_64 = 0;
	public $f65 = 0;
	public $m1 = 0;
	public $m1_4 = 0;
	public $m5_9 = 0;
	public $m10_14 = 0;
	public $m15_19 = 0;
	public $m20_24 = 0;
	public $m25_44 = 0;
	public $m45_49 = 0;
	public $m50_59 = 0;
	public $m60_64 = 0;
	public $m65 = 0;

}


?>