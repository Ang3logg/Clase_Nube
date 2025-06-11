<?php
include '../../Scripts/Config.php';

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    // Verificar si el usuario existe
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Verificar si el usuario es profesor con cursos asignados
        $stmtCursos = $conn->prepare("SELECT COUNT(*) FROM cursos WHERE profesor_id = ?");
        $stmtCursos->bind_param("i", $usuario_id);
        $stmtCursos->execute();
        $stmtCursos->bind_result($cursosAsociados);
        $stmtCursos->fetch();
        $stmtCursos->close();

        if ($cursosAsociados > 0) {
            echo "<script>
                alert('No puedes eliminar este usuario porque es profesor de uno o más cursos.');
                window.location.href = '../Admin/ListarUsuarios.php';
            </script>";
            exit();
        }

        // Eliminar relaciones de amistad
        $stmt = $conn->prepare("DELETE FROM amigos WHERE usuario_id = ? OR amigo_id = ?");
        $stmt->bind_param("ii", $usuario_id, $usuario_id);
        $stmt->execute();
        $stmt->close();

        // Eliminar de alumnos_cursos
        $stmt = $conn->prepare("DELETE FROM alumnos_cursos WHERE usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->close();

        // Finalmente, eliminar el usuario
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->close();

        echo "<script>
            alert('Usuario eliminado correctamente.');
            window.location.href = '../Admin/ListarUsuarios.php';
        </script>";
        exit();

    } else {
        echo "<script>
            alert('Usuario no encontrado.');
            window.location.href = '../Admin/ListarUsuarios.php';
        </script>";
        exit();
    }
} else {
    header("Location: ../Admin/ListarUsuarios.php");
    exit();
}
?>
