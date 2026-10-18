# Aranda de Duero — tema de WordPress

Tema del sitio web del [Ayuntamiento de Aranda de Duero](https://www.arandadeduero.es/).
Tema clásico (PHP) basado en [Underscores](https://underscores.me/), con Bootstrap 5 autoalojado,
JavaScript sin jQuery e iconos SVG mediante la API de iconos de WordPress 7.1.

## Requisitos

- WordPress 7.1 o superior (los iconos usan `wp_get_icon()`; en versiones anteriores no se muestran)
- PHP 7.4 o superior
- Plugins que usan las plantillas: Advanced Custom Fields y My Calendar

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
| `npm run release` | Compila y empaqueta el tema en `dist/` |

La CI (`.github/workflows/tests.yml`) ejecuta todos los linters y los tests en cada push.

## Documentación

- [DEVELOPMENT.md](DEVELOPMENT.md): flujo de trabajo y herramientas
- [BUILD_SETUP.md](BUILD_SETUP.md): configuración del sistema de build
- [RELEASE.md](RELEASE.md): cómo generar y desplegar una versión
- [MODERNIZATION.md](MODERNIZATION.md): decisiones técnicas y estado de la modernización

## Licencia

GPL v2 o posterior. Ver [LICENSE](LICENSE). Basado en Underscores, © Automattic, Inc.
