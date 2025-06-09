<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';  // Aquí asumo que tienes $conn (mysqli)

// Validar conexión
if (!isset($conn)) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Obtener el ID del curso desde la URL o sesión
if (isset($_GET['curso_id'])) {
    $_SESSION['cursoid'] = $_GET['curso_id'];
} elseif (isset($_SESSION['cursoid'])) {
    // Ya existe en sesión
} else {
    die("Error: No se proporcionó el ID del curso.");
}

$curso_id = $_SESSION['cursoid'];

// Obtener datos del curso desde MySQL
$sqlCurso = "SELECT nombre_curso, descripcion FROM cursos WHERE id = ?";
$stmtCurso = $conn->prepare($sqlCurso);
$stmtCurso->bind_param("i", $curso_id);
$stmtCurso->execute();
$resultCurso = $stmtCurso->get_result();

$nombreCurso = 'Curso no encontrado';
$descripcionCurso = 'Descripción no encontrada';

if ($resultCurso && $resultCurso->num_rows > 0) {
    $curso = $resultCurso->fetch_assoc();
    $nombreCurso = htmlspecialchars($curso['nombre_curso']);
    $descripcionCurso = htmlspecialchars($curso['descripcion']);
}

// Obtener sesiones del curso desde MySQL
$sqlSesiones = "SELECT * FROM sesiones WHERE curso_id = ? ORDER BY id ASC";
$stmtSesiones = $conn->prepare($sqlSesiones);
$stmtSesiones->bind_param("i", $curso_id);
$stmtSesiones->execute();
$resultSesiones = $stmtSesiones->get_result();

$sesionesData = [];
while ($fila = $resultSesiones->fetch_assoc()) {
    // Suponiendo que tienes columnas como 'id', 'nombre', 'video', 'ia', 'material' (material puede ser JSON)
    // Si 'material' está guardado en formato JSON en la BD, decodificarlo:
    if (isset($fila['material'])) {
        $fila['material'] = json_decode($fila['material'], true);
    }
    $sesionesData[] = $fila;
}

// Función para generar las pestañas
function generarPestanas($sesiones) {
    if ($sesiones && is_array($sesiones) && !empty($sesiones)) {
        foreach ($sesiones as $index => $sesion) {
            $activeClass = $index == 0 ? 'active' : '';
            $nombreSesion = isset($sesion['nombre']) ? htmlspecialchars($sesion['nombre']) : 'Bienvenido';
            $idSesion = isset($sesion['id']) ? htmlspecialchars($sesion['id']) : '';
            echo "<li class='$activeClass' data-tab='tab-$index' data-sesion-id='$idSesion'>$nombreSesion";
            if (!empty($idSesion)) {
                echo "<form method='POST' action='../../Scripts/Script_Profesor/eliminarSesion.php' style='display:inline;' onsubmit='return confirmarEliminacion(this);'>
                          <input type='hidden' name='sesion_id' value='$idSesion'>
                          <button type='submit'>&times;</button>
                      </form>";
            }
            echo "</li>";
        }
    } else {
        echo "<li class='active'>Bienvenido</li>";
    }
}

