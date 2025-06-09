
<?php include '../../Scripts/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="../../Styles/stylegamer.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Bienvenido</title>
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <h1>ClaseNube UCV</h1>
        </div>
        <ul class="nav-links" id="menuLinks">
            <li><a href="../../Scripts/logoutprofe.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="contenedor">
        <h1 class="titulo">Bienvenido a ClaseNube UCV</h1>
        <p class="descripcion">¡Aquí podrás acceder a tus cursos y mucho más!</p>
    </div>

    <div class="cursos_f">
        <div class="container">
            <h1 class="MisCursos">Mis Cursos</h1>
            <div class="row">
                <?php include '../../Scripts/Script_Profesor/prueba.php'; ?>
            </div>
        </div>
    </div>

    <div class="chatbot">
        <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
        <df-messenger
            chat-title="Robot de Clase Nube"
            agent-id="7526d9c7-1a5a-45b8-aa9c-f8208e3b4ff7"
            language-code="es"
        ></df-messenger>
    </div>
    <footer>
        <p>&copy; 2024 ClaseNube UCV. Todos los derechos reservados.</p>
    </footer>

</body>
</html>

