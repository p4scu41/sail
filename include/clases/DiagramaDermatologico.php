<?php
class DiagramaDermatologico {

	public $idLesion = 0;		// int
	public $idDiagnostico;		// int
	public $idCatTipoLesion;	// int
	public $x;					// int
	public $y;					// int
	public $w;					// int
	public $h;					// int
	public $idPaciente;			// int
	public $imgUrl;				// text
    
	public $error = false;
	public $msgError;
	
	public function updateImgUrl()
	{
		$sql = "UPDATE [diagramaDermatologico] " . " ";
		$sql .= "SET [imgUrl] = '" . $this->imgUrl . "' ";
		$sql .= " WHERE idLesion = " . $this->idLesion . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

	public function insertarBD(){
		$sql = "INSERT INTO [diagramaDermatologico] ([idDiagnostico], [idCatTipoLesion], [x], [y], [w], [h], [idPaciente]) VALUES (";
        $sql .= (int)$this->idDiagnostico . " ," . $this->idCatTipoLesion . " ," . $this->x . " ," . $this->y . " ," . $this->w . " ," . $this->h . "," . (int)$this->idPaciente . ");";
		$sql .= "SELECT @@Identity AS nuevoId";
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idLesion = $tabla["nuevoId"];
		}
	}
	
	public function modificarBD() {
		$sql = "UPDATE [diagramaDermatologico] " . " ";
		$sql .= "SET [idDiagnostico] = '" . $this->idDiagnostico . "' ";
		$sql .= ", [idCatTipoLesion] = " . $this->idCatTipoLesion . " ";
		$sql .= ", [x] = " . $this->x . " ";
		$sql .= ", [y] = " . $this->y . " ";
		$sql .= ", [w] = " . $this->w . " ";
		$sql .= ", [h] = " . $this->h . " ";
        $sql .= ", [idPaciente] = " . $this->idPaciente . " ";
		$sql .= " WHERE idLesion = " . $this->idLesion . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

	public function obtenerBD($idLesion) {
		$sql = "SELECT * FROM [diagramaDermatologico] WHERE idLesion = " . $idLesion . ";";		
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {			
			$tabla = devuelveRowAssoc($consulta);
			$this->idLesion = $tabla["idLesion"];
			$this->idDiagnostico = $tabla["idDiagnostico"];
			$this->idCatTipoLesion = $tabla["idCatTipoLesion"];
			$this->x = $tabla["x"];
			$this->y = $tabla["y"];
			$this->w = $tabla["w"];
			$this->h = $tabla["h"];
            $this->idPaciente = $tabla["idPaciente"];
		}
	}
	
	public function replaceDB($idLesion) {
		$sql = "SELECT COUNT(*) AS lesion FROM [diagramaDermatologico] WHERE idLesion = " . $idLesion . ";";
		$consulta = ejecutaQueryClases($sql);
		
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
			return false;
		} 
		else {
			$result = devuelveRowAssoc($consulta);
			
			if($result['lesion'])
				$this->modificarBD();
			else 
				$this->insertarBD();
		}
	}
	
	public function eliminarBD($idLesion) {
		$sql = "DELETE FROM [diagramaDermatologico] WHERE idLesion = " . (int)$idLesion . ";";		
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
			return false;
		}
		
		return true;
	}
}
?>
