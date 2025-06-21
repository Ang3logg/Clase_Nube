<?php
include '../../Scripts/Config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $original_correo = $_POST['original_correo'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Verificar si existe el usuario
    $sql = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $original_correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Usuario no encontrado'); window.location.href='../../html/Admin/RegistroAlumno.php';</script>";
        exit();
    }

    $usuario = $result->fetch_assoc();
    $usuario_id = $usuario['id'];

    // Actualizar el usuario
    $sql_update = "UPDATE usuarios SET nombre = ?, apellido = ?, correo = ?, tipo_usuario = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $nombre, $apellido, $correo, $tipo_usuario, $usuario_id);
    $stmt_update->execute();

    header("Location: ../../html/Admin/RegistroAlumno.php");
    exit();
} else {
    echo "<script>alert('Acceso no permitido'); window.location.href='../../html/Admin/RegistroAlumno.php';</script>";
    exit();
}
?>
