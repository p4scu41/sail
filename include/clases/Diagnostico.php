<?php
Class Diagnostico {

	public $idDiagnostico = 0;				// int				98
	public $idPaciente;						// int				98
	public $idCatNumeroLesiones;			// int				2
	public $discOjoIzq;						// tinyint			0
	public $discOjoDer;						// tinyint			0
	public $discManoIzq;					// tinyint			1
	public $discManoDer;					// tinyint			0
	public $discPieIzq;						// tinyint			1
	public $discPieDer;						// tinyint			1		
	public $idUsuario;						// int				2					NOTA: Quien capturo la informacion
	public $fechaCaptura;					// date				2011/05/21
	////////////							Posibles nulos
	public $idCatEstadoPaciente;			// int				1
	public $idCatEstadoReaccionalAct;		// int				1	
	public $idCatClasificacionLepra;		// int				1
	public $otrosPadecimientos;				// text				Diabetes, Apendicitis 17 aNos, alergico a la lactosa
	public $descripcionTopografica;			// text				Presenta una serie de lesiones de tipo nudoso en la parte posterior de la mano derecha...
	public $idCatEstadoReaccionalAnt;		// int				1
	public $fechaReaccionAnteriorTipI;		// date				2011/05/21
	public $fechaReaccionAnteriorTipII;		// date				2011/05/21
	public $idCatLocalidadAdqEnf;			// int				8791
	public $idCatMunicipioAdqEnf;			// int				621
	public $idCatEstadoAdqEnf;				// int				89
	public $observaciones;					// text	

	public $idCatTopografia	;				// int				1
	public $idCatTratamiento;				// int				3
	// Segmentos Afectados: Cabeza, Tronco, Miembro Superior Derecho, Miembro Superior Izquierdo, Miembro Inferior Derecho, Miembro Inferior Izquierdo
	public $segAfeCab;						// bit				1				
	public $segAfeTro;						// bit				0
	public $segAfeMSD;						// bit				0
	public $segAfeMSI;						// bit				0
	public $segAfeMID;						// bit				0
	public $segAfeMII;						// bit				1
	// Estado reaccional Actual y Anterior: Eritema Nudoso, Eritema Polimorfo, Eritema Necrosante
	public $estReaAntEriNud;				// bit				1
	public $estReaAntEriPol;				// bit				1
	public $estReaAntEriNec;				// bit				0
	public $estReaActEriNud;				// bit				1
	public $estReaActEriPol;				// bit				1
	public $estReaActEriNec;				// bit				0


	public $error = false;
	public $msgError;

	// ARREGLOS VINCULADOS AL DIAGNOSTICO
	public $arrEstudiosHis = array();
	public $arrEstudiosBac = array();
	public $arrContactos = array();
	public $arrCasosRelacionados = array();
	public $arrControles = array();
	public $arrDiagramaDermatologico = array();
	
	
	public function obtieneIdDiagnostico($idPaciente) {
        if(empty($idPaciente))
            return null;
        
		$query = 'SELECT [idDiagnostico] FROM [diagnostico] WHERE [idPaciente]='.(int)$idPaciente;
		$result = ejecutaQueryClases($query);
		
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			$info = devuelveRowAssoc($result);
			$this->idDiagnostico = $info['idDiagnostico'];
		}		
		return $this->idDiagnostico;
	}

	public function insertarBD() {		
		$sqlA = "INSERT INTO [diagnostico] ([idPaciente], [idCatNumeroLesiones], [idUsuario], [fechaCaptura]";
		$sqlB =  "VALUES (" . $this->idPaciente . ", " . $this->idCatNumeroLesiones . ", " . $this->idUsuario . ", GETDATE()";
        
        if($this->discOjoIzq != '' && !is_null($this->discOjoIzq)) { $sqlA .= ", [discOjoIzq]"; $sqlB .= ", " . $this->discOjoIzq; }
        if($this->discOjoDer != '' && !is_null($this->discOjoDer)) { $sqlA .= ", [discOjoDer]"; $sqlB .= ", " . $this->discOjoDer; }
        if($this->discManoIzq != '' && !is_null($this->discManoIzq)) { $sqlA .= ", [discManoIzq]"; $sqlB .= ", " . $this->discManoIzq; }
        if($this->discManoDer != '' && !is_null($this->discManoDer)) { $sqlA .= ", [discManoDer]"; $sqlB .= ", " . $this->discManoDer; }
        if($this->discPieIzq != '' && !is_null($this->discPieIzq)) { $sqlA .= ", [discPieIzq]"; $sqlB .= ", " . $this->discPieIzq; }
        if($this->discPieDer != '' && !is_null($this->discPieDer)) { $sqlA .= ", [discPieDer]"; $sqlB .= ", " . $this->discPieDer; }
        if($this->idCatEstadoPaciente != '' && !is_null($this->idCatEstadoPaciente)) { $sqlA .= ", [idCatEstadoPaciente]"; $sqlB .= ", " . $this->idCatEstadoPaciente; }
		if($this->idCatEstadoReaccionalAct != '' && !is_null($this->idCatEstadoReaccionalAct)) { $sqlA .= ", [idCatEstadoReaccionalAct]"; $sqlB .= ", " . $this->idCatEstadoReaccionalAct; }
		if($this->idCatClasificacionLepra != '' && !is_null($this->idCatClasificacionLepra)) { $sqlA .= ", [idCatClasificacionLepra]"; $sqlB .= ", " . $this->idCatClasificacionLepra; }
		if($this->otrosPadecimientos != '' && !is_null($this->otrosPadecimientos)) { $sqlA .= ", [otrosPadecimientos]"; $sqlB .= ", '" . $this->otrosPadecimientos. "'"; }
		if($this->descripcionTopografica != '' && !is_null($this->descripcionTopografica)) { $sqlA .= ", [descripcionTopografica]"; $sqlB .= ", '" . $this->descripcionTopografica . "'"; }
		if($this->idCatEstadoReaccionalAnt != '' && !is_null($this->idCatEstadoReaccionalAnt)) { $sqlA .= ", [idCatEstadoReaccionalAnt]"; $sqlB .= ", " . $this->idCatEstadoReaccionalAnt; }
		if($this->fechaReaccionAnteriorTipI != '' && !is_null($this->fechaReaccionAnteriorTipI)) { $sqlA .= ", [fechaReaccionAnteriorTipI]"; $sqlB .= ", '" . formatFechaObj($this->fechaReaccionAnteriorTipI, 'Y-m-d') . "'"; }
		if($this->fechaReaccionAnteriorTipII != '' && !is_null($this->fechaReaccionAnteriorTipII)) { $sqlA .= ", [fechaReaccionAnteriorTipII]"; $sqlB .= ", '" . formatFechaObj($this->fechaReaccionAnteriorTipII, 'Y-m-d') . "'"; }
		if($this->idCatLocalidadAdqEnf != '' && !is_null($this->idCatLocalidadAdqEnf)) { $sqlA .= ", [idCatLocalidadAdqEnf]"; $sqlB .= ", " . $this->idCatLocalidadAdqEnf; }
		if($this->idCatMunicipioAdqEnf != '' && !is_null($this->idCatMunicipioAdqEnf)) { $sqlA .= ", [idCatMunicipioAdqEnf]"; $sqlB .= ", " . $this->idCatMunicipioAdqEnf; }
		if($this->idCatEstadoAdqEnf != '' && !is_null($this->idCatEstadoAdqEnf)) { $sqlA .= ", [idCatEstadoAdqEnf]"; $sqlB .= ", " . $this->idCatEstadoAdqEnf; }
		if($this->observaciones != '' && !is_null($this->observaciones)) { $sqlA .= ", [observaciones]"; $sqlB .= ", '" . $this->observaciones . "'"; }
		if($this->idCatTratamiento != '' && !is_null($this->idCatTratamiento)) { $sqlA .= ", [idCatTratamiento]"; $sqlB .= ", " . $this->idCatTratamiento; }
		if($this->idCatTopografia != '' && !is_null($this->idCatTopografia)) { $sqlA .= ", [idCatTopografia]"; $sqlB .= ", " . $this->idCatTopografia; }
		if($this->segAfeCab != '' && !is_null($this->segAfeCab)) { $sqlA .= ", [segAfeCab]"; $sqlB .= ", " . $this->segAfeCab; }
		if($this->segAfeTro != '' && !is_null($this->segAfeTro)) { $sqlA .= ", [segAfeTro]"; $sqlB .= ", " . $this->segAfeTro; }
		if($this->segAfeMSD != '' && !is_null($this->segAfeMSD)) { $sqlA .= ", [segAfeMSD]"; $sqlB .= ", " . $this->segAfeMSD; }
		if($this->segAfeMSI != '' && !is_null($this->segAfeMSI)) { $sqlA .= ", [segAfeMSI]"; $sqlB .= ", " . $this->segAfeMSI; }
		if($this->segAfeMID != '' && !is_null($this->segAfeMID)) { $sqlA .= ", [segAfeMID]"; $sqlB .= ", " . $this->segAfeMID; }
		if($this->segAfeMII != '' && !is_null($this->segAfeMII)) { $sqlA .= ", [segAfeMII]"; $sqlB .= ", " . $this->segAfeMII; }
		if($this->estReaAntEriNud != '' && !is_null($this->estReaAntEriNud)) { $sqlA .= ", [estReaAntEriNud]"; $sqlB .= ", " . $this->estReaAntEriNud; }
		if($this->estReaAntEriPol != '' && !is_null($this->estReaAntEriPol)) { $sqlA .= ", [estReaAntEriPol]"; $sqlB .= ", " . $this->estReaAntEriPol; }
		if($this->estReaAntEriNec != '' && !is_null($this->estReaAntEriNec)) { $sqlA .= ", [estReaAntEriNec]"; $sqlB .= ", " . $this->estReaAntEriNec; }
		if($this->estReaActEriNud != '' && !is_null($this->estReaActEriNud)) { $sqlA .= ", [estReaActEriNud]"; $sqlB .= ", " . $this->estReaActEriNud; }
		if($this->estReaActEriPol != '' && !is_null($this->estReaActEriPol)) { $sqlA .= ", [estReaActEriPol]"; $sqlB .= ", " . $this->estReaActEriPol; }
		if($this->estReaActEriNec != '' && !is_null($this->estReaActEriNec)) { $sqlA .= ", [estReaActEriNec]"; $sqlB .= ", " . $this->estReaActEriNec; }

		$sqlA .= ") " . $sqlB . "); SELECT @@Identity AS nuevoId;";
		
		$consulta = ejecutaQueryClases($sqlA);		
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idDiagnostico = $tabla["nuevoId"];
		}
	}
	
	public function modificarBD() {
		$sql = "UPDATE [diagnostico] SET ".
			"[idPaciente] = " . $this->idPaciente . " " .
			",[idCatNumeroLesiones] = " . $this->idCatNumeroLesiones . " " .		
			",[idUsuario] = " . $this->idUsuario . " ";
        
		if($this->discOjoIzq != '' && !is_null($this->discOjoIzq)) { $sql .= ",[discOjoIzq] = " . $this->discOjoIzq . " "; }
		if($this->discOjoDer != '' && !is_null($this->discOjoDer)) { $sql .= ",[discOjoDer] = " . $this->discOjoDer . " "; }
		if($this->discManoIzq != '' && !is_null($this->discManoIzq)) { $sql .= ",[discManoIzq] = " . $this->discManoIzq . " "; }
		if($this->discManoDer != '' && !is_null($this->discManoDer)) { $sql .= ",[discManoDer] = " . $this->discManoDer . " "; }
		if($this->discPieIzq != '' && !is_null($this->discPieIzq)) { $sql .= ",[discPieIzq] = " . $this->discPieIzq . " "; }
		if($this->discPieDer != '' && !is_null($this->discPieDer)) { $sql .= ",[discPieDer] = " . $this->discPieDer . " "; }
        if($this->idCatClasificacionLepra != '' && !is_null($this->idCatClasificacionLepra)) { $sql .= ",[idCatClasificacionLepra] = " . $this->idCatClasificacionLepra . " "; }
		if($this->idCatEstadoReaccionalAct != '' && !is_null($this->idCatEstadoReaccionalAct)) { $sql .= ",[idCatEstadoReaccionalAct] = " . $this->idCatEstadoReaccionalAct . " "; }
		if($this->idCatEstadoPaciente != '' && !is_null($this->idCatEstadoPaciente)) { $sql .= ",[idCatEstadoPaciente] = " . $this->idCatEstadoPaciente . " "; }
		if($this->otrosPadecimientos != '' && !is_null($this->otrosPadecimientos)) { $sql .= ",[otrosPadecimientos] = '" . $this->otrosPadecimientos . "' "; }
		if($this->descripcionTopografica != '' && !is_null($this->descripcionTopografica)) { $sql .= ",[descripcionTopografica] = '" . $this->descripcionTopografica . "' "; }
		if($this->idCatEstadoReaccionalAnt != '' && !is_null($this->idCatEstadoReaccionalAnt)) { $sql .= ",[idCatEstadoReaccionalAnt] = " . $this->idCatEstadoReaccionalAnt . " "; }
		if($this->fechaReaccionAnteriorTipI != '' && !is_null($this->fechaReaccionAnteriorTipI)) { $sql .= ",[fechaReaccionAnteriorTipI] = '" . formatFechaObj($this->fechaReaccionAnteriorTipI, 'Y-m-d') . "' "; }
		if($this->fechaReaccionAnteriorTipII != '' && !is_null($this->fechaReaccionAnteriorTipII)) { $sql .= ",[fechaReaccionAnteriorTipII] = '" . formatFechaObj($this->fechaReaccionAnteriorTipII, 'Y-m-d') . "' "; }
		if($this->idCatLocalidadAdqEnf != '' && !is_null($this->idCatLocalidadAdqEnf)) { $sql .= ",[idCatLocalidadAdqEnf] = " . $this->idCatLocalidadAdqEnf . " "; }
		if($this->idCatMunicipioAdqEnf != '' && !is_null($this->idCatMunicipioAdqEnf)) { $sql .= ",[idCatMunicipioAdqEnf] = " . $this->idCatMunicipioAdqEnf . " "; }
		if($this->idCatEstadoAdqEnf != '' && !is_null($this->idCatEstadoAdqEnf)) { $sql .= ",[idCatEstadoAdqEnf] = " . $this->idCatEstadoAdqEnf . " "; }
		if($this->observaciones != '' && !is_null($this->observaciones)) { $sql .= ",[observaciones] = '" . $this->observaciones . "' "; }
		if($this->idCatTratamiento != '' && !is_null($this->idCatTratamiento)) { $sql .= ",[idCatTratamiento] = " . $this->idCatTratamiento . " "; }
		if($this->idCatTopografia != '' && !is_null($this->idCatTopografia)) { $sql .= ",[idCatTopografia] = " . $this->idCatTopografia . " "; }
		if($this->segAfeCab != '' && !is_null($this->segAfeCab)) { $sql .= ",[segAfeCab] = " . $this->segAfeCab . " "; }
		if($this->segAfeTro != '' && !is_null($this->segAfeTro)) { $sql .= ",[segAfeTro] = " . $this->segAfeTro . " "; }
		if($this->segAfeMSD != '' && !is_null($this->segAfeMSD)) { $sql .= ",[segAfeMSD] = " . $this->segAfeMSD . " "; }
		if($this->segAfeMSI != '' && !is_null($this->segAfeMSI)) { $sql .= ",[segAfeMSI] = " . $this->segAfeMSI . " "; }
		if($this->segAfeMID != '' && !is_null($this->segAfeMID)) { $sql .= ",[segAfeMID] = " . $this->segAfeMID . " "; }
		if($this->segAfeMII != '' && !is_null($this->segAfeMII)) { $sql .= ",[segAfeMII] = " . $this->segAfeMII . " "; }
		if($this->estReaAntEriNud != '' && !is_null($this->estReaAntEriNud)) { $sql .= ",[estReaAntEriNud] = " . $this->estReaAntEriNud . " "; }
		if($this->estReaAntEriPol != '' && !is_null($this->estReaAntEriPol)) { $sql .= ",[estReaAntEriPol] = " . $this->estReaAntEriPol . " "; }
		if($this->estReaAntEriNec != '' && !is_null($this->estReaAntEriNec)) { $sql .= ",[estReaAntEriNec] = " . $this->estReaAntEriNec . " "; }
		if($this->estReaActEriNud != '' && !is_null($this->estReaActEriNud)) { $sql .= ",[estReaActEriNud] = " . $this->estReaActEriNud . " "; }
		if($this->estReaActEriPol != '' && !is_null($this->estReaActEriPol)) { $sql .= ",[estReaActEriPol] = " . $this->estReaActEriPol . " "; }
		if($this->estReaActEriNec != '' && !is_null($this->estReaActEriNec)) { $sql .= ",[estReaActEriNec] = " . $this->estReaActEriNec . " "; }

		$sql .= "WHERE idDiagnostico = " . $this->idDiagnostico . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

	public function obtenerBD($idDiagnostico) {
		$sql = "SELECT * FROM [diagnostico] WHERE idDiagnostico = " . $idDiagnostico . ";";		
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {			
			$tabla = devuelveRowAssoc($consulta);			
			$this->idDiagnostico = $tabla["idDiagnostico"];
			$this->idPaciente = $tabla["idPaciente"];
			$this->idCatNumeroLesiones = $tabla["idCatNumeroLesiones"];
			$this->discOjoIzq = $tabla["discOjoIzq"];
			$this->discOjoDer = $tabla["discOjoDer"];
			$this->discManoIzq = $tabla["discManoIzq"];
			$this->discManoDer = $tabla["discManoDer"];
			$this->discPieIzq = $tabla["discPieIzq"];
			$this->discPieDer = $tabla["discPieDer"];			
			$this->idCatEstadoPaciente = $tabla["idCatEstadoPaciente"];
			$this->idUsuario = $tabla["idUsuario"];
			$this->fechaCaptura = $tabla["fechaCaptura"];
			////////////Posibles nulos
			if (!is_null($tabla["idCatClasificacionLepra"])) { $this->idCatClasificacionLepra = $tabla["idCatClasificacionLepra"]; }
			if (!is_null($tabla["idCatEstadoReaccionalAct"])) { $this->idCatEstadoReaccionalAct =  $tabla["idCatEstadoReaccionalAct"]; }
			if (!is_null($tabla["otrosPadecimientos"])) { $this->otrosPadecimientos =  $tabla["otrosPadecimientos"]; }
			if (!is_null($tabla["descripcionTopografica"])) { $this->descripcionTopografica =  $tabla["descripcionTopografica"]; }
			if (!is_null($tabla["idCatEstadoReaccionalAnt"])) { $this->idCatEstadoReaccionalAnt =  $tabla["idCatEstadoReaccionalAnt"]; }
			if (!is_null($tabla["fechaReaccionAnteriorTipI"])) { $this->fechaReaccionAnteriorTipI =  $tabla["fechaReaccionAnteriorTipI"]; }
			if (!is_null($tabla["fechaReaccionAnteriorTipII"])) { $this->fechaReaccionAnteriorTipII =  $tabla["fechaReaccionAnteriorTipII"]; }
			if (!is_null($tabla["idCatLocalidadAdqEnf"])) { $this->idCatLocalidadAdqEnf =  $tabla["idCatLocalidadAdqEnf"]; }
			if (!is_null($tabla["idCatMunicipioAdqEnf"])) { $this->idCatMunicipioAdqEnf =  $tabla["idCatMunicipioAdqEnf"]; }
			if (!is_null($tabla["idCatEstadoAdqEnf"])) { $this->idCatEstadoAdqEnf =  $tabla["idCatEstadoAdqEnf"]; }			
			if (!is_null($tabla["observaciones"])) { $this->observaciones =  $tabla["observaciones"]; }			
			if (!is_null($tabla["idCatTratamiento"])) { $this->idCatTratamiento = $tabla["idCatTratamiento"]; }
			if (!is_null($tabla["idCatTopografia"])) { $this->idCatTopografia = $tabla["idCatTopografia"]; }
			if (!is_null($tabla["segAfeCab"])) { $this->segAfeCab = $tabla["segAfeCab"]; }
			if (!is_null($tabla["segAfeTro"])) { $this->segAfeTro = $tabla["segAfeTro"]; }
			if (!is_null($tabla["segAfeMSD"])) { $this->segAfeMSD = $tabla["segAfeMSD"]; }
			if (!is_null($tabla["segAfeMSI"])) { $this->segAfeMSI = $tabla["segAfeMSI"]; }
			if (!is_null($tabla["segAfeMID"])) { $this->segAfeMID = $tabla["segAfeMID"]; }
			if (!is_null($tabla["segAfeMII"])) { $this->segAfeMII = $tabla["segAfeMII"]; }
			if (!is_null($tabla["estReaAntEriNud"])) { $this->estReaAntEriNud = $tabla["estReaAntEriNud"]; }
			if (!is_null($tabla["estReaAntEriPol"])) { $this->estReaAntEriPol = $tabla["estReaAntEriPol"]; }
			if (!is_null($tabla["estReaAntEriNec"])) { $this->estReaAntEriNec = $tabla["estReaAntEriNec"]; }
			if (!is_null($tabla["estReaActEriNud"])) { $this->estReaActEriNud = $tabla["estReaActEriNud"]; }
			if (!is_null($tabla["estReaActEriPol"])) { $this->estReaActEriPol = $tabla["estReaActEriPol"]; }
			if (!is_null($tabla["estReaActEriNec"])) { $this->estReaActEriNec = $tabla["estReaActEriNec"]; }
		}
	}

	public function cargarArreglosDiagnostico() {
		if ($this->idDiagnostico != 0) {				
			$sql = "SELECT [idEstudioBac] FROM [estudiosBac] WHERE idDiagnostico = " . $this->idDiagnostico . ";";
			$result = ejecutaQueryClases($sql);
			while ($tabla = devuelveRowAssoc($result)) {
				$idTemp = $tabla["idEstudioBac"];
				$objTemp = new EstudioBac();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrEstudiosBac, $objTemp);

			}

			$sql = "SELECT [idEstudioHis] FROM [estudiosHis] WHERE idDiagnostico = " . $this->idDiagnostico . ";";			
			$result = ejecutaQueryClases($sql);
			while ($tabla = devuelveRowAssoc($result)) {
				$idTemp = $tabla["idEstudioHis"];
				$objTemp = new EstudioHis();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrEstudiosHis, $objTemp);
			}

			$sql = "SELECT [idContacto] FROM [Contactos] WHERE idDiagnostico = " . $this->idDiagnostico . ";";			
			$result = ejecutaQueryClases($sql);
			while ($tabla = devuelveRowAssoc($result)) {
				$idTemp = $tabla["idContacto"];
				$objTemp = new Contacto();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrContactos, $objTemp);
			}

			$sql = "SELECT [idCasoRelacionado] FROM [casosRelacionados] WHERE idDiagnostico = " . $this->idDiagnostico . ";";			
			$result = ejecutaQueryClases($sql);
			while ($tabla = devuelveRowAssoc($result)) {
				$idTemp = $tabla["idCasoRelacionado"];
				$objTemp = new CasoRelacionado();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrCasosRelacionados, $objTemp);
			}

			$sql = "SELECT [idControl] FROM [control] WHERE idDiagnostico = " . $this->idDiagnostico . "  ORDER BY fecha ASC;";
			$result = ejecutaQueryClases($sql);
			while ($tabla = devuelveRowAssoc($result)) {
				$idTemp = $tabla["idControl"];
				$objTemp = new Control();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrControles, $objTemp);
			}

			$sql = "SELECT [idLesion] FROM [diagramaDermatologico] WHERE idDiagnostico = " . $this->idDiagnostico . ";";			
			$result = ejecutaQueryClases($sql);
			while ($tabla = devuelveRowAssoc($result)) {
				$idTemp = $tabla["idLesion"];
				$objTemp = new DiagramaDermatologico();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrDiagramaDermatologico, $objTemp);
			}
		}
	}

	public function cargarArreglosDiagnosticoEstudiosBac() {
		if ($this->idDiagnostico != 0) {				
			$sql = "SELECT [idEstudioBac] FROM [estudiosBac] WHERE idDiagnostico = " . $this->idDiagnostico . ";";
			$result = ejecutaQueryClases($sql);
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sqlA;
			} else {
				while ($tabla = devuelveRowAssoc($result)) {
					$idTemp = $tabla["idEstudioBac"];
					$objTemp = new EstudioBac();
					$objTemp->obtenerBD($idTemp);
					//echo $objTemp->msgError;
					array_push($this->arrEstudiosBac, $objTemp);
				}
			}
		}
	}

	public function cargarArreglosDiagnosticoEstudiosHis() {
		if ($this->idDiagnostico != 0) {				
			$sql = "SELECT [idEstudioHis] FROM [estudiosHis] WHERE idDiagnostico = " . $this->idDiagnostico . ";";			
			$result = ejecutaQueryClases($sql);
			
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sqlA;
			} else {
				while ($tabla = devuelveRowAssoc($result)) {
					$idTemp = $tabla["idEstudioHis"];
					$objTemp = new EstudioHis();
					$objTemp->obtenerBD($idTemp);
					array_push($this->arrEstudiosHis, $objTemp);
				}
			}
		}
	}

	public function cargarArreglosDiagnosticoContactos() {
		if ($this->idDiagnostico != 0) {				
			$sql = "SELECT [idContacto] FROM [Contactos] WHERE idDiagnostico = " . $this->idDiagnostico . ";";			
			$result = ejecutaQueryClases($sql);
			
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sqlA;
			} else {
				while ($tabla = devuelveRowAssoc($result)) {
					$idTemp = $tabla["idContacto"];
					$objTemp = new Contacto();
					$objTemp->obtenerBD($idTemp);
					array_push($this->arrContactos, $objTemp);
				}
			}
		}
	}

	public function cargarArreglosDiagnosticoCasosRelacionados() {
		if ($this->idDiagnostico != 0) {				
			$sql = "SELECT [idCasoRelacionado] FROM [casosRelacionados] WHERE idDiagnostico = " . $this->idDiagnostico . ";";			
			$result = ejecutaQueryClases($sql);
			
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sqlA;
			} else {
				while ($tabla = devuelveRowAssoc($result)) {
					$idTemp = $tabla["idCasoRelacionado"];
					$objTemp = new CasoRelacionado();
					$objTemp->obtenerBD($idTemp);
					array_push($this->arrCasosRelacionados, $objTemp);
				}
			}
		}
	}

	public function cargarArreglosDiagnosticoControl() {
		if ($this->idDiagnostico != 0) {				
			$sql = "SELECT [idControl] FROM [control] WHERE idDiagnostico = " . $this->idDiagnostico . " ORDER BY fecha ASC;";			
			$result = ejecutaQueryClases($sql);
			
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sqlA;
			} else {
				while ($tabla = devuelveRowAssoc($result)) {
					$idTemp = $tabla["idControl"];
					$objTemp = new Control();
					$objTemp->obtenerBD($idTemp);
					array_push($this->arrControles, $objTemp);
				}
			}
		}
	}

	public function cargarArreglosDiagnosticoDiagramaDermatologico() {
		if ($this->idDiagnostico != 0) {				
			$sql = "SELECT [idLesion] FROM [diagramaDermatologico] WHERE idDiagnostico = " . $this->idDiagnostico . ";";			
			$result = ejecutaQueryClases($sql);
			
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sqlA;
			} else {
				while ($tabla = devuelveRowAssoc($result)) {
					$idTemp = $tabla["idLesion"];
					$objTemp = new DiagramaDermatologico();
					$objTemp->obtenerBD($idTemp);
					array_push($this->arrDiagramaDermatologico, $objTemp);
				}
			}
		}
	}
}
?>