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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .contenedor {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 25px;
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        thead th {
            color: white;
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        td {
            padding: 15px;
            vertical-align: middle;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.9rem;
            background-color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0 3px;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #218838, #1ea080);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333, #dc2626);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .email-cell {
            font-weight: 500;
            color: #667eea;
            min-width: 200px;
        }

        .actions-cell {
            white-space: nowrap;
            text-align: center;
        }

        @media (max-width: 768px) {
            .contenedor {
                margin: 10px;
                border-radius: 10px;
            }

            h1 {
                font-size: 1.5rem;
                padding: 20px;
            }

            .table-container {
                padding: 20px;
            }

            thead th {
                padding: 15px 10px;
                font-size: 0.8rem;
            }

            td {
                padding: 10px;
            }

            .form-control, .form-select {
                padding: 8px 10px;
                font-size: 0.85rem;
            }

            .btn {
                padding: 6px 12px;
                font-size: 0.8rem;
                margin: 2px;
            }
        }

        /* Animación de carga */
        .contenedor {
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Listado de Usuarios</h1>
        
        <div class="table-container">
            <table>
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
                                <td class="email-cell">
                                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                    <input type="hidden" name="correo" value="<?php echo $usuario['correo']; ?>">
                                    <?php echo htmlspecialchars($usuario['correo']); ?>
                                </td>
                                <td>
                                    <input type="text" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" class="form-control">
                                </td>
                                <td>
                                    <select name="tipo_usuario" class="form-select">
                                        <option value="admin" <?php echo ($usuario['tipo_usuario'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                        <option value="profesor" <?php echo ($usuario['tipo_usuario'] === 'profesor') ? 'selected' : ''; ?>>Profesor</option>
                                        <option value="alumno" <?php echo ($usuario['tipo_usuario'] === 'alumno') ? 'selected' : ''; ?>>Alumno</option>
                                    </select>
                                </td>
                                <td class="actions-cell">
                                    <button type="submit" class="btn btn-success">Guardar</button>
                                    <a href="../Admin_Scripts/EliminarUSU.php?id=<?php echo $usuario['id']; ?>"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');"
                                       class="btn btn-danger">
                                        Eliminar
                                    </a>
                                </td>
                            </form>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>