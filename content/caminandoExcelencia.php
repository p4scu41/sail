<script src="js/caminandoExcelencia.js" ></script>
<!--[if lt IE 9]><script src="excanvas/excanvas.js"></script><![endif]-->

<script type="text/javascript">
    $(document).ready(function(){
        $("#generarReporte").click(generaIndicadores);
    });
</script>

<?PHP 

echo '<h2 align="center">CAMINANDO A LA EXCELENCIA</h2>';

$objHTML = new HTML();
$periodos = array(
			1 => "Primer Trimestre",
			2 => "Segundo Trimestre",
			3 => "Primer Semestre",
			4 => "Tercero Trimestre",
			5 => "Cuarto Trimestre",
			6 => "Segundo Semestre",
			7 => "Anual");

$years = array();

$currentYear = date("Y");
$iniYear = 2005;

for($aa = $iniYear; $aa <= $currentYear; $aa++)
{
	$years[] = $aa;
}

$objHTML->startForm('formReporte', '#', 'POST');

    $objHTML->startFieldset();
    echo '<div align="center">';
            $objHTML->inputSelect('Periodo', 'periodo', $periodos);
			$objHTML->inputSelect2('Año', 'anio', $years);
            /*$objHTML->label('Fecha: ');
            $objHTML->inputText('', 'fecha_inicio', $_POST['fecha_inicio'] ? $_POST['fecha_inicio'] : '01-'.date('m-Y'), array('placeholder'=>'Inicio'));
            $objHTML->inputText('', 'fecha_fin', $_POST['fecha_fin'] ? $_POST['fecha_fin'] : date("d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1)).'-'.date('m-Y'), array('placeholder'=>'Fin'));*/
            $objHTML->inputButton('generarReporte', 'Generar Reporte');
    echo '</div>';
    $objHTML->endFieldset();
    
$objHTML->endFormOnly();


$objHTML->startFieldset();
echo '<div id="caminoExcelencia" style="width: 100%;"></div>';
$objHTML->endFieldset();
?>