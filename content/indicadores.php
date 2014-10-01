<script src="include/RGraph/libraries/RGraph.common.core.js" ></script>
<script src="include/RGraph/libraries/RGraph.common.dynamic.js" ></script>
<script src="include/RGraph/libraries/RGraph.common.effects.js" ></script>
<script src="include/RGraph/libraries/RGraph.meter.js" ></script>
<!--[if lt IE 9]><script src="excanvas/excanvas.js"></script><![endif]-->

<script type="text/javascript">
    $(document).ready(function() {
        setupCalendario('fecha_inicio');
        setupCalendario('fecha_fin');
        setValidacion('formReporte');
        $('#jurisdiccion option:first').text('Estatal');
    });
</script>

<?PHP 
require_once('include/clases/IndicadorDiagnosticoOportuno.php');
require_once('include/clases/IndicadorCalidadDx.php');
require_once('include/clases/IndicadorCoberturaTx.php');
require_once('include/clases/IndicadorExamenContactos.php');
require_once('include/clases/IndicadorTasaPrevalencia.php');
require_once('include/clases/Helpers.php');

echo '<h2 align="center">INDICE B&Aacute;SICO DE DESEMPE&Ntilde;O</h2>';

$objHTML = new HTML();
$objSelects = new Select();
$objHelp = new Helpers();

