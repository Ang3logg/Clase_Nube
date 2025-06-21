<?php
include '../../Scripts/Config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validar si existe el curso en sesión
if (!isset($_SESSION['cursoid'])) {
    die("Error: No se encontró el ID del curso.");
}

$curso_id = $_SESSION['cursoid'];

// Verificar conexión a la base de datos
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener número de sesiones actuales del curso
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM sesiones WHERE curso_id = ?");
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$num_sesiones = $row['total'] + 1;
$stmt->close();

// Preparar nombre de la nueva sesión
$nombreSesion = 'Sesión ' . $num_sesiones;
$video_estado = 'Oculto';
$ia_resultado = 'Sin Contenido';

// Insertar nueva sesión en la base de datos
$stmt = $conn->prepare("INSERT INTO sesiones (curso_id, nombre, video_estado, ia_resultado) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $curso_id, $nombreSesion, $video_estado, $ia_resultado);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    // Redirigir al curso
    header('Location: ../../HTML/Profesor/CursoOp.php?curso_id=' . $curso_id);
    exit();
} else {
    echo "Error al agregar la sesión: " . $stmt->error;
    $stmt->close();
    $conn->close();
    exit();
}
?>
