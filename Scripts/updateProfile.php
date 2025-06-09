<?php
include 'Config.php';
include 'firebaseRDB.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$email = $_SESSION['user_id'];

try {
    $rdb = new firebaseRDB($databaseURL);
    
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $descripcion = $_POST['descripcion'];
    $profile_picture = $_FILES['profile_picture'];

    $profile_picture_url = "";
    if ($profile_picture['name']) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($profile_picture["name"]);
        if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
            $profile_picture_url = basename($profile_picture["name"]);
        } else {
            echo "Error al subir la imagen.";
            exit();
        }
    }

    $data = [
        "nombre" => $nombre,
        "apellido" => $apellido,
        "telefono" => $telefono,
        "descripcion" => $descripcion,
    ];

    if ($profile_picture_url) {
        $data["profile_picture"] = $profile_picture_url;
    }

    // Retrieve user data to find the correct firebase key using the email
    $retrieve = $rdb->retrieve("/usuarios", "correo", "EQUAL", $email);
    $userData = json_decode($retrieve, true);

    if (!empty($userData)) {
        $firebase_key = array_key_first($userData);

        // Update the user data
        $update = $rdb->update("/usuarios", $firebase_key, $data);
        if ($update) {
            header("Location: ../html/MisDatos.php");
        } else {
            echo "Error al actualizar el perfil.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
} catch (Exception $e) {
    echo "Error al actualizar los datos: " . $e->getMessage();
}
?>
