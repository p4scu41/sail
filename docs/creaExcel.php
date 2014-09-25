<?php

session_start();
require_once('../include/var_global.php');
require_once('../include/bdatos.php');
require_once('../include/log.php');
require_once('../include/fecha_hora.php');
//print_r($_POST); exit(0);
$connectionBD = conectaBD();
if(isset($_POST['type']))
{
	/*header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".$_GET['type'].".csv\";" );
	header("Content-Transfer-Encoding: binary");*/

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment;filename=\"".$_POST['type'].".xls\"");
	header("Cache-Control: max-age=0");

	switch($_POST['type'])
	{
		case 'repDGEpi':
			require_once('../include/clases/ReporteListadoGeneral.php');
			$reporteListadoGeneral = new ReporteListadoGeneral();
			$reporteListadoGeneral->idCatEstado = $_SESSION[EDO_USR_SESSION];
			//echo $_SESSION[EDO_USR_SESSION];
			//echo $reporteListadoGeneral->idCatEstado;
			$reporteListadoGeneral->generarReporte();
			$reporteListadoGeneral->imprimirReporte();
		break;
		case 'repSeg':
			require_once('../include/clases/ReporteTrimestral.php');
			$reporteTrimestral = new ReporteTrimestral();
			$reporteTrimestral->idCatEstado = $_SESSION[EDO_USR_SESSION];
			$reporteTrimestral->generarReporte();
			//$reporteTrimestral->imprimirReporte();
			echo $reporteTrimestral->imprimirReporteUnitabla(true);
		break;
	}
}
?>