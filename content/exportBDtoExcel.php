<?php
session_start();
require_once('../include/var_global.php');
require_once('../include/bdatos.php');
require_once('../include/log.php');
require_once('../include/funciones.php');
require_once('../include/fecha_hora.php');
require_once('../include/PHPExcel.php');

$connectionBD = conectaBD();

if( !isset($_SESSION[ID_USR_SESSION]) )
    die();

// Solo se permite por el metodo POST
if(empty($_POST))
    die();

$query = 'SELECT [pacientes].[idPaciente]
    ,[pacientes].[cveExpediente]
    ,DATEPART(year, [pacientes].[fechaNotificacion]) AS anio
    ,DATEPART(quarter, [pacientes].[fechaNotificacion]) AS trimestre
    ,[catTipoPaciente].[descripcion] AS tipoPaciente
    ,[pacientes].[apellidoPaterno]
    ,[pacientes].[apellidoMaterno]
    ,[pacientes].[nombre]
    ,[lepra].[dbo].[diferenciaAnos]([pacientes].[fechaNacimiento], GETDATE()) AS edad
    ,[catSexo].[sexo]
    ,[pacientes].[ocupacion]
    ,[catEstado].[nombre] AS EstadoNacimiento
    ,[catMunicipio].[nombre] AS MunicipioNacimiento
    ,[pacientes].[calle]
    ,[pacientes].[noExterior]
    ,[pacientes].[noInterior]
    ,[pacientes].[colonia]
    ,[catLocalidad].[nombre] AS localidad
    ,municipioResidencia.nombre as municipio
    ,[catJurisdiccion].[nombre] as jurisdicion
    ,(SELECT nombre FROM [catEstado] WHERE [catEstado].[idCatEstado] = [pacientes].[idCatEstado]) AS estado
    ,[pacientes].[anosRadicando]
    ,[pacientes].[mesesRadicando]
    ,[catUnidad].[nombreEntidad] AS edoNotificante
    ,jurisdicionNotificante.[nombre] AS jurisNotificante
    ,[catUnidad].[nombreMunicipio] AS muniNotificante
    ,[catUnidad].[tipoUnidad] AS tipoUniNotificante
    ,[catUnidad].[idCatUnidad] AS uniNotificante
    ,[catUnidad].[nombreUnidad] AS nombreUniNoficante
    ,[catUnidad].[tipología] AS nivelAtencion
    ,[catUnidad].[institucion]
    ,[pacientes].[otraInstitucionUnidadNotificante]
    ,(SELECT [descripcion] FROM [catInstituciones] WHERE [catInstituciones].[idCatInstituciones] = [pacientes].[idCatInstitucionDerechohabiencia]) AS  derechohabiencia
    ,[pacientes].[otraDerechohabiencia]
    ,[catFormaDeteccion].[descripcion] AS formaDeteccion
    ,CONVERT(VARCHAR(10), [pacientes].[fechaInicioPadecimiento], 103) AS fechaInicioPadecimiento
    ,CONVERT(VARCHAR(10), [pacientes].[fechaDiagnostico], 103) AS fechaDiagnostico
    ,CONVERT(VARCHAR(10), [pacientes].[fechaNotificacion], 103) AS fechaNotificacion
    ,[pacientes].[semanaEpidemiologica]
    ,CONVERT(VARCHAR(10), [pacientes].[fechaInicioPQT], 103) AS fechaInicioPQT
    ,(SELECT COUNT(*) FROM [diagramaDermatologico] WHERE [idCatTipoLesion] = 1 AND 
        [diagramaDermatologico].[idDiagnostico] = [diagnostico].[idDiagnostico]) AS NodulosAislados
    ,(SELECT COUNT(*) FROM [diagramaDermatologico] WHERE [idCatTipoLesion] = 2 AND 
        [diagramaDermatologico].[idDiagnostico] = [diagnostico].[idDiagnostico]) AS NodulosAgrupados
    ,(SELECT COUNT(*) FROM [diagramaDermatologico] WHERE [idCatTipoLesion] = 3 AND 
        [diagramaDermatologico].[idDiagnostico] = [diagnostico].[idDiagnostico]) AS ManchasHipopigmentadas
    ,(SELECT COUNT(*) FROM [diagramaDermatologico] WHERE [idCatTipoLesion] = 4 AND 
        [diagramaDermatologico].[idDiagnostico] = [diagnostico].[idDiagnostico]) AS ManchasEritematosas
    ,(SELECT COUNT(*) FROM [diagramaDermatologico] WHERE [idCatTipoLesion] = 5 AND 
        [diagramaDermatologico].[idDiagnostico] = [diagnostico].[idDiagnostico]) AS PlacasInfiltradas
    ,(SELECT COUNT(*) FROM [diagramaDermatologico] WHERE [idCatTipoLesion] = 6 AND 
        [diagramaDermatologico].[idDiagnostico] = [diagnostico].[idDiagnostico]) AS ZonasDeAnestesia
    ,(SELECT COUNT(*) FROM [diagramaDermatologico] WHERE [idCatTipoLesion] = 7 AND 
        [diagramaDermatologico].[idDiagnostico] = [diagnostico].[idDiagnostico]) AS NudosidadesOtras
    ,[catBaciloscopia].[descripcion] AS resultBaciloscopia
    ,[bacIM] AS indiceMorfologico
    ,[catHistopatologia].[descripcion] as resultHistopatologia
    ,[diagnostico].[discOjoIzq]
    ,[diagnostico].[discOjoDer]
    ,[diagnostico].[discManoIzq]
    ,[diagnostico].[discManoDer]
    ,[diagnostico].[discPieIzq]
    ,[diagnostico].[discPieDer]
    ,[catNumeroLesiones].[descripcion] AS numeroLesiones
    ,(SELECT [descripcion] FROM [catEstadoReaccional] WHERE [idCatEstadoReaccional] = [diagnostico].[idCatEstadoReaccionalAnt]) AS estadoReaccionalAnt
    ,(SELECT [descripcion] FROM [catEstadoReaccional] WHERE [idCatEstadoReaccional] = [diagnostico].[idCatEstadoReaccionalAct]) AS estadoReaccionalAct
    ,CONVERT(VARCHAR(10), [diagnostico].[fechaReaccionAnteriorTipI], 103) AS fechaReaccionAnteriorTipI
    ,CONVERT(VARCHAR(10), [diagnostico].[fechaReaccionAnteriorTipII], 103) AS fechaReaccionAnteriorTipII
    ,(SELECT [descripcion] FROM [catClasificacionLepra] WHERE [diagnostico].[idCatClasificacionLepra] = [idCatClasificacionLepra]) AS clasificacionLepra
    ,(SELECT [descipcion] FROM [catEstadoPaciente] WHERE [diagnostico].[idCatEstadoPaciente] = [idCatEstadoPaciente]) AS estadoPaciente
    ,(SELECT [nombre] FROM [catEstado] WHERE [idCatEstado] = [diagnostico].[idCatEstadoAdqEnf]) AS [EstadoAdquirioEnf]
    ,municipioEnfermedad.nombre AS [municipioAdquirioEnf]
    ,localidadEnfermedad.nombre AS [localidadAdquirioEnf]
    ,[diagnostico].[observaciones]
    ,(SELECT COUNT(*) FROM [contactos] WHERE [idDiagnostico]=[diagnostico].[idDiagnostico]) as numContactos
    ,unidadTratante.[nombreEntidad] AS edoTratante
    ,jurisdicionTratante.[nombre] AS jurisTratante
    ,unidadTratante.[nombreMunicipio] AS muniTratante
    ,unidadTratante.[tipoUnidad] AS tipoUniTratante
    ,unidadTratante.[idCatUnidad] AS uniTratante
    ,unidadTratante.[nombreUnidad] AS nombreUniTratante
    ,unidadTratante.[tipología] AS nivelAtencionTratante
    ,unidadTratante.[institucion] AS institucionTratante
    ,[pacientes].[otraInstitucionTratante]
