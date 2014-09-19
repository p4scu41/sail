<?php

class ReporteMAE {
	
	// VARIABLES DE ENTRADA **********************************************************************************
	public $ano;
	public $trimestre;
		
	// VARIABLES DEL REPORTE ******************************************************************************************
	public $estadosMAE = array();
	public $municipiosPrioritarios = array();
	
	// VARIABLES DEL REPORTE ******************************************************************************************
	public $error = false;
	public $msgError;

	/////// CRITERIO PARA MUNICIPIOS PRIORITARIOS:
	///////	Tasa de Prevalencia > 0.1
	static $idCriterioMunicipiosPrioritarios = 0.1;
	///////	
	///////	

	// BD ******************************************************************************************	
	// TABLA catFormaDeteccion
	static $idCatFormaDeteccionCon = 1;			// 1 Consulta
	static $idCatFormaDeteccionExC = 2;			// 2 Exámen de Contactos
	static $idCatFormaDeteccionExP = 3;			// 3 Exámen de Poblacion
	// TABLA ClasificacionLepra
	static $idCatClaLepLN = 1;					// 1	Lepromatosa Nodular (MB)
	static $idCatClaLepD = 2;					// 2	Dimorfa (MB)
	static $idCatClaLepT = 3;					// 3	Tuberculoide (PB)
	static $idCatClaLepI = 4;					// 4	Indeterminado (PB)
	static $idCatClaLepNE = 5;					// 5	No Especificado
	static $idCatClaLepLD = 6;					// 6	Lepromatosa Difusa (MB)
	// TABLA ClasificacionEstadoPaciente
	static $idCatClaEstPacSTX = "1";			// 1	Prevalente sin Tratamiento
	static $idCatClaEstPacCTX = "2, 5, 9";		// 2	Prevalente con Tratamiento, 5	Reingreso PQT,	9 Recaida
	static $idCatClaEstPacPrevalentes = "1, 2, 5, 9";	// 1	Prevalente sin Tratamiento, 2	Prevalente con Tratamiento, 5	Reingreso PQT,	9 Recaida
	static $idCatClaEstPacVigPTX = "3, 6";
	// Las poblaciones estan registradas desde 2010 hasta 2030, el aNo de analisis esta fuera de ese rango, se provoca un error en el reporte
	static $anoInicioPoblacionBD = 2010;
	static $anoFinPoblacionBD = 2030;
	// BD ******************************************************************************************

