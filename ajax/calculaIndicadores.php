<?PHP 
require_once('../include/clases/IndicadorDiagnosticoOportuno.php');
require_once('../include/clases/IndicadorCalidadDx.php');
require_once('../include/clases/IndicadorCoberturaTx.php');
require_once('../include/clases/IndicadorExamenContactos.php');
require_once('../include/clases/IndicadorTasaPrevalencia.php');
require_once('../include/clases/Helpers.php');
require_once('../include/HTML.class.php');
require_once('../include/Catalogo.class.php');
require_once('../include/Select.class.php');
require_once('../include/var_global.php');
require_once('../include/bdatos.php');
require_once('../include/fecha_hora.php');
require_once('../include/log.php');
require_once('../include/funciones.php');

session_start();

if($_SESSION[EDO_USR_SESSION] != 7) { //hack truco borrar
    echo '<div class="msj_error" align="center"><img src="images/error.gif" align="absmiddle" /><h3 style="display:inline">No hay suficientes datos para generar el reporte</h3></div>';
    die();
}

$connectionBD = conectaBD();

/*
1 => "Primer Trimestre"
2 => "Segundo Trimestre"
3 => "Primer Semestre"
4 => "Tercero Trimestre"
5 => "Cuarto Trimestre"
6 => "Segundo Semestre"
7 => "Anual"
*/
switch($_POST['periodo'])
{
	case '1':
		$fecha1 = "01-01-";
		$fecha2 = "31-03-";
	break;
	case '2':
		$fecha1 = "01-04-";
		$fecha2 = "30-06-";
	break;
	case '3':
		$fecha1 = "01-01-";
		$fecha2 = "30-06-";
	break;
	case '4':
		$fecha1 = "01-07-";
		$fecha2 = "30-09-";
	break;
	case '5':
		$fecha1 = "01-10-";
		$fecha2 = "31-12-";
	break;
	case '6':
		$fecha1 = "01-07-";
		$fecha2 = "31-12-";
	break;
	case '7':
		$fecha1 = "01-01-";
		$fecha2 = "31-12-";
	break;
}

$sql = "SELECT * FROM catJurisdiccion WHERE 1=1 ";

if($_SESSION[EDO_USR_SESSION] != 0)
    $sql .= " AND idCatEstado = ".$_SESSION[EDO_USR_SESSION];

$consulta = ejecutaQuery($sql);

$objHelp = new Helpers();

$currentYear = $_POST['anio'];
$pastYear = $currentYear-1;

$agrega_diagonal = "";
/* Compatibilidad con Linux */
if(stripos($_SERVER['DOCUMENT_ROOT'],":/") === FALSE) $agrega_diagonal = "/";

$rootPath = $_SERVER['DOCUMENT_ROOT'].$agrega_diagonal.CARPETA_RAIZ."/docs/";

$xlsFile = $rootPath.'LEPRA_MACRO.xlsm';  
         
$xlsObj = new COM("Excel.application") or Die ("Did not connect"); 
$xlsObj->DisplayAlerts = false; 
$xlsObj->Workbooks->Open($xlsFile); 
$book = $xlsObj->ActiveWorkbook; 

$sheet = $book->Worksheets(1);

$jj = 4;

