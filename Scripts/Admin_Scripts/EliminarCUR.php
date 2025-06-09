<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Verificar si se ha enviado el ID del curso a eliminar
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $_SESSION['id2']=$id;
    ?>

    <script>
        // Mostrar un cuadro de confirmación antes de eliminar el curso
        var confirmacion = confirm("¿Estás seguro de que deseas eliminar este curso?");
        if (confirmacion) {
            window.location.href = "EliminarCurso.php?id=<?php echo $id; ?>";
        } else {
            window.location.href = "../../html/Admin/Cursos.php"; // Redirigir de vuelta a la página de listado
        }
    </script>

    <?php
} else {
    // Si no se envió el ID del curso, redirigir a la página de listado de cursos
    header("Location: ../../html/Admin/Cursos.php");
    exit();
}
?>
