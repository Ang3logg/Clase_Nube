<?php
include '../../Scripts/Config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$subido_exitosamente = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id'])) {
    $sesion_id = intval($_POST['sesion_id']);
    $curso_id = intval($_SESSION['cursoid']); 
    $materiales = $_FILES['material'];

    if (!empty($materiales['name'][0])) {
        $target_dir = "../../uploads/materiales/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        for ($i = 0; $i < count($materiales['name']); $i++) {
            $filename = basename($materiales["name"][$i]);
            $target_file = $target_dir . $filename;
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            if (move_uploaded_file($materiales["tmp_name"][$i], $target_file)) {
                // Insertar en tabla materiales
                $sql = "INSERT INTO materiales (sesion_id, nombre_archivo, tipo_archivo) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $sesion_id, $filename, $extension);
                $stmt->execute();
                $subido_exitosamente = true;
            } else {
                echo "<script>alert('Error al subir uno o más archivos.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
                exit;
            }
        }
    } else {
        echo "<script>alert('No se seleccionó ningún archivo.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
        exit;
    }
} else {
    echo "<script>alert('Error: Datos incompletos para subir los materiales.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
    exit;
}

if ($subido_exitosamente) {
    echo "<script>alert('Materiales subidos y guardados correctamente.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
} else {
    echo "<script>alert('Error al guardar los materiales en la base de datos.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
}
?>
