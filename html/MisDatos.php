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
    <main class="main-content">
        <div class="content-wrapper">
            <header class="page-header">
                <h1>Mis Datos Personales</h1>
                <p class="subtitle">Administra tu información personal</p>
            </header>
            
            <div class="profile-section">
                <div class="DatosPerfil">
                    <?php include '../Scripts/getProfileData.php'; ?>
                </div>
                <button class="btn-volver" onclick="window.location.href='index.php'">
                    <span>← Volver al inicio</span>
                </button>
            </div>
        </div>
    </main>
</body>
</html>