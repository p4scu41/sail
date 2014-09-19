<?php

class ReporteListadoGeneral {

	// VARIABLES DE ENTRADA ************************************************************************
	public $idCatEstado;
	
	// VARIABLES DEL REPORTE ******************************************************************************************
	public $arrJurisdicciones = array();
	public $arrPacientesReporteListadoGeneral = array();
	// VARIABLES DEL REPORTE ******************************************************************************************
	public $error = false;
	public $msgError;
	
	public function generarReporte() {

		if (is_null($this->idCatEstado)) {
			$this->error = true;
			$this->msgError = "El reporte requiere del identificador de estado.";
		} else {
			$sql = "SELECT * FROM [lepra].[dbo].[catJurisdiccion] WHERE 1 = 1 AND idCatEstado = " . $this->idCatEstado . " ORDER BY idCatJurisdiccion ASC;";		
			$consulta = ejecutaQueryClases($sql);
			//echo $sql;
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {		
				while ($tabla = devuelveRowAssoc($consulta)) $this->arrJurisdicciones[$tabla["idCatJurisdiccion"]] = $tabla["nombre"];
				
				$sql = "SELECT p.idPaciente, m.idCatJurisdiccion" .
					" FROM [lepra].[dbo].[pacientes] p, [lepra].[dbo].[catMunicipio] m, [lepra].[dbo].[diagnostico] d " .
					" WHERE m.idCatMunicipio = p.idCatMunicipio" .
					" AND d.idPaciente =  p.idPaciente " .
					" AND m.idCatEstado = p.idCatEstado " .
					" AND m.idCatEstado = " . $this->idCatEstado .
					" ORDER BY idCatJurisdiccion ASC;";
				$consulta = ejecutaQueryClases($sql);
				//echo $sql;
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql;
				} else {
					$contador = 1;
					while ($tabla = devuelveRowAssoc($consulta)) {
						$idTemp = $tabla["idPaciente"];
						$objTemp = new PacienteReporteListadoGeneral();
						$objTemp->obtenerBD($idTemp);
						$objTemp->id = $contador;
						//$this->arrPacientesReporteListadoGeneral[$tabla["idCatJurisdiccion"]] = $objTemp;
						//$this->arrPacientesReporteListadoGeneral[$tabla["idPaciente"]] = $objTemp;
						array_push($this->arrPacientesReporteListadoGeneral, $objTemp);
						$contador += 1;
					}
				}
			}
		}			
	}

	public function imprimirReporte() {

		$longitud = count($this->arrPacientesReporteListadoGeneral);
		$JurisAct = -1;
        $band = true;
		
		//echo '<TABLE>';
		for ($i = 0; $i < $longitud; $i++) {			
			$objTemp = $this->arrPacientesReporteListadoGeneral[$i];
			//var_dump($objTemp);
			if ($JurisAct != $objTemp->jurisdiccion) {
				$JurisAct = $objTemp->jurisdiccion;
				echo '<BR><BR><strong>Jurisdiccion Sanitaria ' . $objTemp->jurisdiccion . ' : ' . $this->arrJurisdicciones[$objTemp->jurisdiccion] . '</strong>';
				echo '<div class="datagrid"><TABLE><thead><TR align="center"><TH>Folio</TH><TH>Fecha Notificaci&oacute;n</TH><TH>Fecha de Captura</TH><TH>Tipo Paciente</TH>
                    <TH>Forma de Detecci&oacute;n</TH><TH>Nombre</TH><TH>Fecha de Nacimiento</TH><TH>Edad</TH><TH>Sexo</TH><TH>Estado</TH><TH>Municipio</TH>
                    <TH>Domicilio y Localidad</TH><TH>Tiempo de radicar</TH><TH>Fecha Inicio Sintomas</TH><TH>Fecha Diagnostico</TH><TH>Manchas Hipocrom.</TH>
                    <TH>Manchas Rojizas</TH><TH>Placas Infilt.</TH><TH>N&oacute;dulos</TH><TH>Otras</TH><TH>Zonas de Anestesia</TH><TH>Fecha de BK</TH><TH>IB</TH>
                    <TH>IM%</TH><TH>Fecha de Histo</TH><TH>Res</TH><TH>Observaciones</TH></TR></thead>';
                $band = true;
			} else {
                $band = false;
            }
			echo '<TR><TD>'.$objTemp->folio.
				'</TD><TD>'.formatFechaObj($objTemp->fechaNotificacion, 'd-m-Y').
				'</TD><TD>'.formatFechaObj($objTemp->fechaCaptura, 'd-m-Y').
				'</TD><TD>'.$objTemp->idCatTipoPaciente.
				'</TD><TD>'.$objTemp->idCatFormaDeteccion.
				'</TD><TD>'.$objTemp->nombreCompleto.
				'</TD><TD>'.formatFechaObj($objTemp->fechaNacimiento, 'd-m-Y').
				'</TD><TD>'.$objTemp->edad.
				'</TD><TD>'.$objTemp->sexo.
				'</TD><TD>'.$objTemp->idCatEstado.
				'</TD><TD>'.$objTemp->idCatMunicipio.
				'</TD><TD>'.$objTemp->domicilio.
				'</TD><TD>A:'.$objTemp->anosRadicando.' M:'.$objTemp->mesesRadicando.
				'</TD><TD>'.formatFechaObj($objTemp->fechaInicioPadecimiento, 'd-m-Y').
				'</TD><TD>'.formatFechaObj($objTemp->fechaDiagnostico, 'd-m-Y').
				'</TD><TD align="center">'.$objTemp->cuentaManchasHipocrom.
				'</TD><TD align="center">'.$objTemp->cuentaManchasEritemat.
				'</TD><TD align="center">'.$objTemp->cuentaPlacasInfiltrad.
				'</TD><TD align="center">'.$objTemp->cuentaNodulos.
				'</TD><TD align="center">'.$objTemp->cuentaOtrasLesiones.
				'</TD><TD align="center">'.$objTemp->cuentaZonasAnestesia.
				'</TD><TD>'.formatFechaObj($objTemp->fechaBaciloscopia, 'd-m-Y').
				'</TD><TD align="center">'.$objTemp->resultadoBaciloscopia.
				'</TD><TD align="center">'.$objTemp->imBaciloscopia.
				'</TD><TD>'.formatFechaObj($objTemp->fechaHistopatologia, 'd-m-Y').
				'</TD><TD>'.$objTemp->resultadoHistopatologia.
				'</TD><TD>'.$objTemp->observaciones.
				'</TD></TR>';
            
            if($this->arrPacientesReporteListadoGeneral[$i+1]) {
                if($JurisAct != $this->arrPacientesReporteListadoGeneral[$i+1]->jurisdiccion) {
                    echo '</TABLE></div>';
                }
            } else {
                echo '</TABLE></div>';
            }
		}
		//echo '</TABLE></div>';
	}
}

