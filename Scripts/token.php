<?php
session_start();

if (!isset($_POST['token']) || !isset($_SESSION['token_generado'])) {
    echo "Datos inválidos.";
    exit;
}

$token_ingresado = $_POST['token'];
$token_esperado = $_SESSION['token_generado'];

if ($token_ingresado === $token_esperado) {
    $_SESSION['token_validado'] = true;

    echo "<script>
            alert('Verificado ✓');
            setTimeout(function() {
                window.location.href = '../html/signup2.html';
            }, 3000);
          </script>";
} else {
    echo "<script>alert('El token es inválido.'); window.location.href='../html/signup.html';</script>";
}
?>
