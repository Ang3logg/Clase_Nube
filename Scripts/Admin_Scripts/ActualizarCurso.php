<?php
include '../../Scripts/Config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nombre_curso = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $correo_profesor = $_POST['profesor'];

    // Buscar ID del profesor por correo
    $sql_prof = "SELECT id FROM usuarios WHERE correo = ? AND tipo_usuario = 'profesor'";
    $stmt_prof = $conn->prepare($sql_prof);
    $stmt_prof->bind_param("s", $correo_profesor);
    $stmt_prof->execute();
    $res_prof = $stmt_prof->get_result();

    if ($res_prof->num_rows === 0) {
        echo "Profesor no encontrado.";
        exit();
    }

    $prof = $res_prof->fetch_assoc();
    $profesor_id = $prof['id'];

    // Actualizar el curso
    $sql_update = "UPDATE cursos SET nombre_curso = ?, descripcion = ?, profesor_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssii", $nombre_curso, $descripcion, $profesor_id, $id);
    $stmt->execute();

    header("Location: ../../html/Admin/Cursos.php");
    exit();
} else {
    header("Location: ../../html/Admin/Cursos.php");
    exit();
}
?>
