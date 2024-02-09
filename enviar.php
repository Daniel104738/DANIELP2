<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar los campos del formulario
    if (empty($_POST['name']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(500);
        exit();
    }

    // Obtener datos del formulario y sanearlos
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);


// Configure PHPMailer
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
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



if (!empty($_FILES['adjunto']['name'])) {
    $adjunto_nombre = $_FILES['adjunto']['name'];
    $adjunto_tmp_name = $_FILES['adjunto']['tmp_name'];

    if ($_FILES['adjunto']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(500);
        echo "Error al subir el archivo: " . $_FILES['adjunto']['error'];
        exit();
    }

    // Definir la ruta donde deseas guardar los archivos adjuntos
    $ruta_destino = 'adjuntos/' . $adjunto_nombre;

    // Mover el archivo cargado a la ubicación deseada
    if (!move_uploaded_file($adjunto_tmp_name, $ruta_destino)) {
        http_response_code(500);
        echo "Error al mover el archivo adjunto";
        exit();
    }

    // Agregar el archivo adjunto al correo
    $mail->addAttachment($ruta_destino);
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
}else {
    // Redirigir si no es una solicitud POST
    header("Location: contact.html");
  exit();
}
?>