class PacienteReporteListadoGeneral {
	
	// BD ******************************************************************************************
	static $idNodulosAislados = 1;
	static $idNodulosAgrupados = 2;
	static $idManchasHipocrom = 3;
	static $idManchasEritemat = 4;
	static $idPlacasInfiltrad = 5;
	static $idZonasAnestesia = 6;
	static $idOtrasLesiones = 7;
	static $idCatTipoEstudioDia = 1;				// TABLA catTipoEstudio Diagnostico
	static $idCatTipoEstudioCon = 2;				//						Control
	// BD ******************************************************************************************

	public $idPaciente;							// idPaciente de la BD
	public $idDiagnostico;						// idDiagnostico de la BD	
	// VARIABLES DEL REPORTE ******************************************************************************************
	public $id = 0;
	public $folio;								// Paciente
	public $fechaNotificacion;					// Paciente
	public $fechaCaptura;						// Diagnostico
	public $idCatTipoPaciente;					// Paciente
	public $idCatFormaDeteccion;				// Paciente
	public $nombreCompleto;
	public $fechaNacimiento;					// Paciente
	public $edad;
	public $sexo;								// Paciente
	public $idCatEstado;						// Paciente
	public $idCatMunicipio;						// Paciente
	public $jurisdiccion;
	public $domicilio;
	public $anosRadicando;						// Paciente
	public $mesesRadicando;						// Paciente
	public $fechaInicioPadecimiento;			// Paciente
	public $fechaDiagnostico;					// Paciente
	public $cuentaManchasHipocrom;
	public $cuentaManchasEritemat;
	public $cuentaPlacasInfiltrad;
	public $cuentaNodulos;
	public $cuentaOtrasLesiones;
	public $cuentaZonasAnestesia;
	public $fechaBaciloscopia;
	public $resultadoBaciloscopia;
	public $imBaciloscopia;
	public $fechaHistopatologia;
	public $resultadoHistopatologia;
	public $observaciones;
	// VARIABLES DEL REPORTE ******************************************************************************************
	public $error = false;
	public $msgError;

