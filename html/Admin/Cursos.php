<?php include '../../Scripts/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/0aa76cd1df.js" crossorigin="anonymous"></script>
    <title>Cursos - ClaseNube UCV</title>
    <style>
        /* === INICIO DE CAMBIOS === */
        
        * {
            margin: 0;
            padding: 0;
            text-decoration: none;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        html, body {
            height: 100%;
            margin: 0;
        }

        /* 1. FONDO DE PÁGINA CAMBIADO A BLANCO */
        body {
            display: flex;
            flex-direction: column;
            background: #f4f7fc !important; /* Fondo blanco suave */
            min-height: 100vh;
            color: #333; /* Color de texto oscuro por defecto */
        }

        /* 2. NAVBAR AHORA TIENE EL DEGRADADO MORADO */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            backdrop-filter: none;
            border-bottom: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar .logo h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(45deg, #fff, #a8edea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3)); }
            to { filter: drop-shadow(0 0 20px rgba(168, 237, 234, 0.6)); }
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 1rem;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: white !important;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }

        .logo a {
            text-decoration: none;
            color: #ffffff;
        }

        /* 3. BLOQUES DE CONTENIDO AHORA TIENEN EL DEGRADADO MORADO */
        .contenedor, .CrudUS {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
            color: white !important;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 0 auto 2rem auto;
            max-width: 1200px;
            box-shadow: 0 15px 40px rgba(118, 75, 162, 0.2);
            border: none;
        }
        
        .contenedor { /* Contenedor del título */
            text-align: center;
            margin-top: 2rem;
        }
        
        .titulo {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
            background: linear-gradient(45deg, #fff, #a8edea, #fed6e3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .descripcion {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0; /* Ajuste */
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .contenedorUS { /* Wrapper de la tabla */
            flex: 1;
            padding: 0 1rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .CrudUS { /* Contenedor de la tabla */
            width: 100%;
            padding: 2rem;
        }

        /* Estilos de la tabla y botones (sin cambios, funcionan bien) */
        .table {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            border: none;
            color: white;
        }

        .table thead th {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            padding: 1rem;
        }

        .table tbody td {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn { border: none; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); }

        /* Responsive Design */
        @media (max-width: 968px) {
            .navbar { flex-direction: column; gap: 1rem; padding: 1rem; }
            .titulo { font-size: 2.5rem; }
            .contenedor, .CrudUS { padding: 1.5rem; margin: 1rem; }
        }

        /* === FIN DE CAMBIOS === */
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <a href="index_admin.php"><h1>ClaseNube UCV</h1></a>
        </div>
        <ul class="nav-links" id="menuLinks">
            <li><a href="RegistroAlumno.php">Usuarios</a></li>
            <li><a href="RegistroCursos.php">Cursos</a></li>
            <li><a href="../../Scripts/logoutadmin.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    
    <div class="contenedor">
        <h1 class="titulo">Cursos</h1>
        <p class="descripcion">¡Aquí podrás ver, modificar y eliminar cursos!</p>
    </div>

    <div class="contenedorUS">
        <div class="CrudUS">
            <?php include '../../Scripts/Admin_Scripts/listadocursos.php'; ?>
        </div>
    </div>

</body>
</html>