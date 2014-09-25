<?php

class ReporteTrimestral {

	// VARIABLES DE ENTRADA ************************************************************************
	public $idCatEstado;						
	public $filtro;
	// **** Prevalente Con tratameento: 2 Prevalente c tratamiento 5 Reingreso PQT 9 Recaida
	// **** Prevalente Sin tratameento: 1 Prevalente s tratameinto
	// **** Alta: 4 Curado Termino de viginalncia Post Tx 7 Emigro 8 fallecio 10 Perdido 11 Abandono 12 Translado 
	// **** Vigilancia: 3 Curado en vigilancia 6 Reingreso Vig PTX
	// **** Todos
	
	// BD ******************************************************************************************
	static $idCatTipoEstudioDia = 1;				// TABLA catTipoEstudio Diagnostico
	static $idCatTipoEstudioCon = 2;				//						Control
	// BD ******************************************************************************************

	// VARIABLES DEL REPORTE ******************************************************************************************
	public $arrPacientesReporteTrimestral  = array();
	public $totalContactosRegistrados;
	public $totalContactosRevisados;
	// VARIABLES DEL REPORTE ******************************************************************************************

	// VARIABLES DEL NEGOCIO
	static $mesesVPT = 6;
	
	static $prevCTX = "2, 5, 9";									// Filtro = 1
	static $prevSTX = "1";											// Filtro = 2
	static $alta = "4, 7, 8, 10, 11, 12";							// Filtro = 3
	static $vigilancia = "3, 6";									// Filtro = 4
	static $todos = "2, 5, 9, 1, 4, 7, 8, 10, 11, 12, 3, 6";		// Filtro = 0
	
	static $catEstadoPacientesNoAplicablesInforme = "4, 8, 7, 10, 11";
	// 4	Curado término de Vigilancia Post
	// 7	Emigró
	// 8	Falleció
	// 10	Perdido
	// 11	Abandono
	static $invervaloTxPB = "P336D";
	static $invervaloVigPostTxPB = "P7M336D";
	static $invervaloFinVigPostTxPB = "P2Y7M336D";
	static $invervaloTxMB = "P672D";
	
	static $invervaloVigPostTxMB = "P7M672D";
	static $invervaloFinVigPostTxMB = "P5Y7M672D";
	// // // // // // //

	public $error = false;
	public $msgError;

	public function imprimirReporte() {

		$longitud = count($this->arrPacientesReporteTrimestral);	
		echo '<BR>';
		echo '<div class="datagrid"><TABLE>';		
		echo '<thead><TR align="center"><TH>No</TH><TH>Nombre</TH><TH>Localidad</TH><TH>Municipio</TH><TH>Tipo de Paciente</TH><TH>Edad</TH><TH>Sexo</TH><TH>Derechohabiencia</TH><TH>Diagnostico Bac Fecha</TH><TH>Diagnostico Bac IB</TH><TH>Diagnostico Bac IM%</TH><TH>Diagnostico His Fecha</TH><TH>Diagnostico His Resultado</TH><TH>Clasificacion Integral</TH><TH>Tipo de Lepra</TH></TR></thead>';
		for ($i = 0; $i < $longitud; $i++) {
			$objTemp = $this->arrPacientesReporteTrimestral[$i];
			
			echo '<TR><TD align="center">' . ($i + 1) .
				'</TD><TD>' . $objTemp->nombre .
				'</TD><TD>' . $objTemp->localidad .
				'</TD><TD>' . $objTemp->municipio .
				'</TD><TD>' . $objTemp->tipoPaciente .
				'</TD><TD align="center">' . $objTemp->edad .
				'</TD><TD>' . $objTemp->sexo .
				'</TD><TD>' . $objTemp->derechohabiencia;			
			$objAux1 = $objTemp->bacDiagnostico;
			echo '</TD><TD>' . formatFechaObj($objAux1->fecha)  .
				'</TD><TD align="center">' . $objAux1->IB .
				'</TD><TD align="center">' . $objAux1->IM;

			$objAux = $objTemp->hisDiagnostico;
			echo '</TD><TD>' . formatFechaObj($objAux->fecha) .
				'</TD><TD align="center">' . $objAux->resultado .
				'</TD><TD align="center">' . $objTemp->clasificacionIntegral .
				'</TD><TD align="center">' . $objTemp->tipoLepra .
				'</TD></TR>';
		}
		echo '</TABLE></div><br />';
		
		
		echo '<div class="datagrid"><TABLE>';
		echo '<thead><TR align="center"><TH>No</TH><TH>Fecha Inicio PQT</TH><TH>Calendario de Dosis</TH><TH>Calendario Controles Bac</TH></TR></thead>';
		for ($i = 0; $i < $longitud; $i++) {
			$objTemp = $this->arrPacientesReporteTrimestral[$i];
			/////////////////////////////// IMPRIME LISTADO DE CONTROLES
			$longitudAux = count($objTemp->arrControlTx);
			$primerMesDelAno = true;
			$tabAux1 = '<div class="datagrid"><TABLE><TR align="center"><TH>A&ntilde;o</TH><TH>Ene</TH><TH>Feb</TH><TH>Mar</TH><TH>Abr</TH><TH>May</TH><TH>Jun</TH><TH>Jul</TH><TH>Ago</TH><TH>Sep</TH><TH>Oct</TH><TH>Nov</TH><TH>Dic</TH></TR>';
			$mesAct = 1;
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrControlTx[$j];
				if ($primerMesDelAno == true) {
					$tabAux1 .= '<TR><TD>' . $objAux->ano .'</TD>';
					$primerMesDelAno = false;
				}

				while ($mesAct < $objAux->mes) {
					$mesAct++;
					$tabAux1 .= '<TD></TD>';
				}
				$tabAux1 .= '<TD>' . $objAux->valor . '</TD>';
				$mesAct++;
				if ($mesAct == 13) {
					$mesAct = 1;
					$tabAux1 .= '</TR>';
					$primerMesDelAno = true;
				}
			}
			$tabAux1 .= '</TABLE></div>';
			/////////////////////////////// IMPRIME LISTADO DE CONTROLES

			/////////////////////////////// IMPRIME LISTADO BACILOSCOPIAS
			$longitudAux = count($objTemp->arrControlBacTx);
			$tabAux2 = '<div class="datagrid"><TABLE><TR align="center"><TH>Fecha</TH><TH>IM</TH><TH>IB</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrControlBacTx[$j];
				$tabAux2 .= '<TR align="center"><TD>' . formatFechaObj($objAux->fecha) . '</TD><TD>' . $objAux->IM . ' %</TD><TD>' . $objAux->IB . '</TD></TR>';
			}
			$tabAux2 .= '</TABLE></div>';
			/////////////////////////////// IMPRIME LISTADO BACILOSCOPIAS

