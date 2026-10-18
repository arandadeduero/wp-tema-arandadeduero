# Modernización del tema

Resumen de las decisiones técnicas del tema. Sustituye a los antiguos `MODERNIZATION.md` y
`MODERNIZATION_COMPLETE.md`, que repetían la misma información; el detalle histórico está en el
historial de git.

## JavaScript

- Sin jQuery ni JavaScript de terceros: todo es JavaScript moderno (`const`/`let`, funciones flecha, `querySelector`) y
  son siete ficheros propios en `js/`: `navigation.js` (menú móvil), `sticky-header.js` (cabecera fija), `home-carousel.js` (carrusel de la portada), `search-suggest.js` (sugerencias del buscador),
  `event-poster.js` (cartel del evento, solo en `single-mc-events`), `aranda-de-duero.js` y `customizer.js`.
- Los scripts del tema se cargan con `defer` y en el pie (`functions.php`).
- ESLint con `--max-warnings 0` (`npm run lint:js`).

## CSS y dependencias

- Bootstrap 5.3.8 (solo CSS) autoalojado en `css/`, sin CDN, para no enviar IPs de visitantes a terceros (RGPD). El JavaScript de Bootstrap (81 KB, 24 KB gzip) se quitó: ni el tema ni los plugins activos lo usan (sin `data-bs-*` ni llamadas a `bootstrap.*`).
- Fuente Outfit autoalojada (`css/fonts/`) y precargada. Es la única tipografía del sitio: la página de enlaces y el
  menú móvil también la usan (decisión del ayuntamiento; no añadir otra).
- Las fuentes de Font Awesome (`css/webfonts/`) se borraron el 2026-10-18: ningún fichero las referenciaba.
- Menú móvil propio del tema (≤ 767 px, `js/navigation.js` y bloque «Menú móvil propio» de `css/aranda-de-duero.css`); ya no
  se usa el plugin Mobile Menu ni su Roboto de Google Fonts.
- Cabecera: **barra superior** con teléfono, horario y tres botones (Cita previa, Portal de transparencia, Sede
  electrónica), leídos de `aranda_de_duero_contact_info()` para que cabecera y pie coincidan; el logo y el menú principal
  van en una sola fila a partir de 1200 px y el logo encima del menú entre 768 y 1199 px. El menú secundario (ubicación
  `top`) ya no existe.
- Iconos: Font Awesome Free 6.5.1 convertido a SVG y registrado con la API de iconos de WordPress 7.1
  (`inc/icons.php`); ya no se carga la webfont completa.
- Variables CSS en `css/aranda-de-duero.css`; Stylelint con `npm run lint:css`.
- **Paleta de colores corporativa y como variables** (2026-10-18). Primero, los 71 colores distintos que había escritos a mano (216 usos en el CSS del tema y 29 en `style.css`) pasaron a variables del
  `:root` sin cambiar ningún valor (equivalencia comprobada y 0 diferencias en 4.554 elementos de 10 páginas reales). Después se unificaron en la **paleta corporativa** del Ayuntamiento: azul
  `#0083c1` (Pantone 7460C) y su 8 % `#e9f4f9`, rojo `#ce0e2d` (186C), ámbar `#f5a800` (130C), marrón `#a4541c` (160C), celeste `#3cb4e5` (298C), negro 75 % `#646464` y 90 % `#3c3c3c`, negro y blanco.
  De 62 colores opacos se pasó a **12** (esos 10 más dos tonos de accesibilidad: `--color-primary-accessible` `#0073ad`, el azul para texto, enlaces y botones con texto blanco, y su hover
  `--color-primary-dark` `#006399`). Los grises claros (bordes, fondos) son transparencias del negro (`--color-black-10`, `-20`…), y los avisos usan transparencias del ámbar. Una regla de stylelint impide
  escribir colores a mano en adelante, y `tests/ColorSourcesTest.php` comprueba que ninguna variable de color se use sin estar definida, que PHP, `theme.json` y el CSS coincidan y que `style.css` no
  esté vacío. `theme.json` usa la misma paleta (el azul accesible en enlaces y botones).
  Unificar **sí cambió el aspecto** (532 de 4.754 elementos medidos): sobre todo los títulos y botones de la página de enlaces (celeste oficial, texto gris 90 %), las tarjetas de enlaces (Deportes pasa de
  beige a marrón), el pie de página (de azul marino a gris 90 % con barra inferior negra), los grises de la 404 y los azules de enlaces y títulos, que pasan al azul accesible. Se corrigieron dos
  regresiones de contraste que esto introdujo (texto del pie al 50 % y títulos blancos sobre el ámbar, que ya fallaban antes).
