# Página de enlaces («Enlaces de interés»)

Sustituye a la página hecha con Elementor (`/enlaces-de-interes/`), que era la única que usaba ese plugin. Es una página de
enlaces tipo «link en la bio»: logotipo, un bloque azul claro con los botones del Ayuntamiento y de «Aranda ME GUSTA» y, debajo,
una tarjeta de color por servicio (Policía Local, Bomberos, CEAS, Deportes, Biblioteca y Protección Civil).

**No hay que pegar nada en la página:** todo el contenido está en la plantilla `page-enlaces.php`. En WordPress basta con
elegir, en la página «Enlaces de interés», la plantilla **«Plantilla de enlaces (sin cabecera)»** (el contenido de la página
da igual: solo se usa su título). La plantilla no lleva la cabecera del Ayuntamiento, pero sí su pie (`get_footer()`): el aviso de cookies (GDPR) y la
analítica (Site Kit) los cargan `wp_head()` y `wp_footer()`, que la plantilla llama igual que cualquier otra.

## Cómo se cambia un enlace
Los enlaces están en la lista `$aranda_enlaces` del principio de `page-enlaces.php`: añadir, quitar o corregir una línea
`array( 'Instagram', 'https://…' )`. El icono se elige solo por la dirección (`instagram.com`, `facebook.com`, `tiktok.com`,
`x.com`, `youtube.com`; `linktr.ee` lleva un libro y «Página Web» un globo). Los colores y el aspecto están en el bloque
«Página de enlaces» de `css/aranda-de-duero.css`; los iconos grandes de las tarjetas, en `images/enlaces/*.svg`.