			echo '<TR align="center"><TD>' . ($i + 1) ;
			echo '</TD><TD>'. formatFechaObj($objTemp->fechaInicioTx);
			echo '</TD><TD>';
			echo $tabAux1; 
			echo '</TD><TD>';
			echo $tabAux2; 		
			echo '</TD></TR>';			
		}
		echo '</TABLE></div><br />';

		
		echo '<div class="datagrid"><TABLE>';
		echo '<thead><TR align="center"><TH>No</TH><TH>Fecha Termino PQT</TH><TH>Calendario Bac TT</TH><TH>Calendario His TT</TH><TH>Estado del Paciente</TH><TH>Inicio Vigilancia posTX</TH><TH>Calendario Seguimiento Revision Clinica</TH><TH>Calendario Segimiento Bacteriologico</TH></TR></thead>';
		for ($i = 0; $i < $longitud; $i++) {
			$objTemp = $this->arrPacientesReporteTrimestral[$i];
			/////////////////////////////// IMPRIME LISTADO BACILOSCOPIAS
			$longitudAux = count($objTemp->arrControlBacFinTx);
			$tabAux1 = '<div class="datagrid"><TABLE align="center"><TR><TH>Fecha</TH><TH>IM</TH><TH>IB</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrControlBacFinTx[$j];
				$tabAux1 .= '<TR align="center"><TD>' . formatFechaObj($objAux->fecha) . '</TD><TD>' . $objAux->IM . ' %</TD><TD>' . $objAux->IB . '</TD></TR>';
			}
			$tabAux1 .= '</TABLE></div>';
			/////////////////////////////// 

			/////////////////////////////// IMPRIME LISTADO HISTOPATOLOGIAS
			$longitudAux = count($objTemp->arrControlHisFinTx);
			$tabAux2 = '<div class="datagrid"><TABLE><TR align="center"><TH>Fecha</TH><TH>Resultado</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrControlHisFinTx[$j];
				$tabAux2 .= '<TR align="center"><TD>' . formatFechaObj($objAux->fecha) . '</TD><TD>' . $objAux->resultado . '</TD></TR>';
			}
			$tabAux2 .= '</TABLE></div>';
			///////////////////////////////

			/////////////////////////////// IMPRIME LISTADO CONTROLES
			$longitudAux = count($objTemp->arrVigilanciaRevision);
			$tabAux3 = '<div class="datagrid"><TABLE><TR align="center"><TH>Mes - A&ntilde;o</TH><TH>Estado</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrVigilanciaRevision[$j];
				$tabAux3 .= '<TR align="center"><TD>' . $objAux->mes . '-' . $objAux->ano . '</TD><TD>' . $objAux->valor . '</TD></TR>';
			}
			$tabAux3 .= '</TABLE></div>';
			///////////////////////////////

			/////////////////////////////// IMPRIME LISTADO BACILOSCOPIAS
			$longitudAux = count($objTemp->arrVigilanciaBac);
			$tabAux4 = '<div class="datagrid"><TABLE><TR align="center"><TH>Fecha</TH><TH>IM</TH><TH>IB</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrVigilanciaBac[$j];
				$tabAux4 .= '<TR align="center"><TD>' . $objAux->fecha . '</TD><TD>' . $objAux->IM . ' %</TD><TD>' . $objAux->IB . '</TD></TR>';
			}
			$tabAux4 .= '</TABLE></div>';
			///////////////////////////////

			echo '<TR align="center"><TD>' . ($i + 1) .
				'</TD><TD>' . formatFechaObj($objTemp->fechaFinTx) .
				'</TD><TD>' . $tabAux1 .
				'</TD><TD>' . $tabAux2 .
				'</TD><TD>' . $objTemp->situacionTerminoTx   .
				'</TD><TD>' . formatFechaObj($objTemp->fechaIVPT)  .
				'</TD><TD>'  . $tabAux3 .
				'</TD><TD>'  . $tabAux4 .
				'</TD></TR>';
		}
		echo '</TABLE></div><br />';

		
		echo '<div class="datagrid"><TABLE>';
		echo '<thead><TR align="center"><TH>No</TH><TH>Grado Discapacidad Ojos</TH><TH>Grado Discapacidad Manos</TH><TH>Grado Discapacidad Pies</TH><TH>Grado Discapacidad General</TH><TH>Estado Reaccional Anterior</TH><TH>Estado Reaccional Actual</TH><TH>Fecha Termino Vigilancia pos TX</TH><TH>Condicion</TH><TH>Estudio de Contactos</TH><TH>Observaciones</TH><TH>Registrados</TH><TH>Revisados</TH></TR></thead>';
		for ($i = 0; $i < $longitud; $i++) {
			$objTemp = $this->arrPacientesReporteTrimestral[$i];

			//Revisados
			$arrContactos = $objTemp->arrContactos;
			$arrContactosExaminados = $objTemp->arrContactosExaminados;
			//$registrados = 
			//$revisados = 
			/////////////////////////////// IMPRIME LISTADO BACILOSCOPIAS
			$longitudAux = count($arrContactos);
			$tabAux1 = '<div class="datagrid"><TABLE>';
			$tabAux1 .= '<TR align="center"><TH>A&ntilde;o</TH><TH>Contactos</TH><TH>Examinados</TH></TR>';
			foreach ($arrContactos as $clave => $valor) {
				$tabAux1 .= '<TR align="center"><TD>' . $clave . '</TD><TD>' . $valor . '</TD><TD>' . $arrContactosExaminados[$clave] . '</TD></TR>';
			}
			$tabAux1 .= '</TABLE></div>';
			/////////////////////////////// 
			
			echo '<TR align="center"><TD>' . ($i + 1) .
				'</TD><TD>' . $objTemp->gradoDiscapacidadOjos .
				'</TD><TD>' . $objTemp->gradoDiscapacidadManos .
				'</TD><TD>' . $objTemp->gradoDiscapacidadPies .
				'</TD><TD>' . $objTemp->gradoDiscapacidadGeneral .
				'</TD><TD>' . $objTemp->estadoReaccionalAnterior .
				'</TD><TD>' . $objTemp->estadoReaccionalActual .
				'</TD><TD>' . formatFechaObj($objTemp->fechaFVPT) .
				'</TD><TD>' . $objTemp->condicion .
				'</TD><TD>' . $tabAux1 . 
				'</TD><TD>' . $objTemp->observaciones .
				'</TD><TD>' . $objTemp->totalContactos .
				'</TD><TD>' . //Revisados . 
				'</TD></TR>';
		}
		echo '</TABLE></div>';		
	}

	public function imprimirReporteUnitabla($return = false) {

        $tabla = '';
		$longitud = count($this->arrPacientesReporteTrimestral);
        
        if($return)
            $tabla .= '<TABLE border="1">';
        else
            $tabla .= '<BR><div class="datagrid"><TABLE>';

		$tabla .= '<thead><TR align="center"><TH>No</TH><TH>Nombre</TH><TH>Localidad</TH><TH>Municipio</TH><TH>Tipo de Paciente</TH><TH>Edad</TH>
            <TH>Sexo</TH><TH>Derechohabiencia</TH><TH>Diagnostico Bac Fecha</TH><TH>Diagnostico Bac IB</TH><TH>Diagnostico Bac IM</TH>
            <TH>Diagnostico His Fecha</TH><TH>Diagnostico His Resultado</TH><TH>Clasificacion Integral</TH><TH>Tipo de Lepra</TH>
            <TH>Fecha Inicio PQT</TH><TH>Calendario de Dosis</TH><TH>Calendario Controles Bac</TH><TH>Fecha Termino PQT</TH>
            <TH>Calendario Bac TT</TH><TH>Calendario His TT</TH><TH>Estado del Paciente al Termino de TX</TH><TH>Inicio Vigilancia posTX</TH>
            <TH>Calendario Seguimiento Revision Clinica</TH><TH>Calendario Segimiento Bacteriologico</TH><TH>Grado Discapacidad Ojos</TH>
            <TH>Grado Discapacidad Manos</TH><TH>Grado Discapacidad Pies</TH><TH>Grado Discapacidad General</TH><TH>Estado Reaccional Anterior
            </TH><TH>Estado Reaccional Actual</TH><TH>Fecha Termino Vigilancia pos TX</TH><TH>Condicion</TH><TH>Estudio de Contactos</TH>
            <TH>Observaciones</TH><TH>Registrados</TH><TH>Revisados</TH></TR></thead>';
        
		for ($i = 0; $i < $longitud; $i++) {
			$objTemp = $this->arrPacientesReporteTrimestral[$i];
			
			$tabla .= '<TR><TD>' . ($i + 1) .
				'</TD><TD>' . $objTemp->nombre .
				'</TD><TD>' . $objTemp->localidad .
				'</TD><TD>' . $objTemp->municipio .
				'</TD><TD>' . $objTemp->tipoPaciente .
				'</TD><TD>' . $objTemp->edad .
				'</TD><TD>' . $objTemp->sexo .
				'</TD><TD>' . $objTemp->derechohabiencia;			
			$objAux1 = $objTemp->bacDiagnostico;
			$tabla .= '</TD><TD>' . $objAux1->fecha  .
				'</TD><TD>' . $objAux1->IB .
				'</TD><TD>' . $objAux1->IM;

			$objAux = $objTemp->hisDiagnostico;
			$tabla .= '</TD><TD>' . $objAux->fecha .
				'</TD><TD>' . $objAux->resultado .
				'</TD><TD>' . $objTemp->clasificacionIntegral .
				'</TD><TD>' . $objTemp->tipoLepra;

			$longitudAux = count($objTemp->arrControlTx);
			$primerMesDelAno = true;
            if($return)
                $tabAux1 = '<TABLE border="1"><TR><TH>A&ntilde;o</TH><TH>Ene</TH><TH>Feb</TH><TH>Mar</TH><TH>Abr</TH><TH>May</TH><TH>Jun</TH>
                <TH>Jul</TH><TH>Ago</TH><TH>Sep</TH><TH>Oct</TH><TH>Nov</TH><TH>Dic</TH></TR>';
            else
                $tabAux1 = '<div class="datagrid"><TABLE><TR><TH>A&ntilde;o</TH><TH>Ene</TH><TH>Feb</TH><TH>Mar</TH><TH>Abr</TH><TH>May</TH><TH>Jun</TH>
                <TH>Jul</TH><TH>Ago</TH><TH>Sep</TH><TH>Oct</TH><TH>Nov</TH><TH>Dic</TH></TR>';
			$mesAct = 1;
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrControlTx[$j];
				if ($primerMesDelAno == true) {
					$tabAux1 .= '<TR><TD>' . $objAux->ano .'</TD>';
					$primerMesDelAno = false;
				}

				while ($mesAct < $objAux->mes) {
					$mesAct++;
					$tabAux1 .= '<TD></TD>';
				}
				$tabAux1 .= '<TD>' . $objAux->valor . '</TD>';
				$mesAct++;
				if ($mesAct == 13) {
					$mesAct = 1;
					$tabAux1 .= '</TR>';
					$primerMesDelAno = true;
				}
			}
            if($return)
                $tabAux1 .= '</TABLE>';
            else
                $tabAux1 .= '</TABLE></div>';
			/////////////////////////////// IMPRIME LISTADO DE CONTROLES

			/////////////////////////////// IMPRIME LISTADO BACILOSCOPIAS			
			$longitudAux = count($objTemp->arrControlBacTx);
            if($return)
                $tabAux2 = '<TABLE border="1"><TR><TH>Fecha</TH><TH>IM</TH><TH>IB</TH></TR>';
            else
                $tabAux2 = '<div class="datagrid"><TABLE><TR><TH>Fecha</TH><TH>IM</TH><TH>IB</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrControlBacTx[$j];
				$tabAux2 .= '<TR><TD>' . $objAux->fecha . '</TD><TD>' . $objAux->IM . ' %</TD><TD>' . $objAux->IB . '</TD></TR>';
			}
			if($return)
                $tabAux2 .= '</TABLE>';
            else
                $tabAux2 .= '</TABLE></div>';
			/////////////////////////////// IMPRIME LISTADO BACILOSCOPIAS

			$tabla .= '</TD><TD>'. $objTemp->fechaInicioTx .
				'</TD><TD>' . $tabAux1 .
				'</TD><TD>' . $tabAux2;
			
			$tabAux1 = "";
			$tabAux2 = "";

			$longitudAux = count($objTemp->arrControlBacFinTx);
			if($return)
                $tabAux1 = '<TABLE border="1"><TR><TH>Fecha</TH><TH>IM</TH><TH>IB</TH></TR>';
            else
                $tabAux1 = '<div class="datagrid"><TABLE><TR><TH>Fecha</TH><TH>IM</TH><TH>IB</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrControlBacFinTx[$j];
				$tabAux1 .= '<TR><TD>' . $objAux->fecha . '</TD><TD>' . $objAux->IM . ' %</TD><TD>' . $objAux->IB . '</TD></TR>';
			}
			if($return)
                $tabAux1 .= '</TABLE>';
            else
                $tabAux1 .= '</TABLE></div>';
			/////////////////////////////// 

			/////////////////////////////// IMPRIME LISTADO HISTOPATOLOGIAS
			$longitudAux = count($objTemp->arrControlHisFinTx);
			if($return)
                $tabAux2 = '<TABLE border="1"><TR><TH>Fecha</TH><TH>Resultado</TH></TR>';
            else
                $tabAux2 = '<div class="datagrid"><TABLE><TR><TH>Fecha</TH><TH>Resultado</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrControlHisFinTx[$j];
				$tabAux2 .= '<TR><TD>' . $objAux->fecha . '</TD><TD>' . $objAux->resultado . '</TD></TR>';
			}
			if($return)
                $tabAux2 .= '</TABLE>';
            else
                $tabAux2 .= '</TABLE><div>';
			///////////////////////////////

			/////////////////////////////// IMPRIME LISTADO CONTROLES
			$longitudAux = count($objTemp->arrVigilanciaRevision);
			if($return)
                $tabAux3 = '<TABLE border="1"><TR><TH>Mes-A&ntilde;o</TH><TH>Estado</TH></TR>';
            else
                $tabAux3 = '<div class="datagrid"><TABLE><TR><TH>Mes-A&ntilde;o</TH><TH>Estado</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrVigilanciaRevision[$j];
				$tabAux3 .= '<TR><TD>' . $objAux->mes . '-' . $objAux->ano . '</TD><TD>' . $objAux->valor . '</TD></TR>';
			}
			if($return)
                $tabAux3 .= '</TABLE>';
            else
                $tabAux3 .= '</TABLE></div>';
			///////////////////////////////

			/////////////////////////////// IMPRIME LISTADO BACILOSCOPIAS
			$longitudAux = count($objTemp->arrVigilanciaBac);
			if($return)
                $tabAux4 = '<TABLE border="1"><TR><TH>Fecha</TH><TH>IM</TH><TH>IB</TH></TR>';
            else
                $tabAux4 = '<div class="datagrid"><TABLE><TR><TH>Fecha</TH><TH>IM</TH><TH>IB</TH></TR>';
			for ($j = 0; $j < $longitudAux; $j++) {
				$objAux = $objTemp->arrVigilanciaBac[$j];
				$tabAux4 .= '<TR><TD>' . $objAux->fecha . '</TD><TD>' . $objAux->IM . ' %</TD><TD>' . $objAux->IB . '</TD></TR>';
			}
			if($return)
                $tabAux4 .= '</TABLE>';
            else
                $tabAux4 .= '</TABLE></div>';
			///////////////////////////////

			$tabla .= '</TD><TD>' . $objTemp->fechaFinTx .
				'</TD><TD>' . $tabAux1 .
				'</TD><TD>' . $tabAux2 .
				'</TD><TD>' . $objTemp->situacionTerminoTx   .
				'</TD><TD>' . $objTemp->fechaIVPT  .
				'</TD><TD>'  . $tabAux3 .
				'</TD><TD>'  . $tabAux4 .
			
			$tabAux1 = "";
			$tabAux2 = "";
			$tabAux3 = "";
			$tabAux4 = "";

			//Revisados
			$arrContactos = $objTemp->arrContactos;
			$arrContactosExaminados = $objTemp->arrContactosExaminados;
			//$registrados = 
			//$revisados =
			/////////////////////////////// IMPRIME LISTADO BACILOSCOPIAS
			$longitudAux = count($arrContactos);
			if($return)
                $tabAux1 = '<TABLE border="1">';
            else
                $tabAux1 = '<div class="datagrid"><TABLE>';
			$tabAux1 .= '<TR><TH>A&ntilde;o</TH><TH>Contactos</TH><TH>Examinados</TH></TR>';
			foreach ($arrContactos as $clave => $valor) {
				$tabAux1 .= '<TR><TD>' . $clave . '</TD><TD>' . $valor . '</TD><TD>' . $arrContactosExaminados[$clave] . '</TD></TR>';
			}
			if($return)
                $tabAux1 .= '</TABLE>';
            else
                $tabAux1 .= '</TABLE></div>';
			/////////////////////////////// 
			
			$tabla .= '</TD><TD>' . $objTemp->gradoDiscapacidadOjos .
				'</TD><TD>' . $objTemp->gradoDiscapacidadManos .
				'</TD><TD>' . $objTemp->gradoDiscapacidadPies .
				'</TD><TD>' . $objTemp->gradoDiscapacidadGeneral .
				'</TD><TD>' . $objTemp->estadoReaccionalAnterior .
				'</TD><TD>' . $objTemp->estadoReaccionalActual .
				'</TD><TD>' . $objTemp->fechaFVPT .
				'</TD><TD>' . $objTemp->condicion .
				'</TD><TD>' . $tabAux1 . 
				'</TD><TD>' . $objTemp->observaciones .
				'</TD><TD>' . $objTemp->totalContactos .
				'</TD><TD>' . $objTemp->totalContactosRevisados . 
				'</TD></TR>';
		}
        if($return) {
            $tabla .= '</TABLE>';
            return $tabla;
        }
        else {
            $tabla .= '</TABLE></div>';
            echo $tabla;
        }
	}
	
	public function generarReporte() {

		if (is_null($this->idCatEstado)) {
			$this->error = true;
			$this->msgError = "El reporte requiere del identificador de estado.";
		} else {
			
			$filtro = "";
			switch ($this->filtro) {
				case 0:
					$filtro = self::$todos;
					break;
				case 1:
					$filtro = self::$prevCTX;
					break;				
				case 2:
					$filtro = self::$prevSTX;
					break;				
				case 3:
					$filtro = self::$alta;
					break;
				default:
					$filtro = self::$vigilancia;
					break;
			}
			
			
			$sql = "SELECT p.idPaciente, d.observaciones, d.idDiagnostico, p.nombre, p.apellidoPaterno, p.apellidoMaterno, l.nombre AS localidad,  m.nombre AS municipio, ctp.descripcion as tipoPaciente, p.fechaNacimiento, cs.sexo AS sexo, ins.descripcion AS derechohabiencia, ccl.descripcion AS clasificacionLepra, p.fechaInicioPQT, d.discOjoDer, d.discOjoIzq, d.discManoDer, d.discManoIzq, d.discPieDer, d.discPieIzq, cer.descripcion AS estReaAnt, cerr.descripcion AS estReaAct, cep.descipcion as condicion " .
			"FROM pacientes p, catLocalidad l, diagnostico d, catMunicipio m, catInstituciones ins, catTipoPaciente ctp, catSexo cs, catClasificacionLepra ccl, catEstadoReaccional cer, catEstadoReaccional cerr, catEstadoPaciente cep  " .
			"WHERE p.idCatEstado = " . $this->idCatEstado . " " .
			"AND d.idPaciente = p.idPaciente " .
			//"AND d.idCatEstadoPaciente NOT IN (" . self::$catEstadoPacientesNoAplicablesInforme . ") " .
			"AND d.idCatEstadoPaciente IN (" . $filtro . ") " .
			"AND d.idCatEstadoPaciente = cep.idCatEstadoPaciente " .
			"AND l.idCatLocalidad = p.idCatLocalidad " .
			"AND l.idCatEstado = p.idCatEstado " .
			"AND l.idCatMunicipio = p.idCatMunicipio " .
			"AND l.idCatMunicipio = m.idCatMunicipio " .
			"AND l.idCatEstado = m.idCatEstado " .
			"AND p.idCatInstitucionDerechohabiencia = ins.idCatInstituciones " .
			"AND p.idCatTipoPaciente = ctp.idCatTipoPaciente " .
			"AND p.sexo = cs.idSexo " .
			"AND ccl.idCatClasificacionLepra = d.idCatClasificacionLepra " . 
			"AND d.idCatEstadoReaccionalAnt = cer.idCatEstadoReaccional " .
			"AND d.idCatEstadoReaccionalAct = cerr.idCatEstadoReaccional;";

			if ($this->idCatEstado == 0) {
				$sql = "SELECT p.idPaciente, d.observaciones, d.idDiagnostico, p.nombre, p.apellidoPaterno, p.apellidoMaterno, l.nombre AS localidad,  m.nombre AS municipio, ctp.descripcion as tipoPaciente, p.fechaNacimiento, cs.sexo AS sexo, ins.descripcion AS derechohabiencia, ccl.descripcion AS clasificacionLepra, p.fechaInicioPQT, d.discOjoDer, d.discOjoIzq, d.discManoDer, d.discManoIzq, d.discPieDer, d.discPieIzq, cer.descripcion AS estReaAnt, cerr.descripcion AS estReaAct, cep.descipcion as condicion " .
				"FROM pacientes p, catLocalidad l, diagnostico d, catMunicipio m, catInstituciones ins, catTipoPaciente ctp, catSexo cs, catClasificacionLepra ccl, catEstadoReaccional cer, catEstadoReaccional cerr, catEstadoPaciente cep  " .
				"WHERE d.idPaciente = p.idPaciente " .
				"AND d.idCatEstadoPaciente IN (" . $filtro . ") " .
				"AND d.idCatEstadoPaciente = cep.idCatEstadoPaciente " .
				"AND l.idCatLocalidad = p.idCatLocalidad " .
				"AND l.idCatEstado = p.idCatEstado " .
				"AND l.idCatMunicipio = p.idCatMunicipio " .
				"AND l.idCatMunicipio = m.idCatMunicipio " .
				"AND l.idCatEstado = m.idCatEstado " .
				"AND p.idCatInstitucionDerechohabiencia = ins.idCatInstituciones " .
				"AND p.idCatTipoPaciente = ctp.idCatTipoPaciente " .
				"AND p.sexo = cs.idSexo " .
				"AND ccl.idCatClasificacionLepra = d.idCatClasificacionLepra " . 
				"AND d.idCatEstadoReaccionalAnt = cer.idCatEstadoReaccional " .
				"AND d.idCatEstadoReaccionalAct = cerr.idCatEstadoReaccional;";
			} else {
				$sql = "SELECT p.idPaciente, d.observaciones, d.idDiagnostico, p.nombre, p.apellidoPaterno, p.apellidoMaterno, l.nombre AS localidad,  m.nombre AS municipio, ctp.descripcion as tipoPaciente, p.fechaNacimiento, cs.sexo AS sexo, ins.descripcion AS derechohabiencia, ccl.descripcion AS clasificacionLepra, p.fechaInicioPQT, d.discOjoDer, d.discOjoIzq, d.discManoDer, d.discManoIzq, d.discPieDer, d.discPieIzq, cer.descripcion AS estReaAnt, cerr.descripcion AS estReaAct, cep.descipcion as condicion " .
				"FROM pacientes p, catLocalidad l, diagnostico d, catMunicipio m, catInstituciones ins, catTipoPaciente ctp, catSexo cs, catClasificacionLepra ccl, catEstadoReaccional cer, catEstadoReaccional cerr, catEstadoPaciente cep  " .
				"WHERE p.idCatEstado = " . $this->idCatEstado . " " .
				"AND d.idPaciente = p.idPaciente " .
				//"AND d.idCatEstadoPaciente NOT IN (" . self::$catEstadoPacientesNoAplicablesInforme . ") " .
				"AND d.idCatEstadoPaciente IN (" . $filtro . ") " .
				"AND d.idCatEstadoPaciente = cep.idCatEstadoPaciente " .
				"AND l.idCatLocalidad = p.idCatLocalidad " .
				"AND l.idCatEstado = p.idCatEstado " .
				"AND l.idCatMunicipio = p.idCatMunicipio " .
				"AND l.idCatMunicipio = m.idCatMunicipio " .
				"AND l.idCatEstado = m.idCatEstado " .
				"AND p.idCatInstitucionDerechohabiencia = ins.idCatInstituciones " .
				"AND p.idCatTipoPaciente = ctp.idCatTipoPaciente " .
				"AND p.sexo = cs.idSexo " .
				"AND ccl.idCatClasificacionLepra = d.idCatClasificacionLepra " . 
				"AND d.idCatEstadoReaccionalAnt = cer.idCatEstadoReaccional " .
				"AND d.idCatEstadoReaccionalAct = cerr.idCatEstadoReaccional;";
			}
		
			$consulta = ejecutaQueryClases($sql);
			//echo "<BR>" . $sql . "<BR>";
			if (is_string($consulta)) {
				$this->error = true;
				$this->msgError = $consulta . " SQL:" . $sql;
			} else {
				while ($tabla = devuelveRowAssoc($consulta)) {
					//print_r($tabla);
                    //die();
					$paciente = new PacientesReporteTrimestral();								
					$fNac = formatFechaObj($tabla["fechaNacimiento"], 'Y-m-d');
					$resClasLepra = strpos($tabla["clasificacionLepra"], "MB");

					$paciente->idPaciente = $tabla["idPaciente"];
					$paciente->idDiagnostico = $tabla["idDiagnostico"];
					$paciente->nombre = $tabla["nombre"] . " " . $tabla["apellidoPaterno"] . " " . $tabla["apellidoMaterno"];
					$paciente->localidad = $tabla["localidad"];
					$paciente->municipio = $tabla["municipio"];
					$paciente->tipoPaciente = $tabla["tipoPaciente"];
					$paciente->edad = calEdad($fNac);
					$paciente->sexo = $tabla["sexo"];
					$paciente->derechohabiencia = $tabla["derechohabiencia"];
					$paciente->condicion = $tabla["condicion"];
					//if ($resClasLepra !== false) $paciente->tipoLepra = "PB"; else $paciente->tipoLepra = "MB";
					if ($resClasLepra > 0) $paciente->tipoLepra = "MB"; else $paciente->tipoLepra = "PB";
					$paciente->fechaInicioTx = formatFechaObj($tabla["fechaInicioPQT"], "Y-m-d");
					if ($paciente->tipoLepra == "PB") {
						$dAux = new DateTime($paciente->fechaInicioTx);
						$paciente->fechaFinTx = formatFechaObj($dAux->add(new DateInterval(self::$invervaloTxPB)), "Y-m-d");
						$dAux = new DateTime($paciente->fechaInicioTx);
						$paciente->fechaIVPT = formatFechaObj($dAux->add(new DateInterval(self::$invervaloVigPostTxPB)), "Y-m-d");
						$dAux = new DateTime($paciente->fechaInicioTx);
						$paciente->fechaFVPT = formatFechaObj($dAux->add(new DateInterval(self::$invervaloFinVigPostTxPB)), "Y-m-d");
					} else {
						$dAux = new DateTime($paciente->fechaInicioTx);
						$paciente->fechaFinTx = formatFechaObj($dAux->add(new DateInterval(self::$invervaloTxMB)), "Y-m-d");
						$dAux = new DateTime($paciente->fechaInicioTx);
						$paciente->fechaIVPT = formatFechaObj($dAux->add(new DateInterval(self::$invervaloVigPostTxMB)), "Y-m-d");
						$dAux = new DateTime($paciente->fechaInicioTx);
						$paciente->fechaFVPT = formatFechaObj($dAux->add(new DateInterval(self::$invervaloFinVigPostTxMB)), "Y-m-d");
					}				
					if ($tabla["discOjoDer"] >= $tabla["discOjoIzq"]) $paciente->gradoDiscapacidadOjos = $tabla["discOjoDer"];  else $paciente->gradoDiscapacidadOjos = $tabla["discOjoIzq"];
					if ($tabla["discManoDer"] >= $tabla["discManoIzq"]) $paciente->gradoDiscapacidadManos = $tabla["discManoDer"];  else $paciente->gradoDiscapacidadManos = $tabla["discManoIzq"];
					if ($tabla["discPieDer"] >= $tabla["discPieIzq"]) $paciente->gradoDiscapacidadPies = $tabla["discPieDer"];  else $paciente->gradoDiscapacidadPies = $tabla["discPieIzq"];

					$discGen = $paciente->gradoDiscapacidadOjos;
					if ($discGen < $paciente->gradoDiscapacidadManos) $discGen = $paciente->gradoDiscapacidadManos;
					if ($discGen < $paciente->gradoDiscapacidadPies) $discGen = $paciente->gradoDiscapacidadPies;
					$paciente->gradoDiscapacidadGeneral = $discGen;
					$paciente->estadoReaccionalAnterior = $tabla["estReaAnt"];
					$paciente->estadoReaccionalActual = $tabla["estReaAct"];
					$paciente->observaciones = $tabla["observaciones"];

					// #################################################################################################### ESTUDIOS
					$diagBac = new BaciloscopiaReporteTrimestral();
					$diagBac->getExamenDiagnostico($paciente->idDiagnostico);
					$diagHis = new HistopatologiaReporteTrimestral();
					$diagHis->getExamenDiagnostico($paciente->idDiagnostico);
					$paciente->bacDiagnostico = $diagBac;
					$paciente->hisDiagnostico = $diagHis;
					$paciente->clasificacionIntegral = $paciente->hisDiagnostico->resultado;
					//var_dump($paciente->hisDiagnostico);
					
					//	ESTUDIOS BACILOSCOPICOS DE CONTROL DURANTE EL PERIODO DE TRATAMIENTO (fechaInicioTx-fechaFinTx)
					$sql = "SELECT idEstudioBac FROM estudiosBac " .
						" WHERE idDiagnostico = " . $paciente->idDiagnostico .
						" AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon .
						" AND idContacto is null " .
						" AND fechaResultado BETWEEN '" . formatFechaObj($paciente->fechaInicioTx, "Y-m-d") . "' AND '" . formatFechaObj($paciente->fechaFinTx, "Y-m-d") . "'" .
						" ORDER BY fechaResultado ASC;";
					$consultaBis = ejecutaQueryClases($sql);
					if (is_string($consultaBis)) {
						$paciente->error = true;
						$paciente->msgError = $consultaBis . " SQL:" . $sql;
					} else {
						while ($tablaBis = devuelveRowAssoc($consultaBis)) {
							$estBac = new BaciloscopiaReporteTrimestral();
							$estBac->getExamen($tablaBis["idEstudioBac"]);					
							array_push($paciente->arrControlBacTx, $estBac);		// BACILOSCOPIA TX
						}
					}

					//	ESTUDIOS BACILOSCOPICOS DE CONTROL DURANTE EL PERIODO DE VIGILANCIA POST TRATAMIENTO (fechaIVPT-fechaFVPT)
					$sql = "SELECT idEstudioBac FROM estudiosBac " .
						" WHERE idDiagnostico = " . $paciente->idDiagnostico .
						" AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon .
						" AND idContacto is null " .
						" AND fechaResultado BETWEEN '" . formatFechaObj($paciente->fechaIVPT, "Y-m-d") . "' AND '" . formatFechaObj($paciente->fechaFVPT, "Y-m-d") . "'" .
						" ORDER BY fechaResultado ASC;";
					$consultaBis = ejecutaQueryClases($sql);
					if (is_string($consultaBis)) {
						$paciente->error = true;
						$paciente->msgError = $consultaBis . " SQL:" . $sql;
					} else {
						while ($tablaBis = devuelveRowAssoc($consultaBis)) {
							$estBac = new BaciloscopiaReporteTrimestral();
							$estBac->getExamen($tablaBis["idEstudioBac"]);					
							array_push($paciente->arrControlBacFinTx, $estBac);		// BACILOSCOPIA VPTX
						}
					}

					//	ESTUDIOS HISTOPATOLOGICOS DE CONTROL DURANTE EL PERIODO DE VIGILANCIA POST TRATAMIENTO (fechaIVPT-fechaFVPT)
					$sql = "SELECT idEstudioHis FROM estudiosHis " .
						" WHERE idDiagnostico = " . $paciente->idDiagnostico .
						" AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon .
						" AND idContacto is null " .
						" AND fechaResultado BETWEEN '" . formatFechaObj($paciente->fechaIVPT, "Y-m-d") . "' AND '" . formatFechaObj($paciente->fechaFVPT, "Y-m-d") . "'" .
						" ORDER BY fechaResultado ASC;";
					$consultaBis = ejecutaQueryClases($sql);
					if (is_string($consultaBis)) {
						$paciente->error = true;
						$paciente->msgError = $consultaBis . " SQL:" . $sql;
					} else {
						while ($tablaBis = devuelveRowAssoc($consultaBis)) {
							$estHis = new HistopatologiaReporteTrimestral();
							$estHis->getExamen($tablaBis["idEstudioHis"]);					
							array_push($paciente->arrControlHisFinTx, $estHis);		// HISTOPATOLOGIA VPTX
						}
					}

					//	ESTUDIOS BACILOSCOPICOS DE CONTROL DESPUES DE LA FECHA DE FIN DE VIGILANCIA POST TRATAMIENTO (fechaFVPT)
					$sql = "SELECT idEstudioBac FROM estudiosBac " .
						" WHERE idDiagnostico = " . $paciente->idDiagnostico .
						" AND idCatTipoEstudio = " . self::$idCatTipoEstudioCon .
						" AND idContacto is null " .
						" AND fechaResultado > '" . formatFechaObj($paciente->fechaFVPT, "Y-m-d") . "'" .
						" ORDER BY fechaResultado ASC;";
					$consultaBis = ejecutaQueryClases($sql);
					if (is_string($consultaBis)) {
						$paciente->error = true;
						$paciente->msgError = $consultaBis . " SQL:" . $sql;
					} else {
						while ($tablaBis = devuelveRowAssoc($consultaBis)) {
							$estBac = new BaciloscopiaReporteTrimestral();
							$estBac->getExamen($tablaBis["idEstudioBac"]);					
							array_push($paciente->arrVigilanciaBac, $estBac);		// BACILOSCOPIA POST VPTX
						}
					}

					// #################################################################################################### CONTROL
					$arrControl = array(array());
					$fechaIni = strtotime(formatFechaObj($paciente->fechaInicioTx, 'Y-m-d'));
					$fechaFin = strtotime(formatFechaObj($paciente->fechaFVPT, 'Y-m-d'));
					$fechaIVPT = strtotime(formatFechaObj($paciente->fechaIVPT, 'Y-m-d'));								
					$mesIni = date("n", $fechaIni);
					$anoIni = date("Y", $fechaIni);
					$mesFin = date("n", $fechaFin);
					$anoFin = date("Y", $fechaFin);
					$mesIVPT = date("n", $fechaIVPT); 
					$anoIVPT = date("Y", $fechaIVPT); 
					$mesActual = $mesIni;
					$anoActual = $anoIni;
					
					if (!empty($paciente->fechaInicioTx)) {
						// CONTROL DESDE INICIO TRATAMIENTO HASTA FIN VIGILANCIA POST TRATAMIENTO(fechaInicioTx-fechaFVPT)
						$sql = "SELECT fecha FROM control " .
							" WHERE idDiagnostico = " . $paciente->idDiagnostico .
							" AND fecha BETWEEN '" . formatFechaObj($paciente->fechaInicioTx, "Y-m-d") . "' AND '" . formatFechaObj($paciente->fechaFVPT, "Y-m-d") . "' " .
							" ORDER BY fecha ASC;";
						$consultaBis = ejecutaQueryClases($sql);
						if (is_string($consultaBis)) {
							$paciente->error = true;
							$paciente->msgError = $consultaBis . " SQL:" . $sql;
						} else {
							while ($tablaBis = devuelveRowAssoc($consultaBis)) {
								$tempFecha = strtotime(formatFechaObj($tablaBis["fecha"] , 'Y-m-d'));
								$mesTemp = date("n", $tempFecha);
								$anoTemp = date("Y", $tempFecha);
								$diaTemp = date("j", $tempFecha);
								$arrControl[$anoTemp][$mesTemp] .= " " . $diaTemp;
							}
						}
	
						$mesVPT = -1;
						$yaPasoVPT = false;
						while ($anoActual < $anoFin) {
							$controlTx = new ControlTxReporteTrimestral();
							$controlTx->ano = $anoActual;
							$controlTx->mes = $mesActual;
							if (!$yaPasoVPT) $controlTx->valor = " No";
							
							if (!is_null($arrControl[$anoActual][$mesActual])) $controlTx->valor = $arrControl[$anoActual][$mesActual];
							
							if (($mesIVPT == $mesActual) && ($anoIVPT == $anoActual)) { 
								$controlTx->valor = " IVPT";
								$mesVPT = 0;
								$yaPasoVPT = true;
							}
	
							if ($mesVPT > -1 && $mesVPT < (self::$mesesVPT + 1)) {
								if ($mesVPT > 0) $controlTx->valor = " IVPT" . $mesVPT;
								$mesVPT += 1;
							}
	
							array_push($paciente->arrControlTx, $controlTx);				// CONTROL INICIO TRATAMIENTO HASTA FIN VIGILANCIA POST TRATAMIENTO
							$mesActual += 1;
							if ($mesActual == 13) {
								$mesActual = 1;
								$anoActual += 1;
							}
						}
						
						if ($anoActual == $anoFin) {
							for ($mesActual = 1; $mesActual <= $mesFin; $mesActual++) {
								$controlTx = new ControlTxReporteTrimestral();
								$controlTx->ano = $anoActual;
								$controlTx->mes = $m;
								//$controlTx->valor = "No";
								if (!is_null($arrControl[$anoActual][$mesActual])) $controlTx->valor = $arrControl[$anoActual][$mesActual];
								if ($mesActual == $mesFin) $controlTx->valor = " FVPT";
								array_push($paciente->arrControlTx, $controlTx);				// CONTROL INICIO TRATAMIENTO HASTA FIN VIGILANCIA POST TRATAMIENTO
							}
						}
	
						// CONTROL POST FIN VIGILANCIA TRATAMIENTO (> fechaFVPT)
						$sql = "SELECT c.fecha, cep.descipcion " .
							" FROM control c, catEstadoPaciente cep " .
							" WHERE idDiagnostico = " . $paciente->idDiagnostico .
							" AND fecha > '" . formatFechaObj($paciente->fechaFVPT, "Y-m-d") . "'" .
							" AND cep.idCatEstadoPaciente = c.idCatEstadoPaciente" .
							" ORDER BY fecha ASC;";
						//echo "<BR>" . $sql . "<BR>"; 
						$consultaBis = ejecutaQueryClases($sql);
						if (is_string($consultaBis)) {
							$paciente->error = true;
							$paciente->msgError = $consultaBis . " SQL:" . $sql;
						} else {
							while ($tablaBis = devuelveRowAssoc($consultaBis)) {
                                //print_r($tablaBis);
                                //die();
								$tempFecha = strtotime(formatFechaObj($tablaBis["fecha"] , 'Y-m-d'));
								$controlTx = new ControlTxReporteTrimestral();
								$controlTx->ano = date("Y", $tempFecha);
								$controlTx->mes = date("n", $tempFecha);
								$controlTx->valor = $tablaBis["descripcion"];
								$paciente->situacionTerminoTx = $tablaBis["descripcion"];
								array_push($paciente->arrVigilanciaRevision, $controlTx);				// CONTROL POST FIN VIGILANCIA TRATAMIENTO
							}
						}
						
						// #################################################################################################### CONTACTOS
						$sql = "SELECT COUNT(idContacto) AS cuenta FROM contactos WHERE idDiagnostico = " . $paciente->idDiagnostico . ";";
						//echo "<BR>" . $sql . "<BR>";
						$consultaBis = ejecutaQueryClases($sql);
						if (is_string($consultaBis)) {
							$this->error = true;
							$this->msgError = $consultaBis . " SQL:" . $sql;
						} else {					
							$tabla = devuelveRowAssoc($consultaBis);
							$this->totalContactos += $tabla["cuenta"];
							$paciente->totalContactos = $tabla["cuenta"];
							for ($y = $anoIni; $y <= $anoFin; $y++){						
								/*$sql = "SELECT COUNT(idContacto) AS cuenta " .
									" FROM estudiosBac WHERE idDiagnostico = " . $paciente->idDiagnostico . 
									" AND fechaResultado BETWEEN '" . $y . "/01/01' AND '" . $y . "/12/31';";*/
								$sql = "SELECT COUNT( DISTINCT cc.idContacto) AS cuenta " .
										"FROM diagnostico d, contactos c, controlContacto cc " .
										"WHERE c.idDiagnostico = d.idDiagnostico " .
										"AND cc.idContacto = c.idContacto " .
										"AND cc.fecha BETWEEN '" . $y . "/01/01' AND '" . $y . "/12/31' " .
										"AND d.idDiagnostico = " . $paciente->idDiagnostico . ";";
								//echo "<BR>" . $sql . "<BR>";
								$consultaBis = ejecutaQueryClases($sql);
								$paciente->arrContactosExaminados[$y] = 0;
								if (is_string($consultaBis)) {
									$this->error = true;
									$this->msgError = $consultaBis . " SQL:" . $sql;
									$paciente->arrContactosExaminados[$y] = "Error";
								} else {					
									$tabla = devuelveRowAssoc($consultaBis);
									$totalContactos = $tabla["cuenta"];
									$paciente->arrContactosExaminados[$y] = $tabla["cuenta"];									
									if ($y == date('Y')) {
										$paciente->totalContactosRevisados = $tabla["cuenta"];
										$this->totalContactosRegistrados += $totalContactos;
										$this->totalContactosRevisados += $tabla["cuenta"];
									}
								}
								$paciente->arrContactos[$y] = $paciente->totalContactos;
							}
						}
                        // Para establecer la situacion al termino de tratamiento
                        // si la fecha de fin de tratamiento es menor que la fecha actual
                        if(cal_dif_fecha($paciente->fechaFinTx) > 0) {
                            // asignamos el valor de la condicion acttual a la situacion al termino de tratamiento
                            $paciente->situacionTerminoTx = $paciente->condicion;
                        }
					}
                    //var_dump($paciente);
                    //die();
					array_push($this->arrPacientesReporteTrimestral, $paciente);
				}
			}

		}		
	}
}

