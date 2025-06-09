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
        <h1 class="titulo">CURSOS</h1>
        <p class="descripcion">¡Aquí podras registrar cursos, gestionar sus cursos, eliminar cursos,etc!</p>
    </div>
    <a class="btn btn-primary" href="Cursos.php" role="button">Ver Cursos</a>

    

        <div class="contenedorUS">

        <div class="regiscur">
       
        <form action="../../Scripts/Admin_Scripts/CrearCurso.php" method="POST">
         <div class="form-group">
        <label for="nombrecruso">Nombre del curso</label>
        <input type="text" class="form-control" id="nombrecruso" name="nombrecruso" aria-describedby="emailHelp" placeholder="Nombre del Curso" required>
        <small id="emailHelp" class="form-text text-muted">el id es automático</small>
            </div>
        <div class="form-group">
        <label for="descripcion">Descripción</label>
        <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción">
        </div>
        <div class="form-group">
        <label for="docente">Docente encargado</label>
        <input type="text" class="form-control" id="docente" name="docente" placeholder="Pegar aquí // Docente encargado" required>
        </div>
        <input type="submit" class="btn btn-primary" value="Registrar"></input>
        </form>
        </div>
        <div class="CrudUS">
        <label for="nombrecruso">LISTADO DE PROFESORES</label>
            <?php include '../../Scripts/Admin_Scripts/ListadoProfesores.php'; ?>
        </div>

    </div>



</body>
</html>