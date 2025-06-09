<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../Scripts/Config.php';

if (isset($_GET['curso_id'])) {
    $_SESSION['cursoid'] = $_GET['curso_id'];
} else {
    die("Error: No se proporcionó el ID del curso.");
}

$curso_id = intval($_SESSION['cursoid']);

// Obtener datos del curso
$sqlCurso = "SELECT nombre_curso, descripcion FROM cursos WHERE id = ?";
$stmtCurso = $conn->prepare($sqlCurso);
$stmtCurso->bind_param("i", $curso_id);
$stmtCurso->execute();
$resultCurso = $stmtCurso->get_result();

$nombreCurso = 'Curso no encontrado';
$descripcionCurso = 'Descripción no encontrada';

if ($row = $resultCurso->fetch_assoc()) {
    $nombreCurso = htmlspecialchars($row['nombre_curso']);
    $descripcionCurso = htmlspecialchars($row['descripcion']);
}

// Obtener sesiones del curso
$sqlSesiones = "SELECT * FROM sesiones WHERE curso_id = ?";
$stmtSesiones = $conn->prepare($sqlSesiones);
$stmtSesiones->bind_param("i", $curso_id);
$stmtSesiones->execute();
$resultSesiones = $stmtSesiones->get_result();

$sesionesData = [];
while ($sesion = $resultSesiones->fetch_assoc()) {
    $sesion_id = $sesion['id'];

    // Obtener video
    $sqlVideo = "SELECT nombre_archivo FROM videos WHERE sesion_id = ?";
    $stmtVideo = $conn->prepare($sqlVideo);
    $stmtVideo->bind_param("i", $sesion_id);
    $stmtVideo->execute();
    $resVideo = $stmtVideo->get_result();
    $video = $resVideo->fetch_assoc();

    $sesion['video'] = $video ? $video['nombre_archivo'] : '';
    
    // Obtener materiales
    $sqlMat = "SELECT nombre_archivo FROM materiales WHERE sesion_id = ?";
    $stmtMat = $conn->prepare($sqlMat);
    $stmtMat->bind_param("i", $sesion_id);
    $stmtMat->execute();
    $resMat = $stmtMat->get_result();

    $materiales = [];
    while ($m = $resMat->fetch_assoc()) {
        $materiales[] = $m['nombre_archivo'];
    }

    $sesion['material'] = $materiales;

    $sesionesData[] = $sesion;
}

// Funciones visuales HTML
function generarPestanas($sesiones) {
    foreach ($sesiones as $index => $sesion) {
        $activeClass = $index == 0 ? 'active' : '';
        $nombreSesion = htmlspecialchars($sesion['nombre']);
        $idSesion = $sesion['id'];
        echo "<li class='$activeClass' data-tab='tab-$index' data-sesion-id='$idSesion'>$nombreSesion</li>";
    }
}

function generarContenido($sesiones) {
    foreach ($sesiones as $index => $sesion) {
        $activeClass = $index == 0 ? 'active' : '';
        echo "<div id='tab-$index' class='tab-pane $activeClass'>";
        echo "<h3>" . htmlspecialchars($sesion['nombre']) . "</h3>";

        echo "<h2>Video:</h2>";
        if (!empty($sesion['video']) && $sesion['video_estado'] === 'Disponible') {
            $video_path = "../uploads/videos/" . htmlspecialchars($sesion['video']);
            echo "<div class='video-container'><video src='$video_path' controls style='width: 100%; height: auto;'></video></div>";
        }

        echo "<h2>Análisis del Video por la IA:</h2>";
        if (!empty($sesion['ia_resultado']) && $sesion['video_estado'] === 'Disponible') {
            $nombreMaterial = $sesion['ia_resultado'];
            $urlMaterial = "../uploads/Documentos/" . htmlspecialchars($nombreMaterial);
            $icono = "../Icon/pdf.jpg";
            echo "<div class='material-card'>";
            echo "<a href='$urlMaterial' download>";
            echo "<img src='$icono' alt='PDF'>";
            echo "<div class='material-name'>$nombreMaterial</div>";
            echo "</a></div>";
        } else {
            echo "<p class='no-materials'>No se encontró el archivo de análisis IA.</p>";
        }

        echo "<h2>Materiales:</h2>";
        if (!empty($sesion['material'])) {
            echo "<div class='materiales-container'>";
            foreach ($sesion['material'] as $mat) {
                $extension = pathinfo($mat, PATHINFO_EXTENSION);
                $urlMaterial = "../uploads/materiales/" . htmlspecialchars($mat);
                switch ($extension) {
                    case 'pdf': $icono = "../Icon/pdf.jpg"; break;
                    case 'ppt':
                    case 'pptx': $icono = "../Icon/ppt.jpg"; break;
                    case 'jpg':
                    case 'jpeg':
                    case 'png': $icono = "../Icon/png.jpg"; break;
                    case 'doc':
                    case 'docx': $icono = "../Icon/word.jpg"; break;
                    case 'xls':
                    case 'xlsx': $icono = "../Icon/excel.jpg"; break;
                    default: $icono = "../Icon/defecto.jpg";
                }

                echo "<div class='material-card'>";
                echo "<a href='$urlMaterial' download>";
                echo "<img src='$icono' alt='$extension'>";
                echo "<div class='material-name'>$mat</div>";
                echo "</a></div>";
            }
            echo "</div>";
        } else {
            echo "<p class='no-materials'>No hay materiales disponibles para esta sesión.</p>";
        }

        echo "</div>";
    }
}

ob_start(); // Para capturar HTML generado dinámicamente
?>

<ul class="tab-header">
    <?php generarPestanas($sesionesData); ?>
</ul>
<div class="tab-content">
    <?php generarContenido($sesionesData); ?>
</div>

<?php
$contenido_tabs = ob_get_clean();

echo "
    <h1>Detalles del Curso</h1>
    <div class='curso'>
        <h2 id='nombreCurso'>$nombreCurso</h2>
        <p id='descripcionCurso'>$descripcionCurso</p>
        <div class='unidades'>
            <h3>Unidades</h3>
            <div class='tabs'>
                $contenido_tabs
            </div>
        </div>
    </div>
";
?>
