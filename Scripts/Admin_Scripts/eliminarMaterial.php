<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

$rdb = new firebaseRDB($databaseURL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_POST['material_index'])) {
    $sesion_id = $_POST['sesion_id'];
    $curso_id = $_SESSION['cursoid'];
    $material_index = $_POST['material_index'];


    // Eliminar el material de la base de datos
    $resultado = $rdb->delete("/sesiones/$curso_id/$sesion_id/material/",$material_index);

    if ($resultado) {
        $_SESSION['message'] = 'Material eliminado correctamente.';
    } else {
        $_SESSION['message'] = 'Error: No se pudo eliminar el material.';
    }

    // Redirigir a la página anterior
    header("Location: http://localhost/ClaseNubeUCV/html/Admin/GestionRecursos.php?curso_id=$curso_id");
    exit;
}

$_SESSION['message'] = 'No se proporcionó el ID de la sesión o el índice del material.';
header("Location: http://localhost/ClaseNubeUCV/html/Admin/GestionRecursos.php?curso_id=$curso_id");
exit;
?>