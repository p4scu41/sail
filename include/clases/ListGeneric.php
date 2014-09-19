<?php
class ListGeneric {
	
	public $arrEstudiosHis = array();
	public $arrEstudiosBac = array();
 
	public $error = false;
	public $msgError;
 
	public function obtenerPendientesBac($estado) 
	{
		/*$sql = 'SELECT e.idEstudioBac FROM estudiosBac e, diagnostico d, pacientes p, catUnidad u 
				WHERE e.idDiagnostico = d.idDiagnostico AND d.idPaciente = p.idPaciente AND u.idCatUnidad = p.idCatUnidadTratante AND 
				u.idCatEstado = '.$estado.' AND e.fechaResultado IS NULL ORDER BY fechaSolicitud';*/
        $sql = 'SELECT e.idEstudioBac, e.fechaSolicitudEstudio, p.idPaciente
                FROM estudiosBac e, diagnostico d, pacientes p, catUnidad u 
                WHERE 
                    e.idDiagnostico = d.idDiagnostico AND 
                    d.idPaciente = p.idPaciente AND 
                    u.idCatUnidad = p.idCatUnidadTratante AND ';
                    if($_SESSION[EDO_USR_SESSION] != 0)
                        $sql .= ' u.idCatEstado = '.$estado.' AND ';
                    $sql .= ' e.fechaResultado IS NULL AND
                    e.folioLaboratorio IS NOT NULL AND 
                    [muestraRechazada]=0
                UNION
                SELECT e.idEstudioBac, e.fechaSolicitudEstudio, p.idPaciente
                FROM estudiosBac e, pacientes p, catUnidad u 
                WHERE 
                    e.idPaciente = p.idPaciente AND 
                    u.idCatUnidad = p.idCatUnidadTratante AND ';
                    if($_SESSION[EDO_USR_SESSION] != 0)
                        $sql .= ' u.idCatEstado = '.$estado.' AND ';
                    $sql .= ' e.fechaResultado IS NULL AND
                    e.folioLaboratorio IS NOT NULL AND 
                    [muestraRechazada]=0
				ORDER BY fechaSolicitudEstudio';
        
		$result = ejecutaQueryClases($sql);
  
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			while ($registro = devuelveRowAssoc($result)) {
				$idTemp = $registro["idEstudioBac"];
				$idPacienteTemp = $registro["idPaciente"];
				
				$objTemp = new EstudioBac();
				$objTemp->obtenerBD($idTemp);
				$objTemp->idPaciente = $idPacienteTemp;
				array_push($this->arrEstudiosBac, $objTemp);
			}
		}
	}
 
	public function obtenerPendientesHis($estado) 
	{
		/*$sql = 'SELECT e.idEstudioHis FROM estudiosHis e, diagnostico d, pacientes p, catUnidad u 
				WHERE e.idDiagnostico = d.idDiagnostico AND d.idPaciente = p.idPaciente AND u.idCatUnidad = p.idCatUnidadTratante AND 
				u.idCatEstado = '.$estado.' AND e.fechaResultado IS NULL ORDER BY fechaSolicitud';*/
        $sql = 'SELECT e.idEstudioHis, e.fechaSolicitudEstudio, p.idPaciente
                FROM estudiosHis e, diagnostico d, pacientes p, catUnidad u 
                WHERE 
                    e.idDiagnostico = d.idDiagnostico AND 
                    d.idPaciente = p.idPaciente AND 
                    u.idCatUnidad = p.idCatUnidadTratante AND ';
                    if($_SESSION[EDO_USR_SESSION] != 0)
                        $sql .= ' u.idCatEstado = '.$estado.' AND ';
                    $sql .= ' e.fechaResultado IS NULL AND
                    e.folioLaboratorio IS NOT NULL AND 
                    [muestraRechazada]=0
                UNION 
                SELECT e.idEstudioHis, e.fechaSolicitudEstudio, p.idPaciente
                FROM estudiosHis e, pacientes p, catUnidad u 
                WHERE 
                    e.idPaciente = p.idPaciente AND 
                    u.idCatUnidad = p.idCatUnidadTratante AND ';
                    if($_SESSION[EDO_USR_SESSION] != 0)
                        $sql .= ' u.idCatEstado = '.$estado.' AND ';
                    $sql .= ' e.fechaResultado IS NULL AND
                    e.folioLaboratorio IS NOT NULL AND 
                    [muestraRechazada]=0
				ORDER BY fechaSolicitudEstudio';
        
		$result = ejecutaQueryClases($sql);
  
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			while ($registro = devuelveRowAssoc($result)) {
				$idTemp = $registro["idEstudioHis"];
				$objTemp = new EstudioHis();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrEstudiosHis, $objTemp);
			}
		}
	}
	
	public function getPendientesBacPaciente($paciente) 
	{
		/*$sql = 'SELECT e.idEstudioBac FROM estudiosBac e, diagnostico d, pacientes p
				WHERE e.idDiagnostico = d.idDiagnostico AND d.idPaciente = p.idPaciente AND
				p.idPaciente='.$paciente.' AND e.fechaResultado IS NULL ORDER BY fechaSolicitud';*/
        $sql = ' SELECT 
               idEstudioBac
           FROM estudiosBac
           WHERE 
               (idPaciente = '.$paciente.' OR 
               idDiagnostico = (SELECT idDiagnostico FROM diagnostico WHERE idPaciente = '.$paciente.'))
               AND estudiosBac.fechaResultado IS NULL 
               AND estudiosBac.muestraRechazada = 0
               ORDER BY fechaSolicitudEstudio';
		$result = ejecutaQueryClases($sql);
  
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			while ($registro = devuelveRowAssoc($result)) {
				$idTemp = $registro["idEstudioBac"];
				$objTemp = new EstudioBac();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrEstudiosBac, $objTemp);
			}
		}
	}
	
	public function getPendientesHisPaciente($paciente) 
	{
		/*$sql = 'SELECT e.idEstudioHis FROM estudiosHis e, diagnostico d, pacientes p 
				WHERE e.idDiagnostico = d.idDiagnostico AND d.idPaciente = p.idPaciente AND 
				p.idPaciente='.$paciente.' AND e.fechaResultado IS NULL ORDER BY fechaSolicitud';*/
        /*$sql = 'SELECT 
                    e.idEstudioHis
                FROM estudiosHis e
                LEFT JOIN diagnostico AS d
                    ON d.idDiagnostico = e.idDiagnostico
                LEFT JOIN pacientes AS p 
                    ON p.idPaciente = e.idPaciente
                WHERE 
                    p.idPaciente='.$paciente.' AND 
                    e.fechaResultado IS NULL 
                ORDER BY fechaSolicitud';*/
        $sql = ' SELECT 
               idEstudioHis
           FROM estudiosHis
           WHERE 
               (idPaciente = '.$paciente.' OR 
               idDiagnostico = (SELECT idDiagnostico FROM diagnostico WHERE idPaciente = '.$paciente.'))
               AND estudiosHis.fechaResultado IS NULL 
               AND estudiosHis.muestraRechazada = 0
               ORDER BY fechaSolicitudEstudio';
        
		$result = ejecutaQueryClases($sql);
  
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			while ($registro = devuelveRowAssoc($result)) {
				$idTemp = $registro["idEstudioHis"];
				$objTemp = new EstudioHis();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrEstudiosHis, $objTemp);
			}
		}
	}
	
	public function getProcesadosBacPaciente($paciente) 
	{
		$this->arrEstudiosBac = array();
		
		/*$sql = 'SELECT e.idEstudioBac FROM estudiosBac e, diagnostico d, pacientes p
				WHERE e.idDiagnostico = d.idDiagnostico AND d.idPaciente = p.idPaciente AND
				p.idPaciente='.$paciente.' AND e.fechaResultado IS NOT NULL ORDER BY fechaResultado';*/
        $sql = ' SELECT 
               idEstudioBac
           FROM estudiosBac
           WHERE 
               (idPaciente = '.$paciente.' OR 
               idDiagnostico = (SELECT idDiagnostico FROM diagnostico WHERE idPaciente = '.$paciente.'))
               AND (estudiosBac.fechaResultado IS NOT NULL 
               OR estudiosBac.muestraRechazada = 1)
               ORDER BY fechaSolicitudEstudio';
        
		$result = ejecutaQueryClases($sql);
  
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			while ($registro = devuelveRowAssoc($result)) {
				$idTemp = $registro["idEstudioBac"];
				$objTemp = new EstudioBac();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrEstudiosBac, $objTemp);
			}
		}
	}
	
	public function getProcesadosHisPaciente($paciente) 
	{
		$this->arrEstudiosHis = array();
		
		/*$sql = 'SELECT e.idEstudioHis FROM estudiosHis e, diagnostico d, pacientes p 
				WHERE e.idDiagnostico = d.idDiagnostico AND d.idPaciente = p.idPaciente AND 
				p.idPaciente='.$paciente.' AND e.fechaResultado IS NOT NULL ORDER BY fechaResultado';*/
        
         /*$sql = 'SELECT 
                    e.idEstudioHis
                FROM estudiosHis e
                LEFT JOIN diagnostico AS d
                    ON d.idDiagnostico = e.idDiagnostico
                LEFT JOIN pacientes AS p 
                    ON p.idPaciente = e.idPaciente
                WHERE 
                    p.idPaciente='.$paciente.' AND 
                    e.fechaResultado IS NOT NULL 
                    OR e.muestraRechazada = 1
                ORDER BY fechaSolicitud';*/
         $sql = ' SELECT 
               idEstudioHis
           FROM estudiosHis
           WHERE 
               (idPaciente = '.$paciente.' OR 
               idDiagnostico = (SELECT idDiagnostico FROM diagnostico WHERE idPaciente = '.$paciente.'))
               AND (estudiosHis.fechaResultado IS NOT NULL 
               OR estudiosHis.muestraRechazada = 1)
               ORDER BY fechaSolicitudEstudio';
         
		$result = ejecutaQueryClases($sql);
  
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			while ($registro = devuelveRowAssoc($result)) {
				$idTemp = $registro["idEstudioHis"];
				$objTemp = new EstudioHis();
				$objTemp->obtenerBD($idTemp);
				array_push($this->arrEstudiosHis, $objTemp);
			}
		}
	}
    
    public function getRecepMuestraBac(){
        $arrEstudios = array();
        $sql = 'SELECT [idDiagnostico]
                ,[idContacto]
                ,[idPaciente]
                ,[idEstudioBac]
                ,[fechaRecepcion]
                ,[folioLaboratorio]
                ,[folioSolicitud]
                ,[idCatSolicitante]
                ,[idCatTipoEstudio]
                ,[fechaTomaMuestra]
                ,[fechaSolicitud]
                ,[fechaSolicitudEstudio]
                ,[muestraRechazada]
                ,[idCatMotivoRechazo]
                ,[otroMotivoRechazo]
          FROM [lepra].[dbo].[estudiosBac]
          WHERE [folioLaboratorio] IS NULL AND 
               [idCatSolicitante] IN 
                    (SELECT [idCatUnidad] FROM [catUnidad] WHERE 1=1 '; 
        
        if($_SESSION[EDO_USR_SESSION] != 0)
            $sql .= ' AND [idCatEstado]='.$_SESSION[EDO_USR_SESSION];
        
        $sql .= ')';
        
        $result = ejecutaQueryClases($sql);
  
		if (is_string($result)) {
			return array();
		} else {
            $help = new Helpers();
            
			while ($registro = devuelveRowAssoc($result)) {
                if(!empty($registro['idContacto'])) {
                    $temp = devuelveRowAssoc(ejecutaQueryClases('SELECT [nombre] FROM [contactos] WHERE [idContacto]='.$registro['idContacto']));
                    $temp['cveExpediente'] = 'Estudio de Contacto';
                }
                else if(!empty($registro['idPaciente'])) {
                    $temp = devuelveRowAssoc(ejecutaQueryClases('SELECT ([nombre]+\' \'+[apellidoPaterno]+\' \'+[apellidoMaterno]) AS nombre, cveExpediente 
                            FROM [pacientes] WHERE idPaciente = '.$registro['idPaciente']));
                }
                else if(!empty($registro['idDiagnostico'])) {
                    $temp = devuelveRowAssoc(ejecutaQueryClases('SELECT ([nombre]+\' \'+[apellidoPaterno]+\' \'+[apellidoMaterno]) AS nombre, cveExpediente 
                            FROM [pacientes] WHERE idPaciente = 
                            (SELECT [idPaciente] FROM [diagnostico] WHERE [idDiagnostico]='.$registro['idDiagnostico'].')'));
                }
                    
				$arrEstudios[] = array(
                    'id'=>$registro['idEstudioBac'],
                    'folio_solicitud'=>$registro['folioSolicitud'],
                    'clave_paciente'=>($temp['cveExpediente']),
                    'nombre'=>$temp['nombre'],
                    'solicitante'=>$registro['idCatSolicitante'].' '.$help->getNameUnidad($registro['idCatSolicitante']),
                    'fecha_muestreo'=>formatFechaObj($registro['fechaTomaMuestra']),
                    'fecha_solicitud'=>formatFechaObj($registro['fechaSolicitudEstudio']),
                    'tipo_analisis'=>'Baciloscópia',
                    'estudio'=>$help->getDescripTipoEstudio($registro['idCatTipoEstudio']),
                    'tipo'=>'bacilos'
                );
			}
		}
        
        return $arrEstudios;
    }
    
    public function getRecepMuestraHis(){
        $arrEstudios = array();
        $sql = 'SELECT [idDiagnostico]
                ,[idContacto]
                ,[idPaciente]
                ,[idEstudioHis]
                ,[fechaRecepcion]
                ,[folioLaboratorio]
                ,[folioSolicitud]
                ,[idCatSolicitante]
                ,[idCatTipoEstudio]
                ,[fechaTomaMuestra]
                ,[fechaSolicitud]
                ,[fechaSolicitudEstudio]
                ,[muestraRechazada]
                ,[idCatMotivoRechazo]
                ,[otroMotivoRechazo]
          FROM [lepra].[dbo].[estudiosHis]
          WHERE [folioLaboratorio] IS NULL AND 
               [idCatSolicitante] IN 
                    (SELECT [idCatUnidad] FROM [catUnidad] WHERE 1=1 '; 
        
        if($_SESSION[EDO_USR_SESSION] != 0)
            $sql .= ' AND [idCatEstado]='.$_SESSION[EDO_USR_SESSION];
        
        $sql .= ')';
        
        $result = ejecutaQueryClases($sql);
  
		if (is_string($result)) {
			return array();
		} else {
            $help = new Helpers();
            
			while ($registro = devuelveRowAssoc($result)) {
                if(!empty($registro['idContacto'])) {
                    $temp = devuelveRowAssoc(ejecutaQueryClases('SELECT [nombre] FROM [contactos] WHERE [idContacto]='.$registro['idContacto']));
                    $temp['cveExpediente'] = 'Estudio de Contacto';
                }
                else if(!empty($registro['idPaciente'])) {
                    $temp = devuelveRowAssoc(ejecutaQueryClases('SELECT ([nombre]+\' \'+[apellidoPaterno]+\' \'+[apellidoMaterno]) AS nombre, cveExpediente 
                            FROM [pacientes] WHERE idPaciente = '.$registro['idPaciente']));
                }
                else if(!empty($registro['idDiagnostico'])) {
                    $temp = devuelveRowAssoc(ejecutaQueryClases('SELECT ([nombre]+\' \'+[apellidoPaterno]+\' \'+[apellidoMaterno]) AS nombre, cveExpediente 
                            FROM [pacientes] WHERE idPaciente = 
                            (SELECT [idPaciente] FROM [diagnostico] WHERE [idDiagnostico]='.$registro['idDiagnostico'].')'));
                }
                    
				$arrEstudios[] = array(
                    'id'=>$registro['idEstudioHis'],
                    'folio_solicitud'=>$registro['folioSolicitud'],
                    'clave_paciente'=>($temp['cveExpediente']),
                    'nombre'=>$temp['nombre'],
                    'solicitante'=>$registro['idCatSolicitante'].' '.$help->getNameUnidad($registro['idCatSolicitante']),
                    'fecha_muestreo'=>formatFechaObj($registro['fechaTomaMuestra']),
                    'fecha_solicitud'=>formatFechaObj($registro['fechaSolicitudEstudio']),
                    'tipo_analisis'=>'Histopatólogia',
                    'estudio'=>$help->getDescripTipoEstudio($registro['idCatTipoEstudio']),
                    'tipo'=>'histo'
                );
			}
		}
        
        return $arrEstudios;
    }
    
}
?>