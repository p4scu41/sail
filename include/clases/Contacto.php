<?php
class Contacto {
	public $idContacto = 0;				//	int			9882
	public $idDiagnostico;				//	int			12
	public $nombre;						//	varchar(70)	Jose Luis Aranda Nucamendi
	public $sexo;						//	char(1)		H
	public $edad;						//	tinyint		24
	public $idCatParentesco;			//	int			4
	////////////						Posibles nulos	
	public $tiempoConvivenciaAnos;		//	tinyint		24
	public $tiempoConvivenciaMeses;		//	tinyint		7
	
	public $arrEstudiosHis = array();
	public $arrEstudiosBac = array();

	public $error = false;
	public $msgError;

	public function insertarBD() {
		$sqlA = "INSERT INTO [contactos] ([idDiagnostico], [nombre], [sexo], [edad], [idCatParentesco]";
		$sqlB = "VALUES (" . $this->idDiagnostico . ", '" . $this->nombre . "', '" . $this->sexo . "', " . (int)$this->edad . ", " . $this->idCatParentesco;
		if($this->tiempoConvivenciaAnos != '' && !is_null($this->tiempoConvivenciaAnos)) { $sqlA .= ", [tiempoConvivenciaAnos]"; $sqlB .= ", " . (int)$this->tiempoConvivenciaAnos; }
		if($this->tiempoConvivenciaMeses != '' && !is_null($this->tiempoConvivenciaMeses)) { $sqlA .= ", [tiempoConvivenciaMeses]"; $sqlB .= ", " . (int)$this->tiempoConvivenciaMeses; }
		$sqlA .= ") " . $sqlB . "); SELECT @@Identity AS nuevoId;";		
		$consulta = ejecutaQueryClases($sqlA);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idContacto = $tabla["nuevoId"];
		}
	}

	public function modificarBD() {
		$sql = "UPDATE [contactos] SET " ;
		$sql .= "[idDiagnostico] = " . $this->idDiagnostico . " " ;
		$sql .= ",[nombre] = '" . $this->nombre . "' " ;
		$sql .= ",[sexo] = '" . $this->sexo . "' " ;
		$sql .= ",[edad] = " . (int)$this->edad . " " ;
		$sql .= ",[idCatParentesco] = " . $this->idCatParentesco . " ";
		if($this->tiempoConvivenciaAnos != '' && !is_null($this->tiempoConvivenciaAnos)) {	$sql .= ",[tiempoConvivenciaAnos] = " . (int)$this->tiempoConvivenciaAnos . " ";  }
		if($this->tiempoConvivenciaMeses != '' && !is_null($this->tiempoConvivenciaMeses)) {	$sql .= ",[tiempoConvivenciaMeses] = " . (int)$this->tiempoConvivenciaMeses . " ";  }
		$sql .= "WHERE idContacto = " . $this->idContacto;
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}
	}

	public function obtenerBD($idContacto) {
		$sql = "SELECT * FROM [contactos] WHERE idContacto = " . $idContacto . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			
			$this->idContacto = $tabla["idContacto"];
			$this->idDiagnostico = $tabla["idDiagnostico"];
			$this->nombre = $tabla["nombre"];
			$this->sexo = $tabla["sexo"];
			$this->edad = $tabla["edad"];
			$this->idCatParentesco = $tabla["idCatParentesco"];

			if (!is_null($tabla["tiempoConvivenciaAnos"])) { $this->tiempoConvivenciaAnos = $tabla["tiempoConvivenciaAnos"]; }
			if (!is_null($tabla["tiempoConvivenciaMeses"])) { $this->tiempoConvivenciaMeses = $tabla["tiempoConvivenciaMeses"]; }
		}
	}
	
	public function replaceDB($idContacto) {
		$sql = "SELECT COUNT(*) AS contacto FROM [contactos] WHERE idContacto = " . (int) $idContacto . ";";
		$consulta = ejecutaQueryClases($sql);
		
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
			return false;
		} 
		else {
			$result = devuelveRowAssoc($consulta);
			
			if($result['contacto'])
				$this->modificarBD();
			else 
				$this->insertarBD();
		}
	}
	
	public function eliminarBD($idContacto) {
		$sql = "DELETE FROM [contactos] WHERE idContacto = " . (int) $idContacto . ";";		
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
			return false;
		}
		
		return true;
	}

	public function cargarEstudiosBac() {
		$sql = "SELECT [idEstudioBac] FROM [estudiosBac] WHERE idContacto = " . $this->idContacto . " ORDER BY fechaResultado ASC;";
		$result = ejecutaQueryClases($sql);
		
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
		} else {
			while ($tabla = devuelveRowAssoc($result)) {
				$idTemp = $tabla["idEstudioBac"];
				$objTemp = new EstudioBac();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrEstudiosBac, $objTemp);
			}
		}
	}	

	public function cargarEstudiosHis() {
		$sql = "SELECT [idEstudioHis] FROM [estudiosHis] WHERE idContacto = " . $this->idContacto . " ORDER BY fechaResultado ASC;";
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
?>