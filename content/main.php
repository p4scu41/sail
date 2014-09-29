<?PHP
	if( !isset($_SESSION[ID_USR_SESSION]) )
		die();
	
	if( !isset($SEGURO) )
		die();
	
		
	switch ($_SESSION[TIPO_USR_SESSION]) {
		case 1: // Administrador
			switch($_GET['mod'])
			{
				case 'ini': include('content/inicio.php'); break;
				case 'bus': include('content/buscar.php'); break;
				case 'cap': include('content/capturar.php'); break;
				case 'noti': include('content/notificar.php'); break;
				case 'lab': include('content/laboratorio.php'); break;
				case 'reg': include('content/registrar.php'); break;
				case 'con': include('content/control.php'); break;
                case 'rep': include('content/reportes.php'); break;
                case 'repSeg': include('content/repSeguimiento.php'); break;
                case 'repMen': include('content/repInfMensual.php'); break;
                case 'repDGEpi': include('content/repDGEpi.php'); break;
                case 'indIndx': include('content/indicadores_index.php'); break;
				case 'ind': include('content/indicadores.php'); break;
                case 'val': include('content/validacion.php'); break;
                case 'valSIS': include('content/valSIS.php'); break;
                case 'valSUAVE': include('content/valSUAVE.php'); break;
                case 'ane': include('content/anexos.php'); break;
                case 'help': include('content/ayuda.php'); break;
                case 'her': include('content/herramientas.php'); break;
                case 'map': include('content/mapa.php'); break;
				case 'logout': cerrar_sesion(); break;
				case 'edadGen':include('content/graphEdades.php'); break;
				case 'locGr': include('content/graphLocalidades.php'); break;
				case 'lesionGr': include('content/graphLesiones.php'); break;
				case 'camEx': include('content/caminandoExcelencia.php'); break;
                case 'usrs': include('content/usuarios.php'); break;
                case 'formUsr': include('content/formUsuario.php'); break;
                case 'exportBD': include('content/exportBDtoExcel.php'); break;
				default: include('content/inicio.php');
			}
		break;
    
		case 2: // Medico
		 switch($_GET['mod'])
			{
				case 'ini': include('content/inicio.php'); break;
				case 'bus': include('content/buscar.php'); break;
				case 'cap': include('content/capturar.php'); break;
				case 'noti': include('content/notificar.php'); break;
				case 'lab': include('content/laboratorio.php'); break;
				case 'reg': include('content/registrar.php'); break;
				case 'con': include('content/control.php'); break;
                case 'ane': include('content/anexos.php'); break;
                case 'her': include('content/herramientas.php'); break;
                case 'help': include('content/ayuda.php'); break;
				case 'logout': cerrar_sesion(); break;
            
                // Nuevos permisos para soportar usuario nacional
                case 'rep': include('content/reportes.php'); break;
                case 'repSeg': include('content/repSeguimiento.php'); break;
                case 'repMen': include('content/repInfMensual.php'); break;
                case 'repDGEpi': include('content/repDGEpi.php'); break;
                case 'indIndx': include('content/indicadores_index.php'); break;
                case 'ind': include('content/indicadores.php'); break;
                case 'val': include('content/validacion.php'); break;
                case 'valSIS': include('content/valSIS.php'); break;
                case 'valSUAVE': include('content/valSUAVE.php'); break;
                case 'map': include('content/mapa.php'); break;
                case 'edadGen':include('content/graphEdades.php'); break;
                case 'locGr': include('content/graphLocalidades.php'); break;
                case 'lesionGr': include('content/graphLesiones.php'); break;
                case 'camEx': include('content/caminandoExcelencia.php'); break;
                case 'usrs': include('content/usuarios.php'); break;
                case 'formUsr': include('content/formUsuario.php'); break;
                case 'exportBD': include('content/exportBDtoExcel.php'); break;
            
				default: include('content/inicio.php');
			}
		break;
		
		case 3: // Laboratorista
			switch($_GET['mod'])
			{
				case 'labIni': include('content/inicioLaboratorio.php'); break;
				//case 'labCed': include('content/cedulaPaciente.php'); break;
				case 'labBus': include('content/buscarLaboratorio.php'); break;
				case 'labSoli': include('content/SolicitudLaboratorio.php'); break;
				case 'labCedu': include('content/cedulaPaciente.php'); break;
				case 'help': include('content/ayuda.php'); break;
				case 'logout': cerrar_sesion(); break;
				default: include('content/inicioLaboratorio');
			}
		break;
        case 4: // Recepcion Muestra
			switch($_GET['mod'])
			{
				case 'recepIni': include('content/recepcionMuestra.php'); break;
                case 'recepBus': include('content/recepcionMuestraBuscar.php'); break;
				case 'help': include('content/ayuda.php'); break;
				case 'logout': cerrar_sesion(); break;
				default: include('content/recepcionMuestra.php');
			}
		break;
        case 5: // Control de calidad
			switch($_GET['mod'])
			{
                case 'calIni': include('content/iniControlCalidad.php'); break;
                case 'labSoli': include('content/SolicitudLaboratorio.php'); break;
				case 'labCedu': include('content/cedulaPaciente.php'); break;
				case 'help': include('content/ayuda.php'); break;
				case 'logout': cerrar_sesion(); break;
				default: include('content/iniControlCalidad.php');
			}
		break;
		
		default:
			echo 'ERROR: Tipo de Usuario no definido';
		break;
	}
?>