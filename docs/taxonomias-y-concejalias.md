# Taxonomías y concejalías: cómo funcionan en el tema

Para quien no conoce WordPress a fondo. Explica qué son las concejalías, los temas y las secciones, dónde se ven en la web,
de dónde sale cada lista y qué se puede cambiar. Cada afirmación sobre el código lleva su `fichero:línea`; todo lo que el
código no puede decir está en [«Qué NO se puede saber desde el código»](#qué-no-se-puede-saber-desde-el-código).

**Método:** lectura del repositorio (`grep` de `taxonomy`, `get_the_terms`, `wp_get_post_terms`, `tax_query`, `is_tax`,
`wp_nav_menu`, `register_sidebar`, `dynamic_sidebar`, etc. y lectura completa de las plantillas). No se ha consultado la web
publicada ni la base de datos. Las líneas son las del repositorio a fecha 2026-10-20.

Convención: **VERIFICADO** = se ve en el código citado. **INFERIDO** = deducido del código o del conocimiento de WordPress,
sin comprobarlo en la web real.

## En pocas palabras

- Una **taxonomía** es una forma de clasificar contenidos (como las categorías de las entradas). Cada clasificación concreta
  se llama **término**: «Juventud» es un término de la taxonomía `concejalia`; «Salud», un término de `tema`.
- **El tema (el código) no crea ninguna de estas taxonomías.** Solo registra el tipo de contenido `portada_slide`
  (`inc/home-carousel.php:29`). Las taxonomías `concejalia`, `tema`, `seccion` y `categoria_tramite`, y los tipos `tramite` y
  `especial`, no se registran en ningún `.php` del repositorio (VERIFICADO: `grep register_taxonomy|register_post_type` solo
  encuentra `inc/home-carousel.php:29`). INFERIDO: los define el plugin Custom Post Type UI y los campos, ACF; su definición
  vive en la base de datos.
- El tema **las consume**: tiene una plantilla de archivo para cada una (`taxonomy-concejalia.php`, `taxonomy-tema.php`,
  `taxonomy-seccion.php`), que WordPress usa solo por el **nombre del fichero** cuando alguien visita la dirección del
  término (`/concejalia/<slug>/`, `/tema/<slug>/`, `/seccion/<slug>/`; INFERIDO que los prefijos son esos, ver más abajo).
- Una **concejalía** o un **tema** se ve como una página que lista las **entradas normales** (`post`) con ese término
  (`functions.php:749-784`). Los **trámites** solo enseñan su concejalía como texto (`template-parts/content-tramite.php:46`).
- Las páginas de concejalía y de tema **no salen de un menú automático**: se llega a ellas desde el menú principal, que es un
  menú de WordPress montado a mano en *Apariencia → Menús* (`header.php:117`), y desde direcciones escritas a mano en el
  código (`inc/audiences.php:23-29`).
- Hay **dos riesgos de rotura silenciosa**: cambiar el *slug* de un término rompe el enlace del menú y las tarjetas de
  «Para ti» (`/tema/salud/`…), y `taxonomy-tema.php` tiene listas de slugs escritas a mano (`taxonomy-tema.php:28-38`).

## Esquema

| Taxonomía / tipo | ¿La registra el tema? | Plantilla | Dónde aparece en la web | De dónde sale su lista |
|---|---|---|---|---|
| `concejalia` | No (INFERIDO: CPT UI) | `taxonomy-concejalia.php` | `/concejalia/<slug>/`; en la ficha de cada trámite como texto (`template-parts/content-tramite.php:46`) | Entradas `post` con ese término (`functions.php:768-784`) |
| `tema` | No (INFERIDO: CPT UI) | `taxonomy-tema.php` | `/tema/<slug>/`; al pie de cada entrada como enlace (`inc/template-tags.php:141-153`) | Entradas `post` con ese término (`functions.php:749-765`) |
| `seccion` | No (INFERIDO: CPT UI) | `taxonomy-seccion.php` | `/seccion/<slug>/` (INFERIDO); nada en el código la enlaza | La consulta principal de WordPress, sin filtro propio (`taxonomy-seccion.php:23`) |
| `categoria_tramite` | No (INFERIDO: CPT UI) | Ninguna propia (cae en `archive.php`) | Solo como texto en la ficha del trámite (`content-tramite.php:34,56-58`) | Términos asignados al trámite |
| `category` (la de WordPress) | Sí, es nativa | `archive.php` | Cabecera de entrada (`template-parts/content-post.php:35`); listados por categoría fija (`page-bandos.php:49-55`) | Entradas `post` |
| `tramite` (tipo) | No (INFERIDO: CPT UI) | `single-tramite.php`, `page-tramite.php` | `/tramite/<slug>/` y el listado `/tramites/` (INFERIDO) | `WP_Query` de tipo `tramite` (`page-tramite.php:20-25`) |

## 1. Lo que registra el tema y lo que no

**VERIFICADO.** En `functions.php` no hay `register_taxonomy` ni `register_post_type`. La única taxonomía que el tema nombra
en consultas son `tema` (`functions.php:759`), `concejalia` (`functions.php:778`), `category` (`page-bandos.php:51`,
`page-notas-de-prensa.php:51`, `page-subvenciones.php:51`) y `categoria_tramite` (`template-parts/content-tramite.php:34`).
`seccion` solo se nombra en el nombre del fichero `taxonomy-seccion.php`.

**Consecuencia práctica.** El tema depende de que existan en WordPress, con exactamente esos nombres internos, `concejalia`,
`tema`, `seccion` y `categoria_tramite`. Si se desactivara CPT UI, las plantillas dejarían de usarse (nadie llegaría a ellas) y
`wp_get_post_terms()` devolvería un error que `template-parts/content-tramite.php:46-48` no controla (INFERIDO: el `foreach` sobre un
`WP_Error` daría un aviso de PHP). Conviene tener una copia exportada de la configuración de CPT UI y de ACF.

**Ficheros viejos.** Los comentarios dicen que las plantillas genéricas sustituyen a ficheros copiados término a término
(`taxonomy-concejalia-juventud.php`, `taxonomy-tema-salud.php`…; `taxonomy-concejalia.php:6-8`, `taxonomy-tema.php:6-9`). Un
despliegue **no borra** ficheros quitados del repositorio (CLAUDE.md), así que el servidor puede conservar esas copias y
WordPress las preferiría a las genéricas (jerarquía de plantillas). No verificable desde el código: ver la lista final.

## 2. Plantillas de archivo

### `taxonomy-concejalia.php`

- Pide la cabecera con imagen y le pasa el término actual (`taxonomy-concejalia.php:16-17`); la imagen sale del campo ACF
  `cabecera_de_pagina` del **término**, o si no hay, de la imagen por defecto del Personalizador
  (`inc/template-tags.php:196-201`).
- Lanza su propia consulta: entradas `post`, de más nuevas a más viejas, 25 por página, con ese `slug` de `concejalia`
  (`functions.php:768-784`, `post_type` en la línea 771).
- Barra lateral: `dynamic_sidebar( 'Servicios' )` (`taxonomy-concejalia.php:24`). WordPress acepta el nombre además del id y
  lo relaciona con `sidebar-servicios` (INFERIDO del comportamiento de `dynamic_sidebar`; el nombre está registrado en
  `functions.php:166`).
- Muestra una tarjeta por entrada: miniatura (si hay), título enlazado y extracto (`taxonomy-concejalia.php:28-60`).
  Paginación con «Noticias anteriores / posteriores» (`:61-76`).
- **Antes del PR #95 (ya corregido):** sin contenido no pintaba nada y no mostraba el nombre ni la descripción de la concejalía
  (ni `<h1>`). **Ahora** pinta `template-parts/term-heading.php` (tipo, `<h1>` con el nombre, descripción y, si la última entrada
  tiene más de 3 años, un aviso «Archivo histórico») y, sin entradas, «Todavía no hay contenido en este apartado»
  (`template-parts/term-posts.php`). Los números de línea de arriba son anteriores a ese cambio.
- **Los trámites no salen aquí**, aunque tengan esa concejalía: la consulta fija `post_type => 'post'`
  (`functions.php:771`). VERIFICADO.
- WordPress además ejecuta la consulta principal del archivo (que incluye los tipos que tengan esa taxonomía), pero esta
  plantilla la ignora y hace otra: una consulta duplicada por visita (VERIFICADO por el código; el coste real no se ha medido).

### `taxonomy-tema.php`

- Igual que la anterior en cabecera y consulta (`taxonomy-tema.php:23-26`, `functions.php:749-765`).
- **Dos diseños** elegidos por una lista de slugs escrita a mano: `tributos`, `concursos`, `oposiciones-y-empleo`,
  `ayudas-y-subvenciones`, `empleo` (`taxonomy-tema.php:28-29`). Esos se pintan como tabla «Fecha / Descripción»
  (`:48-68`); el resto, como tarjetas (`:69-102`).
- **Barra lateral por slug**, también a mano: `anuncios` → `Actualidad`; `oficina-desarrollo-local` y los cinco de la tabla →
  `Tramites`; el resto → `Servicios` (`:31-38`).
- **Corregido en el PR #95:** antes, sin contenido no pintaba nada (o una tabla vacía) y el diseño de tabla cerraba con
  `the_posts_navigation()`, que cuenta la consulta principal y no la de 25 entradas. Ahora usa la misma cabecera, el mismo
  mensaje de «sin contenido» y la paginación `aranda_de_duero_query_pagination()` (`inc/term-archives.php`) que las concejalías,
  y llama a `wp_reset_postdata()`.

### `taxonomy-seccion.php`

- **No hace consulta propia:** recorre la consulta principal (`taxonomy-seccion.php:23-33`) y pinta cada elemento con
  `content-page-notitle.php`, es decir, el **contenido completo** y el título oculto (`d-none`, `template-parts/content-page-notitle.php:15,23`).
- Barra lateral: `dynamic_sidebar( 'Seccion' )` (`:17`). **No existe ninguna barra lateral registrada con ese nombre**
  (las siete están en `functions.php:159-167`), así que la columna sale vacía (INFERIDO: `dynamic_sidebar` devuelve falso sin
  imprimir nada). Probable resto de una versión antigua.
- No pide la imagen de cabecera, no pagina y no controla el caso sin contenido.
- Nada del repositorio enlaza a `/seccion/…`. INFERIDO: es una taxonomía residual o de uso interno; confirmar con datos reales.

### Archivos de WordPress (categorías, etiquetas…)

`archive.php` es la plantilla genérica de categorías, etiquetas, autores y fechas (`archive.php:15-25`), con tarjetas y
mensaje «Todavía no hay contenido» (`:102`). Es el repliegue de `categoria_tramite` y de cualquier taxonomía sin plantilla
propia (INFERIDO de la jerarquía de WordPress).

Para las tres taxonomías hay una dirección canónica (`<link rel="canonical">`) generada para cualquier archivo de término
(`inc/social-meta.php:86-96`; VERIFICADO).

## 3. Menús

- Solo hay **una ubicación de menú**: `menu-1`, «Primary» (`functions.php:72-76`). VERIFICADO. (`MODERNIZATION.md:25` confirma
  que la ubicación `top` ya no existe.)
- Se pinta en la cabecera con `wp_nav_menu` (`header.php:117-123`), sin *walker* propio ni contenedor, y sin menú de
  repliegue (`fallback_cb => false`): si no hay menú asignado, no sale nada.
- **Móvil:** es el **mismo** `<ul>`; `js/navigation.js` lo convierte en panel con submenús desplegables (usa `.menu-item-has-children`
  que añade WordPress, `js/navigation.js:61`) y el CSS cambia a ≤ 767 px. No hay un segundo menú para móvil (VERIFICADO).
- **Pie:** no hay `wp_nav_menu` en `footer.php` (VERIFICADO por `grep`).
- **Mapa web:** `page-webmap.php:25-29` vuelve a pintar el menú principal tal cual.
- **Único filtro sobre menús:** `nav_menu_link_attributes` pone `href="#"` a los elementos sin dirección
  (`inc/template-functions.php:121-128`). No hay filtro que construya el menú ni que genere enlaces a términos.
- **Cómo se enlazan concejalías y temas (INFERIDO):** como elementos del menú de tipo «término» (WordPress calcula la URL
  `/concejalia/<slug>/`) o como «enlaces personalizados» con la dirección escrita. El código no lo distingue y no lo puede
  saber. Ejemplo documentado: «Educación» del menú enlaza a `/concejalia/cultura/` (`MEJORAS-OTROS-AYUNTAMIENTOS.md:110`, fila E2).
- **Menú «Ayuntamiento» de la barra lateral:** `MEJORAS-OTROS-AYUNTAMIENTOS.md:112` dice que existe otro menú
  (`menu-ayuntamiento`), distinto del principal, que se muestra en una barra lateral. El tema registraba `sidebar-ayuntamiento`
  pero **ninguna plantilla la pintaba** (VERIFICADO por `grep`); se retiró en el PR #96. Es código muerto o el menú está en otra barra (INFERIDO: en `sidebar-1`, que sí sale en `page.php:23`).

### Listas de direcciones escritas a mano

| Dónde | Qué | Riesgo |
|---|---|---|
| `inc/audiences.php:23-27` | `/tema/servicios-sociales/`, `salud`, `cultura`, `movilidad-y-transportes`, `consumo-y-comercio` | Si se cambia el slug del tema en WordPress, la tarjeta de «Para ti» da 404 (lo avisa `docs/para-ti.md`) |
| `inc/audiences.php:28-29` | `/concejalia/juventud/`, `/concejalia/urbanismo-y-vivienda/` | Idem |
| `taxonomy-tema.php:28,31-34` | Slugs `tributos`, `concursos`, `oposiciones-y-empleo`, `ayudas-y-subvenciones`, `empleo`, `anuncios`, `oficina-desarrollo-local` | Si el slug cambia, el tema vuelve al diseño de tarjetas y a la barra «Servicios», sin aviso |
| `page-bandos.php:53`, `page-notas-de-prensa.php:53`, `page-subvenciones.php:53` | Categorías `bandos`, `notas-de-prensa`, `subvenciones` | Una categoría renombrada vacía el listado sin error |
| `inc/situations.php:48` | `/tramite/<slug>/` de 17 trámites concretos | Si el trámite cambia de slug, su tarjeta da 404 |
| `inc/situations.php:131,177`, `inc/search.php:106` | `/tramites/` | INFERIDO: depende de cómo esté creada esa página o archivo |

`inc/audiences.php` no comprueba que la dirección exista: construye `home_url()` + la ruta (`inc/audiences.php:37`). Los tests no
llaman a WordPress, así que tampoco lo comprueban (`tests/AudiencesTest.php`).

## 4. El tipo `tramite`

- **Listado:** `page-tramite.php` («Pagina de Trámites») pide los 24 `tramite` más nuevos por ID (`page-tramite.php:20-25`) y
  pinta una tabla Fecha / Gestor / Trámite (`:39-58`). El campo ACF `tramite_gestor` se lee en `:54`. Barra lateral
  `Tramites` (`:33`).
- **Corregido en el PR #95:** antes la plantilla no tenía paginación y solo se veían los 24 últimos. Ahora pide **todos** los
  trámites (sin límite): una página de WordPress pagina con `page` y no con `paged`, así que enlazar páginas habría sido frágil, y
  son pocos. Si llegan a ser cientos, habrá que paginar.
- **Ficha:** `single-tramite.php` (barra lateral `sidebar-tramites`, `:16`) llama a `template-parts/content-tramite.php`.
  Desde el PR #100, si el trámite se publicó hace más de 2 años, la ficha muestra bajo el título el mismo aviso «Contenido antiguo» que
  las noticias (`aranda_de_duero_old_post_disclaimer()`). Hoy los 24 trámites tienen más de 2 años (el más reciente es de enero de 2024),
  así que **todos** lo llevan, también los permanentes; la fecha de publicación no distingue un trámite vigente de uno caducado.
  Los campos de texto de la ficha (`tramite_dirigido`, `tramite_gestor`, `tramite_normativa`, `tramite_pago` y
  `tramite_procedimiento`) guardan HTML del editor y se imprimían con `esc_html()`, es decir, con las etiquetas a la vista. Medido el
  2026-10-20 en los 24 trámites: 23 lo mostraban en «Procedimiento» o «Normativa», 3 en «Gestor» y 1 en «Forma de pago». Desde el
  PR #101 se imprimen con `wp_kses_post()` (el HTML del editor, sin scripts); el listado `page-tramite.php` hace lo mismo con «Gestor».
  Muestra una tabla con: fecha, concejalía, categoría, y los campos ACF `tramite_dirigido`, `tramite_gestor`,
  `tramite_normativa`, `tramite_pago`, `tramite_procedimiento`, `tramite_fichero` (`template-parts/content-tramite.php:64-105`), y el
  contenido normal como «Descripción» (`:82`).
- **Concejalía en la ficha:** se leen los términos con `wp_get_post_terms( $post->ID, 'concejalia' )` (`:33`) y se escriben
  los nombres uno detrás de otro, **sin enlace y sin separador** (`:46-48`). Con dos concejalías saldría «JuventudCultura»
  (VERIFICADO por el código). No lleva a `/concejalia/<slug>/`, y esa página no lista trámites de todos modos.
- **Fichero adjunto:** el enlace «Descargar» se pinta siempre, aunque `tramite_fichero` esté vacío (`:105`); entonces el
  enlace queda vacío (VERIFICADO).
- **Categoría:** la taxonomía `categoria_tramite` solo se muestra como texto (`:34,56-58`).
- **Trámites con página propia en otras piezas:** `inc/situations.php:22-55` lista 17 por su slug, para «Trámites por
  situaciones» y el buscador; `inc/audiences.php` los reutiliza (`inc/audiences.php:85`). Ver [pagos-y-recibos.md](pagos-y-recibos.md)
  y [para-ti.md](para-ti.md). Los campos ACF de arriba los define ACF, no el tema.

## 5. Entradas normales (`post`)

- Una entrada se ve con `single.php` → `template-parts/content-post.php`. Sin barra lateral (`single.php:14-34`).
- **Cabecera de la entrada:** muestra la fecha, el autor y las **categorías de WordPress** (`category`), no las de `tema`
  ni `concejalia` (`template-parts/content-post.php:33-35`, `inc/template-tags.php:54-63`).
- **Pie de la entrada:** `aranda_de_duero_entry_topics_footer()` (`template-parts/content-post.php:68`) muestra los **temas** (`tema`) como
  enlaces «Publicado en <tema>» a `/tema/<slug>/` (`inc/template-tags.php:141-153`), un bloque por tema.
- **Las concejalías no se muestran nunca en una entrada** (VERIFICADO: `get_the_terms`/`wp_get_post_terms` de `concejalia` solo
  aparecen en `template-parts/content-tramite.php:33`). Una entrada con concejalía se alcanza solo desde `/concejalia/<slug>/`.
- Hay otro pie, `aranda_de_duero_entry_footer()` (`inc/template-tags.php:70-105`), usado por `template-parts/content.php:63` y
  `template-parts/content-tramite.php:120`; sólo imprime categorías y etiquetas si el tipo es `post`, así que en el trámite solo sale «Editar».
- Las entradas con el mismo término de `tema` y `concejalia` aparecen en los dos archivos.
- Metadatos sociales: `article:section` usa solo la primera **categoría** (`inc/social-meta.php:146-148`).
- Listados de la portada y de Actualidad: las últimas 9 entradas, **sin filtrar por taxonomía** (`page-home.php:92-97`,
  `page-news.php:27-32`).

## 6. Barras laterales (sidebars) y widgets por contexto

Las siete se registran juntas con el mismo formato (`functions.php:157-186`; VERIFICADO). Un *sidebar* es una zona donde el
administrador pone *widgets* desde *Apariencia → Widgets*. El tema decide **dónde** se pinta cada zona:

| Id (nombre) | Registrada en | Quién la pinta | Contexto |
|---|---|---|---|
| `sidebar-1` («Sidebar») | `functions.php:160` | `sidebar.php:10-16`, llamada desde `page.php:23` e `index.php:59` | Páginas normales y el índice |
| `sidebar-ayuntamiento` («Ayuntamiento») | retirada en el PR #96 | Nadie | Era código muerto; si tenía widgets, quedan en «Widgets inactivos» |
| `sidebar-villa` («Villa») | `functions.php:162` | `page-villa.php:20` (por nombre) | Páginas con la plantilla «Villa de Aranda» |
| `sidebar-noticias` («Noticias») | `functions.php:163` | `search.php:28` | Resultados de búsqueda |
| `sidebar-tramites` («Tramites») | `functions.php:164` | `single-tramite.php:16` (por id), `page-tramite.php:33` (por nombre), `taxonomy-tema.php:44` | Fichas de trámite, listado de trámites y los temas de tipo boletín |
| `sidebar-actualidad` («Actualidad») | `functions.php:165` | `page-multimedia.php:19`; `taxonomy-tema.php:44` (tema `anuncios`) | Multimedia y tema «anuncios» |
| `sidebar-servicios` («Servicios») | `functions.php:166` | `taxonomy-concejalia.php:24`; `taxonomy-tema.php:44` (por defecto) | Todas las concejalías y casi todos los temas |
| `Seccion` | **No existe** | `taxonomy-seccion.php:17` | Siempre vacía |

Detalles:

- El tema mezcla el **id** (`'sidebar-tramites'`) y el **nombre** (`'Tramites'`, `'Servicios'`) al pintar. WordPress admite las dos
  (INFERIDO del código de `dynamic_sidebar`, que también compara el nombre pasado por `sanitize_title`). Convendría unificar
  en ids.
- `body_class` mira siete ids para añadir `no-sidebar` si **ninguno** tiene widgets (`inc/template-functions.php:23-43`);
  el resultado es global, no por página.
- Por defecto, el listado de eventos de My Calendar no se imprime en ninguna barra (`inc/template-functions.php:102-109`).
- **Lo que hay dentro de cada barra** (menús de widget, textos, buscadores) está en la base de datos y no en el código.

## 7. Qué NO se puede saber desde el código

Marcado «no verificable desde el código; lo mide el estudio de la web real»:

1. Qué tipos de contenido tiene cada taxonomía (`post`, `tramite`, `page`, `especial`…).
2. Si `concejalia`, `tema`, `seccion` y `categoria_tramite` son **jerárquicas** (con término padre) o planas.
3. Los **slugs reales** de las rutas (`/concejalia/`, `/tema/`, `/seccion/`, `/tramite/`) y si hay reescritura propia.
4. La lista completa de **términos** de cada taxonomía, cuántas entradas tiene cada uno y cuáles están vacíos.
5. Qué términos tienen imagen de cabecera ACF (`cabecera_de_pagina`).
6. El contenido del **menú principal** (`menu-1`): qué elementos, en qué orden, y si apuntan a términos (tipo «término») o a
   direcciones escritas a mano; cuántos niveles de submenú.
7. Si el menú «Ayuntamiento» (`menu-ayuntamiento`) sigue existiendo y en qué barra o página aparece.
8. Qué widgets hay en cada barra lateral, y en cuál está el menú «Ayuntamiento».
9. Si en el servidor quedan **plantillas viejas** (`taxonomy-concejalia-juventud.php`, `taxonomy-tema-salud.php`…) que
   pisan a las genéricas.
10. Si `/tramites/` es una **página** con la plantilla `page-tramite.php` o un archivo del tipo `tramite`; cuántos trámites
    hay y si se alcanzan más de 24.
11. Si `seccion` se usa en algo hoy y a qué contenido está asignada.
12. Si los slugs de las listas escritas a mano (sección 3) existen hoy.
13. Qué entradas tienen concejalía **y** tema a la vez, y si hay entradas sin ningún término.
14. Qué campos ACF existen realmente y su configuración (el tema solo los lee).
15. Si el plugin CPT UI es el que los define (el repositorio no lo nombra; es una deducción).

## 8. Cómo se haría de otra manera

Todas las opciones respetan las reglas del proyecto (sin JS/CSS/recursos de terceros, PHP 7.4, solo paleta).

| Opción | Ventajas | Inconvenientes |
|---|---|---|
| **A. Seguir como está, pero endurecer** (título en cada archivo, un `else` para el vacío, sacar las listas de slugs a una pieza común, comprobar las direcciones en un test) | Cambios pequeños; no toca la base de datos ni el menú; reversible | Sigue habiendo 3 taxonomías y dos plantillas casi idénticas; las listas de slugs siguen a mano |
| **B. Una sola taxonomía jerárquica** (p. ej. `area`, con concejalías como padre y temas como hijos o viceversa) | Un único menú, un único archivo, una sola URL por tema | Hay que **migrar** los términos de todas las entradas; las direcciones antiguas hay que redirigirlas o romperían buscadores y enlaces externos; CPT UI no migra términos solo; sin entorno de pruebas (CLAUDE.md) es arriesgado |
| **C. Páginas normales con jerarquía padre/hijo** (una página «Juventud» con hijas) | Fácil de editar por el personal; el menú puede salir de la jerarquía; breadcrumbs ya funcionan con ancestros (`inc/structured-data.php:135`) | Pierde el listado automático de noticias por concejalía; hay que mantenerlo a mano o con un bloque de consulta |
| **D. Una plantilla por término de verdad** (`taxonomy-tema-<slug>.php`) | Control total de cada página | Es lo que se evitó: ficheros copiados que se desfasan (`taxonomy-tema.php:6-9`) |
| **E. Datos por término en ACF** (campo «diseño» y «barra lateral» en cada término, en vez de las listas de slugs) | Elimina las listas escritas a mano (`taxonomy-tema.php:28-38`); el ayuntamiento decide sin tocar código | Hay que crear los campos en ACF (la base de datos, no el repo) y dar un valor por defecto |
| **F. Menú dinámico generado desde los términos** (función con `get_terms()` que pinta concejalías y temas) | No se desactualiza: nuevo término, nuevo enlace | Pierde el control de orden y agrupación del menú actual; el menú de WordPress ya es más cómodo para el personal |
| **G. Bloques y patrones** (`inc/block-patterns.php`): un patrón «Lista de noticias de una concejalía» con el bloque Consulta (*Query Loop*) filtrado por término, dentro de páginas normales | Edición visual; sin código por cada concejalía | El tema es clásico: la compatibilidad con *Query Loop* y la maquetación Bootstrap habría que probarla; el filtrado por taxonomía propia depende de que CPT UI la exponga en la API |

Recomendación técnica provisional (no una decisión): A + E. Lo demás son cambios de contenido o de estructura del ayuntamiento y
conviene proponerlos y esperar (CLAUDE.md).

## 9. Dónde tocar para cambiar X

| Tarea | Dónde | Notas |
|---|---|---|
| Añadir una concejalía | *Entradas → Concejalías → Añadir* (WordPress; INFERIDO el nombre del menú, lo pone CPT UI) y asignarla a las entradas | Sale sola en `/concejalia/<slug>/` con `taxonomy-concejalia.php`; no aparece en ningún menú hasta que se añada |
| Quitar una concejalía | Borrar el término en WordPress y quitar su elemento del menú | Si aparece en `inc/audiences.php:28-29`, quitar la fila; el test `tests/AudiencesTest.php` puede fallar |
| Cambiar lo que muestra la página de concejalía | `taxonomy-concejalia.php:28-60` (tarjeta) y `functions.php:768-784` (consulta: tipo, orden, cantidad) | Para incluir trámites, cambiar `post_type` a `array( 'post', 'tramite' )` (`functions.php:771`) |
| Cambiar la imagen de cabecera de una concejalía o tema | Campo ACF `cabecera_de_pagina` del término | `inc/template-tags.php:195-201`; si está vacío, se usa la del Personalizador |
| Añadir la concejalía o el tema al menú | *Apariencia → Menús* (elemento de tipo término o enlace personalizado) | No requiere código; no hay *walker* propio |
| Que la concejalía salga en «Para ti» | Fila nueva en `aranda_de_duero_site_sections()` (`inc/audiences.php:22-30`) y título en `$groups` (`:87-112`) | Escribir el slug exacto; no se comprueba |
| Cambiar el diseño o la barra lateral de un tema | Listas `$table_layout_terms` y `$sidebar_by_term` (`taxonomy-tema.php:28-38`) | Slug exacto; si cambia, vuelve al diseño por defecto |
| Cambiar los widgets de una barra | *Apariencia → Widgets* | No es código; ver la tabla de la sección 6 |
| Cambiar el nombre o el slug de un tema o concejalía | WordPress, y después revisar las listas escritas a mano (sección 3) | Es la causa más probable de 404 silenciosos |
| Mostrar la concejalía en la ficha de una entrada | Nuevo bloque junto a `template-parts/content-post.php:68` y una función hermana de `aranda_de_duero_entry_topics_footer()` (`inc/template-tags.php:136-175`) | Hoy solo se muestra `tema` |
| Enlazar la concejalía en la ficha de un trámite | `template-parts/content-tramite.php:46-48` | Hoy es texto plano sin separador |
| Paginar el listado de trámites | `page-tramite.php:37-61` | Hoy no hay enlaces de paginación, solo 24 |
| Dejar de usar `seccion` | Eliminar `taxonomy-seccion.php` (`dynamic_sidebar('Seccion')` ya no pinta nada) | Confirmar antes con datos reales que ninguna URL `/seccion/…` esté en uso |

Antes de un PR: ejecutar `composer run lint:php`, `lint:wpcs`, `phpstan`, `test` y los linters de JS y CSS (CLAUDE.md), y
actualizar este documento si cambia alguna referencia de línea.

## Índices `/tema/`, `/concejalia/` y `/tramite/` (PR #97)

Esas tres direcciones eran un 404 (CPT UI no registra archivo para ellas). Ahora responden con 200 y un listado
(`inc/term-index.php`, `index-terminos.php`, `template-parts/term-index.php`):

- **Visitantes:** los términos con contenido y de nombre no numérico (o los trámites publicados), cada uno enlazado. Sin `index`
  para buscadores (`noindex`).
- **Administradores** (`manage_options`): todos, con ID, dirección, número de elementos (el de WordPress, de todos los tipos),
  fecha de la última entrada, estado (Vigente / Histórico / Sin entradas / nombre numérico) y enlace «Editar».
- No añade reglas de reescritura (no hace falta vaciar enlaces permanentes): reconoce la petición que WordPress iba a responder
  con 404 por su ruta y la responde con 200. `/tramites/` (plural) sigue siendo la página de la tabla.

## Listado de entradas de concejalías y temas (PR #98)

`template-parts/term-posts.php` (concejalías y temas con diseño de tarjetas) pinta ahora el mismo tipo de tarjeta que `/noticias/`
(`page-news.php`): dos columnas, imagen arriba, **fecha**, título, extracto de 30 palabras y botón «Ver más»; sin imagen, la tarjeta
va sin ella. Antes era una lista de miniatura de 200 px a la izquierda y título. Reutiliza las clases `home-main-news*`, sin CSS
nuevo. El diseño de tabla de los temas (tributos, concursos…) no cambia.

## Ficha de la concejalía: concejal, teléfono, correo y ubicación (PR #106)

La descripción de una concejalía es el campo nativo del término. Los cuatro datos generales son **metadatos del término**
registrados por el tema (`inc/term-contact.php`, sin plugin extra; CPT UI sigue registrando la taxonomía):

| Campo (meta) | Qué es |
|---|---|
| `concejalia_concejal` | Concejal o concejala que lleva el área |
| `concejalia_telefono` | Uno o varios teléfonos separados por `/` (cada uno es un enlace `tel:`) |
| `concejalia_email` | Correo (se descarta si no es una dirección válida) |
| `concejalia_ubicacion` | Dirección o sede |

- Se editan en *Entradas → Concejalías* (al crear y al editar). Un campo vacío no se muestra; sin ningún dato no sale la ficha.
- Salen en la cabecera de cada concejalía (`template-parts/term-heading.php`) y en el índice público `/concejalia/`
  (`template-parts/term-index.php`); en la tabla de administradores hay una columna «Concejal». Pieza común: `template-parts/term-contact.php`.
- Los campos también están en la API REST (`meta`) para rellenarlos en bloque.
- **Los datos hay que mantenerlos:** el concejal y las delegaciones cambian con los pactos y los cambios de gobierno. Cada cierto tiempo
  hay que contrastarlos con `/corporacion-municipal/` (quién lleva qué) y con `/informacion-de-concejalias/` (correo, dirección y teléfono).
  Origen del primer relleno: esas dos páginas el 2026-10-20.

## El directorio de contacto enlaza a cada concejalía (PR #107)

`/informacion-de-concejalias/` es una página normal con una **tabla de TablePress (id 2)** escrita a mano. El tema convierte el
nombre de cada departamento en un enlace a su concejalía (`inc/concejalias-directory.php`, filtro `tablepress_cell_content`).
El emparejamiento está en código (`aranda_de_duero_directory_concejalias()`), por el principio del nombre: Bomberos y Policía →
Seguridad Ciudadana, CEAS → Servicios sociales, Gestión tributaria, Intervención y Tesorería → Hacienda, Informática → Nuevas
Tecnologías, Obras y urbanismo → Urbanismo y vivienda, OMIC → Comercio y Consumo, Recinto ferial → Promoción, Secretaría → Alcaldía,
etc. «Electricidad / alumbrado» queda sin enlace (no hay una concejalía clara). Un departamento nuevo en la tabla no se enlaza hasta
añadirlo a ese mapa. Lo escrito en la tabla no cambia.

## Menú de la izquierda de concejalías y temas: un solo menú (PR #109)

La columna izquierda de `/concejalia/<slug>/` y de la mayoría de `/tema/<slug>/` era la barra «Servicios» con un widget que pintaba el menú
«Servicios municipales» (id 355): una copia vieja del desplegable «Mi ciudad». Ahora pinta los **hijos del elemento del menú principal que
tiene la clase CSS `menu-areas-y-servicios`** («Áreas y servicios»), así que la lista se edita **una sola vez**, en *Apariencia → Menús*
(`inc/areas-menu.php`, `aranda_de_duero_the_areas_menu()`). Sigue usando los estilos de `#menu-servicios-municipales`; la concejalía en la que
estás sale resaltada. Si ningún elemento tiene esa clase, se pinta la barra «Servicios» como antes. Los temas de tabla (tributos, concursos…)
y los de «Actualidad» y «Trámites» mantienen su barra propia. El menú 355 y su widget ya no se usan y se pueden borrar.
