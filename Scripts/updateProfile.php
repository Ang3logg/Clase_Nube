<?php
include 'Config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = $_SESSION['correo'] ?? null;

if (!$email) {
    echo "Usuario no identificado.";
    exit();
}

// Recolectar datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$descripcion = $_POST['descripcion'];
$profile_picture = $_FILES['profile_picture'];

$profile_picture_url = "";

// Manejo de imagen
if ($profile_picture['name']) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($profile_picture["name"]);

    if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
        $profile_picture_url = basename($profile_picture["name"]);
    } else {
        echo "Error al subir la imagen.";
        exit();
    }
}

// Preparar sentencia SQL
$sql = "UPDATE usuarios SET nombre = ?, apellido = ?, telefono = ?, descripcion = ?" . 
       ($profile_picture_url ? ", profile_picture = ?" : "") . " WHERE correo = ?";

$stmt = $conn->prepare($sql);

if ($profile_picture_url) {
    $stmt->bind_param("ssssss", $nombre, $apellido, $telefono, $descripcion, $profile_picture_url, $email);
} else {
    $stmt->bind_param("sssss", $nombre, $apellido, $telefono, $descripcion, $email);
}

// Ejecutar y redirigir
if ($stmt->execute()) {
    header("Location: ../html/MisDatos.php");
    exit();
} else {
    echo "Error al actualizar el perfil: " . $stmt->error;
}
?>
