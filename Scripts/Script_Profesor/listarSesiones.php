<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

$rdb = new firebaseRDB($databaseURL);

// Obtener el ID del curso de la sesión si está presente en la URL
if (isset($_GET['curso_id'])) {
    $_SESSION['cursoid'] = $_GET['curso_id'];
} else {
    die("Error: No se proporcionó el ID del curso.");
}

$curso_id = $_SESSION['cursoid'];

// Obtener datos del curso desde Firebase
$retrieveCursos = $rdb->retrieve("/cursos", "id", "EQUAL", $curso_id);
$cursosData = json_decode($retrieveCursos, true);

$nombreCurso = 'Curso no encontrado';
$descripcionCurso = 'Descripción no encontrada';

if (is_array($cursosData) && !empty($cursosData)) {
    foreach ($cursosData as $curso) {
        $nombreCurso = htmlspecialchars($curso['nombre_curso']);
        $descripcionCurso = htmlspecialchars($curso['descripcion']);
    }
}

// Obtener sesiones del curso
$retrieveSesiones = $rdb->retrieve("/sesiones/$curso_id");
$sesionesData = json_decode($retrieveSesiones, true);

if (!is_array($sesionesData)) {
    $sesionesData = [];
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
            // Mostrar el reproductor de video
            if (!empty($sesion['video']) && isset($sesion['id'])) {
            $video_url = htmlspecialchars($sesion['video']);

            if (strpos($video_url, 'youtube.com') !== false) {
             // Si es un enlace de YouTube, mostrar el reproductor de YouTube
             echo "<div class='video-container'><iframe width='100%' height='auto' src='" . $video_url . "' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe></div>";
             } else {
              // Si es un video subido al servidor
             $video_url = "../../uploads/videos/" . $video_url;
             echo "<div class='video-container'><video src='" . $video_url . "' controls style='width: 100%; height: auto;'></video></div>";
             }
            }  else {
             if (isset($sesion['id'])) {
            // Mostrar un reproductor de video vacío si no hay URL válida disponible
            echo "<div class='video-container'><video controls style='width: 100%; height: auto;'><source src='' type='video/mp4'></video></div>";
            }
            }

            echo "<h2>Análisis del Video por la IA: </h2>";

            if (isset($sesion['ia'])) {
                $nombreMaterial = $sesion['ia']; // Nombre del archivo PDF
                $urlMaterial = "../../uploads/Documentos/" . htmlspecialchars($nombreMaterial); // URL del PDF
                $extensionMaterial = pathinfo($nombreMaterial, PATHINFO_EXTENSION);
        
                // Determinar el icono según la extensión del archivo
                if (in_array($extensionMaterial, ['pdf'])) {
                    $icono = "../../Icon/pdf.jpg";
                } else {
                    $icono = "../../Icon/defecto.jpg"; // Icono genérico por defecto
                }
        
                // Mostrar la tarjeta del PDF con los estilos
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

            // Verificar si existen materiales y mostrarlos
if (isset($sesion['material'])) {
    echo "<div class='materiales-container'>";
    foreach ($sesion['material'] as $index => $material) {
        $nombreMaterial = basename($material); // Obtener el nombre del archivo
        $urlMaterial = "../../uploads/materiales/" . htmlspecialchars($material); // URL del material
        $extensionMaterial = pathinfo($nombreMaterial, PATHINFO_EXTENSION);
        $icono = "icono-generico.png"; // Icono genérico, puedes ajustar según el tipo de archivo

        // Determinar el icono según la extensión del archivo
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
        } else {
            $icono = "../../Icon/defecto.jpg"; // Icono genérico por defecto
        }

        // Mostrar la tarjeta del material con los estilos
        if (is_file($urlMaterial)) {
            echo "<div class='material-card'>";
            echo "<a href='$urlMaterial' download>";
            echo "<img src='$icono' alt='$extensionMaterial'>";
            echo "<div class='material-name'>$nombreMaterial</div>";
            echo "</a>";
            echo "<form method='POST' action='../../Scripts/Script_Profesor/eliminarMaterial.php' class='eliminar-material-form' onsubmit='return confirm(\"¿Estás seguro de eliminar este material?\");'>";
            echo "<input type='hidden' name='sesion_id' value='" . htmlspecialchars($sesion['id']) . "'>"; // Incluir el ID de la sesión
            echo "<input type='hidden' name='material_index' value='" . htmlspecialchars($index) . "'>"; // Incluir el índice del material
            echo "<button type='submit' class='eliminar-material'>&times;</button>";
            echo "</form>";
            echo "</div>";
        }
    }
    echo "</div>";
} else {
    echo "<p class='no-materials'>No hay materiales disponibles para esta sesión.</p>";
}




            // Formulario para transmitir en vivo utilizando YouTube
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
            echo "<h2>Subir Materiales: </h2>";
            // Formulario para subir video
            if (isset($sesion['id'])) {
                echo "<form method='POST' action='../../Scripts/Script_Profesor/subirVideo.php' enctype='multipart/form-data'>
                          <input type='hidden' name='sesion_id' value='" . htmlspecialchars($sesion['id']) . "'>     
                          <div class='form-section'>
                          <label for='videoFile'>SUBE TU VIDEO DE CLASE:</label>
                            <div class='file-upload-container'>
                                <input class='custom-file-upload' type='file' id='videoFile' name='video' accept='video/mp4,video/webm,video/ogg'>
                              </div>
                            </div>
                          <button class='btn-transmitir' type='submit'>Subir Video</button>
                      </form>";
            }
            


            // Formulario para subir materiales
            if (isset($sesion['id'])) {
                echo "<form method='POST' action='../../Scripts/Script_Profesor/subirMaterial.php' enctype='multipart/form-data'>
                          <input type='hidden' name='sesion_id' value='" . htmlspecialchars($sesion['id']) . "'>
                          <br>
                          <div class='form-section'>
                            <label for='materialFiles'>SUBE MATERIALES DE CLASE:</label>
                             <div class='file-upload-container'>
                              <input class='custom-file-upload' type='file' id='materialFile' name='material[]' accept='.pdf,.jpg,.jpeg,.png,.doc,.docx,.ppt' multiple>
                             </div>
                            </div>
                          <button class='btn-transmitir' type='submit'>Subir Documentos</button>
                      </form>";
            } 
            echo "</div>";
        }
    } else {
        echo "<div class='tab-pane active'>Aquí podrás ver el contenido de tus sesiones.</div>";
    }
}

ob_start(); // Iniciar el almacenamiento en búfer de salida
?>

<ul class="tab-header">
    <?php generarPestanas($sesionesData); ?>
</ul>
<div class="tab-content">
    <?php generarContenido($sesionesData); ?>
</div>

<?php
$contenido_tabs = ob_get_clean(); // Obtener el contenido del búfer y limpiarlo

echo "
    <h1>Detalles del Curso</h1>
    <div class='curso'>
        <h2 id='nombreCurso'>" . $nombreCurso . "</h2>
        <p id='descripcionCurso'>" . $descripcionCurso . "</p>
        <div class='unidades'>
            <h3>Unidades</h3>
            <div class='tabs'>
                <!-- Mostrar pestañas y contenido dinámico -->
                $contenido_tabs
            </div>
            <form method='POST' action='../../Scripts/Script_Profesor/agregarSesion.php'>
                <button type='submit' class='agregar-sesion'>Agregar Sesión</button>
            </form>
        </div>
    </div>
";
?>
