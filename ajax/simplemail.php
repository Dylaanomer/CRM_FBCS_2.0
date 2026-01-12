<?php

echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n";

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

require 'dbh.php';

$today = new DateTime();
$now = $today->format('Y-m-d H:i:s');
$today = $today->format('Y-m-d');

$smtp_server = 'smtp.office365.com';
$smtp_port = 587; //USING STARTTLS

$name_from = 'FBCS reminders';
$mail_from = 'reminder@fbcs.nl';
$mail_from_password = 'Xoc74130';

$name_to = 'Info FBCS';
$mail_to = 'info@fbcs.nl';

$mail = new PHPMailer(true);
$mail->SMTPDebug = 3; //Alternative to above constant
$mail->isSMTP();
$mail->Timeout  = 15;
$mail->Host     = $smtp_server;
$mail->SMTPAuth = true;
$mail->Username = $mail_from;
$mail->Password = $mail_from_password;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = $smtp_port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->SMTPOptions = array(
              'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true));

$mail->setFrom($mail_from, $name_from);
$mail->addAddress($mail_to);
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the body.';

if(!$mail->Send()) {
    echo 'Message was not sent.';
    echo 'Mailer error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent.';
}

?>

