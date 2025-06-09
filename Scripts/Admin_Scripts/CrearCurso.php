<?php
include '../../Scripts/Config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_curso = $_POST['nombrecruso'];
    $descripcion = $_POST['descripcion'];
    $correo_profesor = $_POST['docente'];

    // Buscar el ID del profesor por su correo
    $sql_profesor = "SELECT id FROM usuarios WHERE correo = ? AND tipo_usuario = 'profesor'";
    $stmt = $conn->prepare($sql_profesor);
    $stmt->bind_param("s", $correo_profesor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Profesor no encontrado'); window.location.href='../../html/Admin/RegistroCursos.php';</script>";
        exit();
    }

    $row = $result->fetch_assoc();
    $profesor_id = $row['id'];

    // Insertar el nuevo curso
    $sql_insert = "INSERT INTO cursos (nombre_curso, descripcion, profesor_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("ssi", $nombre_curso, $descripcion, $profesor_id);
    $stmt->execute();

    header("Location: ../../html/Admin/RegistroCursos.php");
    exit();
} else {
    header("Location: ../../html/Admin/RegistroCursos.php");
    exit();
}
?>