FROM 
    [pacientes]
LEFT JOIN 
    [diagnostico] ON
    [pacientes].[idPaciente] = [diagnostico].[idPaciente] 
INNER JOIN [catTipoPaciente] ON 
    [pacientes].[idCatTipoPaciente] = [catTipoPaciente].[idCatTipoPaciente]
INNER JOIN [catSexo] ON 
    [pacientes].[sexo] = [catSexo].[idSexo]
INNER JOIN [catEstado] ON 
    [pacientes].[idCatEstadoNacimiento] = [catEstado].[idCatEstado]
INNER JOIN [catMunicipio] ON 
    [pacientes].[idCatEstadoNacimiento] = [catMunicipio].[idCatEstado] AND 
    [pacientes].[idCatMunicipioNacimiento] = [catMunicipio].[idCatMunicipio]
INNER JOIN [catLocalidad] ON 
    [pacientes].[idCatEstado] = [catLocalidad].[idCatEstado] AND
    [pacientes].[idCatMunicipio] = [catLocalidad].[idCatMunicipio] AND
    [pacientes].[idCatLocalidad] = [catLocalidad].[idCatLocalidad]
INNER JOIN [catMunicipio] municipioResidencia ON 
    [pacientes].[idCatEstado] = municipioResidencia.[idCatEstado] AND 
    [pacientes].[idCatMunicipio] = municipioResidencia.[idCatMunicipio]
