<?php
/*
 *	$Id: wsdlclient1.php,v 1.3 2007/11/06 14:48:48 snichol Exp $
 *
 *	WSDL client sample.
 *
 *	Service: WSDL
 *	Payload: document/literal
 *	Transport: http
 *	Authentication: none
 */
 require_once('libSMS/nusoap.php');
 
 error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
 
function sendSMS($para, $mensaje, $fecha, $hora, $titulo = '')
{
	$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
	$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
	$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
	$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
	
	
	$client = new nusoap_client('http://sms.krealo.com/smsdev/ServiceSMS.asmx?WSDL', 'wsdl', $proxyhost, $proxyport, $proxyusername, $proxypassword);
	
	
	
	$err = $client->getError();
	
	if ($err) {
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	}
	
	// Doc/lit parameters get wrapped
	$Username = "ssalud";
	$KeyName = "OQTZHHYLDQ2C6I57BV50QNZOHMVO730MLMEQR30SLSTIY";  //Clave Única SDK Mercacel
	$To = $para;  //Numero de celular de 10 digitos
	$Title = $titulo;    //Titlo para el mensaje. (No se envia)
	$Message = $mensaje; //Mensaje para enviar
	$Date = $fecha;  //Fecha para envio
	$Time = $hora;   //Hora para envio 
	
	
	$param = array("Username" => $Username, "KeyName" => $KeyName, "To" => $To,"Title" => $Title, "Message" => $Message, "Date" => $Date, "Time" => $Time);
	
	$result = $client->call('SendSMS', array('parameters' => $param), '', '', false, true);
	
	
	// Check for a fault
	if ($client->fault) {
		echo '<h2>Fault</h2><pre>';
		print_r($result);
		echo '</pre>';
	} else {
		// Check for errors
		$err = $client->getError();
		if ($err) {
			// Display the error
			echo '<h2>Error</h2><pre>' . $err . '</pre>';
		} else {
			/*// Display the result
			echo '<h2>Result</h2><pre>';
			print_r($result);
			echo '</pre>';*/
		}
	}
}
?>
