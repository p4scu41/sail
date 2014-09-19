<?PHP
class Catalogo
{
	var $tamano = 0;
	var $valores = NULL;
	var $tabla = NULL;
	var $query = NULL;
	
	function Catalogo($tabla='') {
		$this->tabla = $tabla;
	}
	
	function agregarEntrada($index, $value) {
		$this->valores[$index] = $value;
	}
	
	function levantarCatalogo() {
		$datos = NULL;
		$this->valores = NULL;
		
		if($this->query == NULL)
			$query = 'SELECT * FROM '.$this->tabla;
		else
			$query = $this->query;
		
		$result = ejecutaQuery($query);
	
		if(!$result) {
			return false;
		}
		else {
			$this->tamano = devuelveNumRows($result);
			
			while($datos = devuelveRowArray($result)) {
				$this->agregarEntrada($datos[0], $datos[1]);
			}
			
			return true;
		}
	}
	
	function getValores() {
		$this->levantarCatalogo();
		
		return $this->valores;
	}
	
	function setTabla($tabla) {
		$this->tabla = $tabla;
		$this->query = NULL;
		
	}
	
	function setQuery($query) {
		$this->query = $query;
		$this->tabla = NULL;
	}
}
?>