<?php
require_once('../../vendor/autoload.php');
function sendEmail($email, $message){
    try {
        $smtp_server = 'mail.makintern.com';
        $port = 589;
        $username = 'noreply@makintern.com';
        $password = 'henoddo6600';
        // prepare email message
        $message = (new Swift_Message())
                ->setSubject("Supervisor Alert")
                ->setFrom(["noreply@makintern.com"=>"Mak intern"])
                ->setTo([$email])
                ->setBody($message);

    //    create transport
        $transport = (new Swift_SmtpTransport($smtp_server, 25))
            ->setUsername($username)
            ->setPassword($password);
    
        $mailer = new Swift_Mailer($transport);
        
        $results = $mailer->send($message);
    
        return $results;
    
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
