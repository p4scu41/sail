<?php
class Helpers 
{
	public $error = false;
	public $msgError;

	public function getUltimaBaciloscopia($idEstudioHis) {
		$sql = 'SELECT * FROM estudiosHis WHERE idEstudioHis = ' . $idEstudioHis . ';';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {
			$registro = devuelveRowAssoc($result);
			if (!is_null($registro["idContacto"])) {				// DE CONTACTO 
				$sql = 'SELECT TOP 1 * FROM estudiosBac WHERE idContacto = ' . $registro["idContacto"] . ' AND fechaResultado IS NOT NULL ORDER BY fechaSolicitud DESC;';				
			} elseif (is_null($registro["idDiagnostico"])) {			// DE SOSPECHOSO
				$sql = 'SELECT TOP 1 * FROM estudiosBac WHERE idPaciente = ' . $registro["idPaciente"] . ' AND fechaResultado IS NOT NULL AND idContacto IS NULL ORDER BY fechaSolicitud DESC;';
			} else {												// DE CONFIRMADO
				$sql = 'SELECT TOP 1 * FROM estudiosBac WHERE idDiagnostico = ' . $registro["idDiagnostico"] . ' AND fechaResultado IS NOT NULL AND idContacto IS NULL ORDER BY fechaSolicitud DESC;';
			}
			
			$result = ejecutaQueryClases($sql);
			if (is_string($result)) {
				$this->error = true;
				$this->msgError = $result . " SQL:" . $sql;
				return '';
			} else {				
				if (devuelveNumRows($result) > 0) {
					$registro = devuelveRowAssoc($result);
					return formatFechaObj($registro["fechaResultado"], 'Y-m-d') . " Res: " . $registro["bacIM"] . "%";
				} else return '';
			}
		}
	}

	public function getOtrosPadecimientos($idPaciente)
	{
		$sql = 'SELECT d.otrosPadecimientos ' .
			'FROM diagnostico d, pacientes p ' .
			'WHERE p.idPaciente = ' . $idPaciente . ' ' .
			'AND d.idPaciente = p.idPaciente;';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {
			$registro = devuelveRowAssoc($result);
			if (is_null($registro["otrosPadecimientos"])) return '';
			else return $registro["otrosPadecimientos"];
		}
	}

	public function getFechaInicioTratamiento($idPaciente)
	{
		$sql = 'SELECT fechaInicioPQT ' .
			'FROM pacientes ' .
			'WHERE idPaciente = ' . $idPaciente . ';';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {
			$registro = devuelveRowAssoc($result);
			if (is_null($registro["fechaInicioPQT"])) return '';
			else return formatFechaObj($registro["fechaInicioPQT"], 'Y-m-d');
		}
	}

	public function getTratamiento($idPaciente, $asDescripcion)	
	{
		$sql = 'SELECT d.idCatTratamiento, t.descripcion ' .
			'FROM diagnostico d, pacientes p, catTratamiento t ' .
			'WHERE p.idPaciente = ' . $idPaciente . ' ' .
			'AND d.idPaciente = p.idPaciente ' .
			'AND t.idCatTratamiento = d.idCatTratamiento;';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {			
			if (devuelve_num_rows($result) > 0) {
				$registro = devuelveRowAssoc($result);			
				if ($asDescripcion) return $registro["descripcion"];
				else return $registro["idCatTratamiento"];
			} else return '';			
		}
	}

	public function getTipoLepra($idPaciente, $asDescripcion)	
	{
		$sql = 'SELECT d.idCatClasificacionLepra, c.descripcion ' .
			'FROM diagnostico d, pacientes p, catClasificacionLepra c ' .
			'WHERE p.idPaciente = ' . $idPaciente . ' ' .
			'AND d.idPaciente = p.idPaciente ' .
			'AND c.idCatClasificacionLepra = d.idCatClasificacionLepra;'; 
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {
			if (devuelve_num_rows($result) > 0) {
				$registro = devuelveRowAssoc($result);
				if ($asDescripcion) return $registro["descripcion"];
				else return $registro["idCatClasificacionLepra"];
			} else return '';
		}
	}

