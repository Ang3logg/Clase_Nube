<?php include '../../Scripts/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="../../Styles/styles2.css">
    <link rel="stylesheet" href="../../Styles/Admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/0aa76cd1df.js" crossorigin="anonymous"></script>
    <title>Bienvenido</title>
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <a href="index_admin.php" style="text-Decoration:none;"><h1>ClaseNube UCV</h1></a>
        </div>
        <ul class="nav-links" id="menuLinks">
            
            <li> <a href="RegistroAlumno.php">Usuarios</a></li>
            <li><a href="RegistroCursos.php">Cursos</a></li>
            <li><a href="../../Scripts/logoutadmin.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <div class="contenedor">
        <h1 class="titulo">Cursos</h1>
        <p class="descripcion">¡Aquí podras ver, modificar y eliminar crusos!</p>
    </div>
    </div>

    <div class="contenedorUS">
        <div>
            
        </div>
        <div class="CrudUS">
            <?php include '../../Scripts/Admin_Scripts/listadocursos.php'; ?>
        </div>



 
    </div>



</body>
</html>