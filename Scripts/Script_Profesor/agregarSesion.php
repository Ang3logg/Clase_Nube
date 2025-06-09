<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$curso_id = $_SESSION['cursoid'];
$rdb = new firebaseRDB($databaseURL);

// Obtener todas las sesiones del curso
$retrieveSesiones = $rdb->retrieve("/sesiones/$curso_id");
$sesionesData = json_decode($retrieveSesiones, true);

// Obtener el próximo ID de sesión disponible
$sesion_id = 1;
if (is_array($sesionesData)) {
    $ids = array_column($sesionesData, 'id');
    if (!empty($ids)) {
        $sesion_id = max($ids) + 1; // Asignar el siguiente número mayor
    }
}

// Crear nueva sesión con datos básicos
$nuevaSesion = [
    'id_estatico' => $curso_id,
    'id' => $sesion_id,
    'nombre' => 'Sesión ' . $sesion_id,
    'material' => '',
    'video' => '',
    'contenido' => 'Sin Contenido'
];

// Guardar la sesión en la base de datos
$resultado = $rdb->update("/sesiones/$curso_id/$sesion_id", null, $nuevaSesion);

// Redirigir de vuelta a la página del curso
header('Location: ../../html/Profesor/CursoOp.php?curso_id=' . $curso_id);
exit();
?>