	public function imprimirReporte() {

		echo '<DIV CLASS="datagrid"><TABLE BORDER="1"><THEAD>'.
		'<TR ALIGN="center"><TH COLSPAN="8"> </TH><TH COLSPAN="14"> Grupos de Edad </TH><TH COLSPAN="24"> </TH></TR>'.
		'<TR ALIGN="center"><TH ROWSPAN="3"> Entidad Federativa </TH><TH COLSPAN="4"> Casos Nuevos '. $this->ano .' </TH><TH COLSPAN="3"> Forma de detección </TH>'.
		 '<TH COLSPAN="2"> Menor a 1 </TH><TH COLSPAN="2"> 1 a 4 </TH><TH COLSPAN="2"> 5 a 14 </TH><TH COLSPAN="2"> 15 a 24 </TH><TH COLSPAN="2"> 25 a 44 </TH><TH COLSPAN="2"> 45 a 64 </TH><TH COLSPAN="2"> 65 y mas </TH>'.
		 '<TH> Población General </TH><TH> Tasa de Incidencia </TH><TH COLSPAN="4"> Discapacidad '. $this->ano .' </TH><TH COLSPAN="7"> Prevalentes </TH>'.
		 '<TH> Tasa de Prevalencia </TH><TH> % del total </TH><TH COLSPAN="3"> Contactos de la prevalencia </TH><TH COLSPAN="3"> Contactos Vig PTX </TH><TH COLSPAN="3"> Contactos Total </TH></TR>'.
		'<TR ALIGN="center"><TH>MB</TH><TH>PB</TH><TH>S/C</TH><TH>Total</TH><TH>Consulta</TH><TH>Contacto</TH><TH>Población</TH><TH>H</TH><TH>M</TH><TH>H</TH><TH>M</TH><TH>H</TH><TH>M</TH><TH>H</TH><TH>M</TH><TH>H</TH><TH>M</TH>'.
		 '<TH>H</TH><TH>M</TH><TH>H</TH><TH>M</TH><TH COLSPAN="2"></TH><TH>0</TH><TH>1</TH><TH>2</TH><TH>S/C</TH><TH>MB</TH><TH>PB</TH><TH>SC</TH><TH>MB/STX</TH><TH>PB/STX</TH><TH>SC/STX</TH><TH>Total</TH><TH COLSPAN="2"></TH>'.
		 '<TH>Reg</TH><TH>Exam</TH><TH>Enf</TH><TH>Reg</TH><TH>Exam</TH><TH>Enf</TH><TH>Reg</TH><TH>Exam</TH><TH>Enf</TH></TR></THEAD>';
		$longitud = count($this->estadosMAE);
		for ($i = 1; $i < $longitud; $i++) {
			$objTemp = $this->estadosMAE[$i];
			echo '<TR><TD>'.$objTemp->nombre.'</TD><TD>'.$objTemp->casosNuevosMB.'</TD><TD>'.$objTemp->casosNuevosPB.'</TD><TD>'.$objTemp->casosNuevosSC.'</TD><TD>'.$objTemp->casosNuevosTotal.'</TD><TD>'.
				$objTemp->formaDeteccionConsulta.'</TD><TD>'.$objTemp->formaDeteccionContacto.'</TD><TD>'.$objTemp->formaDeteccionPoblacion.'</TD><TD>'.
				$objTemp->grupoEdad1H.'</TD><TD>'.$objTemp->grupoEdad1M.'</TD><TD>'.$objTemp->grupoEdad2H.'</TD><TD>'.$objTemp->grupoEdad2M.'</TD><TD>'.
				$objTemp->grupoEdad3H.'</TD><TD>'.$objTemp->grupoEdad3M.'</TD><TD>'.$objTemp->grupoEdad4H.'</TD><TD>'.$objTemp->grupoEdad4M.'</TD><TD>'.
				$objTemp->grupoEdad5H.'</TD><TD>'.$objTemp->grupoEdad5M.'</TD><TD>'.$objTemp->grupoEdad6H.'</TD><TD>'.$objTemp->grupoEdad6M.'</TD><TD>'.
				$objTemp->grupoEdad7H.'</TD><TD>'.$objTemp->grupoEdad7M.'</TD><TD>'.$objTemp->poblacionGeneral.'</TD><TD>'.number_format($objTemp->tazaIncidencia, 4).'</TD><TD>'.
				$objTemp->gradoDiscapacidad0.'</TD><TD>'.$objTemp->gradoDiscapacidad1.'</TD><TD>'.$objTemp->gradoDiscapacidad2.'</TD><TD>'.$objTemp->gradoDiscapacidadSC.'</TD><TD>'.
				$objTemp->prevalentesMB.'</TD><TD>'.$objTemp->prevalentesPB.'</TD><TD>'.$objTemp->prevalentesSC.'</TD><TD>'.
				$objTemp->prevalentesMBSTX.'</TD><TD>'.$objTemp->prevalentesPBSTX.'</TD><TD>'.$objTemp->prevalentesSCSTX.'</TD><TD>'.$objTemp->prevalentesTotal.'</TD><TD>'.number_format($objTemp->tazaPrevalencia, 4).'</TD><TD>'.$objTemp->porcentajeTotal.'</TD><TD>'.
				$objTemp->contactosPrevalenciaReg.'</TD><TD>'.$objTemp->contactosPrevalenciaExam.'</TD><TD>'.$objTemp->contactosPrevalenciaEnf.'</TD><TD>'.
				$objTemp->contactosVigPTXReg.'</TD><TD>'.$objTemp->contactosVigPTXExam.'</TD><TD>'.$objTemp->contactosVigPTXEnf.'</TD><TD>'.
				$objTemp->contactosTotalReg.'</TD><TD>'.$objTemp->contactosTotalExam.'</TD><TD>'.$objTemp->contactosTotalEnf.'</TD></TR>';
		}
		$objTemp = $this->estadosMAE[0];
		echo '<TR ALIGN="center"><TH>'.$objTemp->nombre.'</TH><TH>'.$objTemp->casosNuevosMB.'</TH><TH>'.$objTemp->casosNuevosPB.'</TH><TH>'.$objTemp->casosNuevosSC.'</TH><TH>'.$objTemp->casosNuevosTotal.'</TH><TH>'.
			$objTemp->formaDeteccionConsulta.'</TH><TH>'.$objTemp->formaDeteccionContacto.'</TH><TH>'.$objTemp->formaDeteccionPoblacion.'</TH><TH>'.
			$objTemp->grupoEdad1H.'</TH><TH>'.$objTemp->grupoEdad1M.'</TH><TH>'.$objTemp->grupoEdad2H.'</TH><TH>'.$objTemp->grupoEdad2M.'</TH><TH>'.
			$objTemp->grupoEdad3H.'</TH><TH>'.$objTemp->grupoEdad3M.'</TH><TH>'.$objTemp->grupoEdad4H.'</TH><TH>'.$objTemp->grupoEdad4M.'</TH><TH>'.
			$objTemp->grupoEdad5H.'</TH><TH>'.$objTemp->grupoEdad5M.'</TH><TH>'.$objTemp->grupoEdad6H.'</TH><TH>'.$objTemp->grupoEdad6M.'</TH><TH>'.
			$objTemp->grupoEdad7H.'</TH><TH>'.$objTemp->grupoEdad7M.'</TH><TH>'.$objTemp->poblacionGeneral.'</TH><TH>'.number_format($objTemp->tazaIncidencia, 4).'</TH><TH>'.
			$objTemp->gradoDiscapacidad0.'</TH><TH>'.$objTemp->gradoDiscapacidad1.'</TH><TH>'.$objTemp->gradoDiscapacidad2.'</TH><TH>'.$objTemp->gradoDiscapacidadSC.'</TH><TH>'.
			$objTemp->prevalentesMB.'</TH><TH>'.$objTemp->prevalentesPB.'</TH><TH>'.$objTemp->prevalentesSC.'</TH><TH>'.
			$objTemp->prevalentesMBSTX.'</TH><TH>'.$objTemp->prevalentesPBSTX.'</TH><TH>'.$objTemp->prevalentesSCSTX.'</TH><TH>'.$objTemp->prevalentesTotal.'</TH><TH>'.number_format($objTemp->tazaPrevalencia, 4).'</TH><TH>'.$objTemp->porcentajeTotal.'</TH><TH>'.
			$objTemp->contactosPrevalenciaReg.'</TH><TH>'.$objTemp->contactosPrevalenciaExam.'</TH><TH>'.$objTemp->contactosPrevalenciaEnf.'</TH><TH>'.
			$objTemp->contactosVigPTXReg.'</TH><TH>'.$objTemp->contactosVigPTXExam.'</TH><TH>'.$objTemp->contactosVigPTXEnf.'</TH><TH>'.
			$objTemp->contactosTotalReg.'</TH><TH>'.$objTemp->contactosTotalExam.'</TH><TH>'.$objTemp->contactosTotalEnf.'</TH></TR>';

		echo '</TABLE></DIV>';

		echo '<DIV CLASS="datagrid"><TABLE BORDER="1"><THEAD><TR ALIGN="center"><TH COLSPAN="4">Municipios Prioritarios</TH></TR><TR ALIGN="center"><TH> Estado </TH><TH> Municipio Prioritario </TH><TH> Enfermos </TH><TH> Tasa </TH></TR></THEAD>';			
		foreach ($this->municipiosPrioritarios as &$municipio) {			
			echo '<TR><TD>'.$municipio->nombre.
				'</TD><TD>'.$municipio->estado.
				'</TD><TD>'.$municipio->enfermos.
				'</TD><TD>'.$municipio->tasa.'</TD></TR>';
		}
		echo '</TABLE></DIV>';
		
	}
	