class PacientesReporteTrimestral {

	public $idPaciente;
	public $idDiagnostico;
	// VARIABLES DEL REPORTE ******************************************************************************************
	public $nombre;
	public $localidad;
	public $municipio;
	public $tipoPaciente;							// idCatTipoPaciente
	public $edad;
	public $sexo;
	public $derechohabiencia;
	public $bacDiagnostico;							// BACILOSCOPIA // Diagnostico
	public $hisDiagnostico;							// HISTOPATOLOGIA // Diagnostico
	public $clasificacionIntegral;					// Resultado Histopatologia
	public $tipoLepra;								// idCatClasificacionLepra
	public $fechaInicioTx;
	public $arrControlTx = array();					// CONTROLTX	// Controles durante TX
	public $arrControlBacTx = array();				// BACILOSCOPIA // durante TX
	public $fechaFinTx;
	public $arrControlBacFinTx = array();			// BACILOSCOPIA // post TX
	public $arrControlHisFinTx = array();			// HISTOPATOLOGIA // post TX
	public $situacionTerminoTx;						// (Estado paciente)
	public $arrVigilanciaRevision = array();		// CONTROLTX	// Controles post TX
	public $arrVigilanciaBac = array();				// BACILOSCOPIA // post fin VPT
	public $gradoDiscapacidadOjos;
	public $gradoDiscapacidadManos;
	public $gradoDiscapacidadPies;
	public $gradoDiscapacidadGeneral;
	public $estadoReaccionalAnterior;
	public $estadoReaccionalActual;
	public $fechaIVPT;
	public $fechaFVPT;
	public $condicion;
	public $arrContactos = array();						// Llave: yyyy / Valor: No.Contactos	
	public $arrContactosExaminados = array();			// Llave: yyyy / Valor: No.Contactos
	public $totalContactos;
	public $totalContactosRevisados;
	public $observaciones;
	// VARIABLES DEL REPORTE ******************************************************************************************

}

