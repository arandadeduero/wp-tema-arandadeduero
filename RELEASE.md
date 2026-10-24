# Publicar una versión del tema

No hay despliegue automático: el paquete se genera con `npm run release` y se sube a mano a producción
(www.arandadeduero.es). Las comprobaciones de GitHub validan el código; no despliegan nada.

## 1. Generar el paquete

```sh
npm run release
```

1. Ejecuta `npm run build`: ESLint con `--max-warnings 0`, Stylelint y la sintaxis PHP (`lint:php`). Si algo falla,
   no se crea el paquete.
2. Ejecuta `scripts/build-release.js`, que empaqueta el tema en **`dist/aranda-de-duero-2.0.0.zip`** (`dist/` está en
   `.gitignore` y se vacía en cada compilación) (el nombre sale de `THEME_VERSION` en ese script; ver «Versión» más abajo).

No hay compilación ni minificación: se empaquetan los ficheros tal cual están.

**Qué lleva el zip** (117 entradas a 2026-10-24):

- Todos los `*.php` de la raíz (plantillas, `functions.php`, taxonomías…). La lista **se lee del disco**: una plantilla
  nueva entra sola, no hay que añadirla a ningún sitio.
- `style.css`, `style-rtl.css`, `theme.json`, `readme.txt`, `screenshot.png`, `LICENSE` y `README.md`.
- Los directorios `css/` (incluidas las fuentes Outfit), `data/` (calendario y catálogo de farmacias), `inc/`, `images/`, `js/`, `languages/` y `template-parts/`.

**Qué no lleva:** `node_modules`, `vendor`, `tests`, `docs`, `scripts`, `.github`, la configuración de las
herramientas ni los documentos de desarrollo.

> Hasta el 2026-10-18 este script estaba **roto** (`archiver is not a function`: Dependabot había subido `archiver` a
> la v8, que solo es ESM) y además tenía la lista de ficheros escrita a mano, sin `page-enlaces.php`,
> `page-subvenciones.php`, `page-notas-de-prensa.php`, `page-barrios-propuestas-respuestas.php`,
> `page-exp-plaza-de-toros.php` ni `theme.json`. Se arregló en el PR de la documentación; si vuelve a fallar tras una
> actualización de `archiver`, lo primero es mirar su API.

`bin/build-zip.sh` hace un `git archive` de **todo** el repositorio (con tests y documentación): sirve para guardar una
copia del código, no para subir a producción.

## 2. Desplegar

**Siempre el tema completo, nunca archivo a archivo.** `functions.php` hace `require` de `inc/*.php`, y
`footer.php` y `page-home.php` dependen uno del otro: una subida parcial ya provocó un error 500 en producción.

Dos maneras equivalentes:

- **Desde WordPress:** *Apariencia → Temas → Añadir nuevo → Subir tema*, con el zip, y reemplazar el tema actual.
- **Por SFTP:** descomprimir y subir la carpeta `aranda-de-duero/` a `/wp-content/themes/`, sobrescribiendo.

**Un despliegue copia ficheros, no borra los que ya no existen en el repositorio.** Si se elimina algo (por ejemplo
`css/webfonts/`), seguirá en el servidor hasta que se borre a mano. No molesta mientras nada lo pida.

## 3. Después de desplegar

1. **Caché de página:** Cache Enabler está desactivado desde 2026-10-25, así que no hay nada que vaciar. Si se reactiva,
   vaciarla: si no, los navegadores siguen recibiendo la página anterior aunque el código esté subido (la respuesta lleva
   `x-cache-handler: cache-enabler-engine` y `Vary: accept`).
2. **Esperar unos 30 s:** el servidor (PHP 7.4) puede seguir sirviendo los `.php` anteriores por la caché de opcache.
3. **Comprobar en la web real**, no en la copia local:
   - Ver lo que ven los visitantes: un navegador, o `curl` con `-H 'Accept: text/html'`. Para comprobar solo que el
     código nuevo funciona con la caché reactivada, cargar la página con un parámetro (`/?sin-cache=x`), que la caché no usa.
   - Cambios visibles: capturas en móvil y escritorio, y Lighthouse (accesibilidad y SEO deberían seguir en 100 en
     la home, noticias, noticia y agenda).
   - Cambios de rendimiento: LCP con red Fast 4G y CPU ×4, sin caché.
4. Solo entonces, mergear el PR y borrar la rama.

## Versión

`THEME_VERSION` está en `scripts/build-release.js` y la misma versión (2.0.0) en `style.css`, `style-rtl.css` y `readme.txt`; hay que
subir las cuatro a la vez. Solo se usan para el nombre del zip y la ficha del tema. Los CSS y JS se versionan solos por
fecha de modificación (`aranda_de_duero_asset_version()`, con `filemtime()`), salvo Bootstrap, cuya versión está
escrita en `aranda_de_duero_bootstrap_css()` en `functions.php`: **subirla cuando se cambie `css/bootstrap.min.css`** para
que los navegadores no sirvan la copia vieja.

## Si algo sale mal

Volver a subir el zip anterior (conservar el último que funcionó) y vaciar la caché. No hay entorno de pruebas
previo: cada despliegue va directo a producción.
