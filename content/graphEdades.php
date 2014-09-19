<script src="include/RGraph/libraries/RGraph.common.core.js" ></script>

    <script src="include/RGraph/libraries/RGraph.common.core.js" ></script>
    <script src="include/RGraph/libraries/RGraph.common.dynamic.js" ></script>
    <script src="include/RGraph/libraries/RGraph.common.tooltips.js" ></script>
    <script src="include/RGraph/libraries/RGraph.common.effects.js" ></script>
        <script src="include/RGraph/libraries/RGraph.pie.js" ></script>
    <script src="include/RGraph/libraries/RGraph.bar.js" ></script>
    <script src="include/RGraph/libraries/RGraph.line.js" ></script>
	<script src="include/RGraph/libraries/RGraph.bipolar.js" ></script>
	<script src="include/RGraph/libraries/RGraph.line.js" ></script>
<!--[if lt IE 9]><script src="excanvas/excanvas.js"></script><![endif]-->

<script type="text/javascript">
    $(document).ready(function() {
        setupCalendario('fecha_inicio');
        setupCalendario('fecha_fin');
        
        $('#jurisdiccion option:first').text('Estatal');
		
		$('#edoReporteEdad').change(function(){ 
			//alert("cambiar");
			actualiza_select( { destino:'jurisdiccion', edo:'edoReporteEdad', tipo:'juris'} );
		});
		
    });
</script>

<?PHP 
	$cat_edades = array();
	$cat_edades[1] = 'Menor de 10';
	$cat_edades[2] = '10 - 15';
	$cat_edades[3] = '16 - 25';
	$cat_edades[4] = '26 - 35';
	$cat_edades[5] = '36 - 45';
	$cat_edades[6] = 'Mayor de 45';
	

echo '<h2 align="center">Reportes Gr&aacute;ficos</h2>';

$objHTML = new HTML();
$objSelects = new Select();

