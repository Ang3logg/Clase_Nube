<?php
include '../../Scripts/Config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_FILES['video'])) {
    $sesion_id = intval($_POST['sesion_id']);
    $curso_id = $_SESSION['cursoid'] ?? null;
    $video = $_FILES['video'];

    if (!$curso_id || empty($video['name'])) {
        echo "<script>alert('Datos faltantes.'); window.history.back();</script>";
        exit;
    }

    $target_dir = "../../uploads/videos/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
    $video_name = "{$curso_id}_{$sesion_id}_{$random_chars}_" . basename($video["name"]);
    $target_file = $target_dir . $video_name;
    $ruta_bd = "uploads/videos/" . $video_name;

    if (move_uploaded_file($video["tmp_name"], $target_file)) {
        $res = $conn->query("SELECT id FROM sesiones WHERE id = $sesion_id");
        if ($res->num_rows === 0) {
            echo "<script>alert('Sesión no existe.'); window.history.back();</script>";
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO videos (ruta_video, sesion_id) VALUES (?, ?)");
        $stmt->bind_param("si", $ruta_bd, $sesion_id);
        if ($stmt->execute()) {
            $input_video = $target_file;
            $output_txt = "../../uploads/Documentos/{$video_name}.txt";
            $python_path = "C:\\Users\\mange\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
            $script_path = "IA.py";

            $command = "\"$python_path\" \"$script_path\" \"$input_video\" \"$output_txt\" 2>&1";
            exec($command, $output, $return_code);

            if ($return_code === 0) {
                $ia_txt_filename = basename($output_txt);
                $stmt2 = $conn->prepare("UPDATE sesiones SET ia_resultado = ? WHERE id = ? AND curso_id = ?");
                $stmt2->bind_param("sii", $ia_txt_filename, $sesion_id, $curso_id);
                $stmt2->execute();

                echo "<script>alert('Video subido y análisis generado correctamente.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
            } else {
                echo "<script>alert('Error en IA.py (Código: $return_code)'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Error al guardar video: " . $stmt->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No se pudo mover el archivo.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Solicitud inválida.'); window.history.back();</script>";
}
?>
