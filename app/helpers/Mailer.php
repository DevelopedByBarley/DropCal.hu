<?php

use PHPMailer\PHPMailer\PHPMailer;


class Mailer
{
    public function send($body, $subject)
    {
        $address = "arpadsz@max.hu";

        try {
            $mail = new PHPMailer;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->CharSet = 'UTF-8';
            // $mail->SMTPDebug  = 1;
            $mail->IsSMTP(); // SMTP-n keresztüli küldés
            $mail->SMTPAuth = false;

            $mail->Host = "owa.rufusz.hu";

            $mail->setFrom("DropCal.hu", "DropCal.hu");
            $mail->addAddress($address, $address);     //Add a recipient
            $mail->addReplyTo("arpadsz@max.hu", "Szaniszló Árpád");

            //Content
            $mail->isHTML(true); //Set email format to HTML

            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);

            $mail->Send();
        } catch (Exception $e) {
            var_dump($e);
            return false;
        }
    }
}





/**
 * class Mailer
{
    public function send($address, $body, $subject)
    {

        try {
            $mail = new PHPMailer;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug  = 3;
            $mail->IsSMTP(); // SMTP-n keresztüli küldés
            $mail->SMTPAuth = true;

            $mail->Host = "smtp.gmail.com";
            $mail->Username = "BarleyMailer@gmail.com";
            $mail->Password = "pdfnnhcofadkgglw";

            $mail->addAddress($address, $address);     //Add a recipient
            $mail->addReplyTo("BarleyMailer@gmail.com", "BarleyMailer@gmail.com");
            $mail->setFrom("BarleyMailer@gmail.com", "DropCalories.hu");
            $mail->SMTPSecure = 'tls';            //Content
            $mail->isHTML(true); //Set email format to HTML

            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            $mail->Port = 587;

            $mail->Send();
        } catch (Exception $e) {
            var_dump($e);
            return false;
        }
    }
}


 * 
 */
