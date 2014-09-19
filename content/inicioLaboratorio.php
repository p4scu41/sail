<?php
require_once('include/clasesLepra.php');
require_once('include/fecha_hora.php');

$listado = new ListGeneric();

$listado->obtenerPendientesBac($_SESSION[EDO_USR_SESSION]);
$listado->obtenerPendientesHis($_SESSION[EDO_USR_SESSION]);

$objHTML = new HTML();
$help = new Helpers();

$objHTML->startFieldset('Solicitudes Pendientes');

echo '<div class="datagrid">
	<table>
	<thead>
            <tr align="center">
                <th>Clave LESP</th>	
                <th>Clave Del Paciente</th>
                <th>Nombre</th>
                <th>Solicitante</th>
                <th>Fecha Muestreo</th>
                <th>Fecha Solicitud</th>
                <th>Tipo</th>
                <th>Estudio</th>
				<th>Cedula</th>
                <th>Ver</th>
            </tr>
            </thead>
            <tbody>';

	foreach($listado->arrEstudiosBac as $pendienteBac){
		$idPacienteBac = $pendienteBac->idPaciente;
        echo '<tr>
            <td>'.$pendienteBac->folioLaboratorio.'</td>	
            <td>'.( !empty($pendienteBac->idContacto )
                ? 'Estudio de Contacto'
                : $help->getClavePaciente(
                        $pendienteBac->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($pendienteBac->idDiagnostico)
                        : $pendienteBac->idPaciente
                  )
            ).'</td>	
            <td>'.( !empty($pendienteBac->idContacto )
                ? $help->getNombreContacto($pendienteBac->idContacto)
                : $help->getNamePaciente(
                        $pendienteBac->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($pendienteBac->idDiagnostico)
                        : $pendienteBac->idPaciente
                  )
            ).'</td>
            <td>'.$pendienteBac->idCatSolicitante.' '.$help->getNameUnidad($pendienteBac->idCatSolicitante).'</td>
            <td>'.formatFechaObj($pendienteBac->fechaTomaMuestra).'</td>
            <td>'.formatFechaObj($pendienteBac->fechaSolicitudEstudio).'</td>
            <td>'.htmlentities($help->getDescripTipoEstudio($pendienteBac->idCatTipoEstudio)).'</td>
            <td>Bacilosc&oacute;pia</td>
			<td align="center">
				<a href="?mod=labCedu&id='.$idPacienteBac.'">
					<img src="images/ver.jpg" border="0"/>
				</a>
			</td>
            <td align="center"><a href="?mod=labSoli&tipo=bacilos&id='.$pendienteBac->idEstudioBac.'"><img src="images/verLab.gif" border="0"/></a></td>
        </tr>';
	}
	
	foreach($listado->arrEstudiosHis as $pendienteHis){
		$idPacienteHis = $pendienteHis->idPaciente;
        echo '<tr>
            <td>'.$pendienteHis->folioLaboratorio.'</td>	
            <td>'.( $pendienteHis->idContacto 
				? 'Estudio de Contacto'
				: $help->getClavePaciente(
                        $pendienteHis->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($pendienteHis->idDiagnostico)
                        : $pendienteHis->idPaciente
                  )
			).'</td>	
            <td>'.( $pendienteHis->idContacto 
				? $help->getNombreContacto($pendienteHis->idContacto)
				: $help->getNamePaciente(
                        $pendienteHis->idDiagnostico
                        ? $help->getIdPacienteFromDiagnostico($pendienteHis->idDiagnostico)
                        : $pendienteHis->idPaciente
                  )
			).'</td>
            <td>'.$pendienteHis->idCatSolicitante.' '.$help->getNameUnidad($pendienteHis->idCatSolicitante).'</td>
            <td>'.formatFechaObj($pendienteHis->fechaTomaMuestra).'</td>
            <td>'.formatFechaObj($pendienteHis->fechaSolicitudEstudio).'</td>
            <td>'.htmlentities($help->getDescripTipoEstudio($pendienteHis->idCatTipoEstudio)).'</td>
            <td>Histopatol&oacute;gia</td>
			<td align="center">
				<a href="?mod=labCedu&id='.$idPacienteHis.'">
					<img src="images/ver.jpg" border="0"/>
				</a>
			</td>
            <td align="center"><a href="?mod=labSoli&tipo=histo&id='.$pendienteHis->idEstudioHis.'"><img src="images/verLab.gif" border="0"/></a></td>
        </tr>';
	}

echo '</tbdy></table></div>';

$objHTML->endFieldset();

?>