class BaciloscopiaReporteTrimestral {

	// BD ******************************************************************************************
	static $idCatTipoEstudioDia = 1;				// TABLA catTipoEstudio Diagnostico
	static $idCatTipoEstudioCon = 2;				//						Control
	// BD ******************************************************************************************

	// VARIABLES DEL REPORTE ******************************************************************************************
	public $fecha;
	public $IB;
	public $IM;
	// VARIABLES DEL REPORTE ******************************************************************************************

	public function getExamenDiagnostico($idDiagnostico) {

		$sql = "SELECT fechaResultado, bacIM AS IM, cb.descripcion AS IB " .
			"FROM [estudiosBac] eb, [catBaciloscopia] cb " .
			"WHERE eb.idDiagnostico = " . $idDiagnostico . " " .
			"AND eb.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " " .
			"AND eb.muestraRechazada = 0 " .
			"AND cb.idCatBaciloscopia = eb.idCatBac;";
		
		$consulta = ejecutaQueryClases($sql);
		//echo "<BR>" . $sql . "<BR>";
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			$tabla = devuelveRowAssoc($consulta);
			if (!is_null($tabla)) {
				$this->fecha = formatFechaObj($tabla["fechaResultado"], 'Y-m-d');
				$this->IM = $tabla["IM"];
				$this->IB = $tabla["IB"];
			} else {
				$this->fecha = NULL;				
				$this->IM = NULL;
				$this->IB = NULL;
			}
		}
	}

	public function getExamen($idEstudioBac) {
		$sql = "SELECT fechaResultado, bacIM AS IM, cb.descripcion AS IB " .
			"FROM [estudiosBac] eb, [catBaciloscopia] cb " .
			"WHERE eb.idEstudioBac = " . $idEstudioBac . " " .
			"AND cb.idCatBaciloscopia = eb.idCatBac;";
		
		$consulta = ejecutaQueryClases($sql);
		//echo "<BR>" . $sql . "<BR>";
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {		
			$tabla = devuelveRowAssoc($consulta);
			if (!is_null($tabla)) {
				$this->fecha = formatFechaObj($tabla["fechaResultado"], 'Y-m-d');
				$this->IM = $tabla["IM"];
				$this->IB = $tabla["IB"];
			} else {
				$this->fecha = NULL;				
				$this->IM = NULL;
				$this->IB = NULL;
			}
		}
	}
}

