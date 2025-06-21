<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../Scripts/Config.php';

if (!isset($_SESSION['user_id'])) {
    echo "No has iniciado sesión.";
    exit;
}

$usuarioId = $_SESSION['user_id'];

// Obtener cursos asignados
$stmt2 = $conn->prepare("
    SELECT cursos.id, cursos.nombre_curso, cursos.descripcion, cursos.imagen 
    FROM cursos
    INNER JOIN alumnos_cursos ON cursos.id = alumnos_cursos.curso_id
    WHERE alumnos_cursos.usuario_id = ?
");
$stmt2->bind_param("i", $usuarioId);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
    while ($curso = $result2->fetch_assoc()) {
        $imagen = $curso['imagen'] ? $curso['imagen'] : null;
        $cursoId = $curso['id'];

        if ($imagen) {
            echo '
            <div class="col-md-4">
                <div class="card mb-4" style="width: 18rem;">
                    <img class="card-img-top" src="' . htmlspecialchars($imagen) . '" alt="Imagen del curso">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($curso['nombre_curso']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($curso['descripcion']) . '</p>
                        <a href="../html/CursoOp.php?curso_id=' . $cursoId . '" class="btn btn-primary">Ir a Curso</a>
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
                        <h5 class="card-title">' . htmlspecialchars($curso['nombre_curso']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($curso['descripcion']) . '</p>
                        <a href="../html/CursoOp.php?curso_id=' . $cursoId . '" class="btn btn-primary">Ir a Curso</a>
                    </div>
                </div>
            </div>';
        }
    }
} else {
    echo "Aún no tienes cursos asignados.";
}
?>
