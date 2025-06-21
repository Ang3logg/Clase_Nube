<?php
// Incluir la clase de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar el autoloader de PHPMailer
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

$token = substr(bin2hex(random_bytes(4)), 0, 8); // Generar un token de 8 caracteres (4 bytes convertidos a hexadecimal)

session_start();
$_SESSION['token_generado'] = $token;
$_SESSION['correotemp'] = $_POST['correo'];

$correo = $_POST['correo'];

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'macsoftinnovation@gmail.com'; 
    $mail->Password = 'rpbcquijhdpxiazr'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('macsoftinnovation@gmail.com'); 
    $mail->addAddress($correo);
    $mail->Subject = 'Token de autenticacion';
    $mail->Body = "Tu token de autenticacion es: $token";

    $mail->send();
    
    echo "<script>alert('Codigo Enviado');</script>";
            echo "<script>window.location.href = '../html/OlvidoContrasena.html';</script>";

} catch (Exception $e) {
    echo "Error al enviar el token de autenticación: {$mail->ErrorInfo}";
}
?>