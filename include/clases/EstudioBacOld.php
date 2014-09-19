<?php
class EstudioBac {

	public $idEstudioBac = 0;				// int
	public $idDiagnostico;					// int					OJO!!! Este campo es 0 cuando el paciente es SOSPECHOSO, se actualiza al IDDIAGNOSTICO para el resto de casos
	public $fechaSolicitud;					// date	
	////////////							Posibles nulos		
	public $idContacto;						// int					OJO!!! Este campo es usado UNICAMENTE cuando el estudio pertenece a un CONTACTO, NULL para el resto de casos
	public $idPaciente;						// int					OJO!!! Este campo es usado UNICAMENTE cuando el paciente es SOSPECHOSO, NULL para el resto de casos
	public $fechaRecepcion;					// date	
	public $folioLaboratorio;				// varchar(10)
	public $folioSolicitud;					// int AUTOINCREMENTAL
	public $idCatSolicitante;				// int
	public $idCatTipoEstudio;				// int
	public $tomMueFrotis1;					// bit
	public $tomMueFrotis2;					// bit
	public $tomMueFrotis3;					// bit
	public $lesionTomoMuestra;				// varchar(50)
	public $regionTomoMuestra;				// varchar(50)
	public $fechaTomaMuestra;				// date
	public $personaTomaMuestra;				// varchar(200)
	public $fechaSolicitudEstudio;			// date
	public $personaSolicitudEstudio;		// varchar(50)
	public $muestraRechazada;				// bit					// OJO!!!! PEGAR COMO 1 o 0 EL VALOR.  1 para TRUE, 0 para FALSE
	public $idCatMotivoRechazo;				// int
	public $otroMotivoRechazo;				// varchar(200)
	public $fechaResultado;					// date
	public $idCatBacFrotis1;				// int
	public $idCatBacFrotis2;				// int
	public $idCatBacFrotis3;				// int
	public $bacPorcViaFrotis1;				// float
	public $bacPorcViaFrotis2;				// float
	public $bacPorcViaFrotis3;				// float
	public $bacCalidadAdecFrotis1;			// bit					// OJO!!!! PEGAR COMO 1 o 0 EL VALOR.  1 para TRUE, 0 para FALSE
	public $bacCalidadAdecFrotis2;			// bit					// OJO!!!! PEGAR COMO 1 o 0 EL VALOR.  1 para TRUE, 0 para FALSE
	public $bacCalidadAdecFrotis3;			// bit					// OJO!!!! PEGAR COMO 1 o 0 EL VALOR.  1 para TRUE, 0 para FALSE
	public $bacIdCatTiposBacilosFrotis1;	// int
	public $bacIdCatTiposBacilosFrotis2;	// int
	public $bacIdCatTiposBacilosFrotis3;	// int	
	public $idCatBac;						// int	
	public $bacIM;							// float
	public $bacObservaciones;				// varchar(300)	
	public $idCatEstadoLaboratorio;			// int
	public $idCatJurisdiccionLaboratorio;	// int
	public $idCatAnalistaLab;				// int
	public $idCatSupervisorLab;				// int

	public $error = false;
	public $msgError;

