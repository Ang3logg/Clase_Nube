<?php 
$correo = $_GET['correo'];
include '../../Scripts/Config.php';

// Obtener usuario por correo
$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Usuario no encontrado.";
    exit();
}

$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Matricular Usuario</title>
    <link rel="stylesheet" href="../../Styles/Modificar.css">
</head>
<body>
    <div class="contenedor">
        <h1>Matricular Usuario - Seleccione cursos</h1>
        <form action="MatricularUsuario.php" method="post">
            <input type="hidden" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>">
            <?php include 'listarCursos.php'; ?>
            <br><br>
            <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>