	public function generarReporte() {

		if (is_null($this->ano) || is_null($this->trimestre)) {
			$this->error = true;
			$this->msgError = "El reporte requiere del ano y trimestre para ejecutarse.";
		} else {
			if (($this->ano < self::$anoInicioPoblacionBD) || ($this->ano > self::$anoFinPoblacionBD)) {
				$this->error = true;
				$this->msgError = "La base de datos pobacional es desde " . self::$anoInicioPoblacionBD . " hasta " . self::$anoFinPoblacionBD . ", usted selecciono " . $this->ano;
			} else {			
				switch ($this->trimestre) {
					case 1:
						$fAuxI = new DateTime($this->ano . "-01-01");
						$fAuxF = new DateTime($this->ano . "-04-01");
						break;
					case 2:
						$fAuxI = new DateTime($this->ano . "-04-01");
						$fAuxF = new DateTime($this->ano . "-07-01");
						break;
					case 3:
						$fAuxI = new DateTime($this->ano . "-07-01");
						$fAuxF = new DateTime($this->ano . "-10-01");
						break;
					default:
						$fAuxI = new DateTime($this->ano . "-10-01");
						$fAuxF = new DateTime($this->ano + 1 . "-01-01");
						break;			
				}
				$fIni = formatFechaObj($fAuxI, 'Y-m-d');
				$fFin = formatFechaObj($fAuxF, 'Y-m-d');
				$fIniExamenContactos = formatFechaObj(new DateTime($this->ano . "-01-01"), 'Y-m-d');
				$fFinExamenContactos = formatFechaObj(new DateTime($this->ano + 1 . "-01-01"), 'Y-m-d');

				// Para las consultas, se considera $fAuxI <= FECHA < $fAuxF

				$sql = "SELECT m.idCatEstado, e.nombre, SUM (m.pob" . $this->ano . ") AS totalPoblacion " .
					"FROM catEstado e, catMunicipio m " .
					"WHERE e.idCatEstado = m.idCatEstado " .
					"GROUP BY m.idCatEstado,  e.nombre;";				
				$consulta = ejecutaQueryClases($sql);
				//echo $sql.'<br><br>';
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
				} else {
					$sumatoriaPoblacion = 0;
					while ($tabla = devuelveRowAssoc($consulta)) {
						$objTemp = new EstadosMAE();
						$objTemp->idCatEstado = $tabla["idCatEstado"];
						$objTemp->nombre = $tabla["nombre"];
						$objTemp->poblacionGeneral = $tabla["totalPoblacion"];
						$sumatoriaPoblacion += $tabla["totalPoblacion"];
						$this->estadosMAE[$tabla["idCatEstado"]] = $objTemp;
					}
					$objTemp = new EstadosMAE();
					$objTemp->idCatEstado = 0;
					$objTemp->nombre = "Total";
					$objTemp->poblacionGeneral = $sumatoriaPoblacion;
					$this->estadosMAE[0] = $objTemp;

					$sql = "SELECT u.idCatEstado, COUNT(p.idCatFormaDeteccion) AS total, p.idCatFormaDeteccion " .
						"FROM pacientes p, diagnostico d, catUnidad u " .
						"WHERE p.idPaciente = d.idDiagnostico " .
						"AND p.idCatUnidadNotificante = u.idCatUnidad " .
						"AND '" . $fIni . "' <= p.fechaDiagnostico " .
						"AND p.fechaDiagnostico < '" . $fFin . "' " .
						"GROUP BY u.idCatEstado, p.idCatFormaDeteccion;";
					$consulta = ejecutaQueryClases($sql);
					//echo $sql.'<br><br>';
					if (is_string($consulta)) {
						$this->error = true;
						$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
					} else {
						$objTempTotal = $this->estadosMAE[0];
						while ($tabla = devuelveRowAssoc($consulta)) {
							$objTemp = $this->estadosMAE[$tabla["idCatEstado"]];
							if ($tabla["idCatFormaDeteccion"] == self::$idCatFormaDeteccionCon) {
								$objTemp->formaDeteccionConsulta += $tabla["total"];
								$objTempTotal->formaDeteccionConsulta += $tabla["total"];
							} elseif ($tabla["idCatFormaDeteccion"] == self::$idCatFormaDeteccionExC) {
								$objTemp->formaDeteccionContacto += $tabla["total"];
								$objTempTotal->formaDeteccionContacto += $tabla["total"];
							} else {
								$objTemp->formaDeteccionPoblacion += $tabla["total"];
								$objTempTotal->formaDeteccionPoblacion += $tabla["total"];
							}
							$this->estadosMAE[$tabla["idCatEstado"]] = $objTemp;
						}
						$this->estadosMAE[0] = $objTempTotal;

						$sql = "SELECT u.idCatEstado, count(d.idCatClasificacionLepra) AS total, d.idCatClasificacionLepra " .
							"FROM pacientes p, diagnostico d, catUnidad u " .
							"WHERE p.idPaciente = d.idDiagnostico " .
							"AND p.idCatUnidadNotificante = u.idCatUnidad " .
							"AND '" . $fIni . "' <= p.fechaDiagnostico " .
							"AND p.fechaDiagnostico < '" . $fFin . "' " .
							"GROUP BY u.idCatEstado, d.idCatClasificacionLepra;";
						$consulta = ejecutaQueryClases($sql);
						//echo $sql.'<br><br>';
						if (is_string($consulta)) {
							$this->error = true;
							$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
						} else {
							$objTempTotal = $this->estadosMAE[0];
							while ($tabla = devuelveRowAssoc($consulta)) {
								$objTemp = $this->estadosMAE[$tabla["idCatEstado"]];
								if ( ($tabla["idCatClasificacionLepra"] == self::$idCatClaLepLN) || ($tabla["idCatClasificacionLepra"] == self::$idCatClaLepD) || ($tabla["idCatClasificacionLepra"] == self::$idCatClaLepLD)) {
									$objTemp->casosNuevosMB += $tabla["total"];
									$objTempTotal->casosNuevosMB += $tabla["total"];
								} elseif (($tabla["idCatClasificacionLepra"] == self::$idCatClaLepT) || ($tabla["idCatClasificacionLepra"] == self::$idCatClaLepI)) {
									$objTemp->casosNuevosPB += $tabla["total"];
									$objTempTotal->casosNuevosPB += $tabla["total"];
								} else {
									$objTemp->casosNuevosSC += $tabla["total"];
									$objTempTotal->casosNuevosSC += $tabla["total"];
								}
								$objTemp->casosNuevosTotal += $tabla["total"];
								$objTempTotal->casosNuevosTotal += $tabla["total"];

								if ($objTemp->poblacionGeneral != 0) $objTemp->tazaIncidencia = ($objTemp->casosNuevosTotal*100000)/$objTemp->poblacionGeneral;
								if ($objTempTotal->poblacionGeneral != 0) $objTempTotal->tazaIncidencia = ($objTempTotal->casosNuevosTotal*100000)/$objTempTotal->poblacionGeneral;

								$this->estadosMAE[$tabla["idCatEstado"]] = $objTemp;
							}
							$this->estadosMAE[0] = $objTempTotal;

							$sql = "(SELECT u.idCatEstado, count(p.idPaciente) AS total, p.sexo, '1' AS rango " .
								"FROM pacientes p, diagnostico d, catUnidad u " .
								"WHERE p.idPaciente = d.idDiagnostico " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) < 1 " .
								"AND p.idCatUnidadNotificante = u.idCatUnidad " .
								"AND '" . $fIni . "' <= p.fechaDiagnostico " .
								"AND p.fechaDiagnostico < '" . $fFin . "' " .								
								"GROUP BY u.idCatEstado, p.sexo ) " .
								"UNION " .
								"(SELECT u.idCatEstado, count(p.idPaciente) AS total, p.sexo, '2' AS rango " .
								"FROM pacientes p, diagnostico d, catUnidad u " .
								"WHERE p.idPaciente = d.idDiagnostico " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) >= 1 " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) <= 4 " .
								"AND p.idCatUnidadNotificante = u.idCatUnidad " .
								"AND '" . $fIni . "' <= p.fechaDiagnostico " .
								"AND p.fechaDiagnostico < '" . $fFin . "' " .
								"GROUP BY u.idCatEstado, p.sexo ) " .
								"UNION " .
								"(SELECT u.idCatEstado, count(p.idPaciente) AS total, p.sexo, '3' AS rango " .
								"FROM pacientes p, diagnostico d, catUnidad u " .
								"WHERE p.idPaciente = d.idDiagnostico " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) >= 5 " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) <= 14 " .
								"AND p.idCatUnidadNotificante = u.idCatUnidad " .
								"AND '" . $fIni . "' <= p.fechaDiagnostico " .
								"AND p.fechaDiagnostico < '" . $fFin . "' " .
								"GROUP BY u.idCatEstado, p.sexo ) " .
								"UNION " .
								"(SELECT u.idCatEstado, count(p.idPaciente) AS total, p.sexo, '4' AS rango " .
								"FROM pacientes p, diagnostico d, catUnidad u " .
								"WHERE p.idPaciente = d.idDiagnostico " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) >= 15 " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) <= 24 " .
								"AND p.idCatUnidadNotificante = u.idCatUnidad " .
								"AND '" . $fIni . "' <= p.fechaDiagnostico " .
								"AND p.fechaDiagnostico < '" . $fFin . "' " .
								"GROUP BY u.idCatEstado, p.sexo ) " .
								"UNION " .
								"(SELECT u.idCatEstado, count(p.idPaciente) AS total, p.sexo, '5' AS rango " .
								"FROM pacientes p, diagnostico d, catUnidad u " .
								"WHERE p.idPaciente = d.idDiagnostico " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) >= 25 " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) <= 44 " .
								"AND p.idCatUnidadNotificante = u.idCatUnidad " .
								"AND '" . $fIni . "' <= p.fechaDiagnostico " .
								"AND p.fechaDiagnostico < '" . $fFin . "' " .
								"GROUP BY u.idCatEstado, p.sexo ) " .
								"UNION " .
								"(SELECT u.idCatEstado, count(p.idPaciente) AS total, p.sexo, '6' AS rango " .
								"FROM pacientes p, diagnostico d, catUnidad u " .
								"WHERE p.idPaciente = d.idDiagnostico " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) >= 45 " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) <= 64 " .
								"AND p.idCatUnidadNotificante = u.idCatUnidad " .
								"AND '" . $fIni . "' <= p.fechaDiagnostico " .
								"AND p.fechaDiagnostico < '" . $fFin . "' " .
								"GROUP BY u.idCatEstado, p.sexo ) " .
								"UNION " .
								"(SELECT u.idCatEstado, count(p.idPaciente) AS total, p.sexo, '7' AS rango " .
								"FROM pacientes p, diagnostico d, catUnidad u " .
								"WHERE p.idPaciente = d.idDiagnostico " .
								"AND dbo.diferenciaAnos(p.fechaNacimiento, p.fechaDiagnostico) >= 65 " .
								"AND p.idCatUnidadNotificante = u.idCatUnidad " .
								"AND '" . $fIni . "' <= p.fechaDiagnostico " .
								"AND p.fechaDiagnostico < '" . $fFin . "' " .
								"GROUP BY u.idCatEstado, p.sexo )";
							$consulta = ejecutaQueryClases($sql);
							//echo $sql.'<br><br>';
							if (is_string($consulta)) {
								$this->error = true;
								$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
							} else {
								$objTempTotal = $this->estadosMAE[0];
								while ($tabla = devuelveRowAssoc($consulta)) {
									$objTemp = $this->estadosMAE[$tabla["idCatEstado"]];
									switch ($tabla["rango"]) {
										case "1":
											if ($tabla["sexo"] == 1) { 
												$objTemp->grupoEdad1H += $tabla["total"];
												$objTempTotal->grupoEdad1H += $tabla["total"];
											} else {
												$objTemp->grupoEdad1M += $tabla["total"];
												$objTempTotal->grupoEdad1M += $tabla["total"];
											}
											break;
										case "2":
											if ($tabla["sexo"] == 1) {
												$objTemp->grupoEdad2H += $tabla["total"];
												$objTempTotal->grupoEdad2H += $tabla["total"];
											} else {
												$objTemp->grupoEdad2M += $tabla["total"];
												$objTempTotal->grupoEdad2M += $tabla["total"];
											}
											break;
										case "3":
											if ($tabla["sexo"] == 1) {
												$objTemp->grupoEdad3H += $tabla["total"];
												$objTempTotal->grupoEdad3H += $tabla["total"];
											} else {
												$objTemp->grupoEdad3M += $tabla["total"];
												$objTempTotal->grupoEdad3M += $tabla["total"];
											} 
											break;
										case "4":
											if ($tabla["sexo"] == 1) {
												$objTemp->grupoEdad4H += $tabla["total"];
												$objTempTotal->grupoEdad4H += $tabla["total"];
											} else {
												$objTemp->grupoEdad4M += $tabla["total"];
												$objTempTotal->grupoEdad4M += $tabla["total"];
											} 
											break;
										case "5":
											if ($tabla["sexo"] == 1) {
												$objTemp->grupoEdad5H += $tabla["total"];
												$objTempTotal->grupoEdad5H += $tabla["total"];
											} else {
												$objTemp->grupoEdad5M += $tabla["total"];
												$objTempTotal->grupoEdad5M += $tabla["total"];
											} 
											break;
										case "6":
											if ($tabla["sexo"] == 1) {
												$objTemp->grupoEdad6H += $tabla["total"];
												$objTempTotal->grupoEdad6H += $tabla["total"];
											} else {
												$objTemp->grupoEdad6M += $tabla["total"];
												$objTempTotal->grupoEdad6M += $tabla["total"];
											} 
											break;
										case "7":
											if ($tabla["sexo"] == 1) {
												$objTemp->grupoEdad7H += $tabla["total"];
												$objTempTotal->grupoEdad7H += $tabla["total"];
											} else {
												$objTemp->grupoEdad7M += $tabla["total"];
												$objTempTotal->grupoEdad7M += $tabla["total"];
											} 
											break;
									}
									$this->estadosMAE[$tabla["idCatEstado"]] = $objTemp;
								}
								$this->estadosMAE[0] = $objTempTotal;

								$sql = "(SELECT u.idCatEstado, count(d.idDiagnostico) AS total, 0 AS Grado ".
									"FROM pacientes p, diagnostico d, catUnidad u ".
									"WHERE p.idPaciente = d.idDiagnostico ".
									"AND p.idCatUnidadNotificante = u.idCatUnidad ".
									"AND '" . $fIni . "' <= p.fechaDiagnostico ". 
									"AND p.fechaDiagnostico < '" . $fFin . "' ".
									"AND dbo.gradoDiscapacidad(d.discOjoIzq,discOjoDer, d.discManoIzq, d.discManoDer, d.discPieIzq , d.discPieDer) = 0 ".
									"GROUP BY u.idCatEstado)" .
									"UNION ".
									"(SELECT u.idCatEstado, count(d.idDiagnostico) AS total, 1 AS Grado ".
									"FROM pacientes p, diagnostico d, catUnidad u ".
									"WHERE p.idPaciente = d.idDiagnostico ".
									"AND p.idCatUnidadNotificante = u.idCatUnidad ".
									"AND '" . $fIni . "' <= p.fechaDiagnostico ". 
									"AND p.fechaDiagnostico < '" . $fFin . "' ".
									"AND dbo.gradoDiscapacidad(d.discOjoIzq,discOjoDer, d.discManoIzq, d.discManoDer, d.discPieIzq , d.discPieDer) = 1 ".
									"GROUP BY u.idCatEstado)" .
									"UNION ".
									"(SELECT u.idCatEstado, count(d.idDiagnostico) AS total, 2 AS Grado ".
									"FROM pacientes p, diagnostico d, catUnidad u ".
									"WHERE p.idPaciente = d.idDiagnostico ".
									"AND p.idCatUnidadNotificante = u.idCatUnidad ".
									"AND '" . $fIni . "' <= p.fechaDiagnostico ". 
									"AND p.fechaDiagnostico < '" . $fFin . "' ".
									"AND dbo.gradoDiscapacidad(d.discOjoIzq,discOjoDer, d.discManoIzq, d.discManoDer, d.discPieIzq , d.discPieDer) = 2 ".
									"GROUP BY u.idCatEstado);";

								$consulta = ejecutaQueryClases($sql);
								//echo $sql.'<br><br>';
								if (is_string($consulta)) {
									$this->error = true;
									$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
								} else {
									$objTempTotal = $this->estadosMAE[0];
									while ($tabla = devuelveRowAssoc($consulta)) {
										$objTemp = $this->estadosMAE[$tabla["idCatEstado"]];
										switch ($tabla["Grado"]) {
											case 0:
												$objTemp->gradoDiscapacidad0 += $tabla["total"];
												$objTempTotal->gradoDiscapacidad0 += $tabla["total"];
												break;
											case 1:
												$objTemp->gradoDiscapacidad1 += $tabla["total"];
												$objTempTotal->gradoDiscapacidad1 += $tabla["total"];
												break;
											case 2:
												$objTemp->gradoDiscapacidad2 += $tabla["total"];
												$objTempTotal->gradoDiscapacidad2 += $tabla["total"];
												break;
										}
										$this->estadosMAE[$tabla["idCatEstado"]] = $objTemp;
									}
									$this->estadosMAE[0] = $objTempTotal;

									$sql = "(SELECT u.idCatEstado, COUNT(d.idDiagnostico) AS Total, 0 AS ConTX, d.idCatClasificacionLepra as ClasificacionLepra " .
										"FROM pacientes p, diagnostico d, catUnidad u " .
										"WHERE p.idPaciente = d.idDiagnostico AND p.idCatUnidadNotificante = u.idCatUnidad " .
										"AND d.idCatEstadoPaciente IN (". self::$idCatClaEstPacSTX .") " .
										"GROUP BY u.idCatEstado, d.idCatClasificacionLepra) " .
										"UNION " .
										"(SELECT u.idCatEstado, COUNT(d.idDiagnostico) AS Total, 1 AS ConTX, d.idCatClasificacionLepra as ClasificacionLepra " .
										"FROM pacientes p, diagnostico d, catUnidad u " .
										"WHERE p.idPaciente = d.idDiagnostico AND p.idCatUnidadNotificante = u.idCatUnidad " .
										"AND d.idCatEstadoPaciente IN (". self::$idCatClaEstPacCTX .") " .
										"GROUP BY u.idCatEstado, d.idCatClasificacionLepra);";
									$consulta = ejecutaQueryClases($sql);
									//echo $sql.'<br><br>';
									if (is_string($consulta)) {
										$this->error = true;
										$this->msgError = $consulta . " SQL:" . $sql.'<br><br>';
									} else {
										$objTempTotal = $this->estadosMAE[0];
										while ($tabla = devuelveRowAssoc($consulta)) {
											$objTemp = $this->estadosMAE[$tabla["idCatEstado"]];
											switch ($tabla["ClasificacionLepra"]) {
												case self::$idCatClaLepLN:
												case self::$idCatClaLepD:
												case self::$idCatClaLepLD:
													if ($tabla["ConTX"] == 0) {
														$objTemp->prevalentesMBSTX += $tabla["Total"];
														$objTempTotal->prevalentesMBSTX += $tabla["Total"];
													} else {
														$objTemp->prevalentesMB += $tabla["Total"];
														$objTempTotal->prevalentesMB += $tabla["Total"];
													}
													break;
												case self::$idCatClaLepT:
												case self::$idCatClaLepI:
													if ($tabla["ConTX"] == 0) {
														$objTemp->prevalentesPBSTX += $tabla["Total"];
														$objTempTotal->prevalentesPBSTX += $tabla["Total"];
													} else {
														$objTemp->prevalentesPB += $tabla["Total"];
														$objTempTotal->prevalentesPB += $tabla["Total"];
													}
													break;
												case self::$idCatClaLepNE:
													if ($tabla["ConTX"] == 0) {
														$objTemp->prevalentesSC += $tabla["Total"];
														$objTempTotal->prevalentesSC += $tabla["Total"];
													} else { 
														$objTemp->prevalentesSCSTX += $tabla["Total"];
														$objTempTotal->prevalentesSCSTX += $tabla["Total"];
													}
													break;
											}
											$objTemp->prevalentesTotal += $tabla["Total"];
											$objTempTotal->prevalentesTotal += $tabla["Total"];

											if ($objTemp->poblacionGeneral != 0) $objTemp->tazaPrevalencia = ($objTemp->prevalentesTotal*10000)/$objTemp->poblacionGeneral;	
											if ($objTempTotal->poblacionGeneral != 0) $objTempTotal->tazaPrevalencia = ($objTempTotal->prevalentesTotal*10000)/$objTempTotal->poblacionGeneral;	
											
											$this->estadosMAE[$tabla["idCatEstado"]] = $objTemp;
										}
										$this->estadosMAE[0] = $objTempTotal;

										$longitud = count($this->estadosMAE);
										$objTempTotal = $this->estadosMAE[0];
										for ($i = 1; $i < ($longitud - 1); $i++) {
											$objTemp = $this->estadosMAE[$i];
											$objTemp->porcentajeTotal = ($objTemp->prevalentesTotal * 100) / $objTempTotal->prevalentesTotal;
											$this->estadosMAE[$i] = $objTemp;
										}
										$this->estadosMAE[0] = $objTempTotal;
										
										$objTemp = new GeneradorMunicipiosPrioritarios();
										$this->municipiosPrioritarios = $objTemp->CalcularMunicipios($this->ano, self::$idCatClaEstPacPrevalentes, self::$idCriterioMunicipiosPrioritarios);

										$objTemp = new GeneradorEstudiosContactos();
										$arreglo = $objTemp->CalcularEstudiosContacto($fIniExamenContactos, $fFinExamenContactos, self::$idCatClaEstPacPrevalentes, self::$idCatClaEstPacVigPTX);

										$longitud = count($this->estadosMAE);
										$objTempTotal = $this->estadosMAE[0];
										for ($i = 1; $i < ($longitud - 1); $i++) {

											$EdC = $arreglo[$i];
											$objTemp = $this->estadosMAE[$i];

											$objTemp->contactosPrevalenciaReg = $EdC->registradosPrev;
											$objTemp->contactosPrevalenciaExam = $EdC->examinadosPrev;
											$objTemp->contactosVigPTXReg = $EdC->registradosVig;
											$objTemp->contactosVigPTXExam = $EdC->examinadosVig;
											$objTemp->contactosTotalReg = $EdC->registradosTot;
											$objTemp->contactosTotalExam = $EdC->examinadosTot;

											$objTempTotal->contactosPrevalenciaReg += $EdC->registradosPrev;
											$objTempTotal->contactosPrevalenciaExam += $EdC->examinadosPrev;
											$objTempTotal->contactosVigPTXReg += $EdC->registradosVig;
											$objTempTotal->contactosVigPTXExam += $EdC->examinadosVig;
											$objTempTotal->contactosTotalReg += $EdC->registradosTot;
											$objTempTotal->contactosTotalExam += $EdC->examinadosTot;

											$this->estadosMAE[$i] = $objTemp;
										}
										$this->estadosMAE[0] = $objTempTotal;										
									}
								}
							}
						}						
					}
				}
			}
		}
	}				
}

