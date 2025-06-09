<?php
include '../../Scripts/Config.php';

session_start();

if (isset($_SESSION['correo2'])) {
    $correo = $_SESSION['correo2'];

    // Obtener ID del usuario por su correo
    $sql_get = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->bind_param("s", $correo);
    $stmt_get->execute();
    $result = $stmt_get->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usuario_id = $row['id'];

        // Eliminar relaciones: primero eliminamos en otras tablas para evitar errores de FK
        $conn->query("DELETE FROM alumnos_cursos WHERE usuario_id = $usuario_id");
        $conn->query("DELETE FROM amigos WHERE usuario_id = $usuario_id OR amigo_id = $usuario_id");
        $conn->query("DELETE FROM cursos WHERE profesor_id = $usuario_id"); // si fue profe

        // Eliminar el usuario
        $sql_delete = "DELETE FROM usuarios WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $usuario_id);
        $stmt_delete->execute();
    }

    header("Location: ../../html/Admin/RegistroAlumno.php");
    exit();
} else {
    header("Location: ../../html/Admin/ListarUsuarios.php");
    exit();
}
?>