INNER JOIN [catJurisdiccion] ON 
    [pacientes].[idCatEstado] = [catJurisdiccion].[idCatEstado] AND 
    municipioResidencia.[idCatJurisdiccion] = [catJurisdiccion].[idCatJurisdiccion]
INNER JOIN [catUnidad] ON
    [pacientes].[idCatUnidadNotificante] = [catUnidad].[idCatUnidad]
INNER JOIN [catMunicipio] municipioNotificacion ON 
    [catUnidad].[idCatEstado] = municipioNotificacion.[idCatEstado] AND 
    [catUnidad].[idCatMunicipio] = municipioNotificacion.[idCatMunicipio]
INNER JOIN [catJurisdiccion] jurisdicionNotificante ON 
    [catUnidad].[idCatEstado] = jurisdicionNotificante.[idCatEstado] AND 
    municipioNotificacion.[idCatJurisdiccion] = jurisdicionNotificante.[idCatJurisdiccion]
INNER JOIN [catFormaDeteccion] ON
    [pacientes].[idCatFormaDeteccion] = [catFormaDeteccion].[idCatFormaDeteccion]
LEFT JOIN [estudiosBac] ON
    [estudiosBac].[idEstudioBac] = (SELECT TOP 1 [idEstudioBac] FROM [estudiosBac] 
        WHERE idDiagnostico = [diagnostico].[idDiagnostico] ORDER BY [fechaResultado] DESC)
LEFT JOIN [catBaciloscopia] ON 
    [estudiosBac].[idCatBac] = [catBaciloscopia].[idCatBaciloscopia]
LEFT JOIN [estudiosHis] ON
    [estudiosHis].[idEstudioHis] = (SELECT TOP 1 [idEstudioHis] FROM [estudiosHis] 
        WHERE idDiagnostico = [diagnostico].[idDiagnostico] ORDER BY [fechaResultado] DESC)
LEFT JOIN [catHistopatologia] ON 
    [estudiosHis].[idCatHisto] = [catHistopatologia].[idCatHisto]
LEFT JOIN [catNumeroLesiones] ON
    [diagnostico].[idCatNumeroLesiones] = [catNumeroLesiones].[idCatNumeroLesiones]
LEFT JOIN [catMunicipio] municipioEnfermedad ON 
    [diagnostico].[idCatEstadoAdqEnf] = municipioEnfermedad.[idCatEstado] AND 
    [diagnostico].[idCatMunicipioAdqEnf] = municipioEnfermedad.[idCatMunicipio]
LEFT JOIN [catLocalidad] localidadEnfermedad ON
    [diagnostico].[idCatEstadoAdqEnf] = localidadEnfermedad.[idCatEstado] AND 
    [diagnostico].[idCatMunicipioAdqEnf] = localidadEnfermedad.[idCatMunicipio] AND
    [diagnostico].[idCatLocalidadAdqEnf] = localidadEnfermedad.[idCatLocalidad]
INNER JOIN [catUnidad] unidadTratante ON
    [pacientes].[idCatUnidadTratante] = unidadTratante.[idCatUnidad]
INNER JOIN [catMunicipio] municipioTratante ON 
    unidadTratante.[idCatEstado] = municipioTratante.[idCatEstado] AND 
    unidadTratante.[idCatMunicipio] = municipioTratante.[idCatMunicipio]
INNER JOIN [catJurisdiccion] jurisdicionTratante ON 
    unidadTratante.[idCatEstado] = jurisdicionTratante.[idCatEstado] AND 
    municipioTratante.[idCatJurisdiccion] = jurisdicionTratante.[idCatJurisdiccion] ';

if($_POST['edoExport']) {
    $query .= ' WHERE unidadTratante.[idCatEstado] = '.$_POST['edoExport'].' ';
}
    $query .= ' ORDER BY [pacientes].[cveExpediente]';

$result = ejecutaQuery(utf8_decode($query));
$encabezado = true;
$pacientes = array();
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Base Lepra');
$numFila = 1;
$numColumna = 0;

