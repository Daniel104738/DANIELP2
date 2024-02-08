<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Validar y sanitizar inputs del formulario
$name = htmlspecialchars($_POST['nombre']);
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

// Check for uploaded file (if applicable)
$file = $_FILES['archivo'];
$hasFile = $file['error'] === UPLOAD_ERR_OK;

// Add validation (optional, adjust as needed)
if ($hasFile) {
    // Check file size (e.g., limit to 1 MB)
    if ($file['size'] > 1048576) {
        http_response_code(400); // Bad request
        exit("El archivo no puede superar los 1 MB.");
    }
    // Check allowed file types (e.g., images only)
    $allowed_mime_types = ['image/png', 'image/jpeg', 'image/jpg'];
    if (!in_array($file['type'], $allowed_mime_types)) {
        http_response_code(400); // Bad request
        exit("Solo se permiten imágenes PNG, JPG o JPEG.");
    }
}

// Configure PHPMailer
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.zoho.com'; // Reemplaza con tu servidor SMTP
$mail->SMTPAuth = true;
$mail->Username = 'cafecito@pinedodaniel.shop'; // Reemplaza con tu correo electrónico
$mail->Password = 'jaziulxd'; // Reemplaza con tu contraseña
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

// Set sender and recipient for Zoho
$mail->setFrom('cafecito@pinedodaniel.shop', 'Daniel');
$mail->addAddress('cafecito@pinedodaniel.shop'); // To your Zoho email

// Set content for Zoho
$mail->isHTML(true);
$mail->Subject = "Nuevo Mensaje de Contacto";
$mail->Body = "Has recibido un nuevo mensaje desde el formulario de contacto de tu sitio web.<br><br>Detalles:<br><br>Nombre: $name<br>Email: $email<br>Mensaje: $message";

if ($hasFile) {
    try {
        $mail->addAttachment($file['tmp_name'], $file['name']);
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo "Error al adjuntar el archivo: " . $e->getMessage();
        exit();
    }
}

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

