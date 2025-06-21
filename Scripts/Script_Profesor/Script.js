const liveVideo = document.getElementById('liveVideo');
const startButton = document.getElementById('startButton');
const stopButton = document.getElementById('stopButton');
const toggleMicrophoneButton = document.getElementById('toggleMicrophoneButton');
const toggleCameraButton = document.getElementById('toggleCameraButton');
const startRecordButton = document.getElementById('startRecordButton');
const pauseResumeButton = document.getElementById('pauseResumeButton');
const stopRecordButton = document.getElementById('stopRecordButton');
const loadingMessage = document.getElementById('loadingMessage');
const completionMessage = document.getElementById('completionMessage');
const cameraSelect = document.getElementById('cameraSelect');
const microphoneSelect = document.getElementById('microphoneSelect');

let cameraStream;
let mediaRecorder;
let chunks = [];
let frameCount = 0;
let lastFrameTime = Date.now();

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

// Llamar a la función para cargar dispositivos al cargar la página
loadDevices();



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

// Event listener para actualizar la cámara seleccionada
cameraSelect.addEventListener('change', () => {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        startButton.click(); // Reiniciar la vista previa con la nueva cámara
    }
});

// Event listener para actualizar el micrófono seleccionado
microphoneSelect.addEventListener('change', () => {
    if (cameraStream) {
        cameraStream.getAudioTracks()[0].stop();
        startButton.click(); // Reiniciar la vista previa con el nuevo micrófono
    }
});


//GRABACION DEL VIDEO
try {
    let mediaRecorder;
    const chunks = [];
    let startTime;

    navigator.mediaDevices.getUserMedia({ video: true, audio: true })
        .then(function (stream) {
            cameraStream = stream;
            mediaRecorder = new MediaRecorder(cameraStream, { mimeType: 'video/webm; codecs=vp9' });

            mediaRecorder.ondataavailable = function (event) {
                if (event.data.size > 0) {
                    chunks.push(event.data);
                }
            };

            mediaRecorder.onstop = function () {
                const blob = new Blob(chunks, { type: 'video/webm' });
                const videoUrl = URL.createObjectURL(blob);
                const videoElement = document.createElement('video');
                videoElement.src = videoUrl;

                videoElement.addEventListener('loadedmetadata', function () {
                    const duration = (Date.now() - startTime) / 1000;
                    const width = videoElement.videoWidth;
                    const height = videoElement.videoHeight;
                    let frameRate = 'N/A';
                    const videoTrack = cameraStream.getVideoTracks()[0];
                    if (videoTrack && videoTrack.getSettings) {
                        const settings = videoTrack.getSettings();
                        frameRate = settings.frameRate || 'N/A'; // Obtener la tasa de fotogramas si está disponible
                    }
                    let audioChannels = 'N/A';
                    let audioSampleRate = 'N/A';
                    const audioTrack = cameraStream.getAudioTracks()[0];
                    if (audioTrack && audioTrack.getSettings) {
                        const audioSettings = audioTrack.getSettings();
                        audioChannels = audioSettings.channelCount || 'N/A';
                        audioSampleRate = audioSettings.sampleRate || 'N/A';
                    }

                    const formData = new FormData();
                    formData.append('sesion_id', "<?php echo $_POST['sesion_id']; ?>");
                    formData.append('video', blob, 'grabacion.webm');
                    formData.append('duration', duration);
                    formData.append('width', width);
                    formData.append('height', height);
                    formData.append('frameRate', frameRate);
                    formData.append('audioChannels', audioChannels);
                    formData.append('audioSampleRate', audioSampleRate);
                    formData.append('source', 'webcam');

                    loadingMessage.style.display = 'block'; // Mostrar mensaje de carga

                    fetch('guardar_video.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => response.text())
                        .then(data => {
                            console.log(data); // Mostrar la respuesta del servidor en la consola
                            loadingMessage.style.display = 'none'; // Ocultar mensaje de carga
                            completionMessage.style.display = 'block'; // Mostrar mensaje de finalización
                        }).catch(error => {
                            console.error('Error al enviar el video al servidor:', error);
                            loadingMessage.style.display = 'none'; // Ocultar mensaje de carga en caso de error
                        });
                });

                videoElement.load(); // Cargar los metadatos del video
            };

            startTime = Date.now();
            mediaRecorder.start();
        })
        .catch(function (error) {
            console.error('Error al acceder a la cámara y/o micrófono:', error);
        });
} catch (error) {
    console.error('Error al iniciar la grabación:', error);
}


// Función para calcular la velocidad de bits (bitrate)
function calculateBitrate(size, duration) {
    return (size * 8) / duration;
}
