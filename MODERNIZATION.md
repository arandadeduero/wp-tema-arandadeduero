# Modernización del tema

Resumen de las decisiones técnicas del tema. Sustituye a los antiguos `MODERNIZATION.md` y
`MODERNIZATION_COMPLETE.md`, que repetían la misma información; el detalle histórico está en el
historial de git.

## JavaScript

- Sin jQuery: todo es JavaScript moderno (`const`/`let`, funciones flecha, `querySelector`).
- Scripts del tema y Bootstrap se cargan con `defer` y en el pie (`functions.php`).
- ESLint con `--max-warnings 0` (`npm run lint:js`).

## CSS y dependencias

- Bootstrap 5.3.2 autoalojado en `css/` y `js/`, sin CDN, para no enviar IPs de visitantes a terceros (RGPD).
- Fuente Outfit autoalojada (`css/fonts/`) y precargada.
- Menú móvil (plugin Mobile Menu): su CSS dinámico fija `font-family: Roboto`; el tema lo sobrescribe con Outfit
  (final de `css/aranda-de-duero.css`) y retira la petición a Google Fonts (`inc/performance.php`, filtro
  `aranda_de_duero_dequeue_google_roboto`). Si el plugin añade reglas nuevas con Roboto, hay que incluirlas en el override
  o ajustar la tipografía en los ajustes del plugin.
- Iconos: Font Awesome Free 6.5.1 convertido a SVG y registrado con la API de iconos de WordPress 7.1
  (`inc/icons.php`); ya no se carga la webfont completa.
- Variables CSS en `css/aranda-de-duero.css`; Stylelint con `npm run lint:css`.
- Los colores del Customizer se aplican con un `<style>` en `wp_head`.

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

- Purgar Bootstrap (≈233 KB de CSS y 80 KB de JS cargados en todas las páginas).
- Tema de bloques e Interactivity API para el menú.
- El ajuste del Customizer «Vídeo para la página de inicio» ya no se usa en ninguna plantilla
  (la home usa Smart Slider): retirarlo o volver a mostrarlo.
