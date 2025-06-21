<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['material_id'])) {
    $material_id = intval($_POST['material_id']);
    $curso_id = isset($_SESSION['cursoid']) ? intval($_SESSION['cursoid']) : 0;

    // Eliminar el material por su ID
    $sql = "DELETE FROM materiales WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $material_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Material eliminado correctamente.';
        } else {
            $_SESSION['message'] = 'Error al ejecutar la eliminación del material.';
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = 'Error al preparar la consulta.';
    }

    $conn->close();
    header("Location: ../../html/Profesor/CursoOp.php?curso_id=$curso_id");
    exit;
} else {
    $_SESSION['message'] = 'Error: No se proporcionó el ID del material.';
    $curso_id = isset($_SESSION['cursoid']) ? intval($_SESSION['cursoid']) : 0;
    header("Location: ../../html/Profesor/CursoOp.php?curso_id=$curso_id");
    exit;
}