$objHTML->startForm('formReporte', '?mod=edadGen', 'POST');



    $objHTML->startFieldset();
    echo '<div align="center">';
			$objSelects->selectEstado('edoReporteEdad', $paciente->idCatEstado ? $paciente->idCatEstado : $_SESSION[EDO_USR_SESSION]);
            $objSelects->selectJurisdiccion('jurisdiccion', $_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion']);
			$objHTML->inputSelect('Rango de Edad', 'select_rangoEdad', $cat_edades, $_POST['select_rangoEdad'] , array('class'=>'validate[required]'));
            $objHTML->label('Fecha: ');
            $objHTML->inputText('', 'fecha_inicio', $_POST['fecha_inicio'] ? $_POST['fecha_inicio'] : '01-'.date('m-Y'), array('placeholder'=>'Inicio'));
            $objHTML->inputText('', 'fecha_fin', $_POST['fecha_fin'] ? $_POST['fecha_fin'] : date("d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1)).'-'.date('m-Y'), array('placeholder'=>'Fin'));
            $objHTML->inputSubmit('generarReporte', 'Generar');
			$objHTML->inputHidden("activar", "true");
			
    echo '</div>';
    $objHTML->endFieldset();
    
$objHTML->endFormOnly();




			//  fechaDiagnostico    ----- caso nuevo
		
			if($_POST['activar'] == true){
				if(true){
					
					$sacar_anio_inicio = explode("-",$_POST['fecha_inicio']);
					$sacar_anio_fin = explode("-",$_POST['fecha_fin']);
					$resultado = $sacar_anio_fin[2]-$sacar_anio_inicio[2];
					$fecha_consu_inicio = $sacar_anio_inicio[2]."-".$sacar_anio_inicio[1]."-".$sacar_anio_inicio[0];
					$fecha_consu_fin = $sacar_anio_fin[2]."-".$sacar_anio_fin[1]."-".$sacar_anio_fin[0];
					
					if($_POST['jurisdiccion'] != "")
					$jurisdic = " AND m.idCatJurisdiccion =".$_POST['jurisdiccion'];
					
					
					
										
					
					if($_POST['fecha_inicio'] == "" && $_POST['fecha_fin'] == "" && $_POST['edoReporteEdad'] == ""){
						$fecha_consu_inicio = date("Y-m-d");
						$fecha_consu_fin = date("Y-m-d");
						$_POST['edoReporteEdad'] = 7;
					}
					
					
					
					$QueryEdad =  "SELECT p.* FROM pacientes p, catJurisdiccion j, catUnidad u, catMunicipio m	WHERE p.idCatUnidadTratante = u.idCatUnidad AND m.idCatJurisdiccion = j.idCatJurisdiccion AND m.idCatEstado = j.idCatEstado AND u.idcatEstado = ".$_POST['edoReporteEdad']." AND u.idCatMunicipio = m.idCatMunicipio ".$jurisdic." AND m.idCatEstado = u.idcatEstado AND fechaDiagnostico BETWEEN '".$fecha_consu_inicio."' AND '".$fecha_consu_fin."' ORDER BY fechaDiagnostico";
					
					$ejecuta_edad = ejecutaQuery($QueryEdad);
					
					
					$numero_registros_primero = devuelveNumRows( $ejecuta_edad);
					
					
					if($_POST['select_rangoEdad'] == "")
						$_POST['select_rangoEdad'] = 0;
					
									
						
						  
						 switch($_POST['select_rangoEdad'])
							{
								case '0':{
									
									$por_anio = "Por Año";
									if($numero_registros_primero == 0 && $numero_registros_segundo == 0)
										echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
			
										else{
										
										
										if($resultado == 0){										
										$arreglo_fecha_2[0] = $sacar_anio_inicio[2];
										}
										else{
											$t =0; 
											for($x=$sacar_anio_inicio[2];$x<=$sacar_anio_fin[2];$x++){
												$arreglo_fecha_2[$t++] = $x;
												}
											
											}
									$e = 0;		
									$t = 0;
									$r = 0;
									$pacientes_fech = array();
									while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										//echo "<br/>".$resultado_edad['nombre']."---";
									    $edad_2 = CalculaEdad($valor_calcular);
																
										// condicion edad
										//if($edad_2< 10){
											//if($edad_2 > 9 && $edad_2 < 16){

											//if($edad_2 > 15 && $edad_2 < 26){
											
											//if($edad_2 > 25 && $edad_2 < 36){
												
											//if($edad_2 > 35 && $edad_2 < 46){
												
											//if($edad_2 > 45){
											
												if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											//if(true){
											
											
											
										
										
												$fecha_diag = explode("-",formatFechaObj($resultado_edad['fechaDiagnostico']));		
												
													$anio = $fecha_diag[2];		
													
													$pacientes_fech[$anio]++;										
													
													if($resultado_edad['sexo'] == 1){
														$pacientes_hombre++;
														$pacientes_fetch2[$anio]['h']++;
													}else{
														$pacientes_fetch2[$anio]['m']++;
														$pacientes_mujer++;
													}
														
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
											
											//fin condicion edad }
											
										}
											
										
										$pobla_anio_1990 = "3326140";
										$pobla_anio_1991 = "3398566";
										$pobla_anio_1992 = "3471236";
										$pobla_anio_1993 = "3543231";
										$pobla_anio_1994 = "3613850";
										$pobla_anio_1995 = "3683005";
										$pobla_anio_1996 = "3750840";
										$pobla_anio_1997 = "3817152";
										$pobla_anio_1998 = "3882823";
										$pobla_anio_1999 = "3949837";
										$pobla_anio_2000 = "4018049";
										$pobla_anio_2001 = "4085008";
										$pobla_anio_2002 = "4148101";
										$pobla_anio_2003 = "4206345";
										$pobla_anio_2004 = "4260523";
										$pobla_anio_2005 = "4312067";
										$pobla_anio_2006 = "4362413";
										$pobla_anio_2007 = "4411808";
										$pobla_anio_2008 = "4460013";
										$pobla_anio_2009 = "4507177";
										$pobla_anio_2010 = "4903700";
										$pobla_anio_2011 = "4978849";
										$pobla_anio_2012 = "5048184";
										$pobla_anio_2013 = "5116489";
										$pobla_anio_2014 = "5183827";
										$pobla_anio_2015 = "5250306";
										$pobla_anio_2016 = "5315921";
										$pobla_anio_2017 = "5380769";
										$pobla_anio_2018 = "5444910";
										$pobla_anio_2019 = "5508320";
										$pobla_anio_2020 = "5571476";
										$pobla_anio_2021 = "5633555";
										$pobla_anio_2022 = "5693619";
										$pobla_anio_2023 = "5752024";
										$pobla_anio_2024 = "5808596";
										$pobla_anio_2025 = "5863237";
										$pobla_anio_2026 = "5915724";
										$pobla_anio_2027 = "5965872";
										$pobla_anio_2028 = "6013569";
										$pobla_anio_2029 = "6058553";
										$pobla_anio_2030 = "6099290";
									
											

											
											
											
										
										foreach($arreglo_fecha_2 as $fechas_pobla){
											
											
											if($fechas_pobla == 1990 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1990] =  round(($pacientes_fech[1990] /$pobla_anio_1990)*100000,2);
												 }
											if($fechas_pobla == 1991 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1991] =  round(($pacientes_fech[1991] /$pobla_anio_1991)*100000,2);
												 }
											
											if($fechas_pobla == 1992 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1992] =  round(($pacientes_fech[1992] /$pobla_anio_1992)*100000,2);
												 }
											
											if($fechas_pobla == 1993 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1993] =  round(($pacientes_fech[1993] /$pobla_anio_1993)*100000,2);
												 }
											
											if($fechas_pobla == 1994 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1994] =  round(($pacientes_fech[1994] /$pobla_anio_1994)*100000,2);
												 }
											
											if($fechas_pobla == 1995 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1995] =  round(($pacientes_fech[1995] /$pobla_anio_1995)*100000,2);
												 }
											
											if($fechas_pobla == 1996 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1996] =  round(($pacientes_fech[1996] /$pobla_anio_1996)*100000,2);
												 }
											
											if($fechas_pobla == 1997 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1997] =  round(($pacientes_fech[1997] /$pobla_anio_1997)*100000,2);
												 }
											
											if($fechas_pobla == 1998 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1998] =  round(($pacientes_fech[1998] /$pobla_anio_1998)*100000,2);
												 }
											
											if($fechas_pobla == 1999 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1999] =  round(($pacientes_fech[1999] /$pobla_anio_1999)*100000,2);
												 }
											
											if($fechas_pobla == 2000 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2000] =  round(($pacientes_fech[2000] /$pobla_anio_2000)*100000,2);
												 }
											
											if($fechas_pobla == 2001 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2001] =  round(($pacientes_fech[2001] /$pobla_anio_2001)*100000,2);
												 }
											
											if($fechas_pobla == 2002 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2002] =  round(($pacientes_fech[2002] /$pobla_anio_2002)*100000,2);
												 }
											
											if($fechas_pobla == 2003 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2003] =  round(($pacientes_fech[2003] /$pobla_anio_2003)*100000,2);
												 }
											
											if($fechas_pobla == 2004 && $_POST['edoReporteEdad'] == 7){ 
												 $tasa_arreglo[2004] =  round(($pacientes_fech[2004] /$pobla_anio_2004)*100000,2);
												 }
											
											if($fechas_pobla == 2005 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2005] =  round(($pacientes_fech[2005] /$pobla_anio_2005)*100000,2);
												 }
											
											if($fechas_pobla == 2006 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2006] =  round(($pacientes_fech[2006] /$pobla_anio_2006)*100000,2);
												 }
											
											if($fechas_pobla == 2007 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2007] =  round(($pacientes_fech[2007] /$pobla_anio_2007)*100000,2);
												 }
											
											if($fechas_pobla == 2008 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2008] =  round(($pacientes_fech[2008] /$pobla_anio_2008)*100000,2);
												 }
											
											if($fechas_pobla == 2009 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2009] =  round(($pacientes_fech[2009] /$pobla_anio_2009)*100000,2);
												 }

											 if($fechas_pobla == 2010 ){
												 $tasa_arreglo[2010] =  round(($pacientes_fech[2010] /$pobla_anio_2010)*100000,2);
												 }
												 
											if($fechas_pobla == 2011){
												 $tasa_arreglo[2011] =  round(($pacientes_fech[2011] /$pobla_anio_2011)*100000,2);
												 }												
											if($fechas_pobla == 2012){
												 $tasa_arreglo[2012] =  round(($pacientes_fech[2012] /$pobla_anio_2012)*100000,2);
												 }
											if($fechas_pobla == 2013){
												 	$tasa_arreglo[2013] =  round(($pacientes_fech[2013] /$pobla_anio_2013)*100000,2);
												 }
												 
												 
												 if($fechas_pobla == 2014){
												 $tasa_arreglo[2014] =  round(($pacientes_fech[2014] /$pobla_anio_2014)*100000,2);
												
												 }
												 
												if($fechas_pobla == 2015){
												 $tasa_arreglo[2015] =  round(($pacientes_fech[2015] /$pobla_anio_2015)*100000,2);
																							 

												 }
												 if($fechas_pobla == 2016){
												 $tasa_arreglo[2016] =  round(($pacientes_fech[2016] /$pobla_anio_2016)*100000,2);
												
												 }
												 if($fechas_pobla == 2017){
												 $tasa_arreglo[2017] =  round(($pacientes_fech[2017] /$pobla_anio_2017)*100000,2);
												
												 }
												 if($fechas_pobla == 2018){
												 $tasa_arreglo[2018] =  round(($pacientes_fech[2018] /$pobla_anio_2018)*100000,2);
												
												 }
												 if($fechas_pobla == 2019){
												 $tasa_arreglo[2019] =  round(($pacientes_fech[2019] /$pobla_anio_2019)*100000,2);
												
												 }
												 if($fechas_pobla == 2020){
												 $tasa_arreglo[2020] =  round(($pacientes_fech[2020] /$pobla_anio_2020)*100000,2);
												
												 }
												 if($fechas_pobla == 2021){
												 $tasa_arreglo[2021] =  round(($pacientes_fech[2021] /$pobla_anio_2021)*100000,2);
												
												 }
												 if($fechas_pobla == 2022){
												 $tasa_arreglo[2022] =  round(($pacientes_fech[2022] /$pobla_anio_2022)*100000,2);
												
												 }
												 if($fechas_pobla == 2023){
												 $tasa_arreglo[2023] =  round(($pacientes_fech[2023] /$pobla_anio_2023)*100000,2);
												
												 }
												 if($fechas_pobla == 2024){
												 $tasa_arreglo[2024] =  round(($pacientes_fech[2024] /$pobla_anio_2024)*100000,2);
												
												 }
												 if($fechas_pobla == 2025){
												 $tasa_arreglo[2025] =  round(($pacientes_fech[2025] /$pobla_anio_2025)*100000,2);
												
												 }
												 if($fechas_pobla == 2026){
												 $tasa_arreglo[2026] =  round(($pacientes_fech[2026] /$pobla_anio_2026)*100000,2);
												
												 }
												 if($fechas_pobla == 2027){
												 $tasa_arreglo[2027] = round(($pacientes_fech[2027] /$pobla_anio_2027)*100000,2);
												
												 }
												 if($fechas_pobla == 2028){
												 
												 $tasa_arreglo[2028] =  round(($pacientes_fech[2028] /$pobla_anio_2028)*100000,2);
												 
												 }
												 if($fechas_pobla == 2029){
												 $tasa_arreglo[2029] =  round(($pacientes_fech[2029] /$pobla_anio_2029)*100000,2);
												 
												 }
												 if($fechas_pobla == 2030){
												 $tasa_arreglo[2030] = round(($pacientes_fech[2030] /$pobla_anio_2030)*100000,2);
												 
												 }

												 												
											  
											 }
												
												
												foreach($arreglo_fecha_2 as $anio_44){	
													if($tasa_arreglo[$anio_44] == "")
														 $tasa_arreglo[$anio_44] = 0;
													else
														 $tasa_arreglo[$anio_44];
													
													
													$mostrar_tas .= $tasa_arreglo[$anio_44].",";
													$mostrar_etiquetas .= "'".$tasa_arreglo[$anio_44]."',";
											
													$mostrar_anio .= "'".$anio_44."',";														
													}	
													
													
																
											foreach($arreglo_fecha_2 as $anio_45){	
													$mostrar_tas_2 .= "'".$tasa_arreglo_2[$anio_45]."',";	
													$mostrar_anio_2 .= "'".$anio_45."',";														
											}	
											
	//-------------------------------------------------------------------------------------------------------------------------------------------------------																			
										$objHTML->startFieldset('Casos Nuevos por Años');
										echo '<div align="center"><canvas id="cvs" width="600" height="250">[No canvas support]</canvas></div>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs", [';
														
															foreach($arreglo_fecha_2 as $anio){																
																$cuantos_pacientes = intval($pacientes_fech[$anio]);
																$cadena_2 .= $cuantos_pacientes.',';
															}															

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
													
													echo'])
														.Set("gutter.bottom", 40)
														.Set("labels", [';

															foreach($arreglo_fecha_2 as $valor){
																$cadena .= '"'.$valor.'",';
															}

															echo $eliminar = substr($cadena, 0, -1);
															
														
														echo '])
														.Draw();
													
													</script>';
													

										
  
										$objHTML->endFieldset();	
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------


									$objHTML->startFieldset('Incidencia  de casos nuevos de Lepra');
									
									echo "<div align='center'><canvas id='cvs_tasa_1' width='450' height='250'>[No canvas support]</canvas><div>";
									
									echo "<script>
										 var dataset2 = ["; 
										 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
										 echo "];
								
											var line = new RGraph.Line('cvs_tasa_1', [dataset2])
												.Set('curvy', true)
												.Set('curvy.tickmarks', true)
												.Set('curvy.tickmarks.fill', null)
												.Set('curvy.tickmarks.stroke', '#aaa')
												.Set('curvy.tickmarks.stroke.linewidth', 2)
												.Set('curvy.tickmarks.size', 5)
												.Set('linewidth', 3)
												.Set('hmargin', 5)
												.Set('ymax', '0.5')
												.Set('labels', [";
												echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
												echo "])
												.Set('tooltips', [";
												
												echo $tasa_coordenadas_etiquetas =  substr($mostrar_etiquetas, 0, -1);
												
												echo "])
												.Set('tickmarks', 'circle')
											RGraph.Effects.Line.jQuery.Trace(line);
									</script>";
									/*echo "<script>
											
    

											 var bipolar = new RGraph.Bipolar('cvs_tasa_1', [";
											 foreach($arreglo_fecha_2 as $anio_8){																
																$cuantos_pacientes_8 = intval($pacientes_fech[$anio_8]);
																$cadena_8 .= $cuantos_pacientes_8.',';
															}	
															echo  $eliminar_8 = substr($cadena_8, 0, -1);	
											 
											 
											 echo "],[";
											 
											 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
											 
											 
											 echo "])
															.Set('labels', [";
															echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
															echo "])
															.Set('colors', ['blue'])
															.Set('chart.title.left','Casos Nuevos')
															.Set('chart.title.right','Casos por Taza')
															.Draw();
											</script>";
*/
											


									$objHTML->endFieldset();

																		
//-------------------------------------------------------------------------------------------------------------------------------------------------------										
										$objHTML->startFieldset('Distribución por Sexo');
										
										
										$totalPac = $pacientes_hombre + $pacientes_mujer;
										
										$porcH = round(($pacientes_hombre * 100) / $totalPac,2);
										$porcM = round(($pacientes_mujer * 100) / $totalPac,2);
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvsP1" width="400" height="250">[No canvas support]</canvas>';
								/*		echo '<script>';
										echo " var data = [".$porcH.",".$porcM."];

												var pie = new RGraph.Pie('cvsP1', data)
													.Set('labels', ['',''])
													.Set('tooltips.event', 'onmousemove')
													.Set('colors', ['blue','pink'])
													.Set('strokestyle', 'white')
													.Set('linewidth', 3)
													.Set('shadow', true)
													.Set('shadow.offsetx', 2)
													.Set('shadow.offsety', 2)
													.Set('shadow.blur', 3)
													.Set('tooltips', ['Hombres','Mujeres'])
													.Set('exploded', 7)
												
												for (var i=0; i<data.length; ++i) {
													pie.Get('labels')[i] = pie.Get('chart.labels')[i] + ', ' + data[i] + '%';
												}
												
												pie.Draw();
											</script>";*/
											
											
								echo "<script>
											var pie = new RGraph.Pie('cvsP1', [".$porcH.",".$porcM."])
											
												
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', ['Hombres','Mujeres'])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', ['".$porcH."','".$porcM."'])
												.Set('chart.colors', ['blue', 'pink'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
										
										
	//------------------------------------------------------------------------------------------------------------------------------------									
										
												echo '<canvas id="cvs_2" width="500" height="250">[No canvas support]</canvas>';
										echo '<script >
												  var bar = new RGraph.Bar("cvs_2", [';
															
															$max = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio){																
																$mujeres = intval($pacientes_fetch2[$anio]['m']);
																$hombres = intval($pacientes_fetch2[$anio]['h']);
																
																if($mujeres > $max)
																	$max = $mujeres;
																if($hombres > $max)
																	$max = $hombres;
																
																$cadena_2 .= '['.$mujeres.','.$hombres.'],';
															}												
															
															if($max <= 5)
																$max = 5;			

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
											
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor){
																$cadena4 .= '"'.$valor.'",';
															}

															echo $eliminar4 = substr($cadena4, 0, -1);
															}
												
												$grafica_sexo_anio = '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max.')
												.Set("shadow", false)
												.Set("shadow.offsetx", 0)
												.Set("shadow.offsety", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 15)
												.Set("colors", ["Gradient(pink)","Gradient(blue)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
										echo utf8_decode($grafica_sexo_anio);
										
										$objHTML->endFieldset();
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------
									
										//PRUEBAAAAAAAAAAAAAAAAAAAAAAAA
										$pacientes = array();
											$pacientes[1] = 0;
											$pacientes[2] = 0;
											$pacientes[3] = 0;
											$pacientes[4] = 0;
											$pacientes[5] = 0;
											$pacientes[6] = 0;
											$pacientes[7] = 0;
											$pacientes[8] = 0;
											$pacientes[9] = 0;
											$pacientes[10] = 0;
											$pacientes[11] = 0;
											$pacientes[12] = 0;
											$pacientes[13] = 0;

											
										$ejecuta_edad = ejecutaQuery($QueryEdad);									
										while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										
											if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){
											
											
											
												$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));										
												$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
												$edad_2 = CalculaEdad($valor_calcular);
												

											
																					
											if($edad_2 < 15){	
												$pacientes[1]++;}
											if($edad_2 >= 15 && $edad_2 <= 19){
												$pacientes[2]++;}
											if($edad_2 >= 20 && $edad_2 <= 24){
												$pacientes[3]++;}
											if($edad_2 >= 25 && $edad_2 <= 29){
												$pacientes[4] ++;}
											if($edad_2 >= 30 && $edad_2 <= 34){
												$pacientes[5]++;}
											if($edad_2 >= 35 && $edad_2 <= 39){
												$pacientes[6]++;}
											if($edad_2 >= 40 && $edad_2 <= 44){
												$pacientes[7]++;}
											if($edad_2 >= 50 && $edad_2 <= 54){
												$pacientes[8]++;}
											if($edad_2 >= 55 && $edad_2 <= 59){
												$pacientes[9]++;}
											if($edad_2 >= 60 && $edad_2 <= 64){
												$pacientes[10]++;}
											if($edad_2 >= 65 && $edad_2 <= 69){
												$pacientes[11]++;}
											if($edad_2 >= 70 && $edad_2 <= 74){
												$pacientes[12]++;}
											if($edad_2 > 75){
												$pacientes[13]++;}

										}
									}
									
									$suma_total= 0;
									foreach($pacientes as $suma_paci){
											$suma_total = $suma_total + $suma_paci;
										}
									
								
									
									
									echo "<br/>";
									foreach($pacientes as $key_paci => $cantidad){																
																$cuantos_pacientes_4 = intval($cantidad);
																if($cuantos_pacientes_4 != 0){
																$cadena_4 .= $cuantos_pacientes_4.',';
																$cadena_key .= $key_paci.',';
																$porcentaje_separado = round(($cuantos_pacientes_4 * 100) / $suma_total,2);
																$porcentaje .= '"'.$porcentaje_separado.'%",';
																}
															}															
														
														$porce_etiqueta = substr($porcentaje, 0, -1);
													    $eliminar_key = substr($cadena_key, 0, -1);
														$separar = explode(",",$eliminar_key);
														$contar_arreglo = count($separar);
														for($w=0;$w<=$contar_arreglo;$w++){
																	if($separar[$w] == 1 )
																$separar[$w] = "<15"; 
															if($separar[$w] == 2)
																$separar[$w] = "15-19"; 
															if($separar[$w] == 3)
																$separar[$w] = "20-24"; 
															if($separar[$w] == 4)
																$separar[$w] = "25-29"; 
															if($separar[$w] == 5)
																$separar[$w] = "30-34"; 
															if($separar[$w] == 6)
																$separar[$w] = "35-39"; 
															if($separar[$w] == 7)
																$separar[$w] = "40-44"; 
															if($separar[$w] == 8)
																$separar[$w] = "50-54"; 
															if($separar[$w] == 9)
																$separar[$w] = "55-59"; 
															if($separar[$w] == 10)
																$separar[$w] = "60-64"; 
															if($separar[$w] == 11)
																$separar[$w] = "65-69"; 
															if($separar[$w] == 12)
																$separar[$w] = "70-75"; 
															if($separar[$w] == 13)
																$separar[$w] = ">76"; 
															
														
															
															}
															
															
															foreach($separar as $etiquetas){
																$etiquetas_2 .= "'".$etiquetas."',";
																}
														
														 
														
														
									$objHTML->startFieldset('Grupo de Edad más Afectado');
									
									echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td>
									<canvas id="cvs_rango_edad_2" width="330" height="250" !style="border:1px solid #ccc">[No canvas support]</canvas>';
									
							echo "<script>var pie = new RGraph.Pie('cvs_rango_edad_2', ["; 
																					
										 echo $eliminar_4 = substr($cadena_4, 0, -1);
													

											echo"])
												.Set('strokestyle', '#e8e8e8')
												.Set('linewidth', 5)
												.Set('shadow', true)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('shadow.color', '#aaa')
												.Set('exploded', 10)
												.Set('radius', 70)
												.Set('tooltips', [";
												echo $porce_etiqueta;
												echo "])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [";
												echo $etiquetas_3 = substr($etiquetas_2, 0, -1);
												echo "])
												.Set('labels.sticks', true)
												.Set('labels.sticks.length', 15);
												
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td>";
									
													
									
										
											echo '<td><canvas id="cvs_4" width="600" height="250">[No canvas support]</canvas>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs_4", [';
														foreach($pacientes as $cantidad_2){																
																$cuantos_pacientes_5 = intval($cantidad_2);
																$cadena_5 .= $cuantos_pacientes_5.',';
															}															

														echo  $eliminar_5 = substr($cadena_5, 0, -1);
						
													
													echo'])
														.Set("ymax", 5)
														.Set("gutter.bottom", 40)
														.Set("labels", ["< 15","15-19","20-24","25-29","30-34","35-39","40-44","50-54","55-59","60-64","65-69","70-75",">76"])
														.Draw();
													
													</script></td></tr></table>';
										$objHTML->endFieldset();
	
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------									
						//ultimaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa		
									
						 	$QueryLepra = "SELECT p.* ,diag.*, tipo_le.* FROM catJurisdiccion j, pacientes p, catUnidad u, catMunicipio m, diagnostico diag, catClasificacionLepra tipo_le WHERE p.idCatUnidadTratante = u.idCatUnidad AND m.idCatJurisdiccion = j.idCatJurisdiccion AND m.idCatEstado = j.idCatEstado AND u.idcatEstado = ".$_POST['edoReporteEdad']." AND u.idCatMunicipio = m.idCatMunicipio AND m.idCatEstado = u.idcatEstado AND diag.idPaciente = p.idPaciente AND tipo_le.idCatClasificacionLepra = diag.idCatClasificacionLepra  AND fechaDiagnostico BETWEEN'".$fecha_consu_inicio."' AND '".$fecha_consu_fin."' ORDER BY fechaDiagnostico";
									
									
									$ejecuda_lepra = ejecutaQuery($QueryLepra);
									$numero_registros_segundo = devuelveNumRows( $ejecuda_lepra);
									
									while($resultado_tipo_lepra = devuelveRowAssoc($ejecuda_lepra)){
										

										$fecha_nac = explode("-",formatFechaObj($resultado_tipo_lepra['fechaNacimiento']));										
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										$edad_2 = CalculaEdad($valor_calcular);
										$resultado_tipo_lepra['idCatClasificacionLepra'];
										//echo '<br>';
										//if($edad_2 > 25 && $edad_2 < 36){
										if(true){
											
											//if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											if(true){
											
												$fecha_diag_2 = explode("-",formatFechaObj($resultado_tipo_lepra['fechaDiagnostico']));		
												
													$anio_2 = $fecha_diag_2[2];		
													
																									
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 1){
														$pacientes_tipo_lepra_lepromatosa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 2){
														$pacientes_tipo_lepra_dimorfo++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['dimorfo']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 3){
														$pacientes_tipo_lepra_tuberculoide++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['tuberculoide']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 4){
														$pacientes_tipo_lepra_indeterminado++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['indeterminado']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 5){
														$pacientes_tipo_lepra_lepromatosa_difusa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa_difusa']++;
													}	
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
										} // fin if edad
									}		//fin while
										
																				
										$objHTML->startFieldset('Por su Clasificación');
										

										
										$totalLepra = $pacientes_tipo_lepra_lepromatosa + $pacientes_tipo_lepra_dimorfo + $pacientes_tipo_lepra_tuberculoide + $pacientes_tipo_lepra_indeterminado +$pacientes_tipo_lepra_lepromatosa_difusa;
										
										$porclepro = ($pacientes_tipo_lepra_lepromatosa * 100) / $totalLepra;
										$porcdimorfo = ($pacientes_tipo_lepra_dimorfo * 100) / $totalLepra;
										$porctubercu = ($pacientes_tipo_lepra_tuberculoide * 100) / $totalLepra;
										$porcindeter = ($pacientes_tipo_lepra_indeterminado * 100) / $totalLepra;
										$porcdifusa = ($pacientes_tipo_lepra_lepromatosa_difusa * 100) / $totalLepra;
										
										
										$porclepro = number_format($porclepro,2,'.','');
										$porcdimorfo = number_format($porcdimorfo,2,'.','');
										$porctubercu = number_format($porctubercu,2,'.','');
										$porcindeter = number_format($porcindeter,2,'.','');
										$porcdifusa = number_format($porcdifusa,2,'.','');
																				
										$data_x = '';
										$color_x = '';
										$label_x = '';
										$label_num = '';
										
										if($porclepro > 0){
											$data_x .= $porclepro.',';
											$color_x .= "'Gradient(pink:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Lepromatosa Nodular (MB)',";
											$label_num .= "'".$porclepro."%',";
										}
										if($porcdimorfo > 0){
											$data_x .= $porcdimorfo.',';
											$color_x .= "'Gradient(blue:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Dimorfo (MB)',";
											$label_num .= "'".$porcdimorfo."%',";
										}
										if($porctubercu > 0){
											$data_x .= $porctubercu.',';
											$color_x .= "'Gradient(red:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Tuberculoide (PB)',";
											$label_num .= "'".$porctubercu."%',";
										}
										if($porcindeter > 0){
											$data_x .= $porcindeter.',';
											$color_x .= "'Gradient(green:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Indeterminado (PB)',";
											$label_num .= "'".$porcindeter."%',";
										}
										if($porcdifusa > 0){
											$data_x .= $porcdifusa.',';
											$color_x .= "'Gradient(purple:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Lepra Lepromatosa Difusa (MB)',";
											$label_num .= "'".$porcdifusa."%',";
										}
										
										$data_x = substr($data_x, 0, -1);
										$color_x = substr($color_x, 0, -1);
										$label_x = substr($label_x, 0, -1);
										$label_num = substr($label_num, 0, -1);
										
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvspor_lepra" width="325" height="250">[No canvas support]</canvas>';
										
										
										echo "<script>
											var pie = new RGraph.Pie('cvspor_lepra', [".$data_x."])
											
														
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', [".$label_x."])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [".$label_num."])
												//.Set('chart.colors', ['red', 'blue'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
									
											
											
											
											echo '<canvas id="cvs_7" width="600" height="250">[No canvas support]</canvas>';
											echo '<script >
												  var bar = new RGraph.Bar("cvs_7", [';
															
															$max_3 = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio_6){						
																									
																$lepromatosa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa']);
																$dimorfo = intval($pacientes_tipo_lepra[$anio_6]['dimorfo']);
																$tuberculoide = intval($pacientes_tipo_lepra[$anio_6]['tuberculoide']);
																$indeterminado = intval($pacientes_tipo_lepra[$anio_6]['indeterminado']);
																$lepromatosa_difusa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa_difusa']);
																
																if($lepromatosa > $max_3)
																	$max_3 = $lepromatosa;
																if($dimorfo > $max_3)
																	$max_3 = $dimorfo;
																if($tuberculoide > $max_3)
																	$max_3 = $tuberculoide;
																if($indeterminado > $max_3)
																	$max_3 = $indeterminado;
																if($lepromatosa_difusa > $max_3)
																	$max_3 = $lepromatosa_difusa;
																	
																
																$cadena_lepra .= '['.$lepromatosa.','.$dimorfo.','.$tuberculoide.','.$indeterminado.','.$lepromatosa_difusa.'],';
																//$cadena_lepra .= '[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,62]';
															}												
															
															if($max_3 <= 5)
																$max_3 = 5;			

														echo  $parametros_lepra = substr($cadena_lepra, 0, -1);
													//echo '[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4]';
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor_3){
																$cadena_3 .= '"'.$valor_3.'",';
															}

															echo $eliminar_cad = substr($cadena_3, 0, -1);
															}
												
												echo '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max_3.')
											
											
												.Set("shadow", false)
												.Set("shadow.offsetx", 10)
												.Set("shadow.offsety", 0)
												.Set("shadow.blur", 40)
												.Set("hmargin.grouped", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 45)
												.Set("colors", ["Gradient(pink)","Gradient(blue)","Gradient(red)","Gradient(green)","Gradient(purple)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
												
										$objHTML->endFieldset();
										
										
										}
										
									}
									
								break;
								
								case '1': {
																	$por_anio = "Por Año";
									if($numero_registros_primero == 0 && $numero_registros_segundo == 0)
										echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
			
										else{
										
										
										if($resultado == 0){										
										$arreglo_fecha_2[0] = $sacar_anio_inicio[2];
										}
										else{
											$t =0; 
											for($x=$sacar_anio_inicio[2];$x<=$sacar_anio_fin[2];$x++){
												$arreglo_fecha_2[$t++] = $x;
												}
											
											}
									$e = 0;		
									$t = 0;
									$r = 0;
									$pacientes_fech = array();
									while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										//echo "<br/>".$resultado_edad['nombre']."---";
									    $edad_2 = CalculaEdad($valor_calcular);
																
										// condicion edad
										if($edad_2< 10){
											//if($edad_2 > 9 && $edad_2 < 16){

											//if($edad_2 > 15 && $edad_2 < 26){
											
											//if($edad_2 > 25 && $edad_2 < 36){
												
											//if($edad_2 > 35 && $edad_2 < 46){
												
											//if($edad_2 > 45){
											
												if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											//if(true){
											
											
											
										
										
												$fecha_diag = explode("-",formatFechaObj($resultado_edad['fechaDiagnostico']));		
												
													$anio = $fecha_diag[2];		
													
													$pacientes_fech[$anio]++;										
													
													if($resultado_edad['sexo'] == 1){
														$pacientes_hombre++;
														$pacientes_fetch2[$anio]['h']++;
													}else{
														$pacientes_fetch2[$anio]['m']++;
														$pacientes_mujer++;
													}
														
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
											
											//fin condicion edad 
											}
											
										}
											
										
										$pobla_anio_1990 = "3326140";
										$pobla_anio_1991 = "3398566";
										$pobla_anio_1992 = "3471236";
										$pobla_anio_1993 = "3543231";
										$pobla_anio_1994 = "3613850";
										$pobla_anio_1995 = "3683005";
										$pobla_anio_1996 = "3750840";
										$pobla_anio_1997 = "3817152";
										$pobla_anio_1998 = "3882823";
										$pobla_anio_1999 = "3949837";
										$pobla_anio_2000 = "4018049";
										$pobla_anio_2001 = "4085008";
										$pobla_anio_2002 = "4148101";
										$pobla_anio_2003 = "4206345";
										$pobla_anio_2004 = "4260523";
										$pobla_anio_2005 = "4312067";
										$pobla_anio_2006 = "4362413";
										$pobla_anio_2007 = "4411808";
										$pobla_anio_2008 = "4460013";
										$pobla_anio_2009 = "4507177";
										$pobla_anio_2010 = "4903700";
										$pobla_anio_2011 = "4978849";
										$pobla_anio_2012 = "5048184";
										$pobla_anio_2013 = "5116489";
										$pobla_anio_2014 = "5183827";
										$pobla_anio_2015 = "5250306";
										$pobla_anio_2016 = "5315921";
										$pobla_anio_2017 = "5380769";
										$pobla_anio_2018 = "5444910";
										$pobla_anio_2019 = "5508320";
										$pobla_anio_2020 = "5571476";
										$pobla_anio_2021 = "5633555";
										$pobla_anio_2022 = "5693619";
										$pobla_anio_2023 = "5752024";
										$pobla_anio_2024 = "5808596";
										$pobla_anio_2025 = "5863237";
										$pobla_anio_2026 = "5915724";
										$pobla_anio_2027 = "5965872";
										$pobla_anio_2028 = "6013569";
										$pobla_anio_2029 = "6058553";
										$pobla_anio_2030 = "6099290";
									
											

											
											
											
										
										foreach($arreglo_fecha_2 as $fechas_pobla){
											
											
											if($fechas_pobla == 1990 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1990] =  round(($pacientes_fech[1990] /$pobla_anio_1990)*100000,2);
												 }
											if($fechas_pobla == 1991 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1991] =  round(($pacientes_fech[1991] /$pobla_anio_1991)*100000,2);
												 }
											
											if($fechas_pobla == 1992 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1992] =  round(($pacientes_fech[1992] /$pobla_anio_1992)*100000,2);
												 }
											
											if($fechas_pobla == 1993 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1993] =  round(($pacientes_fech[1993] /$pobla_anio_1993)*100000,2);
												 }
											
											if($fechas_pobla == 1994 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1994] =  round(($pacientes_fech[1994] /$pobla_anio_1994)*100000,2);
												 }
											
											if($fechas_pobla == 1995 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1995] =  round(($pacientes_fech[1995] /$pobla_anio_1995)*100000,2);
												 }
											
											if($fechas_pobla == 1996 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1996] =  round(($pacientes_fech[1996] /$pobla_anio_1996)*100000,2);
												 }
											
											if($fechas_pobla == 1997 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1997] =  round(($pacientes_fech[1997] /$pobla_anio_1997)*100000,2);
												 }
											
											if($fechas_pobla == 1998 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1998] =  round(($pacientes_fech[1998] /$pobla_anio_1998)*100000,2);
												 }
											
											if($fechas_pobla == 1999 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1999] =  round(($pacientes_fech[1999] /$pobla_anio_1999)*100000,2);
												 }
											
											if($fechas_pobla == 2000 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2000] =  round(($pacientes_fech[2000] /$pobla_anio_2000)*100000,2);
												 }
											
											if($fechas_pobla == 2001 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2001] =  round(($pacientes_fech[2001] /$pobla_anio_2001)*100000,2);
												 }
											
											if($fechas_pobla == 2002 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2002] =  round(($pacientes_fech[2002] /$pobla_anio_2002)*100000,2);
												 }
											
											if($fechas_pobla == 2003 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2003] =  round(($pacientes_fech[2003] /$pobla_anio_2003)*100000,2);
												 }
											
											if($fechas_pobla == 2004 && $_POST['edoReporteEdad'] == 7){ 
												 $tasa_arreglo[2004] =  round(($pacientes_fech[2004] /$pobla_anio_2004)*100000,2);
												 }
											
											if($fechas_pobla == 2005 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2005] =  round(($pacientes_fech[2005] /$pobla_anio_2005)*100000,2);
												 }
											
											if($fechas_pobla == 2006 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2006] =  round(($pacientes_fech[2006] /$pobla_anio_2006)*100000,2);
												 }
											
											if($fechas_pobla == 2007 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2007] =  round(($pacientes_fech[2007] /$pobla_anio_2007)*100000,2);
												 }
											
											if($fechas_pobla == 2008 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2008] =  round(($pacientes_fech[2008] /$pobla_anio_2008)*100000,2);
												 }
											
											if($fechas_pobla == 2009 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2009] =  round(($pacientes_fech[2009] /$pobla_anio_2009)*100000,2);
												 }

											 if($fechas_pobla == 2010 ){
												 $tasa_arreglo[2010] =  round(($pacientes_fech[2010] /$pobla_anio_2010)*100000,2);
												 }
												 
											if($fechas_pobla == 2011){
												 $tasa_arreglo[2011] =  round(($pacientes_fech[2011] /$pobla_anio_2011)*100000,2);
												 }												
											if($fechas_pobla == 2012){
												 $tasa_arreglo[2012] =  round(($pacientes_fech[2012] /$pobla_anio_2012)*100000,2);
												 }
											if($fechas_pobla == 2013){
												 	$tasa_arreglo[2013] =  round(($pacientes_fech[2013] /$pobla_anio_2013)*100000,2);
												 }
												 
												 
												 if($fechas_pobla == 2014){
												 $tasa_arreglo[2014] =  round(($pacientes_fech[2014] /$pobla_anio_2014)*100000,2);
												
												 }
												 
												if($fechas_pobla == 2015){
												 $tasa_arreglo[2015] =  round(($pacientes_fech[2015] /$pobla_anio_2015)*100000,2);
																							 

												 }
												 if($fechas_pobla == 2016){
												 $tasa_arreglo[2016] =  round(($pacientes_fech[2016] /$pobla_anio_2016)*100000,2);
												
												 }
												 if($fechas_pobla == 2017){
												 $tasa_arreglo[2017] =  round(($pacientes_fech[2017] /$pobla_anio_2017)*100000,2);
												
												 }
												 if($fechas_pobla == 2018){
												 $tasa_arreglo[2018] =  round(($pacientes_fech[2018] /$pobla_anio_2018)*100000,2);
												
												 }
												 if($fechas_pobla == 2019){
												 $tasa_arreglo[2019] =  round(($pacientes_fech[2019] /$pobla_anio_2019)*100000,2);
												
												 }
												 if($fechas_pobla == 2020){
												 $tasa_arreglo[2020] =  round(($pacientes_fech[2020] /$pobla_anio_2020)*100000,2);
												
												 }
												 if($fechas_pobla == 2021){
												 $tasa_arreglo[2021] =  round(($pacientes_fech[2021] /$pobla_anio_2021)*100000,2);
												
												 }
												 if($fechas_pobla == 2022){
												 $tasa_arreglo[2022] =  round(($pacientes_fech[2022] /$pobla_anio_2022)*100000,2);
												
												 }
												 if($fechas_pobla == 2023){
												 $tasa_arreglo[2023] =  round(($pacientes_fech[2023] /$pobla_anio_2023)*100000,2);
												
												 }
												 if($fechas_pobla == 2024){
												 $tasa_arreglo[2024] =  round(($pacientes_fech[2024] /$pobla_anio_2024)*100000,2);
												
												 }
												 if($fechas_pobla == 2025){
												 $tasa_arreglo[2025] =  round(($pacientes_fech[2025] /$pobla_anio_2025)*100000,2);
												
												 }
												 if($fechas_pobla == 2026){
												 $tasa_arreglo[2026] =  round(($pacientes_fech[2026] /$pobla_anio_2026)*100000,2);
												
												 }
												 if($fechas_pobla == 2027){
												 $tasa_arreglo[2027] = round(($pacientes_fech[2027] /$pobla_anio_2027)*100000,2);
												
												 }
												 if($fechas_pobla == 2028){
												 
												 $tasa_arreglo[2028] =  round(($pacientes_fech[2028] /$pobla_anio_2028)*100000,2);
												 
												 }
												 if($fechas_pobla == 2029){
												 $tasa_arreglo[2029] =  round(($pacientes_fech[2029] /$pobla_anio_2029)*100000,2);
												 
												 }
												 if($fechas_pobla == 2030){
												 $tasa_arreglo[2030] = round(($pacientes_fech[2030] /$pobla_anio_2030)*100000,2);
												 
												 }

												 												
											  
											 }
												
												
												foreach($arreglo_fecha_2 as $anio_44){	
													if($tasa_arreglo[$anio_44] == "")
														 $tasa_arreglo[$anio_44] = 0;
													else
														 $tasa_arreglo[$anio_44];
													
													
													$mostrar_tas .= $tasa_arreglo[$anio_44].",";
													$mostrar_etiquetas .= "'".$tasa_arreglo[$anio_44]."',";
											
													$mostrar_anio .= "'".$anio_44."',";														
													}	
													
													
																
											foreach($arreglo_fecha_2 as $anio_45){	
													$mostrar_tas_2 .= "'".$tasa_arreglo_2[$anio_45]."',";	
													$mostrar_anio_2 .= "'".$anio_45."',";														
											}	
											
	//-------------------------------------------------------------------------------------------------------------------------------------------------------																			
										$objHTML->startFieldset('Casos Nuevos por Años');
										echo '<div align="center"><canvas id="cvs" width="600" height="250">[No canvas support]</canvas></div>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs", [';
														
															foreach($arreglo_fecha_2 as $anio){																
																$cuantos_pacientes = intval($pacientes_fech[$anio]);
																$cadena_2 .= $cuantos_pacientes.',';
															}															

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
													
													echo'])
														.Set("gutter.bottom", 40)
														.Set("labels", [';

															foreach($arreglo_fecha_2 as $valor){
																$cadena .= '"'.$valor.'",';
															}

															echo $eliminar = substr($cadena, 0, -1);
															
														
														echo '])
														.Draw();
													
													</script>';
													

										
  
										$objHTML->endFieldset();	
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------


									$objHTML->startFieldset('Incidencia  de casos nuevos de Lepra');
									
									echo "<div align='center'><canvas id='cvs_tasa_1' width='450' height='250'>[No canvas support]</canvas><div>";
									
									echo "<script>
										 var dataset2 = ["; 
										 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
										 echo "];
								
											var line = new RGraph.Line('cvs_tasa_1', [dataset2])
												.Set('curvy', true)
												.Set('curvy.tickmarks', true)
												.Set('curvy.tickmarks.fill', null)
												.Set('curvy.tickmarks.stroke', '#aaa')
												.Set('curvy.tickmarks.stroke.linewidth', 2)
												.Set('curvy.tickmarks.size', 5)
												.Set('linewidth', 3)
												.Set('hmargin', 5)
												.Set('ymax', '0.5')
												.Set('labels', [";
												echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
												echo "])
												.Set('tooltips', [";
												
												echo $tasa_coordenadas_etiquetas =  substr($mostrar_etiquetas, 0, -1);
												
												echo "])
												.Set('tickmarks', 'circle')
											RGraph.Effects.Line.jQuery.Trace(line);
									</script>";
									/*echo "<script>
											
    

											 var bipolar = new RGraph.Bipolar('cvs_tasa_1', [";
											 foreach($arreglo_fecha_2 as $anio_8){																
																$cuantos_pacientes_8 = intval($pacientes_fech[$anio_8]);
																$cadena_8 .= $cuantos_pacientes_8.',';
															}	
															echo  $eliminar_8 = substr($cadena_8, 0, -1);	
											 
											 
											 echo "],[";
											 
											 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
											 
											 
											 echo "])
															.Set('labels', [";
															echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
															echo "])
															.Set('colors', ['blue'])
															.Set('chart.title.left','Casos Nuevos')
															.Set('chart.title.right','Casos por Taza')
															.Draw();
											</script>";
*/
											


									$objHTML->endFieldset();

																		
//-------------------------------------------------------------------------------------------------------------------------------------------------------										
										$objHTML->startFieldset('Distribución por Sexo');
										
										
										$totalPac = $pacientes_hombre + $pacientes_mujer;
										
										$porcH = round(($pacientes_hombre * 100) / $totalPac,2);
										$porcM = round(($pacientes_mujer * 100) / $totalPac,2);
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvsP1" width="400" height="250">[No canvas support]</canvas>';
								/*		echo '<script>';
										echo " var data = [".$porcH.",".$porcM."];

												var pie = new RGraph.Pie('cvsP1', data)
													.Set('labels', ['',''])
													.Set('tooltips.event', 'onmousemove')
													.Set('colors', ['blue','pink'])
													.Set('strokestyle', 'white')
													.Set('linewidth', 3)
													.Set('shadow', true)
													.Set('shadow.offsetx', 2)
													.Set('shadow.offsety', 2)
													.Set('shadow.blur', 3)
													.Set('tooltips', ['Hombres','Mujeres'])
													.Set('exploded', 7)
												
												for (var i=0; i<data.length; ++i) {
													pie.Get('labels')[i] = pie.Get('chart.labels')[i] + ', ' + data[i] + '%';
												}
												
												pie.Draw();
											</script>";*/
											
											
								echo "<script>
											var pie = new RGraph.Pie('cvsP1', [".$porcH.",".$porcM."])
											
												
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', ['Hombres','Mujeres'])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', ['".$porcH."','".$porcM."'])
												.Set('chart.colors', ['blue', 'pink'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
										
										
	//------------------------------------------------------------------------------------------------------------------------------------									
										
												echo '<canvas id="cvs_2" width="500" height="250">[No canvas support]</canvas>';
										echo '<script >
												  var bar = new RGraph.Bar("cvs_2", [';
															
															$max = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio){																
																$mujeres = intval($pacientes_fetch2[$anio]['m']);
																$hombres = intval($pacientes_fetch2[$anio]['h']);
																
																if($mujeres > $max)
																	$max = $mujeres;
																if($hombres > $max)
																	$max = $hombres;
																
																$cadena_2 .= '['.$mujeres.','.$hombres.'],';
															}												
															
															if($max <= 5)
																$max = 5;			

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
											
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor){
																$cadena4 .= '"'.$valor.'",';
															}

															echo $eliminar4 = substr($cadena4, 0, -1);
															}
												
												$grafica_sexo_anio = '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max.')
												.Set("shadow", false)
												.Set("shadow.offsetx", 0)
												.Set("shadow.offsety", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 15)
												.Set("colors", ["Gradient(pink)","Gradient(blue)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
										echo utf8_decode($grafica_sexo_anio);
										
										$objHTML->endFieldset();
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------
									
										//PRUEBAAAAAAAAAAAAAAAAAAAAAAAA
										$pacientes = array();
											$pacientes[1] = 0;
											$pacientes[2] = 0;
											$pacientes[3] = 0;
											$pacientes[4] = 0;
											$pacientes[5] = 0;
											$pacientes[6] = 0;
											$pacientes[7] = 0;
											$pacientes[8] = 0;
											$pacientes[9] = 0;
											$pacientes[10] = 0;
											$pacientes[11] = 0;
											$pacientes[12] = 0;
											$pacientes[13] = 0;

											
										$ejecuta_edad = ejecutaQuery($QueryEdad);									
										while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										
											if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){
											
											
											
												$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));										
												$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
												$edad_2 = CalculaEdad($valor_calcular);
												

											
																					
											if($edad_2 < 15){	
												$pacientes[1]++;}
											if($edad_2 >= 15 && $edad_2 <= 19){
												$pacientes[2]++;}
											if($edad_2 >= 20 && $edad_2 <= 24){
												$pacientes[3]++;}
											if($edad_2 >= 25 && $edad_2 <= 29){
												$pacientes[4] ++;}
											if($edad_2 >= 30 && $edad_2 <= 34){
												$pacientes[5]++;}
											if($edad_2 >= 35 && $edad_2 <= 39){
												$pacientes[6]++;}
											if($edad_2 >= 40 && $edad_2 <= 44){
												$pacientes[7]++;}
											if($edad_2 >= 50 && $edad_2 <= 54){
												$pacientes[8]++;}
											if($edad_2 >= 55 && $edad_2 <= 59){
												$pacientes[9]++;}
											if($edad_2 >= 60 && $edad_2 <= 64){
												$pacientes[10]++;}
											if($edad_2 >= 65 && $edad_2 <= 69){
												$pacientes[11]++;}
											if($edad_2 >= 70 && $edad_2 <= 74){
												$pacientes[12]++;}
											if($edad_2 > 75){
												$pacientes[13]++;}

										}
									}
									
									$suma_total= 0;
									foreach($pacientes as $suma_paci){
											$suma_total = $suma_total + $suma_paci;
										}
									
								
									
									
									echo "<br/>";
									foreach($pacientes as $key_paci => $cantidad){																
																$cuantos_pacientes_4 = intval($cantidad);
																if($cuantos_pacientes_4 != 0){
																$cadena_4 .= $cuantos_pacientes_4.',';
																$cadena_key .= $key_paci.',';
																$porcentaje_separado = round(($cuantos_pacientes_4 * 100) / $suma_total,2);
																$porcentaje .= '"'.$porcentaje_separado.'%",';
																}
															}															
														
														$porce_etiqueta = substr($porcentaje, 0, -1);
													    $eliminar_key = substr($cadena_key, 0, -1);
														$separar = explode(",",$eliminar_key);
														$contar_arreglo = count($separar);
														for($w=0;$w<=$contar_arreglo;$w++){
																	if($separar[$w] == 1 )
																$separar[$w] = "<15"; 
															if($separar[$w] == 2)
																$separar[$w] = "15-19"; 
															if($separar[$w] == 3)
																$separar[$w] = "20-24"; 
															if($separar[$w] == 4)
																$separar[$w] = "25-29"; 
															if($separar[$w] == 5)
																$separar[$w] = "30-34"; 
															if($separar[$w] == 6)
																$separar[$w] = "35-39"; 
															if($separar[$w] == 7)
																$separar[$w] = "40-44"; 
															if($separar[$w] == 8)
																$separar[$w] = "50-54"; 
															if($separar[$w] == 9)
																$separar[$w] = "55-59"; 
															if($separar[$w] == 10)
																$separar[$w] = "60-64"; 
															if($separar[$w] == 11)
																$separar[$w] = "65-69"; 
															if($separar[$w] == 12)
																$separar[$w] = "70-75"; 
															if($separar[$w] == 13)
																$separar[$w] = ">76"; 
															
														
															
															}
															
															
															foreach($separar as $etiquetas){
																$etiquetas_2 .= "'".$etiquetas."',";
																}
														
														 
														
														
									$objHTML->startFieldset('Grupo de Edad más Afectado');
									
									echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td>
									<canvas id="cvs_rango_edad_2" width="330" height="250" !style="border:1px solid #ccc">[No canvas support]</canvas>';
									
							echo "<script>var pie = new RGraph.Pie('cvs_rango_edad_2', ["; 
																					
										 echo $eliminar_4 = substr($cadena_4, 0, -1);
													

											echo"])
												.Set('strokestyle', '#e8e8e8')
												.Set('linewidth', 5)
												.Set('shadow', true)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('shadow.color', '#aaa')
												.Set('exploded', 10)
												.Set('radius', 70)
												.Set('tooltips', [";
												echo $porce_etiqueta;
												echo "])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [";
												echo $etiquetas_3 = substr($etiquetas_2, 0, -1);
												echo "])
												.Set('labels.sticks', true)
												.Set('labels.sticks.length', 15);
												
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td>";
									
													
									
										
											echo '<td><canvas id="cvs_4" width="600" height="250">[No canvas support]</canvas>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs_4", [';
														foreach($pacientes as $cantidad_2){																
																$cuantos_pacientes_5 = intval($cantidad_2);
																$cadena_5 .= $cuantos_pacientes_5.',';
															}															

														echo  $eliminar_5 = substr($cadena_5, 0, -1);
						
													
													echo'])
														.Set("ymax", 5)
														.Set("gutter.bottom", 40)
														.Set("labels", ["< 15","15-19","20-24","25-29","30-34","35-39","40-44","50-54","55-59","60-64","65-69","70-75",">76"])
														.Draw();
													
													</script></td></tr></table>';
										$objHTML->endFieldset();
	
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------									
						//ultimaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa		
									
						 	$QueryLepra = "SELECT p.* ,diag.*, tipo_le.* FROM catJurisdiccion j, pacientes p, catUnidad u, catMunicipio m, diagnostico diag, catClasificacionLepra tipo_le WHERE p.idCatUnidadTratante = u.idCatUnidad AND m.idCatJurisdiccion = j.idCatJurisdiccion AND m.idCatEstado = j.idCatEstado AND u.idcatEstado = ".$_POST['edoReporteEdad']." AND u.idCatMunicipio = m.idCatMunicipio AND m.idCatEstado = u.idcatEstado AND diag.idPaciente = p.idPaciente AND tipo_le.idCatClasificacionLepra = diag.idCatClasificacionLepra  AND fechaDiagnostico BETWEEN'".$fecha_consu_inicio."' AND '".$fecha_consu_fin."' ORDER BY fechaDiagnostico";
									
									
									$ejecuda_lepra = ejecutaQuery($QueryLepra);
									$numero_registros_segundo = devuelveNumRows( $ejecuda_lepra);
									
									while($resultado_tipo_lepra = devuelveRowAssoc($ejecuda_lepra)){
										

										$fecha_nac = explode("-",formatFechaObj($resultado_tipo_lepra['fechaNacimiento']));										
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										$edad_2 = CalculaEdad($valor_calcular);
										$resultado_tipo_lepra['idCatClasificacionLepra'];
										//echo '<br>';
										//if($edad_2 > 25 && $edad_2 < 36){
										if(true){
											
											//if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											if(true){
											
												$fecha_diag_2 = explode("-",formatFechaObj($resultado_tipo_lepra['fechaDiagnostico']));		
												
													$anio_2 = $fecha_diag_2[2];		
													
																									
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 1){
														$pacientes_tipo_lepra_lepromatosa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 2){
														$pacientes_tipo_lepra_dimorfo++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['dimorfo']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 3){
														$pacientes_tipo_lepra_tuberculoide++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['tuberculoide']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 4){
														$pacientes_tipo_lepra_indeterminado++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['indeterminado']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 5){
														$pacientes_tipo_lepra_lepromatosa_difusa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa_difusa']++;
													}	
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
										} // fin if edad
									}		//fin while
										
																				
										$objHTML->startFieldset('Por su Clasificación');
										

										
										$totalLepra = $pacientes_tipo_lepra_lepromatosa + $pacientes_tipo_lepra_dimorfo + $pacientes_tipo_lepra_tuberculoide + $pacientes_tipo_lepra_indeterminado +$pacientes_tipo_lepra_lepromatosa_difusa;
										
										$porclepro = ($pacientes_tipo_lepra_lepromatosa * 100) / $totalLepra;
										$porcdimorfo = ($pacientes_tipo_lepra_dimorfo * 100) / $totalLepra;
										$porctubercu = ($pacientes_tipo_lepra_tuberculoide * 100) / $totalLepra;
										$porcindeter = ($pacientes_tipo_lepra_indeterminado * 100) / $totalLepra;
										$porcdifusa = ($pacientes_tipo_lepra_lepromatosa_difusa * 100) / $totalLepra;
										
										
										$porclepro = number_format($porclepro,2,'.','');
										$porcdimorfo = number_format($porcdimorfo,2,'.','');
										$porctubercu = number_format($porctubercu,2,'.','');
										$porcindeter = number_format($porcindeter,2,'.','');
										$porcdifusa = number_format($porcdifusa,2,'.','');
																				
										$data_x = '';
										$color_x = '';
										$label_x = '';
										$label_num = '';
										
										if($porclepro > 0){
											$data_x .= $porclepro.',';
											$color_x .= "'Gradient(pink:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Lepromatosa Nodular (MB)',";
											$label_num .= "'".$porclepro."%',";
										}
										if($porcdimorfo > 0){
											$data_x .= $porcdimorfo.',';
											$color_x .= "'Gradient(blue:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Dimorfo (MB)',";
											$label_num .= "'".$porcdimorfo."%',";
										}
										if($porctubercu > 0){
											$data_x .= $porctubercu.',';
											$color_x .= "'Gradient(red:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Tuberculoide (PB)',";
											$label_num .= "'".$porctubercu."%',";
										}
										if($porcindeter > 0){
											$data_x .= $porcindeter.',';
											$color_x .= "'Gradient(green:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Indeterminado (PB)',";
											$label_num .= "'".$porcindeter."%',";
										}
										if($porcdifusa > 0){
											$data_x .= $porcdifusa.',';
											$color_x .= "'Gradient(purple:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Lepra Lepromatosa Difusa (MB)',";
											$label_num .= "'".$porcdifusa."%',";
										}
										
										$data_x = substr($data_x, 0, -1);
										$color_x = substr($color_x, 0, -1);
										$label_x = substr($label_x, 0, -1);
										$label_num = substr($label_num, 0, -1);
										
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvspor_lepra" width="325" height="250">[No canvas support]</canvas>';
										
										
										echo "<script>
											var pie = new RGraph.Pie('cvspor_lepra', [".$data_x."])
											
														
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', [".$label_x."])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [".$label_num."])
												//.Set('chart.colors', ['red', 'blue'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
									
											
											
											
											echo '<canvas id="cvs_7" width="600" height="250">[No canvas support]</canvas>';
											echo '<script >
												  var bar = new RGraph.Bar("cvs_7", [';
															
															$max_3 = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio_6){						
																									
																$lepromatosa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa']);
																$dimorfo = intval($pacientes_tipo_lepra[$anio_6]['dimorfo']);
																$tuberculoide = intval($pacientes_tipo_lepra[$anio_6]['tuberculoide']);
																$indeterminado = intval($pacientes_tipo_lepra[$anio_6]['indeterminado']);
																$lepromatosa_difusa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa_difusa']);
																
																if($lepromatosa > $max_3)
																	$max_3 = $lepromatosa;
																if($dimorfo > $max_3)
																	$max_3 = $dimorfo;
																if($tuberculoide > $max_3)
																	$max_3 = $tuberculoide;
																if($indeterminado > $max_3)
																	$max_3 = $indeterminado;
																if($lepromatosa_difusa > $max_3)
																	$max_3 = $lepromatosa_difusa;
																	
																
																$cadena_lepra .= '['.$lepromatosa.','.$dimorfo.','.$tuberculoide.','.$indeterminado.','.$lepromatosa_difusa.'],';
																//$cadena_lepra .= '[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,62]';
															}												
															
															if($max_3 <= 5)
																$max_3 = 5;			

														echo  $parametros_lepra = substr($cadena_lepra, 0, -1);
													//echo '[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4]';
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor_3){
																$cadena_3 .= '"'.$valor_3.'",';
															}

															echo $eliminar_cad = substr($cadena_3, 0, -1);
															}
												
												echo '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max_3.')
											
											
												.Set("shadow", false)
												.Set("shadow.offsetx", 10)
												.Set("shadow.offsety", 0)
												.Set("shadow.blur", 40)
												.Set("hmargin.grouped", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 45)
												.Set("colors", ["Gradient(pink)","Gradient(blue)","Gradient(red)","Gradient(green)","Gradient(purple)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
												
										$objHTML->endFieldset();
										
										
										}
										
										
										
									}
									break;
								case '2': {
									
																	$por_anio = "Por Año";
									if($numero_registros_primero == 0 && $numero_registros_segundo == 0)
										echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
			
										else{
										
										
										if($resultado == 0){										
										$arreglo_fecha_2[0] = $sacar_anio_inicio[2];
										}
										else{
											$t =0; 
											for($x=$sacar_anio_inicio[2];$x<=$sacar_anio_fin[2];$x++){
												$arreglo_fecha_2[$t++] = $x;
												}
											
											}
									$e = 0;		
									$t = 0;
									$r = 0;
									$pacientes_fech = array();
									while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										//echo "<br/>".$resultado_edad['nombre']."---";
									    $edad_2 = CalculaEdad($valor_calcular);
																
										// condicion edad
										//if($edad_2< 10){
											if($edad_2 > 9 && $edad_2 < 16){

											//if($edad_2 > 15 && $edad_2 < 26){
											
											//if($edad_2 > 25 && $edad_2 < 36){
												
											//if($edad_2 > 35 && $edad_2 < 46){
												
											//if($edad_2 > 45){
											
												if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											//if(true){
											
											
											
										
										
												$fecha_diag = explode("-",formatFechaObj($resultado_edad['fechaDiagnostico']));		
												
													$anio = $fecha_diag[2];		
													
													$pacientes_fech[$anio]++;										
													
													if($resultado_edad['sexo'] == 1){
														$pacientes_hombre++;
														$pacientes_fetch2[$anio]['h']++;
													}else{
														$pacientes_fetch2[$anio]['m']++;
														$pacientes_mujer++;
													}
														
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
											
											//fin condicion edad 
											}
											
										}
											
										
										$pobla_anio_1990 = "3326140";
										$pobla_anio_1991 = "3398566";
										$pobla_anio_1992 = "3471236";
										$pobla_anio_1993 = "3543231";
										$pobla_anio_1994 = "3613850";
										$pobla_anio_1995 = "3683005";
										$pobla_anio_1996 = "3750840";
										$pobla_anio_1997 = "3817152";
										$pobla_anio_1998 = "3882823";
										$pobla_anio_1999 = "3949837";
										$pobla_anio_2000 = "4018049";
										$pobla_anio_2001 = "4085008";
										$pobla_anio_2002 = "4148101";
										$pobla_anio_2003 = "4206345";
										$pobla_anio_2004 = "4260523";
										$pobla_anio_2005 = "4312067";
										$pobla_anio_2006 = "4362413";
										$pobla_anio_2007 = "4411808";
										$pobla_anio_2008 = "4460013";
										$pobla_anio_2009 = "4507177";
										$pobla_anio_2010 = "4903700";
										$pobla_anio_2011 = "4978849";
										$pobla_anio_2012 = "5048184";
										$pobla_anio_2013 = "5116489";
										$pobla_anio_2014 = "5183827";
										$pobla_anio_2015 = "5250306";
										$pobla_anio_2016 = "5315921";
										$pobla_anio_2017 = "5380769";
										$pobla_anio_2018 = "5444910";
										$pobla_anio_2019 = "5508320";
										$pobla_anio_2020 = "5571476";
										$pobla_anio_2021 = "5633555";
										$pobla_anio_2022 = "5693619";
										$pobla_anio_2023 = "5752024";
										$pobla_anio_2024 = "5808596";
										$pobla_anio_2025 = "5863237";
										$pobla_anio_2026 = "5915724";
										$pobla_anio_2027 = "5965872";
										$pobla_anio_2028 = "6013569";
										$pobla_anio_2029 = "6058553";
										$pobla_anio_2030 = "6099290";
									
											

											
											
											
										
										foreach($arreglo_fecha_2 as $fechas_pobla){
											
											
											if($fechas_pobla == 1990 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1990] =  round(($pacientes_fech[1990] /$pobla_anio_1990)*100000,2);
												 }
											if($fechas_pobla == 1991 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1991] =  round(($pacientes_fech[1991] /$pobla_anio_1991)*100000,2);
												 }
											
											if($fechas_pobla == 1992 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1992] =  round(($pacientes_fech[1992] /$pobla_anio_1992)*100000,2);
												 }
											
											if($fechas_pobla == 1993 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1993] =  round(($pacientes_fech[1993] /$pobla_anio_1993)*100000,2);
												 }
											
											if($fechas_pobla == 1994 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1994] =  round(($pacientes_fech[1994] /$pobla_anio_1994)*100000,2);
												 }
											
											if($fechas_pobla == 1995 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1995] =  round(($pacientes_fech[1995] /$pobla_anio_1995)*100000,2);
												 }
											
											if($fechas_pobla == 1996 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1996] =  round(($pacientes_fech[1996] /$pobla_anio_1996)*100000,2);
												 }
											
											if($fechas_pobla == 1997 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1997] =  round(($pacientes_fech[1997] /$pobla_anio_1997)*100000,2);
												 }
											
											if($fechas_pobla == 1998 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1998] =  round(($pacientes_fech[1998] /$pobla_anio_1998)*100000,2);
												 }
											
											if($fechas_pobla == 1999 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1999] =  round(($pacientes_fech[1999] /$pobla_anio_1999)*100000,2);
												 }
											
											if($fechas_pobla == 2000 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2000] =  round(($pacientes_fech[2000] /$pobla_anio_2000)*100000,2);
												 }
											
											if($fechas_pobla == 2001 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2001] =  round(($pacientes_fech[2001] /$pobla_anio_2001)*100000,2);
												 }
											
											if($fechas_pobla == 2002 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2002] =  round(($pacientes_fech[2002] /$pobla_anio_2002)*100000,2);
												 }
											
											if($fechas_pobla == 2003 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2003] =  round(($pacientes_fech[2003] /$pobla_anio_2003)*100000,2);
												 }
											
											if($fechas_pobla == 2004 && $_POST['edoReporteEdad'] == 7){ 
												 $tasa_arreglo[2004] =  round(($pacientes_fech[2004] /$pobla_anio_2004)*100000,2);
												 }
											
											if($fechas_pobla == 2005 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2005] =  round(($pacientes_fech[2005] /$pobla_anio_2005)*100000,2);
												 }
											
											if($fechas_pobla == 2006 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2006] =  round(($pacientes_fech[2006] /$pobla_anio_2006)*100000,2);
												 }
											
											if($fechas_pobla == 2007 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2007] =  round(($pacientes_fech[2007] /$pobla_anio_2007)*100000,2);
												 }
											
											if($fechas_pobla == 2008 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2008] =  round(($pacientes_fech[2008] /$pobla_anio_2008)*100000,2);
												 }
											
											if($fechas_pobla == 2009 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2009] =  round(($pacientes_fech[2009] /$pobla_anio_2009)*100000,2);
												 }

											 if($fechas_pobla == 2010 ){
												 $tasa_arreglo[2010] =  round(($pacientes_fech[2010] /$pobla_anio_2010)*100000,2);
												 }
												 
											if($fechas_pobla == 2011){
												 $tasa_arreglo[2011] =  round(($pacientes_fech[2011] /$pobla_anio_2011)*100000,2);
												 }												
											if($fechas_pobla == 2012){
												 $tasa_arreglo[2012] =  round(($pacientes_fech[2012] /$pobla_anio_2012)*100000,2);
												 }
											if($fechas_pobla == 2013){
												 	$tasa_arreglo[2013] =  round(($pacientes_fech[2013] /$pobla_anio_2013)*100000,2);
												 }
												 
												 
												 if($fechas_pobla == 2014){
												 $tasa_arreglo[2014] =  round(($pacientes_fech[2014] /$pobla_anio_2014)*100000,2);
												
												 }
												 
												if($fechas_pobla == 2015){
												 $tasa_arreglo[2015] =  round(($pacientes_fech[2015] /$pobla_anio_2015)*100000,2);
																							 

												 }
												 if($fechas_pobla == 2016){
												 $tasa_arreglo[2016] =  round(($pacientes_fech[2016] /$pobla_anio_2016)*100000,2);
												
												 }
												 if($fechas_pobla == 2017){
												 $tasa_arreglo[2017] =  round(($pacientes_fech[2017] /$pobla_anio_2017)*100000,2);
												
												 }
												 if($fechas_pobla == 2018){
												 $tasa_arreglo[2018] =  round(($pacientes_fech[2018] /$pobla_anio_2018)*100000,2);
												
												 }
												 if($fechas_pobla == 2019){
												 $tasa_arreglo[2019] =  round(($pacientes_fech[2019] /$pobla_anio_2019)*100000,2);
												
												 }
												 if($fechas_pobla == 2020){
												 $tasa_arreglo[2020] =  round(($pacientes_fech[2020] /$pobla_anio_2020)*100000,2);
												
												 }
												 if($fechas_pobla == 2021){
												 $tasa_arreglo[2021] =  round(($pacientes_fech[2021] /$pobla_anio_2021)*100000,2);
												
												 }
												 if($fechas_pobla == 2022){
												 $tasa_arreglo[2022] =  round(($pacientes_fech[2022] /$pobla_anio_2022)*100000,2);
												
												 }
												 if($fechas_pobla == 2023){
												 $tasa_arreglo[2023] =  round(($pacientes_fech[2023] /$pobla_anio_2023)*100000,2);
												
												 }
												 if($fechas_pobla == 2024){
												 $tasa_arreglo[2024] =  round(($pacientes_fech[2024] /$pobla_anio_2024)*100000,2);
												
												 }
												 if($fechas_pobla == 2025){
												 $tasa_arreglo[2025] =  round(($pacientes_fech[2025] /$pobla_anio_2025)*100000,2);
												
												 }
												 if($fechas_pobla == 2026){
												 $tasa_arreglo[2026] =  round(($pacientes_fech[2026] /$pobla_anio_2026)*100000,2);
												
												 }
												 if($fechas_pobla == 2027){
												 $tasa_arreglo[2027] = round(($pacientes_fech[2027] /$pobla_anio_2027)*100000,2);
												
												 }
												 if($fechas_pobla == 2028){
												 
												 $tasa_arreglo[2028] =  round(($pacientes_fech[2028] /$pobla_anio_2028)*100000,2);
												 
												 }
												 if($fechas_pobla == 2029){
												 $tasa_arreglo[2029] =  round(($pacientes_fech[2029] /$pobla_anio_2029)*100000,2);
												 
												 }
												 if($fechas_pobla == 2030){
												 $tasa_arreglo[2030] = round(($pacientes_fech[2030] /$pobla_anio_2030)*100000,2);
												 
												 }

												 												
											  
											 }
												
												
												foreach($arreglo_fecha_2 as $anio_44){	
													if($tasa_arreglo[$anio_44] == "")
														 $tasa_arreglo[$anio_44] = 0;
													else
														 $tasa_arreglo[$anio_44];
													
													
													$mostrar_tas .= $tasa_arreglo[$anio_44].",";
													$mostrar_etiquetas .= "'".$tasa_arreglo[$anio_44]."',";
											
													$mostrar_anio .= "'".$anio_44."',";														
													}	
													
													
																
											foreach($arreglo_fecha_2 as $anio_45){	
													$mostrar_tas_2 .= "'".$tasa_arreglo_2[$anio_45]."',";	
													$mostrar_anio_2 .= "'".$anio_45."',";														
											}	
											
	//-------------------------------------------------------------------------------------------------------------------------------------------------------																			
										$objHTML->startFieldset('Casos Nuevos por Años');
										echo '<div align="center"><canvas id="cvs" width="600" height="250">[No canvas support]</canvas></div>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs", [';
														
															foreach($arreglo_fecha_2 as $anio){																
																$cuantos_pacientes = intval($pacientes_fech[$anio]);
																$cadena_2 .= $cuantos_pacientes.',';
															}															

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
													
													echo'])
														.Set("gutter.bottom", 40)
														.Set("labels", [';

															foreach($arreglo_fecha_2 as $valor){
																$cadena .= '"'.$valor.'",';
															}

															echo $eliminar = substr($cadena, 0, -1);
															
														
														echo '])
														.Draw();
													
													</script>';
													

										
  
										$objHTML->endFieldset();	
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------


									$objHTML->startFieldset('Incidencia  de casos nuevos de Lepra');
									
									echo "<div align='center'><canvas id='cvs_tasa_1' width='450' height='250'>[No canvas support]</canvas><div>";
									
									echo "<script>
										 var dataset2 = ["; 
										 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
										 echo "];
								
											var line = new RGraph.Line('cvs_tasa_1', [dataset2])
												.Set('curvy', true)
												.Set('curvy.tickmarks', true)
												.Set('curvy.tickmarks.fill', null)
												.Set('curvy.tickmarks.stroke', '#aaa')
												.Set('curvy.tickmarks.stroke.linewidth', 2)
												.Set('curvy.tickmarks.size', 5)
												.Set('linewidth', 3)
												.Set('hmargin', 5)
												.Set('ymax', '0.5')
												.Set('labels', [";
												echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
												echo "])
												.Set('tooltips', [";
												
												echo $tasa_coordenadas_etiquetas =  substr($mostrar_etiquetas, 0, -1);
												
												echo "])
												.Set('tickmarks', 'circle')
											RGraph.Effects.Line.jQuery.Trace(line);
									</script>";
									/*echo "<script>
											
    

											 var bipolar = new RGraph.Bipolar('cvs_tasa_1', [";
											 foreach($arreglo_fecha_2 as $anio_8){																
																$cuantos_pacientes_8 = intval($pacientes_fech[$anio_8]);
																$cadena_8 .= $cuantos_pacientes_8.',';
															}	
															echo  $eliminar_8 = substr($cadena_8, 0, -1);	
											 
											 
											 echo "],[";
											 
											 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
											 
											 
											 echo "])
															.Set('labels', [";
															echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
															echo "])
															.Set('colors', ['blue'])
															.Set('chart.title.left','Casos Nuevos')
															.Set('chart.title.right','Casos por Taza')
															.Draw();
											</script>";
*/
											


									$objHTML->endFieldset();

																		
//-------------------------------------------------------------------------------------------------------------------------------------------------------										
										$objHTML->startFieldset('Distribución por Sexo');
										
										
										$totalPac = $pacientes_hombre + $pacientes_mujer;
										
										$porcH = round(($pacientes_hombre * 100) / $totalPac,2);
										$porcM = round(($pacientes_mujer * 100) / $totalPac,2);
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvsP1" width="400" height="250">[No canvas support]</canvas>';
								/*		echo '<script>';
										echo " var data = [".$porcH.",".$porcM."];

												var pie = new RGraph.Pie('cvsP1', data)
													.Set('labels', ['',''])
													.Set('tooltips.event', 'onmousemove')
													.Set('colors', ['blue','pink'])
													.Set('strokestyle', 'white')
													.Set('linewidth', 3)
													.Set('shadow', true)
													.Set('shadow.offsetx', 2)
													.Set('shadow.offsety', 2)
													.Set('shadow.blur', 3)
													.Set('tooltips', ['Hombres','Mujeres'])
													.Set('exploded', 7)
												
												for (var i=0; i<data.length; ++i) {
													pie.Get('labels')[i] = pie.Get('chart.labels')[i] + ', ' + data[i] + '%';
												}
												
												pie.Draw();
											</script>";*/
											
											
								echo "<script>
											var pie = new RGraph.Pie('cvsP1', [".$porcH.",".$porcM."])
											
												
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', ['Hombres','Mujeres'])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', ['".$porcH."','".$porcM."'])
												.Set('chart.colors', ['blue', 'pink'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
										
										
	//------------------------------------------------------------------------------------------------------------------------------------									
										
												echo '<canvas id="cvs_2" width="500" height="250">[No canvas support]</canvas>';
										echo '<script >
												  var bar = new RGraph.Bar("cvs_2", [';
															
															$max = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio){																
																$mujeres = intval($pacientes_fetch2[$anio]['m']);
																$hombres = intval($pacientes_fetch2[$anio]['h']);
																
																if($mujeres > $max)
																	$max = $mujeres;
																if($hombres > $max)
																	$max = $hombres;
																
																$cadena_2 .= '['.$mujeres.','.$hombres.'],';
															}												
															
															if($max <= 5)
																$max = 5;			

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
											
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor){
																$cadena4 .= '"'.$valor.'",';
															}

															echo $eliminar4 = substr($cadena4, 0, -1);
															}
												
												$grafica_sexo_anio = '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max.')
												.Set("shadow", false)
												.Set("shadow.offsetx", 0)
												.Set("shadow.offsety", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 15)
												.Set("colors", ["Gradient(pink)","Gradient(blue)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
										echo utf8_decode($grafica_sexo_anio);
										
										$objHTML->endFieldset();
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------
									
										//PRUEBAAAAAAAAAAAAAAAAAAAAAAAA
										$pacientes = array();
											$pacientes[1] = 0;
											$pacientes[2] = 0;
											$pacientes[3] = 0;
											$pacientes[4] = 0;
											$pacientes[5] = 0;
											$pacientes[6] = 0;
											$pacientes[7] = 0;
											$pacientes[8] = 0;
											$pacientes[9] = 0;
											$pacientes[10] = 0;
											$pacientes[11] = 0;
											$pacientes[12] = 0;
											$pacientes[13] = 0;

											
										$ejecuta_edad = ejecutaQuery($QueryEdad);									
										while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										
											if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){
											
											
											
												$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));										
												$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
												$edad_2 = CalculaEdad($valor_calcular);
												

											
																					
											if($edad_2 < 15){	
												$pacientes[1]++;}
											if($edad_2 >= 15 && $edad_2 <= 19){
												$pacientes[2]++;}
											if($edad_2 >= 20 && $edad_2 <= 24){
												$pacientes[3]++;}
											if($edad_2 >= 25 && $edad_2 <= 29){
												$pacientes[4] ++;}
											if($edad_2 >= 30 && $edad_2 <= 34){
												$pacientes[5]++;}
											if($edad_2 >= 35 && $edad_2 <= 39){
												$pacientes[6]++;}
											if($edad_2 >= 40 && $edad_2 <= 44){
												$pacientes[7]++;}
											if($edad_2 >= 50 && $edad_2 <= 54){
												$pacientes[8]++;}
											if($edad_2 >= 55 && $edad_2 <= 59){
												$pacientes[9]++;}
											if($edad_2 >= 60 && $edad_2 <= 64){
												$pacientes[10]++;}
											if($edad_2 >= 65 && $edad_2 <= 69){
												$pacientes[11]++;}
											if($edad_2 >= 70 && $edad_2 <= 74){
												$pacientes[12]++;}
											if($edad_2 > 75){
												$pacientes[13]++;}

										}
									}
									
									$suma_total= 0;
									foreach($pacientes as $suma_paci){
											$suma_total = $suma_total + $suma_paci;
										}
									
								
									
									
									echo "<br/>";
									foreach($pacientes as $key_paci => $cantidad){																
																$cuantos_pacientes_4 = intval($cantidad);
																if($cuantos_pacientes_4 != 0){
																$cadena_4 .= $cuantos_pacientes_4.',';
																$cadena_key .= $key_paci.',';
																$porcentaje_separado = round(($cuantos_pacientes_4 * 100) / $suma_total,2);
																$porcentaje .= '"'.$porcentaje_separado.'%",';
																}
															}															
														
														$porce_etiqueta = substr($porcentaje, 0, -1);
													    $eliminar_key = substr($cadena_key, 0, -1);
														$separar = explode(",",$eliminar_key);
														$contar_arreglo = count($separar);
														for($w=0;$w<=$contar_arreglo;$w++){
																	if($separar[$w] == 1 )
																$separar[$w] = "<15"; 
															if($separar[$w] == 2)
																$separar[$w] = "15-19"; 
															if($separar[$w] == 3)
																$separar[$w] = "20-24"; 
															if($separar[$w] == 4)
																$separar[$w] = "25-29"; 
															if($separar[$w] == 5)
																$separar[$w] = "30-34"; 
															if($separar[$w] == 6)
																$separar[$w] = "35-39"; 
															if($separar[$w] == 7)
																$separar[$w] = "40-44"; 
															if($separar[$w] == 8)
																$separar[$w] = "50-54"; 
															if($separar[$w] == 9)
																$separar[$w] = "55-59"; 
															if($separar[$w] == 10)
																$separar[$w] = "60-64"; 
															if($separar[$w] == 11)
																$separar[$w] = "65-69"; 
															if($separar[$w] == 12)
																$separar[$w] = "70-75"; 
															if($separar[$w] == 13)
																$separar[$w] = ">76"; 
															
														
															
															}
															
															
															foreach($separar as $etiquetas){
																$etiquetas_2 .= "'".$etiquetas."',";
																}
														
														 
														
														
									$objHTML->startFieldset('Grupo de Edad más Afectado');
									
									echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td>
									<canvas id="cvs_rango_edad_2" width="330" height="250" !style="border:1px solid #ccc">[No canvas support]</canvas>';
									
							echo "<script>var pie = new RGraph.Pie('cvs_rango_edad_2', ["; 
																					
										 echo $eliminar_4 = substr($cadena_4, 0, -1);
													

											echo"])
												.Set('strokestyle', '#e8e8e8')
												.Set('linewidth', 5)
												.Set('shadow', true)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('shadow.color', '#aaa')
												.Set('exploded', 10)
												.Set('radius', 70)
												.Set('tooltips', [";
												echo $porce_etiqueta;
												echo "])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [";
												echo $etiquetas_3 = substr($etiquetas_2, 0, -1);
												echo "])
												.Set('labels.sticks', true)
												.Set('labels.sticks.length', 15);
												
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td>";
									
													
									
										
											echo '<td><canvas id="cvs_4" width="600" height="250">[No canvas support]</canvas>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs_4", [';
														foreach($pacientes as $cantidad_2){																
																$cuantos_pacientes_5 = intval($cantidad_2);
																$cadena_5 .= $cuantos_pacientes_5.',';
															}															

														echo  $eliminar_5 = substr($cadena_5, 0, -1);
						
													
													echo'])
														.Set("ymax", 5)
														.Set("gutter.bottom", 40)
														.Set("labels", ["< 15","15-19","20-24","25-29","30-34","35-39","40-44","50-54","55-59","60-64","65-69","70-75",">76"])
														.Draw();
													
													</script></td></tr></table>';
										$objHTML->endFieldset();
	
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------									
						//ultimaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa		
									
						 	$QueryLepra = "SELECT p.* ,diag.*, tipo_le.* FROM catJurisdiccion j, pacientes p, catUnidad u, catMunicipio m, diagnostico diag, catClasificacionLepra tipo_le WHERE p.idCatUnidadTratante = u.idCatUnidad AND m.idCatJurisdiccion = j.idCatJurisdiccion AND m.idCatEstado = j.idCatEstado AND u.idcatEstado = ".$_POST['edoReporteEdad']." AND u.idCatMunicipio = m.idCatMunicipio AND m.idCatEstado = u.idcatEstado AND diag.idPaciente = p.idPaciente AND tipo_le.idCatClasificacionLepra = diag.idCatClasificacionLepra  AND fechaDiagnostico BETWEEN'".$fecha_consu_inicio."' AND '".$fecha_consu_fin."' ORDER BY fechaDiagnostico";
									
									
									$ejecuda_lepra = ejecutaQuery($QueryLepra);
									$numero_registros_segundo = devuelveNumRows( $ejecuda_lepra);
									
									while($resultado_tipo_lepra = devuelveRowAssoc($ejecuda_lepra)){
										

										$fecha_nac = explode("-",formatFechaObj($resultado_tipo_lepra['fechaNacimiento']));										
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										$edad_2 = CalculaEdad($valor_calcular);
										$resultado_tipo_lepra['idCatClasificacionLepra'];
										//echo '<br>';
										//if($edad_2 > 25 && $edad_2 < 36){
										if(true){
											
											//if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											if(true){
											
												$fecha_diag_2 = explode("-",formatFechaObj($resultado_tipo_lepra['fechaDiagnostico']));		
												
													$anio_2 = $fecha_diag_2[2];		
													
																									
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 1){
														$pacientes_tipo_lepra_lepromatosa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 2){
														$pacientes_tipo_lepra_dimorfo++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['dimorfo']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 3){
														$pacientes_tipo_lepra_tuberculoide++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['tuberculoide']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 4){
														$pacientes_tipo_lepra_indeterminado++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['indeterminado']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 5){
														$pacientes_tipo_lepra_lepromatosa_difusa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa_difusa']++;
													}	
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
										} // fin if edad
									}		//fin while
										
																				
										$objHTML->startFieldset('Por su Clasificación');
										

										
										$totalLepra = $pacientes_tipo_lepra_lepromatosa + $pacientes_tipo_lepra_dimorfo + $pacientes_tipo_lepra_tuberculoide + $pacientes_tipo_lepra_indeterminado +$pacientes_tipo_lepra_lepromatosa_difusa;
										
										$porclepro = ($pacientes_tipo_lepra_lepromatosa * 100) / $totalLepra;
										$porcdimorfo = ($pacientes_tipo_lepra_dimorfo * 100) / $totalLepra;
										$porctubercu = ($pacientes_tipo_lepra_tuberculoide * 100) / $totalLepra;
										$porcindeter = ($pacientes_tipo_lepra_indeterminado * 100) / $totalLepra;
										$porcdifusa = ($pacientes_tipo_lepra_lepromatosa_difusa * 100) / $totalLepra;
										
										
										$porclepro = number_format($porclepro,2,'.','');
										$porcdimorfo = number_format($porcdimorfo,2,'.','');
										$porctubercu = number_format($porctubercu,2,'.','');
										$porcindeter = number_format($porcindeter,2,'.','');
										$porcdifusa = number_format($porcdifusa,2,'.','');
																				
										$data_x = '';
										$color_x = '';
										$label_x = '';
										$label_num = '';
										
										if($porclepro > 0){
											$data_x .= $porclepro.',';
											$color_x .= "'Gradient(pink:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Lepromatosa Nodular (MB)',";
											$label_num .= "'".$porclepro."%',";
										}
										if($porcdimorfo > 0){
											$data_x .= $porcdimorfo.',';
											$color_x .= "'Gradient(blue:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Dimorfo (MB)',";
											$label_num .= "'".$porcdimorfo."%',";
										}
										if($porctubercu > 0){
											$data_x .= $porctubercu.',';
											$color_x .= "'Gradient(red:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Tuberculoide (PB)',";
											$label_num .= "'".$porctubercu."%',";
										}
										if($porcindeter > 0){
											$data_x .= $porcindeter.',';
											$color_x .= "'Gradient(green:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Indeterminado (PB)',";
											$label_num .= "'".$porcindeter."%',";
										}
										if($porcdifusa > 0){
											$data_x .= $porcdifusa.',';
											$color_x .= "'Gradient(purple:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Lepra Lepromatosa Difusa (MB)',";
											$label_num .= "'".$porcdifusa."%',";
										}
										
										$data_x = substr($data_x, 0, -1);
										$color_x = substr($color_x, 0, -1);
										$label_x = substr($label_x, 0, -1);
										$label_num = substr($label_num, 0, -1);
										
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvspor_lepra" width="325" height="250">[No canvas support]</canvas>';
										
										
										echo "<script>
											var pie = new RGraph.Pie('cvspor_lepra', [".$data_x."])
											
														
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', [".$label_x."])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [".$label_num."])
												//.Set('chart.colors', ['red', 'blue'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
									
											
											
											
											echo '<canvas id="cvs_7" width="600" height="250">[No canvas support]</canvas>';
											echo '<script >
												  var bar = new RGraph.Bar("cvs_7", [';
															
															$max_3 = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio_6){						
																									
																$lepromatosa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa']);
																$dimorfo = intval($pacientes_tipo_lepra[$anio_6]['dimorfo']);
																$tuberculoide = intval($pacientes_tipo_lepra[$anio_6]['tuberculoide']);
																$indeterminado = intval($pacientes_tipo_lepra[$anio_6]['indeterminado']);
																$lepromatosa_difusa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa_difusa']);
																
																if($lepromatosa > $max_3)
																	$max_3 = $lepromatosa;
																if($dimorfo > $max_3)
																	$max_3 = $dimorfo;
																if($tuberculoide > $max_3)
																	$max_3 = $tuberculoide;
																if($indeterminado > $max_3)
																	$max_3 = $indeterminado;
																if($lepromatosa_difusa > $max_3)
																	$max_3 = $lepromatosa_difusa;
																	
																
																$cadena_lepra .= '['.$lepromatosa.','.$dimorfo.','.$tuberculoide.','.$indeterminado.','.$lepromatosa_difusa.'],';
																//$cadena_lepra .= '[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,62]';
															}												
															
															if($max_3 <= 5)
																$max_3 = 5;			

														echo  $parametros_lepra = substr($cadena_lepra, 0, -1);
													//echo '[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4]';
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor_3){
																$cadena_3 .= '"'.$valor_3.'",';
															}

															echo $eliminar_cad = substr($cadena_3, 0, -1);
															}
												
												echo '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max_3.')
											
											
												.Set("shadow", false)
												.Set("shadow.offsetx", 10)
												.Set("shadow.offsety", 0)
												.Set("shadow.blur", 40)
												.Set("hmargin.grouped", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 45)
												.Set("colors", ["Gradient(pink)","Gradient(blue)","Gradient(red)","Gradient(green)","Gradient(purple)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
												
										$objHTML->endFieldset();
										
										
										}
										
										
									}
									break;
								case '3': {
										
																	$por_anio = "Por Año";
									if($numero_registros_primero == 0 && $numero_registros_segundo == 0)
										echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
			
										else{
										
										
										if($resultado == 0){										
										$arreglo_fecha_2[0] = $sacar_anio_inicio[2];
										}
										else{
											$t =0; 
											for($x=$sacar_anio_inicio[2];$x<=$sacar_anio_fin[2];$x++){
												$arreglo_fecha_2[$t++] = $x;
												}
											
											}
									$e = 0;		
									$t = 0;
									$r = 0;
									$pacientes_fech = array();
									while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										//echo "<br/>".$resultado_edad['nombre']."---";
									    $edad_2 = CalculaEdad($valor_calcular);
																
										// condicion edad
										//if($edad_2< 10){
											//if($edad_2 > 9 && $edad_2 < 16){

											if($edad_2 > 15 && $edad_2 < 26){
											
											//if($edad_2 > 25 && $edad_2 < 36){
												
											//if($edad_2 > 35 && $edad_2 < 46){
												
											//if($edad_2 > 45){
											
												if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											//if(true){
											
											
											
										
										
												$fecha_diag = explode("-",formatFechaObj($resultado_edad['fechaDiagnostico']));		
												
													$anio = $fecha_diag[2];		
													
													$pacientes_fech[$anio]++;										
													
													if($resultado_edad['sexo'] == 1){
														$pacientes_hombre++;
														$pacientes_fetch2[$anio]['h']++;
													}else{
														$pacientes_fetch2[$anio]['m']++;
														$pacientes_mujer++;
													}
														
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
											
											//fin condicion edad 
											}
											
										}
											
										
										$pobla_anio_1990 = "3326140";
										$pobla_anio_1991 = "3398566";
										$pobla_anio_1992 = "3471236";
										$pobla_anio_1993 = "3543231";
										$pobla_anio_1994 = "3613850";
										$pobla_anio_1995 = "3683005";
										$pobla_anio_1996 = "3750840";
										$pobla_anio_1997 = "3817152";
										$pobla_anio_1998 = "3882823";
										$pobla_anio_1999 = "3949837";
										$pobla_anio_2000 = "4018049";
										$pobla_anio_2001 = "4085008";
										$pobla_anio_2002 = "4148101";
										$pobla_anio_2003 = "4206345";
										$pobla_anio_2004 = "4260523";
										$pobla_anio_2005 = "4312067";
										$pobla_anio_2006 = "4362413";
										$pobla_anio_2007 = "4411808";
										$pobla_anio_2008 = "4460013";
										$pobla_anio_2009 = "4507177";
										$pobla_anio_2010 = "4903700";
										$pobla_anio_2011 = "4978849";
										$pobla_anio_2012 = "5048184";
										$pobla_anio_2013 = "5116489";
										$pobla_anio_2014 = "5183827";
										$pobla_anio_2015 = "5250306";
										$pobla_anio_2016 = "5315921";
										$pobla_anio_2017 = "5380769";
										$pobla_anio_2018 = "5444910";
										$pobla_anio_2019 = "5508320";
										$pobla_anio_2020 = "5571476";
										$pobla_anio_2021 = "5633555";
										$pobla_anio_2022 = "5693619";
										$pobla_anio_2023 = "5752024";
										$pobla_anio_2024 = "5808596";
										$pobla_anio_2025 = "5863237";
										$pobla_anio_2026 = "5915724";
										$pobla_anio_2027 = "5965872";
										$pobla_anio_2028 = "6013569";
										$pobla_anio_2029 = "6058553";
										$pobla_anio_2030 = "6099290";
									
											

											
											
											
										
										foreach($arreglo_fecha_2 as $fechas_pobla){
											
											
											if($fechas_pobla == 1990 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1990] =  round(($pacientes_fech[1990] /$pobla_anio_1990)*100000,2);
												 }
											if($fechas_pobla == 1991 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1991] =  round(($pacientes_fech[1991] /$pobla_anio_1991)*100000,2);
												 }
											
											if($fechas_pobla == 1992 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1992] =  round(($pacientes_fech[1992] /$pobla_anio_1992)*100000,2);
												 }
											
											if($fechas_pobla == 1993 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1993] =  round(($pacientes_fech[1993] /$pobla_anio_1993)*100000,2);
												 }
											
											if($fechas_pobla == 1994 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1994] =  round(($pacientes_fech[1994] /$pobla_anio_1994)*100000,2);
												 }
											
											if($fechas_pobla == 1995 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1995] =  round(($pacientes_fech[1995] /$pobla_anio_1995)*100000,2);
												 }
											
											if($fechas_pobla == 1996 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1996] =  round(($pacientes_fech[1996] /$pobla_anio_1996)*100000,2);
												 }
											
											if($fechas_pobla == 1997 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1997] =  round(($pacientes_fech[1997] /$pobla_anio_1997)*100000,2);
												 }
											
											if($fechas_pobla == 1998 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1998] =  round(($pacientes_fech[1998] /$pobla_anio_1998)*100000,2);
												 }
											
											if($fechas_pobla == 1999 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1999] =  round(($pacientes_fech[1999] /$pobla_anio_1999)*100000,2);
												 }
											
											if($fechas_pobla == 2000 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2000] =  round(($pacientes_fech[2000] /$pobla_anio_2000)*100000,2);
												 }
											
											if($fechas_pobla == 2001 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2001] =  round(($pacientes_fech[2001] /$pobla_anio_2001)*100000,2);
												 }
											
											if($fechas_pobla == 2002 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2002] =  round(($pacientes_fech[2002] /$pobla_anio_2002)*100000,2);
												 }
											
											if($fechas_pobla == 2003 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2003] =  round(($pacientes_fech[2003] /$pobla_anio_2003)*100000,2);
												 }
											
											if($fechas_pobla == 2004 && $_POST['edoReporteEdad'] == 7){ 
												 $tasa_arreglo[2004] =  round(($pacientes_fech[2004] /$pobla_anio_2004)*100000,2);
												 }
											
											if($fechas_pobla == 2005 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2005] =  round(($pacientes_fech[2005] /$pobla_anio_2005)*100000,2);
												 }
											
											if($fechas_pobla == 2006 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2006] =  round(($pacientes_fech[2006] /$pobla_anio_2006)*100000,2);
												 }
											
											if($fechas_pobla == 2007 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2007] =  round(($pacientes_fech[2007] /$pobla_anio_2007)*100000,2);
												 }
											
											if($fechas_pobla == 2008 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2008] =  round(($pacientes_fech[2008] /$pobla_anio_2008)*100000,2);
												 }
											
											if($fechas_pobla == 2009 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2009] =  round(($pacientes_fech[2009] /$pobla_anio_2009)*100000,2);
												 }

											 if($fechas_pobla == 2010 ){
												 $tasa_arreglo[2010] =  round(($pacientes_fech[2010] /$pobla_anio_2010)*100000,2);
												 }
												 
											if($fechas_pobla == 2011){
												 $tasa_arreglo[2011] =  round(($pacientes_fech[2011] /$pobla_anio_2011)*100000,2);
												 }												
											if($fechas_pobla == 2012){
												 $tasa_arreglo[2012] =  round(($pacientes_fech[2012] /$pobla_anio_2012)*100000,2);
												 }
											if($fechas_pobla == 2013){
												 	$tasa_arreglo[2013] =  round(($pacientes_fech[2013] /$pobla_anio_2013)*100000,2);
												 }
												 
												 
												 if($fechas_pobla == 2014){
												 $tasa_arreglo[2014] =  round(($pacientes_fech[2014] /$pobla_anio_2014)*100000,2);
												
												 }
												 
												if($fechas_pobla == 2015){
												 $tasa_arreglo[2015] =  round(($pacientes_fech[2015] /$pobla_anio_2015)*100000,2);
																							 

												 }
												 if($fechas_pobla == 2016){
												 $tasa_arreglo[2016] =  round(($pacientes_fech[2016] /$pobla_anio_2016)*100000,2);
												
												 }
												 if($fechas_pobla == 2017){
												 $tasa_arreglo[2017] =  round(($pacientes_fech[2017] /$pobla_anio_2017)*100000,2);
												
												 }
												 if($fechas_pobla == 2018){
												 $tasa_arreglo[2018] =  round(($pacientes_fech[2018] /$pobla_anio_2018)*100000,2);
												
												 }
												 if($fechas_pobla == 2019){
												 $tasa_arreglo[2019] =  round(($pacientes_fech[2019] /$pobla_anio_2019)*100000,2);
												
												 }
												 if($fechas_pobla == 2020){
												 $tasa_arreglo[2020] =  round(($pacientes_fech[2020] /$pobla_anio_2020)*100000,2);
												
												 }
												 if($fechas_pobla == 2021){
												 $tasa_arreglo[2021] =  round(($pacientes_fech[2021] /$pobla_anio_2021)*100000,2);
												
												 }
												 if($fechas_pobla == 2022){
												 $tasa_arreglo[2022] =  round(($pacientes_fech[2022] /$pobla_anio_2022)*100000,2);
												
												 }
												 if($fechas_pobla == 2023){
												 $tasa_arreglo[2023] =  round(($pacientes_fech[2023] /$pobla_anio_2023)*100000,2);
												
												 }
												 if($fechas_pobla == 2024){
												 $tasa_arreglo[2024] =  round(($pacientes_fech[2024] /$pobla_anio_2024)*100000,2);
												
												 }
												 if($fechas_pobla == 2025){
												 $tasa_arreglo[2025] =  round(($pacientes_fech[2025] /$pobla_anio_2025)*100000,2);
												
												 }
												 if($fechas_pobla == 2026){
												 $tasa_arreglo[2026] =  round(($pacientes_fech[2026] /$pobla_anio_2026)*100000,2);
												
												 }
												 if($fechas_pobla == 2027){
												 $tasa_arreglo[2027] = round(($pacientes_fech[2027] /$pobla_anio_2027)*100000,2);
												
												 }
												 if($fechas_pobla == 2028){
												 
												 $tasa_arreglo[2028] =  round(($pacientes_fech[2028] /$pobla_anio_2028)*100000,2);
												 
												 }
												 if($fechas_pobla == 2029){
												 $tasa_arreglo[2029] =  round(($pacientes_fech[2029] /$pobla_anio_2029)*100000,2);
												 
												 }
												 if($fechas_pobla == 2030){
												 $tasa_arreglo[2030] = round(($pacientes_fech[2030] /$pobla_anio_2030)*100000,2);
												 
												 }

												 												
											  
											 }
												
												
												foreach($arreglo_fecha_2 as $anio_44){	
													if($tasa_arreglo[$anio_44] == "")
														 $tasa_arreglo[$anio_44] = 0;
													else
														 $tasa_arreglo[$anio_44];
													
													
													$mostrar_tas .= $tasa_arreglo[$anio_44].",";
													$mostrar_etiquetas .= "'".$tasa_arreglo[$anio_44]."',";
											
													$mostrar_anio .= "'".$anio_44."',";														
													}	
													
													
																
											foreach($arreglo_fecha_2 as $anio_45){	
													$mostrar_tas_2 .= "'".$tasa_arreglo_2[$anio_45]."',";	
													$mostrar_anio_2 .= "'".$anio_45."',";														
											}	
											
	//-------------------------------------------------------------------------------------------------------------------------------------------------------																			
										$objHTML->startFieldset('Casos Nuevos por Años');
										echo '<div align="center"><canvas id="cvs" width="600" height="250">[No canvas support]</canvas></div>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs", [';
														
															foreach($arreglo_fecha_2 as $anio){																
																$cuantos_pacientes = intval($pacientes_fech[$anio]);
																$cadena_2 .= $cuantos_pacientes.',';
															}															

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
													
													echo'])
														.Set("gutter.bottom", 40)
														.Set("labels", [';

															foreach($arreglo_fecha_2 as $valor){
																$cadena .= '"'.$valor.'",';
															}

															echo $eliminar = substr($cadena, 0, -1);
															
														
														echo '])
														.Draw();
													
													</script>';
													

										
  
										$objHTML->endFieldset();	
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------


									$objHTML->startFieldset('Incidencia  de casos nuevos de Lepra');
									
									echo "<div align='center'><canvas id='cvs_tasa_1' width='450' height='250'>[No canvas support]</canvas><div>";
									
									echo "<script>
										 var dataset2 = ["; 
										 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
										 echo "];
								
											var line = new RGraph.Line('cvs_tasa_1', [dataset2])
												.Set('curvy', true)
												.Set('curvy.tickmarks', true)
												.Set('curvy.tickmarks.fill', null)
												.Set('curvy.tickmarks.stroke', '#aaa')
												.Set('curvy.tickmarks.stroke.linewidth', 2)
												.Set('curvy.tickmarks.size', 5)
												.Set('linewidth', 3)
												.Set('hmargin', 5)
												.Set('ymax', '0.5')
												.Set('labels', [";
												echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
												echo "])
												.Set('tooltips', [";
												
												echo $tasa_coordenadas_etiquetas =  substr($mostrar_etiquetas, 0, -1);
												
												echo "])
												.Set('tickmarks', 'circle')
											RGraph.Effects.Line.jQuery.Trace(line);
									</script>";
									/*echo "<script>
											
    

											 var bipolar = new RGraph.Bipolar('cvs_tasa_1', [";
											 foreach($arreglo_fecha_2 as $anio_8){																
																$cuantos_pacientes_8 = intval($pacientes_fech[$anio_8]);
																$cadena_8 .= $cuantos_pacientes_8.',';
															}	
															echo  $eliminar_8 = substr($cadena_8, 0, -1);	
											 
											 
											 echo "],[";
											 
											 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
											 
											 
											 echo "])
															.Set('labels', [";
															echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
															echo "])
															.Set('colors', ['blue'])
															.Set('chart.title.left','Casos Nuevos')
															.Set('chart.title.right','Casos por Taza')
															.Draw();
											</script>";
*/
											


									$objHTML->endFieldset();

																		
//-------------------------------------------------------------------------------------------------------------------------------------------------------										
										$objHTML->startFieldset('Distribución por Sexo');
										
										
										$totalPac = $pacientes_hombre + $pacientes_mujer;
										
										$porcH = round(($pacientes_hombre * 100) / $totalPac,2);
										$porcM = round(($pacientes_mujer * 100) / $totalPac,2);
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvsP1" width="400" height="250">[No canvas support]</canvas>';
								/*		echo '<script>';
										echo " var data = [".$porcH.",".$porcM."];

												var pie = new RGraph.Pie('cvsP1', data)
													.Set('labels', ['',''])
													.Set('tooltips.event', 'onmousemove')
													.Set('colors', ['blue','pink'])
													.Set('strokestyle', 'white')
													.Set('linewidth', 3)
													.Set('shadow', true)
													.Set('shadow.offsetx', 2)
													.Set('shadow.offsety', 2)
													.Set('shadow.blur', 3)
													.Set('tooltips', ['Hombres','Mujeres'])
													.Set('exploded', 7)
												
												for (var i=0; i<data.length; ++i) {
													pie.Get('labels')[i] = pie.Get('chart.labels')[i] + ', ' + data[i] + '%';
												}
												
												pie.Draw();
											</script>";*/
											
											
								echo "<script>
											var pie = new RGraph.Pie('cvsP1', [".$porcH.",".$porcM."])
											
												
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', ['Hombres','Mujeres'])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', ['".$porcH."','".$porcM."'])
												.Set('chart.colors', ['blue', 'pink'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
										
										
	//------------------------------------------------------------------------------------------------------------------------------------									
										
												echo '<canvas id="cvs_2" width="500" height="250">[No canvas support]</canvas>';
										echo '<script >
												  var bar = new RGraph.Bar("cvs_2", [';
															
															$max = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio){																
																$mujeres = intval($pacientes_fetch2[$anio]['m']);
																$hombres = intval($pacientes_fetch2[$anio]['h']);
																
																if($mujeres > $max)
																	$max = $mujeres;
																if($hombres > $max)
																	$max = $hombres;
																
																$cadena_2 .= '['.$mujeres.','.$hombres.'],';
															}												
															
															if($max <= 5)
																$max = 5;			

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
											
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor){
																$cadena4 .= '"'.$valor.'",';
															}

															echo $eliminar4 = substr($cadena4, 0, -1);
															}
												
												$grafica_sexo_anio = '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max.')
												.Set("shadow", false)
												.Set("shadow.offsetx", 0)
												.Set("shadow.offsety", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 15)
												.Set("colors", ["Gradient(pink)","Gradient(blue)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
										echo utf8_decode($grafica_sexo_anio);
										
										$objHTML->endFieldset();
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------
									
										//PRUEBAAAAAAAAAAAAAAAAAAAAAAAA
										$pacientes = array();
											$pacientes[1] = 0;
											$pacientes[2] = 0;
											$pacientes[3] = 0;
											$pacientes[4] = 0;
											$pacientes[5] = 0;
											$pacientes[6] = 0;
											$pacientes[7] = 0;
											$pacientes[8] = 0;
											$pacientes[9] = 0;
											$pacientes[10] = 0;
											$pacientes[11] = 0;
											$pacientes[12] = 0;
											$pacientes[13] = 0;

											
										$ejecuta_edad = ejecutaQuery($QueryEdad);									
										while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										
											if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){
											
											
											
												$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));										
												$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
												$edad_2 = CalculaEdad($valor_calcular);
												

											
																					
											if($edad_2 < 15){	
												$pacientes[1]++;}
											if($edad_2 >= 15 && $edad_2 <= 19){
												$pacientes[2]++;}
											if($edad_2 >= 20 && $edad_2 <= 24){
												$pacientes[3]++;}
											if($edad_2 >= 25 && $edad_2 <= 29){
												$pacientes[4] ++;}
											if($edad_2 >= 30 && $edad_2 <= 34){
												$pacientes[5]++;}
											if($edad_2 >= 35 && $edad_2 <= 39){
												$pacientes[6]++;}
											if($edad_2 >= 40 && $edad_2 <= 44){
												$pacientes[7]++;}
											if($edad_2 >= 50 && $edad_2 <= 54){
												$pacientes[8]++;}
											if($edad_2 >= 55 && $edad_2 <= 59){
												$pacientes[9]++;}
											if($edad_2 >= 60 && $edad_2 <= 64){
												$pacientes[10]++;}
											if($edad_2 >= 65 && $edad_2 <= 69){
												$pacientes[11]++;}
											if($edad_2 >= 70 && $edad_2 <= 74){
												$pacientes[12]++;}
											if($edad_2 > 75){
												$pacientes[13]++;}

										}
									}
									
									$suma_total= 0;
									foreach($pacientes as $suma_paci){
											$suma_total = $suma_total + $suma_paci;
										}
									
								
									
									
									echo "<br/>";
									foreach($pacientes as $key_paci => $cantidad){																
																$cuantos_pacientes_4 = intval($cantidad);
																if($cuantos_pacientes_4 != 0){
																$cadena_4 .= $cuantos_pacientes_4.',';
																$cadena_key .= $key_paci.',';
																$porcentaje_separado = round(($cuantos_pacientes_4 * 100) / $suma_total,2);
																$porcentaje .= '"'.$porcentaje_separado.'%",';
																}
															}															
														
														$porce_etiqueta = substr($porcentaje, 0, -1);
													    $eliminar_key = substr($cadena_key, 0, -1);
														$separar = explode(",",$eliminar_key);
														$contar_arreglo = count($separar);
														for($w=0;$w<=$contar_arreglo;$w++){
																	if($separar[$w] == 1 )
																$separar[$w] = "<15"; 
															if($separar[$w] == 2)
																$separar[$w] = "15-19"; 
															if($separar[$w] == 3)
																$separar[$w] = "20-24"; 
															if($separar[$w] == 4)
																$separar[$w] = "25-29"; 
															if($separar[$w] == 5)
																$separar[$w] = "30-34"; 
															if($separar[$w] == 6)
																$separar[$w] = "35-39"; 
															if($separar[$w] == 7)
																$separar[$w] = "40-44"; 
															if($separar[$w] == 8)
																$separar[$w] = "50-54"; 
															if($separar[$w] == 9)
																$separar[$w] = "55-59"; 
															if($separar[$w] == 10)
																$separar[$w] = "60-64"; 
															if($separar[$w] == 11)
																$separar[$w] = "65-69"; 
															if($separar[$w] == 12)
																$separar[$w] = "70-75"; 
															if($separar[$w] == 13)
																$separar[$w] = ">76"; 
															
														
															
															}
															
															
															foreach($separar as $etiquetas){
																$etiquetas_2 .= "'".$etiquetas."',";
																}
														
														 
														
														
									$objHTML->startFieldset('Grupo de Edad más Afectado');
									
									echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td>
									<canvas id="cvs_rango_edad_2" width="330" height="250" !style="border:1px solid #ccc">[No canvas support]</canvas>';
									
							echo "<script>var pie = new RGraph.Pie('cvs_rango_edad_2', ["; 
																					
										 echo $eliminar_4 = substr($cadena_4, 0, -1);
													

											echo"])
												.Set('strokestyle', '#e8e8e8')
												.Set('linewidth', 5)
												.Set('shadow', true)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('shadow.color', '#aaa')
												.Set('exploded', 10)
												.Set('radius', 70)
												.Set('tooltips', [";
												echo $porce_etiqueta;
												echo "])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [";
												echo $etiquetas_3 = substr($etiquetas_2, 0, -1);
												echo "])
												.Set('labels.sticks', true)
												.Set('labels.sticks.length', 15);
												
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td>";
									
													
									
										
											echo '<td><canvas id="cvs_4" width="600" height="250">[No canvas support]</canvas>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs_4", [';
														foreach($pacientes as $cantidad_2){																
																$cuantos_pacientes_5 = intval($cantidad_2);
																$cadena_5 .= $cuantos_pacientes_5.',';
															}															

														echo  $eliminar_5 = substr($cadena_5, 0, -1);
						
													
													echo'])
														.Set("ymax", 5)
														.Set("gutter.bottom", 40)
														.Set("labels", ["< 15","15-19","20-24","25-29","30-34","35-39","40-44","50-54","55-59","60-64","65-69","70-75",">76"])
														.Draw();
													
													</script></td></tr></table>';
										$objHTML->endFieldset();
	
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------									
						//ultimaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa		
									
						 	$QueryLepra = "SELECT p.* ,diag.*, tipo_le.* FROM catJurisdiccion j, pacientes p, catUnidad u, catMunicipio m, diagnostico diag, catClasificacionLepra tipo_le WHERE p.idCatUnidadTratante = u.idCatUnidad AND m.idCatJurisdiccion = j.idCatJurisdiccion AND m.idCatEstado = j.idCatEstado AND u.idcatEstado = ".$_POST['edoReporteEdad']." AND u.idCatMunicipio = m.idCatMunicipio AND m.idCatEstado = u.idcatEstado AND diag.idPaciente = p.idPaciente AND tipo_le.idCatClasificacionLepra = diag.idCatClasificacionLepra  AND fechaDiagnostico BETWEEN'".$fecha_consu_inicio."' AND '".$fecha_consu_fin."' ORDER BY fechaDiagnostico";
									
									
									$ejecuda_lepra = ejecutaQuery($QueryLepra);
									$numero_registros_segundo = devuelveNumRows( $ejecuda_lepra);
									
									while($resultado_tipo_lepra = devuelveRowAssoc($ejecuda_lepra)){
										

										$fecha_nac = explode("-",formatFechaObj($resultado_tipo_lepra['fechaNacimiento']));										
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										$edad_2 = CalculaEdad($valor_calcular);
										$resultado_tipo_lepra['idCatClasificacionLepra'];
										//echo '<br>';
										//if($edad_2 > 25 && $edad_2 < 36){
										if(true){
											
											//if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											if(true){
											
												$fecha_diag_2 = explode("-",formatFechaObj($resultado_tipo_lepra['fechaDiagnostico']));		
												
													$anio_2 = $fecha_diag_2[2];		
													
																									
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 1){
														$pacientes_tipo_lepra_lepromatosa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 2){
														$pacientes_tipo_lepra_dimorfo++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['dimorfo']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 3){
														$pacientes_tipo_lepra_tuberculoide++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['tuberculoide']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 4){
														$pacientes_tipo_lepra_indeterminado++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['indeterminado']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 5){
														$pacientes_tipo_lepra_lepromatosa_difusa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa_difusa']++;
													}	
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
										} // fin if edad
									}		//fin while
										
																				
										$objHTML->startFieldset('Por su Clasificación');
										

										
										$totalLepra = $pacientes_tipo_lepra_lepromatosa + $pacientes_tipo_lepra_dimorfo + $pacientes_tipo_lepra_tuberculoide + $pacientes_tipo_lepra_indeterminado +$pacientes_tipo_lepra_lepromatosa_difusa;
										
										$porclepro = ($pacientes_tipo_lepra_lepromatosa * 100) / $totalLepra;
										$porcdimorfo = ($pacientes_tipo_lepra_dimorfo * 100) / $totalLepra;
										$porctubercu = ($pacientes_tipo_lepra_tuberculoide * 100) / $totalLepra;
										$porcindeter = ($pacientes_tipo_lepra_indeterminado * 100) / $totalLepra;
										$porcdifusa = ($pacientes_tipo_lepra_lepromatosa_difusa * 100) / $totalLepra;
										
										
										$porclepro = number_format($porclepro,2,'.','');
										$porcdimorfo = number_format($porcdimorfo,2,'.','');
										$porctubercu = number_format($porctubercu,2,'.','');
										$porcindeter = number_format($porcindeter,2,'.','');
										$porcdifusa = number_format($porcdifusa,2,'.','');
																				
										$data_x = '';
										$color_x = '';
										$label_x = '';
										$label_num = '';
										
										if($porclepro > 0){
											$data_x .= $porclepro.',';
											$color_x .= "'Gradient(pink:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Lepromatosa Nodular (MB)',";
											$label_num .= "'".$porclepro."%',";
										}
										if($porcdimorfo > 0){
											$data_x .= $porcdimorfo.',';
											$color_x .= "'Gradient(blue:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Dimorfo (MB)',";
											$label_num .= "'".$porcdimorfo."%',";
										}
										if($porctubercu > 0){
											$data_x .= $porctubercu.',';
											$color_x .= "'Gradient(red:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Tuberculoide (PB)',";
											$label_num .= "'".$porctubercu."%',";
										}
										if($porcindeter > 0){
											$data_x .= $porcindeter.',';
											$color_x .= "'Gradient(green:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Indeterminado (PB)',";
											$label_num .= "'".$porcindeter."%',";
										}
										if($porcdifusa > 0){
											$data_x .= $porcdifusa.',';
											$color_x .= "'Gradient(purple:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Lepra Lepromatosa Difusa (MB)',";
											$label_num .= "'".$porcdifusa."%',";
										}
										
										$data_x = substr($data_x, 0, -1);
										$color_x = substr($color_x, 0, -1);
										$label_x = substr($label_x, 0, -1);
										$label_num = substr($label_num, 0, -1);
										
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvspor_lepra" width="325" height="250">[No canvas support]</canvas>';
										
										
										echo "<script>
											var pie = new RGraph.Pie('cvspor_lepra', [".$data_x."])
											
														
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', [".$label_x."])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [".$label_num."])
												//.Set('chart.colors', ['red', 'blue'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
									
											
											
											
											echo '<canvas id="cvs_7" width="600" height="250">[No canvas support]</canvas>';
											echo '<script >
												  var bar = new RGraph.Bar("cvs_7", [';
															
															$max_3 = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio_6){						
																									
																$lepromatosa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa']);
																$dimorfo = intval($pacientes_tipo_lepra[$anio_6]['dimorfo']);
																$tuberculoide = intval($pacientes_tipo_lepra[$anio_6]['tuberculoide']);
																$indeterminado = intval($pacientes_tipo_lepra[$anio_6]['indeterminado']);
																$lepromatosa_difusa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa_difusa']);
																
																if($lepromatosa > $max_3)
																	$max_3 = $lepromatosa;
																if($dimorfo > $max_3)
																	$max_3 = $dimorfo;
																if($tuberculoide > $max_3)
																	$max_3 = $tuberculoide;
																if($indeterminado > $max_3)
																	$max_3 = $indeterminado;
																if($lepromatosa_difusa > $max_3)
																	$max_3 = $lepromatosa_difusa;
																	
																
																$cadena_lepra .= '['.$lepromatosa.','.$dimorfo.','.$tuberculoide.','.$indeterminado.','.$lepromatosa_difusa.'],';
																//$cadena_lepra .= '[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,62]';
															}												
															
															if($max_3 <= 5)
																$max_3 = 5;			

														echo  $parametros_lepra = substr($cadena_lepra, 0, -1);
													//echo '[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4]';
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor_3){
																$cadena_3 .= '"'.$valor_3.'",';
															}

															echo $eliminar_cad = substr($cadena_3, 0, -1);
															}
												
												echo '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max_3.')
											
											
												.Set("shadow", false)
												.Set("shadow.offsetx", 10)
												.Set("shadow.offsety", 0)
												.Set("shadow.blur", 40)
												.Set("hmargin.grouped", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 45)
												.Set("colors", ["Gradient(pink)","Gradient(blue)","Gradient(red)","Gradient(green)","Gradient(purple)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
												
										$objHTML->endFieldset();
										
										
										}
										
										
									}
									break;
							
								case '4': {								
																		$por_anio = "Por Año";
									if($numero_registros_primero == 0 && $numero_registros_segundo == 0)
										echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
			
										else{
										
										
										if($resultado == 0){										
										$arreglo_fecha_2[0] = $sacar_anio_inicio[2];
										}
										else{
											$t =0; 
											for($x=$sacar_anio_inicio[2];$x<=$sacar_anio_fin[2];$x++){
												$arreglo_fecha_2[$t++] = $x;
												}
											
											}
									$e = 0;		
									$t = 0;
									$r = 0;
									$pacientes_fech = array();
									while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										//echo "<br/>".$resultado_edad['nombre']."---";
									    $edad_2 = CalculaEdad($valor_calcular);
																
										// condicion edad
										//if($edad_2< 10){
											//if($edad_2 > 9 && $edad_2 < 16){

											//if($edad_2 > 15 && $edad_2 < 26){
											
											if($edad_2 > 25 && $edad_2 < 36){
												
											//if($edad_2 > 35 && $edad_2 < 46){
												
											//if($edad_2 > 45){
											
												if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											//if(true){
											
											
											
										
										
												$fecha_diag = explode("-",formatFechaObj($resultado_edad['fechaDiagnostico']));		
												
													$anio = $fecha_diag[2];		
													
													$pacientes_fech[$anio]++;										
													
													if($resultado_edad['sexo'] == 1){
														$pacientes_hombre++;
														$pacientes_fetch2[$anio]['h']++;
													}else{
														$pacientes_fetch2[$anio]['m']++;
														$pacientes_mujer++;
													}
														
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
											
											//fin condicion edad 
											}
											
										}
											
										
										$pobla_anio_1990 = "3326140";
										$pobla_anio_1991 = "3398566";
										$pobla_anio_1992 = "3471236";
										$pobla_anio_1993 = "3543231";
										$pobla_anio_1994 = "3613850";
										$pobla_anio_1995 = "3683005";
										$pobla_anio_1996 = "3750840";
										$pobla_anio_1997 = "3817152";
										$pobla_anio_1998 = "3882823";
										$pobla_anio_1999 = "3949837";
										$pobla_anio_2000 = "4018049";
										$pobla_anio_2001 = "4085008";
										$pobla_anio_2002 = "4148101";
										$pobla_anio_2003 = "4206345";
										$pobla_anio_2004 = "4260523";
										$pobla_anio_2005 = "4312067";
										$pobla_anio_2006 = "4362413";
										$pobla_anio_2007 = "4411808";
										$pobla_anio_2008 = "4460013";
										$pobla_anio_2009 = "4507177";
										$pobla_anio_2010 = "4903700";
										$pobla_anio_2011 = "4978849";
										$pobla_anio_2012 = "5048184";
										$pobla_anio_2013 = "5116489";
										$pobla_anio_2014 = "5183827";
										$pobla_anio_2015 = "5250306";
										$pobla_anio_2016 = "5315921";
										$pobla_anio_2017 = "5380769";
										$pobla_anio_2018 = "5444910";
										$pobla_anio_2019 = "5508320";
										$pobla_anio_2020 = "5571476";
										$pobla_anio_2021 = "5633555";
										$pobla_anio_2022 = "5693619";
										$pobla_anio_2023 = "5752024";
										$pobla_anio_2024 = "5808596";
										$pobla_anio_2025 = "5863237";
										$pobla_anio_2026 = "5915724";
										$pobla_anio_2027 = "5965872";
										$pobla_anio_2028 = "6013569";
										$pobla_anio_2029 = "6058553";
										$pobla_anio_2030 = "6099290";
									
											

											
											
											
										
										foreach($arreglo_fecha_2 as $fechas_pobla){
											
											
											if($fechas_pobla == 1990 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1990] =  round(($pacientes_fech[1990] /$pobla_anio_1990)*100000,2);
												 }
											if($fechas_pobla == 1991 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1991] =  round(($pacientes_fech[1991] /$pobla_anio_1991)*100000,2);
												 }
											
											if($fechas_pobla == 1992 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1992] =  round(($pacientes_fech[1992] /$pobla_anio_1992)*100000,2);
												 }
											
											if($fechas_pobla == 1993 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1993] =  round(($pacientes_fech[1993] /$pobla_anio_1993)*100000,2);
												 }
											
											if($fechas_pobla == 1994 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1994] =  round(($pacientes_fech[1994] /$pobla_anio_1994)*100000,2);
												 }
											
											if($fechas_pobla == 1995 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1995] =  round(($pacientes_fech[1995] /$pobla_anio_1995)*100000,2);
												 }
											
											if($fechas_pobla == 1996 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1996] =  round(($pacientes_fech[1996] /$pobla_anio_1996)*100000,2);
												 }
											
											if($fechas_pobla == 1997 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1997] =  round(($pacientes_fech[1997] /$pobla_anio_1997)*100000,2);
												 }
											
											if($fechas_pobla == 1998 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1998] =  round(($pacientes_fech[1998] /$pobla_anio_1998)*100000,2);
												 }
											
											if($fechas_pobla == 1999 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1999] =  round(($pacientes_fech[1999] /$pobla_anio_1999)*100000,2);
												 }
											
											if($fechas_pobla == 2000 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2000] =  round(($pacientes_fech[2000] /$pobla_anio_2000)*100000,2);
												 }
											
											if($fechas_pobla == 2001 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2001] =  round(($pacientes_fech[2001] /$pobla_anio_2001)*100000,2);
												 }
											
											if($fechas_pobla == 2002 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2002] =  round(($pacientes_fech[2002] /$pobla_anio_2002)*100000,2);
												 }
											
											if($fechas_pobla == 2003 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2003] =  round(($pacientes_fech[2003] /$pobla_anio_2003)*100000,2);
												 }
											
											if($fechas_pobla == 2004 && $_POST['edoReporteEdad'] == 7){ 
												 $tasa_arreglo[2004] =  round(($pacientes_fech[2004] /$pobla_anio_2004)*100000,2);
												 }
											
											if($fechas_pobla == 2005 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2005] =  round(($pacientes_fech[2005] /$pobla_anio_2005)*100000,2);
												 }
											
											if($fechas_pobla == 2006 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2006] =  round(($pacientes_fech[2006] /$pobla_anio_2006)*100000,2);
												 }
											
											if($fechas_pobla == 2007 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2007] =  round(($pacientes_fech[2007] /$pobla_anio_2007)*100000,2);
												 }
											
											if($fechas_pobla == 2008 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2008] =  round(($pacientes_fech[2008] /$pobla_anio_2008)*100000,2);
												 }
											
											if($fechas_pobla == 2009 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2009] =  round(($pacientes_fech[2009] /$pobla_anio_2009)*100000,2);
												 }

											 if($fechas_pobla == 2010 ){
												 $tasa_arreglo[2010] =  round(($pacientes_fech[2010] /$pobla_anio_2010)*100000,2);
												 }
												 
											if($fechas_pobla == 2011){
												 $tasa_arreglo[2011] =  round(($pacientes_fech[2011] /$pobla_anio_2011)*100000,2);
												 }												
											if($fechas_pobla == 2012){
												 $tasa_arreglo[2012] =  round(($pacientes_fech[2012] /$pobla_anio_2012)*100000,2);
												 }
											if($fechas_pobla == 2013){
												 	$tasa_arreglo[2013] =  round(($pacientes_fech[2013] /$pobla_anio_2013)*100000,2);
												 }
												 
												 
												 if($fechas_pobla == 2014){
												 $tasa_arreglo[2014] =  round(($pacientes_fech[2014] /$pobla_anio_2014)*100000,2);
												
												 }
												 
												if($fechas_pobla == 2015){
												 $tasa_arreglo[2015] =  round(($pacientes_fech[2015] /$pobla_anio_2015)*100000,2);
																							 

												 }
												 if($fechas_pobla == 2016){
												 $tasa_arreglo[2016] =  round(($pacientes_fech[2016] /$pobla_anio_2016)*100000,2);
												
												 }
												 if($fechas_pobla == 2017){
												 $tasa_arreglo[2017] =  round(($pacientes_fech[2017] /$pobla_anio_2017)*100000,2);
												
												 }
												 if($fechas_pobla == 2018){
												 $tasa_arreglo[2018] =  round(($pacientes_fech[2018] /$pobla_anio_2018)*100000,2);
												
												 }
												 if($fechas_pobla == 2019){
												 $tasa_arreglo[2019] =  round(($pacientes_fech[2019] /$pobla_anio_2019)*100000,2);
												
												 }
												 if($fechas_pobla == 2020){
												 $tasa_arreglo[2020] =  round(($pacientes_fech[2020] /$pobla_anio_2020)*100000,2);
												
												 }
												 if($fechas_pobla == 2021){
												 $tasa_arreglo[2021] =  round(($pacientes_fech[2021] /$pobla_anio_2021)*100000,2);
												
												 }
												 if($fechas_pobla == 2022){
												 $tasa_arreglo[2022] =  round(($pacientes_fech[2022] /$pobla_anio_2022)*100000,2);
												
												 }
												 if($fechas_pobla == 2023){
												 $tasa_arreglo[2023] =  round(($pacientes_fech[2023] /$pobla_anio_2023)*100000,2);
												
												 }
												 if($fechas_pobla == 2024){
												 $tasa_arreglo[2024] =  round(($pacientes_fech[2024] /$pobla_anio_2024)*100000,2);
												
												 }
												 if($fechas_pobla == 2025){
												 $tasa_arreglo[2025] =  round(($pacientes_fech[2025] /$pobla_anio_2025)*100000,2);
												
												 }
												 if($fechas_pobla == 2026){
												 $tasa_arreglo[2026] =  round(($pacientes_fech[2026] /$pobla_anio_2026)*100000,2);
												
												 }
												 if($fechas_pobla == 2027){
												 $tasa_arreglo[2027] = round(($pacientes_fech[2027] /$pobla_anio_2027)*100000,2);
												
												 }
												 if($fechas_pobla == 2028){
												 
												 $tasa_arreglo[2028] =  round(($pacientes_fech[2028] /$pobla_anio_2028)*100000,2);
												 
												 }
												 if($fechas_pobla == 2029){
												 $tasa_arreglo[2029] =  round(($pacientes_fech[2029] /$pobla_anio_2029)*100000,2);
												 
												 }
												 if($fechas_pobla == 2030){
												 $tasa_arreglo[2030] = round(($pacientes_fech[2030] /$pobla_anio_2030)*100000,2);
												 
												 }

												 												
											  
											 }
												
												
												foreach($arreglo_fecha_2 as $anio_44){	
													if($tasa_arreglo[$anio_44] == "")
														 $tasa_arreglo[$anio_44] = 0;
													else
														 $tasa_arreglo[$anio_44];
													
													
													$mostrar_tas .= $tasa_arreglo[$anio_44].",";
													$mostrar_etiquetas .= "'".$tasa_arreglo[$anio_44]."',";
											
													$mostrar_anio .= "'".$anio_44."',";														
													}	
													
													
																
											foreach($arreglo_fecha_2 as $anio_45){	
													$mostrar_tas_2 .= "'".$tasa_arreglo_2[$anio_45]."',";	
													$mostrar_anio_2 .= "'".$anio_45."',";														
											}	
											
	//-------------------------------------------------------------------------------------------------------------------------------------------------------																			
										$objHTML->startFieldset('Casos Nuevos por Años');
										echo '<div align="center"><canvas id="cvs" width="600" height="250">[No canvas support]</canvas></div>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs", [';
														
															foreach($arreglo_fecha_2 as $anio){																
																$cuantos_pacientes = intval($pacientes_fech[$anio]);
																$cadena_2 .= $cuantos_pacientes.',';
															}															

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
													
													echo'])
														.Set("gutter.bottom", 40)
														.Set("labels", [';

															foreach($arreglo_fecha_2 as $valor){
																$cadena .= '"'.$valor.'",';
															}

															echo $eliminar = substr($cadena, 0, -1);
															
														
														echo '])
														.Draw();
													
													</script>';
													

										
  
										$objHTML->endFieldset();	
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------


									$objHTML->startFieldset('Incidencia  de casos nuevos de Lepra');
									
									echo "<div align='center'><canvas id='cvs_tasa_1' width='450' height='250'>[No canvas support]</canvas><div>";
									
									echo "<script>
										 var dataset2 = ["; 
										 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
										 echo "];
								
											var line = new RGraph.Line('cvs_tasa_1', [dataset2])
												.Set('curvy', true)
												.Set('curvy.tickmarks', true)
												.Set('curvy.tickmarks.fill', null)
												.Set('curvy.tickmarks.stroke', '#aaa')
												.Set('curvy.tickmarks.stroke.linewidth', 2)
												.Set('curvy.tickmarks.size', 5)
												.Set('linewidth', 3)
												.Set('hmargin', 5)
												.Set('ymax', '0.5')
												.Set('labels', [";
												echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
												echo "])
												.Set('tooltips', [";
												
												echo $tasa_coordenadas_etiquetas =  substr($mostrar_etiquetas, 0, -1);
												
												echo "])
												.Set('tickmarks', 'circle')
											RGraph.Effects.Line.jQuery.Trace(line);
									</script>";
									/*echo "<script>
											
    

											 var bipolar = new RGraph.Bipolar('cvs_tasa_1', [";
											 foreach($arreglo_fecha_2 as $anio_8){																
																$cuantos_pacientes_8 = intval($pacientes_fech[$anio_8]);
																$cadena_8 .= $cuantos_pacientes_8.',';
															}	
															echo  $eliminar_8 = substr($cadena_8, 0, -1);	
											 
											 
											 echo "],[";
											 
											 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
											 
											 
											 echo "])
															.Set('labels', [";
															echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
															echo "])
															.Set('colors', ['blue'])
															.Set('chart.title.left','Casos Nuevos')
															.Set('chart.title.right','Casos por Taza')
															.Draw();
											</script>";
*/
											


									$objHTML->endFieldset();

																		
//-------------------------------------------------------------------------------------------------------------------------------------------------------										
										$objHTML->startFieldset('Distribución por Sexo');
										
										
										$totalPac = $pacientes_hombre + $pacientes_mujer;
										
										$porcH = round(($pacientes_hombre * 100) / $totalPac,2);
										$porcM = round(($pacientes_mujer * 100) / $totalPac,2);
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvsP1" width="400" height="250">[No canvas support]</canvas>';
								/*		echo '<script>';
										echo " var data = [".$porcH.",".$porcM."];

												var pie = new RGraph.Pie('cvsP1', data)
													.Set('labels', ['',''])
													.Set('tooltips.event', 'onmousemove')
													.Set('colors', ['blue','pink'])
													.Set('strokestyle', 'white')
													.Set('linewidth', 3)
													.Set('shadow', true)
													.Set('shadow.offsetx', 2)
													.Set('shadow.offsety', 2)
													.Set('shadow.blur', 3)
													.Set('tooltips', ['Hombres','Mujeres'])
													.Set('exploded', 7)
												
												for (var i=0; i<data.length; ++i) {
													pie.Get('labels')[i] = pie.Get('chart.labels')[i] + ', ' + data[i] + '%';
												}
												
												pie.Draw();
											</script>";*/
											
											
								echo "<script>
											var pie = new RGraph.Pie('cvsP1', [".$porcH.",".$porcM."])
											
												
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', ['Hombres','Mujeres'])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', ['".$porcH."','".$porcM."'])
												.Set('chart.colors', ['blue', 'pink'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
										
										
	//------------------------------------------------------------------------------------------------------------------------------------									
										
												echo '<canvas id="cvs_2" width="500" height="250">[No canvas support]</canvas>';
										echo '<script >
												  var bar = new RGraph.Bar("cvs_2", [';
															
															$max = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio){																
																$mujeres = intval($pacientes_fetch2[$anio]['m']);
																$hombres = intval($pacientes_fetch2[$anio]['h']);
																
																if($mujeres > $max)
																	$max = $mujeres;
																if($hombres > $max)
																	$max = $hombres;
																
																$cadena_2 .= '['.$mujeres.','.$hombres.'],';
															}												
															
															if($max <= 5)
																$max = 5;			

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
											
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor){
																$cadena4 .= '"'.$valor.'",';
															}

															echo $eliminar4 = substr($cadena4, 0, -1);
															}
												
												$grafica_sexo_anio = '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max.')
												.Set("shadow", false)
												.Set("shadow.offsetx", 0)
												.Set("shadow.offsety", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 15)
												.Set("colors", ["Gradient(pink)","Gradient(blue)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
										echo utf8_decode($grafica_sexo_anio);
										
										$objHTML->endFieldset();
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------
									
										//PRUEBAAAAAAAAAAAAAAAAAAAAAAAA
										$pacientes = array();
											$pacientes[1] = 0;
											$pacientes[2] = 0;
											$pacientes[3] = 0;
											$pacientes[4] = 0;
											$pacientes[5] = 0;
											$pacientes[6] = 0;
											$pacientes[7] = 0;
											$pacientes[8] = 0;
											$pacientes[9] = 0;
											$pacientes[10] = 0;
											$pacientes[11] = 0;
											$pacientes[12] = 0;
											$pacientes[13] = 0;

											
										$ejecuta_edad = ejecutaQuery($QueryEdad);									
										while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										
											if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){
											
											
											
												$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));										
												$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
												$edad_2 = CalculaEdad($valor_calcular);
												

											
																					
											if($edad_2 < 15){	
												$pacientes[1]++;}
											if($edad_2 >= 15 && $edad_2 <= 19){
												$pacientes[2]++;}
											if($edad_2 >= 20 && $edad_2 <= 24){
												$pacientes[3]++;}
											if($edad_2 >= 25 && $edad_2 <= 29){
												$pacientes[4] ++;}
											if($edad_2 >= 30 && $edad_2 <= 34){
												$pacientes[5]++;}
											if($edad_2 >= 35 && $edad_2 <= 39){
												$pacientes[6]++;}
											if($edad_2 >= 40 && $edad_2 <= 44){
												$pacientes[7]++;}
											if($edad_2 >= 50 && $edad_2 <= 54){
												$pacientes[8]++;}
											if($edad_2 >= 55 && $edad_2 <= 59){
												$pacientes[9]++;}
											if($edad_2 >= 60 && $edad_2 <= 64){
												$pacientes[10]++;}
											if($edad_2 >= 65 && $edad_2 <= 69){
												$pacientes[11]++;}
											if($edad_2 >= 70 && $edad_2 <= 74){
												$pacientes[12]++;}
											if($edad_2 > 75){
												$pacientes[13]++;}

										}
									}
									
									$suma_total= 0;
									foreach($pacientes as $suma_paci){
											$suma_total = $suma_total + $suma_paci;
										}
									
								
									
									
									echo "<br/>";
									foreach($pacientes as $key_paci => $cantidad){																
																$cuantos_pacientes_4 = intval($cantidad);
																if($cuantos_pacientes_4 != 0){
																$cadena_4 .= $cuantos_pacientes_4.',';
																$cadena_key .= $key_paci.',';
																$porcentaje_separado = round(($cuantos_pacientes_4 * 100) / $suma_total,2);
																$porcentaje .= '"'.$porcentaje_separado.'%",';
																}
															}															
														
														$porce_etiqueta = substr($porcentaje, 0, -1);
													    $eliminar_key = substr($cadena_key, 0, -1);
														$separar = explode(",",$eliminar_key);
														$contar_arreglo = count($separar);
														for($w=0;$w<=$contar_arreglo;$w++){
																	if($separar[$w] == 1 )
																$separar[$w] = "<15"; 
															if($separar[$w] == 2)
																$separar[$w] = "15-19"; 
															if($separar[$w] == 3)
																$separar[$w] = "20-24"; 
															if($separar[$w] == 4)
																$separar[$w] = "25-29"; 
															if($separar[$w] == 5)
																$separar[$w] = "30-34"; 
															if($separar[$w] == 6)
																$separar[$w] = "35-39"; 
															if($separar[$w] == 7)
																$separar[$w] = "40-44"; 
															if($separar[$w] == 8)
																$separar[$w] = "50-54"; 
															if($separar[$w] == 9)
																$separar[$w] = "55-59"; 
															if($separar[$w] == 10)
																$separar[$w] = "60-64"; 
															if($separar[$w] == 11)
																$separar[$w] = "65-69"; 
															if($separar[$w] == 12)
																$separar[$w] = "70-75"; 
															if($separar[$w] == 13)
																$separar[$w] = ">76"; 
															
														
															
															}
															
															
															foreach($separar as $etiquetas){
																$etiquetas_2 .= "'".$etiquetas."',";
																}
														
														 
														
														
									$objHTML->startFieldset('Grupo de Edad más Afectado');
									
									echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td>
									<canvas id="cvs_rango_edad_2" width="330" height="250" !style="border:1px solid #ccc">[No canvas support]</canvas>';
									
							echo "<script>var pie = new RGraph.Pie('cvs_rango_edad_2', ["; 
																					
										 echo $eliminar_4 = substr($cadena_4, 0, -1);
													

											echo"])
												.Set('strokestyle', '#e8e8e8')
												.Set('linewidth', 5)
												.Set('shadow', true)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('shadow.color', '#aaa')
												.Set('exploded', 10)
												.Set('radius', 70)
												.Set('tooltips', [";
												echo $porce_etiqueta;
												echo "])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [";
												echo $etiquetas_3 = substr($etiquetas_2, 0, -1);
												echo "])
												.Set('labels.sticks', true)
												.Set('labels.sticks.length', 15);
												
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td>";
									
													
									
										
											echo '<td><canvas id="cvs_4" width="600" height="250">[No canvas support]</canvas>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs_4", [';
														foreach($pacientes as $cantidad_2){																
																$cuantos_pacientes_5 = intval($cantidad_2);
																$cadena_5 .= $cuantos_pacientes_5.',';
															}															

														echo  $eliminar_5 = substr($cadena_5, 0, -1);
						
													
													echo'])
														.Set("ymax", 5)
														.Set("gutter.bottom", 40)
														.Set("labels", ["< 15","15-19","20-24","25-29","30-34","35-39","40-44","50-54","55-59","60-64","65-69","70-75",">76"])
														.Draw();
													
													</script></td></tr></table>';
										$objHTML->endFieldset();
	
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------									
						//ultimaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa		
									
						 	$QueryLepra = "SELECT p.* ,diag.*, tipo_le.* FROM catJurisdiccion j, pacientes p, catUnidad u, catMunicipio m, diagnostico diag, catClasificacionLepra tipo_le WHERE p.idCatUnidadTratante = u.idCatUnidad AND m.idCatJurisdiccion = j.idCatJurisdiccion AND m.idCatEstado = j.idCatEstado AND u.idcatEstado = ".$_POST['edoReporteEdad']." AND u.idCatMunicipio = m.idCatMunicipio AND m.idCatEstado = u.idcatEstado AND diag.idPaciente = p.idPaciente AND tipo_le.idCatClasificacionLepra = diag.idCatClasificacionLepra  AND fechaDiagnostico BETWEEN'".$fecha_consu_inicio."' AND '".$fecha_consu_fin."' ORDER BY fechaDiagnostico";
									
									
									$ejecuda_lepra = ejecutaQuery($QueryLepra);
									$numero_registros_segundo = devuelveNumRows( $ejecuda_lepra);
									
									while($resultado_tipo_lepra = devuelveRowAssoc($ejecuda_lepra)){
										

										$fecha_nac = explode("-",formatFechaObj($resultado_tipo_lepra['fechaNacimiento']));										
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										$edad_2 = CalculaEdad($valor_calcular);
										$resultado_tipo_lepra['idCatClasificacionLepra'];
										//echo '<br>';
										//if($edad_2 > 25 && $edad_2 < 36){
										if(true){
											
											//if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											if(true){
											
												$fecha_diag_2 = explode("-",formatFechaObj($resultado_tipo_lepra['fechaDiagnostico']));		
												
													$anio_2 = $fecha_diag_2[2];		
													
																									
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 1){
														$pacientes_tipo_lepra_lepromatosa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 2){
														$pacientes_tipo_lepra_dimorfo++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['dimorfo']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 3){
														$pacientes_tipo_lepra_tuberculoide++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['tuberculoide']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 4){
														$pacientes_tipo_lepra_indeterminado++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['indeterminado']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 5){
														$pacientes_tipo_lepra_lepromatosa_difusa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa_difusa']++;
													}	
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
										} // fin if edad
									}		//fin while
										
																				
										$objHTML->startFieldset('Por su Clasificación');
										

										
										$totalLepra = $pacientes_tipo_lepra_lepromatosa + $pacientes_tipo_lepra_dimorfo + $pacientes_tipo_lepra_tuberculoide + $pacientes_tipo_lepra_indeterminado +$pacientes_tipo_lepra_lepromatosa_difusa;
										
										$porclepro = ($pacientes_tipo_lepra_lepromatosa * 100) / $totalLepra;
										$porcdimorfo = ($pacientes_tipo_lepra_dimorfo * 100) / $totalLepra;
										$porctubercu = ($pacientes_tipo_lepra_tuberculoide * 100) / $totalLepra;
										$porcindeter = ($pacientes_tipo_lepra_indeterminado * 100) / $totalLepra;
										$porcdifusa = ($pacientes_tipo_lepra_lepromatosa_difusa * 100) / $totalLepra;
										
										
										$porclepro = number_format($porclepro,2,'.','');
										$porcdimorfo = number_format($porcdimorfo,2,'.','');
										$porctubercu = number_format($porctubercu,2,'.','');
										$porcindeter = number_format($porcindeter,2,'.','');
										$porcdifusa = number_format($porcdifusa,2,'.','');
																				
										$data_x = '';
										$color_x = '';
										$label_x = '';
										$label_num = '';
										
										if($porclepro > 0){
											$data_x .= $porclepro.',';
											$color_x .= "'Gradient(pink:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Lepromatosa Nodular (MB)',";
											$label_num .= "'".$porclepro."%',";
										}
										if($porcdimorfo > 0){
											$data_x .= $porcdimorfo.',';
											$color_x .= "'Gradient(blue:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Dimorfo (MB)',";
											$label_num .= "'".$porcdimorfo."%',";
										}
										if($porctubercu > 0){
											$data_x .= $porctubercu.',';
											$color_x .= "'Gradient(red:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Tuberculoide (PB)',";
											$label_num .= "'".$porctubercu."%',";
										}
										if($porcindeter > 0){
											$data_x .= $porcindeter.',';
											$color_x .= "'Gradient(green:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Indeterminado (PB)',";
											$label_num .= "'".$porcindeter."%',";
										}
										if($porcdifusa > 0){
											$data_x .= $porcdifusa.',';
											$color_x .= "'Gradient(purple:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Lepra Lepromatosa Difusa (MB)',";
											$label_num .= "'".$porcdifusa."%',";
										}
										
										$data_x = substr($data_x, 0, -1);
										$color_x = substr($color_x, 0, -1);
										$label_x = substr($label_x, 0, -1);
										$label_num = substr($label_num, 0, -1);
										
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvspor_lepra" width="325" height="250">[No canvas support]</canvas>';
										
										
										echo "<script>
											var pie = new RGraph.Pie('cvspor_lepra', [".$data_x."])
											
														
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', [".$label_x."])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [".$label_num."])
												//.Set('chart.colors', ['red', 'blue'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
									
											
											
											
											echo '<canvas id="cvs_7" width="600" height="250">[No canvas support]</canvas>';
											echo '<script >
												  var bar = new RGraph.Bar("cvs_7", [';
															
															$max_3 = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio_6){						
																									
																$lepromatosa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa']);
																$dimorfo = intval($pacientes_tipo_lepra[$anio_6]['dimorfo']);
																$tuberculoide = intval($pacientes_tipo_lepra[$anio_6]['tuberculoide']);
																$indeterminado = intval($pacientes_tipo_lepra[$anio_6]['indeterminado']);
																$lepromatosa_difusa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa_difusa']);
																
																if($lepromatosa > $max_3)
																	$max_3 = $lepromatosa;
																if($dimorfo > $max_3)
																	$max_3 = $dimorfo;
																if($tuberculoide > $max_3)
																	$max_3 = $tuberculoide;
																if($indeterminado > $max_3)
																	$max_3 = $indeterminado;
																if($lepromatosa_difusa > $max_3)
																	$max_3 = $lepromatosa_difusa;
																	
																
																$cadena_lepra .= '['.$lepromatosa.','.$dimorfo.','.$tuberculoide.','.$indeterminado.','.$lepromatosa_difusa.'],';
																//$cadena_lepra .= '[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,62]';
															}												
															
															if($max_3 <= 5)
																$max_3 = 5;			

														echo  $parametros_lepra = substr($cadena_lepra, 0, -1);
													//echo '[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4]';
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor_3){
																$cadena_3 .= '"'.$valor_3.'",';
															}

															echo $eliminar_cad = substr($cadena_3, 0, -1);
															}
												
												echo '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max_3.')
											
											
												.Set("shadow", false)
												.Set("shadow.offsetx", 10)
												.Set("shadow.offsety", 0)
												.Set("shadow.blur", 40)
												.Set("hmargin.grouped", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 45)
												.Set("colors", ["Gradient(pink)","Gradient(blue)","Gradient(red)","Gradient(green)","Gradient(purple)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
												
										$objHTML->endFieldset();
										
										
										}
											
								
									}
									break;
									case '5': {
																			
									 									$por_anio = "Por Año";
									if($numero_registros_primero == 0 && $numero_registros_segundo == 0)
										echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
			
										else{
										
										
										if($resultado == 0){										
										$arreglo_fecha_2[0] = $sacar_anio_inicio[2];
										}
										else{
											$t =0; 
											for($x=$sacar_anio_inicio[2];$x<=$sacar_anio_fin[2];$x++){
												$arreglo_fecha_2[$t++] = $x;
												}
											
											}
									$e = 0;		
									$t = 0;
									$r = 0;
									$pacientes_fech = array();
									while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										//echo "<br/>".$resultado_edad['nombre']."---";
									    $edad_2 = CalculaEdad($valor_calcular);
																
										// condicion edad
										//if($edad_2< 10){
											//if($edad_2 > 9 && $edad_2 < 16){

											//if($edad_2 > 15 && $edad_2 < 26){
											
											//if($edad_2 > 25 && $edad_2 < 36){
												
											if($edad_2 > 35 && $edad_2 < 46){
												
											//if($edad_2 > 45){
											
												if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											//if(true){
											
											
											
										
										
												$fecha_diag = explode("-",formatFechaObj($resultado_edad['fechaDiagnostico']));		
												
													$anio = $fecha_diag[2];		
													
													$pacientes_fech[$anio]++;										
													
													if($resultado_edad['sexo'] == 1){
														$pacientes_hombre++;
														$pacientes_fetch2[$anio]['h']++;
													}else{
														$pacientes_fetch2[$anio]['m']++;
														$pacientes_mujer++;
													}
														
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
											
											//fin condicion edad 
											}
											
										}
											
										
										$pobla_anio_1990 = "3326140";
										$pobla_anio_1991 = "3398566";
										$pobla_anio_1992 = "3471236";
										$pobla_anio_1993 = "3543231";
										$pobla_anio_1994 = "3613850";
										$pobla_anio_1995 = "3683005";
										$pobla_anio_1996 = "3750840";
										$pobla_anio_1997 = "3817152";
										$pobla_anio_1998 = "3882823";
										$pobla_anio_1999 = "3949837";
										$pobla_anio_2000 = "4018049";
										$pobla_anio_2001 = "4085008";
										$pobla_anio_2002 = "4148101";
										$pobla_anio_2003 = "4206345";
										$pobla_anio_2004 = "4260523";
										$pobla_anio_2005 = "4312067";
										$pobla_anio_2006 = "4362413";
										$pobla_anio_2007 = "4411808";
										$pobla_anio_2008 = "4460013";
										$pobla_anio_2009 = "4507177";
										$pobla_anio_2010 = "4903700";
										$pobla_anio_2011 = "4978849";
										$pobla_anio_2012 = "5048184";
										$pobla_anio_2013 = "5116489";
										$pobla_anio_2014 = "5183827";
										$pobla_anio_2015 = "5250306";
										$pobla_anio_2016 = "5315921";
										$pobla_anio_2017 = "5380769";
										$pobla_anio_2018 = "5444910";
										$pobla_anio_2019 = "5508320";
										$pobla_anio_2020 = "5571476";
										$pobla_anio_2021 = "5633555";
										$pobla_anio_2022 = "5693619";
										$pobla_anio_2023 = "5752024";
										$pobla_anio_2024 = "5808596";
										$pobla_anio_2025 = "5863237";
										$pobla_anio_2026 = "5915724";
										$pobla_anio_2027 = "5965872";
										$pobla_anio_2028 = "6013569";
										$pobla_anio_2029 = "6058553";
										$pobla_anio_2030 = "6099290";
									
											

											
											
											
										
										foreach($arreglo_fecha_2 as $fechas_pobla){
											
											
											if($fechas_pobla == 1990 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1990] =  round(($pacientes_fech[1990] /$pobla_anio_1990)*100000,2);
												 }
											if($fechas_pobla == 1991 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1991] =  round(($pacientes_fech[1991] /$pobla_anio_1991)*100000,2);
												 }
											
											if($fechas_pobla == 1992 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1992] =  round(($pacientes_fech[1992] /$pobla_anio_1992)*100000,2);
												 }
											
											if($fechas_pobla == 1993 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1993] =  round(($pacientes_fech[1993] /$pobla_anio_1993)*100000,2);
												 }
											
											if($fechas_pobla == 1994 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1994] =  round(($pacientes_fech[1994] /$pobla_anio_1994)*100000,2);
												 }
											
											if($fechas_pobla == 1995 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1995] =  round(($pacientes_fech[1995] /$pobla_anio_1995)*100000,2);
												 }
											
											if($fechas_pobla == 1996 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1996] =  round(($pacientes_fech[1996] /$pobla_anio_1996)*100000,2);
												 }
											
											if($fechas_pobla == 1997 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1997] =  round(($pacientes_fech[1997] /$pobla_anio_1997)*100000,2);
												 }
											
											if($fechas_pobla == 1998 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1998] =  round(($pacientes_fech[1998] /$pobla_anio_1998)*100000,2);
												 }
											
											if($fechas_pobla == 1999 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1999] =  round(($pacientes_fech[1999] /$pobla_anio_1999)*100000,2);
												 }
											
											if($fechas_pobla == 2000 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2000] =  round(($pacientes_fech[2000] /$pobla_anio_2000)*100000,2);
												 }
											
											if($fechas_pobla == 2001 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2001] =  round(($pacientes_fech[2001] /$pobla_anio_2001)*100000,2);
												 }
											
											if($fechas_pobla == 2002 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2002] =  round(($pacientes_fech[2002] /$pobla_anio_2002)*100000,2);
												 }
											
											if($fechas_pobla == 2003 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2003] =  round(($pacientes_fech[2003] /$pobla_anio_2003)*100000,2);
												 }
											
											if($fechas_pobla == 2004 && $_POST['edoReporteEdad'] == 7){ 
												 $tasa_arreglo[2004] =  round(($pacientes_fech[2004] /$pobla_anio_2004)*100000,2);
												 }
											
											if($fechas_pobla == 2005 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2005] =  round(($pacientes_fech[2005] /$pobla_anio_2005)*100000,2);
												 }
											
											if($fechas_pobla == 2006 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2006] =  round(($pacientes_fech[2006] /$pobla_anio_2006)*100000,2);
												 }
											
											if($fechas_pobla == 2007 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2007] =  round(($pacientes_fech[2007] /$pobla_anio_2007)*100000,2);
												 }
											
											if($fechas_pobla == 2008 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2008] =  round(($pacientes_fech[2008] /$pobla_anio_2008)*100000,2);
												 }
											
											if($fechas_pobla == 2009 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2009] =  round(($pacientes_fech[2009] /$pobla_anio_2009)*100000,2);
												 }

											 if($fechas_pobla == 2010 ){
												 $tasa_arreglo[2010] =  round(($pacientes_fech[2010] /$pobla_anio_2010)*100000,2);
												 }
												 
											if($fechas_pobla == 2011){
												 $tasa_arreglo[2011] =  round(($pacientes_fech[2011] /$pobla_anio_2011)*100000,2);
												 }												
											if($fechas_pobla == 2012){
												 $tasa_arreglo[2012] =  round(($pacientes_fech[2012] /$pobla_anio_2012)*100000,2);
												 }
											if($fechas_pobla == 2013){
												 	$tasa_arreglo[2013] =  round(($pacientes_fech[2013] /$pobla_anio_2013)*100000,2);
												 }
												 
												 
												 if($fechas_pobla == 2014){
												 $tasa_arreglo[2014] =  round(($pacientes_fech[2014] /$pobla_anio_2014)*100000,2);
												
												 }
												 
												if($fechas_pobla == 2015){
												 $tasa_arreglo[2015] =  round(($pacientes_fech[2015] /$pobla_anio_2015)*100000,2);
																							 

												 }
												 if($fechas_pobla == 2016){
												 $tasa_arreglo[2016] =  round(($pacientes_fech[2016] /$pobla_anio_2016)*100000,2);
												
												 }
												 if($fechas_pobla == 2017){
												 $tasa_arreglo[2017] =  round(($pacientes_fech[2017] /$pobla_anio_2017)*100000,2);
												
												 }
												 if($fechas_pobla == 2018){
												 $tasa_arreglo[2018] =  round(($pacientes_fech[2018] /$pobla_anio_2018)*100000,2);
												
												 }
												 if($fechas_pobla == 2019){
												 $tasa_arreglo[2019] =  round(($pacientes_fech[2019] /$pobla_anio_2019)*100000,2);
												
												 }
												 if($fechas_pobla == 2020){
												 $tasa_arreglo[2020] =  round(($pacientes_fech[2020] /$pobla_anio_2020)*100000,2);
												
												 }
												 if($fechas_pobla == 2021){
												 $tasa_arreglo[2021] =  round(($pacientes_fech[2021] /$pobla_anio_2021)*100000,2);
												
												 }
												 if($fechas_pobla == 2022){
												 $tasa_arreglo[2022] =  round(($pacientes_fech[2022] /$pobla_anio_2022)*100000,2);
												
												 }
												 if($fechas_pobla == 2023){
												 $tasa_arreglo[2023] =  round(($pacientes_fech[2023] /$pobla_anio_2023)*100000,2);
												
												 }
												 if($fechas_pobla == 2024){
												 $tasa_arreglo[2024] =  round(($pacientes_fech[2024] /$pobla_anio_2024)*100000,2);
												
												 }
												 if($fechas_pobla == 2025){
												 $tasa_arreglo[2025] =  round(($pacientes_fech[2025] /$pobla_anio_2025)*100000,2);
												
												 }
												 if($fechas_pobla == 2026){
												 $tasa_arreglo[2026] =  round(($pacientes_fech[2026] /$pobla_anio_2026)*100000,2);
												
												 }
												 if($fechas_pobla == 2027){
												 $tasa_arreglo[2027] = round(($pacientes_fech[2027] /$pobla_anio_2027)*100000,2);
												
												 }
												 if($fechas_pobla == 2028){
												 
												 $tasa_arreglo[2028] =  round(($pacientes_fech[2028] /$pobla_anio_2028)*100000,2);
												 
												 }
												 if($fechas_pobla == 2029){
												 $tasa_arreglo[2029] =  round(($pacientes_fech[2029] /$pobla_anio_2029)*100000,2);
												 
												 }
												 if($fechas_pobla == 2030){
												 $tasa_arreglo[2030] = round(($pacientes_fech[2030] /$pobla_anio_2030)*100000,2);
												 
												 }

												 												
											  
											 }
												
												
												foreach($arreglo_fecha_2 as $anio_44){	
													if($tasa_arreglo[$anio_44] == "")
														 $tasa_arreglo[$anio_44] = 0;
													else
														 $tasa_arreglo[$anio_44];
													
													
													$mostrar_tas .= $tasa_arreglo[$anio_44].",";
													$mostrar_etiquetas .= "'".$tasa_arreglo[$anio_44]."',";
											
													$mostrar_anio .= "'".$anio_44."',";														
													}	
													
													
																
											foreach($arreglo_fecha_2 as $anio_45){	
													$mostrar_tas_2 .= "'".$tasa_arreglo_2[$anio_45]."',";	
													$mostrar_anio_2 .= "'".$anio_45."',";														
											}	
											
	//-------------------------------------------------------------------------------------------------------------------------------------------------------																			
										$objHTML->startFieldset('Casos Nuevos por Años');
										echo '<div align="center"><canvas id="cvs" width="600" height="250">[No canvas support]</canvas></div>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs", [';
														
															foreach($arreglo_fecha_2 as $anio){																
																$cuantos_pacientes = intval($pacientes_fech[$anio]);
																$cadena_2 .= $cuantos_pacientes.',';
															}															

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
													
													echo'])
														.Set("gutter.bottom", 40)
														.Set("labels", [';

															foreach($arreglo_fecha_2 as $valor){
																$cadena .= '"'.$valor.'",';
															}

															echo $eliminar = substr($cadena, 0, -1);
															
														
														echo '])
														.Draw();
													
													</script>';
													

										
  
										$objHTML->endFieldset();	
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------


									$objHTML->startFieldset('Incidencia  de casos nuevos de Lepra');
									
									echo "<div align='center'><canvas id='cvs_tasa_1' width='450' height='250'>[No canvas support]</canvas><div>";
									
									echo "<script>
										 var dataset2 = ["; 
										 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
										 echo "];
								
											var line = new RGraph.Line('cvs_tasa_1', [dataset2])
												.Set('curvy', true)
												.Set('curvy.tickmarks', true)
												.Set('curvy.tickmarks.fill', null)
												.Set('curvy.tickmarks.stroke', '#aaa')
												.Set('curvy.tickmarks.stroke.linewidth', 2)
												.Set('curvy.tickmarks.size', 5)
												.Set('linewidth', 3)
												.Set('hmargin', 5)
												.Set('ymax', '0.5')
												.Set('labels', [";
												echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
												echo "])
												.Set('tooltips', [";
												
												echo $tasa_coordenadas_etiquetas =  substr($mostrar_etiquetas, 0, -1);
												
												echo "])
												.Set('tickmarks', 'circle')
											RGraph.Effects.Line.jQuery.Trace(line);
									</script>";
									/*echo "<script>
											
    

											 var bipolar = new RGraph.Bipolar('cvs_tasa_1', [";
											 foreach($arreglo_fecha_2 as $anio_8){																
																$cuantos_pacientes_8 = intval($pacientes_fech[$anio_8]);
																$cadena_8 .= $cuantos_pacientes_8.',';
															}	
															echo  $eliminar_8 = substr($cadena_8, 0, -1);	
											 
											 
											 echo "],[";
											 
											 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
											 
											 
											 echo "])
															.Set('labels', [";
															echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
															echo "])
															.Set('colors', ['blue'])
															.Set('chart.title.left','Casos Nuevos')
															.Set('chart.title.right','Casos por Taza')
															.Draw();
											</script>";
*/
											


									$objHTML->endFieldset();

																		
//-------------------------------------------------------------------------------------------------------------------------------------------------------										
										$objHTML->startFieldset('Distribución por Sexo');
										
										
										$totalPac = $pacientes_hombre + $pacientes_mujer;
										
										$porcH = round(($pacientes_hombre * 100) / $totalPac,2);
										$porcM = round(($pacientes_mujer * 100) / $totalPac,2);
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvsP1" width="400" height="250">[No canvas support]</canvas>';
								/*		echo '<script>';
										echo " var data = [".$porcH.",".$porcM."];

												var pie = new RGraph.Pie('cvsP1', data)
													.Set('labels', ['',''])
													.Set('tooltips.event', 'onmousemove')
													.Set('colors', ['blue','pink'])
													.Set('strokestyle', 'white')
													.Set('linewidth', 3)
													.Set('shadow', true)
													.Set('shadow.offsetx', 2)
													.Set('shadow.offsety', 2)
													.Set('shadow.blur', 3)
													.Set('tooltips', ['Hombres','Mujeres'])
													.Set('exploded', 7)
												
												for (var i=0; i<data.length; ++i) {
													pie.Get('labels')[i] = pie.Get('chart.labels')[i] + ', ' + data[i] + '%';
												}
												
												pie.Draw();
											</script>";*/
											
											
								echo "<script>
											var pie = new RGraph.Pie('cvsP1', [".$porcH.",".$porcM."])
											
												
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', ['Hombres','Mujeres'])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', ['".$porcH."','".$porcM."'])
												.Set('chart.colors', ['blue', 'pink'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
										
										
	//------------------------------------------------------------------------------------------------------------------------------------									
										
												echo '<canvas id="cvs_2" width="500" height="250">[No canvas support]</canvas>';
										echo '<script >
												  var bar = new RGraph.Bar("cvs_2", [';
															
															$max = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio){																
																$mujeres = intval($pacientes_fetch2[$anio]['m']);
																$hombres = intval($pacientes_fetch2[$anio]['h']);
																
																if($mujeres > $max)
																	$max = $mujeres;
																if($hombres > $max)
																	$max = $hombres;
																
																$cadena_2 .= '['.$mujeres.','.$hombres.'],';
															}												
															
															if($max <= 5)
																$max = 5;			

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
											
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor){
																$cadena4 .= '"'.$valor.'",';
															}

															echo $eliminar4 = substr($cadena4, 0, -1);
															}
												
												$grafica_sexo_anio = '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max.')
												.Set("shadow", false)
												.Set("shadow.offsetx", 0)
												.Set("shadow.offsety", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 15)
												.Set("colors", ["Gradient(pink)","Gradient(blue)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
										echo utf8_decode($grafica_sexo_anio);
										
										$objHTML->endFieldset();
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------
									
										//PRUEBAAAAAAAAAAAAAAAAAAAAAAAA
										$pacientes = array();
											$pacientes[1] = 0;
											$pacientes[2] = 0;
											$pacientes[3] = 0;
											$pacientes[4] = 0;
											$pacientes[5] = 0;
											$pacientes[6] = 0;
											$pacientes[7] = 0;
											$pacientes[8] = 0;
											$pacientes[9] = 0;
											$pacientes[10] = 0;
											$pacientes[11] = 0;
											$pacientes[12] = 0;
											$pacientes[13] = 0;

											
										$ejecuta_edad = ejecutaQuery($QueryEdad);									
										while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										
											if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){
											
											
											
												$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));										
												$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
												$edad_2 = CalculaEdad($valor_calcular);
												

											
																					
											if($edad_2 < 15){	
												$pacientes[1]++;}
											if($edad_2 >= 15 && $edad_2 <= 19){
												$pacientes[2]++;}
											if($edad_2 >= 20 && $edad_2 <= 24){
												$pacientes[3]++;}
											if($edad_2 >= 25 && $edad_2 <= 29){
												$pacientes[4] ++;}
											if($edad_2 >= 30 && $edad_2 <= 34){
												$pacientes[5]++;}
											if($edad_2 >= 35 && $edad_2 <= 39){
												$pacientes[6]++;}
											if($edad_2 >= 40 && $edad_2 <= 44){
												$pacientes[7]++;}
											if($edad_2 >= 50 && $edad_2 <= 54){
												$pacientes[8]++;}
											if($edad_2 >= 55 && $edad_2 <= 59){
												$pacientes[9]++;}
											if($edad_2 >= 60 && $edad_2 <= 64){
												$pacientes[10]++;}
											if($edad_2 >= 65 && $edad_2 <= 69){
												$pacientes[11]++;}
											if($edad_2 >= 70 && $edad_2 <= 74){
												$pacientes[12]++;}
											if($edad_2 > 75){
												$pacientes[13]++;}

										}
									}
									
									$suma_total= 0;
									foreach($pacientes as $suma_paci){
											$suma_total = $suma_total + $suma_paci;
										}
									
								
									
									
									echo "<br/>";
									foreach($pacientes as $key_paci => $cantidad){																
																$cuantos_pacientes_4 = intval($cantidad);
																if($cuantos_pacientes_4 != 0){
																$cadena_4 .= $cuantos_pacientes_4.',';
																$cadena_key .= $key_paci.',';
																$porcentaje_separado = round(($cuantos_pacientes_4 * 100) / $suma_total,2);
																$porcentaje .= '"'.$porcentaje_separado.'%",';
																}
															}															
														
														$porce_etiqueta = substr($porcentaje, 0, -1);
													    $eliminar_key = substr($cadena_key, 0, -1);
														$separar = explode(",",$eliminar_key);
														$contar_arreglo = count($separar);
														for($w=0;$w<=$contar_arreglo;$w++){
																	if($separar[$w] == 1 )
																$separar[$w] = "<15"; 
															if($separar[$w] == 2)
																$separar[$w] = "15-19"; 
															if($separar[$w] == 3)
																$separar[$w] = "20-24"; 
															if($separar[$w] == 4)
																$separar[$w] = "25-29"; 
															if($separar[$w] == 5)
																$separar[$w] = "30-34"; 
															if($separar[$w] == 6)
																$separar[$w] = "35-39"; 
															if($separar[$w] == 7)
																$separar[$w] = "40-44"; 
															if($separar[$w] == 8)
																$separar[$w] = "50-54"; 
															if($separar[$w] == 9)
																$separar[$w] = "55-59"; 
															if($separar[$w] == 10)
																$separar[$w] = "60-64"; 
															if($separar[$w] == 11)
																$separar[$w] = "65-69"; 
															if($separar[$w] == 12)
																$separar[$w] = "70-75"; 
															if($separar[$w] == 13)
																$separar[$w] = ">76"; 
															
														
															
															}
															
															
															foreach($separar as $etiquetas){
																$etiquetas_2 .= "'".$etiquetas."',";
																}
														
														 
														
														
									$objHTML->startFieldset('Grupo de Edad más Afectado');
									
									echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td>
									<canvas id="cvs_rango_edad_2" width="330" height="250" !style="border:1px solid #ccc">[No canvas support]</canvas>';
									
							echo "<script>var pie = new RGraph.Pie('cvs_rango_edad_2', ["; 
																					
										 echo $eliminar_4 = substr($cadena_4, 0, -1);
													

											echo"])
												.Set('strokestyle', '#e8e8e8')
												.Set('linewidth', 5)
												.Set('shadow', true)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('shadow.color', '#aaa')
												.Set('exploded', 10)
												.Set('radius', 70)
												.Set('tooltips', [";
												echo $porce_etiqueta;
												echo "])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [";
												echo $etiquetas_3 = substr($etiquetas_2, 0, -1);
												echo "])
												.Set('labels.sticks', true)
												.Set('labels.sticks.length', 15);
												
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td>";
									
													
									
										
											echo '<td><canvas id="cvs_4" width="600" height="250">[No canvas support]</canvas>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs_4", [';
														foreach($pacientes as $cantidad_2){																
																$cuantos_pacientes_5 = intval($cantidad_2);
																$cadena_5 .= $cuantos_pacientes_5.',';
															}															

														echo  $eliminar_5 = substr($cadena_5, 0, -1);
						
													
													echo'])
														.Set("ymax", 5)
														.Set("gutter.bottom", 40)
														.Set("labels", ["< 15","15-19","20-24","25-29","30-34","35-39","40-44","50-54","55-59","60-64","65-69","70-75",">76"])
														.Draw();
													
													</script></td></tr></table>';
										$objHTML->endFieldset();
	
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------									
						//ultimaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa		
									
						 	$QueryLepra = "SELECT p.* ,diag.*, tipo_le.* FROM catJurisdiccion j, pacientes p, catUnidad u, catMunicipio m, diagnostico diag, catClasificacionLepra tipo_le WHERE p.idCatUnidadTratante = u.idCatUnidad AND m.idCatJurisdiccion = j.idCatJurisdiccion AND m.idCatEstado = j.idCatEstado AND u.idcatEstado = ".$_POST['edoReporteEdad']." AND u.idCatMunicipio = m.idCatMunicipio AND m.idCatEstado = u.idcatEstado AND diag.idPaciente = p.idPaciente AND tipo_le.idCatClasificacionLepra = diag.idCatClasificacionLepra  AND fechaDiagnostico BETWEEN'".$fecha_consu_inicio."' AND '".$fecha_consu_fin."' ORDER BY fechaDiagnostico";
									
									
									$ejecuda_lepra = ejecutaQuery($QueryLepra);
									$numero_registros_segundo = devuelveNumRows( $ejecuda_lepra);
									
									while($resultado_tipo_lepra = devuelveRowAssoc($ejecuda_lepra)){
										

										$fecha_nac = explode("-",formatFechaObj($resultado_tipo_lepra['fechaNacimiento']));										
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										$edad_2 = CalculaEdad($valor_calcular);
										$resultado_tipo_lepra['idCatClasificacionLepra'];
										//echo '<br>';
										//if($edad_2 > 25 && $edad_2 < 36){
										if(true){
											
											//if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											if(true){
											
												$fecha_diag_2 = explode("-",formatFechaObj($resultado_tipo_lepra['fechaDiagnostico']));		
												
													$anio_2 = $fecha_diag_2[2];		
													
																									
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 1){
														$pacientes_tipo_lepra_lepromatosa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 2){
														$pacientes_tipo_lepra_dimorfo++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['dimorfo']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 3){
														$pacientes_tipo_lepra_tuberculoide++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['tuberculoide']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 4){
														$pacientes_tipo_lepra_indeterminado++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['indeterminado']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 5){
														$pacientes_tipo_lepra_lepromatosa_difusa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa_difusa']++;
													}	
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
										} // fin if edad
									}		//fin while
										
																				
										$objHTML->startFieldset('Por su Clasificación');
										

										
										$totalLepra = $pacientes_tipo_lepra_lepromatosa + $pacientes_tipo_lepra_dimorfo + $pacientes_tipo_lepra_tuberculoide + $pacientes_tipo_lepra_indeterminado +$pacientes_tipo_lepra_lepromatosa_difusa;
										
										$porclepro = ($pacientes_tipo_lepra_lepromatosa * 100) / $totalLepra;
										$porcdimorfo = ($pacientes_tipo_lepra_dimorfo * 100) / $totalLepra;
										$porctubercu = ($pacientes_tipo_lepra_tuberculoide * 100) / $totalLepra;
										$porcindeter = ($pacientes_tipo_lepra_indeterminado * 100) / $totalLepra;
										$porcdifusa = ($pacientes_tipo_lepra_lepromatosa_difusa * 100) / $totalLepra;
										
										
										$porclepro = number_format($porclepro,2,'.','');
										$porcdimorfo = number_format($porcdimorfo,2,'.','');
										$porctubercu = number_format($porctubercu,2,'.','');
										$porcindeter = number_format($porcindeter,2,'.','');
										$porcdifusa = number_format($porcdifusa,2,'.','');
																				
										$data_x = '';
										$color_x = '';
										$label_x = '';
										$label_num = '';
										
										if($porclepro > 0){
											$data_x .= $porclepro.',';
											$color_x .= "'Gradient(pink:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Lepromatosa Nodular (MB)',";
											$label_num .= "'".$porclepro."%',";
										}
										if($porcdimorfo > 0){
											$data_x .= $porcdimorfo.',';
											$color_x .= "'Gradient(blue:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Dimorfo (MB)',";
											$label_num .= "'".$porcdimorfo."%',";
										}
										if($porctubercu > 0){
											$data_x .= $porctubercu.',';
											$color_x .= "'Gradient(red:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Tuberculoide (PB)',";
											$label_num .= "'".$porctubercu."%',";
										}
										if($porcindeter > 0){
											$data_x .= $porcindeter.',';
											$color_x .= "'Gradient(green:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Indeterminado (PB)',";
											$label_num .= "'".$porcindeter."%',";
										}
										if($porcdifusa > 0){
											$data_x .= $porcdifusa.',';
											$color_x .= "'Gradient(purple:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Lepra Lepromatosa Difusa (MB)',";
											$label_num .= "'".$porcdifusa."%',";
										}
										
										$data_x = substr($data_x, 0, -1);
										$color_x = substr($color_x, 0, -1);
										$label_x = substr($label_x, 0, -1);
										$label_num = substr($label_num, 0, -1);
										
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvspor_lepra" width="325" height="250">[No canvas support]</canvas>';
										
										
										echo "<script>
											var pie = new RGraph.Pie('cvspor_lepra', [".$data_x."])
											
														
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', [".$label_x."])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [".$label_num."])
												//.Set('chart.colors', ['red', 'blue'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
									
											
											
											
											echo '<canvas id="cvs_7" width="600" height="250">[No canvas support]</canvas>';
											echo '<script >
												  var bar = new RGraph.Bar("cvs_7", [';
															
															$max_3 = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio_6){						
																									
																$lepromatosa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa']);
																$dimorfo = intval($pacientes_tipo_lepra[$anio_6]['dimorfo']);
																$tuberculoide = intval($pacientes_tipo_lepra[$anio_6]['tuberculoide']);
																$indeterminado = intval($pacientes_tipo_lepra[$anio_6]['indeterminado']);
																$lepromatosa_difusa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa_difusa']);
																
																if($lepromatosa > $max_3)
																	$max_3 = $lepromatosa;
																if($dimorfo > $max_3)
																	$max_3 = $dimorfo;
																if($tuberculoide > $max_3)
																	$max_3 = $tuberculoide;
																if($indeterminado > $max_3)
																	$max_3 = $indeterminado;
																if($lepromatosa_difusa > $max_3)
																	$max_3 = $lepromatosa_difusa;
																	
																
																$cadena_lepra .= '['.$lepromatosa.','.$dimorfo.','.$tuberculoide.','.$indeterminado.','.$lepromatosa_difusa.'],';
																//$cadena_lepra .= '[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,62]';
															}												
															
															if($max_3 <= 5)
																$max_3 = 5;			

														echo  $parametros_lepra = substr($cadena_lepra, 0, -1);
													//echo '[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4]';
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor_3){
																$cadena_3 .= '"'.$valor_3.'",';
															}

															echo $eliminar_cad = substr($cadena_3, 0, -1);
															}
												
												echo '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max_3.')
											
											
												.Set("shadow", false)
												.Set("shadow.offsetx", 10)
												.Set("shadow.offsety", 0)
												.Set("shadow.blur", 40)
												.Set("hmargin.grouped", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 45)
												.Set("colors", ["Gradient(pink)","Gradient(blue)","Gradient(red)","Gradient(green)","Gradient(purple)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
												
										$objHTML->endFieldset();
										
										
										}
										
									
									}
									break;
								case '6': {
											
																		$por_anio = "Por Año";
									if($numero_registros_primero == 0 && $numero_registros_segundo == 0)
										echo '<br><br><h3 align="center">No se encontraron resultados en la busqueda</h3>';
			
										else{
										
										
										if($resultado == 0){										
										$arreglo_fecha_2[0] = $sacar_anio_inicio[2];
										}
										else{
											$t =0; 
											for($x=$sacar_anio_inicio[2];$x<=$sacar_anio_fin[2];$x++){
												$arreglo_fecha_2[$t++] = $x;
												}
											
											}
									$e = 0;		
									$t = 0;
									$r = 0;
									$pacientes_fech = array();
									while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										//echo "<br/>".$resultado_edad['nombre']."---";
									    $edad_2 = CalculaEdad($valor_calcular);
																
										// condicion edad
										//if($edad_2< 10){
											//if($edad_2 > 9 && $edad_2 < 16){

											//if($edad_2 > 15 && $edad_2 < 26){
											
											//if($edad_2 > 25 && $edad_2 < 36){
												
											//if($edad_2 > 35 && $edad_2 < 46){
												
											if($edad_2 > 45){
											
												if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											//if(true){
											
											
											
										
										
												$fecha_diag = explode("-",formatFechaObj($resultado_edad['fechaDiagnostico']));		
												
													$anio = $fecha_diag[2];		
													
													$pacientes_fech[$anio]++;										
													
													if($resultado_edad['sexo'] == 1){
														$pacientes_hombre++;
														$pacientes_fetch2[$anio]['h']++;
													}else{
														$pacientes_fetch2[$anio]['m']++;
														$pacientes_mujer++;
													}
														
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
											
											//fin condicion edad
											 }
											
										}
											
										
										$pobla_anio_1990 = "3326140";
										$pobla_anio_1991 = "3398566";
										$pobla_anio_1992 = "3471236";
										$pobla_anio_1993 = "3543231";
										$pobla_anio_1994 = "3613850";
										$pobla_anio_1995 = "3683005";
										$pobla_anio_1996 = "3750840";
										$pobla_anio_1997 = "3817152";
										$pobla_anio_1998 = "3882823";
										$pobla_anio_1999 = "3949837";
										$pobla_anio_2000 = "4018049";
										$pobla_anio_2001 = "4085008";
										$pobla_anio_2002 = "4148101";
										$pobla_anio_2003 = "4206345";
										$pobla_anio_2004 = "4260523";
										$pobla_anio_2005 = "4312067";
										$pobla_anio_2006 = "4362413";
										$pobla_anio_2007 = "4411808";
										$pobla_anio_2008 = "4460013";
										$pobla_anio_2009 = "4507177";
										$pobla_anio_2010 = "4903700";
										$pobla_anio_2011 = "4978849";
										$pobla_anio_2012 = "5048184";
										$pobla_anio_2013 = "5116489";
										$pobla_anio_2014 = "5183827";
										$pobla_anio_2015 = "5250306";
										$pobla_anio_2016 = "5315921";
										$pobla_anio_2017 = "5380769";
										$pobla_anio_2018 = "5444910";
										$pobla_anio_2019 = "5508320";
										$pobla_anio_2020 = "5571476";
										$pobla_anio_2021 = "5633555";
										$pobla_anio_2022 = "5693619";
										$pobla_anio_2023 = "5752024";
										$pobla_anio_2024 = "5808596";
										$pobla_anio_2025 = "5863237";
										$pobla_anio_2026 = "5915724";
										$pobla_anio_2027 = "5965872";
										$pobla_anio_2028 = "6013569";
										$pobla_anio_2029 = "6058553";
										$pobla_anio_2030 = "6099290";
									
											

											
											
											
										
										foreach($arreglo_fecha_2 as $fechas_pobla){
											
											
											if($fechas_pobla == 1990 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1990] =  round(($pacientes_fech[1990] /$pobla_anio_1990)*100000,2);
												 }
											if($fechas_pobla == 1991 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1991] =  round(($pacientes_fech[1991] /$pobla_anio_1991)*100000,2);
												 }
											
											if($fechas_pobla == 1992 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1992] =  round(($pacientes_fech[1992] /$pobla_anio_1992)*100000,2);
												 }
											
											if($fechas_pobla == 1993 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1993] =  round(($pacientes_fech[1993] /$pobla_anio_1993)*100000,2);
												 }
											
											if($fechas_pobla == 1994 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1994] =  round(($pacientes_fech[1994] /$pobla_anio_1994)*100000,2);
												 }
											
											if($fechas_pobla == 1995 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1995] =  round(($pacientes_fech[1995] /$pobla_anio_1995)*100000,2);
												 }
											
											if($fechas_pobla == 1996 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1996] =  round(($pacientes_fech[1996] /$pobla_anio_1996)*100000,2);
												 }
											
											if($fechas_pobla == 1997 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1997] =  round(($pacientes_fech[1997] /$pobla_anio_1997)*100000,2);
												 }
											
											if($fechas_pobla == 1998 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1998] =  round(($pacientes_fech[1998] /$pobla_anio_1998)*100000,2);
												 }
											
											if($fechas_pobla == 1999 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[1999] =  round(($pacientes_fech[1999] /$pobla_anio_1999)*100000,2);
												 }
											
											if($fechas_pobla == 2000 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2000] =  round(($pacientes_fech[2000] /$pobla_anio_2000)*100000,2);
												 }
											
											if($fechas_pobla == 2001 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2001] =  round(($pacientes_fech[2001] /$pobla_anio_2001)*100000,2);
												 }
											
											if($fechas_pobla == 2002 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2002] =  round(($pacientes_fech[2002] /$pobla_anio_2002)*100000,2);
												 }
											
											if($fechas_pobla == 2003 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2003] =  round(($pacientes_fech[2003] /$pobla_anio_2003)*100000,2);
												 }
											
											if($fechas_pobla == 2004 && $_POST['edoReporteEdad'] == 7){ 
												 $tasa_arreglo[2004] =  round(($pacientes_fech[2004] /$pobla_anio_2004)*100000,2);
												 }
											
											if($fechas_pobla == 2005 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2005] =  round(($pacientes_fech[2005] /$pobla_anio_2005)*100000,2);
												 }
											
											if($fechas_pobla == 2006 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2006] =  round(($pacientes_fech[2006] /$pobla_anio_2006)*100000,2);
												 }
											
											if($fechas_pobla == 2007 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2007] =  round(($pacientes_fech[2007] /$pobla_anio_2007)*100000,2);
												 }
											
											if($fechas_pobla == 2008 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2008] =  round(($pacientes_fech[2008] /$pobla_anio_2008)*100000,2);
												 }
											
											if($fechas_pobla == 2009 && $_POST['edoReporteEdad'] == 7){
												 $tasa_arreglo[2009] =  round(($pacientes_fech[2009] /$pobla_anio_2009)*100000,2);
												 }

											 if($fechas_pobla == 2010 ){
												 $tasa_arreglo[2010] =  round(($pacientes_fech[2010] /$pobla_anio_2010)*100000,2);
												 }
												 
											if($fechas_pobla == 2011){
												 $tasa_arreglo[2011] =  round(($pacientes_fech[2011] /$pobla_anio_2011)*100000,2);
												 }												
											if($fechas_pobla == 2012){
												 $tasa_arreglo[2012] =  round(($pacientes_fech[2012] /$pobla_anio_2012)*100000,2);
												 }
											if($fechas_pobla == 2013){
												 	$tasa_arreglo[2013] =  round(($pacientes_fech[2013] /$pobla_anio_2013)*100000,2);
												 }
												 
												 
												 if($fechas_pobla == 2014){
												 $tasa_arreglo[2014] =  round(($pacientes_fech[2014] /$pobla_anio_2014)*100000,2);
												
												 }
												 
												if($fechas_pobla == 2015){
												 $tasa_arreglo[2015] =  round(($pacientes_fech[2015] /$pobla_anio_2015)*100000,2);
																							 

												 }
												 if($fechas_pobla == 2016){
												 $tasa_arreglo[2016] =  round(($pacientes_fech[2016] /$pobla_anio_2016)*100000,2);
												
												 }
												 if($fechas_pobla == 2017){
												 $tasa_arreglo[2017] =  round(($pacientes_fech[2017] /$pobla_anio_2017)*100000,2);
												
												 }
												 if($fechas_pobla == 2018){
												 $tasa_arreglo[2018] =  round(($pacientes_fech[2018] /$pobla_anio_2018)*100000,2);
												
												 }
												 if($fechas_pobla == 2019){
												 $tasa_arreglo[2019] =  round(($pacientes_fech[2019] /$pobla_anio_2019)*100000,2);
												
												 }
												 if($fechas_pobla == 2020){
												 $tasa_arreglo[2020] =  round(($pacientes_fech[2020] /$pobla_anio_2020)*100000,2);
												
												 }
												 if($fechas_pobla == 2021){
												 $tasa_arreglo[2021] =  round(($pacientes_fech[2021] /$pobla_anio_2021)*100000,2);
												
												 }
												 if($fechas_pobla == 2022){
												 $tasa_arreglo[2022] =  round(($pacientes_fech[2022] /$pobla_anio_2022)*100000,2);
												
												 }
												 if($fechas_pobla == 2023){
												 $tasa_arreglo[2023] =  round(($pacientes_fech[2023] /$pobla_anio_2023)*100000,2);
												
												 }
												 if($fechas_pobla == 2024){
												 $tasa_arreglo[2024] =  round(($pacientes_fech[2024] /$pobla_anio_2024)*100000,2);
												
												 }
												 if($fechas_pobla == 2025){
												 $tasa_arreglo[2025] =  round(($pacientes_fech[2025] /$pobla_anio_2025)*100000,2);
												
												 }
												 if($fechas_pobla == 2026){
												 $tasa_arreglo[2026] =  round(($pacientes_fech[2026] /$pobla_anio_2026)*100000,2);
												
												 }
												 if($fechas_pobla == 2027){
												 $tasa_arreglo[2027] = round(($pacientes_fech[2027] /$pobla_anio_2027)*100000,2);
												
												 }
												 if($fechas_pobla == 2028){
												 
												 $tasa_arreglo[2028] =  round(($pacientes_fech[2028] /$pobla_anio_2028)*100000,2);
												 
												 }
												 if($fechas_pobla == 2029){
												 $tasa_arreglo[2029] =  round(($pacientes_fech[2029] /$pobla_anio_2029)*100000,2);
												 
												 }
												 if($fechas_pobla == 2030){
												 $tasa_arreglo[2030] = round(($pacientes_fech[2030] /$pobla_anio_2030)*100000,2);
												 
												 }

												 												
											  
											 }
												
												
												foreach($arreglo_fecha_2 as $anio_44){	
													if($tasa_arreglo[$anio_44] == "")
														 $tasa_arreglo[$anio_44] = 0;
													else
														 $tasa_arreglo[$anio_44];
													
													
													$mostrar_tas .= $tasa_arreglo[$anio_44].",";
													$mostrar_etiquetas .= "'".$tasa_arreglo[$anio_44]."',";
											
													$mostrar_anio .= "'".$anio_44."',";														
													}	
													
													
																
											foreach($arreglo_fecha_2 as $anio_45){	
													$mostrar_tas_2 .= "'".$tasa_arreglo_2[$anio_45]."',";	
													$mostrar_anio_2 .= "'".$anio_45."',";														
											}	
											
	//-------------------------------------------------------------------------------------------------------------------------------------------------------																			
										$objHTML->startFieldset('Casos Nuevos por Años');
										echo '<div align="center"><canvas id="cvs" width="600" height="250">[No canvas support]</canvas></div>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs", [';
														
															foreach($arreglo_fecha_2 as $anio){																
																$cuantos_pacientes = intval($pacientes_fech[$anio]);
																$cadena_2 .= $cuantos_pacientes.',';
															}															

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
													
													echo'])
														.Set("gutter.bottom", 40)
														.Set("labels", [';

															foreach($arreglo_fecha_2 as $valor){
																$cadena .= '"'.$valor.'",';
															}

															echo $eliminar = substr($cadena, 0, -1);
															
														
														echo '])
														.Draw();
													
													</script>';
													

										
  
										$objHTML->endFieldset();	
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------


									$objHTML->startFieldset('Incidencia  de casos nuevos de Lepra');
									
									echo "<div align='center'><canvas id='cvs_tasa_1' width='450' height='250'>[No canvas support]</canvas><div>";
									
									echo "<script>
										 var dataset2 = ["; 
										 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
										 echo "];
								
											var line = new RGraph.Line('cvs_tasa_1', [dataset2])
												.Set('curvy', true)
												.Set('curvy.tickmarks', true)
												.Set('curvy.tickmarks.fill', null)
												.Set('curvy.tickmarks.stroke', '#aaa')
												.Set('curvy.tickmarks.stroke.linewidth', 2)
												.Set('curvy.tickmarks.size', 5)
												.Set('linewidth', 3)
												.Set('hmargin', 5)
												.Set('ymax', '0.5')
												.Set('labels', [";
												echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
												echo "])
												.Set('tooltips', [";
												
												echo $tasa_coordenadas_etiquetas =  substr($mostrar_etiquetas, 0, -1);
												
												echo "])
												.Set('tickmarks', 'circle')
											RGraph.Effects.Line.jQuery.Trace(line);
									</script>";
									/*echo "<script>
											
    

											 var bipolar = new RGraph.Bipolar('cvs_tasa_1', [";
											 foreach($arreglo_fecha_2 as $anio_8){																
																$cuantos_pacientes_8 = intval($pacientes_fech[$anio_8]);
																$cadena_8 .= $cuantos_pacientes_8.',';
															}	
															echo  $eliminar_8 = substr($cadena_8, 0, -1);	
											 
											 
											 echo "],[";
											 
											 echo $tasa_coordenadas =  substr($mostrar_tas, 0, -1);
											 
											 
											 echo "])
															.Set('labels', [";
															echo $tasa_coordenadas_anios = substr($mostrar_anio, 0, -1);
															echo "])
															.Set('colors', ['blue'])
															.Set('chart.title.left','Casos Nuevos')
															.Set('chart.title.right','Casos por Taza')
															.Draw();
											</script>";
*/
											


									$objHTML->endFieldset();

																		
//-------------------------------------------------------------------------------------------------------------------------------------------------------										
										$objHTML->startFieldset('Distribución por Sexo');
										
										
										$totalPac = $pacientes_hombre + $pacientes_mujer;
										
										$porcH = round(($pacientes_hombre * 100) / $totalPac,2);
										$porcM = round(($pacientes_mujer * 100) / $totalPac,2);
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvsP1" width="400" height="250">[No canvas support]</canvas>';
								/*		echo '<script>';
										echo " var data = [".$porcH.",".$porcM."];

												var pie = new RGraph.Pie('cvsP1', data)
													.Set('labels', ['',''])
													.Set('tooltips.event', 'onmousemove')
													.Set('colors', ['blue','pink'])
													.Set('strokestyle', 'white')
													.Set('linewidth', 3)
													.Set('shadow', true)
													.Set('shadow.offsetx', 2)
													.Set('shadow.offsety', 2)
													.Set('shadow.blur', 3)
													.Set('tooltips', ['Hombres','Mujeres'])
													.Set('exploded', 7)
												
												for (var i=0; i<data.length; ++i) {
													pie.Get('labels')[i] = pie.Get('chart.labels')[i] + ', ' + data[i] + '%';
												}
												
												pie.Draw();
											</script>";*/
											
											
								echo "<script>
											var pie = new RGraph.Pie('cvsP1', [".$porcH.",".$porcM."])
											
												
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', ['Hombres','Mujeres'])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', ['".$porcH."','".$porcM."'])
												.Set('chart.colors', ['blue', 'pink'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
										
										
	//------------------------------------------------------------------------------------------------------------------------------------									
										
												echo '<canvas id="cvs_2" width="500" height="250">[No canvas support]</canvas>';
										echo '<script >
												  var bar = new RGraph.Bar("cvs_2", [';
															
															$max = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio){																
																$mujeres = intval($pacientes_fetch2[$anio]['m']);
																$hombres = intval($pacientes_fetch2[$anio]['h']);
																
																if($mujeres > $max)
																	$max = $mujeres;
																if($hombres > $max)
																	$max = $hombres;
																
																$cadena_2 .= '['.$mujeres.','.$hombres.'],';
															}												
															
															if($max <= 5)
																$max = 5;			

														echo  $eliminar_2 = substr($cadena_2, 0, -1);
											
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor){
																$cadena4 .= '"'.$valor.'",';
															}

															echo $eliminar4 = substr($cadena4, 0, -1);
															}
												
												$grafica_sexo_anio = '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max.')
												.Set("shadow", false)
												.Set("shadow.offsetx", 0)
												.Set("shadow.offsety", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 15)
												.Set("colors", ["Gradient(pink)","Gradient(blue)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
										echo utf8_decode($grafica_sexo_anio);
										
										$objHTML->endFieldset();
										
//-------------------------------------------------------------------------------------------------------------------------------------------------------
									
										//PRUEBAAAAAAAAAAAAAAAAAAAAAAAA
										$pacientes = array();
											$pacientes[1] = 0;
											$pacientes[2] = 0;
											$pacientes[3] = 0;
											$pacientes[4] = 0;
											$pacientes[5] = 0;
											$pacientes[6] = 0;
											$pacientes[7] = 0;
											$pacientes[8] = 0;
											$pacientes[9] = 0;
											$pacientes[10] = 0;
											$pacientes[11] = 0;
											$pacientes[12] = 0;
											$pacientes[13] = 0;

											
										$ejecuta_edad = ejecutaQuery($QueryEdad);									
										while($resultado_edad = devuelveRowAssoc($ejecuta_edad)){
										
											if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){
											
											
											
												$fecha_nac = explode("-",formatFechaObj($resultado_edad['fechaNacimiento']));										
												$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
												$edad_2 = CalculaEdad($valor_calcular);
												

											
																					
											if($edad_2 < 15){	
												$pacientes[1]++;}
											if($edad_2 >= 15 && $edad_2 <= 19){
												$pacientes[2]++;}
											if($edad_2 >= 20 && $edad_2 <= 24){
												$pacientes[3]++;}
											if($edad_2 >= 25 && $edad_2 <= 29){
												$pacientes[4] ++;}
											if($edad_2 >= 30 && $edad_2 <= 34){
												$pacientes[5]++;}
											if($edad_2 >= 35 && $edad_2 <= 39){
												$pacientes[6]++;}
											if($edad_2 >= 40 && $edad_2 <= 44){
												$pacientes[7]++;}
											if($edad_2 >= 50 && $edad_2 <= 54){
												$pacientes[8]++;}
											if($edad_2 >= 55 && $edad_2 <= 59){
												$pacientes[9]++;}
											if($edad_2 >= 60 && $edad_2 <= 64){
												$pacientes[10]++;}
											if($edad_2 >= 65 && $edad_2 <= 69){
												$pacientes[11]++;}
											if($edad_2 >= 70 && $edad_2 <= 74){
												$pacientes[12]++;}
											if($edad_2 > 75){
												$pacientes[13]++;}

										}
									}
									
									$suma_total= 0;
									foreach($pacientes as $suma_paci){
											$suma_total = $suma_total + $suma_paci;
										}
									
								
									
									
									echo "<br/>";
									foreach($pacientes as $key_paci => $cantidad){																
																$cuantos_pacientes_4 = intval($cantidad);
																if($cuantos_pacientes_4 != 0){
																$cadena_4 .= $cuantos_pacientes_4.',';
																$cadena_key .= $key_paci.',';
																$porcentaje_separado = round(($cuantos_pacientes_4 * 100) / $suma_total,2);
																$porcentaje .= '"'.$porcentaje_separado.'%",';
																}
															}															
														
														$porce_etiqueta = substr($porcentaje, 0, -1);
													    $eliminar_key = substr($cadena_key, 0, -1);
														$separar = explode(",",$eliminar_key);
														$contar_arreglo = count($separar);
														for($w=0;$w<=$contar_arreglo;$w++){
																	if($separar[$w] == 1 )
																$separar[$w] = "<15"; 
															if($separar[$w] == 2)
																$separar[$w] = "15-19"; 
															if($separar[$w] == 3)
																$separar[$w] = "20-24"; 
															if($separar[$w] == 4)
																$separar[$w] = "25-29"; 
															if($separar[$w] == 5)
																$separar[$w] = "30-34"; 
															if($separar[$w] == 6)
																$separar[$w] = "35-39"; 
															if($separar[$w] == 7)
																$separar[$w] = "40-44"; 
															if($separar[$w] == 8)
																$separar[$w] = "50-54"; 
															if($separar[$w] == 9)
																$separar[$w] = "55-59"; 
															if($separar[$w] == 10)
																$separar[$w] = "60-64"; 
															if($separar[$w] == 11)
																$separar[$w] = "65-69"; 
															if($separar[$w] == 12)
																$separar[$w] = "70-75"; 
															if($separar[$w] == 13)
																$separar[$w] = ">76"; 
															
														
															
															}
															
															
															foreach($separar as $etiquetas){
																$etiquetas_2 .= "'".$etiquetas."',";
																}
														
														 
														
														
									$objHTML->startFieldset('Grupo de Edad más Afectado');
									
									echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td>
									<canvas id="cvs_rango_edad_2" width="330" height="250" !style="border:1px solid #ccc">[No canvas support]</canvas>';
									
							echo "<script>var pie = new RGraph.Pie('cvs_rango_edad_2', ["; 
																					
										 echo $eliminar_4 = substr($cadena_4, 0, -1);
													

											echo"])
												.Set('strokestyle', '#e8e8e8')
												.Set('linewidth', 5)
												.Set('shadow', true)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('shadow.color', '#aaa')
												.Set('exploded', 10)
												.Set('radius', 70)
												.Set('tooltips', [";
												echo $porce_etiqueta;
												echo "])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [";
												echo $etiquetas_3 = substr($etiquetas_2, 0, -1);
												echo "])
												.Set('labels.sticks', true)
												.Set('labels.sticks.length', 15);
												
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td>";
									
													
									
										
											echo '<td><canvas id="cvs_4" width="600" height="250">[No canvas support]</canvas>';
										echo '<script >
												  function MovePie (obj)
													{
														setTimeout(function () {RGraph.Effects.Animate(obj, {"chart.radius": 30, "chart.centerx": 60, "chart.centery": 60,"frames": 5});},  500);
													}
										
													var bar = new RGraph.Bar("cvs_4", [';
														foreach($pacientes as $cantidad_2){																
																$cuantos_pacientes_5 = intval($cantidad_2);
																$cadena_5 .= $cuantos_pacientes_5.',';
															}															

														echo  $eliminar_5 = substr($cadena_5, 0, -1);
						
													
													echo'])
														.Set("ymax", 5)
														.Set("gutter.bottom", 40)
														.Set("labels", ["< 15","15-19","20-24","25-29","30-34","35-39","40-44","50-54","55-59","60-64","65-69","70-75",">76"])
														.Draw();
													
													</script></td></tr></table>';
										$objHTML->endFieldset();
	
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------									
						//ultimaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa		
									
						 	$QueryLepra = "SELECT p.* ,diag.*, tipo_le.* FROM catJurisdiccion j, pacientes p, catUnidad u, catMunicipio m, diagnostico diag, catClasificacionLepra tipo_le WHERE p.idCatUnidadTratante = u.idCatUnidad AND m.idCatJurisdiccion = j.idCatJurisdiccion AND m.idCatEstado = j.idCatEstado AND u.idcatEstado = ".$_POST['edoReporteEdad']." AND u.idCatMunicipio = m.idCatMunicipio AND m.idCatEstado = u.idcatEstado AND diag.idPaciente = p.idPaciente AND tipo_le.idCatClasificacionLepra = diag.idCatClasificacionLepra  AND fechaDiagnostico BETWEEN'".$fecha_consu_inicio."' AND '".$fecha_consu_fin."' ORDER BY fechaDiagnostico";
									
									
									$ejecuda_lepra = ejecutaQuery($QueryLepra);
									$numero_registros_segundo = devuelveNumRows( $ejecuda_lepra);
									
									while($resultado_tipo_lepra = devuelveRowAssoc($ejecuda_lepra)){
										

										$fecha_nac = explode("-",formatFechaObj($resultado_tipo_lepra['fechaNacimiento']));										
										$valor_calcular = $fecha_nac[0]."-".$fecha_nac[1]."-".$fecha_nac[2];
										$edad_2 = CalculaEdad($valor_calcular);
										$resultado_tipo_lepra['idCatClasificacionLepra'];
										//echo '<br>';
										//if($edad_2 > 25 && $edad_2 < 36){
										if(true){
											
											//if($resultado_edad['idCatTipoPaciente'] != 5 && $resultado_edad['idCatTipoPaciente'] != 6){																																																							
											if(true){
											
												$fecha_diag_2 = explode("-",formatFechaObj($resultado_tipo_lepra['fechaDiagnostico']));		
												
													$anio_2 = $fecha_diag_2[2];		
													
																									
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 1){
														$pacientes_tipo_lepra_lepromatosa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 2){
														$pacientes_tipo_lepra_dimorfo++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['dimorfo']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 3){
														$pacientes_tipo_lepra_tuberculoide++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['tuberculoide']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 4){
														$pacientes_tipo_lepra_indeterminado++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['indeterminado']++;
													}
													if($resultado_tipo_lepra['idCatClasificacionLepra'] == 5){
														$pacientes_tipo_lepra_lepromatosa_difusa++;
														//$pacientes_fetch2[$anio]['h']++;
														$pacientes_tipo_lepra[$anio_2]['lepromatosa_difusa']++;
													}	
																				
													
													 //echo $resultado_edad['nombre']."<br />";
											} //fin if tipo paciente
										} // fin if edad
									}		//fin while
										
																				
										$objHTML->startFieldset('Por su Clasificación');
										

										
										$totalLepra = $pacientes_tipo_lepra_lepromatosa + $pacientes_tipo_lepra_dimorfo + $pacientes_tipo_lepra_tuberculoide + $pacientes_tipo_lepra_indeterminado +$pacientes_tipo_lepra_lepromatosa_difusa;
										
										$porclepro = ($pacientes_tipo_lepra_lepromatosa * 100) / $totalLepra;
										$porcdimorfo = ($pacientes_tipo_lepra_dimorfo * 100) / $totalLepra;
										$porctubercu = ($pacientes_tipo_lepra_tuberculoide * 100) / $totalLepra;
										$porcindeter = ($pacientes_tipo_lepra_indeterminado * 100) / $totalLepra;
										$porcdifusa = ($pacientes_tipo_lepra_lepromatosa_difusa * 100) / $totalLepra;
										
										
										$porclepro = number_format($porclepro,2,'.','');
										$porcdimorfo = number_format($porcdimorfo,2,'.','');
										$porctubercu = number_format($porctubercu,2,'.','');
										$porcindeter = number_format($porcindeter,2,'.','');
										$porcdifusa = number_format($porcdifusa,2,'.','');
																				
										$data_x = '';
										$color_x = '';
										$label_x = '';
										$label_num = '';
										
										if($porclepro > 0){
											$data_x .= $porclepro.',';
											$color_x .= "'Gradient(pink:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Lepromatosa Nodular (MB)',";
											$label_num .= "'".$porclepro."%',";
										}
										if($porcdimorfo > 0){
											$data_x .= $porcdimorfo.',';
											$color_x .= "'Gradient(blue:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Dimorfo (MB)',";
											$label_num .= "'".$porcdimorfo."%',";
										}
										if($porctubercu > 0){
											$data_x .= $porctubercu.',';
											$color_x .= "'Gradient(red:rgba(255, 176, 176, 0.5))',";
											$label_x .= "'Lepra Tuberculoide (PB)',";
											$label_num .= "'".$porctubercu."%',";
										}
										if($porcindeter > 0){
											$data_x .= $porcindeter.',';
											$color_x .= "'Gradient(green:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Caso Indeterminado (PB)',";
											$label_num .= "'".$porcindeter."%',";
										}
										if($porcdifusa > 0){
											$data_x .= $porcdifusa.',';
											$color_x .= "'Gradient(purple:rgba(153, 208, 249,0.5))',";
											$label_x .= "'Lepra Lepromatosa Difusa (MB)',";
											$label_num .= "'".$porcdifusa."%',";
										}
										
										$data_x = substr($data_x, 0, -1);
										$color_x = substr($color_x, 0, -1);
										$label_x = substr($label_x, 0, -1);
										$label_num = substr($label_num, 0, -1);
										
											
										echo '<table>
									<tr>
										<td align="center"><h3>Global</h3></td>
										<td align="center"><h3>'.utf8_decode($por_anio).'</h3></td>
									</tr>
									<tr>	
										<td><canvas id="cvspor_lepra" width="325" height="250">[No canvas support]</canvas>';
										
										
										echo "<script>
											var pie = new RGraph.Pie('cvspor_lepra', [".$data_x."])
											
														
												.Set('linewidth', 5)
												.Set('shadow', false)
												.Set('shadow.blur', 5)
												.Set('shadow.offsety', 5)
												.Set('shadow.offsetx', 5)
												.Set('exploded', 10)
												.Set('radius', 90)
												.Set('tooltips', [".$label_x."])
												.Set('tooltips.event', 'onmousemove')
												.Set('labels', [".$label_num."])
												//.Set('chart.colors', ['red', 'blue'])
											
											!ISIE || ISIE9UP ? RGraph.Effects.Pie.RoundRobin(pie, {frames:30}) : pie.Draw();
											
											pie.onclick = function (e, shape)
											{
												var index = shape.index;
												var obj = shape.object;
												
												// Reset all the segments to 10
												obj.Set('exploded', 10);
												
												obj.Explode(index, 15);
												
												e.stopPropagation();
											}
											
											pie.onmousemove = function (e, shape)
											{

												e.target.style.cursor = 'pointer';
											}
											
											window.addEventListener('mousedown', function (e)
											{
												pie.Set('exploded', 10);
											}, true);
									
										
											</script></td><td>";
									
											
											
											
											echo '<canvas id="cvs_7" width="600" height="250">[No canvas support]</canvas>';
											echo '<script >
												  var bar = new RGraph.Bar("cvs_7", [';
															
															$max_3 = 0;
															$cadena_2 = '';
															foreach($arreglo_fecha_2 as $anio_6){						
																									
																$lepromatosa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa']);
																$dimorfo = intval($pacientes_tipo_lepra[$anio_6]['dimorfo']);
																$tuberculoide = intval($pacientes_tipo_lepra[$anio_6]['tuberculoide']);
																$indeterminado = intval($pacientes_tipo_lepra[$anio_6]['indeterminado']);
																$lepromatosa_difusa = intval($pacientes_tipo_lepra[$anio_6]['lepromatosa_difusa']);
																
																if($lepromatosa > $max_3)
																	$max_3 = $lepromatosa;
																if($dimorfo > $max_3)
																	$max_3 = $dimorfo;
																if($tuberculoide > $max_3)
																	$max_3 = $tuberculoide;
																if($indeterminado > $max_3)
																	$max_3 = $indeterminado;
																if($lepromatosa_difusa > $max_3)
																	$max_3 = $lepromatosa_difusa;
																	
																
																$cadena_lepra .= '['.$lepromatosa.','.$dimorfo.','.$tuberculoide.','.$indeterminado.','.$lepromatosa_difusa.'],';
																//$cadena_lepra .= '[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,6,2],[5,2,1,62]';
															}												
															
															if($max_3 <= 5)
																$max_3 = 5;			

														echo  $parametros_lepra = substr($cadena_lepra, 0, -1);
													//echo '[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4],[5,2,1,3,4]';
										echo '											  
												  ])
												.Set("labels", [';
												if(isset($arreglo_fecha))
															echo $arreglo_fecha;
														else {
															foreach($arreglo_fecha_2 as $valor_3){
																$cadena_3 .= '"'.$valor_3.'",';
															}

															echo $eliminar_cad = substr($cadena_3, 0, -1);
															}
												
												echo '])
												.Set("tooltips.event", "onmousemove")
												.Set("ymax", '.$max_3.')
											
											
												.Set("shadow", false)
												.Set("shadow.offsetx", 10)
												.Set("shadow.offsety", 0)
												.Set("shadow.blur", 40)
												.Set("hmargin.grouped", 0)
												.Set("units.pre", "")
												.Set("gutter.bottom", 20)
												.Set("gutter.left", 40)
												.Set("gutter.right", 45)
												.Set("colors", ["Gradient(pink)","Gradient(blue)","Gradient(red)","Gradient(green)","Gradient(purple)"])
												.Set("background.grid.autofit.numhlines", 5)
												.Set("background.grid.autofit.numvlines", 4)
											
											// This draws the chart
											RGraph.Effects.Fade.In(bar, {"duration": 250});        
													
													
											</script></td></tr></table>';
												
										$objHTML->endFieldset();
										
										
										}
										
									}
								break;
								default:
						
						}
					
					
					
					$numero_personas = devuelveNumRows( $ejecuta_edad);
					
					
				}

											}


?>