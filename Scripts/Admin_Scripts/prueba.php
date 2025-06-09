<?php
// Inicia la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
include '../../Scripts/Config.php';

// Consulta para obtener todos los cursos
$sql = "SELECT * FROM cursos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($curso = $result->fetch_assoc()) {
        $imagen = isset($curso['imagen']) ? $curso['imagen'] : null;
        $cursoId = $curso['id']; // Asegúrate que tu tabla 'cursos' tenga este campo
        $_SESSION['cursoid'] = $cursoId;

        if ($imagen) {
            echo '
            <div class="col-md-4">
                <div class="card mb-4" style="width: 18rem;">
                    <img class="card-img-top" src="' . $imagen . '" alt="Imagen del curso">
                    <div class="card-body">
                        <h5 class="card-title">' . $curso['nombre_curso'] . '</h5>
                        <p class="card-text">' . $curso['descripcion'] . '</p>
                        <a href="GestionRecursos.php?curso_id=' . $cursoId . '" class="btn btn-primary">Ir a Curso</a>
                    </div>
                </div>
            </div>';
        } else {
            $randomColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            echo '
            <div class="col-md-4">
                <div class="card mb-4" style="width: 18rem;">
                    <div class="card-img-top" style="background-color:' . $randomColor . '; height: 180px;"></div>
                    <div class="card-body">
                        <h5 class="card-title">' . $curso['nombre_curso'] . '</h5>
                        <p class="card-text">' . $curso['descripcion'] . '</p>
                        <a href="GestionRecursos.php?curso_id=' . $cursoId . '" class="btn btn-primary">Ir a Curso</a>
                    </div>
                </div>
            </div>';
        }
    }
} else {
    echo "<p>No hay cursos disponibles en la base de datos.</p>";
}
?>
