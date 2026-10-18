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

## Notas
- El logotipo no es una imagen: se monta con el escudo (`uploads/2025/11/08-escudo-Ayto-Aranda-de-Duero-color-300x300.png`) y dos líneas de texto.
  Si cambia el escudo, hay que cambiar esa ruta en `page-enlaces.php`.
- La tipografía es Outfit, la del resto del sitio (decisión del ayuntamiento: no usar otra).
- Como la plantilla no usa la cabecera, el enlace «saltar al contenido» no existe en ella; el `<h1>` es el título de la
  página («Enlaces de interés») y cada grupo repite ese título con el nombre del perfil en un `<span>`.
- Con esta plantilla Elementor ya no se usa en ninguna página: el plugin se puede desactivar.
- Los perfiles de «Aranda ME GUSTA» (Facebook `ArandaMeGusta`) y de Protección Civil (Instagram `protearanda`) los dio el
  ayuntamiento el 2026-10-18; no se ha comprobado que abran.
