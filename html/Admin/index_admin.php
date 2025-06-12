<?php include '../../Scripts/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="../../Styles/styles2.css">
    <link rel="stylesheet" href="../../Styles/Admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Bienvenido</title>
    <link rel="icon" href="../../Images/favicon.ico" type="image/x-icon">
    
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">
            <h1>ClaseNube UCV</h1>
        </div>
        <ul class="nav-links" id="menuLinks">
            <li><a href="RegistroAlumno.php">Usuarios</a></li>
            <li><a href="RegistroCursos.php">Cursos</a></li>
            <li><a href="../../Scripts/logoutadmin.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="contenedor">
        <h1 class="titulo">Bienvenido SuperUsuario</h1>
        <p class="descripcion">¡Aquí podras registrar cursos, administrar los usuarios alumnos, etc!</p>
    </div>
    <div class="AC_RAPIDO">
        <a href="RegistroAlumno.php">USUARIOS</a>
        <a href="RegistroCursos.php">CURSOS</a>
        <a href="../signup.html">CREACION DE USUARIO</a>
    </div>

    <div class="cursos_f">
        <div class="container">
            <h1 class="titulo">Gestionar Cursos</h1>
            <div class="row">
                <?php include '../../Scripts/Admin_Scripts/prueba.php'; ?>
            </div>
        </div>
    </div>



</main>

<footer>
    <p>&copy; 2024 ClaseNube UCV. Todos los derechos reservados.</p>
</footer>

</body>
</html>
