<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

function mailGonder($alici_email, $konu, $icerik) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'webmertcanulubeyi@gmail.com'; // senin Gmail adresin
        $mail->Password = 'vhzq smap oaki exuo'; // uygulama şifresi
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('webmertcanulubeyi@gmail.com', 'ISG Uygulaması');
        $mail->addAddress($alici_email);

        $mail->isHTML(true);
        $mail->Subject = $konu;
        $mail->Body = $icerik;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail gönderilemedi: {$mail->ErrorInfo}");
        return false;
    }
}
