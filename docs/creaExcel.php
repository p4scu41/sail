<?php

session_start();
require_once('../include/var_global.php');
require_once('../include/bdatos.php');
require_once('../include/log.php');
require_once('../include/fecha_hora.php');
require_once('../include/clases/Helpers.php');

$connectionBD = conectaBD();
$help = new Helpers();

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
			$reporteListadoGeneral->generarReporte();
			$reporteListadoGeneral->imprimirReporte(true);
		break;
		case 'repSeg':
			require_once('../include/clases/ReporteTrimestral.php');
			$reporteTrimestral = new ReporteTrimestral();
			$reporteTrimestral->idCatEstado = $_SESSION[EDO_USR_SESSION];
            $reporteTrimestral->filtro = $_POST['tipo_paciente'];
			$reporteTrimestral->generarReporte();
			$reporte = $reporteTrimestral->imprimirReporteUnitabla(true);
            $nivel = 'Nacional';
            
            if($reporteTrimestral->idCatEstado != 0) {
                $nivel = $help->getNombreEstado($reporteTrimestral->idCatEstado);
            }

            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
  </head>
  <body>
	<table border="0" cellpadding="0" cellspacing="0" id="sheet0" class="sheet0 gridlines">
		<tbody>
		  <tr align="center"class="row0">
			<td class="column0 style86 s style86" colspan="15"><strong>CENTRO NACIONAL DE PROGRAMAS PREVENTIVOS Y CONTROL DE ENFERMEDADES</strong></td>
			<td class="column15 style86 null style86" colspan="17"></td>
			<td class="column32 style86 null style86" colspan="13"></td>
			<td class="column45 style86 null style86" colspan="12"></td>
			<td class="column57 style8 null">&nbsp;</td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		  <tr align="center"class="row1">
			<td class="column0 style86 s style86" colspan="15"><strong>DIRECCI&Oacute;N DE MICOBACTERIOSIS</strong></td>
			<td class="column15 style86 null style86" colspan="17"></td>
			<td class="column32 style86 null style86" colspan="13"></td>
			<td class="column45 style86 null style86" colspan="12"></td>
			<td class="column57 style8 null">&nbsp;</td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		  <tr align="center"class="row2">
			<td class="column0 style86 s style86" colspan="15"><strong>PROGRAMA DE PREVENCI&Oacute;N Y CONTROL DE LA LEPRA</strong></td>
			<td class="column15 style86 null style86" colspan="17"></td>
			<td class="column32 style86 null style86" colspan="13"></td>
			<td class="column45 style86 null style86" colspan="12"></td>
			<td class="column57 style8 null">&nbsp;</td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		  <tr align="center"class="row3">
			<td class="column0 style2 null"></td>
			<td class="column1 style3 null"></td>
			<td class="column2 style3 null"></td>
			<td class="column3 style3 null"></td>
			<td class="column4 style3 null"></td>
			<td class="column5 style3 null"></td>
			<td class="column6 style3 null"></td>
			<td class="column7 style3 null"></td>
			<td class="column8 style3 null"></td>
			<td class="column9 style3 null"></td>
			<td class="column10 style3 null"></td>
			<td class="column11 style3 null"></td>
			<td class="column12 style3 null"></td>
			<td class="column13 style3 null"></td>
			<td class="column14 style3 null"></td>
			<td class="column15 style3 null"></td>
			<td class="column16 style4 null"></td>
			<td class="column17 style3 null"></td>
			<td class="column18 style3 null"></td>
			<td class="column19 style3 null"></td>
			<td class="column20 style3 null"></td>
			<td class="column21 style3 null"></td>
			<td class="column22 style3 null"></td>
			<td class="column23 style3 null"></td>
			<td class="column24 style3 null"></td>
			<td class="column25 style3 null"></td>
			<td class="column26 style3 null"></td>
			<td class="column27 style3 null"></td>
			<td class="column28 style3 null"></td>
			<td class="column29 style3 null"></td>
			<td class="column30 style3 null"></td>
			<td class="column31 style3 null"></td>
			<td class="column32 style3 null"></td>
			<td class="column33 style3 null"></td>
			<td class="column34 style3 null"></td>
			<td class="column35 style3 null"></td>
			<td class="column36 style3 null"></td>
			<td class="column37 style3 null"></td>
			<td class="column38 style3 null"></td>
			<td class="column39 style3 null"></td>
			<td class="column40 style5 null"></td>
			<td class="column41 style3 null"></td>
			<td class="column42 style3 null"></td>
			<td class="column43 style3 null"></td>
			<td class="column44 style3 null"></td>
			<td class="column45 style5 null"></td>
			<td class="column46 style5 null"></td>
			<td class="column47 style5 null"></td>
			<td class="column48 style5 null"></td>
			<td class="column49 style5 null"></td>
			<td class="column50 style5 null"></td>
			<td class="column51 style3 null"></td>
			<td class="column52 style3 null"></td>
			<td class="column53 style3 null"></td>
			<td class="column54 style3 null"></td>
			<td class="column55 style3 null"></td>
			<td class="column56 style3 null"></td>
			<td class="column57 style8 null">&nbsp;</td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		  <tr align="center"class="row4">
			<td class="column0 style86 s style86" colspan="15"><strong>REGISTRO Y SEGUIMIENTO DE CASOS DE LEPRA</strong></td>
			<td class="column15 style86 null style86" colspan="17"></td>
			<td class="column32 style86 null style86" colspan="13"></td>
			<td class="column45 style86 null style86" colspan="12"></td>
			<td class="column57 style8 null">&nbsp;</td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		  <tr align="center"class="row5">
			<td class="column0 style61 null style62" colspan="2"></td>
			<td class="column2 style3 null"></td>
			<td class="column3 style3 null"></td>
			<td class="column4 style3 null"></td>
			<td class="column5 style3 null"></td>
			<td class="column6 style3 null"></td>
			<td class="column7 style3 null"></td>
			<td class="column8 style3 null"></td>
			<td class="column9 style3 null"></td>
			<td class="column10 style3 null"></td>
			<td class="column11 style3 null"></td>
			<td class="column12 style3 null"></td>
			<td class="column13 style3 null"></td>
			<td class="column14 style3 null"></td>
			<td class="column15 style3 null"></td>
			<td class="column16 style4 null"></td>
			<td class="column17 style3 null"></td>
			<td class="column18 style3 null"></td>
			<td class="column19 style3 null"></td>
			<td class="column20 style3 null"></td>
			<td class="column21 style3 null"></td>
			<td class="column22 style3 null"></td>
			<td class="column23 style3 null"></td>
			<td class="column24 style3 null"></td>
			<td class="column25 style3 null"></td>
			<td class="column26 style3 null"></td>
			<td class="column27 style3 null"></td>
			<td class="column28 style3 null"></td>
			<td class="column29 style3 null"></td>
			<td class="column30 style3 null"></td>
			<td class="column31 style3 null"></td>
			<td class="column32 style3 null"></td>
			<td class="column33 style3 null"></td>
			<td class="column34 style3 null"></td>
			<td class="column35 style3 null"></td>
			<td class="column36 style3 null"></td>
			<td class="column37 style3 null"></td>
			<td class="column38 style3 null"></td>
			<td class="column39 style3 null"></td>
			<td class="column40 style5 null"></td>
			<td class="column41 style3 null"></td>
			<td class="column42 style3 null"></td>
			<td class="column43 style3 null"></td>
			<td class="column44 style3 null"></td>
			<td class="column45 style5 null"></td>
			<td class="column46 style5 null"></td>
			<td class="column47 style5 null"></td>
			<td class="column48 style5 null"></td>
			<td class="column49 style5 null"></td>
			<td class="column50 style5 null"></td>
			<td class="column51 style3 null"></td>
			<td class="column52 style3 null"></td>
			<td class="column53 style3 null"></td>
			<td class="column54 style3 null"></td>
			<td class="column55 style3 null"></td>
			<td class="column56 style3 null"></td>
			<td class="column57 style8 null">&nbsp;</td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		  <tr align="center"class="row6">
			<td class="column0 style8 null">&nbsp;</td>
			<td class="column1 style6 s"><strong></strong></td>
			<td class="column2 style108 null style108" colspan="4"></td>
			<td class="column6 style5 null"></td>
			<td class="column7 style7 s"><strong>ESTADO:</strong></td>
			<td class="column8 style8 null">'.$nivel.'</td>
			<td class="column9 style8 null"></td>
			<td class="column10 style6 s"></td>
			<td class="column11 style65 null style66" colspan="2"></td>
			<td class="column13 style8 s"><strong>FECHA:</strong></td>
			<td class="column14 style9 null">'.date('d-m-Y').'</td>
			<td class="column15 style6 s"><strong></strong></td>
			<td class="column16 style108 null style108" colspan="4"></td>
			<td class="column20 style5 null"></td>
			<td class="column21 style7 s"><strong>ESTADO:</strong></td>
			<td class="column22 style8 null">'.$nivel.'</td>
			<td class="column23 style8 null"></td>
			<td class="column24 style6 s"></td>
			<td class="column25 style65 null style109" colspan="4"></td>
			<td class="column29 style8 s"><strong>FECHA:</strong></td>
			<td class="column30 style110 null style110" colspan="2">'.date('d-m-Y').'</td>
			<td class="column32 style6 s"><strong></strong></td>
			<td class="column33 style119 null style119" colspan="2"></td>
			<td class="column35 style120 s style120" colspan="2"><strong></strong></td>
			<td class="column37 style8 null">&nbsp;</td>
			<td class="column38 style8 null"></td>
			<td class="column39 style6 s"></td>
			<td class="column40 style65 null style66" colspan="2"></td>
			<td class="column42 style6 s"><strong>FECHA:</strong></td>
			<td class="column43 style110 null style110" colspan="2">'.date('d-m-Y').'</td>
			<td class="column45 style123 null style123" colspan="4"></td>
			<td class="column49 style5 null"></td>
			<td class="column50 style7 s"><strong>ESTADO:</strong></td>
			<td class="column51 style8 null">'.$nivel.'</td>
			<td class="column52 style8 null"></td>
			<td class="column53 style121 null style122" colspan="3"></td>
			<td class="column56 style110 null style110" colspan="2"></td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		  <tr align="center"class="row7">
			<td class="column0 style8 null"></td>
			<td class="column1 style10 null"></td>
			<td class="column2 style10 null"></td>
			<td class="column3 style3 null"></td>
			<td class="column4 style10 null"></td>
			<td class="column5 style3 null"></td>
			<td class="column6 style10 null"></td>
			<td class="column7 style10 null"></td>
			<td class="column8 style10 null"></td>
			<td class="column9 style10 null"></td>
			<td class="column10 style3 null"></td>
			<td class="column11 style10 null"></td>
			<td class="column12 style10 null"></td>
			<td class="column13 style10 null"></td>
			<td class="column14 style10 null"></td>
			<td class="column15 style10 null"></td>
			<td class="column16 style11 null"></td>
			<td class="column17 style5 null"></td>
			<td class="column18 style5 null"></td>
			<td class="column19 style3 null"></td>
			<td class="column20 style5 null"></td>
			<td class="column21 style10 null"></td>
			<td class="column22 style5 null"></td>
			<td class="column23 style10 null"></td>
			<td class="column24 style10 null"></td>
			<td class="column25 style10 null"></td>
			<td class="column26 style5 null"></td>
			<td class="column27 style10 null"></td>
			<td class="column28 style5 null"></td>
			<td class="column29 style10 null"></td>
			<td class="column30 style10 null"></td>
			<td class="column31 style10 null"></td>
			<td class="column32 style10 null"></td>
			<td class="column33 style10 null"></td>
			<td class="column34 style3 null"></td>
			<td class="column35 style10 null"></td>
			<td class="column36 style3 null"></td>
			<td class="column37 style3 null"></td>
			<td class="column38 style10 null"></td>
			<td class="column39 style10 null"></td>
			<td class="column40 style12 null"></td>
			<td class="column41 style10 null"></td>
			<td class="column42 style10 null"></td>
			<td class="column43 style3 null"></td>
			<td class="column44 style10 null"></td>
			<td class="column45 style12 null"></td>
			<td class="column46 style12 null"></td>
			<td class="column47 style5 null"></td>
			<td class="column48 style12 null"></td>
			<td class="column49 style5 null"></td>
			<td class="column50 style5 null"></td>
			<td class="column51 style10 null"></td>
			<td class="column52 style10 null"></td>
			<td class="column53 style3 null"></td>
			<td class="column54 style10 null"></td>
			<td class="column55 style3 null"></td>
			<td class="column56 style3 null"></td>
			<td class="column57 style8 null">&nbsp;</td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		  <tr align="center"class="row8">
			<td class="column0 style8 null">&nbsp;</td>
			<td class="column1 style2 s"></td>
			<td class="column2 style13 null"></td>
			<td class="column3 style14 null"></td>
			<td class="column4 style10 null"></td>
			<td class="column5 style10 null"></td>
			<td class="column6 style10 null"></td>
			<td class="column7 style10 null"></td>
			<td class="column8 style8 null"></td>
			<td class="column9 style10 null"></td>
			<td class="column10 style8 null">&nbsp;</td>
			<td class="column11 style10 null"></td>
			<td class="column12 style8 null"></td>
			<td class="column13 style8 null"></td>
			<td class="column14 style8 null"></td>
			<td class="column15 style8 null">&nbsp;</td>
			<td class="column16 style8 null">&nbsp;</td>
			<td class="column17 style8 null">&nbsp;</td>
			<td class="column18 style8 null">&nbsp;</td>
			<td class="column19 style8 null">&nbsp;</td>
			<td class="column20 style8 null">&nbsp;</td>
			<td class="column21 style8 null">&nbsp;</td>
			<td class="column22 style5 null"></td>
			<td class="column23 style10 null"></td>
			<td class="column24 style8 null"></td>
			<td class="column25 style10 null"></td>
			<td class="column26 style5 null"></td>
			<td class="column27 style8 null">&nbsp;</td>
			<td class="column28 style5 null"></td>
			<td class="column29 style3 null"></td>
			<td class="column30 style8 null"></td>
			<td class="column31 style8 null"></td>
			<td class="column32 style8 null">&nbsp;</td>
			<td class="column33 style8 null">&nbsp;</td>
			<td class="column34 style8 null">&nbsp;</td>
			<td class="column35 style8 null">&nbsp;</td>
			<td class="column36 style8 null">&nbsp;</td>
			<td class="column37 style8 null">&nbsp;</td>
			<td class="column38 style10 null"></td>
			<td class="column39 style8 null"></td>
			<td class="column40 style7 null"></td>
			<td class="column41 style8 null">&nbsp;</td>
			<td class="column42 style10 null"></td>
			<td class="column43 style3 null"></td>
			<td class="column44 style10 null"></td>
			<td class="column45 style8 null">&nbsp;</td>
			<td class="column46 style8 null">&nbsp;</td>
			<td class="column47 style8 null">&nbsp;</td>
			<td class="column48 style8 null">&nbsp;</td>
			<td class="column49 style8 null">&nbsp;</td>
			<td class="column50 style8 null">&nbsp;</td>
			<td class="column51 style10 null"></td>
			<td class="column52 style8 null"></td>
			<td class="column53 style3 null"></td>
			<td class="column54 style10 null"></td>
			<td class="column55 style3 null"></td>
			<td class="column56 style3 null"></td>
			<td class="column57 style8 null">&nbsp;</td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		  <tr align="center"class="row9">
			<td class="column0 style3 null"></td>
			<td class="column1 style3 null"></td>
			<td class="column2 style3 null"></td>
			<td class="column3 style3 null"></td>
			<td class="column4 style3 null"></td>
			<td class="column5 style3 null"></td>
			<td class="column6 style3 null"></td>
			<td class="column7 style3 null"></td>
			<td class="column8 style3 null"></td>
			<td class="column9 style3 null"></td>
			<td class="column10 style3 null"></td>
			<td class="column11 style3 null"></td>
			<td class="column12 style3 null"></td>
			<td class="column13 style5 null"></td>
			<td class="column14 style3 null"></td>
			<td class="column15 style3 null"></td>
			<td class="column16 style4 null"></td>
			<td class="column17 style5 null"></td>
			<td class="column18 style5 null"></td>
			<td class="column19 style5 null"></td>
			<td class="column20 style5 null"></td>
			<td class="column21 style5 null"></td>
			<td class="column22 style5 null"></td>
			<td class="column23 style5 null"></td>
			<td class="column24 style5 null"></td>
			<td class="column25 style5 null"></td>
			<td class="column26 style5 null"></td>
			<td class="column27 style5 null"></td>
			<td class="column28 style5 null"></td>
			<td class="column29 style3 null"></td>
			<td class="column30 style3 null"></td>
			<td class="column31 style3 null"></td>
			<td class="column32 style3 null"></td>
			<td class="column33 style3 null"></td>
			<td class="column34 style3 null"></td>
			<td class="column35 style3 null"></td>
			<td class="column36 style3 null"></td>
			<td class="column37 style3 null"></td>
			<td class="column38 style3 null"></td>
			<td class="column39 style3 null"></td>
			<td class="column40 style5 null"></td>
			<td class="column41 style3 null"></td>
			<td class="column42 style3 null"></td>
			<td class="column43 style3 null"></td>
			<td class="column44 style3 null"></td>
			<td class="column45 style5 null"></td>
			<td class="column46 style5 null"></td>
			<td class="column47 style5 null"></td>
			<td class="column48 style5 null"></td>
			<td class="column49 style5 null"></td>
			<td class="column50 style5 null"></td>
			<td class="column51 style3 null"></td>
			<td class="column52 style3 null"></td>
			<td class="column53 style3 null"></td>
			<td class="column54 style3 null"></td>
			<td class="column55 style3 null"></td>
			<td class="column56 style3 null"></td>
			<td class="column57 style8 null">&nbsp;</td>
			<td class="column58 style8 null">&nbsp;</td>
			<td class="column59 style8 null">&nbsp;</td>
		  </tr>
		</tbody>
	</table>

    <table border="1" cellpadding="0" cellspacing="0" id="sheet0" class="sheet0 gridlines">
		<tbody>
		  <tr valign="middle" align="center"class="row10">
			<td class="column0 style26 s style28" rowspan="5"><strong>CASO NO.</strong></td>
			<td class="column1 style34 s style81" colspan="7"><strong>IDENTIFICACI&Oacute;N DEL ENFERMO</strong></td>
			<td class="column8 style34 s style80" colspan="7"><strong>CONDICIONES AL INICIO DE LA POLIQUIMIOTERAPIA</strong></td>
			<td class="column15 style34 s style81" colspan="17"><strong>CONTROL DEL TRATAMIENTO</strong></td>
			<td class="column32 style34 s style81" colspan="7"><strong>CONDICIONES AL TERMINO DE LA PQT</strong></td>
			<td class="column39 style34 s style81" colspan="6"><strong>VIGILANCIA POSTRATAMIENTO</strong></td>
			<td class="column45 style98 s style99" colspan="4"><strong>GRADO DE DIACAPACIDAD</strong></td>
			<td class="column49 style29 s style31" rowspan="5"><strong>EDO. REACCIONAL ANTERIOR </strong></td>
			<td class="column50 style103 s style105" rowspan="5"><strong>EDO. REACCIONAL ACTUAL</strong></td>
			<td class="column51 style16 s"><strong>TERMINO DE VIG. POSTX</strong></td>
			<td class="column52 style17 null"></td>
			<td class="column53 style82 s style137" colspan="3" rowspan="4"><strong>ESTUDIO DE CONTACTOS</strong></td>
			<td class="column56 style103 s style105" rowspan="5"><strong>OBSERVACIONES</strong></td>
			<td class="column57 style63 s style64" rowspan="5"><strong>Registrados </strong></td>
			<td class="column58 style63 s style64" rowspan="5"><strong>Revisados</strong></td>
		  </tr>
		  <tr valign="middle" align="center"class="row11">
			<td class="column1 style26 s style28" rowspan="4"><strong>NOMBRE DEL ENFERMO</strong></td>
			<td class="column2 style67 s style68" rowspan="4"><strong>LOCALIDAD</strong></td>
			<td class="column3 style67 s style68" rowspan="4"><strong>MUNICIPIO</strong></td>
			<td class="column4 style27 s style28" rowspan="4"><strong>TIPO PAC.</strong></td>
			<td class="column5 style82 s style84" rowspan="4"><strong>EDAD</strong></td>
			<td class="column6 style26 s style85" rowspan="4"><strong>SEXO</strong></td>
			<td class="column7 style27 s style28" rowspan="4"><strong>DERECHO-HABIENCIA</strong></td>
			<td class="column8 style87 s style34" colspan="5"><strong>DIAGNOSTICO</strong></td>
			<td class="column13 style26 s style28" rowspan="4"><strong>CLASIFICACI&Oacute;N INTEGRAL</strong></td>
			<td class="column14 style26 s style28" rowspan="4"><strong>TIPO DE LEPRA</strong></td>
			<td class="column15 style75 s style77" rowspan="4"><strong>FECHA DE INICIO DE PQT </strong></td>
			<td class="column16 style128 s style133" colspan="13" rowspan="2"><strong>CALENDARIO DE DOSIS  </strong></td>
			<td class="column29 style43 s style45" colspan="3"><strong>CONTROL BACILOSC&Oacute;PICO</strong></td>
			<td class="column32 style111 s style113" rowspan="4"><strong>FECHA QUE TERMIN&Oacute; TRATAMIENTO  (12 ciclos)</strong></td>
			<td class="column33 style114 s style116" colspan="3"><strong>BACILOSCOPIA</strong></td>
			<td class="column36 style117 s style118" colspan="2"><strong>HISTOPATOLOG&Iacute;A</strong></td>
			<td class="column38 style111 s style113" rowspan="4"><strong>SITUACI&Oacute;N DEL PACIENTE QUE TERMIN&Oacute; TX</strong></td>
			<td class="column39 style112 s style113" rowspan="4"><strong>INICIO VIG POS TX  FECHA</strong></td>
			<td class="column40 style32 s style35" colspan="5"><strong>SEGUIMIENTO </strong></td>
			<td class="column45 style100 s style102" rowspan="4"><strong>O</strong></td>
			<td class="column46 style100 s style102" rowspan="4"><strong>M</strong></td>
			<td class="column47 style100 s style102" rowspan="4"><strong>P</strong></td>
			<td class="column48 style127 s style47" rowspan="4"><strong>G</strong></td>
			<td class="column51 style48 s style49" rowspan="4"><strong>FECHA </strong></td>
			<td class="column52 style124 s style126" rowspan="4"><strong>CONDICION</strong></td>
		  </tr>
		  <tr valign="middle" align="center"class="row12">
			<td class="column8 style88 s style90" colspan="3"><strong>BACILOSCOPIA</strong></td>
			<td class="column11 style95 s style90" colspan="2"><strong>HISTOPATOLOG&Iacute;A</strong></td>
			<td class="column29 style78 s style79" rowspan="3"><strong>FECHA</strong></td>
			<td class="column30 style54 s style57" colspan="2" rowspan="2"><strong>RESULTADO</strong></td>
			<td class="column33 style36 s style38" rowspan="3"><strong>FECHA</strong></td>
			<td class="column34 style39 s style42" colspan="2" rowspan="2"><strong>RESULTADO</strong></td>
			<td class="column36 style36 s style38" rowspan="3"><strong>FECHA</strong></td>
			<td class="column37 style46 s style47" rowspan="3"><strong>RESULTADO</strong></td>
			<td class="column40 style52 s style53" colspan="2"><strong>REVISI&Oacute;N CLINICA</strong></td>
			<td class="column42 style58 s style60" colspan="3"><strong>BACILOSCOPIA</strong></td>
		  </tr>
		  <tr valign="middle" align="center"class="row13">
			<td class="column8 style96 s style97" rowspan="2"><strong>FECHA</strong></td>
			<td class="column9 style69 s style70" colspan="2"><strong>RESULTADO</strong></td>
			<td class="column11 style91 s style92" rowspan="2"><strong>FECHA</strong></td>
			<td class="column12 style93 s style94" rowspan="2"><strong>RESULTADO</strong></td>
			<td class="column16 style71 s style73" colspan="13"><strong>D&iacute;a de toma supervisada/Situaci&oacute;n de las lesiones</strong></td>
			<td class="column40 style36 s style38" rowspan="2"><strong>FECHA</strong></td>
			<td class="column41 style46 s style47" rowspan="2"><strong>RESULTADO</strong></td>
			<td class="column42 style48 s style49" rowspan="2"><strong>FECHA</strong></td>
			<td class="column43 style50 s style51" colspan="2"><strong>RESULTADO</strong></td>
		  </tr>
		  <tr valign="middle" align="center"class="row14">
			<td class="column9 style18 s"><strong>IB</strong></td>
			<td class="column10 style19 s"><strong>IM %</strong></td>
			<td class="column16 style20 s"><strong>A&Ntilde;O</strong></td>
			<td class="column17 style21 s"><strong>Ene</strong></td>
			<td class="column18 style21 s"><strong>Feb</strong></td>
			<td class="column19 style21 s"><strong>Mar</strong></td>
			<td class="column20 style21 s"><strong>Abr</strong></td>
			<td class="column21 style21 s"><strong>May</strong></td>
			<td class="column22 style21 s"><strong>Jun</strong></td>
			<td class="column23 style21 s"><strong>Jul</strong></td>
			<td class="column24 style21 s"><strong>Ago</strong></td>
			<td class="column25 style21 s"><strong>Sep</strong></td>
			<td class="column26 style21 s"><strong>Oct</strong></td>
			<td class="column27 style21 s"><strong>Nov</strong></td>
			<td class="column28 style22 s"><strong>Dic</strong></td>
			<td class="column30 style18 s"><strong>IB</strong></td>
			<td class="column31 style19 s"><strong>IM %</strong></td>
			<td class="column34 style23 s"><strong>IB</strong></td>
			<td class="column35 style24 s"><strong>IM %</strong></td>
			<td class="column43 style23 s"><strong>IB</strong></td>
			<td class="column44 style24 s"><strong>IM %</strong></td>
			<td class="column53 style134 s"><strong>A&Ntilde;O</strong></td>
			<td class="column54 style134 s"><strong>CONTACTOS</strong></td>
			<td class="column55 style134 s"><strong>EXAMINADOS</strong></td>
		  </tr>
		</tbody>
	</table>';
            echo str_replace('<table border="1"></table>','',$reporte);
            echo '</body>
</html>';
		break;
	}
}
?>