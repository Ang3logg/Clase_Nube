<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styles/styleLive.css">
    <title>Grabar Sesión</title>
</head>
<body>
<div class="container">
    <h1>Grabar Sesión</h1>

    <!-- Formulario para iniciar la transmisión en vivo -->
    <div class="form-container">
        <div class="input-group">
            <label for="cameraSelect">Seleccionar Cámara:</label>
            <select id="cameraSelect"></select>
        </div>
        <div class="input-group">
            <label for="microphoneSelect">Seleccionar Micrófono:</label>
            <select id="microphoneSelect"></select>
        </div>
        <div class="button-group">
            <button type="button" id="startButton">Vista Previa</button>
            <button type="button" id="stopButton" disabled>Terminar Sesión</button>
            <button type="button" id="toggleMicrophoneButton">Activar/Desactivar Micrófono</button>
            <button type="button" id="toggleCameraButton">Activar/Desactivar Cámara</button>
        </div>
    </div>

    <!-- Contenedor para el video -->
    <div class="video-container">
        <div class="video-wrapper">
            <video id="liveVideo" autoplay muted></video>
        </div>
    </div>

    <!-- Botones para controlar la grabación -->
    <div class="record-controls">
        <button id="startRecordButton">Grabar</button>
        <button id="pauseResumeButton" disabled>Pausar</button>
        <button id="stopRecordButton" disabled>Detener y Guardar</button>
    </div>

    <button type="button" class="btn-regresar" onclick="window.history.back();">
        ← Regresar
    </button>

    <!-- Mensajes de carga y finalización -->
    <div class="messages">
        <div id="loadingMessage" style="display: none;">
            <p>Cargando...</p>
        </div>
        <div id="completionMessage" style="display: none;">
            <p>¡Video grabado!</p>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('liveVideo');
    const startButton = document.getElementById('startButton');
    const stopButton = document.getElementById('stopButton');
    const toggleCameraButton = document.getElementById('toggleCameraButton');
    const loadingMessage = document.getElementById('loadingMessage');
    const completionMessage = document.getElementById('completionMessage');
    

    let stream;
    let mediaRecorder;
    let chunks = [];

    async function loadDevices() {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const cameras = devices.filter(device => device.kind === 'videoinput');
        const microphones = devices.filter(device => device.kind === 'audioinput');

        cameras.forEach(camera => {
            const option = document.createElement('option');
            option.value = camera.deviceId;
            option.textContent = camera.label || `Cámara ${cameraSelect.options.length + 1}`;
            cameraSelect.appendChild(option);
        });

        microphones.forEach(microphone => {
            const option = document.createElement('option');
            option.value = microphone.deviceId;
            option.textContent = microphone.label || `Micrófono ${microphoneSelect.options.length + 1}`;
            microphoneSelect.appendChild(option);
        });

        // Establecer la primera cámara y micrófono como predeterminados
        cameraSelect.value = cameras.length > 0 ? cameras[0].deviceId : '';
        microphoneSelect.value = microphones.length > 0 ? microphones[0].deviceId : '';
    } catch (error) {
        console.error('Error al cargar dispositivos:', error);
    }
}
loadDevices();

    startButton.addEventListener('click', async () => {
        try {
            const constraints = {
        video: {
        width: { ideal: 1280 },
        height: { ideal: 720 },
        aspectRatio: 16/9,
        facingMode: 'environment' // Puedes añadir esta opción si deseas usar la cámara trasera en dispositivos móviles
        },
        audio: true
            };

            stream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = stream;
            startButton.disabled = true;
            stopButton.disabled = false;
            toggleCameraButton.disabled = false;
            
            
        } catch (error) {
            console.error('Error al acceder a la cámara y/o micrófono:', error);
            alert('No se pudo acceder a la cámara y/o micrófono. Verifica los permisos.');
        }
    });

    stopButton.addEventListener('click', () => {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
            startButton.disabled = false;
            stopButton.disabled = true;
            toggleCameraButton.disabled = true;
        }
    });

    toggleCameraButton.addEventListener('click', () => {
        if (stream) {
            const videoTrack = stream.getVideoTracks()[0];
            if (videoTrack) {
                videoTrack.enabled = !videoTrack.enabled;
                toggleCameraButton.textContent = videoTrack.enabled ? 'Desactivar Cámara' : 'Activar Cámara';
            }
        }
    });

    const startRecordButton = document.getElementById('startRecordButton');
    const pauseResumeButton = document.getElementById('pauseResumeButton');
    const stopRecordButton = document.getElementById('stopRecordButton');

    startRecordButton.addEventListener('click', () => {
        if (!stream) {
            console.error('No se ha accedido a la cámara y/o micrófono.');
            return;
        }

        startRecordButton.disabled = true;
        pauseResumeButton.disabled = false;
        stopRecordButton.disabled = false;

        try {
            mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm; codecs=vp9' });

            mediaRecorder.ondataavailable = function(event) {
                if (event.data.size > 0) {
                    chunks.push(event.data);
                }
            };

            mediaRecorder.onstop = function() {
                const blob = new Blob(chunks, { type: 'video/webm' });
                const formData = new FormData();
                formData.append('sesion_id', '<?php echo isset($_POST["sesion_id"]) ? $_POST["sesion_id"] : "default_sesion_id"; ?>');
                formData.append('video', blob, 'grabacion.webm');

                loadingMessage.style.display = 'block'; // Mostrar mensaje de carga

                fetch('../../Scripts/Script_Profesor/guardar_video.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Mostrar la respuesta del servidor en la consola
                    loadingMessage.style.display = 'none'; // Ocultar mensaje de carga
                    completionMessage.style.display = 'block'; // Mostrar mensaje de finalización
                })
                .catch(error => {
                    console.error('Error al enviar el video al servidor:', error);
                    loadingMessage.style.display = 'none'; // Ocultar mensaje de carga en caso de error
                });
            };

            mediaRecorder.start();
        } catch (error) {
            console.error('Error al iniciar la grabación:', error);
        }
    });

    pauseResumeButton.addEventListener('click', () => {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.pause();
            pauseResumeButton.textContent = 'Continuar';
        } else if (mediaRecorder && mediaRecorder.state === 'paused') {
            mediaRecorder.resume();
            pauseResumeButton.textContent = 'Pausar';
        }
    });

    stopRecordButton.addEventListener('click', () => {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            startRecordButton.disabled = false;
            pauseResumeButton.disabled = true;
            stopRecordButton.disabled = true;
        }
    });
</script>


</body>
</html>
