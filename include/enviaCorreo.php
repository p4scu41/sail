<?PHP 
    require_once 'Swift-5.0.1/lib/swift_required.php';
    
    function sendMail($to, $body, $subject='Notificación de la Plataforma del Programa de Prevención y Control de la Lepra')
    {
        $message = Swift_Message::newInstance()
            // Give the message a subject
            ->setSubject($subject)
            // Set the From address with an associative array
            ->setFrom(array('cievac@hotmail.com' => 'Notifificación de la Plataforma Lepra'))
            // Set the To addresses with an associative array
            ->setTo($to)
            // Give it a body
            ->setBody($body, 'text/html');
            // And optionally an alternative body
            //->addPart('<q>Here is the message itself</q>', 'text/html')
            // Optionally add any attachments
            //->attach(Swift_Attachment::fromPath('my-document.pdf'));

        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance('smtp.live.com', 587, 'tls')
        ->setUsername('cievac@hotmail.com')
        ->setPassword('chiapas123ads');

	    $transport->setLocalDomain('[127.0.0.1]');

        //$transport = Swift_MailTransport::newInstance();

        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        try {
            // Send the message
            if (!$mailer->send($message))
            {
                echo "ERROR al enviar el mensaje: ";
            }
        } catch (Exception $e) {
            echo 'ERROR al enviar el correo: '.$e->getMessage();
        }
    }
?>