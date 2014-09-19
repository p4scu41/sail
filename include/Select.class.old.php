<?PHP
class Select {
	var $valores_array;
	var $objCatalogo;
	var $objHTML;
	
	function Select() {
		$this->valores_array = NULL;
		$this->objCatalogo = new Catalogo();
		$this->objHTML = new HTML();
	}
	
	function SelectCatalogo($label, $name, $catalogo, $default=NULL, $attributes = array(), $addIndex = TRUE, $return = false, $sort = false) {
		$this->objCatalogo->setTabla($catalogo);
		$this->valores_array = $this->objCatalogo->getValores();
		
		if($addIndex == TRUE) {
			foreach($this->valores_array as $index => $value)
				$this->valores_array[$index] = $value;
				//$this->valores_array[$index] = $index.'. '.$value;
		}
        
        if($sort) {
            asort($this->valores_array);
        }
        
        if($return)
            return $this->objHTML->inputSelect($label, $name, $this->valores_array, $default, $attributes, $return);
        else
            $this->objHTML->inputSelect($label, $name, $this->valores_array, $default, $attributes, $return);
	}
	
	function selectEstado($name, $default=NULL, $attributes = array(), $setEtiqueta=true) {
		$this->objCatalogo->setTabla('catEstado');
		$this->valores_array = array();
		$this->valores_array2 = $this->objCatalogo->getValores();
		
		$aux = false;
		
		//print_r($_SESSION); exit(0);
		if($_SESSION[EDO_USR_SESSION] == 0){
			$this->valores_array[0] = "Nacional";
			$aux = true;
		}
		//echo $default; exit(0);
		foreach($this->valores_array2 as $index => $value){
            // pascual - comente esta linea para que aparesca la lista de estados al agregar el resultado de laboratorio
			//if($default == $index || $aux)
				$this->valores_array[$index] = $value.' ['.str_pad($index,2,'0',STR_PAD_LEFT).']';
		}
		
		$this->objHTML->inputSelect( ($setEtiqueta ? 'Estado ' : '') , $name, $this->valores_array, $default, $attributes);
	}
	
	function selectEstadoGeneral($name, $default=NULL, $attributes = array(), $setEtiqueta=true) {
		$this->objCatalogo->setTabla('catEstado');
		$this->valores_array = array();
		$this->valores_array2 = $this->objCatalogo->getValores();
		
		foreach($this->valores_array2 as $index => $value){
			$this->valores_array[$index] = $value.' ['.str_pad($index,2,'0',STR_PAD_LEFT).']';
		}
		
		$this->objHTML->inputSelect( ($setEtiqueta ? 'Estado ' : '') , $name, $this->valores_array, $default, $attributes);
	}
	
	function selectJurisdiccion($name, $edo, $default=NULL, $attributes = array(), $setEtiqueta=true) {
		if ($edo != NULL) {
			$this->objCatalogo->setQuery('SELECT idCatJurisdiccion, nombre FROM catJurisdiccion WHERE idCatEstado = '.$edo);
			$this->valores_array = $this->objCatalogo->getValores();
			
			foreach($this->valores_array as $index => $value)
				$this->valores_array[$index] = $value.' ['.str_pad($index,2,'0',STR_PAD_LEFT).']';
		}
		else
			$this->valores_array = array();
			
		$this->objHTML->inputSelect( ($setEtiqueta ? 'Jurisdicción ' : '') , $name, $this->valores_array, $default, $attributes);
	}
	
	function selectMunicipio($name, $edo, $juris=NULL, $default=NULL, $attributes = array(), $setEtiqueta=true) {
		if ($edo != NULL) {
			$query = 'SELECT idCatMunicipio, nombre FROM catMunicipio WHERE idCatEstado = '.$edo;
			
			if($juris!=NULL)
				$query .= ' AND idCatJurisdiccion = '.$juris;
			
			$this->objCatalogo->setQuery($query.' ORDER BY nombre');
			$this->valores_array = $this->objCatalogo->getValores();
		}
		else
			$this->valores_array = array();
		
		foreach($this->valores_array as $index => $value)
			$this->valores_array[$index] = ucwords(mb_strtolower($value)).' ['.str_pad($index,3,'0',STR_PAD_LEFT).']';
		
		$this->objHTML->inputSelect(($setEtiqueta ? 'Municipio ' : '') , $name, $this->valores_array, $default, $attributes);
	}
	
	function selectLocalidad($name, $edo=NULL, $muni=NULL, $default=NULL, $attributes = array(), $setEtiqueta=true) {
		if($edo != NULL) {
			$query = 'SELECT idCatLocalidad, nombre FROM catLocalidad WHERE idCatEstado = '.$edo;
			
			if($muni!=NULL)
				$query .= ' AND idCatMunicipio = '.$muni;
				
			$this->objCatalogo->setQuery($query.' ORDER BY nombre');
			$this->valores_array = $this->objCatalogo->getValores();
			
			foreach($this->valores_array as $index => $value)
				$this->valores_array[$index] = $value.' ['.str_pad($index,4,'0',STR_PAD_LEFT).']';
		}
		else
			$this->valores_array = array();
		
		$this->objHTML->inputSelect( ($setEtiqueta ? 'Localidad ' : '') , $name, $this->valores_array, $default, $attributes);
	}
	
	function selectUnidad($name, $edo, $juris=NULL, $muni=NULL, $locali=NULL, $default=NULL, $attributes = array(), $setEtiqueta=true) {
		if ($edo != NULL) {
			$query = 'SELECT idCatUnidad, nombreUnidad FROM catUnidad WHERE idCatEstado='.$edo;
			
			if($juris!=NULL)
				$query .= ' AND idCatMunicipio IN (SELECT idCatMunicipio FROM catMunicipio WHERE idCatEstado='.$edo.' AND idCatJurisdiccion = '.$juris.')';
			if($muni!=NULL)
				$query .= ' AND idCatMunicipio = '.$muni;
			if($locali!=NULL)
				$query .= ' AND idCatLocalidad = '.$locali;
            
            $query .= ' ORDER BY nombreUnidad';
			
			$this->objCatalogo->setQuery($query);
			$this->valores_array = $this->objCatalogo->getValores();
		}
		else
			$this->valores_array = array();
		
		foreach($this->valores_array as $index => $value)
			$this->valores_array[$index] = ucwords(mb_strtolower($value)).' ['.$index.']';
		
		$this->objHTML->inputSelect( ($setEtiqueta ? 'Unidad ' : '') , $name, $this->valores_array, $default, $attributes);
	}
	
	function selectGlobias($label, $name, $value = 1, $attributes)
	{
		foreach($attributes as $key => $val) {
			$strInput .= $key.'="'.$val.'" ';
		}
		if($value == 2)
			$selected = "selected=selected";
		echo '<label>'.htmlentities(utf8_decode(trim($label))).' 
			<select name = "'.$name.'" id="'.$name.'" '.$strInput.' >
				<option value="1">No</option>
				<option value="2" '.$selected.'>Si</option>
			</select>
		';
	}
}
?>
