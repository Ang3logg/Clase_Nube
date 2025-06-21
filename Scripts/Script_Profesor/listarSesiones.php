<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';

// Validar y obtener el ID del curso
if (isset($_GET['curso_id'])) {
    $_SESSION['cursoid'] = $_GET['curso_id'];
} else {
    die("Error: No se proporcionó el ID del curso.");
}

$curso_id = $_SESSION['cursoid'];

// Obtener datos del curso
$queryCurso = "SELECT nombre_curso, descripcion FROM cursos WHERE id = ?";
$stmtCurso = $conn->prepare($queryCurso);
$stmtCurso->bind_param("i", $curso_id);
$stmtCurso->execute();
$resultCurso = $stmtCurso->get_result();

$nombreCurso = 'Curso no encontrado';
$descripcionCurso = 'Descripción no encontrada';

if ($resultCurso->num_rows > 0) {
    $curso = $resultCurso->fetch_assoc();
    $nombreCurso = htmlspecialchars($curso['nombre_curso']);
    $descripcionCurso = htmlspecialchars($curso['descripcion']);
}

// Obtener sesiones
$querySesiones = "SELECT * FROM sesiones WHERE curso_id = ?";
$stmtSesiones = $conn->prepare($querySesiones);
$stmtSesiones->bind_param("i", $curso_id);
$stmtSesiones->execute();
$resultSesiones = $stmtSesiones->get_result();

$sesionesData = [];
while ($row = $resultSesiones->fetch_assoc()) {
    $sesionesData[] = $row;
}

function obtenerMaterialesSesion($sesion_id) {
    global $conn;
    $materiales = [];
    $query = "SELECT id, nombre_archivo, tipo_archivo FROM materiales WHERE sesion_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sesion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $materiales[] = $row;
    }
    return $materiales;
}

function obtenerVideoSesion($sesion_id) {
    global $conn;
    $query = "SELECT ruta_video FROM videos WHERE sesion_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sesion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['ruta_video'] ?? null;
}

function generarPestanas($sesiones) {
    foreach ($sesiones as $index => $sesion) {
        $activeClass = $index == 0 ? 'active' : '';
        $nombreSesion = htmlspecialchars($sesion['nombre']);
        $idSesion = htmlspecialchars($sesion['id']);
        echo "<li class='$activeClass' data-tab='tab-$index' data-sesion-id='$idSesion'>$nombreSesion
            <form method='POST' action='../../Scripts/Script_Profesor/eliminarSesion.php' style='display:inline;' onsubmit='return confirmarEliminacion(this);'>
                <input type='hidden' name='sesion_id' value='$idSesion'>
                <button type='submit'>&times;</button>
            </form>
        </li>";
    }
}

function generarContenido($sesiones) {
    global $nombreCurso, $descripcionCurso;
    foreach ($sesiones as $index => $sesion) {
        $activeClass = $index == 0 ? 'active' : '';
        $idSesion = htmlspecialchars($sesion['id']);
        $nombreSesion = htmlspecialchars($sesion['nombre']);
        $video = obtenerVideoSesion($idSesion);
        $ia = $sesion['ia_resultado'];

        echo "<div id='tab-$index' class='tab-pane $activeClass'>";
        echo "<h3>$nombreSesion</h3>";

        echo "<h2>Video:</h2>";
        if ($video) {
            if (strpos($video, 'youtube.com') !== false) {
                echo "<div class='video-container'><iframe width='100%' src='$video' frameborder='0' allowfullscreen></iframe></div>";
            } else {
                $videoSrc = '../../uploads/videos/' . basename($video);
                echo "<div class='video-container'><video src='$videoSrc' controls style='width: 100%;'></video></div>";
            }
        } else {
            echo "<p>No hay video disponible.</p>";
        }

        echo "<h2>Análisis del Video por la IA:</h2>";
        if ($ia) {
            $extension = pathinfo($ia, PATHINFO_EXTENSION);
            $icono = "../../Icon/" . ($extension === 'pdf' ? 'pdf.jpg' : 'defecto.svg');
            echo "<div class='material-card'>
                    <a href='../../uploads/Documentos/$ia' download>
                        <img src='$icono' alt='$extension'>
                        <div class='material-name'>$ia</div>
                    </a>
                  </div>";
        } else {
            echo "<p class='no-materials'>No se encontró análisis IA.</p>";
        }

        echo "<h2>Materiales:</h2>";
        $materiales = obtenerMaterialesSesion($idSesion);
        if ($materiales) {
            echo "<div class='materiales-container'>";
            foreach ($materiales as $mat) {
                $material_id = htmlspecialchars($mat['id']);
                $nombre = htmlspecialchars($mat['nombre_archivo']);
                $extension = pathinfo($nombre, PATHINFO_EXTENSION);
                if (in_array($extension, ['pdf'])) {
                    $icono = "../../Icon/pdf.svg";
                } elseif (in_array($extension, ['ppt', 'pptx'])) {
                    $icono = "../../Icon/ppt.svg";
                } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $icono = "../../Icon/png.svg";
                } elseif (in_array($extension, ['doc', 'docx'])) {
                    $icono = "../../Icon/word.svg";
                } elseif (in_array($extension, ['xls', 'xlsx'])) {
                    $icono = "../../Icon/excel.svg";
                } else {
                    $icono = "../../Icon/defecto.jpg";
                }

                echo "<div class='material-card'>
                        <a href='../../uploads/materiales/$nombre' download>
                            <img src='$icono' alt='$extension'>
                            <div class='material-name'>$nombre</div>
                        </a>
                        <form method='POST' action='../../Scripts/Script_Profesor/eliminarMaterial.php' onsubmit='return confirm(\"¿Eliminar material?\");'>
                            <input type='hidden' name='material_id' value='$material_id'>
                            <button type='submit' class='eliminar-material'>&times;</button>
                        </form>
                      </div>";
            }
            echo "</div>";
        } else {
            echo "<p class='no-materials'>No hay materiales disponibles.</p>";
        }

        echo "<form method='POST' action='../../html/Profesor/VistaTransmision.php'>
                  <input type='hidden' name='sesion_id' value='$idSesion'>
                  <input type='hidden' name='nombre_curso' value='$nombreCurso'>
                  <input type='hidden' name='descripcion_curso' value='$descripcionCurso'>
                  <button type='submit' class='btn-transmitir'>Grabar Clase</button>
              </form>";

        echo "<h2>Subir Materiales:</h2>
              <form method='POST' action='../../Scripts/Script_Profesor/subirMaterial.php' enctype='multipart/form-data'>
                  <input type='hidden' name='sesion_id' value='$idSesion'>
                  <label>Sube materiales de clase:</label>
                  <input type='file' name='material[]' multiple>
                  <button type='submit'>Subir Documentos</button>
              </form>";

        echo "</div>";
    }
}

ob_start();
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
        <h2>$nombreCurso</h2>
        <p>$descripcionCurso</p>
        <div class='unidades'>
            <h3>Unidades</h3>
            <div class='tabs'>
                $contenido_tabs
            </div>
            <form method='POST' action='../../Scripts/Script_Profesor/agregarSesion.php'>
                <button type='submit' class='agregar-sesion'>Agregar Sesión</button>
            </form>
        </div>
    </div>
";
?>
