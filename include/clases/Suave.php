<?php

class Suave {
	
	// VARIABLES DE ENTRADA ******************************************************************************************
	public $semanas = array();
	public $idCatEstado;
	public $idCatJurisdiccion;
	public $ubicacionBD = './DBPlatataformas/';

	// VARIABLES ESTATICAS  *******************************************************************************************
	static $noColumnaJurisdiccion = 1;
	static $noColumnaSemanas = 4;
	static $noColumnaInicioM = 7;
	static $noColumnaInicioF = 19;
	
	// VARIABLES DE LA CLASE ******************************************************************************************
	public $m1 = 0;
	public $m1_4 = 0;
	public $m5_9 = 0;
	public $m10_14 = 0;
	public $m15_19 = 0;
	public $m20_24 = 0;
	public $m25_44 = 0;
	public $m45_49 = 0;
	public $m50_59 = 0;
	public $m60_64 = 0;
	public $m65 = 0;
	public $mIgn = 0;
	public $f1 = 0;	
	public $f1_4 = 0;
	public $f5_9 = 0;
	public $f10_14 = 0;
	public $f15_19 = 0;
	public $f20_24 = 0;
	public $f25_44 = 0;
	public $f45_49 = 0;
	public $f50_59 = 0;
	public $f60_64 = 0;
	public $f65 = 0;
	public $fIgn = 0;

	public $error = false;
	public $msgError;

	public function levantarDatos() {

		try {
			$noFila = 1;
			if (($handler = fopen($this->ubicacionBD . $this->idCatEstado . ".txt", "r")) !== FALSE) {
				while (($fila = fgetcsv($handler, 1000, ";")) !== FALSE) {
					foreach ($this->semanas as $semana) {
						//echo '<BR> Semana:' . (int)$fila[self::$noColumnaSemanas] . ' Semana buscada ' . $semana . ' Jurisdiccion ' . (int)$fila[self::$noColumnaJurisdiccion] . ' Jurisdiccion buscada ' . $this->idCatJurisdiccion;

						if (((int)$fila[self::$noColumnaSemanas] == $semana) && (($this->idCatJurisdiccion == 0) || ((int)$fila[self::$noColumnaJurisdiccion] == $this->idCatJurisdiccion))) {
							$this->m1 += (int)$fila[self::$noColumnaInicioM];
							$this->m1_4 += (int)$fila[self::$noColumnaInicioM + 1];
							$this->m5_9 += (int)$fila[self::$noColumnaInicioM + 2];
							$this->m10_14 += (int)$fila[self::$noColumnaInicioM + 3];
							$this->m15_19 += (int)$fila[self::$noColumnaInicioM + 4];
							$this->m20_24 += (int)$fila[self::$noColumnaInicioM + 5];
							$this->m25_44 += (int)$fila[self::$noColumnaInicioM + 6];
							$this->m45_49 += (int)$fila[self::$noColumnaInicioM + 7];
							$this->m50_59 += (int)$fila[self::$noColumnaInicioM + 8];
							$this->m60_64 += (int)$fila[self::$noColumnaInicioM + 9];
							$this->m65 += (int)$fila[self::$noColumnaInicioM + 10];
							$this->mIgn += (int)$fila[self::$noColumnaInicioM + 11];
							$this->f1 += (int)$fila[self::$noColumnaInicioF];	
							$this->f1_4 += (int)$fila[self::$noColumnaInicioF + 1];
							$this->f5_9 += (int)$fila[self::$noColumnaInicioF + 2];
							$this->f10_14 += (int)$fila[self::$noColumnaInicioF + 3];
							$this->f15_19 += (int)$fila[self::$noColumnaInicioF + 4];
							$this->f20_24 += (int)$fila[self::$noColumnaInicioF + 5];
							$this->f25_44 += (int)$fila[self::$noColumnaInicioF + 6];
							$this->f45_49 += (int)$fila[self::$noColumnaInicioF + 7];
							$this->f50_59 += (int)$fila[self::$noColumnaInicioF + 8];
							$this->f60_64 += (int)$fila[self::$noColumnaInicioF + 9];
							$this->f65 += (int)$fila[self::$noColumnaInicioF + 10];
							$this->fIgn += (int)$fila[self::$noColumnaInicioF + 11];
						}
					}

					/*$maximoCampos = count($fila);
					echo "<p>" . $num . " fields in line " . $noFila . ": <br /></p>\n";
					$noFila++;
					for ($campo = 0; $campo < $maximoCampos; $campo++) {
						echo $fila[$campo] . "<br />\n";
					}*/
				}
				fclose($handler);
			} else {
				$this->error = true;
				$this->msgError = "No fue posible abrir el archivo " . $this->ubicacionBD . $this->idCatJurisdiccion . ".csv ";
			}
		} catch (Exception $e) {
			$this->error = true;
			$this->msgError = "Error al acceder al archivo: " . $e->getMessage();
		}
		
	}

