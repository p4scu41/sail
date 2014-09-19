<?php
class CasoRelacionado {

	public $idCasoRelacionado = 0;				//	int			9882
	public $idDiagnostico;						//	int			12
	public $nombre;								//	varchar(70)	Jose Luis Aranda Nucamendi
	public $idCatParentesco;					//	int			12
	public $idCatSituacionCasoRelacionado;		//	int			3
	public $tiempoConvivenciaMeses;				//	int			1
	public $tiempoConvivenciaAnos;				//	int			9

	public $error = false;
	public $msgError;

	public function insertarBD() {
		$sql = "INSERT INTO [casosRelacionados] ([idDiagnostico], [Nombre], [idCatParentesco], [idCatSituacionCasoRelacionado], 
                [tiempoConvivenciaMeses], [tiempoConvivenciaAnos]) ";
		$sql .= "VALUES (" . $this->idDiagnostico . ", '" . $this->nombre . "' ," . $this->idCatParentesco . " ," . 
                $this->idCatSituacionCasoRelacionado . " ," . (int)$this->tiempoConvivenciaMeses . " ," . (int)$this->tiempoConvivenciaAnos . ");";
		$sql .= "SELECT @@Identity AS nuevoId";
				
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sqlA;
			return false;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idCasoRelacionado = $tabla["nuevoId"];
		}
		
		return true;
	}
	
	public function modificarBD() {
		$sql = "UPDATE [casosRelacionados] " . " ";
		$sql .= "SET [Nombre] = '" . $this->nombre . "' ";
		$sql .= ", [idCatParentesco] = " . $this->idCatParentesco . " ";
		$sql .= ", [idCatSituacionCasoRelacionado] = " . $this->idCatSituacionCasoRelacionado . " ";
		$sql .= ", [tiempoConvivenciaMeses] = " . (int)$this->tiempoConvivenciaMeses . " ";
		$sql .= ", [tiempoConvivenciaAnos] = " . (int)$this->tiempoConvivenciaAnos . " ";
		$sql .= " WHERE idCasoRelacionado = " . $this->idCasoRelacionado . ";";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
			return false;
		}
		
		return true;
	}

	public function obtenerBD($idCasoRelacionado) {
		$sql = "SELECT * FROM [casosRelacionados] WHERE idCasoRelacionado = " . $idCasoRelacionado . ";";		
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
			return false;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			$this->idCasoRelacionado = $tabla["idCasoRelacionado"];
			$this->idDiagnostico = $tabla["idDiagnostico"];
			$this->nombre = $tabla["Nombre"];
			$this->idCatParentesco = $tabla["idCatParentesco"];
			$this->idCatSituacionCasoRelacionado = $tabla["idCatSituacionCasoRelacionado"];
			$this->tiempoConvivenciaMeses = $tabla["tiempoConvivenciaMeses"];
			$this->tiempoConvivenciaAnos = $tabla["tiempoConvivenciaAnos"];
			return true;
		}
	}
	
	public function replaceDB($idCasoRelacionado) {
		/*IF NOT EXISTS(<SELECT>)
		BEGIN
			<INSERT>
		END
		ELSE
		BEGIN
			<UPDATE>
		END*/
		/*MERGE <target_table> [AS TARGET]
		USING <table_source> [AS SOURCE]
		ON <search_condition>
		[WHEN MATCHED] THEN 
			<merge_matched> ]
		[WHEN NOT MATCHED [BY TARGET] THEN 
			<merge_not_matched> ]
		[WHEN NOT MATCHED BY SOURCE THEN 
			<merge_ matched> ];*/
		
		$sql = "SELECT COUNT(*) AS caso FROM [casosRelacionados] WHERE idCasoRelacionado = " . (int) $idCasoRelacionado . ";";
		$consulta = ejecutaQueryClases($sql);
		
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
			return false;
		} 
		else {
			$result = devuelveRowAssoc($consulta);
			
			if($result['caso'])
				$this->modificarBD();
			else 
				$this->insertarBD();
		}
	}
	
	public function eliminarBD($idCasoRelacionado) {
		$sql = "DELETE FROM [casosRelacionados] WHERE idCasoRelacionado = " . (int) $idCasoRelacionado . ";";		
		
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