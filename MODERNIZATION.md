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
- Iconos: Font Awesome Free 6.5.1 convertido a SVG y registrado con la API de iconos de WordPress 7.1
  (`inc/icons.php`); ya no se carga la webfont completa.
- Variables CSS en `css/aranda-de-duero.css`; Stylelint con `npm run lint:css`.
- Los colores del Customizer se aplican con un `<style>` en `wp_head`.

## PHP

- Estándares de WordPress verificados con phpcs, PHPStan nivel 5 y tests unitarios (PHPUnit + WP_Mock).
- Versión de cada asset según `filemtime()` (`aranda_de_duero_asset_version()`).
- Imágenes destacadas con `the_post_thumbnail()` (`srcset`/`sizes` automáticos).
- Código dividido en `inc/`: iconos, patrones, Customizer, metadatos sociales, datos estructurados,
  Speculation Rules y Jetpack.

## SEO y seguridad

- Open Graph, Twitter Card, canonical de taxonomías y JSON-LD; ver `inc/social-meta.php` y `inc/structured-data.php`.
- `wp_generator`, `wlwmanifest_link` y `rsd_link` eliminados de `wp_head`.
- Customizer: el JavaScript personalizado solo lo edita quien tiene `unfiltered_html`;
  colores, imágenes y textos pasan por `sanitize_callback`.

## Pendiente

- Purgar Bootstrap (≈233 KB de CSS y 80 KB de JS cargados en todas las páginas).
- Mover la plantilla a `theme.json` completo / tema de bloques e Interactivity API para el menú.
- Revisar contrastes de los colores configurables en el Customizer.
- El ajuste del Customizer «Vídeo para la página de inicio» ya no se usa en ninguna plantilla
  (la home usa Smart Slider): retirarlo o volver a mostrarlo.