if(devuelveNumRows($result) == 0) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($numColumna, $numFila, 'No se encontraron pacientes en con los parametros seleccionados');
}
else {
    while ($registro = devuelveRowAssoc($result)) {
        if($encabezado) {
            $numColumna = 0;
            $nombresEncabezado = array_keys($registro);

            foreach ($nombresEncabezado as $nombreColumna) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($numColumna, $numFila, $nombreColumna);
                $numColumna++;
            }
            $encabezado = false;
            $numFila++;
        }

        $numColumna = 0;
        $pacientes[] = $registro['idPaciente'];
        unset($registro['idPaciente']);

        foreach ($registro as $celda) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($numColumna, $numFila, $celda);
            $numColumna++;
        }

        $numFila++;
    }

    /*********************************************************************************************************/

    $query = 'SELECT 
        [pacientes].[cveExpediente]
        ,[casosRelacionados].[Nombre]
        ,[catParentesco].[descripcion] AS parentesco
        ,[tiempoConvivenciaMeses]
        ,[tiempoConvivenciaAnos]
        ,[catSituacionCasoRelacionado].[descripcion] AS situacion
    FROM 
        [casosRelacionados]
    INNER JOIN [catParentesco] ON 
        [catParentesco].[idCatParentesco] = [casosRelacionados].idCatParentesco
    INNER JOIN [catSituacionCasoRelacionado] ON 
        [catSituacionCasoRelacionado].[idCatSituacionCasoRelacionado] = [casosRelacionados].[idCatSituacionCasoRelacionado]
    INNER JOIN [diagnostico] ON 
        [diagnostico].[idDiagnostico] = [casosRelacionados].[idDiagnostico]
    INNER JOIN [pacientes] ON 
        [pacientes].[idPaciente] = [diagnostico].[idPaciente]
    WHERE 
        [pacientes].[idPaciente] IN ('.  implode(',', $pacientes).') 
    ORDER BY 
        [pacientes].[cveExpediente]';

    $result = ejecutaQuery(utf8_decode($query));
    $encabezado = true;
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->setTitle('Base_Lepra_Cont_Relacion');
    //Base_Lepra_Contactos
    $numFila = 1;
    $numColumna = 0;

    while ($registro = devuelveRowAssoc($result)) {
        if($encabezado) {
            $numColumna = 0;
            $nombresEncabezado = array_keys($registro);

            foreach ($nombresEncabezado as $nombreColumna) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($numColumna, $numFila, $nombreColumna);
                $numColumna++;
            }
            $encabezado = false;
            $numFila++;
        }

        $numColumna = 0;

        foreach ($registro as $celda) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($numColumna, $numFila, $celda);
            $numColumna++;
        }

        $numFila++;
    }

    /*********************************************************************************************************/

    $query = 'SELECT 
        [pacientes].[cveExpediente]
        ,[contactos].[nombre]
        ,[catSexo].[sexo]
        ,[edad]
        ,[catParentesco].[descripcion] AS parentesco
        ,[tiempoConvivenciaAnos]
        ,[tiempoConvivenciaMeses]
    FROM 
        [contactos]
    INNER JOIN [catSexo] ON
        [catSexo].[idSexo] = [contactos].[sexo]
    INNER JOIN [catParentesco] ON 
        [catParentesco].[idCatParentesco] = [contactos].idCatParentesco
    INNER JOIN [diagnostico] ON 
        [diagnostico].[idDiagnostico] = [contactos].[idDiagnostico]
    INNER JOIN [pacientes] ON 
        [pacientes].[idPaciente] = [diagnostico].[idPaciente]
    WHERE 
        [pacientes].[idPaciente] IN ('.  implode(',', $pacientes).')
    ORDER BY 
        [pacientes].[cveExpediente]';

    $result = ejecutaQuery(utf8_decode($query));
    $encabezado = true;
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(2);
    $objPHPExcel->getActiveSheet()->setTitle('Base_Lepra_Contactos');
    $numFila = 1;
    $numColumna = 0;

    while ($registro = devuelveRowAssoc($result)) {
        if($encabezado) {
            $numColumna = 0;
            $nombresEncabezado = array_keys($registro);

            foreach ($nombresEncabezado as $nombreColumna) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($numColumna, $numFila, $nombreColumna);
                $numColumna++;
            }
            $encabezado = false;
            $numFila++;
        }

        $numColumna = 0;

        foreach ($registro as $celda) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($numColumna, $numFila, $celda);
            $numColumna++;
        }

        $numFila++;
    }
}
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="BD_Lepra_'.date('Y-m-d_H:i:s').'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;
?>