- **Regla: no CSS inline.** Todo el CSS va en `css/aranda-de-duero.css`: nada de `style=""` en las plantillas ni de `<style>` en el HTML. Los colores del Customizer y de la cabecera solo imprimen variables `--aranda-*` en `:root` (`inc/customizer-css.php`, `inc/custom-header.php`) y las reglas están en el fichero. Bootstrap carga después: refuerza el selector en vez de recurrir a `style=`.

## Portada, listados y páginas

- **Carrusel propio** (`inc/home-carousel.php`, `js/home-carousel.js`): sustituye a Smart Slider. Las diapositivas son
  un tipo de contenido privado, «Portada: diapositivas» (`portada_slide`): imagen destacada, título (texto alternativo
  si la imagen no lo tiene), enlace opcional y orden. Es una tira con scroll-snap en CSS, con flechas, pausa y cambio
  automático (que no se activa con `prefers-reduced-motion`); precarga las demás diapositivas al terminar de cargar la
  página. Mientras no haya ninguna diapositiva, `page-home.php` sigue imprimiendo Smart Slider, cuyas imágenes se
  difieren salvo la primera (`aranda_de_duero_defer_slider_images()`, `inc/performance.php`).
- **Cajita de presentación** (`template-parts/page-intro.php`): título, presentación y tres consejos. La usan Agenda,
  Bandos, Subvenciones y Notas de prensa; la presentación de la Agenda pasa por el filtro `aranda_de_duero_agenda_intro`.
  Antes esas tres páginas usaban un aviso azul de Bootstrap y no tenían `h1`.
- **Agenda, Ayuntamiento, noticia y evento a ancho completo y sin barra lateral** (`page-agenda.php`,
  `page-ayuntamiento.php`, `single.php`, `single-mc-events.php`; el evento muestra el cartel ampliable en un
  `<dialog>`). El resto de plantillas conservan su maquetación.
- **Archivos** (categorías, etiquetas, fechas): cabecera y fichas con el mismo aspecto (`archive.php`).
- **Accesos frecuentes y «Pagos y recibos»** (`inc/services.php`, `template-parts/service-cards.php`, `page-pagos.php`): ocho
  tarjetas entre el carrusel y las noticias, y una página que agrupa recibos, multas, liquidaciones y calendario fiscal. Casi todo
  existía ya en la sede electrónica; aquí solo se hace encontrable. Los enlaces y descripciones salen de la propia sede
  (lista editable en `inc/services.php`, con los filtros `aranda_de_duero_quick_access` y `aranda_de_duero_payment_services`).
  Ver [docs/pagos-y-recibos.md](docs/pagos-y-recibos.md).
- **Buscador «¿Qué necesitas?»** (`inc/search.php`, `template-parts/search-box.php`, `search.php`): campo visible en la home, sobre una
  franja del azul de marca a todo el ancho con el título y el campo centrados (`.home-buscador`), y en los resultados, y un bloque «Servicios y trámites» encima de los resultados con los servicios de la sede, del portal y de «Especiales» que coinciden con lo escrito
  (sin acentos, todas las palabras al inicio de palabra, con sinónimos y con los aciertos en el título primero), más un enlace al catálogo de trámites. La búsqueda de WordPress solo mira noticias y
  páginas y no encontraba, por ejemplo, el certificado de padrón. La página de resultados ya no usa los textos en inglés de Underscores.
  La búsqueda de WordPress sigue tardando ≈ 4 s (medido el 2026-10-18): no se ha tocado.
  **Sugerencias al escribir** (`js/search-suggest.js`): la lista de servicios (unas 55 entradas, ≈ 3,5 KB comprimidos) viaja con la página
  (`wp_localize_script`, `aranda_de_duero_search_index()`) y el navegador la filtra con el mismo criterio que el servidor, de modo que las
  sugerencias salen al instante y **sin ninguna petición** (no es AJAX a propósito: cada petición pasaría por WordPress, ≈ 4 s). Patrón ARIA
  «combobox con listbox»: flechas, Enter, Escape, y aviso al lector de pantalla. Sin JavaScript el buscador es un formulario normal.
