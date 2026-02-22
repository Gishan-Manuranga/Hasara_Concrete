<?php
session_start();

if (!isset($_POST['send'])) {
    header("Location: contact.php");
    exit();
}

if (!empty($_POST['website'])) {
    header("Location: contact.php?sent=0");
    exit();
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    header("Location: contact.php?sent=0");
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: contact.php?sent=0");
    exit();
}


require_once __DIR__ . "/PHPMailer/class.phpmailer.php";
require_once __DIR__ . "/PHPMailer/class.smtp.php";


$yourGmail    = "hasaraconcrete@gmail.com";         
$appPassword  = "vygx narz ijcm shnz";            
$receiveTo    = "hasaraconcrete@gmail.com";         

$mail = new PHPMailer();

$mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Username = $yourGmail;
$mail->Password = $appPassword;
$mail->SMTPSecure = "tls";
$mail->Port = 587;

$mail->setFrom($yourGmail, "Hasara Concrete Website");
$mail->addAddress($receiveTo);
$mail->addReplyTo($email, $name);

$mail->Subject = "New Contact Message - Hasara Concrete";
$mail->Body =
    "New message from Contact Form:\n\n" .
    "Name: $name\n" .
    "Email: $email\n\n" .
    "Message:\n$message\n\n" .
    "----\nSent from website contact page.";

if (!$mail->send()) {
    die("Mail error: " . $mail->ErrorInfo);
}


header("Location: contact.php?sent=1");
exit();