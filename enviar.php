<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Validate and sanitize inputs from AJAX request
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);

// Check for empty fields
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400); // Bad request
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Bad request
    exit();
}

// Configure PHPMailer
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.zoho.com';
$mail->SMTPAuth = true;
$mail->Username = 'cafecito@pinedodaniel.shop';
$mail->Password = 'jaziulxd';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

// Set sender and recipient for Zoho
$mail->setFrom('cafecito@pinedodaniel.shop', 'Daniel');
$mail->addAddress('cafecito@pinedodaniel.shop'); // To your Zoho email

// Set content for Zoho
$mail->isHTML(true);
$mail->Subject = "Nuevo Mensaje de Contacto";
$mail->Body = "Has recibido un nuevo mensaje desde el formulario de contacto de tu sitio web.<br><br>Detalles:<br><br>Nombre: $name<br>Email: $email<br>Mensaje: $message";


    // Send email to Zoho
    $mail->send();

    // Send confirmation email to user
    $mail->clearAddresses();
    $mail->addAddress($email);
    $mail->Subject = "Gracias por ponerte en contacto";
    $mail->Body = "¡Gracias por ponerte en contacto! Hemos recibido tu mensaje y nos pondremos en contacto contigo pronto.";

    $mail->send();

    // Send successful response to AJAX request
    echo "Mensaje enviado correctamente";
