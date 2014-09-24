<?php

class Sospechoso {

	public $idPaciente;				// int
	// POSIBLES NULOS
	public $idCatTopografia;		// int
	public $descripcionTopografica;	// text
	public $idCatNumeroLesiones;	// int
	public $segAfeCab;				// bit
	public $segAfeTro;				// bit
	public $segAfeMSD;				// bit
	public $segAfeMSI;				// bit
	public $segAfeMID;				// bit
	public $segAfeMII;				// bit

	public $error = false;
	public $msgError;

	public function insertarBD() {
		$sqlA = "INSERT INTO [sospechoso] ([idPaciente] " ;
		$sqlB = "VALUES (" . $this->idPaciente;
		
		if($this->idCatTopografia != '' && !is_null($this->idCatTopografia)) { $sqlA .= ", [idCatTopografia]"; $sqlB .= ", " . $this->idCatTopografia; }
		if($this->descripcionTopografica != '' && !is_null($this->descripcionTopografica)) { $sqlA .= ", [descripcionTopografica]"; $sqlB .= ", '" . $this->descripcionTopografica . "'"; }
		if($this->idCatNumeroLesiones != '' && !is_null($this->idCatNumeroLesiones)) { $sqlA .= ", [idCatNumeroLesiones]"; $sqlB .= ", " . $this->idCatNumeroLesiones; }
		if($this->segAfeCab != '' && !is_null($this->segAfeCab)) { $sqlA .= ", [segAfeCab]"; $sqlB .= ", " . $this->segAfeCab; }
		if($this->segAfeTro != '' && !is_null($this->segAfeTro)) { $sqlA .= ", [segAfeTro]"; $sqlB .= ", " . $this->segAfeTro; }
		if($this->segAfeMSD != '' && !is_null($this->segAfeMSD)) { $sqlA .= ", [segAfeMSD]"; $sqlB .= ", " . $this->segAfeMSD; }
		if($this->segAfeMSI != '' && !is_null($this->segAfeMSI)) { $sqlA .= ", [segAfeMSI]"; $sqlB .= ", " . $this->segAfeMSI; }
		if($this->segAfeMID != '' && !is_null($this->segAfeMID)) { $sqlA .= ", [segAfeMID]"; $sqlB .= ", " . $this->segAfeMID; }
		if($this->segAfeMII != '' && !is_null($this->segAfeMII)) { $sqlA .= ", [segAfeMII]"; $sqlB .= ", " . $this->segAfeMII; }
        
        $sqlA .= ", [idUsuario]"; $sqlB .= ", " .$_SESSION[ID_USR_SESSION] ; 
		
		$sqlA .= ") " . $sqlB . ");";
		$consulta = ejecutaQueryClases($sqlA);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		}
	}

	public function modificarBD() {		
		$sql = "UPDATE [sospechoso]  SET idPaciente = " . $this->idPaciente;
		if($this->idCatTopografia != '' && !is_null($this->idCatTopografia)) { $sql .= ", [idCatTopografia] = " . $this->idCatTopografia; }
		if($this->descripcionTopografica != '' && !is_null($this->descripcionTopografica)) { $sql .= ", [descripcionTopografica] = '" . $this->descripcionTopografica . "'" ; }
		if($this->idCatNumeroLesiones != '' && !is_null($this->idCatNumeroLesiones)) { $sql .= ", [idCatNumeroLesiones] = " . $this->idCatNumeroLesiones; }
		if($this->segAfeCab != '' && !is_null($this->segAfeCab)) { $sql .= ", [segAfeCab] = " . $this->segAfeCab; }
		if($this->segAfeTro != '' && !is_null($this->segAfeTro)) { $sql .= ", [segAfeTro] = " . $this->segAfeTro; }
		if($this->segAfeMSD != '' && !is_null($this->segAfeMSD)) { $sql .= ", [segAfeMSD] = " . $this->segAfeMSD; }
		if($this->segAfeMSI != '' && !is_null($this->segAfeMSI)) { $sql .= ", [segAfeMSI] = " . $this->segAfeMSI; }
		if($this->segAfeMID != '' && !is_null($this->segAfeMID)) { $sql .= ", [segAfeMID] = " . $this->segAfeMID; }
		if($this->segAfeMII != '' && !is_null($this->segAfeMII)) { $sql .= ", [segAfeMII] = " . $this->segAfeMII; }
		$sql .= " WHERE idPaciente = " . $this->idPaciente . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

	public function obtenerBD($idPaciente) {
		$sql = "SELECT * FROM [sospechoso] WHERE idPaciente = " . $idPaciente . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);

			$this->idPaciente = $tabla["idPaciente"];
			if (!is_null($tabla["idCatTopografia"])) { $this->idCatTopografia = $tabla["idCatTopografia"]; }
			if (!is_null($tabla["descripcionTopografica"])) { $this->descripcionTopografica = $tabla["descripcionTopografica"]; }
			if (!is_null($tabla["idCatNumeroLesiones"])) { $this->idCatNumeroLesiones = $tabla["idCatNumeroLesiones"]; }
			if (!is_null($tabla["segAfeCab"])) { $this->segAfeCab = $tabla["segAfeCab"]; }
			if (!is_null($tabla["segAfeTro"])) { $this->segAfeTro = $tabla["segAfeTro"]; }
			if (!is_null($tabla["segAfeMSD"])) { $this->segAfeMSD = $tabla["segAfeMSD"]; }
			if (!is_null($tabla["segAfeMSI"])) { $this->segAfeMSI = $tabla["segAfeMSI"]; }
			if (!is_null($tabla["segAfeMID"])) { $this->segAfeMID = $tabla["segAfeMID"]; }
			if (!is_null($tabla["segAfeMII"])) { $this->segAfeMII = $tabla["segAfeMII"]; }			
		}
	}

    public function eliminarBD($idPaciente) {
		$sql = "DELETE FROM [sospechoso] WHERE idPaciente = " . $idPaciente . ";";

		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

}
?>