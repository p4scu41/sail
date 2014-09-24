<?php
class Control {

	public $idControl = 0;					// int				12
	public $idDiagnostico;					// int				99
	public $fecha;							// date				2011/05/21
	////////////							Posibles nulos		
	public $reingreso;						// bit				0			// OJO!!!! PEGAR COMO 1 o 0 EL VALOR.  1 para TRUE, 0 para FALSE
	public $idCatEstadoPaciente;			// int				12
	public $idCatTratamientoPreescrito;		// int				9
	public $vigilanciaPostratamiento;		// bit				1			// OJO!!!! PEGAR COMO 1 o 0 EL VALOR.  1 para TRUE, 0 para FALSE
	public $observaciones;					// varchar(200)		Este es un campo de texto largo
    
    public $idCatEvolucionClinica;
    public $idCatBaja;
    public $seed;

	public $error = false;
	public $msgError;

	public function insertarBD() {
		$sqlA = "INSERT INTO [control] ([idDiagnostico], [fecha]" ;
		$sqlB = "VALUES (" . $this->idDiagnostico. " , '" . formatFechaObj($this->fecha, 'Y-m-d') . "'";
		if($this->reingreso != '' && !is_null($this->reingreso)) { $sqlA .= ", [reingreso]"; $sqlB .= ", " . $this->reingreso; }
		if($this->idCatEstadoPaciente != '' && !is_null($this->idCatEstadoPaciente)) { $sqlA .= ", [idCatEstadoPaciente]"; $sqlB .= ", " . $this->idCatEstadoPaciente; }
		if($this->idCatTratamientoPreescrito != '' && !is_null($this->idCatTratamientoPreescrito)) { $sqlA .= ", [idCatTratamientoPreescrito]"; $sqlB .= ", " . $this->idCatTratamientoPreescrito; }
		if($this->vigilanciaPostratamiento != '' && !is_null($this->vigilanciaPostratamiento)) { $sqlA .= ", [vigilanciaPostratamiento]"; $sqlB .= ", " . $this->vigilanciaPostratamiento; }
		if($this->observaciones != '' && !is_null($this->observaciones)) { $sqlA .= ", [observaciones]"; $sqlB .= ", '" . $this->observaciones . "'"; }
        if($this->idCatEvolucionClinica != '' && !is_null($this->idCatEvolucionClinica)) { $sqlA .= ", [idCatEvolucionClinica]"; $sqlB .= ", '" . $this->idCatEvolucionClinica . "'"; }
        if($this->idCatBaja != '' && !is_null($this->idCatBaja)) { $sqlA .= ", [idCatBaja]"; $sqlB .= ", '" . $this->idCatBaja . "'"; }
        if($this->seed != '' && !is_null($this->seed)) { $sqlA .= ", [seed]"; $sqlB .= ", '" . $this->seed . "'"; }
        
		$sqlA .= ") " . $sqlB . "); SELECT @@Identity AS nuevoId;";		
		$consulta = ejecutaQueryClases($sqlA);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idControl = $tabla["nuevoId"];
		}
        
