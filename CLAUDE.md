# Reglas del proyecto (tema de WordPress de arandadeduero.es)

Para trabajar con Claude Code en este repositorio. El detalle está en [DEVELOPMENT.md](DEVELOPMENT.md),
[RELEASE.md](RELEASE.md), [MODERNIZATION.md](MODERNIZATION.md) y [MEJORAS-OTROS-AYUNTAMIENTOS.md](MEJORAS-OTROS-AYUNTAMIENTOS.md).

## Cómo se trabaja aquí

- **Idioma:** español en las respuestas, los commits y los PR.
- **No hay despliegue automático, pero el flujo lo hace Claude.** En cada cambio: PR, zip (`npm run release`, siempre **el tema
  completo**, desde una rama que lleve todo lo que ya está en producción), subida a `wp-admin/theme-install.php` con Chrome (la
  sesión del usuario ya está abierta; nunca escribir contraseñas), comprobación del cambio en la web real y un informe con lo
  verificado, lo no verificado y **qué debe mirar el usuario y en qué página**. **Solo se mergea cuando el usuario responde
  «merge»** (o «comprueba y mergea»).
- **Excepción: PR solo de documentación.** Si todos los ficheros son `.md` o no cambian la web (nada de `.php`, `.js`, `.css`, `theme.json`,
  `composer.json`, `package.json`, `scripts/` ni `.github/`), se hace commit con `[skip ci]`, PR y merge al momento
  (`gh pr merge N --merge --delete-branch --body "[skip ci]"`), sin esperar a la CI ni al despliegue. Comprobar antes con `git diff --name-only main`.
- Commits y cuerpo de los PR acaban con `Co-Authored-By: Claude <modelo usado> <noreply@anthropic.com>`.
- Informes honestos: separar lo **verificado** de lo **no verificado** y decir qué método se usó. No dar nada por hecho
  sin una comprobación independiente (un `grep`, un test, una medición en la web publicada).
- Decisiones de contenido, plugins o textos legales son del usuario y del ayuntamiento: proponer y esperar.

## Reglas del código

- **Ningún CSS inline** (ni `style=""` ni `<style>`): todo en `css/aranda-de-duero.css`. Bootstrap carga después: reforzar
  el selector antes que usar `!important`.
- **Ningún JavaScript de terceros ni jQuery**, y **ningún recurso de terceros** en el navegador (RGPD).
- **Ningún color escrito a mano, y solo los de la paleta corporativa.** Todo color es una variable del `:root` de `css/aranda-de-duero.css`: azul `#0083c1` (Pantone 7460C),
  azul claro `#e9f4f9`, rojo `#ce0e2d` (186C), ámbar `#f5a800` (130C), marrón `#a4541c` (160C), celeste `#3cb4e5` (298C), negro 75 % `#646464` y 90 % `#3c3c3c`, más negro, blanco
  y sus transparencias (`--color-black-10`…). Excepción del Ayuntamiento (2026-10-25): los cinco colores claros de los contenedores (`--color-bin-*`), tomados de su folleto, solo en la página de residuos. Solo hay dos excepciones más, por accesibilidad: `--color-primary-accessible` `#0073ad` (texto, enlaces y botones con texto blanco, porque
  `#0083c1` da 4,19:1) y su hover `--color-primary-dark`. `stylelint` falla con cualquier hex, `rgb()`/`hsl()` o color con nombre fuera de la paleta. **No inventar tonos**: si hace falta
  uno que no está, preguntar. En PHP, los pocos valores que no pueden leer una variable (`aranda_de_duero_brand_color()`, los valores por defecto del Personalizador) están en
  `inc/customizer-css.php`; `theme.json` usa la misma paleta; `tests/ColorSourcesTest.php` vigila que todo coincida.
- **Tipografía: solo Outfit.**
- Compatible con **PHP 7.4** (el del servidor).
- Antes de un PR: `composer run lint:php`, `lint:wpcs`, `phpstan`, `test`, `npm run lint:js`, `npm run lint:css`.
- Un listado o bloque repetido va en una pieza común de `template-parts/`.

## Producción (www.arandadeduero.es)

- Cache Enabler está desactivado desde 2026-10-25: no hay caché de página. Si se reactiva, hay que vaciarla tras desplegar y, para ver código nuevo, usar `?sin-cache=x` o `Accept: text/html`.
- Un despliegue **no borra** los ficheros que se quitan del repositorio.
- No hay WordPress local ni entorno de pruebas: se verifica con tests, una copia del HTML real con el CSS nuevo y,
  sobre todo, la web publicada.

## Documentación viva

Al terminar un cambio, actualizar lo que toque: `MEJORAS-OTROS-AYUNTAMIENTOS.md` (fila y registro, en el mismo PR),
`MODERNIZATION.md` (decisiones técnicas) y `docs/` (páginas con plantilla propia). Editar las tablas Markdown con
sustituciones exactas y volver a contar las columnas.
