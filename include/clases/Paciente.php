<?php
class Paciente {
	
	public $idPaciente = 0;						// int				1
	public $nombre;								// varchar(20)		Luis
	public $apellidoPaterno;					// varchar(20)		Robledo
	public $apellidoMaterno;					// varchar(20)		Sanchez
	public $sexo;								// char(1)			H
	public $fechaNacimiento;					// date				1976/07/13
	public $cveExpediente;						// varchar(18)		ROSL760713HDFBNS01
	public $idCatTipoPaciente;					// int				1
	public $idCatMunicipioNacimiento;			// int				3821
	public $idCatEstadoNacimiento;				// int				982
	public $idCatLocalidad;						// int				1945
	public $idCatMunicipio;						// int				7123
	public $idCatEstado;						// int				12
	public $idCatUnidadNotificante;				// varchar(11)		CSSSA0001892
	public $idCatFormaDeteccion;				// int				1
	public $fechaInicioPadecimiento;			// date				1998/03/01
	public $fechaDiagnostico;					// date				1999/09/10
	////////////								Posibles nulos
	public $fechaNotificacion;					// date				1999/09/11
	public $semanaEpidemiologica;				// tinyint			8
	public $ocupacion;							// varchar(20)		Licenciado
	public $calle;								// varchar(40)		Av. Siempre Viva
	public $noExterior;							// varchar(6)		707
	public $noInterior;							// varchar(6)		A2-202
	public $colonia;							// varchar(15)		El Retiro
	public $telefono;							// varchar(16)		961-887-1289
	public $anosRadicando;						// tinyint			4
	public $mesesRadicando;						// tinyint			1
	public $idCatInstitucionUnidadNotificante;	// int				5464
	public $otraInstitucionUnidadNotificante;	// varchar(15)		Clinica Privada 
	public $idCatInstitucionDerechohabiencia;	// int				87
	public $otraDerechohabiencia;				// varchar(12)		SPDA
	public $fechaInicioPQT;						// date				2000/01/14
	public $idCatUnidadReferido;
	public $idCatUnidadTratante;				// int				122
	public $idCatInstitucionTratante;			// int				874
	public $otraInstitucionTratante;			// varchar(12)		Otra
    public $idCatEstadoReferido;
	public $fechaDxHisto;
	public $fechaDxBacil;
	public $edad;
	public $celularContacto;
	public $campoExtrangero;
    public $folioRegistro;
    public $medicoElaboro;				// string				0
	public $medicoValido;				// string				0

	public $error = false;
	public $msgError;

	// ARREGLOS VINCULADOS AL PACIENTE
	public $arrDiagnosticos = array();
		
