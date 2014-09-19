<?php
class EstudioHis {

	public $idEstudioHis = 0;				// int
	public $idDiagnostico = 0;				// int						OJO!!! Este campo es 0 cuando el paciente es SOSPECHOSO, se actualiza al IDDIAGNOSTICO para el resto de casos
	public $fechaSolicitud;					// date
	////////////							Posibles nulos
	public $idContacto;						// int						OJO!!! Este campo es usado UNICAMENTE cuando el estudio pertenece a un CONTACTO, NULL para el resto de casos
	public $idPaciente;						// int						OJO!!! Este campo es usado UNICAMENTE cuando el paciente es SOSPECHOSO, NULL para el resto de casos
	public $fechaRecepcion;
	public $folioLaboratorio;				// varchar(10)
	public $folioSolicitud;
	public $idCatSolicitante;				// int
	public $idCatTipoEstudio;				// int
	public $lesionTomoMuestra;				// varchar(50)
	public $regionTomoMuestra;				// varchar(50)
	public $fechaTomaMuestra;				// date
	public $personaTomaMuestra;				// varchar(200)
	public $fechaSolicitudEstudio;			// date
	public $personaSolicitudEstudio;		// varchar(50)
	public $muestraRechazada;				// bit		// OJO!!!! PEGAR COMO 1 o 0 EL VALOR.  1 para TRUE, 0 para FALSE
	public $idCatMotivoRechazo;				// int
	public $otroMotivoRechazo;				// varchar(200)
	public $fechaResultado;					// date
	public $hisDescMacro;					// varchar(300)
	public $hisDescMicro;					// varchar(300)
	public $hisResultado;					// varchar(300)
	public $idCatHisto;						// int
	public $idCatEstadoLaboratorio;			// int
	public $idCatJurisdiccionLaboratorio;	// int
	public $idCatAnalistaLab;				// int
	public $idCatSupervisorLab;				// int	
	public $idCatEstadoTratante;			// int
	public $IdCatJurisdiccionTratante;		// int

	public $error = false;
	public $msgError;

