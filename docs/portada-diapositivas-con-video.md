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
- Se reproduce **solo, una vez y sin sonido**, y el carrusel pasa a la siguiente diapositiva **cuando acaba**
  (una diapositiva con solo imagen sigue cambiando a los 6 segundos). El botón de pausa del carrusel también lo detiene.
- **No se descargan todos a la vez:** el primero se pide cuando termina de cargar la página y cada uno de los siguientes, cuando
  empieza a reproducirse el anterior.
- Con una sola diapositiva el vídeo se repite y se queda el botón de pausa, sin flechas.
- **El cambio de diapositiva es un fundido** (se desvanece, cambia y reaparece, 0,3 s cada tramo), no un desplazamiento lateral.
  Un gesto con el dedo sigue deslizando la tira.
- **Quien tiene activado «Reducir movimiento»** (en iPhone, iPad, Mac, Windows o Android) ve el carrusel en pausa, con el botón en
  forma de «play», solo las imágenes y los cambios de diapositiva sin animación. Si pulsa «play», se reproducen los vídeos: es una
  petición expresa. Para ver los vídeos sin pulsar nada hay que desactivar esa opción en el dispositivo (iOS: Ajustes →
  Accesibilidad → Movimiento → Reducir movimiento).

## Cómo preparar el vídeo (lo que decide el peso)

Sin sonido, H.264, con el índice al principio (`faststart`, para que empiece sin esperar a descargarlo entero) y del mismo ancho
que las imágenes actuales (1392 px; la proporción de la tira es 3,72:1):

```sh
ffmpeg -i original.mp4 -an -c:v libx264 -crf 28 -preset slow -pix_fmt yuv420p -movflags +faststart \
  -vf "scale=1392:-2" portada.mp4
```

Un `-crf` más alto pesa menos y se ve peor. Conviene que cada vídeo pese pocos MB. Los seis vídeos de la portada pesan entre
34 y 199 KB, de 5 s y 1392×510 (medido en la web el 2026-10-20), y sus imágenes destacadas son JPG de entre 34 y 307 KB: la portada
ya no lleva ningún GIF.

## Comprobado y por comprobar

**Comprobado en la web publicada (2026-10-20, con los seis vídeos reales):** los vídeos se piden de uno en uno, a unos 5,4 s de
distancia (el siguiente cuando empieza el anterior), la portada pasa a la siguiente diapositiva al acabar cada uno y recorre las seis,
el cambio es un fundido y no hay errores de vídeo ni de JavaScript (Chrome de escritorio). También en la web publicada, con WebKit y el
perfil de iPad Pro 11 y «Reducir movimiento» activado: arranca en pausa, sin pedir ningún vídeo, y al pulsar «play» los reproduce
en orden. El desplegable de vídeos del administrador funciona (las seis diapositivas lo usan). En una copia local se probaron además
el ahorro de datos, la diapositiva única y los clics rápidos en las flechas.

**Sin comprobar:** un iPad o un iPhone reales y el modo de bajo consumo, que puede impedir reproducir solo (en ese caso se queda
la imagen, y «play» no lo cambia si Safari lo rechaza).