    public function insertarBD() {
        // Se calcula el folio de registro manualmente contando todos los registros actuales en la base de datos
        // revisar si no entra en conflicto con la concurrencia
        $resultFolio = ejecutaQueryClases('SELECT ( COUNT([idPaciente])+1 ) AS folio FROM [pacientes]');
        $folio = devuelveRowAssoc($resultFolio);
        
        $resultEdoFolio = ejecutaQueryClases("SELECT [idCatEstado] FROM [catUnidad] WHERE [idCatUnidad] = '".$this->idCatUnidadTratante."'");
        $edoFolio = devuelveRowAssoc($resultEdoFolio);
        
        $this->folioRegistro = 'LEP'.str_pad($edoFolio['idCatEstado'],2,'0',STR_PAD_LEFT).str_pad($folio['folio'],5,'0',STR_PAD_LEFT);
        
        $sqlA = "INSERT INTO [pacientes] ([nombre] ,[apellidoPaterno] ,[apellidoMaterno] ,[sexo] ,[fechaNacimiento] ,
        		[cveExpediente] ,[idCatTipoPaciente] ,[idCatMunicipioNacimiento] ,[idCatEstadoNacimiento] ,
        		[idCatLocalidad] ,[idCatMunicipio] ,[idCatEstado] ,[idCatUnidadNotificante] ,[idCatFormaDeteccion] ,
        		[fechaInicioPadecimiento] ,[fechaDiagnostico],[celularContacto]";
		$sqlB = "VALUES ('" . $this->nombre . "', '" . $this->apellidoPaterno . "', '" . $this->apellidoMaterno . "', '" . 
				$this->sexo . "', '" . formatFechaObj($this->fechaNacimiento, 'Y-m-d') . "', '" . $this->cveExpediente . "', " . 
				$this->idCatTipoPaciente . ", '" . $this->idCatMunicipioNacimiento . "', " . $this->idCatEstadoNacimiento . ", " . 
				$this->idCatLocalidad . ", " . $this->idCatMunicipio . ", " . $this->idCatEstado . ", '" . 
				$this->idCatUnidadNotificante . "', " . $this->idCatFormaDeteccion . ", '" . formatFechaObj($this->fechaInicioPadecimiento, 'Y-m-d') . "', '" . 
				formatFechaObj($this->fechaDiagnostico, 'Y-m-d') . "', '" . $this->celularContacto . "'";
     
		if($this->fechaNotificacion != '' && !is_null($this->fechaNotificacion)) { $sqlA .= ", [fechaNotificacion]"; $sqlB .= ", '" . formatFechaObj($this->fechaNotificacion, 'Y-m-d') . "'"; }
		if($this->semanaEpidemiologica != '' && !is_null($this->semanaEpidemiologica)) { $sqlA .= ", [semanaEpidemiologica]";$sqlB .= ", " . $this->semanaEpidemiologica; }
		if($this->ocupacion != '' && !is_null($this->ocupacion)) { $sqlA .= ", [ocupacion]"; $sqlB .= ", '" . $this->ocupacion . "'"; }
		if($this->calle != '' && !is_null($this->calle)) { $sqlA .= ", [calle]"; $sqlB .= ", '" . $this->calle . "'"; }
		if($this->noExterior != '' && !is_null($this->noExterior)) { $sqlA .= ", [noExterior]"; $sqlB .= ", '" . $this->noExterior . "'"; }
		if($this->noInterior != '' && !is_null($this->noInterior)) { $sqlA .= ", [noInterior]"; $sqlB .= ", '" . $this->noInterior . "'"; }
		//if($this->celularContacto != '' && !is_null($this->celularContacto)) { $sqlA .= ", [celularContacto]"; $sqlB .= ", '" . $this->celularContacto. "'"; }
		if($this->colonia != '' && !is_null($this->colonia)) { $sqlA .= ", [colonia]"; $sqlB .= ", '" . $this->colonia . "'"; }
		if($this->telefono != '' && !is_null($this->telefono)) { $sqlA .= ", [telefono]"; $sqlB .= ", '" . $this->telefono . "'"; }
		if($this->anosRadicando != '' && !is_null($this->anosRadicando)) { $sqlA .= ", [anosRadicando]";$sqlB .= ", " . $this->anosRadicando; }
		if($this->mesesRadicando != '' && !is_null($this->mesesRadicando)) { $sqlA .= ", [mesesRadicando]"; $sqlB .= ", " . $this->mesesRadicando; }
		if($this->idCatInstitucionUnidadNotificante != '' && !is_null($this->idCatInstitucionUnidadNotificante)) { $sqlA .= ", [idCatInstitucionUnidadNotificante]"; $sqlB .= ", " . $this->idCatInstitucionUnidadNotificante; }
		if($this->otraInstitucionUnidadNotificante != '' && !is_null($this->otraInstitucionUnidadNotificante)) { $sqlA .= ", [otraInstitucionUnidadNotificante]"; $sqlB .= ", '" . $this->otraInstitucionUnidadNotificante . "'"; }
		if($this->idCatInstitucionDerechohabiencia != '' && !is_null($this->idCatInstitucionDerechohabiencia)) { $sqlA .= ", [idCatInstitucionDerechohabiencia]"; $sqlB .= ", " . $this->idCatInstitucionDerechohabiencia; }
		if($this->otraDerechohabiencia != '' && !is_null($this->otraDerechohabiencia)) { $sqlA .= ", [otraDerechohabiencia]"; $sqlB .= ", '" . $this->otraDerechohabiencia . "'"; }
		if($this->fechaInicioPQT != '' && !is_null($this->fechaInicioPQT)) { $sqlA .= ", [fechaInicioPQT]"; $sqlB .= ", '" . formatFechaObj($this->fechaInicioPQT, 'Y-m-d') . "'"; }
		if($this->idCatUnidadReferido != '' && !is_null($this->idCatUnidadReferido)) { $sqlA .= ", [idCatUnidadReferido]"; $sqlB .= ", '" . $this->idCatUnidadReferido . "'"; }
		if($this->idCatUnidadTratante != '' && !is_null($this->idCatUnidadTratante)) { $sqlA .= ", [idCatUnidadTratante]"; $sqlB .= ", '" . $this->idCatUnidadTratante . "'"; }
		if($this->idCatInstitucionTratante != '' && !is_null($this->idCatInstitucionTratante)) { $sqlA .= ", [idCatInstitucionTratante]"; $sqlB .= ", " . $this->idCatInstitucionTratante . " "; }
		if($this->campoExtrangero != '' && !is_null($this->campoExtrangero)) { $sqlA .= ", [campoExtrangero]"; $sqlB .= ", '" . $this->campoExtrangero . "'"; }
		if($this->otraInstitucionTratante != '' && !is_null($this->otraInstitucionTratante)) { $sqlA .= ", [otraInstitucionTratante]"; $sqlB .= ", '" . $this->otraInstitucionTratante . "'"; }
        if($this->idCatEstadoReferido != '' && !is_null($this->idCatEstadoReferido)) { $sqlA .= ", [idCatEstadoReferido]"; $sqlB .= ", '" . $this->idCatEstadoReferido . "'"; }
        if($this->folioRegistro != '' && !is_null($this->folioRegistro)) { $sqlA .= ", [folioRegistro]"; $sqlB .= ", '" . $this->folioRegistro . "'"; }				
        if($this->medicoElaboro != '' && !is_null($this->medicoElaboro)) { $sqlA .= ", [medicoElaboro]"; $sqlB .= ", '" . $this->medicoElaboro . "'"; }
		if($this->medicoValido != '' && !is_null($this->medicoValido)) { $sqlA .= ", [medicoValido]"; $sqlB .= ", '" . $this->medicoValido . "'"; }

		$sqlA .= ") " . $sqlB . "); SELECT @@Identity AS nuevoId;";
		
		$consulta = ejecutaQueryClases($sqlA);		
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idPaciente = $tabla["nuevoId"];
		}
    }

	public function modificarBD() {
		$sql = "UPDATE [pacientes] SET ".
			"[nombre] = '" . $this->nombre . "' " .
			",[apellidoPaterno] = '" . $this->apellidoPaterno . "' " .
			",[apellidoMaterno] = '" . $this->apellidoMaterno . "' " .
			",[sexo] = '" . $this->sexo . "' " .
			",[fechaNacimiento] = '" . formatFechaObj($this->fechaNacimiento, 'Y-m-d') . "' " .
			",[cveExpediente] = '" . $this->cveExpediente . "' " .
			",[idCatTipoPaciente] = " . $this->idCatTipoPaciente . " " .
			",[idCatMunicipioNacimiento] = '" . $this->idCatMunicipioNacimiento . "' " .
			",[idCatEstadoNacimiento] = " . $this->idCatEstadoNacimiento . " " .
			",[idCatLocalidad] = " . $this->idCatLocalidad . " " .
			",[idCatMunicipio] = " . $this->idCatMunicipio . " " .
			",[idCatEstado] = " . $this->idCatEstado . " " .
			",[idCatUnidadNotificante] = '" . $this->idCatUnidadNotificante . "' " .
			",[idCatFormaDeteccion] = " . $this->idCatFormaDeteccion . " " .
			",[fechaInicioPadecimiento] = '" . formatFechaObj($this->fechaInicioPadecimiento, 'Y-m-d') . "' " .
			",[fechaDiagnostico] = '" . formatFechaObj($this->fechaDiagnostico, 'Y-m-d') . "' ";
			",[folioRegistro] = '" . $this->folioRegistro . "' ";
			
		if($this->fechaNotificacion != '' && !is_null($this->fechaNotificacion)) { $sql .= ",[fechaNotificacion] = '" . formatFechaObj($this->fechaNotificacion, 'Y-m-d') . "' "; }
		if($this->semanaEpidemiologica != '' && !is_null($this->semanaEpidemiologica)) { $sql .= ",[semanaEpidemiologica] = " . $this->semanaEpidemiologica . " "; }
		if($this->ocupacion != '' && !is_null($this->ocupacion)) { $sql .= ",[ocupacion] = '" . $this->ocupacion . "' "; }
		if($this->calle != '' && !is_null($this->calle)) { $sql .= ",[calle] = '" . $this->calle . "' "; }
		if($this->noExterior != '' && !is_null($this->noExterior)) { $sql .= ",[noExterior] = '" . $this->noExterior . "' "; }
		if($this->noInterior != '' && !is_null($this->noInterior)) { $sql .= ",[noInterior] = '" . $this->noInterior . "' "; }
		if($this->colonia != '' && !is_null($this->colonia)) { $sql .= ",[colonia] = '" . $this->colonia . "' "; }
		if($this->telefono != '' && !is_null($this->telefono)) { $sql .= ",[telefono] = '" . $this->telefono . "' "; }
		if($this->celularContacto != '' && !is_null($this->celularContacto)) { $sql .= ",[celularContacto] = '" . $this->celularContacto . "' "; }
		if($this->anosRadicando != '' && !is_null($this->anosRadicando)) { $sql .= ",[anosRadicando] = " . $this->anosRadicando . " "; }
		if($this->mesesRadicando != '' && !is_null($this->mesesRadicando)) { $sql .= ",[mesesRadicando] = " . $this->mesesRadicando . " "; }
		if($this->idCatInstitucionUnidadNotificante != '' && !is_null($this->idCatInstitucionUnidadNotificante)) { $sql .= ",[idCatInstitucionUnidadNotificante] = " . $this->idCatInstitucionUnidadNotificante . " "; }
		if($this->otraInstitucionUnidadNotificante != '' && !is_null($this->otraInstitucionUnidadNotificante)) { $sql .= ",[otraInstitucionUnidadNotificante] = '" . $this->otraInstitucionUnidadNotificante . "' "; }
		if($this->idCatInstitucionDerechohabiencia != '' && !is_null($this->idCatInstitucionDerechohabiencia)) { $sql .= ",[idCatInstitucionDerechohabiencia] = " . $this->idCatInstitucionDerechohabiencia . " "; }
		if($this->otraDerechohabiencia != '' && !is_null($this->otraDerechohabiencia)) { $sql .= ",[otraDerechohabiencia] = '" . $this->otraDerechohabiencia . "' "; }
		if($this->fechaInicioPQT != '' && !is_null($this->fechaInicioPQT)) { $sql .= ",[fechaInicioPQT] = '" . formatFechaObj($this->fechaInicioPQT, 'Y-m-d') . "' "; }
		if($this->idCatUnidadReferido != '' && !is_null($this->idCatUnidadReferido)) { $sql .= ",[idCatUnidadReferido] = '" . $this->idCatUnidadReferido . "' "; }
		if($this->idCatUnidadTratante != '' && !is_null($this->idCatUnidadTratante)) { $sql .= ",[idCatUnidadTratante] = '" . $this->idCatUnidadTratante . "' "; }
		if($this->idCatInstitucionTratante != '' && !is_null($this->idCatInstitucionTratante)) { $sql .= ",[idCatInstitucionTratante] = " . $this->idCatInstitucionTratante . " "; }
		if($this->otraInstitucionTratante != '' && !is_null($this->otraInstitucionTratante)) { $sql .= ",[otraInstitucionTratante] = '" . $this->otraInstitucionTratante . "' "; }
        if($this->idCatEstadoReferido != '' && !is_null($this->idCatEstadoReferido)) { $sql .= ",[idCatEstadoReferido] = '" . $this->idCatEstadoReferido . "' "; }
		if($this->campoExtrangero != '' && !is_null($this->campoExtrangero)) { $sql .= ",[campoExtrangero] = '" . $this->campoExtrangero . "' "; }
        if($this->medicoElaboro != '' && !is_null($this->medicoElaboro)) { $sql .= ",[medicoElaboro] = '" . $this->medicoElaboro . "' "; }
		if($this->medicoValido != '' && !is_null($this->medicoValido)) { $sql .= ",[medicoValido] = '" . $this->medicoValido . "' "; }

        $sql .= "WHERE idPaciente = " . $this->idPaciente . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

	public function obtenerBD($idPaciente) {
		$sql = "SELECT * FROM [pacientes] WHERE idPaciente = " . $idPaciente . ";";		
		$consulta = ejecutaQueryClases($sql);
		
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			$this->idPaciente =  $tabla["idPaciente"];
			$this->nombre =  $tabla["nombre"];
			$this->apellidoPaterno =  $tabla["apellidoPaterno"];
			$this->apellidoMaterno =  $tabla["apellidoMaterno"];
			$this->sexo =  $tabla["sexo"];
			$this->fechaNacimiento =  $tabla["fechaNacimiento"];
			$this->cveExpediente =  $tabla["cveExpediente"];
			$this->idCatTipoPaciente =  $tabla["idCatTipoPaciente"];
			$this->idCatMunicipioNacimiento =  $tabla["idCatMunicipioNacimiento"];
			$this->idCatEstadoNacimiento =  $tabla["idCatEstadoNacimiento"];
			$this->idCatLocalidad =  $tabla["idCatLocalidad"];
			$this->idCatMunicipio =  $tabla["idCatMunicipio"];
			$this->idCatEstado =  $tabla["idCatEstado"];
			$this->idCatUnidadNotificante =  $tabla["idCatUnidadNotificante"];
			$this->idCatFormaDeteccion =  $tabla["idCatFormaDeteccion"];
			$this->fechaInicioPadecimiento =  $tabla["fechaInicioPadecimiento"];
			$this->fechaDiagnostico =  $tabla["fechaDiagnostico"];			
			$this->folioRegistro =  $tabla["folioRegistro"];			
			////////////Posibles nulos
			if (!is_null($tabla["fechaNotificacion"])) { $this->fechaNotificacion =  $tabla["fechaNotificacion"]; }
			if (!is_null($tabla["semanaEpidemiologica"])) { $this->semanaEpidemiologica =  $tabla["semanaEpidemiologica"]; }
			if (!is_null($tabla["ocupacion"])) { $this->ocupacion =  $tabla["ocupacion"]; }
			if (!is_null($tabla["calle"])) { $this->calle =  $tabla["calle"]; }
			if (!is_null($tabla["noExterior"])) { $this->noExterior =  $tabla["noExterior"]; }
			if (!is_null($tabla["noInterior"])) { $this->noInterior =  $tabla["noInterior"]; }
			if (!is_null($tabla["colonia"])) { $this->colonia =  $tabla["colonia"]; }
			if (!is_null($tabla["telefono"])) { $this->telefono =  $tabla["telefono"]; }
			if (!is_null($tabla["celularContacto"])) { $this->celularContacto =  $tabla["celularContacto"]; }
			if (!is_null($tabla["anosRadicando"])) { $this->anosRadicando =  $tabla["anosRadicando"]; }
			if (!is_null($tabla["mesesRadicando"])) { $this->mesesRadicando =  $tabla["mesesRadicando"]; }
			if (!is_null($tabla["idCatInstitucionUnidadNotificante"])) { $this->idCatInstitucionUnidadNotificante =  $tabla["idCatInstitucionUnidadNotificante"]; }
			if (!is_null($tabla["otraInstitucionUnidadNotificante"])) { $this->otraInstitucionUnidadNotificante =  $tabla["otraInstitucionUnidadNotificante"]; }
			if (!is_null($tabla["idCatInstitucionDerechohabiencia"])) { $this->idCatInstitucionDerechohabiencia =  $tabla["idCatInstitucionDerechohabiencia"]; }
			if (!is_null($tabla["otraDerechohabiencia"])) { $this->otraDerechohabiencia =  $tabla["otraDerechohabiencia"]; }
			if (!is_null($tabla["fechaInicioPQT"])) { $this->fechaInicioPQT =  $tabla["fechaInicioPQT"]; }
			if (!is_null($tabla["idCatUnidadReferido"])) { $this->idCatUnidadReferido =  $tabla["idCatUnidadReferido"]; }
			if (!is_null($tabla["idCatUnidadTratante"])) { $this->idCatUnidadTratante =  $tabla["idCatUnidadTratante"]; }
			if (!is_null($tabla["idCatInstitucionTratante"])) { $this->idCatInstitucionTratante =  $tabla["idCatInstitucionTratante"]; }
			if (!is_null($tabla["otraInstitucionTratante"])) { $this->otraInstitucionTratante =  $tabla["otraInstitucionTratante"]; }
            if (!is_null($tabla["idCatEstadoReferido"])) { $this->idCatEstadoReferido =  $tabla["idCatEstadoReferido"]; }
			if (!is_null($tabla["campoExtrangero"])) { $this->campoExtrangero =  $tabla["campoExtrangero"]; }
            if (!is_null($tabla["medicoElaboro"])) { $this->medicoElaboro = $tabla["medicoElaboro"]; }
			if (!is_null($tabla["medicoValido"])) { $this->medicoValido = $tabla["medicoValido"]; }
		}
		
		if($this->idCatTipoPaciente != 5)
		{
			$sql = "SELECT * FROM [estudiosBac] WHERE idPaciente = " . $this->idPaciente . " AND idCatTipoEstudio = 1 ORDER BY fechaResultado;";
			$consulta = ejecutaQueryClases($sql);
			
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$infoEstudio = devuelveRowAssoc($consulta);
				$this->fechaDxBacil = $infoEstudio['fechaResultado'];
			}
			
			if($this->fechaDxBacil == "" || $this->fechaDxBacil == NULL)
			{
				$sql = "SELECT * FROM lepra.dbo.diagnostico WHERE idPaciente = ".$this->idPaciente;
				$consulta = ejecutaQueryClases($sql);
				
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql;
				} else {
					$infoEstudio = devuelveRowAssoc($consulta);
					$idDiagnostico = $infoEstudio['idDiagnostico'];
				}
				
				
				$sql = "SELECT * FROM [estudiosBac] WHERE idDiagnostico = " . $idDiagnostico . " AND idCatTipoEstudio = 1 ORDER BY fechaResultado;";
				$consulta = ejecutaQueryClases($sql);
				
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql;
				} else {
					$infoEstudio = devuelveRowAssoc($consulta);
					$this->fechaDxBacil = $infoEstudio['fechaResultado'];
				}
			}
			
			$sql = "SELECT * FROM [estudiosHis] WHERE idPaciente = " . $this->idPaciente . " AND idCatTipoEstudio = 1 ORDER BY fechaResultado;";
			$consulta = ejecutaQueryClases($sql);
			
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				$infoEstudio = devuelveRowAssoc($consulta);
				$this->fechaDxHisto = $infoEstudio['fechaResultado'];
			}
			
			if($this->fechaDxHisto == "" || $this->fechaDxHisto == NULL)
			{
				$sql = "SELECT * FROM lepra.dbo.diagnostico WHERE idPaciente = ".$this->idPaciente;
				$consulta = ejecutaQueryClases($sql);
				
				if (is_string($consulta)) {
					$this->error = true;
					$this->msgError = $consulta . " SQL:" . $sql;
				} else {
					$infoEstudio = devuelveRowAssoc($consulta);
					$idDiagnostico = $infoEstudio['idDiagnostico'];
				}
				
				if($idDiagnostico != NULL && $idDiagnostico != "")
				{
					$sql = "SELECT * FROM [estudiosHis] WHERE idDiagnostico = " . $idDiagnostico . " AND idCatTipoEstudio = 1 ORDER BY fechaResultado;";
					$consulta = ejecutaQueryClases($sql);
					
					if (is_string($consulta)) {
						$this->error = true;
						$this->msgError = $consulta . " SQL:" . $sql;
					} else {
						$infoEstudio = devuelveRowAssoc($consulta);
						$this->fechaDxHisto = $infoEstudio['fechaResultado'];
					}
				}
			}
		}
	}

	public function cargarArreglosPaciente() {
		if ($this->idPaciente != 0) {					
			$sql = "SELECT [idDiagnostico] FROM [diagnostico] WHERE idPaciente = " . $this->idPaciente . ";";
			$result = ejecutaQueryClases($sql);
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sqlA;
			} else {
				while ($tabla = devuelveRowAssoc($result)) {
					$idTemp = $tabla["idDiagnostico"];
					$objTemp = new Diagnostico();
					$objTemp->obtenerBD($idTemp);
					array_push($this->arrDiagnosticos, $objTemp);
				}
			}
		}
	}
}
?>