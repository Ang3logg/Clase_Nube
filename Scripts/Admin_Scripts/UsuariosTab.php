<?php 
include '../../Scripts/Config.php';

// Recuperar todos los usuarios
$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);
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
        <?php while ($usuario = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $usuario['correo']; ?></td>
                <td><?php echo $usuario['apellido']; ?></td>
                <td><?php echo $usuario['nombre']; ?></td>
                <td><?php echo $usuario['tipo_usuario']; ?></td>
                <td>
                    <a href="../../Scripts/Admin_Scripts/MatricularUSU.php?correo=<?php echo $usuario['correo']; ?>"><i class="fa-solid fa-book" style="color: #63E6BE;"></i></a>
                    <a href="../../Scripts/Admin_Scripts/ModificarUSU.php?correo=<?php echo $usuario['correo']; ?>"><i class="fa-sharp fa-solid fa-pen-to-square"></i></a>
                    <a href="../../Scripts/Admin_Scripts/EliminarUSU.php?correo=<?php echo $usuario['correo']; ?>"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
