<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // El usuario no está autenticado, redirigir a la página de inicio de sesión
    header("Location: login.html");
    exit(); // Asegura que el script se detenga después de la redirección
}
?>