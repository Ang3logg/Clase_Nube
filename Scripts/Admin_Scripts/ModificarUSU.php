<?php 
$correo = $_GET['correo'];
include '../../Scripts/Config.php';

// Buscar el usuario por correo
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styles/Modificar.css">
    <title>Editar Usuario</title>
</head>
<body>
    <div class="contenedor">
        <h1>Editar Usuario</h1>
        <form action="ActualizarUsuario.php" method="post">
            <input type="hidden" name="original_correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>">
            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
            <input type="text" name="apellido" placeholder="Apellidos" value="<?php echo htmlspecialchars($usuario['apellido']); ?>">
            <input type="email" name="correo" placeholder="Correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>">
            <select name="tipo_usuario" required>
                <option value="" disabled>Tipo de perfil</option>
                <option value="alumno" <?php if ($usuario['tipo_usuario'] === 'alumno') echo 'selected'; ?>>Alumno</option>
                <option value="profesor" <?php if ($usuario['tipo_usuario'] === 'profesor') echo 'selected'; ?>>Profesor</option>
                <option value="admin" <?php if ($usuario['tipo_usuario'] === 'admin') echo 'selected'; ?>>Admin</option>
            </select>
            <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>
