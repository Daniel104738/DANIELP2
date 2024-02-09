<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Validar y sanitizar entradas del request AJAX
$nombre = htmlspecialchars($_POST['name']);
$correo = htmlspecialchars($_POST['email']);
$mensaje = htmlspecialchars($_POST['message']);

// Validar campos vacíos
if (empty($nombre) || empty($correo) || empty($mensaje)) {
    http_response_code(400); // Solicitud incorrecta
    exit();
}

// Validar formato de email
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Solicitud incorrecta
    exit();
}

// Validar archivo
if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
    // Validar tamaño de archivo
    if ($_FILES['file']['size'] > 1048576) { // Límite de 1MB
        echo json_encode(['error' => 'El archivo es demasiado grande. Límite: 1MB']);
        exit();
    }

    // Validar tipo de archivo
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'pdf'];
    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    if (!in_array($extension, $extensionesPermitidas)) {
        echo json_encode(['error' => 'Tipo de archivo no permitido. Permitidos: ' . implode(', ', $extensionesPermitidas)]);
        exit();
    }

    // Guardar archivo
    $rutaDestino = "uploads/" . $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $rutaDestino);

    // Adjuntar archivo al correo
    $mail->addAttachment($rutaDestino, $_FILES['file']['name']);
}

// Configurar PHPMailer
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.zoho.com';
$mail->SMTPAuth = true;
$mail->Username = 'cafecito@pinedodaniel.shop';
$mail->Password = 'jaziulxd';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

// Configurar remitente y destinatario para Zoho
$mail->setFrom('cafecito@pinedodaniel.shop', 'Daniel');
$mail->addAddress('cafecito@pinedodaniel.shop'); // A tu correo de Zoho

// Configurar contenido para Zoho
$mail->isHTML(true);
$mail->Subject = "Nuevo Mensaje de Contacto";
$mail->Body = "Has recibido un nuevo mensaje desde el formulario de contacto de tu sitio web.<br><br>Detalles:<br><br>Nombre: $nombre<br>Email: $correo<br>Mensaje: $mensaje";

// Enviar correo a Zoho
$mail->send();

// Enviar correo de confirmación al usuario
$mail->clearAddresses();
$mail->addAddress($correo);
$mail->Subject = "Gracias por ponerte en contacto";
$mail->Body = "¡Gracias por ponerte en contacto! Hemos recibido tu mensaje y nos pondremos en contacto contigo pronto.";

$mail->send();

// Enviar respuesta exitosa al request AJAX
echo json_encode(['success' => 'Mensaje enviado correctamente']);
