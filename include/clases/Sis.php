<?php

class Sis {
	
	// VARIABLES DE ENTRADA ******************************************************************************************
	public $idCatEstado;
	public $fechaIni;
	public $fechaFin;	
	public $idCatJurisdiccion;
	public $idCatMunicipio;
	public $idCatUnidad;
	public $ubicacionBD = './DBPlatataformas/';
	

	// VARIABLES ESTATICAS  *******************************************************************************************
	static $noColumnaClues = 0;
	static $noColumnaIndice = 1;
	static $noColumnaCasos = 2;
	static $noColumnaMes = 3;
	static $noColumnaAno = 4;
	// *******************************************************************************************
	static $claveIngCon = "MBL01";
	static $claveReiCon = "MBL02";
	static $claveConTx = "MBL03";
	static $claveSinTx = "MBL04";
	static $claveVigPosTx = "MBL05";
	static $claveBacDiaPos = "LCC30";
	static $claveBacDiaNeg = "LCC31";
	static $claveBacConPos = "LCC33";
	static $claveBacConNeg = "LCC34";
	
	// VARIABLES DE LA CLASE ******************************************************************************************
	public $clues = array();
	public $ingCon = 0;
	public $reiCon = 0;
	public $conTx = 0;
	public $sinTx = 0;
	public $vigPosTx = 0;
	public $bacDiaPos = 0;
	public $bacDiaNeg = 0;
	public $bacConPos = 0;
	public $bacConNeg = 0;
	
	public $error = false;
	public $msgError;

	public function levantarDatos() {
		
		$timTem = strtotime($this->fechaIni);
		$mesIni = date("F", $timTem);
		$anoIni = date("Y", $timTem);
		$timTem = strtotime($this->fechaFin);
		$mesFin = date("F", $timTem);
		$anoFin = date("Y", $timTem);

		$sql = "SELECT u.idCatUnidad " .
			"FROM catUnidad u, catJurisdiccion j, catMunicipio m " .
			"WHERE u.idCatEstado = j.idCatEstado " .
			"AND m.idCatEstado = j.idCatEstado " .
			"AND j.idCatJurisdiccion = m.idCatJurisdiccion " .
			"AND m.idCatMunicipio = u.idCatMunicipio " .
			"AND j.idCatEstado = " . $this->idCatEstado;
		if(!is_null($this->idCatJurisdiccion) && $this->idCatJurisdiccion != 0)	$sql .= "AND j.idCatJurisdiccion = " . $this->idCatJurisdiccion . " ";
		if(!is_null($this->idCatMunicipio) && $this->idCatMunicipio != 0)	$sql .= "AND m.idCatMunicipio = " . $this->idCatMunicipio . " ";
		if(!is_null($this->idCatUnidad) && $this->idCatUnidad != 0)	$this->clues = array($this->idCatUnidad);
		else {
			$sql .= ";";
			$result = ejecutaQueryClases($sql);
			if (is_string($result)) {
				$this->error = true;
				$this->msgError = $result . " SQL:" . $sql;
			} else {
				$arr = array();
				$c = 0;
				while ($registro = devuelveRowAssoc($result)) {
					$arr[$c] = $registro['idCatTipoLesion'];
					$c++;
				}
				$this->clues = $arr;
			}
		}		

		try {
			$noFila = 1;
			if (($handler = fopen($this->ubicacionBD . $this->idCatEstado . ".csv", "r")) !== FALSE) {
				while (($fila = fgetcsv($handler, 1000, ",")) !== FALSE) {
					foreach ($this->clues as $clue) {
						if (($clue == $fila[self::$noColumnaClues]) && ( ($mesIni >= $fila[self::$noColumnaMes]) && ($anoIni >= $fila[self::$noColumnaAno]) ) && ( ($mesFin <= $fila[self::$noColumnaMes]) && ($anoFin <= $fila[self::$noColumnaAno]) )) {
							if ($fila[self::$noColumnaIndice] == $claveIngCon) { $this->ingCon += (int)$fila[self::$noColumnaCasos]; }
							elseif ($fila[self::$noColumnaIndice] == $claveReiCon) { $this->reiCon += (int)$fila[self::$noColumnaCasos]; }
							elseif ($fila[self::$noColumnaIndice] == $claveConTx) { $this->conTx += (int)$fila[self::$noColumnaCasos]; }
							elseif ($fila[self::$noColumnaIndice] == $claveSinTx) { $this->sinTx += (int)$fila[self::$noColumnaCasos]; }
							elseif ($fila[self::$noColumnaIndice] == $claveVigPosTx) { $this->vigPosTx += (int)$fila[self::$noColumnaCasos]; }
							elseif ($fila[self::$noColumnaIndice] == $claveBacDiaPos) { $this->bacDiaPos += (int)$fila[self::$noColumnaCasos]; }
							elseif ($fila[self::$noColumnaIndice] == $claveBacDiaNeg) { $this->bacDiaNeg += (int)$fila[self::$noColumnaCasos]; }
							elseif ($fila[self::$noColumnaIndice] == $claveBacConPos) { $this->bacConPos += (int)$fila[self::$noColumnaCasos]; }
							elseif ($fila[self::$noColumnaIndice] == $claveBacConNeg) { $this->bacConNeg += (int)$fila[self::$noColumnaCasos]; }											
						}
					}
				}
				fclose($handler);
			} else {
				$this->error = true;
				$this->msgError = "No fue posible abrir el archivo " . $this->ubicacionBD . $this->idCatEstado . ".csv ";
			}
		} catch (Exception $e) {
			$this->error = true;
			$this->msgError = "Error al acceder al archivo: " . $e->getMessage();
		}		
	}

	function imprimir(){

		
		echo '<DIV CLASS="datagrid"><TABLE><THEAD><TR><TH COLSPAN="5">Validacion SIS</TH></TR>' .
			'<TR><TH>Ingresos a Control</TH><TH>Reingresos a Control</TH><TH>Casos Registrados en Tratamiento</TH><TH>Casos Registrados sin Tratamiento</TH><TH>Casos Registrados en Vigilancia post Tratamiento</TH></TR></THEAD>' .
			'<TR><TD>' . $this->ingCon .
			'</TD><TD>' . $this->reiCon . 
			'</TD><TD>' . $this->conTx .
			'</TD><TD>' . $this->sinTx .
			'</TD><TD>' . $this->vigPosTx .
			'</TD></TR></TABLE></DIV>';

		echo '<BR><DIV CLASS="datagrid"><TABLE><THEAD><TR><TH COLSPAN="3">Baciloscopias</TH></TR>' .
			'<TR><TH>Tipo</TH><TH>Diagnostico</TH><TH>Control</TH></TR></THEAD>' .
			'<TR><TH>Positivo</TH><TD>' . $this->bacDiaPos . '</TD><TD>' . $this->bacConPos . '</TD></TR>' .
			'<TR><TH>Negativo</TH><TD>' . $this->bacDiaNeg . '</TD><TD>' . $this->bacConNeg . '</TD></TR></TABLE></DIV>';	
	
	}
}

?>
