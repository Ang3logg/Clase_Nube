<?php include '../../Scripts/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Cursos</title>
    <link rel="stylesheet" href="../../Styles/StyleCurso.css">
    <style>
        .video-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden; /* Para asegurar que la sombra no se desborde */
            box-shadow: 0px 0px 35px #63FF59 ;
        }

        .video-container iframe,
        .video-container video {
            width: 100%;
            height: auto;
            display: block;
        }

        .materiales-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .material-card {
        width: 150px;
        text-align: center;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f0f0f0;
        overflow: hidden; /* Para cortar el texto que sobresalga */
        white-space: nowrap; /* Para evitar saltos de línea */
        text-overflow: ellipsis; /* Para mostrar puntos suspensivos (...) cuando el texto se corta */
        }
        .material-card img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }
    
        
    </style>

    
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h1>ClaseNube UCV</h1>
        </div>
        <ul class="nav-links" id="menuLinks">
        <li><a href="RegistroAlumno.php">Usuarios</a></li>
            <li><a href="RegistroCursos.php">Cursos</a></li>
            <li><a href="GestionRecursos.php">Gestion de Recursos</a></li>
            <li><a href="../../Scripts/logoutadmin.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    
    <!-- Incluir el PHP que genera las pestañas y el contenido -->
    <?php include '../../Scripts/Admin_Scripts/listarSessiones.php'; ?>
<ul class="tab-header">
    <?php generarPestanas($sesionesData); ?>
</ul>

<div class="tab-content">
    <?php generarContenido($sesionesData); ?>
</div>
    <script>
    function cambiarPestana(tabId) {
        // Ocultar todas las pestañas
        var tabs = document.querySelectorAll('.tab-pane');
        tabs.forEach(function(tab) {
            tab.style.display = 'none';
        });

        // Mostrar la pestaña seleccionada
        var selectedTab = document.getElementById(tabId);
        if (selectedTab) {
            selectedTab.style.display = 'block';
        }

        // Actualizar las clases activas en las pestañas del encabezado
        var tabHeaders = document.querySelectorAll('.tab-header li');
        tabHeaders.forEach(function(tabHeader) {
            tabHeader.classList.remove('active');
            if (tabHeader.getAttribute('data-tab') === tabId) {
                tabHeader.classList.add('active');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar la primera pestaña como activa al cargar la página
        var firstTab = document.querySelector('.tab-header li.active');
        if (firstTab) {
            var tabId = firstTab.getAttribute('data-tab');
            cambiarPestana(tabId);
        }
    });

    // Agregar eventos de clic a las pestañas para cambiar el contenido
    var tabs = document.querySelectorAll('.tab-header li');
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            var tabId = this.getAttribute('data-tab');
            cambiarPestana(tabId);
        });
    });
</script>

    <!-- Widget del chatbot -->
    <div class="chatbot">
        <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
        <df-messenger
            chat-title="Robot de Clase Nube"
            agent-id="7526d9c7-1a5a-45b8-aa9c-f8208e3b4ff7"
            language-code="es"
        ></df-messenger>
    </div>
</body>
</html>
