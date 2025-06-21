<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesionid'])) {
    $sesionid = intval($_POST['sesionid']);
    $cursoid = intval($_SESSION['cursoid']);
    $video_name = $_POST['video_name'];

    // Actualizar el estado del video a "Disponible"
    $sql = "UPDATE sesiones SET video_estado = 'Disponible', video = ? WHERE id = ? AND curso_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $video_name, $sesionid, $cursoid);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Video habilitado correctamente.';
    } else {
        $_SESSION['message'] = 'Error: No se pudo mostrar el video.';
    }

    header("Location: http://localhost/ClaseNubeUCV/html/Admin/GestionRecursos.php?curso_id=$cursoid");
    exit;
}

$_SESSION['message'] = 'No se proporcionó el ID de la sesión.';
header("Location: http://localhost/ClaseNubeUCV/html/Admin/GestionRecursos.php?curso_id=$cursoid");
exit;
?>
