<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_FILES['video'])) {
    $sesion_id = intval($_POST['sesion_id']);
    $curso_id = $_SESSION['cursoid'] ?? null;
    $video = $_FILES['video'];

    if (!$curso_id) {
        echo "<script>alert('No se encontró el ID del curso en la sesión.'); window.history.back();</script>";
        exit;
    }

    if (!empty($video['name']) && $video['error'] === 0) {
        $target_dir = "../../uploads/videos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Generar nombre aleatorio y ruta
        $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        $video_name = "{$curso_id}_{$sesion_id}_{$random_chars}_" . basename($video["name"]);
        $target_file = $target_dir . $video_name;
        $ruta_para_bd = "uploads/videos/" . $video_name;

        // Mover archivo
        if (move_uploaded_file($video["tmp_name"], $target_file)) {

            // Guardar video en la tabla 'videos'
            $stmt = $conn->prepare("INSERT INTO videos (ruta_video, sesion_id) VALUES (?, ?)");
            $stmt->bind_param("si", $ruta_para_bd, $sesion_id);

            if ($stmt->execute()) {
                // Ejecutar script Python para análisis IA
                $output_txt = "../../uploads/Documentos/{$video_name}.txt";
                $python_path = "C:\\Users\\mange\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
                $script_path = "IA.py";
                $command = "\"$python_path\" \"$script_path\" \"$target_file\" \"$output_txt\" 2>&1";

                exec($command, $output, $return_code);

                if ($return_code === 0) {
                    $ia_txt_filename = basename($output_txt);
                    $stmt2 = $conn->prepare("UPDATE sesiones SET ia = ?, video_estado = 'Disponible' WHERE id = ? AND curso_id = ?");
                    $stmt2->bind_param("sii", $ia_txt_filename, $sesion_id, $curso_id);

                    if ($stmt2->execute()) {
                        echo "<script>alert('Video subido y análisis generado correctamente.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
                    } else {
                        echo "<script>alert('Error al actualizar sesión con IA.'); window.history.back();</script>";
                    }

                    $stmt2->close();
                } else {
                    echo "<script>alert('Error al ejecutar IA.py. Código: $return_code'); window.history.back();</script>";
                }

            } else {
                echo "<script>alert('Error al guardar el video en la base de datos.'); window.history.back();</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error al mover el archivo al servidor.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Archivo no válido o no se seleccionó archivo.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Solicitud no válida.'); window.history.back();</script>";
}
?>
