<?php 
include '../../Scripts/Config.php';

// Consulta para obtener los profesores desde MySQL
$sql = "SELECT id, nombre, apellido, correo, tipo_usuario FROM usuarios WHERE tipo_usuario = 'profesor'";
$result = $conn->query($sql);

$profesores = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $profesores[] = $row;
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
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($profesores as $profesor) { ?>
            <tr>
                <td><?php echo htmlspecialchars($profesor['correo']); ?></td>
                <td><?php echo htmlspecialchars($profesor['apellido']); ?></td>
                <td><?php echo htmlspecialchars($profesor['nombre']); ?></td>
                <td><?php echo htmlspecialchars($profesor['tipo_usuario']); ?></td>
                <td>
                    <!-- Enlace para copiar el correo -->
                    <a href="#" class="copy-icon" data-correo="<?php echo htmlspecialchars($profesor['correo']); ?>">
                        <i class="fa-duotone fa-copy" style="--fa-primary-color: #25db00; --fa-secondary-color: #25db00;"></i> Copiar
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
// Script para copiar el correo al portapapeles
document.addEventListener('DOMContentLoaded', function () {
    var copyIcons = document.querySelectorAll('.copy-icon');
    copyIcons.forEach(function (icon) {
        icon.addEventListener('click', function (event) {
            event.preventDefault();
            var correo = this.getAttribute('data-correo');
            navigator.clipboard.writeText(correo).then(function () {
                alert('Correo copiado al portapapeles: ' + correo);
            }, function () {
                alert('Error al copiar el correo');
            });
        });
    });
});
</script>
