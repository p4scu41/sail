<?PHP
	$nacimiento = explode("-",$_POST['edad']);
    $fecha = array("dia" => $nacimiento[0], "mes" => $nacimiento[1], "anio" => $nacimiento[2]);
	$hoy = array("dia" => date('d'), "mes" => date('m'), "anio" => date('Y'));
	
	$edad = $hoy["anio"] - $fecha["anio"];
	
	if ($hoy["mes"] < $fecha["mes"] || $hoy["mes"] == $fecha["mes"] && $hoy["dia"] < $fecha["dia"]) $edad--;
	
	echo $edad;
?>