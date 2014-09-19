<?PHP
function elimina_espacio_post()
{
	foreach( $_POST as $key => $value )
	{
		if(is_array($_POST[$key])) {
			foreach($_POST[$key] as $index => $val)
				$_POST[$key][$index] = ucwords(mb_strtolower(trim($val))); // Elimina espacio y convierte solo la primera letra a mayuscula	
		}
		else
			$_POST[$key] = ucwords(mb_strtolower(trim($value))); // Elimina espacio y convierte solo la primera letra a mayuscula
	}
}

function msj_error($mensaje)
{
	return '<br /><div class="msj_error" align="center"><img src="images/error.gif" align="absmiddle" /><h3 style="display:inline"> '.$mensaje.'</h3></div>';
}

function msj_ok($mensaje)
{
	return '<br /><div class="msj_ok" align="center"><img src="images/ok.gif" align="absmiddle" /><h3 style="display:inline"> '.$mensaje.'</h3></div><br />';
}

function redirect($url,$msj='Guardando datos . . . ',$time=2)
{
    echo '<center><b><h3>'.$msj.'</h3></b><img src="images/barra_animada.gif" /></center>
			<br /><meta http-equiv="refresh" content="'.$time.';url='.$url.'" />';
}

function CalculaEdad($value) {
    if($value == '')
        return '';
    
	$nacimiento = explode("-",$value);
    $fecha = array("dia" => $nacimiento[0], "mes" => $nacimiento[1], "anio" => $nacimiento[2]);
	$hoy = array("dia" => date('d'), "mes" => date('m'), "anio" => date('Y'));
	
	$edad = $hoy["anio"] - $fecha["anio"];
	
	if ($hoy["mes"] < $fecha["mes"] || $hoy["mes"] == $fecha["mes"] && $hoy["dia"] < $fecha["dia"]) $edad--;
	
	return $edad;
}
?>