<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rdb = new firebaseRDB($databaseURL);

if (isset($_POST['sesion_id'])) {
    $sesion_id = $_POST['sesion_id'];
    $curso_id = $_SESSION['cursoid']; // Asegúrate de tener la sesión correcta aquí

    // Eliminar la sesión de la base de datos
    $resultado = $rdb->delete("/sesiones/$curso_id", $sesion_id);

    if ($resultado) {
        $_SESSION['message'] = 'Sesión eliminada correctamente.';
    } else {
        $_SESSION['message'] = 'Error: No se pudo eliminar la sesión.';
    }

     // Redirigir a cursoOp.php con el ID del curso
     header("Location: ../../html/Admin/GestionRecursos.php?curso_id=$curso_id");
     exit;
}

$_SESSION['message'] = 'No se proporcionó el ID de la sesión.';
header("Location: ../../html/Admin/GestionRecursos.php?curso_id=$curso_id");
exit;
?>
