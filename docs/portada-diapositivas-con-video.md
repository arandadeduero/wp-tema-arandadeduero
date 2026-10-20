# Portada: diapositivas con vídeo

Cada diapositiva del carrusel de la portada (*Portada → Diapositivas*) puede llevar un vídeo MP4 encima de su imagen.

## Cómo se usa

1. **Medios → Añadir nuevo:** sube el MP4. WordPress puede rechazarlo si pesa más que el límite de subida del servidor.
2. **Portada → Añadir diapositiva** (o editar una): pon el título, la **Imagen destacada** (es obligatoria) y, en «Enlace y vídeo
   de la diapositiva», elige el vídeo de la lista. «Orden» (en Atributos de página) fija la posición.
3. Publica. Con varios vídeos, uno por diapositiva.

## Qué hace la web con él

- La **imagen destacada se sigue viendo** mientras el vídeo carga, y siempre para quien no pueda reproducirlo (movimiento
  reducido, ahorro de datos, un navegador que no permite reproducir solo). Por eso es obligatoria y conviene que sea un fotograma
  del propio vídeo.
- Se reproduce **solo, en bucle de una vez y sin sonido**, y el carrusel pasa a la siguiente diapositiva **cuando acaba**
  (una diapositiva con solo imagen sigue cambiando a los 6 segundos). El botón de pausa del carrusel también lo detiene.
- **No se descargan todos a la vez:** el primero se pide cuando termina de cargar la página y cada uno de los siguientes, cuando
  empieza a reproducirse el anterior.
- Con una sola diapositiva el vídeo se repite y se queda el botón de pausa, sin flechas.

## Cómo preparar el vídeo (lo que decide el peso)

Sin sonido, H.264, con el índice al principio (`faststart`, para que empiece sin esperar a descargarlo entero) y del mismo ancho
que las imágenes actuales (1392 px; la proporción de la tira es 3,72:1):

```sh
ffmpeg -i original.mp4 -an -c:v libx264 -crf 28 -preset slow -pix_fmt yuv420p -movflags +faststart \
  -vf "scale=1392:-2" portada.mp4
```

Un `-crf` más alto pesa menos y se ve peor. Conviene que cada vídeo pese pocos MB: los GIF actuales ya son lo más pesado de la
portada.

## Comprobado y por comprobar

Comprobado (2026-10-20, con vídeos de prueba y el HTML real de la portada): orden de descarga, avance al terminar, pausa,
movimiento reducido y ahorro de datos, en Chromium y en WebKit con el perfil de iPhone 14. **Sin comprobar:** un iPhone real
(modo de bajo consumo, que puede impedir reproducir solo: en ese caso se queda la imagen) y el selector de vídeo en el
administrador de WordPress.
