<?php
include '../Config.php';

if (isset($_GET['id'])) {
    $usuario_id = intval($_GET['id']);

    // Primero elimina registros dependientes para evitar errores de claves foráneas
    $conn->query("DELETE FROM alumnos_cursos WHERE usuario_id = $usuario_id");
    $conn->query("DELETE FROM amigos WHERE usuario_id = $usuario_id OR amigo_id = $usuario_id");

    // Finalmente elimina el usuario
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Usuario eliminado correctamente.');
            window.location.href = '../Admin_Scripts/ListarUsuarios.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Error al eliminar el usuario.');
            window.location.href = '../Admin_Scripts/ListarUsuarios.php';
        </script>";
        exit();
    }
} else {
    // Si no se recibe un ID válido, redirige
    header("Location: ../Admin_Scripts/ListarUsuarios.php");
    exit();
}
?>