- **Trámites por situaciones** (`inc/situations.php`, `page-tramites-situaciones.php`): siete situaciones («me empadrono», «me caso», «negocio»,
  «obras», «impuestos», «empleo o ayudas», «presentar un documento o una queja») que agrupan los **17 trámites permanentes con página propia en el
  portal** (`/tramite/<slug>/`) y los servicios de la sede. Las convocatorias anuales (ayudas a asociaciones, a estudiantes…) no se fijan porque
  cambian de dirección cada año. Los mismos trámites alimentan el buscador (`aranda_de_duero_portal_procedures()`).
- **Página de enlaces** (`page-enlaces.php`): plantilla sin cabecera con todo el contenido dentro; sustituye a la página
  de Elementor, que era la única que usaba ese plugin. Ver [docs/pagina-enlaces.md](docs/pagina-enlaces.md).
- Los iconos de Font Awesome que el editor guarda dentro de entradas y páginas (`svg-inline--fa`) llevan tamaño propio.

## PHP

- Estándares de WordPress verificados con phpcs, PHPStan nivel 5 y tests unitarios (PHPUnit + WP_Mock).
- Versión de cada asset según `filemtime()` (`aranda_de_duero_asset_version()`).
- Imágenes destacadas con `the_post_thumbnail()` (`srcset`/`sizes` automáticos).
- Código dividido en `inc/`: iconos, patrones, Customizer, eventos, contraste de color, metadatos
  sociales, datos estructurados, Speculation Rules y Jetpack.

## Rendimiento de consultas (`inc/events.php`)

La agenda de la home (`aranda_de_duero_get_upcoming_events()`) leía los eventos de My Calendar y hacía
una consulta SQL por cada uno para obtener su `occur_id`, en cada visita.

- **Caché:** el resultado se guarda 10 minutos en un transient (`aranda_upcoming_events_<límite>`).
  Se vacía al crear, editar, borrar o enviar a la papelera un `mc-events` (`save_post`, `deleted_post`,
  `trashed_post`). Los límites usados se recuerdan en la opción `aranda_upcoming_events_limits`.
- **Una sola consulta** para todos los `occur_id`, en lugar de una por evento.
- **Solo contenido publicado** (`post_status => publish`): como la caché se sirve a todos los visitantes,
  no puede depender de quién la rellene (WP_Query añade los eventos privados a quien puede verlos).
- **«Especiales» solo en la home y sin Ajax Load More:** la sección salía en todas las páginas (con una llamada a
  `admin-ajax.php` en cada carga); luego pasó al final de `page-home.php` y ahora la pinta el propio tema
  (`inc/especiales.php`) desde el tipo `especial`: imagen destacada, título y el campo ACF `especiales_enlace`. Salen
  todas (hasta 24), sin paginación ni «cargar más»; el campo `mostrar_en_home` ya no se usa. Ya no hay JS, ni llamada
  AJAX, ni `css/ajax-load-more.css`; el plugin Ajax Load More puede desactivarse.
- **Sin `COUNT` de paginación:** `aranda_de_duero_content()` acepta `$paginated = false`; la usan la
  agenda y las 9 noticias de la home, que no paginan.
- **Límite conocido:** un cambio en My Calendar que no pase por `save_post` tarda hasta 10 minutos en verse.
  Para forzarlo: `wp transient delete aranda_upcoming_events_50`.

## Colores y accesibilidad (`inc/color-contrast.php`)

El Customizer rechaza al guardar los pares de colores con contraste inferior a 4,5:1 (WCAG 2.1 AA,
exigido por el RD 1112/2018): texto, encabezados y enlaces sobre el fondo, y el texto de las secciones
principales sobre su fondo. Cada color se comprueba contra su pareja tal como quedará tras ese guardado.
No revisa los valores que ya estuvieran guardados. Los seis colores usan `sanitize_hex_color`.

## theme.json

Fuente Outfit declarada con `fontFace` (los mismos archivos de `css/fonts/`), escala de espaciados de
cinco tamaños y estilos para los bloques botón, cita y separador. El `@font-face` de
`css/aranda-de-duero.css` sigue duplicado; se puede quitar tras comprobar la fuente en el navegador.

