<?php
// Iniciar la sesión para acceder a la variable de sesión
session_start();

// Obtener el token ingresado por el usuario desde el formulario
$token_ingresado = $_POST['token'];


// Obtener el token generado previamente almacenado en la variable de sesión
$token_esperado = $_SESSION['token_generado'];

if ($token_ingresado === $token_esperado) {
    // Establecer la variable de sesión para indicar que el token ha sido validado
    $_SESSION['token_validado'] = true;
    $_SESSION['token_validado'] = $token_ingresado;
    // Token válido, activar el div "registroForm" y ocultar "correoForm" y "codigoForm"
    echo "<script>
            alert('Verificado ✓');
            setTimeout(function() {
                window.location.href = '../html/OlvidoContrasena2.html';
            }, 1000); // Redirigir después de 1 segundos (3000 milisegundos)
          </script>";
} else {
    echo "El token es inválido. Por favor, inténtalo de nuevo.";
}
?>