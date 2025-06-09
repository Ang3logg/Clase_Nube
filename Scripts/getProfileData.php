<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'Config.php';  // aquí tienes la conexión $conn a MySQL

$email = $_SESSION['user_id'];

if (!$email) {
    echo 'Usuario no identificado.';
    exit;
}

$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    echo '<div class="perfil-container">';
    echo '<img src="../uploads/' . ($user['profile_picture'] ?? 'default.png') . '" alt="Foto de Perfil" class="foto-perfil">';
    echo '<form action="../Scripts/updateProfile.php" method="POST" enctype="multipart/form-data">';
    echo '<label for="nombre">Nombre:</label>';
    echo '<input type="text" id="nombre" name="nombre" value="' . htmlspecialchars($user['nombre']) . '">';
    echo '<label for="apellido">Apellido:</label>';
    echo '<input type="text" id="apellido" name="apellido" value="' . htmlspecialchars($user['apellido']) . '">';
    echo '<label for="correo">Correo Electrónico:</label>';
    echo '<input type="email" id="correo" name="correo" value="' . htmlspecialchars($user['correo']) . '" readonly>';
    echo '<label for="telefono">Teléfono:</label>';
    echo '<input type="text" id="telefono" name="telefono" value="' . htmlspecialchars($user['telefono'] ?? '') . '">';
    echo '<label for="descripcion">Descripción:</label>';
    echo '<textarea id="descripcion" name="descripcion">' . htmlspecialchars($user['descripcion'] ?? '') . '</textarea>';
    echo '<label for="profile_picture">Cambiar Foto de Perfil:</label>';
    echo '<input type="file" id="profile_picture" name="profile_picture">';
    echo '<button type="submit">Actualizar Perfil</button>';
    echo '</form>';

    // Aquí si tienes función para agregar amigos (modifícala para MySQL)
    echo '<form action="../Scripts/addFriend.php" method="POST">';
    echo '<label for="friend_email">Agregar Amigo por Correo:</label>';
    echo '<input type="email" id="friend_email" name="friend_email" placeholder="Correo del Amigo">';
    echo '<button type="submit">Agregar Amigo</button>';
    echo '</form>';

    echo '</div>';
} else {
    echo '<p>No se encontraron datos del usuario.</p>';
}
?>
