<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_FILES['video'])) {
    $sesion_id = intval($_POST['sesion_id']);
    $curso_id = $_SESSION['cursoid'];
    $video = $_FILES['video'];

    $target_dir = "../../uploads/videos/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $videoFileName = 'grabacion_' . uniqid() . '.mp4';
    $target_file = $target_dir . $videoFileName;

    if (move_uploaded_file($video['tmp_name'], $target_file)) {
        // Extraer metadatos con FFmpeg
        $ffmpegOutput = shell_exec("ffmpeg -i \"$target_file\" 2>&1");
        preg_match('/Duration: (\d+:\d+:\d+\.\d+)/', $ffmpegOutput, $durationMatch);
        preg_match('/(\d+) fps/', $ffmpegOutput, $frameRateMatch);
        $duration = $durationMatch[1] ?? 'N/A';
        $frameRate = $frameRateMatch[1] ?? 'N/A';

        // Insertar o actualizar en la tabla videos
        $sql = "INSERT INTO videos (sesion_id, nombre_archivo, ruta_video, duracion, frame_rate) 
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE nombre_archivo = VALUES(nombre_archivo), ruta_video = VALUES(ruta_video),
                                        duracion = VALUES(duracion), frame_rate = VALUES(frame_rate)";
        $stmt = $conn->prepare($sql);
        $ruta = "uploads/videos/" . $videoFileName;
        $stmt->bind_param("issss", $sesion_id, $videoFileName, $ruta, $duration, $frameRate);
        $stmt->execute();

        // Ejecutar IA (análisis de video)
        $output_txt = "../../uploads/Documentos/{$videoFileName}.txt";
        $input_txt = $target_file;
        $python_path = "C:\\Users\\pc\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
        $command = "{$python_path} IA.py \"{$input_txt}\" \"{$output_txt}\" 2>&1";
        exec($command, $output, $return_code);

        if ($return_code === 0) {
            $ia_nombre = basename($output_txt);
            $sql_update = "UPDATE sesiones SET ia_resultado = ?, video_estado = 'Disponible' WHERE id = ?";
            $stmt2 = $conn->prepare($sql_update);
            $stmt2->bind_param("si", $ia_nombre, $sesion_id);
            $stmt2->execute();
            $stmt2->close();

            echo "<script>alert('Video subido y análisis generado correctamente.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
        } else {
            echo "<script>alert('Error al generar el análisis: Código $return_code'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Error al subir el archivo de video.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
    }
} else {
    echo "<script>alert('No se recibieron datos POST válidos.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
}
?>