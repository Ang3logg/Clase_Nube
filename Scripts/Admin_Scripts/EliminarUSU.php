<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Verificar si se ha enviado el correo del usuario a eliminar
if(isset($_GET['correo'])) {
    $correo = $_GET['correo'];
    
    $_SESSION['correo2']=$correo;
    ?>

    <script>
        // Mostrar un cuadro de confirmación antes de eliminar el usuario
        var confirmacion = confirm("¿Estás seguro de que deseas eliminar este usuario?");
        if (confirmacion) {
            window.location.href = "EliminarUsuario.php?correo=<?php echo $correo; ?>";
        } else {
            window.location.href = "../Admin/ListarUsuarios.php"; // Redirigir de vuelta a la página de listado
        }
    </script>

    <?php
} else {
    // Si no se envió el correo del usuario, redirigir a la página de listado de usuarios
    header("Location: ../Admin/ListarUsuarios.php");
    exit();
}
?>
