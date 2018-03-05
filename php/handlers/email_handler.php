<?php
require_once('../../vendor/autoload.php');
function sendEmail($email, $message){
    try {
        // prepare email message
        $message = (new Swift_Message())
                ->setSubject("Supervisor Alert")
                ->setFrom(["noreply@alekswebdemos.epizy.com"=>"Mak intern"])
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
