<?php
$load_files = true;
$filesIncluded = get_required_files(); 

// verificar que los archivos necesarios esten incluidos
foreach ($filesIncluded as $file_included) {
	if (preg_match("/conf.php/", $file_included)) {
		$load_files = false;
		break;
	}
}
$isAjax = 0;
// Si no estan incluidos, debemos cargarlos
// Esto quiere decir que la peticion es AJAX
if ($load_files) {
	session_start();
	$SEGURO = TRUE;
	
	require_once('../include/var_global.php');
	require_once('../include/conf.php');
	require_once('../include/log.php');
	require_once('../include/bdatos.php');
	require_once('../include/fecha_hora.php');
	require_once('../include/HTML.class.php');
	require_once('../include/Catalogo.class.php');
	require_once('../include/Select.class.php');
	require_once('../include/clasesLepra.php');
	require_once('../include/enviaCorreo.php');
	require_once('../include/commandSMS.php');
    require_once('../include/clases/controlCalidad.php');
	
	$objHTML = new HTML();
	$objSelects = new Select();
	$estudio = new EstudioHis();
	
	$estudio->obtenerBD($_POST['id']);
	$isAjax = 1;
	$auxBR = '<br />';
}
?>

<script type="text/javascript">
$(document).ready(function() {
	<?php 
	if ($load_files) 
	{ ?>
		deshabilitarCampos = new Array('cve_lesp_histo',
				'fecha_recepcion_histo',
				'rechazo_muestra_histo',
				'criterio_rechazo_histo',
				'otro_criterio_rechazo_histo',
				'fecha_resultado_histo',
				'macroscopica',
				'microscopica',
				'resultado_histo',
				'tipo_resultado',
				'edoLab',
				'jurisLab',
				'analista',
				'supervisor',
                'calidadMuestra',
                'sinMuestra',
                'sinElemeCelu',
                'abunEritro',
                'otrosCalidadMuestra',
                'calidadFrotis',
                'calidadFrotisTipo',
                'otrosCalidadFrotis',
                'calidadTincion',
                'crisFucsi',
                'preciFucsi',
                'calenExce',
                'decoInsufi',
                'otrosCalidadTincion',
                'calidadLectura',
                'falPosi',
                'falNega',
                'difMas2IB',
                'difMas25IM',
                'otrosCalidadLectura',
                'calidadResultado',
                'soloSimbCruz',
                'soloPosiNega',
                'noEmiteIM',
                'otrosCalidadResultado',
                'recomendacion');
	
		for(campo in deshabilitarCampos) {
			$('*[name='+deshabilitarCampos[campo]+']').each(function(){
                $(this).attr('disabled',true);
            });
		}
		<?php 
	}
	else 
	{ ?>
		//setupCalendario("fecha_recepcion_histo");
		setupCalendario("fecha_resultado_histo");
	
		$('#edoLab').change(function(){ 
			actualiza_select( { destino:'jurisLab', edo:'edoLab', tipo:'juris'} );
		});
        
        setValidacion('frmResultadoEstudio');
		<?php 
        if(empty($_POST)){
            echo '$("#fecha_resultado_histo").focus();';
        }
	} ?>
});
</script>

<?php
$objHTML->startFieldset('Informe Histopatológico de Resultados');

	$objHTML->inputText('Clave LESP:', 'cve_lesp_histo', $estudio->folioLaboratorio, array('maxlength'=>'10'));
	echo $auxBR;
	$objHTML->inputText('Fecha Recepción:', 'fecha_recepcion_histo', formatFechaObj($estudio->fechaRecepcion), array('class'=>'validate[required]'));
	
	$objHTML->inputText('Fecha Resultado:', 'fecha_resultado_histo', formatFechaObj($estudio->fechaResultado), array('class'=>'validate[required]'));
	echo '<br /><br />';
	$objHTML->inputCheckbox('Rechazo Muestra', 'rechazo_muestra_histo', 1, $estudio->muestraRechazada);
	
	$objSelects->SelectCatalogo('Criterio de Rechazo', 'criterio_rechazo_histo', 'catMotivoRechazo', $estudio->idCatMotivoRechazo);
	echo $auxBR;
	$objHTML->inputText('Otro Criterio de Rechazo', 'otro_criterio_rechazo_histo', $estudio->otroMotivoRechazo, array('size'=>40));
	echo '<br /><br /><div align="center">';
	$objHTML->label('Descripción Macroscópica:');
	echo '<br />';
	$objHTML->inputTextarea('','macroscopica', $estudio->hisDescMacro, array('class'=>'validate[required]', 'cols'=>60));
	echo '<br />';
	$objHTML->label('Descripción Microscópica:');
	echo '<br />';
	$objHTML->inputTextarea('','microscopica', $estudio->hisDescMicro, array('class'=>'validate[required]', 'cols'=>60));
	echo '<br />';
	$objHTML->label('Resultado:');
	echo '<br />';
	$objHTML->inputTextarea('','resultado_histo', $estudio->hisResultado, array('class'=>'validate[required]', 'cols'=>60));
	echo '</div><br />';
	
	$objSelects->SelectCatalogo('Tipo Resultado', 'tipo_resultado', 'catHistopatologia', $estudio->idCatHisto, array('class'=>'validate[required]'));
	echo $auxBR.$auxBR;
	
	$objCatalogo = new Catalogo();
	$activos = "";
	if($isAjax == 0)
		$activos = "[activo] = 1 AND";
	
	/*$queryAnalista = 'SELECT [idCatAnalistaLab],[nombre] FROM [catAnalistaLab] WHERE '.$activos.' 1=1 ';
    if($_SESSION[EDO_USR_SESSION] != 0)
        $queryAnalista .= ' AND [idCatEstado]='.$_SESSION[EDO_USR_SESSION];
    
	$objCatalogo->setQuery($queryAnalista);
	$objHTML->inputSelect('Analista:', 'analista', $objCatalogo->getValores(), $estudio->idCatAnalistaLab, array('class'=>'validate[required]'));*/
	
    $querySupervisor = 'SELECT [idCatSupervisorLab],[nombre] FROM [catSupervisorLab] WHERE '.$activos.' 1=1 ';
    if($_SESSION[EDO_USR_SESSION] != 0)
        $querySupervisor .= ' AND [idCatEstado]='.$_SESSION[EDO_USR_SESSION];
    
	$objCatalogo->setQuery($querySupervisor);
	$objHTML->inputSelect('Diagnosticó:', 'supervisor', $objCatalogo->getValores(), $estudio->idCatSupervisorLab, array('class'=>'validate[required]'));
	
	echo '<br /><br />';
	$objSelects->selectEstado('edoLab', $resultadoGuardado ? $infUni['idCatEstado'] : $estudio->idCatEstadoLaboratorio);			
	$objSelects->selectJurisdiccion('jurisLab', $resultadoGuardado ? $infUni['idCatEstado'] : $estudio->idCatEstadoLaboratorio, $resultadoGuardado ? $infUni['idCatJurisdiccion'] : $estudio->idCatJurisdiccionLaboratorio, array('class'=>'validate[required]'));
	
	echo '<br /><br />';
$objHTML->endFieldset();

/*if($isAjax) {
    $objCalidad = new controlCalidad();
    $objCalidad->obtenerByHisto($_POST['id']);

    if($objCalidad->idcontrolCalidad)
        include_once('../content/controlCalidadMuestra.php');
    
    echo '<style type="text/css">div.selector span { max-width: none !important; }</style>';
}*/
?>