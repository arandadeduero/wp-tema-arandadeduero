# Desarrollo

Cómo trabajar en el tema: herramientas, comprobaciones, reglas de código y flujo de PR. Para publicar una versión,
[RELEASE.md](RELEASE.md). Para las decisiones técnicas, [MODERNIZATION.md](MODERNIZATION.md).

## Puesta en marcha

```sh
composer install   # phpcs (WPCS + PHPCompatibility), phpstan, parallel-lint, PHPUnit + WP_Mock
npm install        # eslint, stylelint, prettier, browser-sync
```

Requisitos: PHP 8.2 o superior **para las herramientas** (la CI usa 8.2; el código del tema debe seguir funcionando
en PHP 7.4, que es el del servidor), Node 20 o superior y Composer.

**No hay paso de compilación.** Lo que hay en `css/` y `js/` es exactamente lo que se sirve: no hay SCSS, ni
minificación, ni Autoprefixer en el flujo. `postcss.config.js` queda de herencia y ningún script lo usa.

## Comandos

| Comando | Qué hace |
| --- | --- |
| `composer run lint:wpcs` | Estándares de WordPress (phpcs). Solo deben quedar los avisos conocidos de `tax_query` |
| `composer run lint:php` | Errores de sintaxis PHP (parallel-lint) |
| `composer run phpstan` | Análisis estático, nivel 5 (`phpstan.neon.dist`; la línea base está en `phpstan-baseline.neon`) |
| `composer run test` | Tests unitarios (PHPUnit + WP_Mock, sin WordPress) |
| `npm run lint:js` | ESLint sobre `js/` |
| `npm run lint:css` | Stylelint sobre `css/` |
| `npm run fix:css` | Stylelint con `--fix` |
| `npm run format` | Prettier sobre `js/` y `css/` |
| `npm run lint` | `lint:js`, `lint:css` y `lint:php` |
| `npm run release` | Lint y paquete del tema (ver [RELEASE.md](RELEASE.md)) |
| `npm start` | browser-sync con recarga en vivo (solo sirve ficheros estáticos; no ejecuta PHP) |

Compatibilidad con PHP 7.4 (no la ejecuta la CI; hacerlo antes de un PR que toque PHP):

```sh
./vendor/bin/phpcs -p --standard=PHPCompatibilityWP --runtime-set testVersion 7.4 --extensions=php \
  --ignore='vendor/*,node_modules/*,dist/*,tests/*' .
```

## Antes de abrir un PR

Todo en verde: `composer run lint:php`, `lint:wpcs`, `phpstan`, `test`, `npm run lint:js`, `npm run lint:css`, y la
compatibilidad con PHP 7.4 si se tocó PHP. La CI (`.github/workflows/tests.yml`) ejecuta los seis primeros en cada
push y en cada PR, con permisos de solo lectura (`permissions: contents: read`).

- `phpcbf` reformatea los arrays y puede romper parches hechos por sustitución de texto: después de editar con un
  script, comprobar con `grep` o con un test que el cambio está.
- Stylelint exige una línea en blanco antes de los comentarios dentro de una regla. Si falla, no encadenar
  `git add && git commit` detrás: se llegó a subir CSS roto así.
- Los tests están en `tests/` (78 en total): metadatos sociales, datos estructurados, caché de eventos, contraste, carrusel,
  «Especiales», accesos frecuentes, pagos, buscador de servicios, widgets de eventos, cadenas de My Calendar, avisos de cumplimiento, rendimiento y diferido del slider.

## Reglas del código

- **Sin CSS inline.** Todo el CSS va en `css/aranda-de-duero.css`: ni `style=""` en las plantillas ni `<style>` en el
  HTML. Los valores dinámicos (colores del Personalizador) salen como variables `--aranda-*` en `:root`. Bootstrap
  carga **después** del CSS del tema: si una regla pierde contra él, reforzar el selector (`h2.clase`,
  `.padre .clase`), y `!important` solo donde el inline original lo llevaba.
