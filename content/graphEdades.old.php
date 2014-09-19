<script src="include/RGraph/libraries/RGraph.common.core.js" ></script>
<script src="include/RGraph/libraries/RGraph.bar.js" ></script>
<!--[if lt IE 9]><script src="excanvas/excanvas.js"></script><![endif]-->

<script type="text/javascript">
    $(document).ready(function() {
        setupCalendario('fecha_inicio');
        setupCalendario('fecha_fin');
        
        $('#jurisdiccion option:first').text('Estatal');
	
    });
</script>

<?PHP 

echo '<h2 align="center">Reporte por Edades y Genero</h2>';

$objHTML = new HTML();
$objSelects = new Select();

$objHTML->startForm('formReporte', '?mod=ind', 'POST');

    $objHTML->startFieldset();
    echo '<div align="center">';
            $objSelects->selectJurisdiccion('jurisdiccion', $_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion']);
			$objSelects->SelectCatalogo('Sexo', 'idCatSexo', 'catSexo');
            $objHTML->label('Fecha: ');
            $objHTML->inputText('', 'fecha_inicio', $_POST['fecha_inicio'] ? $_POST['fecha_inicio'] : '01-'.date('m-Y'), array('placeholder'=>'Inicio'));
            $objHTML->inputText('', 'fecha_fin', $_POST['fecha_fin'] ? $_POST['fecha_fin'] : date("d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1)).'-'.date('m-Y'), array('placeholder'=>'Fin'));
            $objHTML->inputSubmit('generarReporte', 'Generar');
    echo '</div>';
    $objHTML->endFieldset();
    
$objHTML->endFormOnly();

$objHTML->startFieldset();
echo '<canvas id="myBar" width="500" height="250" style="float: left;">[No canvas support]</canvas>';

echo "
<script>
       
            var bar = new RGraph.Bar('myBar', [12,13,16,15,16,19,19,12,23,16,13,24])
                .Set('gutter.left', 35)
                .Set('title', 'A basic chart')
                .Set('labels', ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])
                .Draw();
        
    </script>
";
$objHTML->endFieldset();
?>