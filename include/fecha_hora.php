<?PHP
// Fuente: http://jquerybyexample.blogspot.com/2011/12/validate-date-using-jquery.html
function isDate($txtDate)
{
  $currVal = $txtDate;
  if($currVal == '')
    return false;
  
  //Declare Regex  
  $rxDatePattern = "/^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/"; 
  $dtArray = null;
  
  if (preg_match($rxDatePattern, $currVal, $dtArray) == false)
     return false;
  
  //Checks for dd/mm/yyyy format.
  $dtDay= $dtArray[1];
  $dtMonth = $dtArray[3];
  $dtYear = $dtArray[5];

  if ($dtMonth < 1 || $dtMonth > 12)
      return false;
  else if ($dtDay < 1 || $dtDay> 31)
      return false;
  else if (($dtMonth==4 || $dtMonth==6 || $dtMonth==9 || $dtMonth==11) && $dtDay ==31)
      return false;
  else if ($dtMonth == 2)
  {
     $isleap = ($dtYear % 4 == 0 && ($dtYear % 100 != 0 || $dtYear % 400 == 0));
     if ($dtDay> 29 || ($dtDay ==29 && !$isleap))
          return false;
  }
  return true;
}

/*************************************************/
function formatFecha($fecha, $return_null=0)
{
	$arreglo = explode('-',$fecha);
	
	if(count($arreglo) != 3) 
		$arreglo = explode('/',$fecha);
		
	if(count($arreglo)<3) return "";
	
	$result_fecha = $arreglo[2]."-".$arreglo[1]."-".$arreglo[0];
	
	if($result_fecha == "00-00-0000")
	{
		$result_fecha = "";
		
		if($return_null) 
			$result_fecha = NULL;
	}
	
	return $result_fecha;
}

function formatFechaObj($objFecha, $formato="d-m-Y") {
	if($objFecha instanceof DateTime)
		return $objFecha->format($formato);
	else {
		$arrayFecha = explode('-',$objFecha);
	
		if(count($arrayFecha) != 3) 
			$arrayFecha = explode('/',$objFecha);
			
		if(count($arrayFecha) != 3) 
			return NULL;
		
		$fecha = new DateTime();
		
		// formato d-m-Y
		if(strlen($arrayFecha[2]) == 4)
			$fecha->setDate($arrayFecha[2], $arrayFecha[1], $arrayFecha[0]);
		
		// formato y-m-d
		if(strlen($arrayFecha[0]) == 4)
			$fecha->setDate($arrayFecha[0], $arrayFecha[1], $arrayFecha[2]);
		
		return $fecha->format($formato);
	}
}

/*************************************************/
function convierte_hora($arg) 
{
	$hora = substr($arg,0,2);
	$min = substr($arg,3,2);
	$seg = substr($arg,6);

	$x='am';
	if($hora>=12) 
		$x = 'pm';

	if($hora>12) 
		$hora = $hora-12;
	
	$arg = $hora.":".$min." ".$x;
	
	return $arg;
}

/*************************************************/
function convierte_fecha($arg)
{
	//$arg = new DateTime($arg);
	if(!($arg instanceof DateTime))
		$arg = new DateTime($arg);
	
	$fecha = $arg->format("Y-n-d");
	$dia = $arg->format("w");
	$meses = array(1 => 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	$dias = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sabado');
	
	$f = explode('-',$fecha);
	$result_fecha = $dias[$dia].", ".$f[2]." de ".$meses[$f[1]]." de ".$f[0];

	return $result_fecha;
}

/*************************************************/
function fecha_bdatos($fecha)
{
	$meses = array('ENERO'=>'01','FEBRERO'=>'02','MARZO'=>'03','ABRIL'=>'04','MAYO'=>'05','JUNIO'=>'06','JULIO'=>'07','AGOSTO'=>'08','SEPTIEMBRE'=>'09','OCTUBRE'=>'10','NOVIEMBRE'=>'11','DICIEMBRE'=>'12');
	
	$otraFecha = explode(',',$fecha);
	$otraFecha = explode(' de ',$otraFecha[1]);
	
	if($meses[strtoupper(trim($otraFecha[1]))])
		return trim($otraFecha[2])."-".$meses[strtoupper(trim($otraFecha[1]))]."-".trim($otraFecha[0]);
	else
		return date('Y-n-d');
}

/*************************************************/
function moneda($numero,$deci=2,$cero=0)
{
	if(!$numero || empty($numero))
		$numero = 0;
	else
		$result = number_format($numero,$deci, '.', ',');//$result = "$".number_format($numero,$deci, '.', ',');
	
	if((!$numero || empty($numero)) && $cero==1)
		$result = '0';//$result = '$0';
	
	return $result;
}

/*************************************************/
function cal_dif_fecha($fecha1, $fecha2=0)
{
	$objFecha = new DateTime();
	
	if($fecha2==0) 				// Por defecto la fecha2 es la fecha actual
		$fecha2 = date("Y-m-d");
	
	$f = explode("-",$fecha2);
	$objFecha->setDate($f[0],$f[1],$f[2]);
	$f2 = $objFecha->format("U");	// Convierte la fecha en segundos
	
	$f = explode("-",$fecha1);
	$objFecha->setDate($f[0],$f[1],$f[2]);
	$f1 = $objFecha->format("U");
	
	$result = ceil(((float)$f2 - (float)$f1)/(60*60*24)); // Calcula la direrencia en segundos y la convierte a dias
	
	/* + ; la fecha2 es mayor que fecha1 // La fecha es pasada de la actual
	   - ; La fecha2 es menor que fehca1 // La fecha es futura de la actual 
	   
	    Ejemplo:
		cal_dif_fecha('01-01-2010','03-01-2010'); ==> +730
		cal_dif_fecha('03-01-2010','01-01-2010'); ==> -730*/
			
	return $result;
}

/*echo cal_dif_fecha('01-01-2010','2010-01-02'); 
echo "<br />";
echo cal_dif_fecha('03-01-2010','01-01-2010');
echo "<br />";
echo cal_dif_fecha('01-01-2010','30-01-2010'); 
echo "<br />";
echo cal_dif_fecha('01-01-2010','02-01-2010');
echo "<br />";
echo formatFecha('01-01-2010');
echo "<br />";
echo formatFecha('02-01-2010');*/

function calEdad($fechaNac){
	$dias = cal_dif_fecha($fechaNac);
	
	return intval($dias/365);
}

/*************************************************/
// Devuelve la fecha y hora, incluyedo milisegundos. Ejem: 20110822 14:17:29.531
function get_fecha_hora_full()
{
	$now = (string)microtime();
	$now = explode(' ', $now);
	$mm = explode('.', $now[0]);
	$mm = $mm[1];
	/*$now = $now[1];
	$segundos = $now % 60;
	$segundos = $segundos < 10 ? "$segundos" : $segundos;
	return strval(date("YmdHi",mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))) . "$segundos$mm");*/
	return date('Ymd G:i:s').".".substr($mm, 0, 3);
}
?>