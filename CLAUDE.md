# Reglas del proyecto (tema de WordPress de arandadeduero.es)

Para trabajar con Claude Code en este repositorio. El detalle está en [DEVELOPMENT.md](DEVELOPMENT.md),
[RELEASE.md](RELEASE.md), [MODERNIZATION.md](MODERNIZATION.md) y [MEJORAS-OTROS-AYUNTAMIENTOS.md](MEJORAS-OTROS-AYUNTAMIENTOS.md).

## Cómo se trabaja aquí

- **Idioma:** español en las respuestas, los commits y los PR.
- **No hay despliegue automático.** El usuario sube el tema a mano (`npm run release`, siempre **el tema completo**) y vacía
  la caché de Cache Enabler. Al abrir un PR, recordárselo. **Solo se mergea cuando dice que está desplegado y se ha
  comprobado en la web real**; «comprueba y mergea» autoriza el merge si la comprobación sale bien.
- Commits y cuerpo de los PR acaban con `Co-Authored-By: Claude <modelo usado> <noreply@anthropic.com>`.
- Informes honestos: separar lo **verificado** de lo **no verificado** y decir qué método se usó. No dar nada por hecho
  sin una comprobación independiente (un `grep`, un test, una medición en la web publicada).
- Decisiones de contenido, plugins o textos legales son del usuario y del ayuntamiento: proponer y esperar.

## Reglas del código

- **Ningún CSS inline** (ni `style=""` ni `<style>`): todo en `css/aranda-de-duero.css`. Bootstrap carga después: reforzar
  el selector antes que usar `!important`.
- **Ningún JavaScript de terceros ni jQuery**, y **ningún recurso de terceros** en el navegador (RGPD).
- **Tipografía: solo Outfit.**
- Compatible con **PHP 7.4** (el del servidor).
- Antes de un PR: `composer run lint:php`, `lint:wpcs`, `phpstan`, `test`, `npm run lint:js`, `npm run lint:css`.
- Un listado o bloque repetido va en una pieza común de `template-parts/`.

## Producción (www.arandadeduero.es)

- **Bloquea IPs tras ráfagas** (503 durante una hora): pocas peticiones, espaciadas, nunca en paralelo.
- La caché de página devuelve la versión anterior hasta vaciarla; para ver código nuevo: `?sin-cache=x` o `Accept: text/html`.
- Un despliegue **no borra** los ficheros que se quitan del repositorio.
- No hay WordPress local ni entorno de pruebas: se verifica con tests, una copia del HTML real con el CSS nuevo y,
  sobre todo, la web publicada.

## Documentación viva

Al terminar un cambio, actualizar lo que toque: `MEJORAS-OTROS-AYUNTAMIENTOS.md` (fila y registro, en el mismo PR),
`MODERNIZATION.md` (decisiones técnicas) y `docs/` (páginas con plantilla propia). Editar las tablas Markdown con
sustituciones exactas y volver a contar las columnas.