	public function obtenerBD($idPaciente) {

		$sql = "SELECT p.*, m.idCatJurisdiccion, d.idDiagnostico, d.fechaCaptura, ctp.descripcion as tipoPaciente, s.sexo as sexoP,  cfd.descripcion as formaDeteccion, ce.nombre as estado, cl.nombre as localidad" .
			" FROM [lepra].[dbo].[pacientes] p, [lepra].[dbo].[catMunicipio] m, [lepra].[dbo].[diagnostico] d, [lepra].[dbo].[catTipoPaciente] ctp, [lepra].[dbo].[catSexo] s, [lepra].[dbo].[catFormaDeteccion] cfd, [lepra].[dbo].[catEstado] ce, [lepra].[dbo].[catLocalidad] cl" .
			" WHERE p.idPaciente = " . $idPaciente . 
			" AND d.idPaciente = p.idPaciente" .
			" AND m.idCatMunicipio = p.idCatMunicipio" .
			" AND m.idCatEstado = p.idCatEstado" .
			" AND ctp.idCatTipoPaciente = p.idCatTipoPaciente" .
			" AND s.idSexo = p.sexo" .
			" AND cfd.idCatFormaDeteccion = p.idCatFormaDeteccion" .
			" AND ce.idCatEstado = p.idCatEstado" .
			" AND cl.idCatEstado = p.idCatEstado" .
			" AND cl.idCatMunicipio = p.idCatMunicipio" .
			" AND cl.idCatLocalidad = p.idCatLocalidad;";
		$consulta = ejecutaQueryClases($sql);
		//echo $sql;
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {		
			$tabla = devuelveRowAssoc($consulta);
			$this->idPaciente =  $tabla["idPaciente"];
			$this->idDiagnostico = $tabla["idDiagnostico"];

			$this->folio = $tabla["cveExpediente"];
			$this->fechaNotificacion =  formatFechaObj($tabla["fechaNotificacion"], 'Y-m-d');
			$this->fechaCaptura = formatFechaObj($tabla["fechaCaptura"], 'Y-m-d');
			$this->idCatTipoPaciente =  $tabla["tipoPaciente"];
			$this->idCatFormaDeteccion =  $tabla["formaDeteccion"];
			$this->nombreCompleto =  $tabla["nombre"] . ' ' . $tabla["apellidoPaterno"] . ' ' . $tabla["apellidoMaterno"];
			$this->fechaNacimiento =  formatFechaObj($tabla["fechaNacimiento"], 'Y-m-d');
			$this->edad = calEdad(formatFechaObj($this->fechaNacimiento, 'Y-m-d'));
			$this->sexo =  $tabla["sexoP"];
			$this->idCatEstado =  $tabla["estado"];
			$this->idCatMunicipio =  $tabla["localidad"];
			$this->jurisdiccion = $tabla["idCatJurisdiccion"];
			$this->domicilio = $tabla["calle"] . ' ' . $tabla["noExterior"] . ' ' . $tabla["noInterior"] . ' ' . $tabla["colonia"];
			if (!is_null($tabla["anosRadicando"])) { $this->anosRadicando =  $tabla["anosRadicando"]; }
			if (!is_null($tabla["mesesRadicando"])) { $this->mesesRadicando =  $tabla["mesesRadicando"]; }
			$this->fechaInicioPadecimiento =  formatFechaObj($tabla["fechaInicioPadecimiento"], 'Y-m-d');
			$this->fechaDiagnostico =  formatFechaObj($tabla["fechaDiagnostico"], 'Y-m-d');

			
			$sql = "SELECT " .
				"(SELECT COUNT(idLesion) FROM [lepra].[dbo].[diagramaDermatologico] WHERE idDiagnostico = " . $this->idDiagnostico . " AND idCatTipoLesion = " . self::$idManchasHipocrom . " ) AS cMH, " .
				"(SELECT COUNT(idLesion) FROM [lepra].[dbo].[diagramaDermatologico] WHERE idDiagnostico = " . $this->idDiagnostico . " AND idCatTipoLesion = " . self::$idManchasEritemat . " ) AS cME, " .
				"(SELECT COUNT(idLesion) FROM [lepra].[dbo].[diagramaDermatologico] WHERE idDiagnostico = " . $this->idDiagnostico . " AND idCatTipoLesion = " . self::$idPlacasInfiltrad . " ) AS cPI, " .
				"(SELECT COUNT(idLesion) FROM [lepra].[dbo].[diagramaDermatologico] WHERE idDiagnostico = " . $this->idDiagnostico . " AND (idCatTipoLesion = " . self::$idNodulosAislados . " OR idCatTipoLesion = " . self::$idNodulosAgrupados . ")) AS cNo, " .
				"(SELECT COUNT(idLesion) FROM [lepra].[dbo].[diagramaDermatologico] WHERE idDiagnostico = " . $this->idDiagnostico . " AND idCatTipoLesion = " . self::$idOtrasLesiones . " ) AS cOL, " .
				"(SELECT COUNT(idLesion) FROM [lepra].[dbo].[diagramaDermatologico] WHERE idDiagnostico = " . $this->idDiagnostico . " AND idCatTipoLesion = " . self::$idZonasAnestesia . " ) AS cZA;";

			$consulta = ejecutaQueryClases($sql);
			//echo "<BR><BR>" . $sql . "<BR><BR>";
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$tabla = devuelveRowAssoc($consulta);
				$this->cuentaManchasHipocrom = $tabla["cMH"];
				$this->cuentaManchasEritemat = $tabla["cME"];
				$this->cuentaPlacasInfiltrad = $tabla["cPI"];
				$this->cuentaNodulos = $tabla["cNo"];
				$this->cuentaOtrasLesiones = $tabla["cOL"];
				$this->cuentaZonasAnestesia = $tabla["cZA"];
			}			

			$sql = "SELECT TOP 1 muestraRechazada, fechaSolicitud, fechaResultado, bacIM, bacPorcViaFrotis1, bacPorcViaFrotis2, bacPorcViaFrotis3 
				FROM [lepra].[dbo].[estudiosBac] 
				WHERE idDiagnostico = " . $this->idDiagnostico . " 
				AND muestraRechazada = 0 
				AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia  . " 
				ORDER BY fechaResultado ASC;";
			$consulta = ejecutaQueryClases($sql);
			//echo "<BR><BR>" . $sql . "<BR><BR>";
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$tabla = devuelveRowAssoc($consulta);
				if (is_null($tabla)) { 
					$this->fechaBaciloscopia = "-";
					$this->resultadoBaciloscopia = "Sin Estudio";
					$this->imBaciloscopia = "Sin Estudio";					
				} else {
					if (!is_null($tabla["fechaResultado"])) { $this->fechaBaciloscopia =  formatFechaObj($tabla["fechaResultado"], 'Y-m-d'); } else { $this->fechaBaciloscopia =  formatFechaObj($tabla["fechaSolicitud"], 'Y-m-d'); }
					if (!is_null($tabla["bacPorcViaFrotis1"])) { $this->resultadoBaciloscopia = $tabla["bacPorcViaFrotis1"]; } else { $this->resultadoBaciloscopia = "Esperando Resultado"; }
					if (!is_null($tabla["bacIM"])) { $this->imBaciloscopia = $tabla["bacIM"]; } else { $this->imBaciloscopia = "Esperando Resultado"; }
				}
			}