$objHTML->startForm('formReporte', '?mod=ind', 'POST');

    $objHTML->startFieldset();
    echo '<div align="center">';
            if(isset($_POST['edoNac']))
                $objSelects->selectEstado('edoNac', $_POST['edoNac'], $_SESSION[EDO_USR_SESSION] == 0 ? array() : array('disabled'=>'disabled'));
            else
                $objSelects->selectEstado('edoNac', $_SESSION[EDO_USR_SESSION], $_SESSION[EDO_USR_SESSION] == 0 ? array() : array('disabled'=>'disabled'));
            
			if(isset($_POST['edoNac']))
				$objSelects->selectJurisdiccion('jurisdiccion', $_POST['edoNac'], $_POST['jurisdiccion']);
			else if($_SESSION[EDO_USR_SESSION] != 0)
				$objSelects->selectJurisdiccion('jurisdiccion', $_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion']);
            
            $objHTML->label('Fecha: ');
            $objHTML->inputText('', 'fecha_inicio', $_POST['fecha_inicio'] ? $_POST['fecha_inicio'] : '01-'.date('m-Y'), array('placeholder'=>'Inicio'));
            $objHTML->inputText('', 'fecha_fin', $_POST['fecha_fin'] ? $_POST['fecha_fin'] : date("d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1)).'-'.date('m-Y'), array('placeholder'=>'Fin'));
            $objHTML->inputSubmit('generarReporte', 'Generar Reporte');
    echo '</div>';
    $objHTML->endFieldset();
    
$objHTML->endFormOnly();


$objHTML->startFieldset();

    if(!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio'])){
		
		$idCatEstadoBusqueda = $_SESSION[EDO_USR_SESSION];
		
		if($_SESSION[EDO_USR_SESSION] == 0)
			$idCatEstadoBusqueda = $_POST['edoNac'];
		
        $indDiaOpo = new IndicadorDiagnosticoOportuno();
        
        $indDiaOpo->idCatEstado = $idCatEstadoBusqueda;
        $indDiaOpo->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indDiaOpo->fechaInicio = $_POST['fecha_inicio'];
        $indDiaOpo->fechaFin = $_POST['fecha_fin'];
        
        $indDiaOpo->calcular();
        //$indDiaOpo->imprimir();
        
        
        $indCalDx = new IndicadorCalidadDx();
        
        $indCalDx->idCatEstado = $idCatEstadoBusqueda;
        $indCalDx->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indCalDx->fechaInicio = $_POST['fecha_inicio'];
        $indCalDx->fechaFin = $_POST['fecha_fin'];
        
        $indCalDx->calcular();
        //$indCalDx->imprimir();
        
        
        $indCobTx = new IndicadorCoberturaTx();
        
        $indCobTx->idCatEstado = $idCatEstadoBusqueda;
        $indCobTx->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indCobTx->fechaInicio = $_POST['fecha_inicio'];
        $indCobTx->fechaFin = $_POST['fecha_fin'];
        
        $indCobTx->calcular();
        //$indCobTx->imprimir();
        
        
        $indExaCon = new IndicadorExamenContactos();
        
        $indExaCon->idCatEstado = $idCatEstadoBusqueda;
        $indExaCon->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indExaCon->fechaInicio = $_POST['fecha_inicio'];
        $indExaCon->fechaFin = $_POST['fecha_fin'];
        
        $indExaCon->calcular();
        //$indExaCon->imprimir();
        
        
        $indTasPre = new IndicadorTasaPrevalencia();
        $indTasPre->idCatEstado = $idCatEstadoBusqueda;
        $indTasPre->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indTasPre->fechaInicio = $_POST['fecha_inicio'];
        $indTasPre->fechaFin = $_POST['fecha_fin'];
        
        $indTasPre->calcular();
        //$indTasPre->imprimir();
        
        $estado = $objHelp->getNombreEstado($idCatEstadoBusqueda);
        
        $jurisdiccion = $objHelp->getNombreJurisdiccion($idCatEstadoBusqueda, $_POST['jurisdiccion']);
        $jurisdiccion = $jurisdiccion ? ', Jurisdicci&oacute;n: '.htmlentities($jurisdiccion) : '';
        
        echo '<br><h3>Estado: '.$estado.' '.$jurisdiccion.'</h3><br>';
        
        
        /**************************************************/
        //Limpiando valores y asignando 0
		if($indDiaOpo->resultado == "-")
		{
			$indDiaOpo->resultado = 0;
		}
		
		if($indCalDx->resultado == "-")
		{
			$indCalDx->resultado = 0;
		}
		
		if($indCobTx->resultado == "-")
		{
			$indCobTx->resultado = 0;
		}
		
		if($indExaCon->resultado == "-")
		{
			$indExaCon->resultado = 0;
		}
		
		/*
		$indDiaOpo->resultado = rand(75,90);
		$indCalDx->resultado = rand(95,100);
		$indCobTx->resultado = rand(85,100);
		$indExaCon->resultado = rand(95,100);*/
		
        // aqui va el codigo de la grafica
		//<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
		echo '<div align="center">
				<table>
					<tbody>			
					<tr>
					<td align="center">
					<div style="width: 295px; height: 295px; background-color: white; border-radius: 250px; text-align: center; font-family: Arial; box-shadow: 0px 0px 25px gray; border: 2px solid #ddd">
						<canvas id="cvs" width="300" height="170">[No canvas support]</canvas>        
						<script>
							meter = new RGraph.Meter("cvs", 0,100,'.round($indDiaOpo->resultado, 2).')
								.Set("border", false)
								.Set("units.post", "%")
								.Set("tickmarks.small.num", 0)
								.Set("tickmarks.big.num", 0)
								.Set("segment.radius.start", 80)
								.Set("text.size", 10)
								.Set("colors.ranges", [
													   [0,40,"Gradient(#000:#333:#000)"],
													   [40,60,"Gradient(#c00:#f00:#c00)"],
													   [60,80,"Gradient(#f90:#fc0:#f90)"],
													   [80,100,"Gradient(#060:#090:#060)"]
													  ])
								.Set("needle.radius", 60)
								.Set("gutter.bottom", 25)
								.Set("gutter.left", 10)
								.Draw();
						</script>
					<div>
					<h2>'.$indDiaOpo->nombre.'</h2>
					<h2>'.round($indDiaOpo->resultado, 2).'%</h2>
					</td>
					<td>&nbsp;</td>
					<td align="center">        
					<div style="width: 295px; height: 295px; background-color: white; border-radius: 250px; text-align: center; font-family: Arial; box-shadow: 0px 0px 25px gray; border: 2px solid #ddd">
						<canvas id="cvs2" width="300" height="170">[No canvas support]</canvas>        
						<script>
							meter2 = new RGraph.Meter("cvs2", 0,100,'.round($indCalDx->resultado, 2).')
								.Set("border", false)
								.Set("units.post", "%")
								.Set("tickmarks.small.num", 0)
								.Set("tickmarks.big.num", 0)
								.Set("segment.radius.start", 80)
								.Set("text.size", 10)
								.Set("colors.ranges", [
													   [0,40,"Gradient(#000:#333:#000)"],
													   [40,60,"Gradient(#c00:#f00:#c00)"],
													   [60,80,"Gradient(#f90:#fc0:#f90)"],
													   [80,100,"Gradient(#060:#090:#060)"]
													  ])
								.Set("needle.radius", 60)
								.Set("gutter.bottom", 25)
								.Set("gutter.left", 10)
								.Draw();
						</script>
					<div>
					<h2>'.$indCalDx->nombre.'</h2>
					<h2>'.round($indCalDx->resultado, 2).'%</h2>
					</td>
				  </tr>
				  <tr>
				  <td height="30px">&nbsp;</td><td>&nbsp;</td>
				  </tr>
				  <tr>
					<td align="center">
					<div style="width: 295px; height: 295px; background-color: white; border-radius: 250px; text-align: center; font-family: Arial; box-shadow: 0px 0px 25px gray; border: 2px solid #ddd">
						<canvas id="cvs3" width="300" height="170">[No canvas support]</canvas>        
						<script>
							meter3 = new RGraph.Meter("cvs3", 0,100,'.round($indCobTx->resultado, 2).')
								.Set("border", false)
								.Set("units.post", "%")
								.Set("tickmarks.small.num", 0)
								.Set("tickmarks.big.num", 0)
								.Set("segment.radius.start", 80)
								.Set("text.size", 10)
								.Set("colors.ranges", [
													   [0,40,"Gradient(#000:#333:#000)"],
													   [40,60,"Gradient(#c00:#f00:#c00)"],
													   [60,80,"Gradient(#f90:#fc0:#f90)"],
													   [80,100,"Gradient(#060:#090:#060)"]
													  ])
								.Set("needle.radius", 60)
								.Set("gutter.bottom", 25)
								.Set("gutter.left", 10)
								.Draw();
						</script>
					<div>
					<h2>'.$indCobTx->nombre.'</h2>
					<h2>'.round($indCobTx->resultado, 2).'%</h2>
					</td>
					<td width="30px">&nbsp;</td>
					<td align="center">        
					<div style="width: 295px; height: 295px; background-color: white; border-radius: 250px; text-align: center; font-family: Arial; box-shadow: 0px 0px 25px gray; border: 2px solid #ddd">
						<canvas id="cvs4" width="300" height="170">[No canvas support]</canvas>        
						<script>
							meter4 = new RGraph.Meter("cvs4", 0,100,'.round($indExaCon->resultado, 2).')
								.Set("border", false)
								.Set("units.post", "%")
								.Set("tickmarks.small.num", 0)
								.Set("tickmarks.big.num", 0)
								.Set("segment.radius.start", 80)
								.Set("text.size", 10)
								.Set("colors.ranges", [
													   [0,40,"Gradient(#000:#333:#000)"],
													   [40,60,"Gradient(#c00:#f00:#c00)"],
													   [60,80,"Gradient(#f90:#fc0:#f90)"],
													   [80,100,"Gradient(#060:#090:#060)"]
													  ])
								.Set("needle.radius", 60)
								.Set("gutter.bottom", 25)
								.Set("gutter.left", 10)
								.Draw();
						</script>
					<div>
					<h2>'.$indExaCon->nombre.'</h2>
					<h2>'.round($indExaCon->resultado, 2).'%</h2>
					</td>
				  </tr>
				  <tbody>
				  </table></div><br>';
		
        
        /**************************************************/
        
        /*
        echo '<div class="datagrid" style="margin: auto;">
            <table>
                <thead>
                <tr align="center">
                    <th>Indicador</th>
                    <th>Est&aacute;ndar</th>
                    <th>Resultado</th>
                    <th>Ponderaci&oacute;n</th>
                    <th>&Iacute;ndice</th>
                </tr>
                </thead>
                <tbody>
                <tr align="center">
                    <td align="left">'.$indDiaOpo->nombre.'</td>
                    <td>'.$indDiaOpo->estandar.'%</td>
                    <td>'.round($indDiaOpo->resultado, 2).'</td>
                    <td>'.$indDiaOpo->ponderacion.'</td>
                    <td>'.round($indDiaOpo->indice, 2).'</td>
                </tr>
                <tr align="center">
                    <td align="left">'.$indCalDx->nombre.'</td>
                    <td>'.$indCalDx->estandar.'%</td>
                    <td>'.round($indCalDx->resultado, 2).'</td>
                    <td>'.$indCalDx->ponderacion.'</td>
                    <td>'.round($indCalDx->indice, 2).'</td>
                </tr>
                <tr align="center">
                    <td align="left">'.$indCobTx->nombre.'</td>
                    <td>'.$indCobTx->estandar.'%</td>
                    <td>'.round($indCobTx->resultado, 2).'</td>
                    <td>'.$indCobTx->ponderacion.'</td>
                    <td>'.round($indCobTx->indice, 2).'</td>
                </tr>
                <tr align="center">
                    <td align="left">'.$indExaCon->nombre.'</td>
                    <td>'.$indExaCon->estandar.'%</td>
                    <td>'.round($indExaCon->resultado, 2).'</td>
                    <td>'.$indExaCon->ponderacion.'</td>
                    <td>'.round($indExaCon->indice, 2).'</td>
                </tr>
                <tr align="center">
                    <td align="left">'.$indTasPre->nombre.'</td>
                    <td>'.$indTasPre->estandar.'</td>
                    <td>'.round($indTasPre->resultado, 2).'</td>
                    <td>'.$indTasPre->ponderacion.'</td>
                    <td>'.round($indTasPre->indice, 2).'</td>
                </tr>
                <tr>
                    <td align="right" colspan="4"><strong>Indice del desempe&ntilde;o</strong></td>
                    <td align="center"><strong>'.round(($indDiaOpo->indice + $indCalDx->indice + $indCobTx->indice + $indExaCon->indice + $indTasPre->indice), 2).'</strong></td>
                </tr>
                </tbody>
            </table>
        </div>';*/
        
        echo '<br><br><div class="datagrid">
            <table>
                <thead>
                <tr align="center">
                    <th></th>
                    <th>Valor del desempe&ntilde;o</th>
                    <th>Valoraci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td bgcolor="#006D00"> &nbsp; </td>
                    <td>80 a m&aacute;s puntos</td>
                    <td>Sobresaliente</td>
                </tr>
                <tr>
                    <td bgcolor="#FFA100"> &nbsp; </td>
                    <td>60 a 79 puntos</td>
                    <td>Satisfactorio</td>
                </tr>
                <tr>
                    <td bgcolor="#D70000"> &nbsp; </td>
                    <td>40 a 59 puntos</td>
                    <td>M&iacute;nimo</td>
                </tr>
                <tr>
                    <td bgcolor="#0B0B0B"> &nbsp; </td>
                    <td><40 puntos</td>
                    <td>Precario</td>
                </tr>
                </tbody>
            </table>
        </div><br>';
        
    }
    
$objHTML->endFieldset();
?>
