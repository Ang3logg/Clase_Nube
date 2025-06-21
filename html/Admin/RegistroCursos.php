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
    <title>Gestión de Cursos - ClaseNube UCV</title>
    
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
            background: #f4f7fc !important; /* Fondo blanco suave */
            min-height: 100vh;
            color: #333; /* Color de texto oscuro por defecto */
            overflow-x: hidden;
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

        /* 3. BLOQUES DE CONTENIDO CON DEGRADADO MORADO */
        .contenedor, .regiscur, .CrudUS {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
            color: white !important;
            border-radius: 20px;
            padding: 2.5rem;
            border: none;
            box-shadow: 0 15px 40px rgba(118, 75, 162, 0.2);
        }

        .contenedor { /* Contenedor del Título */
            text-align: center;
            margin: 2rem auto;
            max-width: 1400px;
        }

        .titulo {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
            background: linear-gradient(45deg, #fff, #a8edea, #fed6e3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInUp 1s ease;
        }

        .descripcion {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.2s backwards;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-modern {
            background: linear-gradient(45deg, #5c67e3, #6b3fb9);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            text-decoration: none;
        }

        .btn-modern:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            text-decoration: none;
            color: white;
        }

        .contenedorUS {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            padding: 0 2rem 2rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .regiscur h3, .CrudUS label {
            color: white;
            font-size: 1.5rem;
            text-align: center;
            background: linear-gradient(45deg, #fff, #a8edea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .form-control::placeholder { color: rgba(255, 255, 255, 0.6); }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: none;
            outline: none;
            color: white;
        }

        .form-text { color: rgba(255, 255, 255, 0.7); }

        .btn-primary {
            background: linear-gradient(45deg, #5c67e3, #6b3fb9);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(45deg, #515cde, #6136b4);
            border: none;
        }

        .table { background: transparent; color: white; }
        .table th { border: none; background: rgba(255, 255, 255, 0.1); }
        .table td { border-top: 1px solid rgba(255, 255, 255, 0.1); }
        .table tbody tr:hover { background: rgba(255, 255, 255, 0.05); }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .contenedorUS { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .navbar { flex-direction: column; gap: 1rem; padding: 1rem; }
            .titulo { font-size: 2.5rem; }
            .contenedorUS { padding: 1rem; gap: 1.5rem; }
            .contenedor, .regiscur, .CrudUS { padding: 1.5rem; margin: 1rem; }
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
            <li><a href="RegistroAlumno.php"><i class="fas fa-users"></i> Usuarios</a></li>
            <li><a href="RegistroCursos.php"><i class="fas fa-book"></i> Cursos</a></li>
            <li><a href="../../Scripts/logoutadmin.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="contenedor">
        <h1 class="titulo">GESTIÓN DE CURSOS</h1>
        <p class="descripcion">¡Aquí podrás registrar cursos, gestionar tus cursos, eliminar cursos y mucho más!</p>
        <a class="btn-modern" href="Cursos.php" role="button">
            <i class="fas fa-eye"></i> Ver Todos los Cursos
        </a>
    </div>

    <div class="contenedorUS">
        <div class="regiscur">
            <h3><i class="fas fa-plus-circle"></i> Registrar Nuevo Curso</h3>
            
            <form action="../../Scripts/Admin_Scripts/CrearCurso.php" method="POST">
                <div class="form-group">
                    <label for="nombrecruso"><i class="fas fa-bookmark"></i> Nombre del curso</label>
                    <input type="text" class="form-control" id="nombrecruso" name="nombrecruso" 
                           placeholder="Ingrese el nombre del curso" required>
                    <small class="form-text">El ID del curso se genera automáticamente</small>
                </div>
                
                <div class="form-group">
                    <label for="descripcion"><i class="fas fa-info-circle"></i> Descripción</label>
                    <input type="text" class="form-control" id="descripcion" name="descripcion" 
                           placeholder="Descripción del curso">
                </div>
                
                <div class="form-group">
                    <label for="docente"><i class="fas fa-chalkboard-teacher"></i> Docente encargado</label>
                    <input type="text" class="form-control" id="docente" name="docente" 
                           placeholder="Seleccione un docente de la lista" required>
                    <small class="form-text">Consulte la lista de profesores disponibles</small>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar Curso
                </button>
            </form>
        </div>

        <div class="CrudUS">
            <label for="nombrecruso"><i class="fas fa-list"></i> LISTADO DE PROFESORES</label>
            <div class="table-responsive">
                <?php include '../../Scripts/Admin_Scripts/ListadoProfesores.php'; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
</body>
</html>