			$sql = "SELECT muestraRechazada, fechaSolicitud, fechaResultado, hisResultado 
				FROM [lepra].[dbo].[estudiosHis] 
				WHERE idDiagnostico = " . $this->idDiagnostico . " 
				AND muestraRechazada = 0 
				AND idCatTipoEstudio = " . self::$idCatTipoEstudioDia  . " 				
				ORDER BY fechaResultado ASC;";
			$consulta = ejecutaQueryClases($sql);
			//echo "<BR><BR>" . $sql . "<BR><BR>";
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$tabla = devuelveRowAssoc($consulta);
				if (is_null($tabla)) { 
					$this->resultadoHistopatologia = "Sin Estudio";
					$this->fechaHistopatologia =  "-";
				} else {
					if (!is_null($tabla["fechaResultado"])) { $this->fechaHistopatologia =  formatFechaObj($tabla["fechaResultado"], 'Y-m-d'); } else { $this->fechaHistopatologia =  formatFechaObj($tabla["fechaSolicitud"], 'Y-m-d'); }
					if (!is_null($tabla["hisResultado"])) { $this->resultadoHistopatologia = $tabla["hisResultado"]; } else { $this->fechaHistopatologia =  "Esperando Resultado"; }										
				}
									
			}
			$this->observaciones;		
		}		
	}
}


?>
