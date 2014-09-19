<?PHP
	session_start(); // Necesario para el manejo de sesiones, ejecutar antes de enviar cualquier header
	$SEGURO = TRUE;  // Variable que controla el nivel de seguridad del sistema
	
	require_once('include/conf.php'); // Archivo que contiene todas las variables globales
	
	//print_r($_SESSION);
	
	if(isset($_SESSION[NAME_USR_SESSION]))
	{
		$isValidUser = check_user($_SESSION[NAME_USR_SESSION]);
	}
	
?>
<!DOCTYPE HTML>

<!--[if lt IE 7]> 
	<html class="nojs ms lt_ie7" lang="es" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;"> 
<![endif]-->

<!--[if IE 7]>    
	<html class="nojs ms ie7" lang="es" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;"> 
<![endif]-->

<!--[if IE 8]>    
	<html class="nojs ms ie8" lang="es" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;"> 
<![endif]-->

<!--[if gt IE 8]> 
	<html class="nojs ms" lang="es" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;"> 
<![endif]-->

<html lang="es" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
<title><?PHP echo htmlentities(utf8_decode(TITULO_SISTEMA)); ?></title>

<link rel="shortcut icon" href="images/favicon_cie.ico">
<link rel="stylesheet" type="text/css" href="styles/style.css">
<link rel="stylesheet" type="text/css" href="js/jquery.alerts-1.1/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="js/formValidator.2.5.5.1/css/validationEngine.jquery.css" />
<link rel="stylesheet" type="text/css" href="include/workless/css/minified.css.php">
<link rel="stylesheet" type="text/css" href="js/JSCal2-1.9/src/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="js/JSCal2-1.9/src/css/border-radius.css" />
<link rel="stylesheet" type="text/css" href="js/JSCal2-1.9/src/css/steel/steel.css" />

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts-1.1/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="js/jquery.alerts-1.1/jquery.alerts.js"></script>
<script type="text/javascript" src="js/formValidator.2.5.5.1/js/languages/jquery.validationEngine-es.js" charset="utf-8"></script>
<script type="text/javascript" src="js/formValidator.2.5.5.1/js/jquery.validationEngine.js" charset="utf-8"></script>
<script type="text/javascript" src="js/JSCal2-1.9/src/js/jscal2.js"></script>
<script type="text/javascript" src="js/JSCal2-1.9/src/js/lang/es.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="include/workless/js/modernizr.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	testIE();
});

