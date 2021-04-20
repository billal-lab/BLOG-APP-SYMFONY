<?php

namespace App\Service;

use Exception;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class EmailSender{
    
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