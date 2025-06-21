import sys
import moviepy.editor as mp
import os
import speech_recognition as sr
from nltk.tokenize import word_tokenize
from nltk.probability import FreqDist
from datetime import datetime
from sumy.parsers.plaintext import PlaintextParser
from sumy.nlp.tokenizers import Tokenizer
from sumy.summarizers.lsa import LsaSummarizer
import random
import string

# Función para generar un nombre único para el archivo de audio temporal
def generar_nombre_audio_temporal():
    timestamp = datetime.now().strftime('%Y%m%d%H%M%S')
    random_chars = ''.join(random.choices(string.ascii_letters + string.digits, k=5))
    return f"temp_audio_{timestamp}_{random_chars}.wav"

# Función para extraer el texto más repetido en una lista de palabras
def palabras_mas_repetidas(texto, n=5):
    tokens = word_tokenize(texto.lower())  # Convertir a minúsculas para contar correctamente
    fdist = FreqDist(tokens)
    return fdist.most_common(n)

# Función para realizar la transcripción de voz usando PocketSphinx
def transcribir_audio(input_video):
    try:
        # Extraer el audio del video usando moviepy
        video_clip = mp.VideoFileClip(input_video)
        audio_clip = video_clip.audio

        # Generar un nombre único para el archivo de audio temporal
        temp_audio_file = generar_nombre_audio_temporal()

        # Guardar el audio en el archivo temporal WAV
        audio_clip.write_audiofile(temp_audio_file)

        # Transcribir el audio usando SpeechRecognition
        recognizer = sr.Recognizer()
        with sr.AudioFile(temp_audio_file) as source:
            audio_data = recognizer.record(source)
            texto_transcrito = recognizer.recognize_sphinx(audio_data, language='es-ES')

        # Eliminar el archivo temporal de audio
        os.remove(temp_audio_file)

        return texto_transcrito
    except Exception as e:
        print(f"Error al transcribir audio: {str(e)}")
        return None

# Función para procesar el video y generar el resumen en texto
def procesar_video(input_video, output_txt):
    try:
        # Transcribir el audio del video
        texto_transcrito = transcribir_audio(input_video)
        if texto_transcrito is None:
            raise Exception("Error al transcribir el audio del video.")

        # Generar texto resumen
        texto_resumen = "Bienvenido a Clase Nube UCV\n"
        texto_resumen += "Resumen del Video:\n"

        # Palabras más repetidas en el texto transcrito
        palabras_comunes = palabras_mas_repetidas(texto_transcrito)
        texto_resumen += "\nPalabras más repetidas en el texto transcrito:\n"
        for palabra, frecuencia in palabras_comunes:
            texto_resumen += f"- {palabra}: {frecuencia} veces\n"

        # Resumen del texto transcrito usando LSA (Latent Semantic Analysis)
        resumen_texto = resumir_texto(texto_transcrito)
        texto_resumen += "\nResumen del texto transcrito:\n"
        texto_resumen += resumen_texto + "\n"

        # Mensaje final y derechos de autor (actualizando el año automáticamente)
        texto_resumen += f"\nEspero que este documento te sea de ayuda.\nAtte: Nubia.\n© Todos los derechos reservados - {datetime.now().year}"

        # Guardar el texto en un archivo
        with open(output_txt, 'w', encoding='utf-8') as file:
            file.write(texto_resumen)

        return True
    except Exception as e:
        print(f"Error al procesar el video: {str(e)}")
        return False

# Función para resumir el texto usando LSA (Latent Semantic Analysis)
def resumir_texto(texto):
    parser = PlaintextParser.from_string(texto, Tokenizer('spanish'))
    summarizer = LsaSummarizer()
    summary = summarizer(parser.document, 2)  # Resumen de 2 oraciones
    resumen = ""
    for sentence in summary:
        resumen += str(sentence) + " "
    return resumen.strip()

# Obtener los argumentos pasados desde PHP
if len(sys.argv) != 3:
    print("Uso: python IA.py ruta/al/video.mp4 ruta/de/salida/resumen.txt")
    sys.exit(1)

input_video = sys.argv[1]
output_txt = sys.argv[2]

# Procesar el video y generar el archivo de texto
if procesar_video(input_video, output_txt):
    print("Análisis de video completado correctamente.")
    sys.exit(0)
else:
    print("Error al analizar el video.")
    sys.exit(1)
