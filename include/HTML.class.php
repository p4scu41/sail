<?PHP
// p4scu41
// 23-03-2013

class HTML {
	
	function makeInput($type, $label, $name, $value, $attributes = array()) {
		$strInput = '<input type="'.trim($type).'" name="'.trim($name).'" id="'.trim($name).'" value="'.trim($value).'" ';
		
		if(count($attributes)!=0) {
			foreach($attributes as $key => $val) {
				$strInput .= trim($key).'="'.trim($val).'" ';
			}
		}
		
		$strInput .= ' />';
		
		switch($type) {
			case 'checkbox';
			case 'radio';
				$strInput = '<label>'.$strInput.' '.htmlentities(utf8_decode(trim($label))).'</label>';
			break;
			case 'hidden':
			case 'submit':
			case 'reset':
			case 'button':
				// No agregamos label
			break;
			default:
				$strInput = '<label>'.htmlentities(utf8_decode(trim($label))).' '.$strInput.'</label>';
		}
		
		return $strInput;
	}
	
	function inputButton($name, $value, $attributes = array(), $return = false) {
		$strInput = $this->makeInput('button', '', $name, $value, $attributes);
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function inputCheckbox($label, $name, $value, $default='', $attributes = array(), $return = false) {
		if($value == $default)
			$attributes['checked'] = 'checked';
		
		$strInput = $this->makeInput('checkbox', $label, $name, $value, $attributes);
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function inputHidden($name, $value, $return = false) {
		$strInput = $this->makeInput('hidden', '', $name, $value, NULL);
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function inputRadio($name, $elements, $default=NULL, $attributes = array(), $addBr='', $return = false) {
		$strInput = '';
		
		foreach($elements as $key => $val) {
			
			if($default == $key && $default!==NULL)
				$attributes['checked'] = 'checked';
			else
				unset($attributes['checked']);
			
			$strInput .= $this->makeInput('radio', $val, $name, $key, $attributes).$addBr;
		}
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function inputSubmit($name, $value, $attributes = array(), $return = false) {
		$strInput = $this->makeInput('submit', '', $name, $value, $attributes);
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function inputReset($name, $value, $attributes = array(), $return = false) {
		$strInput = $this->makeInput('reset', '', $name, $value, $attributes);
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function inputSelect($label, $name, $elements, $default='', $attributes = array(), $return = false) {
		$selected = '';
		$strInput = '<label>'.htmlentities(utf8_decode(trim($label))).' <select name="'.trim($name).'" id="'.trim($name).'" ';
		
		foreach($attributes as $key => $val) {
			$strInput .= $key.'="'.$val.'" ';
		}
		
		$strInput .= ' > <option value="" > Elegir </option> ';
		
		foreach($elements as $key => $val) {
			
			if($default == $key && $default!==null)
				$selected = 'selected="selected"';
			else
				$selected = '';
			
			$strInput .= '<option value="'.trim($key).'" '.$selected.'>'.htmlentities(trim($val)).'</option> ';
		}
		
		$strInput .= '</select> </label>';
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function inputSelect2($label, $name, $elements, $default='', $attributes = array(), $return = false) {
		$selected = '';
		$strInput = '<label>'.htmlentities(utf8_decode(trim($label))).' <select name="'.trim($name).'" id="'.trim($name).'" ';
		
		foreach($attributes as $key => $val) {
			$strInput .= $key.'="'.$val.'" ';
		}
		
		$strInput .= ' > <option value="" > Elegir </option> ';
		
		foreach($elements as $key => $val) {
			
			if($default == $key)
				$selected = 'selected="selected"';
			else
				$selected = '';
			
			$strInput .= '<option value="'.trim($val).'" '.$selected.'>'.htmlentities(trim($val)).'</option> ';
		}
		
		$strInput .= '</select> </label>';
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function inputText($label, $name, $value='', $attributes = array(), $return = false) {
		$strInput = $this->makeInput('text', $label, $name, $value, $attributes);
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
    
	/**
     * Genera un elemento HTML Textarea
     * 
     * @param string $label Etiqueta
     * @param string $name Nombre (ID)
     * @param string $value Valor default
     * @param array $attributes 
     * @param bool $return si es false imprime el elemento, si es true devuelve una cadena html del elemento
     * 
     * @return string|echo Depende de $return, si es false imprime el elemento, si es true devuelve una cadena html del elemento
     */
	function inputTextarea($label, $name, $value='', $attributes = array(), $return = false, $raw = false) {
		$strInput = '<label>'.htmlentities(trim(utf8_decode($label))).' <textarea name="'.trim($name).'" id="'.trim($name).'" ';
		
		foreach($attributes as $key => $val) {
			$strInput .= trim($key).'="'.trim($val).'" ';
		}
		
		if($raw)
			$strInput .= '>'.trim($value).'</textarea></label>';
		else
			$strInput .= '>'.htmlentities(trim($value)).'</textarea></label>';
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function startFieldset($label='', $attributes = array(), $return = false) {
		$strInput .= '<fieldset ';
		
		foreach($attributes as $key => $val) {
			$strInput .= trim($key).'="'.trim($val).'" ';
		}
		
		$strInput .= '>';
		
		if($label !='')
			$strInput .= '<legend>'.htmlentities(utf8_decode(trim($label))).'</legend>';
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function endFieldset($return = false) {
		$strInput = '</fieldset>';
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function startForm($name, $action, $method, $attributes = array(), $return = false) {
		$strInput .= '<form name="'.trim($name).'" id="'.trim($name).'" action="'.trim($action).'" method="'.trim($method).'" ';
		
		foreach($attributes as $key => $val) {
			$strInput .= trim($key).'="'.trim($val).'" ';
		}
		
		$strInput .= '>';
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function endForm($nameSubmit, $valueSubmit, $nameReset, $valueReset, $return = false) {
		$strInput = '<div align="center">';
		$strInput .= $this->inputSubmit($nameSubmit, $valueSubmit, NULL, true);
		$strInput .= ' &nbsp; ';
		$strInput .= $this->inputReset($nameReset, $valueReset, NULL, true);
		$strInput .= '</div></form>';
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function endFormOnly($return = false) {
		$strInput = '</form>';
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
	
	function label($text, $attributes = array(), $return = false) {
		$strInput = '<label ';
		
		foreach($attributes as $key => $val) {
			$strInput .= trim($key).'="'.trim($val).'" ';
		}
		
		$strInput .= '>'.htmlentities(utf8_decode(trim($text))).'</label> ';
		
		if($return)
			return $strInput;
		else
			echo $strInput;
	}
}
?> 