	public function insertarBD() {
		$sqlA = "INSERT INTO [estudiosBac] ([idDiagnostico], [fechaSolicitud]";
		$sqlB = "VALUES (" . $this->idDiagnostico . ", '" . formatFechaObj($this->fechaSolicitud, 'Y-m-d') . "'";
		
		if($this->idContacto != '' && !is_null($this->idContacto)) { $sqlA .= ", [idContacto]"; $sqlB .= ", " . $this->idContacto; }
		if($this->idPaciente != '' && !is_null($this->idPaciente)) { $sqlA .= ", [idPaciente]"; $sqlB .= ", " . $this->idPaciente; }
		if($this->fechaRecepcion != '' && !is_null($this->fechaRecepcion)) { $sqlA .= ", [fechaRecepcion]"; $sqlB .= ", '" . formatFechaObj($this->fechaRecepcion, 'Y-m-d') . "'"; }
		if($this->folioLaboratorio != '' && !is_null($this->folioLaboratorio)) { $sqlA .= ", [folioLaboratorio]"; $sqlB .= ", '" . $this->folioLaboratorio . "'"; }
		if($this->idCatSolicitante != '' && !is_null($this->idCatSolicitante)) { $sqlA .= ", [idCatSolicitante]"; $sqlB .= ", '" . $this->idCatSolicitante . "'"; }
		if($this->idCatTipoEstudio != '' && !is_null($this->idCatTipoEstudio)) { $sqlA .= ", [idCatTipoEstudio]"; $sqlB .= ", " . $this->idCatTipoEstudio; }
		if($this->tomMueFrotis1 != '' && !is_null($this->tomMueFrotis1)) { $sqlA .= ", [tomMueFrotis1]"; $sqlB .= ", " . $this->tomMueFrotis1; }
		if($this->tomMueFrotis2 != '' && !is_null($this->tomMueFrotis2)) { $sqlA .= ", [tomMueFrotis2]"; $sqlB .= ", " . $this->tomMueFrotis2; }
		if($this->tomMueFrotis3 != '' && !is_null($this->tomMueFrotis3)) { $sqlA .= ", [tomMueFrotis3]"; $sqlB .= ", " . $this->tomMueFrotis3; }		
		if($this->lesionTomoMuestra != '' && !is_null($this->lesionTomoMuestra)) { $sqlA .= ", [lesionTomoMuestra]"; $sqlB .= ", '" . $this->lesionTomoMuestra . "'"; }
		if($this->regionTomoMuestra != '' && !is_null($this->regionTomoMuestra)) { $sqlA .= ", [regionTomoMuestra]"; $sqlB .= ", '" . $this->regionTomoMuestra . "'"; }
		if($this->fechaTomaMuestra != '' && !is_null($this->fechaTomaMuestra)) { $sqlA .= ", [fechaTomaMuestra]"; $sqlB .= ", '" . formatFechaObj($this->fechaTomaMuestra, 'Y-m-d') . "'"; }
		if($this->personaTomaMuestra != '' && !is_null($this->personaTomaMuestra)) { $sqlA .= ", [personaTomaMuestra]"; $sqlB .= ", '" . $this->personaTomaMuestra . "'"; }
		if($this->fechaSolicitudEstudio != '' && !is_null($this->fechaSolicitudEstudio)) { $sqlA .= ", [fechaSolicitudEstudio]"; $sqlB .= ", '" . formatFechaObj($this->fechaSolicitudEstudio, 'Y-m-d') . "'"; }
		if($this->personaSolicitudEstudio != '' && !is_null($this->personaSolicitudEstudio)) { $sqlA .= ", [personaSolicitudEstudio]"; $sqlB .= ", '" . $this->personaSolicitudEstudio . "'"; }
		if($this->muestraRechazada != '' && !is_null($this->muestraRechazada)) { $sqlA .= ", [muestraRechazada]"; $sqlB .= ", " . $this->muestraRechazada; }
		if($this->idCatMotivoRechazo != '' && !is_null($this->idCatMotivoRechazo)) { $sqlA .= ", [idCatMotivoRechazo]"; $sqlB .= ", " . $this->idCatMotivoRechazo; }
		if($this->otroMotivoRechazo != '' && !is_null($this->otroMotivoRechazo)) { $sqlA .= ", [otroMotivoRechazo]"; $sqlB .= ", '" . $this->otroMotivoRechazo . "'"; }
		if($this->fechaResultado != '' && !is_null($this->fechaResultado)) { $sqlA .= ", [fechaResultado]"; $sqlB .= ", '" . formatFechaObj($this->fechaResultado, 'Y-m-d') . "'"; }
		if($this->idCatBacFrotis1 != '' && !is_null($this->idCatBacFrotis1)) { $sqlA .= ", [idCatBacFrotis1]"; $sqlB .= ", " . $this->idCatBacFrotis1; }
		if($this->idCatBacFrotis2 != '' && !is_null($this->idCatBacFrotis2)) { $sqlA .= ", [idCatBacFrotis2]"; $sqlB .= ", " . $this->idCatBacFrotis2; }
		if($this->idCatBacFrotis3 != '' && !is_null($this->idCatBacFrotis3)) { $sqlA .= ", [idCatBacFrotis3]"; $sqlB .= ", " . $this->idCatBacFrotis3; }
		if($this->bacPorcViaFrotis1 != '' && !is_null($this->bacPorcViaFrotis1)) { $sqlA .= ", [bacPorcViaFrotis1]"; $sqlB .= ", " . $this->bacPorcViaFrotis1; }
		if($this->bacPorcViaFrotis2 != '' && !is_null($this->bacPorcViaFrotis2)) { $sqlA .= ", [bacPorcViaFrotis2]"; $sqlB .= ", " . $this->bacPorcViaFrotis2; }
		if($this->bacPorcViaFrotis3 != '' && !is_null($this->bacPorcViaFrotis3)) { $sqlA .= ", [bacPorcViaFrotis3]"; $sqlB .= ", " . $this->bacPorcViaFrotis3; }
		if($this->bacCalidadAdecFrotis1 != '' && !is_null($this->bacCalidadAdecFrotis1)) { $sqlA .= ", [bacCalidadAdecFrotis1]"; $sqlB .= ", " . $this->bacCalidadAdecFrotis1; }
		if($this->bacCalidadAdecFrotis2 != '' && !is_null($this->bacCalidadAdecFrotis2)) { $sqlA .= ", [bacCalidadAdecFrotis2]"; $sqlB .= ", " . $this->bacCalidadAdecFrotis2; }
		if($this->bacCalidadAdecFrotis3 != '' && !is_null($this->bacCalidadAdecFrotis3)) { $sqlA .= ", [bacCalidadAdecFrotis3]"; $sqlB .= ", " . $this->bacCalidadAdecFrotis3; }		
		if($this->bacIdCatTiposBacilosFrotis1 != '' && !is_null($this->bacIdCatTiposBacilosFrotis1)) { $sqlA .= ", [bacIdCatTiposBacilosFrotis1]"; $sqlB .= ", " . $this->bacIdCatTiposBacilosFrotis1; }
		if($this->bacIdCatTiposBacilosFrotis2 != '' && !is_null($this->bacIdCatTiposBacilosFrotis2)) { $sqlA .= ", [bacIdCatTiposBacilosFrotis2]"; $sqlB .= ", " . $this->bacIdCatTiposBacilosFrotis2; }
		if($this->bacIdCatTiposBacilosFrotis3 != '' && !is_null($this->bacIdCatTiposBacilosFrotis3)) { $sqlA .= ", [bacIdCatTiposBacilosFrotis3]"; $sqlB .= ", " . $this->bacIdCatTiposBacilosFrotis3; }
		if($this->idCatBac != '' && !is_null($this->idCatBac)) { $sqlA .= ", [idCatBac]"; $sqlB .= ", " . $this->idCatBac; }
		if($this->bacIM != '' && !is_null($this->bacIM)) { $sqlA .= ", [bacIM]"; $sqlB .= ", " . $this->bacIM; }
		if($this->bacObservaciones != '' && !is_null($this->bacObservaciones)) { $sqlA .= ", [bacObservaciones]"; $sqlB .= ", '" . $this->bacObservaciones . "'"; }
		if($this->idCatEstadoLaboratorio != '' && !is_null($this->idCatEstadoLaboratorio)) { $sqlA .= ", [idCatEstadoLaboratorio]"; $sqlB .= ", " . $this->idCatEstadoLaboratorio; }
		if($this->idCatJurisdiccionLaboratorio != '' && !is_null($this->idCatJurisdiccionLaboratorio)) { $sqlA .= ", [idCatJurisdiccionLaboratorio]"; $sqlB .= ", " . $this->idCatJurisdiccionLaboratorio; }
		if($this->idCatAnalistaLab != '' && !is_null($this->idCatAnalistaLab)) { $sqlA .= ", [idCatAnalistaLab]"; $sqlB .= ", " . $this->idCatAnalistaLab; }
		if($this->idCatSupervisorLab != '' && !is_null($this->idCatSupervisorLab)) { $sqlA .= ", [idCatSupervisorLab]"; $sqlB .= ", " . $this->idCatSupervisorLab; }		
		$sqlA .= ") " . $sqlB . "); SELECT @@Identity AS nuevoId;";	
		$consulta = ejecutaQueryClases($sqlA);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idEstudioBac = $tabla["nuevoId"];
		}
	}

	public function modificarBD() {
		$sql = "UPDATE [estudiosBac] SET ";
		$sql .= " [idDiagnostico] = " . $this->idDiagnostico . " ";
		if($this->idContacto != '' && !is_null($this->idContacto)) { $sql .= ",[idContacto] = " . $this->idContacto . " "; }
		if($this->idPaciente != '' && !is_null($this->idPaciente)) { $sql .= ",[idPaciente] = " . $this->idPaciente . " "; }
		if($this->fechaRecepcion != '' && !is_null($this->fechaRecepcion)) {	$sql .= ",[fechaRecepcion] = '" . formatFechaObj($this->fechaRecepcion, 'Y-m-d') . "' "; }
		if($this->folioLaboratorio != '' && !is_null($this->folioLaboratorio)) { $sql .= ",[folioLaboratorio] = '" . $this->folioLaboratorio . "' "; }
		if($this->idCatSolicitante != '' && !is_null($this->idCatSolicitante)) { $sql .= ",[idCatSolicitante] = '" . $this->idCatSolicitante . "' "; }
		if($this->idCatTipoEstudio != '' && !is_null($this->idCatTipoEstudio)) { $sql .= ",[idCatTipoEstudio] = " . $this->idCatTipoEstudio . " "; }
		if($this->tomMueFrotis1 != '' && !is_null($this->tomMueFrotis1)) { $sql .= ",[tomMueFrotis1] = " . $this->tomMueFrotis1 . " "; }
		if($this->tomMueFrotis2 != '' && !is_null($this->tomMueFrotis2)) { $sql .= ",[tomMueFrotis2] = " . $this->tomMueFrotis2 . " "; }
		if($this->tomMueFrotis3 != '' && !is_null($this->tomMueFrotis3)) { $sql .= ",[tomMueFrotis3] = " . $this->tomMueFrotis3 . " "; }
		if($this->lesionTomoMuestra != '' && !is_null($this->lesionTomoMuestra)) {	$sql .= ",[lesionTomoMuestra] = '" . $this->lesionTomoMuestra . "' "; }
		if($this->regionTomoMuestra != '' && !is_null($this->regionTomoMuestra)) {	$sql .= ",[regionTomoMuestra] = '" . $this->regionTomoMuestra . "' "; }
		if($this->fechaTomaMuestra != '' && !is_null($this->fechaTomaMuestra)) { $sql .= ",[fechaTomaMuestra] = '" . formatFechaObj($this->fechaTomaMuestra, 'Y-m-d') . "' "; }
		if($this->personaTomaMuestra != '' && !is_null($this->personaTomaMuestra)) {	$sql .= ",[personaTomaMuestra] = '" . $this->personaTomaMuestra . "' "; }
		if($this->fechaSolicitudEstudio != '' && !is_null($this->fechaSolicitudEstudio)) {	$sql .= ",[fechaSolicitudEstudio] = '" . formatFechaObj($this->fechaSolicitudEstudio, 'Y-m-d') . "' "; }
		if($this->personaSolicitudEstudio != '' && !is_null($this->personaSolicitudEstudio)) {	$sql .= ",[personaSolicitudEstudio] = '" . $this->personaSolicitudEstudio . "' "; }
		if($this->muestraRechazada != '' && !is_null($this->muestraRechazada)) {	$sql .= ",[muestraRechazada] = " . $this->muestraRechazada . " "; }
		if($this->idCatMotivoRechazo  != '' && !is_null($this->idCatMotivoRechazo )) {	$sql .= ",[idCatMotivoRechazo] = " . $this->idCatMotivoRechazo . " "; }
		if($this->otroMotivoRechazo != '' && !is_null($this->otroMotivoRechazo)) {	$sql .= ",[otroMotivoRechazo] = '" . $this->otroMotivoRechazo . "' "; }
		if($this->fechaResultado != '' && !is_null($this->fechaResultado)) {	$sql .= ",[fechaResultado] = '" . formatFechaObj($this->fechaResultado, 'Y-m-d') . "' "; }
		if($this->idCatBacFrotis1 != '' && !is_null($this->idCatBacFrotis1)) {	$sql .= ",[idCatBacFrotis1] = " . $this->idCatBacFrotis1 . " "; }
		if($this->idCatBacFrotis2 != '' && !is_null($this->idCatBacFrotis2)) {	$sql .= ",[idCatBacFrotis2] = " . $this->idCatBacFrotis2 . " "; }
		if($this->idCatBacFrotis3 != '' && !is_null($this->idCatBacFrotis3)) {	$sql .= ",[idCatBacFrotis3] = " . $this->idCatBacFrotis3 . " "; }
		if($this->bacPorcViaFrotis1 != '' && !is_null($this->bacPorcViaFrotis1)) {	$sql .= ",[bacPorcViaFrotis1] = " . $this->bacPorcViaFrotis1 . " "; }
		if($this->bacPorcViaFrotis2 != '' && !is_null($this->bacPorcViaFrotis2)) {	$sql .= ",[bacPorcViaFrotis2] = " . $this->bacPorcViaFrotis2 . " "; }
		if($this->bacPorcViaFrotis3 != '' && !is_null($this->bacPorcViaFrotis3)) {	$sql .= ",[bacPorcViaFrotis3] = " . $this->bacPorcViaFrotis3 . " "; }
		if($this->bacCalidadAdecFrotis1 != '' && !is_null($this->bacCalidadAdecFrotis1)) {	$sql .= ",[bacCalidadAdecFrotis1] = " . $this->bacCalidadAdecFrotis1 . " "; }
		if($this->bacCalidadAdecFrotis2 != '' && !is_null($this->bacCalidadAdecFrotis2)) {	$sql .= ",[bacCalidadAdecFrotis2] = " . $this->bacCalidadAdecFrotis2 . " "; }
		if($this->bacCalidadAdecFrotis3 != '' && !is_null($this->bacCalidadAdecFrotis3)) {	$sql .= ",[bacCalidadAdecFrotis3] = " . $this->bacCalidadAdecFrotis3 . " "; }		
		if($this->bacIdCatTiposBacilosFrotis1 != '' && !is_null($this->bacIdCatTiposBacilosFrotis1)) {	$sql .= ",[bacIdCatTiposBacilosFrotis1] = " . $this->bacIdCatTiposBacilosFrotis1 . " "; }
		if($this->bacIdCatTiposBacilosFrotis2 != '' && !is_null($this->bacIdCatTiposBacilosFrotis2)) {	$sql .= ",[bacIdCatTiposBacilosFrotis2] = " . $this->bacIdCatTiposBacilosFrotis2 . " "; }
		if($this->bacIdCatTiposBacilosFrotis3 != '' && !is_null($this->bacIdCatTiposBacilosFrotis3)) {	$sql .= ",[bacIdCatTiposBacilosFrotis3] = " . $this->bacIdCatTiposBacilosFrotis3 . " "; }
		if($this->idCatBac != '' && !is_null($this->idCatBac)) {	$sql .= ",[idCatBac] = " . $this->idCatBac . " "; }
		if($this->bacIM != '' && !is_null($this->bacIM)) {	$sql .= ",[bacIM] = " . $this->bacIM . " "; }		
		if($this->bacObservaciones != '' && !is_null($this->bacObservaciones)) {	$sql .= ",[bacObservaciones] = '" . $this->bacObservaciones . "' "; }		
		if($this->idCatEstadoLaboratorio != '' && !is_null($this->idCatEstadoLaboratorio)) {	$sql .= ",[idCatEstadoLaboratorio] = " . $this->idCatEstadoLaboratorio . " "; }
		if($this->idCatJurisdiccionLaboratorio != '' && !is_null($this->idCatJurisdiccionLaboratorio)) {	$sql .= ",[idCatJurisdiccionLaboratorio] = " . $this->idCatJurisdiccionLaboratorio . " "; }
		if($this->idCatAnalistaLab != '' && !is_null($this->idCatAnalistaLab)) {	$sql .= ",[idCatAnalistaLab] = " . $this->idCatAnalistaLab . " "; }
		if($this->idCatSupervisorLab != '' && !is_null($this->idCatSupervisorLab)) {	$sql .= ",[idCatSupervisorLab] = " . $this->idCatSupervisorLab . " "; }
		$sql .= "WHERE idEstudioBac = " . $this->idEstudioBac . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

	public function setNullIdPacienteBD()  {
		$sql = "UPDATE [estudiosBac] SET [idPaciente] = NULL WHERE idEstudioBac = " . $this->idEstudioBac . ";";
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}		
	}

	public function obtenerBD($idEstudioBac) {
		$sql = "SELECT * FROM [estudiosBac] WHERE idEstudioBac = " . $idEstudioBac;
		// p4scu41
		/*if($this->idContacto != '' && !is_null($this->idContacto)) { 
			$sql .= " and idContacto = ".$this->idContacto.";";
		}
		else {
			$sql .= " and idContacto is null;";
		}*/
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			$this->idEstudioBac = $tabla["idEstudioBac"];
			$this->idDiagnostico = $tabla["idDiagnostico"];
			$this->fechaSolicitud = $tabla["fechaSolicitud"];			
			////////////							Posibles nulos
			if (!is_null($tabla["idContacto"])) { $this->idContacto = $tabla["idContacto"]; }
			if (!is_null($tabla["idPaciente"])) { $this->idPaciente = $tabla["idPaciente"]; }
			if (!is_null($tabla["fechaRecepcion"])) { $this->fechaRecepcion = $tabla["fechaRecepcion"]; }
			if (!is_null($tabla["folioLaboratorio"])) { $this->folioLaboratorio = $tabla["folioLaboratorio"]; }
			if (!is_null($tabla["folioSolicitud"])) { $this->folioSolicitud = $tabla["folioSolicitud"]; }
			if (!is_null($tabla["idCatSolicitante"])) { $this->idCatSolicitante = $tabla["idCatSolicitante"]; }
			if (!is_null($tabla["idCatTipoEstudio"])) { $this->idCatTipoEstudio = $tabla["idCatTipoEstudio"]; } 
			if (!is_null($tabla["tomMueFrotis1"])) { $this->tomMueFrotis1 = $tabla["tomMueFrotis1"]; }
			if (!is_null($tabla["tomMueFrotis2"])) { $this->tomMueFrotis2 = $tabla["tomMueFrotis2"]; }
			if (!is_null($tabla["tomMueFrotis3"])) { $this->tomMueFrotis3 = $tabla["tomMueFrotis3"]; }
			if (!is_null($tabla["lesionTomoMuestra"])) { $this->lesionTomoMuestra = $tabla["lesionTomoMuestra"]; } 
			if (!is_null($tabla["regionTomoMuestra"])) { $this->regionTomoMuestra = $tabla["regionTomoMuestra"]; } 
			if (!is_null($tabla["fechaTomaMuestra"])) { $this->fechaTomaMuestra = $tabla["fechaTomaMuestra"]; }
			if (!is_null($tabla["personaTomaMuestra"])) { $this->personaTomaMuestra = $tabla["personaTomaMuestra"]; }
			if (!is_null($tabla["fechaSolicitudEstudio"])) { $this->fechaSolicitudEstudio = $tabla["fechaSolicitudEstudio"]; } 
			if (!is_null($tabla["personaSolicitudEstudio"])) { $this->personaSolicitudEstudio = $tabla["personaSolicitudEstudio"]; } 			
			if (!is_null($tabla["muestraRechazada"])) { $this->muestraRechazada = $tabla["muestraRechazada"]; }
			if (!is_null($tabla["idCatMotivoRechazo"])) { $this->idCatMotivoRechazo = $tabla["idCatMotivoRechazo"]; }
			if (!is_null($tabla["otroMotivoRechazo"])) { $this->otroMotivoRechazo = $tabla["otroMotivoRechazo"]; }
			if (!is_null($tabla["fechaResultado"])) { $this->fechaResultado = $tabla["fechaResultado"]; }
			if (!is_null($tabla["idCatBacFrotis1"])) { $this->idCatBacFrotis1 = $tabla["idCatBacFrotis1"]; }
			if (!is_null($tabla["idCatBacFrotis2"])) { $this->idCatBacFrotis2 = $tabla["idCatBacFrotis2"]; }
			if (!is_null($tabla["idCatBacFrotis3"])) { $this->idCatBacFrotis3 = $tabla["idCatBacFrotis3"]; }
			if (!is_null($tabla["bacPorcViaFrotis1"])) { $this->bacPorcViaFrotis1 = $tabla["bacPorcViaFrotis1"]; }
			if (!is_null($tabla["bacPorcViaFrotis2"])) { $this->bacPorcViaFrotis2 = $tabla["bacPorcViaFrotis2"]; }
			if (!is_null($tabla["bacPorcViaFrotis3"])) { $this->bacPorcViaFrotis3 = $tabla["bacPorcViaFrotis3"]; }
			if (!is_null($tabla["bacCalidadAdecFrotis1"])) { $this->bacCalidadAdecFrotis1 = $tabla["bacCalidadAdecFrotis1"]; }
			if (!is_null($tabla["bacCalidadAdecFrotis2"])) { $this->bacCalidadAdecFrotis2 = $tabla["bacCalidadAdecFrotis2"]; }
			if (!is_null($tabla["bacCalidadAdecFrotis3"])) { $this->bacCalidadAdecFrotis3 = $tabla["bacCalidadAdecFrotis3"]; }			
			if (!is_null($tabla["bacIdCatTiposBacilosFrotis1"])) { $this->bacIdCatTiposBacilosFrotis1 = $tabla["bacIdCatTiposBacilosFrotis1"]; }
			if (!is_null($tabla["bacIdCatTiposBacilosFrotis2"])) { $this->bacIdCatTiposBacilosFrotis2 = $tabla["bacIdCatTiposBacilosFrotis2"]; }
			if (!is_null($tabla["bacIdCatTiposBacilosFrotis3"])) { $this->bacIdCatTiposBacilosFrotis3 = $tabla["bacIdCatTiposBacilosFrotis3"]; }
			if (!is_null($tabla["idCatBac"])) { $this->idCatBac = $tabla["idCatBac"]; }
			if (!is_null($tabla["bacIM"])) { $this->bacIM = $tabla["bacIM"]; }
			if (!is_null($tabla["bacObservaciones"])) { $this->bacObservaciones = $tabla["bacObservaciones"]; }			
			if (!is_null($tabla["idCatEstadoLaboratorio"])) { $this->idCatEstadoLaboratorio = $tabla["idCatEstadoLaboratorio"]; }
			if (!is_null($tabla["idCatJurisdiccionLaboratorio"])) { $this->idCatJurisdiccionLaboratorio = $tabla["idCatJurisdiccionLaboratorio"]; }
			if (!is_null($tabla["idCatAnalistaLab"])) { $this->idCatAnalistaLab = $tabla["idCatAnalistaLab"]; }
			if (!is_null($tabla["idCatSupervisorLab"])) { $this->idCatSupervisorLab = $tabla["idCatSupervisorLab"]; }			
		}
	}
}
?>