class GeneradorEstudiosContactos {

	public function CalcularEstudiosContacto($fIni, $fFin, $idCatClaEstPacPrevalentes, $idCatClaEstPacVigPTX) {

		$listadoEstados = array();
		for ($i = 1; $i < 33; $i++) {
			$objTemp = new EstudioDeContactos();
			$objTemp->idCatEstado = $i;
			$listadoEstados[$i] = $objTemp;
		}

		$sql = "( SELECT u.idcatEstado, COUNT(c.idContacto) AS total, 'PREVALENTE' AS tipo " .
			"FROM diagnostico d, contactos c, pacientes p, catUnidad u " .
			"WHERE c.idDiagnostico = d.idDiagnostico " .
			"AND d.idCatEstadoPaciente IN (" . $idCatClaEstPacPrevalentes . ") " .
			"AND p.idPaciente = d.idPaciente " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"GROUP BY u.idcatEstado " .
			") UNION ( " .
			"SELECT u.idcatEstado, COUNT(c.idContacto) AS total, 'VPTX' AS tipo " .
			"FROM diagnostico d, contactos c, pacientes p, catUnidad u " .
			"WHERE c.idDiagnostico = d.idDiagnostico " .
			"AND d.idCatEstadoPaciente IN (" . $idCatClaEstPacVigPTX . ") " .
			"AND p.idPaciente = d.idPaciente " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"GROUP BY u.idcatEstado )";

		$consulta = ejecutaQueryClases($sql);
		if (!is_string($consulta)) {			
			while ($tabla = devuelveRowAssoc($consulta)) {				
				
				$objTemp = $listadoEstados[$tabla["idcatEstado"]];
				$objTemp->registradosTot += $tabla["total"];
				if ($tabla["tipo"] == 'PREVALENTE') $objTemp->registradosPrev = $tabla["total"];
				else $objTemp->registradosVig = $tabla["total"];
								
				$listadoEstados[$i] = $objTemp;
			}
		}

		$sql = "(SELECT u.idcatEstado, COUNT( DISTINCT cc.idContacto) AS total " .
			"FROM diagnostico d, contactos c, pacientes p, catUnidad u, controlContacto cc " .
			"WHERE c.idDiagnostico = d.idDiagnostico " .
			"AND cc.idContacto = c.idContacto " .
			"AND d.idCatEstadoPaciente IN (" . $idCatClaEstPacPrevalentes . ") " .
			"AND p.idPaciente = d.idPaciente " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"AND cc.fecha BETWEEN '" . $fIni . "' AND '" . $fFin . "' " .
			"GROUP BY u.idcatEstado );" .
			/*"UNION " .
			"(SELECT u.idcatEstado, COUNT( DISTINCT eb.idContacto) AS total " .
			"FROM diagnostico d, contactos c, pacientes p, catUnidad u, estudiosBac eb " .
			"WHERE c.idDiagnostico = d.idDiagnostico " .
			"AND eb.idContacto = c.idContacto " .
			"AND eb.idPaciente IS NULL " .
			"AND d.idCatEstadoPaciente IN (" . $idCatClaEstPacPrevalentes . ") " .
			"AND p.idPaciente = d.idPaciente " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"AND eb.fechaResultado BETWEEN '" . $fIni . "' AND '" . $fFin . "' " .
			"GROUP BY u.idcatEstado) " .
			"UNION " .
			"(SELECT u.idcatEstado, COUNT( DISTINCT eh.idContacto) AS total " .
			"FROM diagnostico d, contactos c, pacientes p, catUnidad u, estudiosHis eh " .
			"WHERE c.idDiagnostico = d.idDiagnostico " .
			"AND eh.idContacto = c.idContacto " .
			"AND eh.idPaciente IS NULL " .
			"AND d.idCatEstadoPaciente IN (" . $idCatClaEstPacPrevalentes . ") " .
			"AND p.idPaciente = d.idPaciente " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"AND eh.fechaResultado BETWEEN '" . $fIni . "' AND '" . $fFin . "' " .
			"GROUP BY u.idcatEstado);";*/

		$consulta = ejecutaQueryClases($sql);		
		if (!is_string($consulta)) {			
			while ($tabla = devuelveRowAssoc($consulta)) {
				$key = $tabla["idcatEstado"];
				$objTemp = $listadoEstados[$key];
				$objTemp->examinadosPrev += $tabla["total"];
				$objTemp->examinadosTot += $tabla["total"];
				$listadoEstados[$i] = $objTemp;
			}
		}

		$sql = "(SELECT u.idcatEstado, COUNT( DISTINCT cc.idContacto) AS total " .
			"FROM diagnostico d, contactos c, pacientes p, catUnidad u, controlContacto cc " .
			"WHERE c.idDiagnostico = d.idDiagnostico " .
			"AND cc.idContacto = c.idContacto " .
			"AND d.idCatEstadoPaciente IN (" . $idCatClaEstPacVigPTX . ") " .
			"AND p.idPaciente = d.idPaciente " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"AND cc.fecha BETWEEN '" . $fIni . "' AND '" . $fFin . "' " .
			"GROUP BY u.idcatEstado );";
			/*"UNION " .
			"(SELECT u.idcatEstado, COUNT( DISTINCT eb.idContacto) AS total " .
			"FROM diagnostico d, contactos c, pacientes p, catUnidad u, estudiosBac eb " .
			"WHERE c.idDiagnostico = d.idDiagnostico " .
			"AND eb.idContacto = c.idContacto " .
			"AND eb.idPaciente IS NULL " .
			"AND d.idCatEstadoPaciente IN (" . $idCatClaEstPacVigPTX . ") " .
			"AND p.idPaciente = d.idPaciente " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"AND eb.fechaResultado BETWEEN '" . $fIni . "' AND '" . $fFin . "' " .
			"GROUP BY u.idcatEstado) " .
			"UNION " .
			"(SELECT u.idcatEstado, COUNT( DISTINCT eh.idContacto) AS total " .
			"FROM diagnostico d, contactos c, pacientes p, catUnidad u, estudiosHis eh " .
			"WHERE c.idDiagnostico = d.idDiagnostico " .
			"AND eh.idContacto = c.idContacto " .
			"AND eh.idPaciente IS NULL " .
			"AND d.idCatEstadoPaciente IN (" . $idCatClaEstPacVigPTX . ") " .
			"AND p.idPaciente = d.idPaciente " .
			"AND p.idCatUnidadTratante = u.idCatUnidad " .
			"AND eh.fechaResultado BETWEEN '" . $fIni . "' AND '" . $fFin . "' " .
			"GROUP BY u.idcatEstado);";*/

		$consulta = ejecutaQueryClases($sql);		
		if (!is_string($consulta)) {			
			while ($tabla = devuelveRowAssoc($consulta)) {
				$objTemp = $listadoEstados[$tabla["idCatEstado"]];
				$objTemp->examinadosVig += $tabla["total"];
				$objTemp->examinadosTot += $tabla["total"];
				$listadoEstados[$i] = $objTemp;
			}
		}
		return $listadoEstados;
	}

}

