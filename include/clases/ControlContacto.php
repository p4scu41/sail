<?php
class ControlContacto {

	public $idContacto;
    public $arrRevisionContacto;
    
	public $error = false;
	public $msgError;

	public function obtenerBD($idContacto) {
		$sql = "SELECT [idControlContacto],[fecha],[idCatRevisionContacto],[observaciones] 
                FROM [controlContacto] WHERE [idContacto]=" . (int)$idContacto . " ORDER BY [fecha] ASC";
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$this->idContacto = $idContacto;
            $this->arrRevisionContacto = null;
            
            while ($registro = devuelveRowAssoc($consulta))
            {
                $objRevision = new RevisionContacto();
                
                $objRevision->idControlContacto = $registro['idControlContacto'];
                $objRevision->fecha = formatFechaObj($registro['fecha']);
                $objRevision->idCatRevisionContacto = $registro['idCatRevisionContacto'];
                $objRevision->observaciones = $registro['observaciones'];
                
                $this->arrRevisionContacto[] = $objRevision;
            }
		}
	}
}

class RevisionContacto {
    public $idControlContacto;
    public $fecha;
    public $idCatRevisionContacto;
    public $observaciones;
}
?>