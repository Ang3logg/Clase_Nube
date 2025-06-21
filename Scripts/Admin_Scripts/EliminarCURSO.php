<?php
include '../../Scripts/Config.php';
session_start();

if (isset($_SESSION['id2'])) {
    $id = intval($_SESSION['id2']); // ID del curso

    // 1. Obtener sesiones del curso
    $sql_get_sesiones = "SELECT id FROM sesiones WHERE curso_id = ?";
    $stmt_ses = $conn->prepare($sql_get_sesiones);
    $stmt_ses->bind_param("i", $id);
    $stmt_ses->execute();
    $res_ses = $stmt_ses->get_result();

    // 2. Eliminar materiales y videos asociados a cada sesión
    while ($sesion = $res_ses->fetch_assoc()) {
        $sesion_id = $sesion['id'];
        $conn->query("DELETE FROM materiales WHERE sesion_id = $sesion_id");
        $conn->query("DELETE FROM videos WHERE sesion_id = $sesion_id");
    }

    // 3. Eliminar las sesiones del curso
    $conn->query("DELETE FROM sesiones WHERE curso_id = $id");

    // 4. Eliminar matrículas del curso
    $conn->query("DELETE FROM alumnos_cursos WHERE curso_id = $id");

    // 5. Eliminar el curso
    $sql_delete = "DELETE FROM cursos WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // 6. Limpiar variable de sesión
    unset($_SESSION['id2']);

    header("Location: ../../html/Admin/Cursos.php");
    exit();
} else {
    header("Location: ../../html/Admin/Cursos.php");
    exit();
}
?>
