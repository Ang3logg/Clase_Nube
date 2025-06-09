<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';  // Aquí $conn es tu conexión MySQL

$userId = $_SESSION['user_id'];

if (!$userId) {
    echo "Usuario no identificado.";
    exit;
}

// Consulta correcta usando la columna profesor_id
$sql = "SELECT * FROM cursos WHERE profesor_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$stmt->bind_param("i", $userId);  // Es INT según tu BD
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($curso = $result->fetch_assoc()) {
        $imagen = isset($curso['imagen']) ? $curso['imagen'] : null;
        $cursoId = $curso['id'];
        $_SESSION['cursoid'] = $cursoId;

        if ($imagen) {
            echo '
            <div class="col-md-4">
                <div class="card mb-4" style="width: 18rem;">
                    <img class="card-img-top" src="' . htmlspecialchars($imagen) . '" alt="Imagen del curso">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($curso['nombre_curso']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($curso['descripcion']) . '</p>
                        <a href="../../html/Profesor/CursoOp.php?curso_id=' . urlencode($cursoId) . '" class="btn btn-primary">Ir a Curso</a>
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
                        <a href="../../html/Profesor/CursoOp.php?curso_id=' . urlencode($cursoId) . '" class="btn btn-primary">Ir a Curso</a>
                    </div>
                </div>
            </div>';
        }
    }
} else {
    echo "No hay cursos asignados para este profesor.";
}
?>
