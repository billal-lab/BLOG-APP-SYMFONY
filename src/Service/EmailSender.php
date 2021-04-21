<?php

namespace App\Service;

use Exception;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

/**
 * le servive gerant l'envoie des email
 */
class EmailSender{
    

    /**
     * @param string $from l'emmeteur
     * @param string $from le recepteur
     * @return bool return true si tout se passe bien
     */
    public static function send($from, $to, $subject, $content, MailerInterface $mailer):bool {
        try{
            $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html('<p>'.$content.'</p>');
            $mailer->send($email);
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    
}