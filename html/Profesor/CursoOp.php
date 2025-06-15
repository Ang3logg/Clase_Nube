<?php include '../../Scripts/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Curso</title>
    <link rel="stylesheet" href="../../Styles/StyleCurso.css">
    <script>
        function confirmarEliminacion(form) {
            if (confirm("¿Estás seguro de que deseas eliminar esta sesión?")) {
                return true; // Permitir el envío del formulario
            } else {
                return false; // Cancelar el envío del formulario
            }
        }
    </script>
    <style>
        .video-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;   
            overflow: hidden; /* Para asegurar que la sombra no se desborde */
            background-color: #007bff; /* Fondo azul para el contenedor del video */
            border-radius: 8px; /* Bordes redondeados opcionales */
            padding: 8px; /* Espaciado interno opcional */
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
        
        /* Estilo para el contenedor del input file */
.file-upload-container {
    position: relative;
    margin-top: 15px;
}


/* Estilo para el botón personalizado de selección de archivo */
.custom-file-upload {
    background-color: #dc3545;
    color: #fff;
    border: none;
    padding: 15px 30px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: inline-block;
}

.custom-file-upload:hover {
    background-color: #c82333;
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
}

/* Estilo para el botón de enviar */
.btn-transmitir {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 15px 30px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    animation: pulse 2s infinite alternate;
}

.btn-transmitir:hover {
    background-color: #0056b3;
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
    animation: none; /* Detiene la animación al pasar el cursor */
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.05);
    }
}

   
    </style>

    
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h1>ClaseNube UCV</h1>
        </div>
        <ul class="nav-links" id="menuLinks">
            <li><a href="../../html/Profesor/prueba.php">Mis Cursos</a></li>
            <li><a href="../../Scripts/logoutprofe.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    
    <!-- Incluir el PHP que genera las pestañas y el contenido -->
    <?php include '../../Scripts/Script_Profesor/listarSesiones.php'; ?>

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
