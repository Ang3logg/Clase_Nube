<?php 
$id = $_GET['id'];
include '../../Scripts/Config.php';

// Obtener el curso
$sql = "SELECT * FROM cursos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Curso no encontrado.";
    exit();
}

$curso = $result->fetch_assoc();
$profesor_id = $curso['profesor_id'];

// Obtener el correo del profesor
$correo_profesor = 'No encontrado';
$sqlCorreo = "SELECT correo FROM usuarios WHERE id = ?";
$stmtCorreo = $conn->prepare($sqlCorreo);
$stmtCorreo->bind_param("i", $profesor_id);
$stmtCorreo->execute();
$resultCorreo = $stmtCorreo->get_result();

if ($resultCorreo->num_rows > 0) {
    $correo_profesor = $resultCorreo->fetch_assoc()['correo'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
    <link rel="stylesheet" href="../../Styles/Modificar.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Curso</h1>
        <form action="ActualizarCurso.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <input type="text" name="nombre_curso" placeholder="Nombre del Curso" 
                   value="<?php echo htmlspecialchars($curso['nombre_curso']); ?>">

            <input type="text" name="descripcion" placeholder="Descripción" 
                   value="<?php echo htmlspecialchars($curso['descripcion']); ?>">

            <input type="text" name="profesor" placeholder="Profesor (correo)" 
                   value="<?php echo htmlspecialchars($correo_profesor); ?>" readonly>

            <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>