class HistopatologiaReporteTrimestral {

	// BD ******************************************************************************************
	static $idCatTipoEstudioDia = 1;				// TABLA catTipoEstudio Diagnostico
	static $idCatTipoEstudioCon = 2;				//						Control
	// BD ******************************************************************************************

	// VARIABLES DEL REPORTE ******************************************************************************************
	public $fecha;
	public $resultado;
	// VARIABLES DEL REPORTE ******************************************************************************************

	public function getExamenDiagnostico($idDiagnostico) {

		$sql = "SELECT fechaResultado, ch.descripcion AS res " .
			"FROM [estudiosHis] eh, [catHistopatologia] ch " .
			"WHERE eh.idDiagnostico = " . $idDiagnostico . " " .
			"AND eh.idCatTipoEstudio = " . self::$idCatTipoEstudioDia . " " .
			"AND eh.muestraRechazada = 0 " .
			"AND ch.idCatHisto = eh.idCatHisto;";
		
		$consulta = ejecutaQueryClases($sql);
		//echo "<BR>" . $sql . "<BR>";
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {		
			$tabla = devuelveRowAssoc($consulta);
			if (!is_null($tabla)) {
				$this->fecha = formatFechaObj($tabla["fechaResultado"], 'Y-m-d');
				$this->resultado = $tabla["res"];
			} else {
				$this->fecha = NULL;				
				$this->resultado = NULL;
			}
		}		
	}

