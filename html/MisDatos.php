<?php include '../Scripts/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/styles2.css">
    <link rel="stylesheet" href="../Styles/MisDatos.css">
    <title>Mis Datos - ClaseNube UCV</title>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php"><h1 class="titulo">ClaseNube UCV</h1></a>
        </div>
        <ul class="nav-links" id="menuLinks">
            <li><a href="index.php">Mis Cursos</a></li>
            <li><a href="../Scripts/logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <main>
        <header>
            <h1>Bienvenido!</h1>
            <hr>
            <div class="DatosPerfil">
                <?php include '../Scripts/getProfileData.php'; ?>
            </div>
        </header>
    </main>
    <footer>
        <a href="index.php">Volver al inicio</a>
    </footer>
</body>
</html>
