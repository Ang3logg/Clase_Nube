<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se recibieron los datos necesarios del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_FILES['video'])) {
    $sesion_id = $_POST['sesion_id'];
    $curso_id = $_SESSION['cursoid']; 
    $video = $_FILES['video'];

    // Verificar si se seleccionó un archivo de video
    if ($video['name']) {
        $target_dir = "../../uploads/videos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Generar nombre aleatorio para el archivo de video
        $random_chars = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        $video_url = "{$curso_id}_{$sesion_id}_{$random_chars}_" . basename($video["name"]);
        $target_file = $target_dir . $video_url;

        // Mover el archivo subido a la carpeta de destino
        if (move_uploaded_file($video["tmp_name"], $target_file)) {
            // Guardar el enlace del video en Firebase
            $firebase = new firebaseRDB($databaseURL);
            $data = [
                "video" => $video_url
            ];

            try {
                $update_path = "sesiones/$curso_id";
                $update = $firebase->update($update_path, $sesion_id, $data);

                if ($update) {
                    // Ejecutar el script de Python para procesar el video
                    $output_txt = "../../uploads/Documentos/{$video_url}.txt";
                    $input_txt = "../../uploads/videos/{$video_url}";
                    $python_path = "C:\\Users\\mange\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
                    $command = "{$python_path} IA.py {$input_txt} {$output_txt} 2>&1";
                    exec($command, $output, $return_code);

                    // Mostrar salida y manejar errores según el código de retorno
                    echo "<pre>";
                    echo "Salida de IA.py:\n";
                    echo implode("\n", $output) . "\n";
                    echo "Código de retorno: $return_code\n";
                    echo "</pre>";

                    if ($return_code === 0) {
                        // Actualizar Firebase con el nombre del archivo de texto generado
                        $txt_filename = basename($output_txt);
                        $data_txt = [
                            "ia" => $txt_filename
                        ];
                        $update_txt = $firebase->update($update_path, $sesion_id, $data_txt);

                        if ($update_txt) {
                            echo "<script>alert('Video subido y análisis generado correctamente.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
                        } else {
                            echo "<script>alert('Error al guardar el nombre del archivo de texto en Firebase.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
                        }
                    } else {
                        echo "<script>alert('Error al generar el análisis: Código de retorno $return_code'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
                    }
                } else {
                    echo "<script>alert('Error al actualizar el enlace del video en la base de datos.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('Error al actualizar el enlace del video en la base de datos: {$e->getMessage()}'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
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
