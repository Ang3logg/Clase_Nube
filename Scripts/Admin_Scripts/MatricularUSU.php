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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styles/Modificar.css">
    <title>Matricular Usuario</title>
</head>
<body>
    <div class="contenedor">
        <h1>Matricula - Seleccione curso</h1>
        <form action="MatricularUsuario.php" method="post">
            <input type="hidden" name="correo" value="<?php echo $usuario['correo']; ?>">
            <?php include '../Admin_Scripts/listarCursos.php'; ?>
            <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>
