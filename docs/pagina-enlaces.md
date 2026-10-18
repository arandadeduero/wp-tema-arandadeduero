# Página de enlaces («Enlaces de interés»)

Sustituye a la página hecha con Elementor (`/enlaces-de-interes/`), que era la única que usaba ese plugin. Es una página de
enlaces tipo «link en la bio»: logotipo, un bloque azul claro con los botones del Ayuntamiento y de «Aranda ME GUSTA» y, debajo,
una tarjeta de color por servicio (Policía Local, Bomberos, CEAS, Deportes, Biblioteca y Protección Civil).

**No hay que pegar nada en la página:** todo el contenido está en la plantilla `page-enlaces.php`. En WordPress basta con
elegir, en la página «Enlaces de interés», la plantilla **«Plantilla de enlaces (sin cabecera)»** (el contenido de la página
da igual: solo se usa su título). La plantilla no lleva cabecera ni el pie del Ayuntamiento: termina con una línea de
copyright y se cierra sola.

## Cómo se cambia un enlace
Los enlaces están en la lista `$aranda_enlaces` del principio de `page-enlaces.php`: añadir, quitar o corregir una línea
`array( 'Instagram', 'https://…' )`. El icono se elige solo por la dirección (`instagram.com`, `facebook.com`, `tiktok.com`,
`x.com`, `youtube.com`; `linktr.ee` lleva un libro y «Página Web» un globo). Los colores y el aspecto están en el bloque
«Página de enlaces» de `css/aranda-de-duero.css`; los iconos grandes de las tarjetas, en `images/enlaces/*.svg`.

## Dos enlaces de la página original que estaban mal (no incluidos)
- *Aranda ME GUSTA → Facebook* apuntaba a `facebook.com/search/top` (una búsqueda, no un perfil).
- *Protección Civil → «Instagram»* apuntaba a `facebook.com/BiblioAranda` (la Biblioteca): la tarjeta sale sin botón hasta que
  se sepa su dirección. Se añade en `$aranda_enlaces` (`'proteccion-civil'`).
