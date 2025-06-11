<?php
include '../../Scripts/Config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_FILES['video'])) {
    $sesion_id = $_POST['sesion_id'];
    $curso_id = $_SESSION['cursoid'];
    $video = $_FILES['video'];

    if (!empty($video['name'])) {
        $target_dir = "../../uploads/videos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        $video_url = "{$curso_id}_{$sesion_id}_{$random_chars}_" . basename($video["name"]);
        $target_file = $target_dir . $video_url;

        if (move_uploaded_file($video["tmp_name"], $target_file)) {
            // Guardar en MySQL
            $stmt = $conn->prepare("UPDATE sesiones SET video = ? WHERE id = ? AND curso_id = ?");
            $stmt->bind_param("sii", $video_url, $sesion_id, $curso_id);

            if ($stmt->execute()) {
                // Ejecutar script Python
                $input_video = $target_file;
                $output_txt = "../../uploads/Documentos/{$video_url}.txt";
                $python_path = "C:\\Users\\mange\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
                $script_path = "IA.py";
                $command = "\"$python_path\" \"$script_path\" \"$input_video\" \"$output_txt\" 2>&1";

                exec($command, $output, $return_code);

                echo "<pre>";
                echo "Salida de IA.py:\n";
                echo implode("\n", $output);
                echo "\nCódigo de retorno: $return_code\n";
                echo "</pre>";

                if ($return_code === 0) {
                    $ia_txt_filename = basename($output_txt);
                    $stmt2 = $conn->prepare("UPDATE sesiones SET ia = ? WHERE id = ? AND curso_id = ?");
                    $stmt2->bind_param("sii", $ia_txt_filename, $sesion_id, $curso_id);

                    if ($stmt2->execute()) {
                        echo "<script>alert('Video subido y análisis generado correctamente.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
                    } else {
                        echo "<script>alert('Error al guardar el archivo IA en la base de datos.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
                    }
                } else {
                    echo "<script>alert('Error al ejecutar IA.py. Código de retorno: $return_code'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
                }
            } else {
                echo "<script>alert('Error al guardar el video en la base de datos.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
            }
        } else {
            echo "<script>alert('Error al mover el archivo al servidor.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
        }
    } else {
        echo "<script>alert('No se seleccionó ningún archivo.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
    }
} else {
    echo "<script>alert('Solicitud no válida.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
}
?>