class GeneradorMunicipiosPrioritarios {

	public function CalcularMunicipios($ano, $idCatClaEstPacPrevalentes, $idCriterioMunicipiosPrioritarios) {

		$municipiosPrioritarios = array();
		$sql = "SELECT u.idCatEstado, u.idCatMunicipio, COUNT(d.idDiagnostico) AS total, m.pob" . $ano . " AS pob, m.nombre, e.nombre AS estado " .
			"FROM pacientes p, diagnostico d, catUnidad u, catMunicipio m, catEstado e " .
			"WHERE p.idPaciente = d.idDiagnostico " .
			"AND p.idCatUnidadNotificante = u.idCatUnidad " .
			"AND d.idCatEstadoPaciente IN (" . $idCatClaEstPacPrevalentes . ") " .
			"AND m.idCatMunicipio = u.idCatMunicipio " .
			"AND m.idCatEstado = u.idCatEstado " .
			"AND e.idCatEstado = u.idCatEstado " .
			"GROUP BY u.idCatEstado, u.idCatMunicipio, m.nombre, m.pob" . $ano . ", e.nombre " .
			"ORDER BY u.idCatEstado, u.idCatMunicipio;";
		$consulta = ejecutaQueryClases($sql);
		if (!is_string($consulta)) {
			while ($tabla = devuelveRowAssoc($consulta)) {
				$indice = $tabla["total"] / $tabla["pob"];
				if ($indice > $idCriterioMunicipiosPrioritarios) {
					$objTemp = new MunicipiosPrioritarios();
					$objTemp->idCatEstado = $tabla["idCatEstado"];
					$objTemp->idCatMunicipio = $tabla["idCatMunicipio"];
					$objTemp->estado = $tabla["estado"];
					$objTemp->nombre = $tabla["nombre"];
					$objTemp->enfermos = $tabla["total"];
					$objTemp->tasa = $indice;					
					$key = $tabla["idCatEstado"] . "-" . $tabla["idCatMunicipio"];
					$municipiosPrioritarios[$key] = $objTemp;
				}
			}
		}
		return $municipiosPrioritarios;
	}
}

