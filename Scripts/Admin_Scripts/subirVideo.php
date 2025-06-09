<?php
include '../../Scripts/Config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_FILES['video'])) {
    $sesion_id = intval($_POST['sesion_id']);
    $curso_id = $_SESSION['cursoid'];
    $video = $_FILES['video'];

    if ($video['name']) {
        $target_dir = "../../uploads/videos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        $video_url = "{$curso_id}_{$sesion_id}_{$random_chars}_" . basename($video["name"]);
        $target_file = $target_dir . $video_url;

        if (move_uploaded_file($video["tmp_name"], $target_file)) {
            // Guardar datos en la tabla `videos`
            $sql = "INSERT INTO videos (sesion_id, nombre_archivo, ruta_video) VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE nombre_archivo = VALUES(nombre_archivo), ruta_video = VALUES(ruta_video)";
            $stmt = $conn->prepare($sql);
            $ruta = "uploads/videos/" . $video_url;
            $stmt->bind_param("iss", $sesion_id, $video_url, $ruta);
            $stmt->execute();

            // Ejecutar análisis IA (Python)
            $output_txt = "../../uploads/Documentos/{$video_url}.txt";
            $input_txt = $target_file;
            $python_path = "C:\\Users\\mange\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
            $command = "{$python_path} IA.py {$input_txt} {$output_txt} 2>&1";
            exec($command, $output, $return_code);

            echo "<pre>Salida de IA.py:\n" . implode("\n", $output) . "\nCódigo de retorno: $return_code\n</pre>";

            if ($return_code === 0) {
                $txt_filename = basename($output_txt);
                // Guardar análisis IA en la tabla `sesiones`
                $sql_update = "UPDATE sesiones SET ia_resultado = ?, video_estado = 'Disponible' WHERE id = ?";
                $stmt2 = $conn->prepare($sql_update);
                $stmt2->bind_param("si", $txt_filename, $sesion_id);
                $stmt2->execute();

                echo "<script>alert('Video subido y análisis generado correctamente.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
            } else {
                echo "<script>alert('Error al generar el análisis IA (código: $return_code)'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
            }
        } else {
            echo "<script>alert('Error al mover el archivo de video al servidor.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
        }
    } else {
        echo "<script>alert('No se recibió el archivo de video.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
    }
} else {
    echo "<script>alert('No se recibieron datos POST válidos.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
}
?>
