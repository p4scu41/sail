<?php
class Incidencia {

	public $idIncidencia = 0;				// int				12
	public $idUsuario;						// int				99
	public $idCatEstadoIncidencia = 1;		// int				1						// OJO, inicializado automaticamente como NUEVA
	public $contenido;						// text
	public $fechaCaptura;					// datetime			1990-12-21 15:33:12
	
	public $error = false;
	public $msgError;

	// idCatEstadoIncidencia
	// NUEVA = 1
	// PROCESADA = 2

	public function insertarBD() {
		
		// OJO, ponendo estado como NUEVA (dada la inicializacion)
		$sql = "INSERT INTO [incidencia] ([idUsuario], [idCatEstadoIncidencia], [contenido]) VALUES (" . $this->idUsuario. ", " . $this->idCatEstadoIncidencia . " , '" . $this->contenido . "'); SELECT @@identity AS nuevoId;";		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			sqlsrv_next_result($consulta);
			$tabla = devuelveRowAssoc($consulta);
			$this->fechaCaptura = date("Y-m-d H:i:s");
			$this->idIncidencia = $tabla["nuevoId"];			
		}		
	}

	public function procesar() {		
		
		$this->idCatEstadoIncidencia = 2;		// OJO, poniendo estado como PROCESADA
		$sql = "UPDATE [incidencia]  SET idCatEstadoIncidencia = " . $this->idCatEstadoIncidencia . ", contenido = '" . $this->contenido . "' WHERE idIncidencia = " . $this->idIncidencia . ";";
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		}		
	}

	public function obtenerBD($idIncidencia) {
		
		$sql = "SELECT * FROM [incidencia] WHERE idIncidencia = " . $idIncidencia . ";";		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			$this->idIncidencia = $tabla["idControl"];
			$this->idUsuario = $tabla["idDiagnostico"];
			$this->idCatEstadoIncidencia = $tabla["fecha"];
			$this->contenido = $tabla["contenido"];
			$this->fechaCaptura = $tabla["fechaCaptura"];			
		}
	}

}
?>