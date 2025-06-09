<?php
include 'Config.php'; // Aquí se debe definir $conn como mysqli
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar que correo exista en sesión
if (!isset($_SESSION['correotemp'])) {
    die("Error: No hay correo definido en sesión.");
}

$correo = $_SESSION['correotemp'];

if (!isset($_SESSION['token_validado']) || !isset($_SESSION['token_generado'])) {
    die("Error: Tokens no definidos.");
}

if ($_SESSION['token_validado'] !== $_SESSION['token_generado']) {
    die("Token inválido.");
}

if (!isset($_POST['nueva_contrasena'], $_POST['confirmar_contrasena'])) {
    die("Error: Datos de contraseña incompletos.");
}

$nueva_contrasena = $_POST['nueva_contrasena'];
$confirmacion = $_POST['confirmar_contrasena'];

if ($nueva_contrasena !== $confirmacion) {
    die("Contraseñas distintas.");
}

// Aquí puedes agregar validaciones de seguridad para la contraseña (largo, caracteres, etc.)

// Preparar la consulta para actualizar la contraseña del usuario
// Asumo que tu tabla usuarios tiene campos: correo, contraseña (o password)

$sql = "UPDATE usuarios SET contraseña = ? WHERE correo = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

// Hashear la contraseña antes de guardar (recomendado)
$hash_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

$stmt->bind_param("ss", $hash_password, $correo);

if ($stmt->execute()) {
    // Contraseña actualizada exitosamente
    echo "<script>alert('Contraseña cambiada correctamente.');</script>";
    echo "<script>window.location.href = '../html/login.html';</script>";
} else {
    echo "Error al actualizar la contraseña: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
