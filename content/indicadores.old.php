<script type="text/javascript">
    $(document).ready(function() {
        setupCalendario('fecha_inicio');
        setupCalendario('fecha_fin');
        
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

echo '<h2 align="center">INDICADORES B&Aacute;SICOS DE DESEMPE&Ntilde;O</h2>';

$objHTML = new HTML();
$objSelects = new Select();
$objHelp = new Helpers();

$objHTML->startForm('formReporte', '?mod=ind', 'POST');

    $objHTML->startFieldset();
    echo '<div align="center">';
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
        $indDiaOpo = new IndicadorDiagnosticoOportuno();
        
        $indDiaOpo->idCatEstado = $_SESSION[EDO_USR_SESSION];
        $indDiaOpo->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indDiaOpo->fechaInicio = $_POST['fecha_inicio'];
        $indDiaOpo->fechaFin = $_POST['fecha_fin'];
        
        $indDiaOpo->calcular();
        //$indDiaOpo->imprimir();
        
        
        $indCalDx = new IndicadorCalidadDx();
        
        $indCalDx->idCatEstado = $_SESSION[EDO_USR_SESSION];
        $indCalDx->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indCalDx->fechaInicio = $_POST['fecha_inicio'];
        $indCalDx->fechaFin = $_POST['fecha_fin'];
        
        $indCalDx->calcular();
        //$indCalDx->imprimir();
        
        
        $indCobTx = new IndicadorCoberturaTx();
        
        $indCobTx->idCatEstado = $_SESSION[EDO_USR_SESSION];
        $indCobTx->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indCobTx->fechaInicio = $_POST['fecha_inicio'];
        $indCobTx->fechaFin = $_POST['fecha_fin'];
        
        $indCobTx->calcular();
        //$indCobTx->imprimir();
        
        
        $indExaCon = new IndicadorExamenContactos();
        
        $indExaCon->idCatEstado = $_SESSION[EDO_USR_SESSION];
        $indExaCon->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indExaCon->fechaInicio = $_POST['fecha_inicio'];
        $indExaCon->fechaFin = $_POST['fecha_fin'];
        
        $indExaCon->calcular();
        //$indExaCon->imprimir();
        
        
        $indTasPre = new IndicadorTasaPrevalencia();
        $indTasPre->idCatEstado = $_SESSION[EDO_USR_SESSION];
        $indTasPre->idCatJurisdiccion = $_POST['jurisdiccion'];
        $indTasPre->fechaInicio = $_POST['fecha_inicio'];
        $indTasPre->fechaFin = $_POST['fecha_fin'];
        
        $indTasPre->calcular();
        //$indTasPre->imprimir();
        
        $estado = $objHelp->getNombreEstado($_SESSION[EDO_USR_SESSION]);
        
        $jurisdiccion = $objHelp->getNombreJurisdiccion($_SESSION[EDO_USR_SESSION], $_POST['jurisdiccion']);
        $jurisdiccion = $jurisdiccion ? ', Jurisdicci&oacute;n: '.htmlentities($jurisdiccion) : '';
        
        echo '<br><h3>Estado: '.$estado.' '.$jurisdiccion.'</h3><br>';
        
        
        /**************************************************/
        
        // aqui va el codigo de la grafica
        
        /**************************************************/
        
        
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
                    <td>'.$indDiaOpo->resultado.'</td>
                    <td>'.$indDiaOpo->ponderacion.'</td>
                    <td>'.$indDiaOpo->indice.'</td>
                </tr>
                <tr align="center">
                    <td align="left">'.$indCalDx->nombre.'</td>
                    <td>'.$indCalDx->estandar.'%</td>
                    <td>'.$indCalDx->resultado.'</td>
                    <td>'.$indCalDx->ponderacion.'</td>
                    <td>'.$indCalDx->indice.'</td>
                </tr>
                <tr align="center">
                    <td align="left">'.$indCobTx->nombre.'</td>
                    <td>'.$indCobTx->estandar.'%</td>
                    <td>'.$indCobTx->resultado.'</td>
                    <td>'.$indCobTx->ponderacion.'</td>
                    <td>'.$indCobTx->indice.'</td>
                </tr>
                <tr align="center">
                    <td align="left">'.$indExaCon->nombre.'</td>
                    <td>'.$indExaCon->estandar.'%</td>
                    <td>'.$indExaCon->resultado.'</td>
                    <td>'.$indExaCon->ponderacion.'</td>
                    <td>'.$indExaCon->indice.'</td>
                </tr>
                <tr align="center">
                    <td align="left">'.$indTasPre->nombre.'</td>
                    <td>'.$indTasPre->estandar.'</td>
                    <td>'.$indTasPre->resultado.'</td>
                    <td>'.$indTasPre->ponderacion.'</td>
                    <td>'.$indTasPre->indice.'</td>
                </tr>
                <tr>
                    <td align="right" colspan="4"><strong>Indice del desempe&ntilde;o</strong></td>
                    <td align="center"><strong>'.($indDiaOpo->indice + $indCalDx->indice + $indCobTx->indice + $indExaCon->indice + $indTasPre->indice).'</strong></td>
                </tr>
                </tbody>
            </table>
        </div>';
        
        echo '<br><br><div class="datagrid">
            <table>
                <thead>
                <tr align="center">
                    <th>Valor del desempe&ntilde;o</th>
                    <th>Valoraci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>80 a m&aacute;s puntos</td>
                    <td>Sobresaliente</td>
                </tr>
                <tr>
                    <td>60 a 79 puntos</td>
                    <td>Satisfactorio</td>
                </tr>
                <tr>
                    <td>40 a 59 puntos</td>
                    <td>M&iacute;nimo</td>
                </tr>
                <tr>
                    <td><40 puntos</td>
                    <td>Precario</td>
                </tr>
                </tbody>
            </table>
        </div><br>';
        
    }
    
$objHTML->endFieldset();
?>