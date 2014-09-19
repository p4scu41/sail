<?php

class BusquedaEstudios {
	
	public $idCatEstado;
	public $folioLaboratorio;
	public $folioSolicitud;
	public $fechaInicio;
	public $fechaFin;
	
	public $resultado = array();
	
	public $error = false;
	public $msgError;
	
	public $totalRegistro;
	public $maxPages;
	public $page = 1;

	public function buscar() {
		$sql = "SELECT idEstudioHis as idEstudio, folioLaboratorio, folioSolicitud, personaSolicitudEstudio, fechaTomaMuestra, fechaSolicitudEstudio, idCatTipoEstudio, 'histo' AS tipo, estudiosHis.idDiagnostico, diagnostico.idPaciente as idPacienteDiag, estudiosHis.idPaciente, idContacto  " .
			"FROM estudiosHis LEFT JOIN diagnostico ON diagnostico.idDiagnostico = estudiosHis.idDiagnostico " .
			"WHERE idCatSolicitante IN (SELECT idCatUnidad FROM catUnidad WHERE 1=1 ";
        
            if($_SESSION[EDO_USR_SESSION] != 0)
                $sql .= " AND idCatEstado = " . $this->idCatEstado;
            $sql .= " ) ";
            
			if (!is_null($this->fechaInicio) && $this->fechaInicio != '') $sql .= "AND fechaSolicitudEstudio >= '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' ";
			if (!is_null($this->fechaFin) && $this->fechaFin != '') $sql .= "AND fechaSolicitudEstudio <= '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "' ";
			if (!is_null($this->folioLaboratorio) && $this->folioLaboratorio != '') $sql .= "AND folioLaboratorio = '" . $this->folioLaboratorio . "' ";
			if (!is_null($this->folioSolicitud) && $this->folioSolicitud != '') $sql .= "AND folioSolicitud = '" . $this->folioSolicitud . "' ";
		
        $sql .= " UNION " .
			"SELECT idEstudioBac as idEstudio,folioLaboratorio, folioSolicitud, personaSolicitudEstudio, fechaTomaMuestra, fechaSolicitudEstudio, idCatTipoEstudio, 'bacilos' AS tipo, estudiosBac.idDiagnostico, diagnostico.idPaciente as idPacienteDiag, estudiosBac.idPaciente, idContacto  " .
			"FROM estudiosBac LEFT JOIN diagnostico ON diagnostico.idDiagnostico = estudiosBac.idDiagnostico " .
			"WHERE idCatSolicitante IN (SELECT idCatUnidad FROM catUnidad WHERE 1=1 ";
            
            if($_SESSION[EDO_USR_SESSION] != 0)
                $sql .= " AND idCatEstado = " . $this->idCatEstado;
            $sql .= " ) ";
            
			if (!is_null($this->fechaInicio) && $this->fechaInicio != '') $sql .= "AND fechaSolicitudEstudio >= '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' ";
			if (!is_null($this->fechaFin) && $this->fechaFin != '') $sql .= "AND fechaSolicitudEstudio <= '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "' ";
			if (!is_null($this->folioLaboratorio) && $this->folioLaboratorio != '') $sql .= "AND folioLaboratorio = '" . $this->folioLaboratorio . "' ";
			if (!is_null($this->folioSolicitud) && $this->folioSolicitud != '') $sql .= "AND folioSolicitud = '" . $this->folioSolicitud . "' ";
		$sql .= "ORDER BY fechaSolicitudEstudio;";
		
        $help = new Helpers();
		$consulta = ejecutaQueryClases($sql);

		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$arr = array();
			$c = 0;
			while ($registro = devuelveRowAssoc($consulta)) {
				$estudio = new resultadoBusquedaEstudio();
                $estudio->idEstudio = $registro['idEstudio'];
                $estudio->idPaciente = $registro['idPacienteDiag'] ? $registro['idPacienteDiag'] : $registro['idPaciente'];
                $estudio->folioLaboratorio = $registro['folioLaboratorio'];
				$estudio->folioSolicitud = $registro['folioSolicitud'];
				$estudio->solicitante = $registro['personaSolicitudEstudio'];
				$estudio->fechaMuestreo = $registro['fechaTomaMuestra'];
				$estudio->fechaSolicitud = $registro['fechaSolicitudEstudio'];
				$estudio->idCatTipoEstudio = $registro['idCatTipoEstudio'];
				$estudio->estudio = $registro['tipo'];
				
				if (!empty($registro['idContacto'])) {										// CONTACTO
                    $estudio->nombre = $help->getNombreContacto($registro['idContacto']);
					$estudio->clavePaciente = "Contacto: Sin Clave";
                }
                else if (!empty($registro['idDiagnostico'])) {		// CONFIRMADO
					$estudio->nombre = $help->getNamePacienteDiagnostico($registro['idDiagnostico']);
					$estudio->clavePaciente = $help->getClavePacienteDiagnostico($registro['idDiagnostico']);
				} else {																			// SOSPECHOSO
					$estudio->nombre = $help->getNamePaciente($registro['idPaciente']);
					$estudio->clavePaciente = $help->getClavePaciente($registro['idPaciente']); 
				}
				$arr[$c] = $estudio;
                $c++;
			}
			$this->resultado = $arr;
		}		
	}
    
    public function buscarCalidad() {
		$sql = "SELECT idEstudioHis as idEstudio, folioLaboratorio, folioSolicitud, personaSolicitudEstudio, fechaTomaMuestra, fechaSolicitudEstudio, idCatTipoEstudio, 'histo' AS tipo, estudiosHis.idDiagnostico, diagnostico.idPaciente as idPacienteDiag, estudiosHis.idPaciente, idContacto, fechaResultado  " .
			"FROM estudiosHis LEFT JOIN diagnostico ON diagnostico.idDiagnostico = estudiosHis.idDiagnostico " .
			"WHERE idCatSolicitante IN (SELECT idCatUnidad FROM catUnidad WHERE fechaResultado is NOT NULL ";
        
            if($_SESSION[EDO_USR_SESSION] != 0)
                $sql .= " AND idCatEstado = " . $this->idCatEstado;
            $sql .= " ) ";
            
			if (!is_null($this->fechaInicio) && $this->fechaInicio != '') $sql .= "AND fechaResultado >= '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' ";
			if (!is_null($this->fechaFin) && $this->fechaFin != '') $sql .= "AND fechaResultado <= '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "' ";
			if (!is_null($this->folioLaboratorio) && $this->folioLaboratorio != '') $sql .= "AND folioLaboratorio = '" . $this->folioLaboratorio . "' ";
			if (!is_null($this->folioSolicitud) && $this->folioSolicitud != '') $sql .= "AND folioSolicitud = '" . $this->folioSolicitud . "' ";
		
        $sql .= " UNION " .
			"SELECT idEstudioBac as idEstudio,folioLaboratorio, folioSolicitud, personaSolicitudEstudio, fechaTomaMuestra, fechaSolicitudEstudio, idCatTipoEstudio, 'bacilos' AS tipo, estudiosBac.idDiagnostico, diagnostico.idPaciente as idPacienteDiag, estudiosBac.idPaciente, idContacto, fechaResultado  " .
			"FROM estudiosBac LEFT JOIN diagnostico ON diagnostico.idDiagnostico = estudiosBac.idDiagnostico " .
			"WHERE idCatSolicitante IN (SELECT idCatUnidad FROM catUnidad WHERE fechaResultado is NOT NULL ";
            
            if($_SESSION[EDO_USR_SESSION] != 0)
                $sql .= " AND idCatEstado = " . $this->idCatEstado;
            $sql .= " ) ";
            
			if (!is_null($this->fechaInicio) && $this->fechaInicio != '') $sql .= "AND fechaResultado >= '" . formatFechaObj($this->fechaInicio, 'Y-m-d') . "' ";
			if (!is_null($this->fechaFin) && $this->fechaFin != '') $sql .= "AND fechaResultado <= '" . formatFechaObj($this->fechaFin, 'Y-m-d') . "' ";
			if (!is_null($this->folioLaboratorio) && $this->folioLaboratorio != '') $sql .= "AND folioLaboratorio = '" . $this->folioLaboratorio . "' ";
			if (!is_null($this->folioSolicitud) && $this->folioSolicitud != '') $sql .= "AND folioSolicitud = '" . $this->folioSolicitud . "' ";
		$sql .= "ORDER BY fechaResultado DESC;";
		
        $help = new Helpers();
		$consulta = ejecutaQueryClases($sql);

		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$arr = array();
			$c = 0;
			while ($registro = devuelveRowAssoc($consulta)) {
				$estudio = new resultadoBusquedaEstudio();
                $estudio->idEstudio = $registro['idEstudio'];
                $estudio->idPaciente = $registro['idPacienteDiag'] ? $registro['idPacienteDiag'] : $registro['idPaciente'];
                $estudio->folioLaboratorio = $registro['folioLaboratorio'];
				$estudio->folioSolicitud = $registro['folioSolicitud'];
				$estudio->solicitante = $registro['personaSolicitudEstudio'];
				$estudio->fechaMuestreo = $registro['fechaTomaMuestra'];
				$estudio->fechaSolicitud = $registro['fechaSolicitudEstudio'];
				$estudio->fechaResultado = $registro['fechaResultado'];
				$estudio->idCatTipoEstudio = $registro['idCatTipoEstudio'];
				$estudio->estudio = $registro['tipo'];
				
				if (!empty($registro['idContacto'])) {										// CONTACTO
                    $estudio->nombre = $help->getNombreContacto($registro['idContacto']);
					$estudio->clavePaciente = "Contacto: Sin Clave";
                }
                else if (!empty($registro['idDiagnostico'])) {		// CONFIRMADO
					$estudio->nombre = $help->getNamePacienteDiagnostico($registro['idDiagnostico']);
					$estudio->clavePaciente = $help->getClavePacienteDiagnostico($registro['idDiagnostico']);
				} else {																			// SOSPECHOSO
					$estudio->nombre = $help->getNamePaciente($registro['idPaciente']);
					$estudio->clavePaciente = $help->getClavePaciente($registro['idPaciente']); 
				}
				$arr[$c] = $estudio;
                $c++;
			}
			$this->resultado = $arr;
		}		
	}
}



class resultadoBusquedaEstudio {

    public $idEstudio;
    public $idPaciente;
	public $folioLaboratorio;
    public $folioSolicitud;
	public $clavePaciente;
	public $nombre;
	public $solicitante;
	public $fechaMuestreo;
	public $fechaSolicitud;
	public $fechaResultado;
	public $idCatTipoEstudio;
	public $estudio;

}
?>