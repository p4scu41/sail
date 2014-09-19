<?php

class BusquedaPaciente {
	
	public $idCatEstado;
	public $idCatJurisdiccion;
	public $idCatMunicipio;
	public $idCatUnidad;
	public $idCatTipoPaciente;
	public $clavePaciente;
	public $nombre;
	public $apellidoP;
	public $apellidoM;
	public $statusPaciente;
	
	public $resultado = array();
	
	public $error = false;
	public $msgError;
	
	public $totalRegistro;
	public $maxPages;
	public $page = 1;
	
	private function queryBusquedaDiagnostico($fields)
	{
		$sqlP = "SELECT ".$fields." ".
			"FROM pacientes p, catJurisdiccion j, catEstado e, catMunicipio m, diagnostico d " .
			"WHERE p.idCatEstado = e.idCatEstado " .
			"AND p.idCatMunicipio = m.idCatMunicipio " .
			"AND m.idCatEstado = e.idCatEstado " .
			"AND j.idCatEstado = m.idCatEstado " .
			"AND m.idCatJurisdiccion = j.idCatJurisdiccion ".
			"AND d.idPaciente = p.idPaciente "; //.
			//"AND e.idCatEstado = " . $this->idCatEstado . " ";
            // p4scu41
            if (!is_null($this->idCatEstado) && $this->idCatEstado != '') $sqlP .= "AND e.idCatEstado = " . $this->idCatEstado . " ";
			if (!is_null($this->idCatJurisdiccion) && $this->idCatJurisdiccion != '') $sqlP .= "AND j.idCatJurisdiccion = " . $this->idCatJurisdiccion . " ";
			if (!is_null($this->idCatMunicipio) && $this->idCatMunicipio != '') $sqlP .= "AND m.idCatMunicipio = " . $this->idCatMunicipio . " ";
			if (!is_null($this->apellidoP) && $this->apellidoP != '') $sqlP .= "AND p.apellidoPaterno = '" . $this->apellidoP ."' ";
			if (!is_null($this->apellidoM) && $this->apellidoM != '') $sqlP .= "AND p.apellidoMaterno = '" . $this->apellidoM ."' ";
			if (!is_null($this->nombre) && $this->nombre != '') $sqlP .= "AND p.nombre like '%" . $this->nombre ."%' ";
			if (!is_null($this->clavePaciente) && $this->clavePaciente != '') $sqlP .= "AND p.cveExpediente = '" . $this->clavePaciente ."' ";
			if (!is_null($this->idCatUnidad) && $this->idCatUnidad != '') $sqlP .= "AND p.idCatUnidadTratante = '" . $this->idCatUnidad . "' ";
			if (!is_null($this->statusPaciente) && $this->statusPaciente != '') $sqlP .= "AND d.idCatEstadoPaciente = ".$this->statusPaciente;
			if (!is_null($this->idCatTipoPaciente) && $this->idCatTipoPaciente != '') $sqlP .= "AND p.idCatTipoPaciente = " . $this->idCatTipoPaciente ."";
			//$sqlP .= ";";
		return $sqlP;
	}
	
	private function queryBusquedaSospechoso($fields)
	{
		$sqlP = "SELECT ".$fields." ".
			"FROM pacientes p, catJurisdiccion j, catEstado e, catMunicipio m, sospechoso d " .
			"WHERE p.idCatEstado = e.idCatEstado " .
			"AND p.idCatMunicipio = m.idCatMunicipio " .
			"AND m.idCatEstado = e.idCatEstado " .
			"AND j.idCatEstado = m.idCatEstado " .
			"AND m.idCatJurisdiccion = j.idCatJurisdiccion ".
			"AND d.idPaciente = p.idPaciente "; //.
			///"AND e.idCatEstado = " . $this->idCatEstado . " ";
            // p4scu41
            if (!is_null($this->idCatEstado) && $this->idCatEstado != '') $sqlP .= "AND e.idCatEstado = " . $this->idCatEstado . " ";
			if (!is_null($this->idCatJurisdiccion) && $this->idCatJurisdiccion != '') $sqlP .= "AND j.idCatJurisdiccion = " . $this->idCatJurisdiccion . " ";
			if (!is_null($this->idCatMunicipio) && $this->idCatMunicipio != '') $sqlP .= "AND m.idCatMunicipio = " . $this->idCatMunicipio . " ";
			if (!is_null($this->apellidoP) && $this->apellidoP != '') $sqlP .= "AND p.apellidoPaterno = '" . $this->apellidoP ."' ";
			if (!is_null($this->apellidoM) && $this->apellidoM != '') $sqlP .= "AND p.apellidoMaterno = '" . $this->apellidoM ."' ";
			if (!is_null($this->nombre) && $this->nombre != '') $sqlP .= "AND p.nombre like '%" . $this->nombre ."%' ";
			if (!is_null($this->clavePaciente) && $this->clavePaciente != '') $sqlP .= "AND p.cveExpediente = '" . $this->clavePaciente ."' ";
			if (!is_null($this->idCatUnidad) && $this->idCatUnidad != '') $sqlP .= "AND p.idCatUnidadTratante = '" . $this->idCatUnidad . "' ";
			if (!is_null($this->idCatTipoPaciente) && $this->idCatTipoPaciente != '') $sqlP .= "AND p.idCatTipoPaciente = " . $this->idCatTipoPaciente ."";/**/
			$sqlP .= "AND p.idPaciente NOT IN(".$this->queryBusquedaDiagnostico("p.idPaciente").")";
		return $sqlP;
	}

