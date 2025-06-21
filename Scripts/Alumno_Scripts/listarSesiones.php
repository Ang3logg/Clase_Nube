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
    $sqlVideo = "SELECT ruta_video FROM videos WHERE sesion_id = ?";
    $stmtVideo = $conn->prepare($sqlVideo);
    $stmtVideo->bind_param("i", $sesion_id);
    $stmtVideo->execute();
    $resVideo = $stmtVideo->get_result();
    $video = $resVideo->fetch_assoc();

    $sesion['video'] = $video ? $video['ruta_video'] : '';

    // Obtener archivo IA
    $sesion['ia_resultado'] = $sesion['ia'] ?? '';

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

// INICIO HTML
ob_start();
?>

<ul class="tab-header">
    <?php foreach ($sesionesData as $index => $sesion): ?>
        <li class="<?= $index === 0 ? 'active' : '' ?>" data-tab="tab-<?= $index ?>" data-sesion-id="<?= $sesion['id'] ?>">
            <?= htmlspecialchars($sesion['nombre']) ?>
        </li>
    <?php endforeach; ?>
</ul>

<div class="tab-content">
    <?php foreach ($sesionesData as $index => $sesion): ?>
        <div id="tab-<?= $index ?>" class="tab-pane <?= $index === 0 ? 'active' : '' ?>">
            <h3><?= htmlspecialchars($sesion['nombre']) ?></h3>

            <h2>Video:</h2>
            <?php if (!empty($sesion['video'])):
                $video_path = "../" . htmlspecialchars($sesion['video']);
                if (file_exists($video_path)): ?>
                    <div class='video-container'>
                        <video src="<?= $video_path ?>" controls style="width: 100%; height: auto;"></video>
                    </div>
                <?php else: ?>
                    <p class="no-materials">El archivo de video no se encontró en el servidor.</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="no-materials">No hay video disponible para esta sesión.</p>
            <?php endif; ?>

            <h2>Análisis del Video por la IA:</h2>
            <?php if (!empty($sesion['ia_resultado'])):
                $iaNombre = htmlspecialchars($sesion['ia_resultado']);
                $iaRuta = "../uploads/Documentos/" . $iaNombre;
                $iconoIA = "../Icon/pdf.jpg"; ?>
                <div class="material-card">
                    <a href="<?= $iaRuta ?>" download>
                        <img src="<?= $iconoIA ?>" alt="PDF">
                        <div class="material-name"><?= $iaNombre ?></div>
                    </a>
                </div>
            <?php else: ?>
                <p class="no-materials">No se encontró el archivo de análisis IA.</p>
            <?php endif; ?>

            <h2>Materiales:</h2>
            <?php if (!empty($sesion['material'])): ?>
                <div class="materiales-container">
                    <?php foreach ($sesion['material'] as $mat):
                        $extension = pathinfo($mat, PATHINFO_EXTENSION);
                        $rutaMaterial = "../uploads/materiales/" . htmlspecialchars($mat);
                        $icono = match($extension) {
                            'pdf' => "../Icon/pdf.svg",
                            'ppt', 'pptx' => "../Icon/ppt.svg",
                            'jpg', 'jpeg', 'png' => "../Icon/png.svg",
                            'doc', 'docx' => "../Icon/word.svg",
                            'xls', 'xlsx' => "../Icon/excel.svg",
                            default => "../Icon/defecto.svg",
                        }; ?>
                        <div class="material-card">
                            <a href="<?= $rutaMaterial ?>" download>
                                <img src="<?= $icono ?>" alt="<?= $extension ?>">
                                <div class="material-name"><?= htmlspecialchars($mat) ?></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-materials">No hay materiales disponibles para esta sesión.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
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
