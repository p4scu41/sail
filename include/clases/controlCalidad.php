<?php
class controlCalidad {

	public $idcontrolCalidad; // int IDENTITY(1,1) NOT NULL,
	public $idEstudioHis; // int,
	public $idEstudioBac; // int,
	public $calidadMuestra; // varchar(1) NOT NULL,
	public $sinMuestra; // bit,
	public $sinElemeCelu; // bit,
	public $abunEritro; // bit,
	public $otrosCalidadMuestra; // varchar(50),
	
	public $calidadFrotis; // varchar(1) NOT NULL,
	public $calidadFrotisTipo; // int NOT NULL,
	public $otrosCalidadFrotis; // varchar(50),
	
	public $calidadTincion; // varchar(1) NOT NULL,
	public $crisFucsi; // bit,
	public $preciFucsi; // bit,
	public $calenExce; // bit,
	public $decoInsufi; // bit,
	public $otrosCalidadTincion; // varchar(50),
	
	public $calidadLectura; // varchar(1) NOT NULL,
	public $falPosi; // bit,
	public $falNega; // bit,
	public $difMas2IB; // bit,
	public $difMas25IM; // bit,
	public $otrosCalidadLectura; // varchar(50),
	
	public $calidadResultado; // varchar(1) NOT NULL,
	public $soloSimbCruz; // bit,
	public $soloPosiNega; // bit,
	public $noEmiteIM; // bit,
	public $otrosCalidadResultado; // varchar(50),
	public $recomendacion; // int
    
    public $idUsuario; // int
    public $fechaCaptura; // int

	public $error = false;
	public $msgError;