	public function buscar()
	{
		//Se crea la vista para manejarla
		$sqlP1 = $this->queryBusquedaDiagnostico("p.idPaciente, m.idCatMunicipio, j.idCatJurisdiccion, e.idCatEstado, p.nombre, p.apellidoPaterno, p.apellidoMaterno, p.sexo, p.cveExpediente, p.idCatTipoPaciente, p.idCatUnidadNotificante, p.idCatUnidadTratante, d.idCatEstadoPaciente");
		$sqlP2 = $this->queryBusquedaSospechoso("p.idPaciente, m.idCatMunicipio, j.idCatJurisdiccion, e.idCatEstado, p.nombre, p.apellidoPaterno, p.apellidoMaterno, p.sexo, p.cveExpediente, p.idCatTipoPaciente, p.idCatUnidadNotificante, p.idCatUnidadTratante, 0");
		
		$sql = "DROP VIEW view_pacientes";
		ejecutaQueryClases($sql);
		
		$sql = "
			CREATE VIEW view_pacientes
			AS
			".$sqlP1."
			UNION
			".$sqlP2.";
		";
		ejecutaQueryClases($sql);
		
		$sql = "SELECT COUNT(*) as totalRegistros FROM view_pacientes;";
			
		$totalRows = ejecutaQueryClases($sql);
		//echo 'adafdfasd'.devuelveNumRows($totalRows);
		while ($totalRow = devuelveRowAssoc($totalRows))
		{
			$this->totalRegistro = $totalRow['totalRegistros'];
		}
		
		$this->maxPages = ceil($this->totalRegistro/MAX_PER_PAGE);
		
		$sql = "SELECT TOP ".MAX_PER_PAGE." * FROM view_pacientes WHERE 1=1 ";
		//"AND e.idCatEstado = " . $this->idCatEstado . " ";
            // p4scu41
            if (!is_null($this->idCatEstado) && $this->idCatEstado != '') $sql .= "AND idCatEstado = " . $this->idCatEstado . " ";
			if (!is_null($this->idCatJurisdiccion) && $this->idCatJurisdiccion != '') $sql .= "AND idCatJurisdiccion = " . $this->idCatJurisdiccion . " ";
			if (!is_null($this->idCatMunicipio) && $this->idCatMunicipio != '') $sql .= "AND idCatMunicipio = " . $this->idCatMunicipio . " ";
			if (!is_null($this->apellidoP) && $this->apellidoP != '') $sql .= "AND apellidoPaterno = '" . $this->apellidoP ."' ";
			if (!is_null($this->apellidoM) && $this->apellidoM != '') $sql .= "AND apellidoMaterno = '" . $this->apellidoM ."' ";
			if (!is_null($this->nombre) && $this->nombre != '') $sqlP .= "AND nombre like '%" . $this->nombre ."%' ";
			if (!is_null($this->clavePaciente) && $this->clavePaciente != '') $sql .= "AND cveExpediente = '" . $this->clavePaciente ."' ";
			if (!is_null($this->idCatUnidad) && $this->idCatUnidad != '') $sql .= "AND idCatUnidadTratante = '" . $this->idCatUnidad . "' ";
			if (!is_null($this->statusPaciente) && $this->statusPaciente != '') $sql .= "AND idCatEstadoPaciente = ".$this->statusPaciente;
			if (!is_null($this->idCatTipoPaciente) && $this->idCatTipoPaciente != '') $sql .= "AND idCatTipoPaciente = " . $this->idCatTipoPaciente ."";
			
		$sql2 = "SELECT TOP ".(($this->page-1)*MAX_PER_PAGE)." idPaciente FROM view_pacientes WHERE 1=1 ";
			if (!is_null($this->idCatEstado) && $this->idCatEstado != '') $sql2 .= "AND idCatEstado = " . $this->idCatEstado . " ";
			if (!is_null($this->idCatJurisdiccion) && $this->idCatJurisdiccion != '') $sql2 .= "AND idCatJurisdiccion = " . $this->idCatJurisdiccion . " ";
			if (!is_null($this->idCatMunicipio) && $this->idCatMunicipio != '') $sql2 .= "AND idCatMunicipio = " . $this->idCatMunicipio . " ";
			if (!is_null($this->apellidoP) && $this->apellidoP != '') $sql2 .= "AND apellidoPaterno = '" . $this->apellidoP ."' ";
			if (!is_null($this->apellidoM) && $this->apellidoM != '') $sql2 .= "AND apellidoMaterno = '" . $this->apellidoM ."' ";
			if (!is_null($this->nombre) && $this->nombre != '') $sql2 .= "AND nombre like '%" . $this->nombre ."%' ";
			if (!is_null($this->clavePaciente) && $this->clavePaciente != '') $sql2 .= "AND cveExpediente = '" . $this->clavePaciente ."' ";
			if (!is_null($this->idCatUnidad) && $this->idCatUnidad != '') $sql2 .= "AND idCatUnidadTratante = '" . $this->idCatUnidad . "' ";
			if (!is_null($this->statusPaciente) && $this->statusPaciente != '') $sql2 .= "AND idCatEstadoPaciente = ".$this->statusPaciente;
			if (!is_null($this->idCatTipoPaciente) && $this->idCatTipoPaciente != '') $sql2 .= "AND idCatTipoPaciente = " . $this->idCatTipoPaciente ."";
		
			
		$sql2 .= " ORDER BY idPaciente";
		$sql .= " AND idPaciente NOT IN (".$sql2.")";
			
		
	/*	$sql3 = $this->queryBusquedaSospechoso("TOP ".MAX_PER_PAGE." p.idPaciente, p.nombre, p.apellidoPaterno, p.apellidoMaterno, p.sexo, p.cveExpediente, p.idCatTipoPaciente, p.idCatUnidadNotificante, p.idCatUnidadTratante, p.idPaciente ");
		//$sql2 = $this->queryBusquedaSospechoso("TOP ".(($this->page-1)*MAX_PER_PAGE)." p.idPaciente ");
			
		//$sql2 .= " ORDER BY p.idPaciente";
		//$sql3 .= " AND p.idPaciente NOT IN (".$sql2.")";
		
		$sqlCompleto = $sql." UNION ".$sql3;
		
		$sqlCompleto .= " ORDER BY p.idPaciente";*/
			//$sql .= ";";
			
		$consulta = ejecutaQueryClases($sql);
		
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$arr = array();
			$c = 0;
			while ($registro = devuelveRowAssoc($consulta)) {
				$paciente = new resultadoBusquedaPaciente();
				$paciente->idPaciente = $registro['idPaciente'];
				$paciente->nombre = $registro['nombre'];
				$paciente->apellidoPaterno = $registro['apellidoPaterno'];
				$paciente->apellidoMaterno = $registro['apellidoMaterno'];
				$paciente->sexo = $registro['sexo'];
				$paciente->cveExpediente = $registro['cveExpediente'];
				$paciente->idCatTipoPaciente = $registro['idCatTipoPaciente'];
				$paciente->idCatUnidadNotificante = $registro['idCatUnidadNotificante'];
				$paciente->idCatUnidadTratante = $registro['idCatUnidadTratante'];
				$arr[$c] = $paciente;
                $c++;
			}
            
			$this->resultado = $arr;
		}		
	}
}

class resultadoBusquedaPaciente {

	public $idPaciente;
	public $nombre;
	public $apellidoPaterno;
	public $apellidoMaterno;
	public $sexo;
	public $cveExpediente;
	public $idCatTipoPaciente;
	public $idCatUnidadNotificante;
	public $idCatUnidadTratante;

}
?>