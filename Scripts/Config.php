<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datos de conexión a la base de datos MySQL
$host = "localhost";
$user = "root";
$password = ""; // En XAMPP suele estar vacío
$database = "clasenube_db"; // Cambialo si usás otro nombre de BD

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar si hay error de conexión
if ($conn->connect_error) {
    die("Conexión fallida a MySQL: " . $conn->connect_error);
}
?>