// Función para generar el contenido de las pestañas
function generarContenido($sesiones) {
    if ($sesiones && is_array($sesiones) && !empty($sesiones)) {
        foreach ($sesiones as $index => $sesion) {
            $activeClass = $index == 0 ? 'active' : '';
            echo "<div id='tab-$index' class='tab-pane $activeClass'>";
            echo "<h3>" . (isset($sesion['nombre']) ? htmlspecialchars($sesion['nombre']) : 'Aquí está el contenido del curso, navega entre las sesiones para el contenido') . "</h3>";

            echo "<h2>Video: </h2>";
            if (!empty($sesion['video']) && isset($sesion['id'])) {
                $video_url = htmlspecialchars($sesion['video']);
                $video_name = htmlspecialchars($sesion['video']);
                $sesionid = htmlspecialchars($sesion['id']);

                if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                    // Ajustar para que el embed funcione con URLs de YouTube
                    $embed_url = $video_url;
                    // Si es un link normal, convertir a embed
                    if (strpos($video_url, 'watch?v=') !== false) {
                        parse_str(parse_url($video_url, PHP_URL_QUERY), $query);
                        if (isset($query['v'])) {
                            $embed_url = "https://www.youtube.com/embed/" . $query['v'];
                        }
                    }
                    echo "<div class='video-container'><iframe width='100%' height='auto' src='" . $embed_url . "' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe></div>";
                } else {
                    $video_path = "../../uploads/videos/" . $video_url;
                    echo "<div class='video-container'><video src='" . $video_path . "' controls style='width: 100%; height: auto;'></video></div>";
                }

                echo "<form method='POST' action='../../Scripts/Admin_Scripts/ocultarvideo.php'>";
                echo "<input type='hidden' name='sesionid' value='" . $sesionid . "'>";
                echo "<input type='hidden' name='video_name' value='" . $video_name . "'>";
                echo "<input type='submit' name='ocultar_video' value='Ocultar Video'>";
                echo "</form>";

                echo "<form method='POST' action='../../Scripts/Admin_Scripts/mostrarvideo.php'>";
                echo "<input type='hidden' name='sesionid' value='" . $sesionid . "'>";
                echo "<input type='hidden' name='video_name' value='" . $video_name . "'>";
                echo "<input type='submit' name='mostrar_video' value='Mostrar Video'>";
                echo "</form>";
            }

            echo "<h2>Análisis del Video por la IA: </h2>";

            if (isset($sesion['ia']) && !empty($sesion['ia'])) {
                $nombreMaterial = $sesion['ia'];
                $urlMaterial = "../../uploads/Documentos/" . htmlspecialchars($nombreMaterial);
                $extensionMaterial = pathinfo($nombreMaterial, PATHINFO_EXTENSION);
                $icono = "../../Icon/defecto.jpg";
                if ($extensionMaterial === 'pdf') {
                    $icono = "../../Icon/pdf.jpg";
                }

                echo "<div class='material-card'>";
                echo "<a href='$urlMaterial' download>";
                echo "<img src='$icono' alt='$extensionMaterial'>";
                echo "<div class='material-name'>$nombreMaterial</div>";
                echo "</a>";
                echo "</div>";
            } else {
                echo "<p class='no-materials'>No se encontró el archivo txt generado por la IA para esta sesión.</p>";
            }

            echo "<h2>Materiales: </h2>";

            if (isset($sesion['material']) && is_array($sesion['material']) && !empty($sesion['material'])) {
                echo "<div class='materiales-container'>";
                foreach ($sesion['material'] as $index => $nombreMaterial) {
                    $urlMaterial = "../../uploads/materiales/" . $nombreMaterial;
                    $extensionMaterial = pathinfo($nombreMaterial, PATHINFO_EXTENSION);
                    $icono = "../../Icon/defecto.jpg";

                    if (in_array($extensionMaterial, ['pdf'])) {
                        $icono = "../../Icon/pdf.jpg";
                    } elseif (in_array($extensionMaterial, ['ppt', 'pptx'])) {
                        $icono = "../../Icon/ppt.jpg";
                    } elseif (in_array($extensionMaterial, ['jpg', 'jpeg', 'png'])) {
                        $icono = "../../Icon/png.jpg";
                    } elseif (in_array($extensionMaterial, ['doc', 'docx'])) {
                        $icono = "../../Icon/word.jpg";
                    } elseif (in_array($extensionMaterial, ['xls', 'xlsx'])) {
                        $icono = "../../Icon/excel.jpg";
                    }

                    if (is_file($urlMaterial)) {
                        echo "<div class='material-card'>";
                        echo "<a href='$urlMaterial' download>";
                        echo "<img src='$icono' alt='$extensionMaterial'>";
                        echo "<div class='material-name'>$nombreMaterial</div>";
                        echo "</a>";
                        echo "<form method='POST' action='../../Scripts/Admin_Scripts/eliminarMaterial.php' class='eliminar-material-form' onsubmit='return confirm(\"¿Estás seguro de eliminar este material?\");'>";
                        echo "<input type='hidden' name='sesion_id' value='" . htmlspecialchars($sesion['id']) . "'>";
                        echo "<input type='hidden' name='material_index' value='" . htmlspecialchars($index) . "'>";
                        echo "<button type='submit' class='eliminar-material'>&times;</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                }
                echo "</div>";
            } else {
                echo "<p class='no-materials'>No hay materiales disponibles para esta sesión.</p>";
            }

            if (isset($sesion['id'])) {
                echo "<form id='formTransmision-$index' method='POST' action='../../html/Profesor/VistaTransmision.php'>
                          <input type='hidden' name='sesion_id' value='" . htmlspecialchars($sesion['id']) . "'>
                          <input type='hidden' name='sesion_id_static' value='static_id_aqui'>
                          <input type='hidden' name='nombre_curso' value='" . htmlspecialchars($GLOBALS['nombreCurso']) . "'>
                          <input type='hidden' name='descripcion_curso' value='" . htmlspecialchars($GLOBALS['descripcionCurso']) . "'>
                          <button type='submit' class='btn-transmitir'>Grabar Clase</button>
                      </form>";
            }

            echo "<br>";

            if (isset($sesion['id'])) {
                echo "<h2>Subir Materiales <br></h2>";
                echo "<h2>Seleccione o jale aquí su video editado<br></h2>";
                echo "<form action='../../Scripts/Script_Profesor/subirMaterial.php' method='POST' enctype='multipart/form-data'>
                      <input type='hidden' name='sesion_id' value='" . htmlspecialchars($sesion['id']) . "'>
                      <input type='file' name='material' accept='video/*'>
                      <input type='submit' name='subir_material' value='Subir Material'>
                      </form>";
            }

            echo "</div>";
        }
    } else {
        echo "<div class='tab-pane active'><h3>Aquí está el contenido del curso, navega entre las sesiones para el contenido</h3></div>";
    }
}
?>
