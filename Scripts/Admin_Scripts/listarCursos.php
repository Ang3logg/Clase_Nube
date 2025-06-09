<?php
$correo = $_GET['correo'];
include '../../Scripts/Config.php';

// Obtener el usuario por correo
$sql_user = "SELECT id FROM usuarios WHERE correo = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $correo);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows === 0) {
    echo "Usuario no encontrado.";
    exit();
}

$usuario = $result_user->fetch_assoc();
$usuario_id = $usuario['id'];

// Obtener todos los cursos
$sql_cursos = "SELECT id, nombre_curso FROM cursos";
$result_cursos = $conn->query($sql_cursos);

echo '<input type="hidden" name="correo" value="' . htmlspecialchars($correo) . '">';
if ($result_cursos->num_rows > 0) {
    echo '<label for="cursos">Seleccione los cursos:</label>';
    echo '<select name="cursos[]" id="cursos" multiple>';
    while ($curso = $result_cursos->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($curso['id']) . '">' . htmlspecialchars($curso['nombre_curso']) . '</option>';
    }
    echo '</select>';
} else {
    echo '<p>No hay cursos disponibles.</p>';
}
?>
