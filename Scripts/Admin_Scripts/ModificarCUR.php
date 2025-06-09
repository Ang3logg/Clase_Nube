<?php 
$id = $_GET['id'];
include '../../Scripts/Config.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styles/Modificar.css">
    <title>Editar Curso</title>
</head>
<body>
    <div class="contenedor">
        <h1>Editar Curso</h1>
        <form action="ActualizarCurso.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="text" name="nombre_curso" placeholder="Nombre del Curso" value="<?php echo $curso['nombre_curso']; ?>">
            <input type="text" name="descripcion" placeholder="Descripción" value="<?php echo $curso['descripcion']; ?>">
            <input type="text" name="profesor" placeholder="Profesor (correo)" value="<?php echo $curso['profesor_id']; ?>">
            <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>