	public function insertarBD() {
		$sqlA = 'INSERT INTO [controlCalidad] ([idEstudioHis], [idEstudioBac] ';
		$sqlB = 'VALUES (' . (int)$this->idEstudioHis . ', ' . (int)$this->idEstudioBac ;
		
		if($this->calidadMuestra != '' && !is_null($this->calidadMuestra)) { $sqlA .= ', [calidadMuestra]'; $sqlB .= ', \'' . $this->calidadMuestra .'\''; }
		if($this->sinMuestra != '' && !is_null($this->sinMuestra)) { $sqlA .= ', [sinMuestra]'; $sqlB .= ', \'' . $this->sinMuestra .'\''; }
        if($this->sinElemeCelu != '' && !is_null($this->sinElemeCelu)) { $sqlA .= ', [sinElemeCelu]'; $sqlB .= ', \'' . $this->sinElemeCelu .'\''; }
        if($this->abunEritro != '' && !is_null($this->abunEritro)) { $sqlA .= ', [abunEritro]'; $sqlB .= ', \'' . $this->abunEritro .'\''; }
        if($this->otrosCalidadMuestra != '' && !is_null($this->otrosCalidadMuestra)) { $sqlA .= ', [otrosCalidadMuestra]'; $sqlB .= ', \'' . $this->otrosCalidadMuestra .'\''; }
        if($this->calidadFrotis != '' && !is_null($this->calidadFrotis)) { $sqlA .= ', [calidadFrotis]'; $sqlB .= ', \'' . $this->calidadFrotis .'\''; }
        if($this->calidadFrotisTipo != '' && !is_null($this->calidadFrotisTipo)) { $sqlA .= ', [calidadFrotisTipo]'; $sqlB .= ', \'' . $this->calidadFrotisTipo .'\''; }
        if($this->otrosCalidadFrotis != '' && !is_null($this->otrosCalidadFrotis)) { $sqlA .= ', [otrosCalidadFrotis]'; $sqlB .= ', \'' . $this->otrosCalidadFrotis .'\''; }
        if($this->calidadTincion != '' && !is_null($this->calidadTincion)) { $sqlA .= ', [calidadTincion]'; $sqlB .= ', \'' . $this->calidadTincion .'\''; }
        if($this->crisFucsi != '' && !is_null($this->crisFucsi)) { $sqlA .= ', [crisFucsi]'; $sqlB .= ', \'' . $this->crisFucsi .'\''; }
        if($this->preciFucsi != '' && !is_null($this->preciFucsi)) { $sqlA .= ', [preciFucsi]'; $sqlB .= ', \'' . $this->preciFucsi .'\''; }
        if($this->calenExce != '' && !is_null($this->calenExce)) { $sqlA .= ', [calenExce]'; $sqlB .= ', \'' . $this->calenExce .'\''; }
        if($this->decoInsufi != '' && !is_null($this->decoInsufi)) { $sqlA .= ', [decoInsufi]'; $sqlB .= ', \'' . $this->decoInsufi .'\''; }
        if($this->otrosCalidadTincion != '' && !is_null($this->otrosCalidadTincion)) { $sqlA .= ', [otrosCalidadTincion]'; $sqlB .= ', \'' . $this->otrosCalidadTincion .'\''; }
        if($this->calidadLectura != '' && !is_null($this->calidadLectura)) { $sqlA .= ', [calidadLectura]'; $sqlB .= ', \'' . $this->calidadLectura .'\''; }
        if($this->falPosi != '' && !is_null($this->falPosi)) { $sqlA .= ', [falPosi]'; $sqlB .= ', \'' . $this->falPosi .'\''; }
        if($this->falNega != '' && !is_null($this->falNega)) { $sqlA .= ', [falNega]'; $sqlB .= ', \'' . $this->falNega .'\''; }
        if($this->difMas2IB != '' && !is_null($this->difMas2IB)) { $sqlA .= ', [difMas2IB]'; $sqlB .= ', \'' . $this->difMas2IB .'\''; }
        if($this->difMas25IM != '' && !is_null($this->difMas25IM)) { $sqlA .= ', [difMas25IM]'; $sqlB .= ', \'' . $this->difMas25IM .'\''; }
        if($this->otrosCalidadLectura != '' && !is_null($this->otrosCalidadLectura)) { $sqlA .= ', [otrosCalidadLectura]'; $sqlB .= ', \'' . $this->otrosCalidadLectura .'\''; }
        if($this->calidadResultado != '' && !is_null($this->calidadResultado)) { $sqlA .= ', [calidadResultado]'; $sqlB .= ', \'' . $this->calidadResultado .'\''; }
        if($this->soloSimbCruz != '' && !is_null($this->soloSimbCruz)) { $sqlA .= ', [soloSimbCruz]'; $sqlB .= ', \'' . $this->soloSimbCruz .'\''; }
        if($this->soloPosiNega != '' && !is_null($this->soloPosiNega)) { $sqlA .= ', [soloPosiNega]'; $sqlB .= ', \'' . $this->soloPosiNega .'\''; }
        if($this->noEmiteIM != '' && !is_null($this->noEmiteIM)) { $sqlA .= ', [noEmiteIM]'; $sqlB .= ', \'' . $this->noEmiteIM .'\''; }
        if($this->otrosCalidadResultado != '' && !is_null($this->otrosCalidadResultado)) { $sqlA .= ', [otrosCalidadResultado]'; $sqlB .= ', \'' . $this->otrosCalidadResultado .'\''; }
        if($this->recomendacion != '' && !is_null($this->recomendacion)) { $sqlA .= ', [recomendacion]'; $sqlB .= ', \'' . $this->recomendacion .'\''; }
		
        $sqlA .= ", [idUsuario], [fechaCaptura]) " . $sqlB . ", ".$_SESSION[ID_USR_SESSION].", CONVERT(datetime, GETDATE())); SELECT @@Identity AS nuevoId;";	
		$consulta = ejecutaQueryClases($sqlA);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idcontrolCalidad = $tabla["nuevoId"];
		}
	}

	public function modificarBD() {
		$sql = 'UPDATE [controlCalidad] SET ';
		$sql .= ' [idEstudioBac] = ' . $this->idEstudioBac . ', [idEstudioHis] = ' . $this->idEstudioHis . ' ';
        		
        $sql .= ', [calidadMuestra] = \'' . $this->calidadMuestra .'\''; 
		$sql .= ', [sinMuestra] = \'' . $this->sinMuestra .'\''; 
        $sql .= ', [sinElemeCelu] = \'' . $this->sinElemeCelu .'\''; 
        $sql .= ', [abunEritro] = \'' . $this->abunEritro .'\''; 
        $sql .= ', [otrosCalidadMuestra] = \'' . $this->otrosCalidadMuestra .'\''; 
        $sql .= ', [calidadFrotis] = \'' . $this->calidadFrotis .'\''; 
        $sql .= ', [calidadFrotisTipo] = \'' . $this->calidadFrotisTipo .'\''; 
        $sql .= ', [otrosCalidadFrotis] = \'' . $this->otrosCalidadFrotis .'\''; 
        $sql .= ', [calidadTincion] = \'' . $this->calidadTincion .'\''; 
        $sql .= ', [crisFucsi] = \'' . $this->crisFucsi .'\''; 
        $sql .= ', [preciFucsi] = \'' . $this->preciFucsi .'\''; 
        $sql .= ', [calenExce] = \'' . $this->calenExce .'\''; 
        $sql .= ', [decoInsufi] = \'' . $this->decoInsufi .'\''; 
        $sql .= ', [otrosCalidadTincion] = \'' . $this->otrosCalidadTincion .'\''; 
        $sql .= ', [calidadLectura] = \'' . $this->calidadLectura .'\''; 
        $sql .= ', [falPosi] = \'' . $this->falPosi .'\'';
        $sql .= ', [falNega] = \'' . $this->falNega .'\'';
        $sql .= ', [difMas2IB] = \'' . $this->difMas2IB .'\'';
        $sql .= ', [difMas25IM] = \'' . $this->difMas25IM .'\'';
        $sql .= ', [otrosCalidadLectura] = \'' . $this->otrosCalidadLectura .'\'';
        $sql .= ', [calidadResultado] = \'' . $this->calidadResultado .'\'';
        $sql .= ', [soloSimbCruz] = \'' . $this->soloSimbCruz .'\'';
        $sql .= ', [soloPosiNega] = \'' . $this->soloPosiNega .'\'';
        $sql .= ', [noEmiteIM] = \'' . $this->noEmiteIM .'\'';
        $sql .= ', [otrosCalidadResultado] = \'' . $this->otrosCalidadResultado .'\'';
        $sql .= ', [recomendacion] = \'' . $this->recomendacion .'\'';
        
        $sql .= "WHERE idcontrolCalidad = " . $this->idcontrolCalidad . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

	public function obtenerBD($idcontrolCalidad) {
		$sql = "SELECT * FROM [controlCalidad] WHERE idcontrolCalidad = " . $idcontrolCalidad;
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			$this->idcontrolCalidad = $tabla["idcontrolCalidad"];
			$this->idEstudioHis = $tabla["idEstudioHis"];
			$this->idEstudioBac = $tabla["idEstudioBac"];			
			////////////							Posibles nulos
			if (!is_null($tabla["calidadMuestra"])) { $this->calidadMuestra = $tabla["calidadMuestra"]; }
			if (!is_null($tabla["sinMuestra"])) { $this->sinMuestra = $tabla["sinMuestra"]; }
			if (!is_null($tabla["sinElemeCelu"])) { $this->sinElemeCelu = $tabla["sinElemeCelu"]; }
			if (!is_null($tabla["abunEritro"])) { $this->abunEritro = $tabla["abunEritro"]; }
			if (!is_null($tabla["otrosCalidadMuestra"])) { $this->otrosCalidadMuestra = $tabla["otrosCalidadMuestra"]; }
			if (!is_null($tabla["calidadFrotis"])) { $this->calidadFrotis = $tabla["calidadFrotis"]; }
			if (!is_null($tabla["calidadFrotisTipo"])) { $this->calidadFrotisTipo = $tabla["calidadFrotisTipo"]; }
			if (!is_null($tabla["otrosCalidadFrotis"])) { $this->otrosCalidadFrotis = $tabla["otrosCalidadFrotis"]; }
			if (!is_null($tabla["calidadTincion"])) { $this->calidadTincion = $tabla["calidadTincion"]; }
			if (!is_null($tabla["crisFucsi"])) { $this->crisFucsi = $tabla["crisFucsi"]; }
			if (!is_null($tabla["preciFucsi"])) { $this->preciFucsi = $tabla["preciFucsi"]; }
			if (!is_null($tabla["calenExce"])) { $this->calenExce = $tabla["calenExce"]; }
			if (!is_null($tabla["decoInsufi"])) { $this->decoInsufi = $tabla["decoInsufi"]; }
			if (!is_null($tabla["otrosCalidadTincion"])) { $this->otrosCalidadTincion = $tabla["otrosCalidadTincion"]; }
			if (!is_null($tabla["calidadLectura"])) { $this->calidadLectura = $tabla["calidadLectura"]; }
			if (!is_null($tabla["falPosi"])) { $this->falPosi = $tabla["falPosi"]; }
			if (!is_null($tabla["falNega"])) { $this->falNega = $tabla["falNega"]; }
			if (!is_null($tabla["difMas2IB"])) { $this->difMas2IB = $tabla["difMas2IB"]; }
			if (!is_null($tabla["difMas25IM"])) { $this->difMas25IM = $tabla["difMas25IM"]; }
			if (!is_null($tabla["otrosCalidadLectura"])) { $this->otrosCalidadLectura = $tabla["otrosCalidadLectura"]; }
			if (!is_null($tabla["calidadResultado"])) { $this->calidadResultado = $tabla["calidadResultado"]; }
			if (!is_null($tabla["soloSimbCruz"])) { $this->soloSimbCruz = $tabla["soloSimbCruz"]; }
			if (!is_null($tabla["soloPosiNega"])) { $this->soloPosiNega = $tabla["soloPosiNega"]; }
			if (!is_null($tabla["noEmiteIM"])) { $this->noEmiteIM = $tabla["noEmiteIM"]; }
			if (!is_null($tabla["otrosCalidadResultado"])) { $this->otrosCalidadResultado = $tabla["otrosCalidadResultado"]; }
			if (!is_null($tabla["recomendacion"])) { $this->recomendacion = $tabla["recomendacion"]; }
						
		}
	}
    
    public function obtenerByBacilos($idEstudioBac) {
		$sql = "SELECT * FROM [controlCalidad] WHERE idEstudioBac = " . $idEstudioBac;
        
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			$this->idcontrolCalidad = $tabla["idcontrolCalidad"];
			$this->idEstudioHis = $tabla["idEstudioHis"];
			$this->idEstudioBac = $idEstudioBac;
			////////////							Posibles nulos
			if (!is_null($tabla["calidadMuestra"])) { $this->calidadMuestra = $tabla["calidadMuestra"]; }
			if (!is_null($tabla["sinMuestra"])) { $this->sinMuestra = $tabla["sinMuestra"]; }
			if (!is_null($tabla["sinElemeCelu"])) { $this->sinElemeCelu = $tabla["sinElemeCelu"]; }
			if (!is_null($tabla["abunEritro"])) { $this->abunEritro = $tabla["abunEritro"]; }
			if (!is_null($tabla["otrosCalidadMuestra"])) { $this->otrosCalidadMuestra = $tabla["otrosCalidadMuestra"]; }
			if (!is_null($tabla["calidadFrotis"])) { $this->calidadFrotis = $tabla["calidadFrotis"]; }
			if (!is_null($tabla["calidadFrotisTipo"])) { $this->calidadFrotisTipo = $tabla["calidadFrotisTipo"]; }
			if (!is_null($tabla["otrosCalidadFrotis"])) { $this->otrosCalidadFrotis = $tabla["otrosCalidadFrotis"]; }
			if (!is_null($tabla["calidadTincion"])) { $this->calidadTincion = $tabla["calidadTincion"]; }
			if (!is_null($tabla["crisFucsi"])) { $this->crisFucsi = $tabla["crisFucsi"]; }
			if (!is_null($tabla["preciFucsi"])) { $this->preciFucsi = $tabla["preciFucsi"]; }
			if (!is_null($tabla["calenExce"])) { $this->calenExce = $tabla["calenExce"]; }
			if (!is_null($tabla["decoInsufi"])) { $this->decoInsufi = $tabla["decoInsufi"]; }
			if (!is_null($tabla["otrosCalidadTincion"])) { $this->otrosCalidadTincion = $tabla["otrosCalidadTincion"]; }
			if (!is_null($tabla["calidadLectura"])) { $this->calidadLectura = $tabla["calidadLectura"]; }
			if (!is_null($tabla["falPosi"])) { $this->falPosi = $tabla["falPosi"]; }
			if (!is_null($tabla["falNega"])) { $this->falNega = $tabla["falNega"]; }
			if (!is_null($tabla["difMas2IB"])) { $this->difMas2IB = $tabla["difMas2IB"]; }
			if (!is_null($tabla["difMas25IM"])) { $this->difMas25IM = $tabla["difMas25IM"]; }
			if (!is_null($tabla["otrosCalidadLectura"])) { $this->otrosCalidadLectura = $tabla["otrosCalidadLectura"]; }
			if (!is_null($tabla["calidadResultado"])) { $this->calidadResultado = $tabla["calidadResultado"]; }
			if (!is_null($tabla["soloSimbCruz"])) { $this->soloSimbCruz = $tabla["soloSimbCruz"]; }
			if (!is_null($tabla["soloPosiNega"])) { $this->soloPosiNega = $tabla["soloPosiNega"]; }
			if (!is_null($tabla["noEmiteIM"])) { $this->noEmiteIM = $tabla["noEmiteIM"]; }
			if (!is_null($tabla["otrosCalidadResultado"])) { $this->otrosCalidadResultado = $tabla["otrosCalidadResultado"]; }
			if (!is_null($tabla["recomendacion"])) { $this->recomendacion = $tabla["recomendacion"]; }
						
		}
	}
    
    public function obtenerByHisto($idEstudioHis) {
		$sql = "SELECT * FROM [controlCalidad] WHERE idEstudioHis = " . $idEstudioHis;
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			$this->idcontrolCalidad = $tabla["idcontrolCalidad"];
			$this->idEstudioHis = $idEstudioHis;
			$this->idEstudioBac = $tabla["idEstudioBac"];
			////////////							Posibles nulos
			if (!is_null($tabla["calidadMuestra"])) { $this->calidadMuestra = $tabla["calidadMuestra"]; }
			if (!is_null($tabla["sinMuestra"])) { $this->sinMuestra = $tabla["sinMuestra"]; }
			if (!is_null($tabla["sinElemeCelu"])) { $this->sinElemeCelu = $tabla["sinElemeCelu"]; }
			if (!is_null($tabla["abunEritro"])) { $this->abunEritro = $tabla["abunEritro"]; }
			if (!is_null($tabla["otrosCalidadMuestra"])) { $this->otrosCalidadMuestra = $tabla["otrosCalidadMuestra"]; }
			if (!is_null($tabla["calidadFrotis"])) { $this->calidadFrotis = $tabla["calidadFrotis"]; }
			if (!is_null($tabla["calidadFrotisTipo"])) { $this->calidadFrotisTipo = $tabla["calidadFrotisTipo"]; }
			if (!is_null($tabla["otrosCalidadFrotis"])) { $this->otrosCalidadFrotis = $tabla["otrosCalidadFrotis"]; }
			if (!is_null($tabla["calidadTincion"])) { $this->calidadTincion = $tabla["calidadTincion"]; }
			if (!is_null($tabla["crisFucsi"])) { $this->crisFucsi = $tabla["crisFucsi"]; }
			if (!is_null($tabla["preciFucsi"])) { $this->preciFucsi = $tabla["preciFucsi"]; }
			if (!is_null($tabla["calenExce"])) { $this->calenExce = $tabla["calenExce"]; }
			if (!is_null($tabla["decoInsufi"])) { $this->decoInsufi = $tabla["decoInsufi"]; }
			if (!is_null($tabla["otrosCalidadTincion"])) { $this->otrosCalidadTincion = $tabla["otrosCalidadTincion"]; }
			if (!is_null($tabla["calidadLectura"])) { $this->calidadLectura = $tabla["calidadLectura"]; }
			if (!is_null($tabla["falPosi"])) { $this->falPosi = $tabla["falPosi"]; }
			if (!is_null($tabla["falNega"])) { $this->falNega = $tabla["falNega"]; }
			if (!is_null($tabla["difMas2IB"])) { $this->difMas2IB = $tabla["difMas2IB"]; }
			if (!is_null($tabla["difMas25IM"])) { $this->difMas25IM = $tabla["difMas25IM"]; }
			if (!is_null($tabla["otrosCalidadLectura"])) { $this->otrosCalidadLectura = $tabla["otrosCalidadLectura"]; }
			if (!is_null($tabla["calidadResultado"])) { $this->calidadResultado = $tabla["calidadResultado"]; }
			if (!is_null($tabla["soloSimbCruz"])) { $this->soloSimbCruz = $tabla["soloSimbCruz"]; }
			if (!is_null($tabla["soloPosiNega"])) { $this->soloPosiNega = $tabla["soloPosiNega"]; }
			if (!is_null($tabla["noEmiteIM"])) { $this->noEmiteIM = $tabla["noEmiteIM"]; }
			if (!is_null($tabla["otrosCalidadResultado"])) { $this->otrosCalidadResultado = $tabla["otrosCalidadResultado"]; }
			if (!is_null($tabla["recomendacion"])) { $this->recomendacion = $tabla["recomendacion"]; }
						
		}
	}
}
?>