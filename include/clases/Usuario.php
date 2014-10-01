<?php
class Usuario {

    public $idUsuario;
    public $nombreUsuario;
    public $password;
    public $nombre;
    public $apellidoPaterno;
    public $apellidoMaterno;
    public $correo;
    public $habilitado;
    public $idCatTipoUsuario;
    public $idCatEstado;
    public $idCatJurisdiccion;

	public $error = false;
	public $msgError;

	public function insertarBD() {
		$sqlA = 'INSERT INTO [usuarios] ([nombreUsuario], [password], [nombre], [apellidoPaterno], 
                [apellidoMaterno], [correo], [idCatTipoUsuario]';
		$sqlB = 'VALUES (\''.trim($this->nombreUsuario).'\', \''.md5(trim($this->password)).'\', \''.$this->nombre.'\', \''.$this->apellidoPaterno
                .'\', \''.$this->apellidoMaterno.'\', \''.$this->correo.'\', \''.$this->idCatTipoUsuario.'\' ';
		
		if($this->idCatEstado != '' && !is_null($this->idCatEstado)) { $sqlA .= ', [idCatEstado]'; $sqlB .= ', \'' . $this->idCatEstado.'\''; }
		if($this->idCatJurisdiccion != '' && !is_null($this->idCatJurisdiccion)) { $sqlA .= ', [idCatJurisdiccion]'; $sqlB .= ', \'' . $this->idCatJurisdiccion.'\''; }
        if($this->habilitado != '' && !is_null($this->habilitado)) { $sqlA .= ', [habilitado]'; $sqlB .= ', \'' . $this->habilitado.'\''; }
		
        $sqlA .= ') ' . $sqlB . '); SELECT @@Identity AS nuevoId;';	
		$consulta = ejecutaQueryClases($sqlA);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . ' SQL:' . $sqlA;
		} else {
			sqlsrv_next_result($consulta);			
			$tabla = devuelveRowAssoc($consulta);
			$this->idUsuario = $tabla['nuevoId'];
		}
	}

	public function modificarBD() {
		$sql = 'UPDATE [usuarios] SET ';
		$sql .= ' [habilitado] = ' . (int)$this->habilitado . ' ';
		
        if($this->nombreUsuario != '' && !is_null($this->nombreUsuario)) { $sql .= ',[nombreUsuario] = \'' . $this->nombreUsuario . '\' ' ; }
		if($this->password != '' && !is_null($this->password)) { $sql .= ',[password] = \'' . md5($this->password). '\' ' ; }
        if($this->nombre != '' && !is_null($this->nombre)) { $sql .= ',[nombre] = \'' . $this->nombre . '\' ' ; }
        if($this->apellidoPaterno != '' && !is_null($this->apellidoPaterno)) { $sql .= ',[apellidoPaterno] = \'' . $this->apellidoPaterno . '\' ' ; }
        if($this->apellidoMaterno != '' && !is_null($this->apellidoMaterno)) { $sql .= ',[apellidoMaterno] = \'' . $this->apellidoMaterno . '\' ' ; }
        if($this->correo != '' && !is_null($this->correo)) { $sql .= ',[correo] = \'' . $this->correo . '\' ' ; }
        if($this->idCatTipoUsuario != '' && !is_null($this->idCatTipoUsuario)) { $sql .= ',[idCatTipoUsuario] = \'' . $this->idCatTipoUsuario . '\' ' ; }
        if($this->idCatEstado != '' && !is_null($this->idCatEstado)) { $sql .= ',[idCatEstado] = \'' . $this->idCatEstado . '\' ' ; }
        if($this->idCatJurisdiccion != '' && !is_null($this->idCatJurisdiccion)) { $sql .= ',[idCatJurisdiccion] = \'' . $this->idCatJurisdiccion . '\' ' ; }
        
		$sql .= 'WHERE idUsuario = ' . $this->idUsuario . ';';
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . ' SQL:' . $sql;
		}
	}

	public function obtenerBD($idUsuario) {
		$sql = 'SELECT * FROM [usuarios] WHERE idUsuario = ' . $idUsuario;
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . ' SQL:' . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			$this->nombreUsuario = $tabla['nombreUsuario'];
			$this->password = ''; //$this->password = '';
            $this->nombre = $tabla['nombre'];
			$this->apellidoPaterno = $tabla['apellidoPaterno'];
            $this->apellidoMaterno = $tabla['apellidoMaterno'];
            $this->correo = $tabla['correo'];
            $this->idCatTipoUsuario = $tabla['idCatTipoUsuario'];
            $this->idCatEstado = $tabla['idCatEstado'];
            $this->idCatJurisdiccion = $tabla['idCatJurisdiccion'];
            $this->habilitado = $tabla['habilitado'];
            $this->idUsuario = $tabla['idUsuario'];
		}
	}
    
    public function obtenerTodos($filtro) {
        $resultado = null;
		$sql = 'SELECT [idUsuario]
                    ,[nombreUsuario]
                    ,[usuarios].[nombre]
                    ,[apellidoPaterno]
                    ,[apellidoMaterno]
                    ,[catTipoUsuario].[descripcion] AS tipo_usuario
                    ,[catEstado].[nombre] as estado
                    ,[idCatJurisdiccion]
                    ,[correo]
                    ,[habilitado]
                FROM [usuarios]
                LEFT JOIN [catEstado] ON 
                    [usuarios].[idCatEstado] = [catEstado].[idCatEstado]
                LEFT JOIN [catTipoUsuario] ON 
                    [usuarios].[idCatTipoUsuario] = [catTipoUsuario].[idCatTipoUsuario]';
        
        if(count($filtro) > 0) {
            $sql .= ' WHERE 1=1 ';
            
            if(!empty($filtro['nombre'])) {
                $sql .= ' AND ([usuarios].[nombre] like \'%'.$filtro['nombre'].'%\' or nombreUsuario like \'%'.$filtro['nombre'].'%\') ';
            }
            
            if(!empty($filtro['idCatTipoUsuario'])) {
                $sql .= ' AND [usuarios].[idCatTipoUsuario] = '.$filtro['idCatTipoUsuario'];
            }
            
            if(!empty($filtro['idCatEstado'])) {
                $sql .= ' AND [usuarios].[idCatEstado] = '.$filtro['idCatEstado'];
            }
        }
        
        $sql .= ' ORDER BY [nombre]';
        
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . ' SQL:' . $sql;
		} else {
            while ($registro = devuelveRowAssoc($consulta)) {
                $resultado[] = $registro;
            }
		}
        
        return $resultado;
	}
    
    function validNombreUsuario() {
        $sql = 'SELECT count(*) as total FROM [usuarios] WHERE nombreUsuario = \'' . $this->nombreUsuario .'\'';
		
		$consulta = ejecutaQueryClases($sql);
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . ' SQL:' . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
            
            if($tabla['total'] == 0)
                return true;
            else
                return false;
		}
    }
}
?>