        // Revisar, actualiza el Estado del paciente (Diagnostico) al reguistrar un nuevo control
		$sql = "";
		if($this->idCatEstadoPaciente != '' && !is_null($this->idCatEstadoPaciente)) $sql .= "UPDATE diagnostico SET idCatEstadoPaciente = " . $this->idCatEstadoPaciente . " WHERE idDiagnostico = " . $this->idDiagnostico . ";";
		if($this->idCatTratamientoPreescrito != '' && !is_null($this->idCatTratamientoPreescrito)) $sql .= "UPDATE diagnostico SET idCatTratamiento = " . $this->idCatTratamientoPreescrito . " WHERE idDiagnostico = " . $this->idDiagnostico . ";";
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " No se pudo actualizar el estado del paciente en la tabla Diagnostico SQL:" . $sqlA;
		}
	}

	public function modificarBD() {		
		$sql = "UPDATE [control]  SET ";
		$sql .= " [idDiagnostico] = " . $this->idDiagnostico . " ";
		$sql .= ",[fecha] = '" . formatFechaObj($this->fecha, 'Y-m-d') . "' ";
		if($this->reingreso != '' && !is_null($this->reingreso)) { $sql .= ",[reingreso] = " . $this->reingreso . " "; }
		if($this->idCatEstadoPaciente != '' && !is_null($this->idCatEstadoPaciente)) { $sql .= ",[idCatEstadoPaciente] = " . $this->idCatEstadoPaciente . " ";  }
		if($this->idCatTratamientoPreescrito != '' && !is_null($this->idCatTratamientoPreescrito)) { $sql .= ",[idCatTratamientoPreescrito] = " . $this->idCatTratamientoPreescrito . " "; }
		if($this->vigilanciaPostratamiento != '' && !is_null($this->vigilanciaPostratamiento)) { $sql .= ",[vigilanciaPostratamiento] = " . $this->vigilanciaPostratamiento . " "; }
		if($this->observaciones != '' && !is_null($this->observaciones)) { $sql .= ",[observaciones] = '" . $this->observaciones . "' "; }
        if($this->idCatEvolucionClinica != '' && !is_null($this->idCatEvolucionClinica)) { $sql .= ", [idCatEvolucionClinica] = '" . $this->idCatEvolucionClinica . "'"; }
        if($this->idCatBaja != '' && !is_null($this->idCatBaja)) { $sql .= ", [idCatBaja]= '" . $this->idCatBaja . "'"; }
        if($this->seed != '' && !is_null($this->seed)) { $sql .= ", [seed] = '" . $this->seed . "'"; }
        
		$sql .= "WHERE idControl = " . $this->idControl . ";";
        
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}

		$sql = "";
		if($this->idCatEstadoPaciente != '' && !is_null($this->idCatEstadoPaciente)) $sql .= "UPDATE diagnostico SET idCatEstadoPaciente = " . $this->idCatEstadoPaciente . " WHERE idDiagnostico = " . $this->idDiagnostico . ";";
		if($this->idCatTratamientoPreescrito != '' && !is_null($this->idCatTratamientoPreescrito)) $sql .= "UPDATE diagnostico SET idCatTratamiento = " . $this->idCatTratamientoPreescrito . " WHERE idDiagnostico = " . $this->idDiagnostico . ";";
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " No se pudo actualizar el estado del paciente en la tabla Diagnostico SQL:" . $sqlA;
		}
	}

	public function obtenerBD($idControl) {
		$sql = "SELECT * FROM [control] WHERE idControl = " . $idControl . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);

			$this->idControl = $tabla["idControl"];
			$this->idDiagnostico = $tabla["idDiagnostico"];
			$this->fecha = $tabla["fecha"];
			////////////							Posibles nulos		
			if (!is_null($tabla["reingreso"])) { $this->reingreso = $tabla["reingreso"]; }
			if (!is_null($tabla["idCatEstadoPaciente"])) { $this->idCatEstadoPaciente = $tabla["idCatEstadoPaciente"]; }
			if (!is_null($tabla["idCatTratamientoPreescrito"])) { $this->idCatTratamientoPreescrito = $tabla["idCatTratamientoPreescrito"]; }
			if (!is_null($tabla["vigilanciaPostratamiento"])) { $this->vigilanciaPostratamiento = $tabla["vigilanciaPostratamiento"]; }
			if (!is_null($tabla["observaciones"])) { $this->observaciones = $tabla["observaciones"]; }
            
            if (!is_null($tabla["idCatEvolucionClinica"])) { $this->idCatEvolucionClinica = $tabla["idCatEvolucionClinica"]; }
            if (!is_null($tabla["idCatBaja"])) { $this->idCatBaja = $tabla["idCatBaja"]; }
            if (!is_null($tabla["seed"])) { $this->seed = $tabla["seed"]; }
            
		}
	}

    public function countByDiagnostico($idDiagnostico) {
		$sql = "SELECT COUNT(*) AS total FROM [control] WHERE idDiagnostico = " . $idDiagnostico . ";";

		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;

            return null;
		} else {
			$result = devuelveRowAssoc($consulta);

            return $result['total'];
		}
	}

}
?>