## Tests

`composer run test` (99 tests, PHPUnit 9 + WP_Mock, sin WordPress): metadatos sociales, datos estructurados, caché de
eventos, contraste de color, carrusel, «Especiales», accesos frecuentes, pagos, buscador de servicios, widgets de eventos, cadenas de My Calendar, contacto, avisos de
cumplimiento, variables del Personalizador y rendimiento. La CI los ejecuta junto con phpcs, phpstan 2, parallel-lint,
ESLint y Stylelint. PHPUnit sigue en la 9 porque `10up/wp_mock` no soporta la 10+.

## Dependencias y seguridad

- **Dependabot** (`.github/dependabot.yml`): cada lunes, por ecosistema (Actions, composer, npm), un PR con las
  actualizaciones minor y patch y otro con las major. Ver [DEVELOPMENT.md](DEVELOPMENT.md).
- **Seguridad de GitHub** (2026-10-18): alertas y actualizaciones de seguridad de Dependabot, secret scanning con push
  protection, informe privado de vulnerabilidades y CodeQL. El análisis de CodeQL detectó 6 alertas
  (`actions/missing-workflow-permissions`) que se cerraron con `permissions: contents: read` en la CI.
- **Paquete de release** (`scripts/build-release.js`): la lista de ficheros se lee del disco, de modo que una plantilla
  nueva no se queda fuera. Ver [RELEASE.md](RELEASE.md).
- **Dependencias de desarrollo mínimas** (2026-10-18): se quitaron `@wordpress/scripts`, `browser-sync`, PostCSS, cssnano, autoprefixer, `postcss-cli` y
  terser (1.145 paquetes, nada los usaba), junto con el script `compile:rtl` (dependía de `rtlcss`, que solo estaba de rebote) y `npm start`. Las
  alertas de seguridad de Dependabot pasan de 17 a las pocas que quedan dentro de `stylelint`. Ver [DEVELOPMENT.md](DEVELOPMENT.md).
- Bootstrap se actualiza sustituyendo `css/bootstrap.min.css` (fichero oficial de `npm pack bootstrap@x.y.z`, carpeta
  `dist/css`) **y** el número de versión de `aranda_de_duero_bootstrap_css()` en `functions.php`.

## SEO y seguridad

- Open Graph, Twitter Card, canonical de taxonomías y JSON-LD; ver `inc/social-meta.php` y `inc/structured-data.php`.
- `wp_generator`, `wlwmanifest_link` y `rsd_link` eliminados de `wp_head`.
- Customizer: el JavaScript personalizado solo lo edita quien tiene `unfiltered_html`;
  colores, imágenes y textos pasan por `sanitize_callback`.

## Pendiente

- Purgar el CSS de Bootstrap (≈233 KB, 31 KB gzip; en 9 páginas medidas solo se usa ~12 % de las reglas).
- Tema de bloques e Interactivity API para el menú.
- El ajuste del Customizer «Vídeo para la página de inicio» (`aranda_de_duero_front_page_video`, en `functions.php`) ya no
  se usa en ninguna plantilla: retirarlo o volver a mostrarlo.
- Quitar del todo Smart Slider cuando la portada ya tenga sus diapositivas (la rama de reserva de `page-home.php` y
  `aranda_de_duero_defer_slider_images()`).
- **LCP de la home en escritorio (medido el 2026-10-18, Fast 4G):** la imagen de LCP es la primera diapositiva (GIF de 305 KB) y
  tarda ~1,9 s en bajar porque compite con la imagen de la primera noticia (352 KB, `loading="eager"` +
  `fetchpriority="high"` en `page-home.php`), que se pide a la vez. En móvil esa noticia **es** el LCP. Con la noticia en
  `lazy` el LCP de escritorio baja de 2,17 a 1,66 s, pero el de móvil sube de 2,50 a 2,87 s; una precarga con
  `media="(max-width: 767px)"` no llegó a usarse en la prueba. Además el `sizes` de esa imagen (`640px`) no coincide con su
  tamaño real (≈ 90 % del contenedor, recortada a 225 px de alto). Lo que más pesa sigue siendo el tamaño de los GIF.
- Cabeceras de caché del navegador para estáticos (`Cache-Control`/`Expires`, en el `.htaccess`): el tema no puede ponerlas.
