<?php
include 'Config.php';
include 'firebaseRDB.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$correo_usuario = $_SESSION['user_id'];
$correo_amigo = $_POST['friend_email'];

try {
    $rdb = new firebaseRDB($databaseURL);
    
    // Check if the friend exists
    $retrieve = $rdb->retrieve("/usuarios", "correo", "EQUAL", $correo_amigo);
    $friendData = json_decode($retrieve, true);

    if (!empty($friendData)) {
        $amigo_key = array_key_first($friendData);

        // Add friend
        $data = [
            "correo_usuario" => $correo_usuario,
            "correo_amigo" => $correo_amigo
        ];

        $insert = $rdb->insert("/amigos", $data);
        if ($insert) {
            echo "Amigo agregado exitosamente.";
            header("Location: ../html/MisDatos.php");
        } else {
            echo "Error al agregar el amigo.";
        }
    } else {
        echo "El correo del amigo no existe.";
    }
} catch (Exception $e) {
    echo "Error al agregar el amigo: " . $e->getMessage();
}
?>