	public function insertarBD() {
		$sqlA = "INSERT INTO [estudiosHis] ([idDiagnostico], [fechaSolicitud]";
		$sqlB = "VALUES (" . $this->idDiagnostico . ", '" . formatFechaObj($this->fechaSolicitud, 'Y-m-d') . "'";

		if($this->idContacto != '' && !is_null($this->idContacto)) { $sqlA .= ", [idContacto]"; $sqlB .= ", " . $this->idContacto; }
		if($this->idPaciente != '' && !is_null($this->idPaciente)) { $sqlA .= ", [idPaciente]"; $sqlB .= ", " . $this->idPaciente; }
		if($this->fechaRecepcion != '' && !is_null($this->fechaRecepcion)) { $sqlA .= ", [fechaRecepcion]"; $sqlB .= ", '" . formatFechaObj($this->fechaRecepcion, 'Y-m-d') . "'"; }
		if($this->folioLaboratorio != '' && !is_null($this->folioLaboratorio)) { $sqlA .= ", [folioLaboratorio]"; $sqlB .= ", '" . $this->folioLaboratorio . "'"; }
		if($this->idCatSolicitante != '' && !is_null($this->idCatSolicitante)) { $sqlA .= ", [idCatSolicitante]"; $sqlB .= ", '" . $this->idCatSolicitante . "'"; }
		if($this->idCatTipoEstudio != '' && !is_null($this->idCatTipoEstudio)) { $sqlA .= ", [idCatTipoEstudio]"; $sqlB .= ", " . $this->idCatTipoEstudio; }
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
		if($this->hisDescMacro != '' && !is_null($this->hisDescMacro)) { $sqlA .= ", [hisDescMacro]"; $sqlB .= ", '" . $this->hisDescMacro . "'"; }
		if($this->hisDescMicro != '' && !is_null($this->hisDescMicro)) { $sqlA .= ", [hisDescMicro]"; $sqlB .= ", '" . $this->hisDescMicro . "'"; }
		if($this->hisResultado != '' && !is_null($this->hisResultado)) { $sqlA .= ", [hisResultado]"; $sqlB .= ", '" . $this->hisResultado . "'"; }	
		if($this->idCatHisto != '' && !is_null($this->idCatHisto)) { $sqlA .= ", [idCatHisto]"; $sqlB .= ", " . $this->idCatHisto; }
		if($this->idCatEstadoLaboratorio != '' && !is_null($this->idCatEstadoLaboratorio)) { $sqlA .= ", [idCatEstadoLaboratorio]"; $sqlB .= ", " . $this->idCatEstadoLaboratorio; }
		if($this->idCatJurisdiccionLaboratorio != '' && !is_null($this->idCatJurisdiccionLaboratorio)) { $sqlA .= ", [idCatJurisdiccionLaboratorio]"; $sqlB .= ", " . $this->idCatJurisdiccionLaboratorio; }
		if($this->idCatAnalistaLab != '' && !is_null($this->idCatAnalistaLab)) { $sqlA .= ", [idCatAnalistaLab]"; $sqlB .= ", " . $this->idCatAnalistaLab; }
		if($this->idCatSupervisorLab != '' && !is_null($this->idCatSupervisorLab)) { $sqlA .= ", [idCatSupervisorLab]"; $sqlB .= ", " . $this->idCatSupervisorLab; }
		
		if($this->idCatEstadoTratante != '' && !is_null($this->idCatEstadoTratante)) { $sqlA .= ", [idCatEstadoTratante]"; $sqlB .= ", " . $this->idCatEstadoTratante; }
		if($this->IdCatJurisdiccionTratante != '' && !is_null($this->IdCatJurisdiccionTratante)) { $sqlA .= ", [IdCatJurisdiccionTratante]"; $sqlB .= ", " . $this->IdCatJurisdiccionTratante; }

		$sqlA .= ") " . $sqlB . "); SELECT @@Identity AS nuevoId;";		
		
		$consulta = ejecutaQueryClases($sqlA);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idEstudioHis = $tabla["nuevoId"];
		}
	}

	public function modificarBD() {
		$sql = "UPDATE [estudiosHis] SET ";
		$sql .= " [idDiagnostico] = " . $this->idDiagnostico . " ";
		if($this->idContacto != '' && !is_null($this->idContacto)) {	$sql .= ",[idContacto] = " . $this->idContacto . " "; }
		if($this->idPaciente != '' && !is_null($this->idPaciente)) { $sql .= ",[idPaciente] = " . $this->idPaciente . " "; }
		if($this->fechaRecepcion != '' && !is_null($this->fechaRecepcion)) {	$sql .= ",[fechaRecepcion] = '" . formatFechaObj($this->fechaRecepcion, 'Y-m-d') . "' "; }
		if($this->folioLaboratorio != '' && !is_null($this->folioLaboratorio)) {	$sql .= ",[folioLaboratorio] = '" . $this->folioLaboratorio . "' "; }
		if($this->idCatSolicitante != '' && !is_null($this->idCatSolicitante)) {	$sql .= ",[idCatSolicitante] = '" . $this->idCatSolicitante . "' "; }
		if($this->idCatTipoEstudio != '' && !is_null($this->idCatTipoEstudio)) { $sql .= ",[idCatTipoEstudio] = " . $this->idCatTipoEstudio . " "; }
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
		if($this->hisDescMacro != '' && !is_null($this->hisDescMacro)) {	$sql .= ",[hisDescMacro] = '" . $this->hisDescMacro . "' "; }
		if($this->hisDescMicro != '' && !is_null($this->hisDescMicro)) {	$sql .= ",[hisDescMicro] = '" . $this->hisDescMicro . "' "; }
		if($this->hisResultado != '' && !is_null($this->hisResultado)) {	$sql .= ",[hisResultado] = '" . $this->hisResultado . "' "; }
		if($this->idCatHisto != '' && !is_null($this->idCatHisto)) { $sql .= ",[idCatHisto] = " . $this->idCatHisto . " "; }
		if($this->idCatEstadoLaboratorio != '' && !is_null($this->idCatEstadoLaboratorio)) {	$sql .= ",[idCatEstadoLaboratorio] = " . $this->idCatEstadoLaboratorio . " "; }
		if($this->idCatJurisdiccionLaboratorio != '' && !is_null($this->idCatJurisdiccionLaboratorio)) {	$sql .= ",[idCatJurisdiccionLaboratorio] = " . $this->idCatJurisdiccionLaboratorio . " "; }
		if($this->idCatAnalistaLab != '' && !is_null($this->idCatAnalistaLab)) {	$sql .= ",[idCatAnalistaLab] = " . $this->idCatAnalistaLab . " "; }
		if($this->idCatSupervisorLab != '' && !is_null($this->idCatSupervisorLab)) {	$sql .= ",[idCatSupervisorLab] = " . $this->idCatSupervisorLab . " "; }

		if($this->idCatEstadoTratante != '' && !is_null($this->idCatEstadoTratante)) {	$sql .= ",[idCatEstadoTratante] = " . $this->idCatEstadoTratante . " "; }
		if($this->IdCatJurisdiccionTratante != '' && !is_null($this->IdCatJurisdiccionTratante)) {	$sql .= ",[IdCatJurisdiccionTratante] = " . $this->IdCatJurisdiccionTratante . " "; }
		$sql .= "WHERE idEstudioHis = " . $this->idEstudioHis . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

	public function setNullIdPacienteBD($idDiagnostico)  {
		$sql = "UPDATE [estudiosHis] SET [idPaciente] = NULL, [idDiagnostico] = ".$idDiagnostico."  WHERE idEstudioHis = " . $this->idEstudioHis . ";";
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}		
	}

	public function obtenerBD($idEstudioHis) {
		$sql = "SELECT * FROM [estudiosHis] WHERE idEstudioHis = " . $idEstudioHis;
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
            
            //var_dump($tabla);
            
			$this->idEstudioHis = $tabla["idEstudioHis"];
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
			if (!is_null($tabla["hisDescMacro"])) { $this->hisDescMacro = $tabla["hisDescMacro"]; }
			if (!is_null($tabla["hisDescMicro"])) { $this->hisDescMicro = $tabla["hisDescMicro"]; }
			if (!is_null($tabla["hisResultado"])) { $this->hisResultado = $tabla["hisResultado"]; }
			if (!is_null($tabla["idCatHisto"])) { $this->idCatHisto = $tabla["idCatHisto"]; }
			if (!is_null($tabla["idCatEstadoLaboratorio"])) { $this->idCatEstadoLaboratorio = $tabla["idCatEstadoLaboratorio"]; }
			if (!is_null($tabla["idCatJurisdiccionLaboratorio"])) { $this->idCatJurisdiccionLaboratorio = $tabla["idCatJurisdiccionLaboratorio"]; }
			if (!is_null($tabla["idCatAnalistaLab"])) { $this->idCatAnalistaLab = $tabla["idCatAnalistaLab"]; }
			if (!is_null($tabla["idCatSupervisorLab"])) { $this->idCatSupervisorLab = $tabla["idCatSupervisorLab"]; }			
			
			if (!is_null($tabla["idCatEstadoTratante"])) { $this->idCatEstadoTratante = $tabla["idCatEstadoTratante"]; }			
			if (!is_null($tabla["IdCatJurisdiccionTratante"])) { $this->IdCatJurisdiccionTratante = $tabla["IdCatJurisdiccionTratante"]; }			
		}
	}
}
?>