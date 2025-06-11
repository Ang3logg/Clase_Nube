<?php
include '../../Scripts/Config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $tipo_usuario = $_POST['tipo_usuario'];

    $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, tipo_usuario = ? WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $apellido, $tipo_usuario, $correo);
    $stmt->execute();
}

// Obtener todos los usuarios
$sql_usuarios = "SELECT * FROM usuarios";
$result = $conn->query($sql_usuarios);
$usuarios = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Usuarios</title>
    <link rel="stylesheet" href="../../Styles/estilos.css"> <!-- Opcional -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Listado de Usuarios</h2>

        <table class="table table-dark table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID / Correo</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario) { ?>
                    <tr>
                        <form method="post">
                            <td>
                                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                <input type="hidden" name="correo" value="<?php echo $usuario['correo']; ?>">
                                <?php echo $usuario['correo']; ?>
                            </td>
                            <td><input type="text" name="apellido" value="<?php echo $usuario['apellido']; ?>" class="form-control"></td>
                            <td><input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" class="form-control"></td>
                            <td>
                                <select name="tipo_usuario" class="form-select">
                                    <option value="admin" <?php echo ($usuario['tipo_usuario'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="profesor" <?php echo ($usuario['tipo_usuario'] === 'profesor') ? 'selected' : ''; ?>>Profesor</option>
                                    <option value="alumno" <?php echo ($usuario['tipo_usuario'] === 'alumno') ? 'selected' : ''; ?>>Alumno</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                                <a href="../Admin_Scripts/EliminarUSU.php?id=<?php echo $usuario['id']; ?>"
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');"
                                   class="btn btn-danger btn-sm">
                                    Eliminar
                                </a>
                            </td>
                        </form>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