	function imprimir() {
		
		$strSemanas = "";
		foreach ($this->semanas as $semana) {
			$strSemanas .= $semana . " ";
		}

		echo '<DIV CLASS="datagrid"><TABLE>';
		echo '<THEAD><TR><TH>Semanas:' . $strSemanas ;
		echo '</TH><TH COLSPAN="2">< 1 año</TH><TH COLSPAN="2">1-4</TH><TH COLSPAN="2">5-9</TH><TH COLSPAN="2">10-14</TH><TH COLSPAN="2">15-19</TH><TH COLSPAN="2">20-24</TH><TH COLSPAN="2">25-44</TH><TH COLSPAN="2">45-49</TH><TH COLSPAN="2">50-59</TH><TH COLSPAN="2">60-64</TH><TH COLSPAN="2">65 y ></TH><TH COLSPAN="2">Ign</TH><TH COLSPAN="2">total</TH><TH></TH></TR>';
		if ($this->idCatJurisdiccion == 0) 		
			echo '<TR><TH>Estatal</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>Total</TH></TR></THEAD>';
		else
			echo '<TR><TH>Jurisdiccion ' . $this->idCatJurisdiccion . '</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>M</TH><TH>F</TH><TH>Total</TH></TR></THEAD>';

		echo '<TR><TD>TOTAL' .
			'</TD><TD>' . $this->m1 . '</TD><TD>' . $this->f1 . 
			'</TD><TD>' . $this->m1_4 . '</TD><TD>' . $this->f1_4 . 
			'</TD><TD>' . $this->m5_9 . '</TD><TD>' . $this->f5_9 . 
			'</TD><TD>' . $this->m10_14 . '</TD><TD>' . $this->f10_14 . 
			'</TD><TD>' . $this->m15_19 . '</TD><TD>' . $this->f15_19 . 
			'</TD><TD>' . $this->m20_24 . '</TD><TD>' . $this->f20_24 . 
			'</TD><TD>' . $this->m25_44 . '</TD><TD>' . $this->f25_44 . 
			'</TD><TD>' . $this->m45_49 . '</TD><TD>' . $this->f45_49 . 
			'</TD><TD>' . $this->m50_59 . '</TD><TD>' . $this->f50_59 . 
			'</TD><TD>' . $this->m60_64 . '</TD><TD>' . $this->f60_64 . 
			'</TD><TD>' . $this->m65 . '</TD><TD>' . $this->f65 . 
			'</TD><TD>' . $this->mIgn . '</TD><TD>' . $this->fIgn . 
			'</TD><TD>' . ($this->m1 + $this->m1_4 + $this->m5_9 + $this->m10_14 + $this->m15_19 + $this->m20_24 + $this->m25_44 + $this->m45_49 + $this->m50_59 + $this->m60_64 + $this->m65) . '</TD><TD>' . ($this->f1 + $this->f1_4 + $this->f5_9 + $this->f10_14 + $this->f15_19 + $this->f20_24 + $this->f25_44 + $this->f45_49 + $this->f50_59 + $this->f60_64 + $this->f65) . 
			'</TD><TD>' . ($this->m1 + $this->m1_4 + $this->m5_9 + $this->m10_14 + $this->m15_19 + $this->m20_24 + $this->m25_44 + $this->m45_49 + $this->m50_59 + $this->m60_64 + $this->m65 + $this->f1 + $this->f1_4 + $this->f5_9 + $this->f10_14 + $this->f15_19 + $this->f20_24 + $this->f25_44 + $this->f45_49 + $this->f50_59 + $this->f60_64 + $this->f65) .
			'</TR>';
	
	}
}

?>
