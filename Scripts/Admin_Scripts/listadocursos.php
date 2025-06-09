<?php 
include '../../Scripts/Config.php';

$sql = "SELECT cursos.id, cursos.nombre_curso, cursos.descripcion, usuarios.nombre AS profesor 
        FROM cursos 
        LEFT JOIN usuarios ON cursos.profesor_id = usuarios.id 
        WHERE usuarios.tipo_usuario = 'profesor'";

$resultado = $conn->query($sql);
?>

<table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre del Curso</th>
            <th scope="col">Descripción</th>
            <th scope="col">Profesor</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($curso = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $curso['id']; ?></td>
                <td><?php echo $curso['nombre_curso']; ?></td>
                <td><?php echo $curso['descripcion']; ?></td>
                <td><?php echo $curso['profesor'] ? $curso['profesor'] : 'No asignado'; ?></td>
                <td>
                    <a href="../../Scripts/Admin_Scripts/ModificarCUR.php?id=<?php echo $curso['id']; ?>">
                        <i class="fa-sharp fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="../../Scripts/Admin_Scripts/EliminarCUR.php?id=<?php echo $curso['id']; ?>">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
