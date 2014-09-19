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
    require_once('../include/clases/controlCalidad.php');
	
	$objHTML = new HTML();
	$objSelects = new Select();
	$estudio = new EstudioBac();
	
	$estudio->obtenerBD($_POST['id']);
	$isAjax = 1;
	
	$auxBR = '<br />';
}
?>

<script type="text/javascript">
function calcPromedioIB() {
	ind_baci_ft1 = parseInt($('#ind_baci_ft1').val());
	ind_baci_ft2 = parseInt($('#ind_baci_ft2').val());
	ind_baci_ft3 = parseInt($('#ind_baci_ft3').val());
    numFt = 0;
    
	if(ind_baci_ft1<0 || isNaN(ind_baci_ft1))
		ind_baci_ft1 = 0;

	if(ind_baci_ft2<0 || isNaN(ind_baci_ft2))
		ind_baci_ft2 = 0;
	
	if(ind_baci_ft3<0 || isNaN(ind_baci_ft3))
		ind_baci_ft3 = 0;
    
    if(ind_baci_ft1 != 0)
        numFt++;
    
    if(ind_baci_ft2 != 0)
        numFt++;
    
    if(ind_baci_ft3 != 0)
        numFt++;
	
	promedio = ( ind_baci_ft1 + ind_baci_ft2 + ind_baci_ft3 ) / numFt;
	promedio = Math.round(promedio);
	
	if(promedio==0 || isNaN(promedio))
		promedio = 1;

	$('#ib_promedio option[value='+promedio+']').attr("selected",true);
	$('#uniform-ib_promedio span').text( $('#ib_promedio option[value='+promedio+']').text() );
}

function calcPromedioIM() {
	bacilos_ft1 = parseInt($('#bacilos_ft1').val());
	bacilos_ft2 = parseInt($('#bacilos_ft2').val());
	bacilos_ft3 = parseInt($('#bacilos_ft3').val());
    numFt = 0;

	if(bacilos_ft1<0 || isNaN(bacilos_ft1))
		bacilos_ft1 = 0;

	if(bacilos_ft2<0 || isNaN(bacilos_ft2))
		bacilos_ft2 = 0;
	
	if(bacilos_ft3<0 || isNaN(bacilos_ft3))
		bacilos_ft3 = 0;
	
    if(bacilos_ft1 != 0)
        numFt++;
    
    if(bacilos_ft2 != 0)
        numFt++;
    
    if(bacilos_ft3 != 0)
        numFt++;
    
	promedio = ( bacilos_ft1 + bacilos_ft2 + bacilos_ft3 ) / numFt;
	promedio = Math.round(promedio);
    
    if(promedio==0 || isNaN(promedio))
		promedio = '';

	$('#im_promedio').val(promedio);
}

