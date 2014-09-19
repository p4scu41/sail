<?PHP 
	require_once('../../../include/var_global.php');
	require_once('../../../include/bdatos.php');
	require_once('../../../include/log.php');
	require_once('../../../include/fecha_hora.php');
	

	echo '<BR>Sospechoso ' .
		'<BR><A HREF="solicitudBac.php?idEstudioBac=28">Solicitud Baciloscopia</a>'.
		'<BR><A HREF="respuestaBac.php?idEstudioBac=28">Respuesta Baciloscopia</a>'.
		'<BR><A HREF="respuestaRec.php?idEstudio=28&tipo=bac">Rechazo Baciloscopia</a>'.
		'<BR><A HREF="solicitudHis.php?idEstudioHis=17">Solicitud Histopatologia</a>'.
		'<BR><A HREF="respuestaHis.php?idEstudioHis=17">Respuesta Histopatologia</a>'.
		'<BR><A HREF="respuestaRec.php?idEstudio=17&tipo=his">Rechazo Histopatologia</a>'.
		'<BR>Diagnosticado ' .
		'<BR><A HREF="solicitudBac.php?idEstudioBac=29">Solicitud Baciloscopia</a>'.
		'<BR><A HREF="respuestaBac.php?idEstudioBac=29">Respuesta Baciloscopia</a>'.
		'<BR><A HREF="respuestaRec.php?idEstudio=29&tipo=bac">Rechazo Baciloscopia</a>'.
		'<BR><A HREF="solicitudHis.php?idEstudioHis=18">Solicitud Histopatologia</a>'.
		'<BR><A HREF="respuestaHis.php?idEstudioHis=18">Respuesta Histopatologia</a>'.
		'<BR><A HREF="respuestaRec.php?idEstudio=18&tipo=his">Rechazo Histopatologia</a>'.
		'<BR>Contacto ' .
		'<BR><A HREF="solicitudBac.php?idEstudioBac=30">Solicitud Baciloscopia</a>'.
		'<BR><A HREF="respuestaBac.php?idEstudioBac=30">Respuesta Baciloscopia</a>'.
		'<BR><A HREF="respuestaRec.php?idEstudio=30&tipo=bac">Rechazo Baciloscopia</a>'.
		'<BR><A HREF="solicitudHis.php?idEstudioHis=19">Solicitud Histopatologia</a>'.
		'<BR><A HREF="respuestaHis.php?idEstudioHis=19">Respuesta Histopatologia</a>'.
		'<BR><A HREF="respuestaRec.php?idEstudio=19&tipo=his">Rechazo Histopatologia</a>';
	
?>