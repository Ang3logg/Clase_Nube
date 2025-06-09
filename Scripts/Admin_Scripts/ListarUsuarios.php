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

<table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">ID / Correo</th>
            <th scope="col">Apellidos</th>
            <th scope="col">Nombres</th>
            <th scope="col">Tipo</th>
            <th scope="col">Acciones</th>
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
                    <td><input type="text" name="apellido" value="<?php echo $usuario['apellido']; ?>"></td>
                    <td><input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>"></td>
                    <td>
                        <select name="tipo_usuario">
                            <option value="admin" <?php echo ($usuario['tipo_usuario'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="profesor" <?php echo ($usuario['tipo_usuario'] === 'profesor') ? 'selected' : ''; ?>>Profesor</option>
                            <option value="alumno" <?php echo ($usuario['tipo_usuario'] === 'alumno') ? 'selected' : ''; ?>>Alumno</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit">Guardar Cambios</button>
                    </td>
                </form>
            </tr>
        <?php } ?>
    </tbody>
</table>
