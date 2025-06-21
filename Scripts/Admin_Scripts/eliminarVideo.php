<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_POST['video_url'])) {
    $sesion_id = intval($_POST['sesion_id']);
    $curso_id = intval($_SESSION['cursoid']);
    $video_url = $_POST['video_url'];

    // Eliminar referencia al video en base de datos
    $sql_delete = "DELETE FROM videos WHERE sesion_id = ? AND nombre_archivo = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("is", $sesion_id, $video_url);
    $stmt->execute();

    // También limpiar en tabla `sesiones` si hay `ia_resultado` asociado
    $sql_clear = "UPDATE sesiones SET ia_resultado = NULL, video_estado = 'No disponible' WHERE id = ?";
    $stmt2 = $conn->prepare($sql_clear);
    $stmt2->bind_param("i", $sesion_id);
    $stmt2->execute();

    // Eliminar archivo físico si existe
    $ruta_video = "../../uploads/videos/" . $video_url;
    if (file_exists($ruta_video)) {
        unlink($ruta_video);
    }

    echo "$sesion_id <br> $curso_id<br> $video_url<br>";
    exit;
}
?>