class EstudioDeContactos {
	
	public $idCatEstado;
	public $registradosPrev = 0;
	public $examinadosPrev = 0;
	public $registradosVig = 0;
	public $examinadosVig = 0;
	public $registradosTot = 0;
	public $examinadosTot = 0;
}

class MunicipiosPrioritarios {

	public $idCatEstado;
	public $idCatMunicipio;
	// VARIABLES DEL REPORTE ******************************************************************************************	
	public $nombre = "Nombre";
	public $estado = "Estado";
	public $enfermos = 0;
	public $tasa = 0;
	// VARIABLES DEL REPORTE ******************************************************************************************	
	public $error = false;
	public $msgError;
}

class EstadosMAE {

	public $idCatEstado;
	// VARIABLES DEL REPORTE ******************************************************************************************	
	public $nombre;
	public $casosNuevosMB = 0;
	public $casosNuevosPB = 0;
	public $casosNuevosSC = 0;
	public $casosNuevosTotal = 0;
	public $formaDeteccionConsulta = 0;
	public $formaDeteccionContacto = 0;
	public $formaDeteccionPoblacion = 0;
	// Grupos de Edad: 1(<1)	2(1 a 4)	3(5 a 14)	4(15 a 24)	5(25 a 44)	6(45 a 64)	7(65 y más)
	public $grupoEdad1H = 0;
	public $grupoEdad1M = 0;
	public $grupoEdad2H = 0;
	public $grupoEdad2M = 0;
	public $grupoEdad3H = 0;
	public $grupoEdad3M = 0;
	public $grupoEdad4H = 0;
	public $grupoEdad4M = 0;
	public $grupoEdad5H = 0;
	public $grupoEdad5M = 0;
	public $grupoEdad6H = 0;
	public $grupoEdad6M = 0;
	public $grupoEdad7H = 0;
	public $grupoEdad7M = 0;
	public $poblacionGeneral = 0;
	public $tazaIncidencia = 0;
	public $gradoDiscapacidad0 = 0;
	public $gradoDiscapacidad1 = 0;
	public $gradoDiscapacidad2 = 0;
	public $gradoDiscapacidadSC = 0;
	public $prevalentesMB = 0;
	public $prevalentesPB = 0;
	public $prevalentesSC = 0;
	public $prevalentesMBSTX = 0;
	public $prevalentesPBSTX = 0;
	public $prevalentesSCSTX = 0;
	public $prevalentesTotal = 0;
	public $tazaPrevalencia = 0;
	public $porcentajeTotal = 0;
	public $contactosPrevalenciaReg = 0;
	public $contactosPrevalenciaExam = 0;
	public $contactosPrevalenciaEnf = 0;
	public $contactosVigPTXReg = 0;
	public $contactosVigPTXExam = 0;
	public $contactosVigPTXEnf = 0;
	public $contactosTotalReg = 0;
	public $contactosTotalExam = 0;
	public $contactosTotalEnf = 0;
	// VARIABLES DEL REPORTE ******************************************************************************************	
	public $error = false;
	public $msgError;

}
?>