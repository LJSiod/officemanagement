<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'itstaffneocash@gmail.com';
    $mail->Password = 'rgja fxol indy zkfo';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('itstaffneocash@gmail.com', 'Your Name');
    $mail->addAddress('itstaffneocash@gmail.com'); // Recipient

    // Content
    $mail->isHTML(false);
    $mail->Subject = 'My Subject';
    $mail->Body = 'This is a test email sent from PHPMailer!';

    $mail->send();
    echo 'Email successfully sent';
} catch (Exception $e) {
    echo "Email sending failed. Mailer Error: {$mail->ErrorInfo}";
}
?>