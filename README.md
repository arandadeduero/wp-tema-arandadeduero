# Aranda de Duero — tema de WordPress

Tema del sitio web del [Ayuntamiento de Aranda de Duero](https://www.arandadeduero.es/).
Tema clásico (PHP) basado en [Underscores](https://underscores.me/), con el CSS de Bootstrap 5 autoalojado (sin su
JavaScript), JavaScript propio sin jQuery, fuente Outfit local e iconos SVG mediante la API de iconos de WordPress 7.1.
Sin recursos de terceros en el navegador del visitante (RGPD).

## Código público

Este tema es software libre (GPL v2 o posterior) y su código es público. Responde a una política del equipo de gobierno: toda
inversión en programas propios del Ayuntamiento se publica para que pueda ser reutilizada por otras administraciones, en la línea
de la campaña europea [«Dinero público, código público»](https://publiccode.eu/) de la Free Software Foundation Europe. Se puede
consultar, usar y adaptar; las propuestas de mejora son bienvenidas como incidencias o *pull requests*.

## Requisitos

- WordPress 7.1 o superior (los iconos usan `wp_get_icon()`; en versiones anteriores no se muestran)
- PHP 7.4 o superior
- Plugins que usan las plantillas: Advanced Custom Fields y My Calendar
- Ya **no hacen falta** (el tema los sustituye y se pueden desactivar): Elementor, Smart Slider, Ajax Load More,
  Mobile Menu y el plugin de Font Awesome

## Qué incluye

- **SEO y redes sociales:** Open Graph, Twitter Card, canonical en taxonomías y JSON-LD
  (`GovernmentOrganization`, `WebSite`, `NewsArticle`, `Event`, `BreadcrumbList`).
  Se desactivan si hay Yoast, Rank Math, AIOSEO o SEOPress. Ver `inc/social-meta.php` y `inc/structured-data.php`.
- **Rendimiento:** versión de assets por `filemtime()`, scripts con `defer`, precarga de la fuente,
  Speculation Rules en modo `prerender` (`inc/speculation-rules.php`).
- **Consultas:** la agenda de eventos se cachea en un transient y se invalida al editar eventos (`inc/events.php`).
- **Accesibilidad:** el Customizer rechaza combinaciones de color por debajo de 4,5:1 (`inc/color-contrast.php`).
- **Seguridad:** el JavaScript personalizado del Customizer exige `unfiltered_html`;
  todos los ajustes del Customizer tienen `sanitize_callback`.
- **Portada propia:** carrusel (`inc/home-carousel.php`, tipo de contenido `portada_slide`), «Especiales»
  (`inc/especiales.php`), agenda y noticias, sin plugins ni peticiones AJAX.
- **Cabecera y menú propios:** barra superior con teléfono, horario y accesos (cita previa, transparencia, sede) y menú
  móvil del tema (`js/navigation.js`).
- **Páginas con plantilla propia:** Agenda, Ayuntamiento, Bandos, Subvenciones y Notas de prensa comparten la cajita de
  presentación (`template-parts/page-intro.php`); `page-enlaces.php` es la página de enlaces «link en la bio»
  (ver [docs/pagina-enlaces.md](docs/pagina-enlaces.md)).
- **Patrones de bloques** en `inc/block-patterns.php` y paleta/tipografía en `theme.json`.

## Desarrollo

```sh
composer install   # herramientas PHP
npm install        # herramientas JS/CSS
```

| Comando | Qué hace |
| --- | --- |
| `composer run lint:wpcs` | Estándares de código de WordPress (phpcs) |
| `composer run lint:php` | Errores de sintaxis PHP |
| `composer run phpstan` | Análisis estático |
| `composer run test` | Tests unitarios (PHPUnit + WP_Mock, sin necesidad de WordPress) |
| `npm run lint` | ESLint, Stylelint y sintaxis PHP |
| `npm run release` | Lint y paquete del tema: `aranda-de-duero-2.0.0.zip` en la raíz |

La CI (`.github/workflows/tests.yml`) ejecuta los linters y los tests en cada push. **No hay despliegue automático**:
el tema se sube a mano (ver [RELEASE.md](RELEASE.md)). Dependabot abre cada lunes un PR con las actualizaciones minor y
patch y otro con las major; CodeQL, el análisis de secretos y las alertas de seguridad están activados en GitHub.

## Documentación

- [DEVELOPMENT.md](DEVELOPMENT.md): herramientas, comprobaciones, reglas del código y flujo de PR
- [RELEASE.md](RELEASE.md): cómo generar y desplegar una versión
- [MODERNIZATION.md](MODERNIZATION.md): decisiones técnicas y estado de la modernización
- [MEJORAS-OTROS-AYUNTAMIENTOS.md](MEJORAS-OTROS-AYUNTAMIENTOS.md): lista viva de mejoras, con su estado, PR y fuente
- [docs/](docs/): informe de cumplimiento (bloque A) y página de enlaces
- [CLAUDE.md](CLAUDE.md): reglas del proyecto para trabajar con Claude Code

## Licencia

GPL v2 o posterior. Ver [LICENSE](LICENSE). Basado en Underscores, © Automattic, Inc.
