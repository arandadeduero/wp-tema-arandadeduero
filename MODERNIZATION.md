# Modernización del tema

Resumen de las decisiones técnicas del tema. Sustituye a los antiguos `MODERNIZATION.md` y
`MODERNIZATION_COMPLETE.md`, que repetían la misma información; el detalle histórico está en el
historial de git.

## JavaScript

- Sin jQuery: todo es JavaScript moderno (`const`/`let`, funciones flecha, `querySelector`).
- Los scripts del tema se cargan con `defer` y en el pie (`functions.php`).
- ESLint con `--max-warnings 0` (`npm run lint:js`).

## CSS y dependencias

- Bootstrap 5.3.8 (solo CSS) autoalojado en `css/`, sin CDN, para no enviar IPs de visitantes a terceros (RGPD). El JavaScript de Bootstrap (81 KB, 24 KB gzip) se quitó: ni el tema ni los plugins activos lo usan (sin `data-bs-*` ni llamadas a `bootstrap.*`).
- Fuente Outfit autoalojada (`css/fonts/`) y precargada.
- Menú móvil propio del tema (≤ 767 px, `js/navigation.js` y bloque «Menú móvil propio» de `css/aranda-de-duero.css`); ya no
  se usa el plugin Mobile Menu ni su Roboto de Google Fonts.
- Iconos: Font Awesome Free 6.5.1 convertido a SVG y registrado con la API de iconos de WordPress 7.1
  (`inc/icons.php`); ya no se carga la webfont completa.
- Variables CSS en `css/aranda-de-duero.css`; Stylelint con `npm run lint:css`.
- **Regla: no CSS inline.** Todo el CSS va en `css/aranda-de-duero.css`: nada de `style=""` en las plantillas ni de `<style>` en el HTML. Los colores del Customizer y de la cabecera solo imprimen variables `--aranda-*` en `:root` (`inc/customizer-css.php`, `inc/custom-header.php`) y las reglas están en el fichero. Bootstrap carga después: refuerza el selector en vez de recurrir a `style=`.

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

`composer run test` (PHPUnit + WP_Mock, sin WordPress): metadatos sociales, datos estructurados,
caché de eventos y contraste de color. La CI los ejecuta junto con phpcs, phpstan, ESLint y Stylelint.

## SEO y seguridad

- Open Graph, Twitter Card, canonical de taxonomías y JSON-LD; ver `inc/social-meta.php` y `inc/structured-data.php`.
- `wp_generator`, `wlwmanifest_link` y `rsd_link` eliminados de `wp_head`.
- Customizer: el JavaScript personalizado solo lo edita quien tiene `unfiltered_html`;
  colores, imágenes y textos pasan por `sanitize_callback`.

## Pendiente

- Purgar el CSS de Bootstrap (≈233 KB, 31 KB gzip; en 9 páginas medidas solo se usa ~12 % de las reglas).
- Tema de bloques e Interactivity API para el menú.
- El ajuste del Customizer «Vídeo para la página de inicio» ya no se usa en ninguna plantilla
  (la home usa Smart Slider): retirarlo o volver a mostrarlo.