	public function getArrayLesionesDiagramaSospechoso($idPaciente)
	{
		$arr = array();
		$sql = 'SELECT DISTINCT idCatTipoLesion' .
			' FROM diagramaDermatologico d' .
			' WHERE d.idPaciente = ' . $idPaciente .
			' GROUP BY idCatTipoLesion';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$c = 0;
			while ($registro = devuelveRowAssoc($result)) {
				$arr[$c] = $registro['idCatTipoLesion'];
                $c++;
			}				
		}
		return $arr;
	}

	public function getArrayLesionesDiagramaDiagnosticado($idDiagnostico)
	{
		$arr = array();
		$sql = 'SELECT DISTINCT idCatTipoLesion' .
			' FROM diagramaDermatologico d' .
			' WHERE d.idDiagnostico = ' . $idDiagnostico .
			' GROUP BY idCatTipoLesion';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$c = 0;
			while ($registro = devuelveRowAssoc($result)) {
				$arr[$c] = $registro['idCatTipoLesion'];
                $c++;
			}				
		}
		return $arr;
	}

	public function getArrayCatHistopatologia()
	{
		$arr = array();
		$sql = 'SELECT idCatHisto, descripcion FROM catHistopatologia;';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			while ($registro = devuelveRowAssoc($result))
				$arr[$registro['idCatHisto']] = $registro['descripcion'];
		}
		return $arr;
	}

	public function getTiempoDeTratamiento($idPaciente, $fechaHasta)
	{
		$sql = 'SELECT fechaDiagnostico FROM pacientes WHERE idPaciente = ' . $idPaciente . ';';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {
			$registro = devuelveRowAssoc($result);
			$fechaDesde = '2000-01-01';
			if(!is_null($registro["fechaDiagnostico"]))	{
				$fechaDesde = $registro["fechaDiagnostico"];
				$tiempoEvolucion = date_diff($fechaDesde, $fechaHasta);
				return $tiempoEvolucion->format('%y a&ntilde;o(s) %m mese(s)');
			} else {
				return "Sin Tratamiento.";
			}			
		}
	}

	public function getArrDatosUnidadTratante($idPaciente)
	{
		$arr = array();
		$sql = 'SELECT u.idCatUnidad, nombreUnidad, institucion, nombreEntidad, nombreMunicipio, nombreLocalidad ' .
			'FROM catUnidad u, pacientes p ' .
			'WHERE p.idPaciente = ' . $idPaciente . ' ' .
			'AND p.idCatUnidadTratante = u.idCatUnidad;';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {
			$registro = devuelveRowAssoc($result);
			$arr["idUnidad"] = $registro["idCatUnidad"];
			$arr["nombre"] = $registro["nombreUnidad"];
			$arr["institucion"] = $registro["institucion"];
			$arr["municipio"] = $registro["nombreMunicipio"];
			$arr["estado"] = $registro["nombreEntidad"];
			$arr["localidad"] = $registro["nombreLocalidad"];
			return $arr;
		}
	}

	public function getArrDomicilioMunicipioEstado($idPaciente)
	{
		$arr = array();
		$sql = 'SELECT p.calle, p.noExterior, p.noInterior, p.colonia, m.nombre AS municipio, e.nombre as estado, l.nombre as localidad ' .
			'FROM pacientes p, catMunicipio m, catEstado e, catLocalidad l ' .
			'WHERE p.idCatEstado = e.idCatEstado  ' .
			'AND p.idCatMunicipio = m.idCatMunicipio ' .
			'AND e.idCatEstado = m.idCatEstado ' .
			'AND l.idCatEstado = p.idCatEstado ' .
			'AND l.idCatMunicipio = m.idCatMunicipio ' .
			'AND l.idCatLocalidad = p.idCatLocalidad ' .
			'AND p.idPaciente = ' . $idPaciente . ';';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {
			$registro = devuelveRowAssoc($result);
			$arr["domicilio"] = $registro["calle"] . " " . $registro["noExterior"] . " " . $registro["noInterior"];
			$arr["municipio"] = $registro["municipio"];
			$arr["estado"] = $registro["estado"];
			$arr["localidad"] = $registro["localidad"];
			return $arr;
		}
	}

	public function getSupervisorLab($idCatSupervisorLab)
	{
		$sql = 'SELECT nombre FROM catSupervisorLab WHERE idCatSupervisorLab = ' . $idCatSupervisorLab . ';';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {
			$registro = devuelveRowAssoc($result);
			return $registro["nombre"];
		}
	}
	
	public function getAnalistaLab($idCatAnalistaLab)
	{
		$sql = 'SELECT nombre FROM catAnalistaLab WHERE idCatAnalistaLab = ' . $idCatAnalistaLab . ';';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {
			$registro = devuelveRowAssoc($result);
			return $registro["nombre"];
		}
	}	

	public function getArrayCatBaciloscopia()
	{
		$arr = array();
		$sql = 'SELECT * FROM catBaciloscopia;';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			while ($registro = devuelveRowAssoc($result)) {
				$arr[$registro['idCatBaciloscopia']] = $registro['descripcion'];
			}
		}
		return $arr;
	}

	public function getEdadPaciente($idPaciente)
	{
		$sql = 'SELECT [dbo].[diferenciaAnos] (p.fechaNacimiento , GETDATE()) AS edad FROM pacientes p WHERE p.idPaciente = ' . $idPaciente . ';';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return 0;
		} else {			
			$registro = devuelveRowAssoc($result);
			return $registro['edad'];
		}				
	}

	public function getSexoPaciente($idPaciente)
	{
		$sql = 'SELECT s.sexo AS sexo FROM pacientes p, catSexo s WHERE p.idPaciente = ' . $idPaciente . ' AND s.idSexo = p.sexo;';
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return "?";
		} else {			
			$registro = devuelveRowAssoc($result);
			return $registro['sexo'];
		}				
	}
	
	public function getNameUnidad($idCatUnidad)
	{
		$sql = 'SELECT [nombreUnidad] FROM [catUnidad] WHERE [idCatUnidad]=\''.$idCatUnidad.'\'';		
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {			
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['nombreUnidad'];
	}
	
	public function getClavePaciente($idPaciente){
		$sql = "SELECT [cveExpediente] FROM [pacientes] WHERE [idPaciente]=".(int)$idPaciente;		
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {			
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['cveExpediente'];
	}
	
	public function getNamePaciente($idPaciente) {
		$sql = "SELECT ([nombre]+' '+[apellidoPaterno]+' '+[apellidoMaterno]) AS nombre FROM [pacientes] WHERE [idPaciente]=".(int)$idPaciente;		
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {			
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['nombre'];
	}

	public function getClavePacienteDiagnostico($idDiagnostico){
		$sql = "SELECT [cveExpediente] FROM pacientes p, diagnostico d WHERE d.idPaciente = p.idPaciente AND d.idDiagnostico = ".(int)$idDiagnostico;				
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {			
			$registro = devuelveRowAssoc($result);
			return $registro['cveExpediente'];
		}		
	}
	
	public function getNamePacienteDiagnostico($idDiagnostico) {
		$sql = "SELECT (p.nombre + ' ' + p.apellidoPaterno + ' ' + p.apellidoMaterno) AS nombre FROM pacientes p, diagnostico d WHERE d.idPaciente = p.idPaciente AND d.idDiagnostico = ".(int)$idDiagnostico;
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
			return '';
		} else {			
			$registro = devuelveRowAssoc($result);
			return $registro['nombre'];
		}				
	}
	
	public function getIdPacienteFromDiagnostico($idDiagnostico) {
		$sql = 'SELECT [idPaciente] FROM [diagnostico] WHERE [idDiagnostico]='.(int)$idDiagnostico;		
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {			
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['idPaciente'];
	}
	
	public function getDescripTipoEstudio($idCatTipoEstudio) {
		if(empty($idCatTipoEstudio))
			return '';
		
		$sql = 'SELECT [descripcion] FROM [catTipoEstudio] WHERE [idCatTipoEstudio]='.(int)$idCatTipoEstudio;		
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {			
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['descripcion'];
	}
	
	public function getDescripBaciloscopia($idCatBaciloscopia) {
		if(empty($idCatBaciloscopia))
			return '';
	
		$sql = 'SELECT [descripcion] FROM [catBaciloscopia] WHERE [idCatBaciloscopia]='.(int)$idCatBaciloscopia;		
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {			
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['descripcion'];
	}

	public function getDatosUnidad($idCatUnidad) {
		$sql = 'SELECT 
					[catUnidad].[idCatEstado],
					[catMunicipio].[idCatJurisdiccion],
					[catUnidad].[idCatMunicipio] 
				FROM [catUnidad],[catMunicipio] 
				WHERE [catMunicipio].[idCatEstado] = [catUnidad].[idCatEstado] AND 
					[catMunicipio].[idCatMunicipio] = [catUnidad].[idCatMunicipio] AND 
					[idCatUnidad]=\''.$idCatUnidad.'\'';
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro;
	}
    
    public function getDescripcionSexo($idSexo) {
		$sql = 'SELECT [sexo] FROM [catSexo] WHERE [idSexo]=\''.$idSexo.'\'';
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['sexo'];
	}
	
	 public function getDescripcionHistopatologia($idCatHisto) {
		if(empty($idCatHisto))
			return '';
		
		$sql = 'SELECT [descripcion] FROM [catHistopatologia] WHERE [idCatHisto]='.(int)$idCatHisto;
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['descripcion'];
	}
	
	public function getNombreContacto($idContacto) {
		$sql = 'SELECT [nombre] FROM [contactos] WHERE [idContacto]='.(int)$idContacto;
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['nombre'];
	}
    
    public function getAllTipoLesion($idDiagnostico) {
        $registro = null;
		$sql = 'SELECT [idCatTipoLesion] FROM [diagramaDermatologico] WHERE [idDiagnostico]='.(int)$idDiagnostico.' GROUP BY [idCatTipoLesion]';
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
            while ($row = devuelveRowAssoc($result)) {
                $registro[] = $row['idCatTipoLesion'];
            }
		}
		
		return $registro;
	}
    
    public function getLastBaciloscopia($idDiagnostico, $idPaciente) {
        $registro = null;
		$sql = 'SELECT [fechaResultado],[idCatBac],[bacIM] FROM [estudiosBac] 
                WHERE [idDiagnostico]='.(int)$idDiagnostico.' AND [idPaciente]='.(int)$idPaciente.' 
                    AND [fechaResultado] IS NOT NULL AND [idContacto] IS NULL
                ORDER BY [fechaResultado] DESC';
		
        //echo $sql;
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
            $registro = devuelveRowAssoc($result);
		}
		
		return $registro;
	}
    
    public function getNombreEstado($idCatEstado) {
		if(empty($idCatEstado))
			return '';
		
		$sql = 'SELECT [nombre] FROM [catEstado] WHERE [idCatEstado]='.(int)$idCatEstado;
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['nombre'];
	}
    
    public function getNombreJurisdiccion($idCatEstado, $idCatJurisdiccion) {
		if(empty($idCatEstado))
			return '';
		
		$sql = 'SELECT [nombre] FROM [catJurisdiccion] WHERE [idCatEstado]='.(int)$idCatEstado.' AND [idCatJurisdiccion]='.(int)$idCatJurisdiccion;
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['nombre'];
	}
    
    public function getNombreMunicipio($idCatEstado, $idCatJurisdiccion, $idCatMunicipio) {
		if(empty($idCatEstado))
			return '';
		
		$sql = 'SELECT [nombre] FROM [catMunicipio] WHERE [idCatEstado]='.(int)$idCatEstado.' AND [idCatJurisdiccion]='.(int)$idCatJurisdiccion.' AND [idCatMunicipio]='.(int)$idCatMunicipio;
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['nombre'];
	}
    
    public function getNombreUnidad($idCatUnidad) {
		if(empty($idCatUnidad))
			return '';
		
		$sql = 'SELECT [nombreUnidad] FROM [catUnidad] WHERE [idCatUnidad]=\''.$idCatUnidad.'\'';
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
		}
		
		return $registro['nombreUnidad'];
	}
	
	public function getAllEstudiosBacFromPaciente($idPaciente) {
		$sql = 'SELECT [idEstudioBac] FROM [estudiosBac] WHERE [idPaciente]='.$idPaciente;
		$arreglo = null;
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
			$arreglo[] = $registro['idEstudioBac'];
		}
		
		return $arreglo;
	}
	
	public function getAllEstudiosHisFromPaciente($idPaciente) {
		$sql = 'SELECT [idEstudioHis] FROM [estudiosHis] WHERE [idPaciente]='.$idPaciente;
		$arreglo = null;
		
		$result = ejecutaQueryClases($sql);
		if (is_string($result)) {
			$this->error = true;
			$this->msgError = $result . " SQL:" . $sql;
		} else {
			$registro = devuelveRowAssoc($result);
			$arreglo[] = $registro['idEstudioHis'];
		}
		
		return $arreglo;
	}
    
}
?>