- **Sin JavaScript de terceros ni jQuery.** `js/` son cinco ficheros propios, con `defer` y en el pie. De Bootstrap
  solo se carga el CSS.
- **Sin recursos de terceros** (CDN, fuentes de Google, emojis de WordPress): todo autoalojado, para no enviar la IP
  de los visitantes (RGPD). La fuente es Outfit (`css/fonts/`); no usar otra.
- Prefijo `aranda_de_duero_` para funciones, filtros y hooks; textos con el dominio `aranda-de-duero`.
- Todo el contenido de un listado repetido (por ejemplo la cajita de presentación de Agenda, Bandos, Subvenciones
  y Notas de prensa) va en una pieza común de `template-parts/`, no copiado en cada plantilla.
- Hay que mantener el aspecto al refactorizar: comparar estilos calculados antes y después, y comprobar en la web
  real, no solo en una copia local.

## Verificar sin WordPress local

No hay entorno de pruebas ni WordPress en local. Se verifica con:

1. Tests unitarios (PHPUnit + WP_Mock).
2. Una copia del HTML real de producción servida en local con el CSS nuevo (`python3 -m http.server`), que sirve para
   comparar maquetación, pero **no carga las fuentes de producción** (CORS): la tipografía de reserva engaña.
3. Inyectar CSS en la página publicada con las herramientas del navegador.
4. Tras desplegar: Lighthouse sobre la web real y medición del LCP (red Fast 4G, CPU ×4).

Producción **bloquea IPs tras ráfagas de peticiones** (503 durante una hora): pocas peticiones, espaciadas y nunca en
paralelo. Y la caché de página (Cache Enabler) devuelve la versión anterior hasta que se vacía: para ver código
recién desplegado, añadir un parámetro a la URL (`/?sin-cache=x`) o enviar `Accept: text/html` si se usa `curl`.

## Dependencias y seguridad

- **Dependabot** (`.github/dependabot.yml`): cada lunes abre, por ecosistema (GitHub Actions, composer y npm), **un PR
  con todas las minor y patch** y **otro aparte con las major**. Composer usa `versioning-strategy: increase` porque
  no hay `composer.lock` en el repositorio. Quedan ignoradas las major de `phpunit/phpunit` (10up/wp_mock exige
  PHPUnit 9.6) y de `squizlabs/php_codesniffer` (WPCS exige phpcs 3); quitar esas líneas cuando se puedan subir.
- **Seguridad de GitHub** (activada el 2026-10-18): alertas y actualizaciones de seguridad de Dependabot, secret
  scanning con push protection, informe privado de vulnerabilidades y CodeQL con la configuración por defecto
  (Actions y JavaScript; CodeQL no analiza PHP). No se pudieron activar los patrones genéricos de secretos ni las
  comprobaciones de validez: parecen depender del plan de la organización.
- Para subir a mano dentro de rangos: `npm install paquete@x.y.z` y `composer update paquete`. `npm update` a secas
  reescribe medio `package-lock.json`; evitarlo.

## Flujo de trabajo

1. Rama desde `main`: `git checkout -b nombre-descriptivo`.
2. Commit y push; PR con `gh pr create`. El mensaje de commit y el cuerpo del PR acaban con
   `Co-Authored-By: Claude <modelo> <noreply@anthropic.com>` cuando ha intervenido Claude.
3. **No hay despliegue automático.** Cada PR se sube a producción a mano (ver [RELEASE.md](RELEASE.md)); solo se
   mergea cuando está desplegado **y comprobado en la web real**.
4. Tras el merge: volver a `main`, `git pull` y borrar la rama.
5. Si la mejora sale de [MEJORAS-OTROS-AYUNTAMIENTOS.md](MEJORAS-OTROS-AYUNTAMIENTOS.md), actualizar su fila y el
   registro en el mismo PR.
