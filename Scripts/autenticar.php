<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

session_start();

// Validar que el correo haya sido enviado
if (!isset($_POST['correo']) || empty($_POST['correo'])) {
    echo "<script>alert('Correo no recibido.'); window.location.href = '../html/signup.html';</script>";
    exit();
}

$correo = $_POST['correo'];
$token = substr(bin2hex(random_bytes(4)), 0, 8); // Token de 8 caracteres

$_SESSION['token_generado'] = $token;
$_SESSION['correo_temporal'] = $correo;

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
    $mail->Subject = 'Token de autenticación';
    $mail->Body = "Tu token de autenticación es: $token";

    $mail->send();

    echo "<script>alert('Código enviado'); window.location.href = '../html/signup.html';</script>";
} catch (Exception $e) {
    echo "Error al enviar el token: {$mail->ErrorInfo}";
}
?>
