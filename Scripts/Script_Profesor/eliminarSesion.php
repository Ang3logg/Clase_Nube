<?php
include '../../Scripts/Config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['sesion_id'])) {
    $sesion_id = intval($_POST['sesion_id']);
    $curso_id = $_SESSION['cursoid'] ?? 0;

    // Eliminar los materiales relacionados (opcional pero recomendable)
    $stmtMateriales = $conn->prepare("DELETE FROM materiales WHERE sesion_id = ?");
    $stmtMateriales->bind_param("i", $sesion_id);
    $stmtMateriales->execute();
    $stmtMateriales->close();

    // Eliminar el video relacionado (opcional pero recomendable)
    $stmtVideos = $conn->prepare("DELETE FROM videos WHERE sesion_id = ?");
    $stmtVideos->bind_param("i", $sesion_id);
    $stmtVideos->execute();
    $stmtVideos->close();

    // Eliminar la sesión
    $stmtSesion = $conn->prepare("DELETE FROM sesiones WHERE id = ?");
    $stmtSesion->bind_param("i", $sesion_id);
    
    if ($stmtSesion->execute()) {
        $_SESSION['message'] = 'Sesión eliminada correctamente.';
    } else {
        $_SESSION['message'] = 'Error: No se pudo eliminar la sesión.';
    }

    $stmtSesion->close();
    $conn->close();

    header("Location: ../../html/Admin/GestionRecursos.php?curso_id=$curso_id");
    exit;
}

$_SESSION['message'] = 'No se proporcionó el ID de la sesión.';
$curso_id = $_SESSION['cursoid'] ?? 0;
header("Location: ../../html/Admin/GestionRecursos.php?curso_id=$curso_id");
exit;
?>
