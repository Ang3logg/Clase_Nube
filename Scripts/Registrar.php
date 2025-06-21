<?php
include 'Config.php'; // ahora usa mysqli y conecta a tu BD MySQL

session_start();

// Verificar si se recibió el correo temporal desde la sesión
if (!isset($_SESSION['correo_temporal'])) {
    echo "<script>alert('No se encontró el correo temporal.');</script>";
    echo "<script>window.location.href = '../html/signup.html';</script>";
    exit();
}

$correotem = $_SESSION['correo_temporal'];

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$tipo_usuario = $_POST['tipo_usuario'];
$contraseña = $_POST['pass2'];

// Encriptar la contraseña
$contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

try {
    // Verificar si el correo ya está registrado
    $sql_check = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("s", $correotem);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('El correo ya está registrado');</script>";
        echo "<script>window.location.href = '../html/signup.html';</script>";
        exit();
    }

    // Insertar nuevo usuario
    $sql_insert = "INSERT INTO usuarios (correo, nombre, apellido, tipo_usuario, contraseña) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sssss", $correotem, $nombre, $apellido, $tipo_usuario, $contraseña_hash);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Cuenta creada con éxito');</script>";
        echo "<script>window.location.href = '../html/login.html';</script>";
    } else {
        echo "<script>alert('Error al crear la cuenta');</script>";
        echo "<script>window.location.href = '../html/signup.html';</script>";
    }
} catch (Exception $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    echo "<script>window.location.href = '../html/signup.html';</script>";
}
?>
