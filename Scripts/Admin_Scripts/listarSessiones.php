<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';

if (!isset($conn)) {
    die("Error: No se pudo conectar a la base de datos.");
}

if (isset($_GET['curso_id'])) {
    $_SESSION['cursoid'] = $_GET['curso_id'];
} elseif (!isset($_SESSION['cursoid'])) {
    die("Error: No se proporcionó el ID del curso.");
}

$curso_id = $_SESSION['cursoid'];

// Obtener curso
$sqlCurso = "SELECT nombre_curso, descripcion FROM cursos WHERE id = ?";
$stmtCurso = $conn->prepare($sqlCurso);
$stmtCurso->bind_param("i", $curso_id);
$stmtCurso->execute();
$resultCurso = $stmtCurso->get_result();
$curso = $resultCurso->fetch_assoc();
$nombreCurso = htmlspecialchars($curso['nombre_curso'] ?? 'Curso no encontrado');
$descripcionCurso = htmlspecialchars($curso['descripcion'] ?? 'Descripción no encontrada');

// Obtener sesiones
$sqlSesiones = "SELECT * FROM sesiones WHERE curso_id = ?";
$stmtSesiones = $conn->prepare($sqlSesiones);
$stmtSesiones->bind_param("i", $curso_id);
$stmtSesiones->execute();
$resultSesiones = $stmtSesiones->get_result();

$sesionesData = [];
while ($sesion = $resultSesiones->fetch_assoc()) {
    $sesion_id = $sesion['id'];

    // Obtener ruta del video
    $sqlVideo = "SELECT ruta_video FROM videos WHERE sesion_id = ?";
    $stmtVideo = $conn->prepare($sqlVideo);
    $stmtVideo->bind_param("i", $sesion_id);
    $stmtVideo->execute();
    $resVideo = $stmtVideo->get_result();
    $video = $resVideo->fetch_assoc();
    $sesion['video'] = $video['ruta_video'] ?? '';

    // Obtener materiales
    $sqlMat = "SELECT nombre_archivo FROM materiales WHERE sesion_id = ?";
    $stmtMat = $conn->prepare($sqlMat);
    $stmtMat->bind_param("i", $sesion_id);
    $stmtMat->execute();
    $resMat = $stmtMat->get_result();
    $materiales = [];
    while ($mat = $resMat->fetch_assoc()) {
        $materiales[] = $mat['nombre_archivo'];
    }
    $sesion['materiales'] = $materiales;

    $sesionesData[] = $sesion;
}

// Función para generar pestañas
function generarPestanas($sesiones) {
    foreach ($sesiones as $i => $sesion) {
        $active = $i === 0 ? 'active' : '';
        echo "<li class='$active' data-tab='tab-$i'>" . htmlspecialchars($sesion['nombre']) . "</li>";
    }
}

// Función para generar contenido de cada sesión
function generarContenido($sesiones) {
    foreach ($sesiones as $i => $sesion) {
        $active = $i === 0 ? 'active' : '';
        echo "<div id='tab-$i' class='tab-pane $active'>";
        echo "<h3>" . htmlspecialchars($sesion['nombre']) . "</h3>";

        // Video
        echo "<h4>Video:</h4>";
        if (!empty($sesion['video'])) {
            $videoPath = "../../" . $sesion['video'];
            if (file_exists($videoPath)) {
                echo "<div class='video-container'><video src='$videoPath' controls width='100%'></video></div>";
            } else {
                echo "<p>El video no se encuentra en el servidor.</p>";
            }
        } else {
            echo "<p>No hay video disponible.</p>";
        }

        // Análisis IA
        echo "<h4>Análisis IA:</h4>";
        $iaNombre = $sesion['ia_resultado'] ?? '';
        if ($iaNombre && file_exists("../../uploads/Documentos/$iaNombre")) {
            echo "<div class='material-card'><a href='../../uploads/Documentos/$iaNombre' download>$iaNombre</a></div>";
        } else {
            echo "<p>No se encontró el análisis IA.</p>";
        }

        // Materiales
        echo "<h4>Materiales:</h4>";
        if (!empty($sesion['materiales'])) {
            echo "<div class='materiales-container'>";
            foreach ($sesion['materiales'] as $material) {
                $materialUrl = "../../uploads/materiales/" . htmlspecialchars($material);
                echo "<div class='material-card'><a href='$materialUrl' download>$material</a></div>";
            }
            echo "</div>";
        } else {
            echo "<p>No hay materiales disponibles.</p>";
        }

        echo "</div>";
    }
}
?>