	public function getExamen($idEstudioHis) {

		$sql = "SELECT fechaResultado, ch.descripcion AS res " .
			"FROM [estudiosHis] eh, [catHistopatologia] ch " .
			"WHERE eh.idEstudioHis = " . $idEstudioHis . " " .			
			"AND ch.idCatHisto = eh.idCatHisto;";
		
		$consulta = ejecutaQueryClases($sql);
		//echo "<BR>" . $sql . "<BR>";
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {		
			$tabla = devuelveRowAssoc($consulta);
			if (!is_null($tabla)) {
				$this->fecha = formatFechaObj($tabla["fechaResultado"], 'Y-m-d');
				$this->resultado = $tabla["res"];
			} else {
				$this->fecha = NULL;				
				$this->resultado = NULL;
			}
		}
	}

}

class ControlTxReporteTrimestral {

	// VARIABLES DEL REPORTE ******************************************************************************************
	public $ano;
	public $mes;
	public $valor;			// Valores posibles: Dia(s) de control / IVPT / FVPT / Cuenta meses de espera
	// VARIABLES DEL REPORTE ******************************************************************************************
	
	// NUNCA SE LLEGO A UTILIZAR **************************************************************************************
	/*public function getControl($idControl) {
		$sql = "SELECT fecha " .
			"FROM [control] " .
			"WHERE idControl = " . $idControl . " " .
			"AND cb.idCatBaciloscopia = eb.idCatBac;";
		$connectionBD = conectaBD();
		if($connectionBD === FALSE) { 
			$this->error = true;
			$this->msgError = "ERROR: No se pudo conectar con la Base de Datos, verifique el archivo de configuracion";
		}
		$consulta = ejecutaQueryClases($sql);
		echo "<BR>" . $sql . "<BR>";
		if (is_string($consulta)) {
			$this->error = true;
			$this->msgError = $consulta . " SQL:" . $sql;
		} else {
			if ($tabla = devuelveRowAssoc($consulta)) {
				$time = strtotime(formatFechaObj($tabla["fecha"], 'Y-m-d'));
				$this->ano = date("Y", $time);
				$this->mes = date("F", $time);
				$this->valor = date("j", $time);
			}
		}
		$connectionBD = closeConexion();
	}*/
	// NUNCA SE LLEGO A UTILIZAR **************************************************************************************

}

?>
