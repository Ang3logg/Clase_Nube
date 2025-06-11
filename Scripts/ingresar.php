<?php
include 'Config.php';

$email = $_POST['email'];
$pass = $_POST['contra'];

try {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Comparación directa para contraseña en texto plano
        if ($pass === $user['contraseña']) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['correo'] = $user['correo'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['authenticated'] = true;

            if ($user['tipo_usuario'] === 'admin') {
                header("Location: ../html/Admin/index_admin.php");
            } elseif ($user['tipo_usuario'] === 'profesor') {
                header("Location: ../html/Profesor/prueba.php");
            } else {
                header("Location: ../html/index.php");
            }
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href = '../html/login.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href = '../html/login.html';</script>";
        exit();
    }
} catch (Exception $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>
