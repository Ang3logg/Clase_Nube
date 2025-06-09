<?php
include '../../Scripts/Config.php';

session_start();

// Obtener los datos del formulario
$correo = $_POST['correo'];
$cursosSeleccionados = $_POST['cursos'];

// Validaciones
if (empty($correo) || empty($cursosSeleccionados)) {
    echo "Correo o cursos no proporcionados.";
    exit();
}

// Buscar el ID del usuario por su correo
$sql_usuario = "SELECT id FROM usuarios WHERE correo = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $correo);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

if ($result_usuario->num_rows === 0) {
    echo "Usuario no encontrado.";
    exit();
}

$row_usuario = $result_usuario->fetch_assoc();
$usuario_id = $row_usuario['id'];

// Matricular al usuario en cada curso
foreach ($cursosSeleccionados as $curso_id) {
    // Validar que el curso exista
    $sql_check_curso = "SELECT id FROM cursos WHERE id = ?";
    $stmt_curso = $conn->prepare($sql_check_curso);
    $stmt_curso->bind_param("i", $curso_id);
    $stmt_curso->execute();
    $result_curso = $stmt_curso->get_result();

    if ($result_curso->num_rows > 0) {
        // Insertar matrícula si no existe ya
        $sql_insert = "INSERT INTO alumnos_cursos (usuario_id, curso_id)
                       SELECT ?, ? FROM DUAL
                       WHERE NOT EXISTS (
                           SELECT 1 FROM alumnos_cursos WHERE usuario_id = ? AND curso_id = ?
                       )";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iiii", $usuario_id, $curso_id, $usuario_id, $curso_id);
        $stmt_insert->execute();
    }
}

echo "Usuario matriculado en los cursos seleccionados.";
?>
