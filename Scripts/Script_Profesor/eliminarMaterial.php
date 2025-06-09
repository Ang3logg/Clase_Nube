<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_POST['material_index'])) {
    $sesion_id = intval($_POST['sesion_id']);
    $curso_id = intval($_SESSION['cursoid']); // debe estar seteado en sesión
    $material_id = intval($_POST['material_index']);

    // Eliminar el material
    $sql = "DELETE FROM materiales WHERE id = ? AND sesion_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $material_id, $sesion_id);
    $stmt->execute();

    $_SESSION['message'] = 'Material eliminado correctamente.';
    header("Location: http://localhost/ClaseNubeUCV/html/Profesor/CursoOp.php?curso_id=$curso_id");
    exit;
} else {
    $_SESSION['message'] = 'Error: No se pudo eliminar el material.';
    $curso_id = $_SESSION['cursoid'] ?? 0;
    header("Location: http://localhost/ClaseNubeUCV/html/Profesor/CursoOp.php?curso_id=$curso_id");
    exit;
}
?>