$(document).ready(function() {
	<?php 
	if ($load_files) 
	{ ?>
		deshabilitarCampos = new Array('cve_lesp_bacilos',
				'fecha_recepcion_bacilos',
				'rechazo_muestra_bacilos',
				'criterio_rechazo_bacilos',
				'otro_criterio_rechazo_bacilos',
				'fecha_resultado_bacilos',
				'ind_baci_ft1',
				'ind_baci_ft2',
				'ind_baci_ft3',
				'bacilos_ft1',
				'bacilos_ft2',
				'bacilos_ft3',
				'calidad_muestra_ft1',
				'calidad_muestra_ft2',
				'calidad_muestra_ft3',
				'tipo_bacilo_ft1',
				'tipo_bacilo_ft2',
				'tipo_bacilo_ft3',
				'ib_promedio',
				'im_promedio',
				'obser_bacilos',
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
		//setupCalendario("fecha_recepcion_bacilos");
		setupCalendario("fecha_resultado_bacilos");
	
		$('#ind_baci_ft1, #ind_baci_ft2, #ind_baci_ft3').change(calcPromedioIB);
		$('#bacilos_ft1, #bacilos_ft2, #bacilos_ft3').change(calcPromedioIM);
	
		$('#edoLab').change(function(){ 
			actualiza_select( { destino:'jurisLab', edo:'edoLab', tipo:'juris'} );
		});
        
        setValidacion('frmResultadoEstudio');
		<?php 
        if(empty($_POST)){
            echo '$("#fecha_resultado_bacilos").focus();';
        }
	} ?>
});
</script>
<?php
$objHTML->startFieldset('Informe de Resultado de Baciloscopía');
	$calidad_muestra = array( 1=>'Adecuada', 
							  0=>'Inadecuada');
							  
	$isGlobias = array( 2=>'Si', 
						1=>'No');

	$objHTML->inputText('Clave LESP:', 'cve_lesp_bacilos', $estudio->folioLaboratorio, array('maxlength'=>'10'));
	
	echo $auxBR;
	$objHTML->inputText('Fecha Recepción:', 'fecha_recepcion_bacilos', formatFechaObj($estudio->fechaRecepcion), array('class'=>'validate[required]'));
	
	$objHTML->inputText('Fecha Resultado:', 'fecha_resultado_bacilos', formatFechaObj($estudio->fechaResultado), array('class'=>'validate[required]'));
	echo '<br /><br />';
	$objHTML->inputCheckbox('Rechazo Muestra', 'rechazo_muestra_bacilos', 1, $estudio->muestraRechazada);
	
	$objSelects->SelectCatalogo('Criterio Rechazo', 'criterio_rechazo_bacilos', 'catMotivoRechazo', $estudio->idCatMotivoRechazo);
	
	echo $auxBR;
	$objHTML->inputText('Otro Criterio de Rechazo', 'otro_criterio_rechazo_bacilos', $estudio->otroMotivoRechazo, array('size'=>40));
	
	echo '<br /><br />';
	$objHTML->label('Frotis 1 ( LO )', array('style'=>'text-decoration:underline'));
	echo '<br />';
	$objSelects->SelectCatalogo('Indice Bacteriológico:', 'ind_baci_ft1', 'catBaciloscopia', $estudio->idCatBacFrotis1, array('class'=>'validate[required]'), false);
	
	//$objSelects->SelectCatalogo('Tipo Bacilos:', 'tipo_bacilo_ft1', 'catTiposBacilos', $estudio->bacIdCatTiposBacilosFrotis1, array('class'=>'validate[required]'));
	$objHTML->inputSelect('Globias:', 'tipo_bacilo_ft1', $isGlobias, $estudio->bacIdCatTiposBacilosFrotis1, array('class'=>'validate[required]'));
	echo '<br />';
	$objHTML->inputText('Ind. Morf. %:', 'bacilos_ft1', $estudio->bacPorcViaFrotis1, array('class'=>'validate[required,custom[integer]]','placeholder'=>'%'));
	
	echo $auxBR;
	/*$objHTML->label('Calidad Muestra:');
	$objHTML->inputRadio('calidad_muestra_ft1', $calidad_muestra, $estudio->bacCalidadAdecFrotis1, array('class'=>'validate[required]'));*/
	$objHTML->inputSelect('Calidad Muestra:', 'calidad_muestra_ft1', $calidad_muestra, array('class'=>'validate[required]'));
	
	echo '<br /><br />';
	$objHTML->label('Frotis 2 ( LC )', array('style'=>'text-decoration:underline'));
	echo '<br />';
	$objSelects->SelectCatalogo('Indice Bacteriológico:', 'ind_baci_ft2', 'catBaciloscopia', $estudio->idCatBacFrotis2,NULL,false);
	
	//$objSelects->SelectCatalogo('Tipo Bacilos:', 'tipo_bacilo_ft2', 'catTiposBacilos', $estudio->bacIdCatTiposBacilosFrotis2);
	$objHTML->inputSelect('Globias:', 'tipo_bacilo_ft2', $isGlobias, $estudio->bacIdCatTiposBacilosFrotis2);
	echo '<br />';
	$objHTML->inputText('Ind. Morf. %:', 'bacilos_ft2', $estudio->bacPorcViaFrotis2, array('class'=>'validate[optional,custom[integer]]','placeholder'=>'%'));
	
	echo $auxBR;
	/*$objHTML->label('Calidad Muestra:');
	$objHTML->inputRadio('calidad_muestra_ft2', $calidad_muestra, $estudio->bacCalidadAdecFrotis2);*/
	$objHTML->inputSelect('Calidad Muestra:', 'calidad_muestra_ft2', $calidad_muestra, $estudio->bacCalidadAdecFrotis2);
	
	echo '<br /><br />';
	$objHTML->label('Frotis 3 ( MN )', array('style'=>'text-decoration:underline'));
	echo '<br />';
	$objSelects->SelectCatalogo('Indice Bacteriológico:', 'ind_baci_ft3', 'catBaciloscopia', $estudio->idCatBacFrotis3,NULL,false);
	
	//$objSelects->SelectCatalogo('Tipo Bacilos:', 'tipo_bacilo_ft3', 'catTiposBacilos', $estudio->bacIdCatTiposBacilosFrotis3);
	$objHTML->inputSelect('Globias:', 'tipo_bacilo_ft3', $isGlobias, $estudio->bacIdCatTiposBacilosFrotis3);
	echo '<br />';
	$objHTML->inputText('Ind. Morf. %:', 'bacilos_ft3', $estudio->bacPorcViaFrotis3, array('class'=>'validate[optional,custom[integer]]','placeholder'=>'%'));
	
	echo $auxBR;
	/*$objHTML->label('Calidad Muestra:');
	$objHTML->inputRadio('calidad_muestra_ft3', $calidad_muestra, $estudio->bacCalidadAdecFrotis3);*/
	$objHTML->inputSelect('Calidad Muestra:', 'calidad_muestra_ft3', $calidad_muestra, $estudio->bacCalidadAdecFrotis3);
	
	echo '<br /><br />';
	$objHTML->label('Promedio', array('style'=>'text-decoration:underline'));
	echo '<br />';
	$objSelects->SelectCatalogo('Indice Bacteriológico:', 'ib_promedio', 'catBaciloscopia', $estudio->idCatBac, array('class'=>'validate[required]'), false);
	
	$objHTML->inputText('Indice Morfológico', 'im_promedio', $estudio->bacIM, array('class'=>'validate[required,custom[integer]]', 'size'=>'10', 'placeholder'=>'%'));
	echo '<br /><br />';
	
	$objCatalogo = new Catalogo();
	$activos = "";
	if($isAjax == 0)
		$activos = "[activo] = 1 AND";
	
    $queryAnalista = 'SELECT [idCatAnalistaLab],[nombre] FROM [catAnalistaLab] WHERE '.$activos.' 1=1 ';
    if($_SESSION[EDO_USR_SESSION] != 0)
        $queryAnalista .= ' AND [idCatEstado]='.$_SESSION[EDO_USR_SESSION];
    
	$objCatalogo->setQuery($queryAnalista);
	$objHTML->inputSelect('Analista:', 'analista', $objCatalogo->getValores(), $estudio->idCatAnalistaLab, array('class'=>'validate[required]'));
	
    $querySupervisor = 'SELECT [idCatSupervisorLab],[nombre] FROM [catSupervisorLab] WHERE '.$activos.' 1=1 ';
    if($_SESSION[EDO_USR_SESSION] != 0)
        $querySupervisor .= ' AND [idCatEstado]='.$_SESSION[EDO_USR_SESSION];
    
	$objCatalogo->setQuery($querySupervisor);
	$objHTML->inputSelect('Supervisor:', 'supervisor', $objCatalogo->getValores(), $estudio->idCatSupervisorLab, array('class'=>'validate[required]'));
	
	echo '<br /><br />';
	$objSelects->selectEstado('edoLab', $resultadoGuardado ? $infUni['idCatEstado'] : $estudio->idCatEstadoLaboratorio);			
	$objSelects->selectJurisdiccion('jurisLab', $resultadoGuardado ? $infUni['idCatEstado'] : $estudio->idCatEstadoLaboratorio, $resultadoGuardado ? $infUni['idCatJurisdiccion'] : $estudio->idCatJurisdiccionLaboratorio, array('class'=>'validate[required]'));
	
	echo '<br /><br />';
	echo '<div align="center">';
	$objHTML->label('Observaciones:');
	echo '<br />';
	$objHTML->inputTextarea('','obser_bacilos', $estudio->bacObservaciones,array('cols'=>55));
	echo '</div>';
$objHTML->endFieldset();

/*if($isAjax) {
    $objCalidad = new controlCalidad();
    $objCalidad->obtenerByBacilos($_POST['id']);

    if($objCalidad->idcontrolCalidad)
        include_once('../content/controlCalidadMuestra.php');
    
    echo '<style type="text/css">div.selector span { max-width: none !important; }</style>';
}*/
?>