</script>
</head>
<body class="blueish" id="top" onLoad="removeLoading()">
	<div id="div_loading"> &nbsp; </div>
	<!-- wraper -->
	<div id="wrapper">
		<!-- shell -->
		<div class="shell">
			<!-- container -->
			<div class="container">
				<!-- header -->
				<header id="header">
					<!--
                    <h1 id="logo">
                    <table align="left"><tr valign="middle">
                        <td id="logo_ssa"><img src="images/logo_salud_federal.png" height="60" /></td>
                        <td></td></tr>
                    </table>
                    </h1>
					
					<div class="search">
                        <img src="images/logo_lepra.png" height="60" align="absmiddle" />
					</div>
					-->
                    <table align="center" width="100%">
                        <tr valign="middle" align="center">
                            <td width="33%"><img src="images/logo_salud_federal.png" height="60" /></td>
                            <td width="34%"><img src="images/logo_dgepi.jpg" height="60" /></td>
                            <td width="33%"><img src="images/logo_lepra.png" height="60" /></td>
                        </tr>
                    </table>
				</header>
				<!-- end of header -->
				<!-- navigation -->
				<nav id="navigation">
					<?PHP
					if( isset($_SESSION[ID_USR_SESSION]) && !empty($_SESSION[ID_USR_SESSION]) && $isValidUser) {
						echo '<a href="?mod=ini" class="nav-btn">MENU<span class="arr"></span></a>';
						include_once('include/menu.php');
					}
					?>
				</nav>
				<!-- end of navigation -->
				<!-- slider -->
				<div class="m-slider">
					<div class="slider-holder">
						<span class="slider-shadow"></span>
					</div>
				</div>
				<!-- end of slider -->
				<!-- main -->
				<div class="main">
					<div class="infoUser">
					<?PHP 
					if( isset($_SESSION[ID_USR_SESSION]) && !empty($_SESSION[ID_USR_SESSION]) && $isValidUser) {
						echo '<span id="fecha">'.htmlentities(utf8_decode(convierte_fecha(date("Y-m-d")))).' <span id="reloj"></span></span> <br />
						[ <span>'.$_SESSION[NAME_USR_SESSION].' ( <a href="?mod=logout"> Salir <img border="0" src="images/logout.png" align="absmiddle"/> </a> )</span> ] &nbsp;
						<script type="text/javascript">
						$(document).ready(function() {
							set_reloj(); 
							setInterval(reloj, 1000);
							//setInterval(testConexion, 10000);
						});
						</script>';
					}
					?>
					</div>
					<section class="post">
					<?PHP
                    // set the include path properly for PHPIDS
                    set_include_path(
                        get_include_path()
                        . PATH_SEPARATOR
                        . 'include/PHPIDS-0.7/lib/'
                    );
                    require_once 'IDS/Init.php';
                    
                    try {
                        /*
                        * It's pretty easy to get the PHPIDS running
                        * 1. Define what to scan
                        * 
                        * Please keep in mind what array_merge does and how this might interfer 
                        * with your variables_order settings
                        */
                        $request = array(
                            'REQUEST' => $_REQUEST,
                            'GET' => $_GET,
                            'POST' => $_POST,
                            'COOKIE' => $_COOKIE
                        );

                        $init = IDS_Init::init(dirname(__FILE__) . '/include/PHPIDS-0.7/lib/IDS/Config/Config.ini.php');
                        
                        /**
                         * You can also reset the whole configuration
                         * array or merge in own data
                         * or you can access the config directly like here:
                         */

                        $init->config['General']['base_path'] = dirname(__FILE__) . '/include/PHPIDS-0.7/lib/IDS/';
                        $init->config['General']['use_base_path'] = true;
                        $init->config['Caching']['caching'] = 'none';

                        // 2. Initiate the PHPIDS and fetch the results
                        $ids = new IDS_Monitor($request, $init);
                        $result = $ids->run();

                        /*
                        * That's it - now you can analyze the results:
                        *
                        * In the result object you will find any suspicious
                        * fields of the passed array enriched with additional info
                        *
                        * Note: it is moreover possible to dump this information by
                        * simply echoing the result object, since IDS_Report implemented
                        * a __toString method.
                        */
                        if (!$result->isEmpty()) {
                        //if(false){
                            echo msj_error('Ataque detectado');
                            echo $result;
                            //en modo produccion. al detectar un ataque redireccionar a la pagina de inicio
                            //redirect('?mod=ini','',0);

                            /*
                            * The following steps are optional to log the results
                            */
                            require_once 'IDS/Log/File.php';
                            require_once 'IDS/Log/Composite.php';

                            $compositeLog = new IDS_Log_Composite();
                            $compositeLog->addLogger(IDS_Log_File::getInstance($init));
                            
                            $compositeLog->execute($result);


                        } else {
                            // Contenido principal
                            /**********************************************************/
                            if( !isset($_SESSION[ID_USR_SESSION]) || !$isValidUser)
							{
								?>
								<div align="center"><h2><?PHP echo utf8_decode(TITULO_SISTEMA_2); ?></h2></div>
								<?php
                                form_sesion();
							}
                           else if(isset($_SESSION[ID_USR_SESSION]) && $isValidUser)
                               include('content/main.php');
                            /**********************************************************/
                        }
                    } catch (Exception $e) {
                        /*
                        * sth went terribly wrong - maybe the
                        * filter rules weren't found?
                        */
                        printf(
                            'An error occured: %s',
                            $e->getMessage()
                        );
                        // En modo produccion, dejar solo el redireccionado a la pagina principal
                        //redirect('?mod=ini','',0);
                    }
					?>
					</section>
				</div>
				<!-- end of main -->
				<div id="footer">
					<div class="footer-cols">
                        Para cualquier duda, comentario e incidencia en la operaci&oacute;n de los sistemas favor de reportarlo al tel&eacute;fono: (0155) 5337-1702 y/o a los correos electr&oacute;nicos: mesadeservicio@dgepi.salud.gob.mx o plataforma@dgepi.salud.gob.mx. En un horario de atenci&oacute;n de Lunes a Viernes de 09:00 a 18:00hrs.
					</div>
					<!-- end of footer-cols -->
					<div class="footer-bottom">
						<p class="copy">&copy; Copyright 2013, Instituto de Salud del Estado de Chiapas 
						<img src="images/chiapasnosune.png" height="30" align="absmiddle"/></p>
					</div>
				</div>
			</div>
			<!-- end of container -->	
		</div>
		<!-- end of shell -->	
	</div>
	<!-- end of wrapper -->

<script type="text/javascript" src="include/workless/js/plugins.js"></script>
<script type="text/javascript" src="include/workless/js/application.js"></script>
</body>
</html>

<?PHP
	closeConexion(); // Cierra la conexion con la BDatos
?>