while($jurisdiccion = devuelveRowAssoc($consulta))
{
	//echo $jurisdiccion['idCatJurisdiccion']."<br>";
	
	if($jj != 14)
	{
		$indDiaOpo = new IndicadorDiagnosticoOportuno();
		
		$indDiaOpo->idCatEstado = $_SESSION[EDO_USR_SESSION];
		$indDiaOpo->idCatJurisdiccion = $jurisdiccion['idCatJurisdiccion'];
		$indDiaOpo->fechaInicio = $fecha1.$currentYear;
		$indDiaOpo->fechaFin = $fecha2.$currentYear;
		
		$indDiaOpo->calcular();
		
		$CasosNuevosSinDiscapacidad1 = $indDiaOpo->CasosNuevosSinDiscapacidad;
		$totalCasosNuevosDiagnosticados1 = $indDiaOpo->totalCasosNuevosDiagnosticados;
		
		$indDiaOpo = new IndicadorDiagnosticoOportuno();
		
		$indDiaOpo->idCatEstado = $_SESSION[EDO_USR_SESSION];
		$indDiaOpo->idCatJurisdiccion = $jurisdiccion['idCatJurisdiccion'];
		
		$indDiaOpo->fechaInicio = $fecha1.$pastYear;
		$indDiaOpo->fechaFin = $fecha2.$pastYear;
		
		$indDiaOpo->calcular();
		
		$CasosNuevosSinDiscapacidad2 = $indDiaOpo->CasosNuevosSinDiscapacidad;
		$totalCasosNuevosDiagnosticados2 = $indDiaOpo->totalCasosNuevosDiagnosticados;
		
		/////////////////
		$indCalDx = new IndicadorCalidadDx();
		
		$indCalDx->idCatEstado = $_SESSION[EDO_USR_SESSION];
		$indCalDx->idCatJurisdiccion = $jurisdiccion['idCatJurisdiccion'];
		$indCalDx->fechaInicio = $fecha1.$currentYear;
		$indCalDx->fechaFin = $fecha2.$currentYear;
		
		$indCalDx->calcular();
		
		$casosNuevosConBkyHp1 = $indCalDx->casosNuevosConBkyHp;
		$totalCasosNuevos1 = $indCalDx->totalCasosNuevos;
		
		$indCalDx = new IndicadorCalidadDx();
		
		$indCalDx->idCatEstado = $_SESSION[EDO_USR_SESSION];
		$indCalDx->idCatJurisdiccion = $jurisdiccion['idCatJurisdiccion'];
		
		$indCalDx->fechaInicio = $fecha1.$pastYear;
		$indCalDx->fechaFin = $fecha2.$pastYear;
		
		$indCalDx->calcular();
		
		$casosNuevosConBkyHp2 = $indCalDx->casosNuevosConBkyHp;
		$totalCasosNuevos2 = $indCalDx->totalCasosNuevos;
		
		////////////////
		$indCobTx = new IndicadorCoberturaTx();
		
		$indCobTx->idCatEstado = $_SESSION[EDO_USR_SESSION];
		$indCobTx->idCatJurisdiccion = $jurisdiccion['idCatJurisdiccion'];
		$indCobTx->fechaInicio = $fecha1.$currentYear;
		$indCobTx->fechaFin = $fecha2.$currentYear;
		
		$indCobTx->calcular();
		
		$casosPQT1 = $indCobTx->casosPQT;
		$totalCasos1 = $indCobTx->totalCasos;
		
		$indCobTx = new IndicadorCoberturaTx();
		
		$indCobTx->idCatEstado = $_SESSION[EDO_USR_SESSION];
		$indCobTx->idCatJurisdiccion = $jurisdiccion['idCatJurisdiccion'];
		
		$indCobTx->fechaInicio = $fecha1.$pastYear;
		$indCobTx->fechaFin = $fecha2.$pastYear;
		
		$indCobTx->calcular();
		
		$casosPQT2 = $indCobTx->casosPQT;
		$totalCasos2 = $indCobTx->totalCasos;
		
		//////////////
		$indExaCon = new IndicadorExamenContactos();
		
		$indExaCon->idCatEstado = $_SESSION[EDO_USR_SESSION];
		$indExaCon->idCatJurisdiccion = $jurisdiccion['idCatJurisdiccion'];
		$indExaCon->fechaInicio = $fecha1.$currentYear;
		$indExaCon->fechaFin = $fecha2.$currentYear;
		
		$indExaCon->calcular();
		
		$numeroContactosExaminados1 = $indExaCon->numeroContactosExaminados;
		$totalContactosRegistrados1 = $indExaCon->totalContactosRegistrados;
		
		$indExaCon = new IndicadorExamenContactos();
		
		$indExaCon->idCatEstado = $_SESSION[EDO_USR_SESSION];
		$indExaCon->idCatJurisdiccion = $jurisdiccion['idCatJurisdiccion'];
		
		$indExaCon->fechaInicio = $fecha1.$pastYear;
		$indExaCon->fechaFin = $fecha2.$pastYear;
		
		$indExaCon->calcular();
		
		$numeroContactosExaminados2 = $indExaCon->numeroContactosExaminados;
		$totalContactosRegistrados2 = $indExaCon->totalContactosRegistrados;
		
		/*//////////////AÑO ACTUAL///////////////////
		echo $CasosNuevosSinDiscapacidad1." - ";
		echo $totalCasosNuevosDiagnosticados1." -- ";
		
		echo $numeroContactosExaminados1." - ";
		echo $totalContactosRegistrados1." -- ";
		
		echo $casosPQT1." - ";
		echo $totalCasos1." -- ";
		
		echo $casosNuevosConBkyHp1." - ";
		echo $totalCasosNuevos1." <br> ";
		
		///////////////AÑO ANTERIOR///////////////////
		echo $CasosNuevosSinDiscapacidad2." - ";
		echo $totalCasosNuevosDiagnosticados2." -- ";
		
		echo $numeroContactosExaminados2." - ";
		echo $totalContactosRegistrados2." -- ";
		
		echo $casosPQT2." - ";
		echo $totalCasos2." -- ";
		
		echo $casosNuevosConBkyHp2." - ";
		echo $totalCasosNuevos2." <br> ";
		
		///////////////AÑO ACTUAL//////////////////*/
		
		$cell = $sheet->Cells($jj,2);
		$cell->Activate;
		$cell->value = $CasosNuevosSinDiscapacidad2;
		
		$cell = $sheet->Cells($jj,3);
		$cell->Activate;
		$cell->value = $totalCasosNuevosDiagnosticados2;
		
		$cell = $sheet->Cells($jj,4);
		$cell->Activate;
		$cell->value = $casosNuevosConBkyHp2;
		
		$cell = $sheet->Cells($jj,5);
		$cell->Activate;
		$cell->value = $totalCasosNuevos2;
		
		$cell = $sheet->Cells($jj,6);
		$cell->Activate;
		$cell->value = $casosPQT2;
		
		$cell = $sheet->Cells($jj,7);
		$cell->Activate;
		$cell->value = $totalCasos2;
		
		$cell = $sheet->Cells($jj,8);
		$cell->Activate;
		$cell->value = $numeroContactosExaminados2;
		
		$cell = $sheet->Cells($jj,9);
		$cell->Activate;
		$cell->value = $totalContactosRegistrados2;
	
		///////////////AÑO ANTERIOR///////////////////
		
		$cell = $sheet->Cells($jj,10);
		$cell->Activate;
		$cell->value = $CasosNuevosSinDiscapacidad1;
		
		$cell = $sheet->Cells($jj,11);
		$cell->Activate;
		$cell->value = $totalCasosNuevosDiagnosticados1;
		
		$cell = $sheet->Cells($jj,12);
		$cell->Activate;
		$cell->value = $casosNuevosConBkyHp1;
		
		$cell = $sheet->Cells($jj,13);
		$cell->Activate;
		$cell->value = $totalCasosNuevos1;
		
		$cell = $sheet->Cells($jj,14);
		$cell->Activate;
		$cell->value = $casosPQT1;
		
		$cell = $sheet->Cells($jj,15);
		$cell->Activate;
		$cell->value = $totalCasos1;
		
		$cell = $sheet->Cells($jj,16);
		$cell->Activate;
		$cell->value = $numeroContactosExaminados1;
		
		$cell = $sheet->Cells($jj,17);
		$cell->Activate;
		$cell->value = $totalContactosRegistrados1;
		
		$jj++;
	}
	
	unset($indDiaOpo);
	unset($indExaCon);
	unset($indCobTx);
	unset($indCalDx);
	
	$totalContactosRegistrados1 = 0;
	$numeroContactosExaminados1 = 0;
	$totalCasos1 = 0;
	$casosPQT1 = 0;
	$totalCasosNuevos1 = 0;
	$casosNuevosConBkyHp1 = 0;
	$totalCasosNuevosDiagnosticados1 = 0;
	$CasosNuevosSinDiscapacidad1 = 0;
	
	$totalContactosRegistrados2 = 0;
	$numeroContactosExaminados2 = 0;
	$totalCasos2 = 0;
	$casosPQT2 = 0;
	$totalCasosNuevos2 = 0;
	$casosNuevosConBkyHp2 = 0;
	$totalCasosNuevosDiagnosticados2 = 0;
	$CasosNuevosSinDiscapacidad2 = 0;
}

$xlsObj->Run("LEPRA");
$book->saveAs($rootPath."caminando_excelencia.xlsm");
$book->Close(false); 
unset($sheets); 
$xlsObj->Workbooks->Close(); 
unset($book); 
$xlsObj->Quit; 
unset($xlsObj);
echo "<a href='caminando_excelencia.xlsm'>Descargar Boletin Caminando a la Excelencia</a>";
?>