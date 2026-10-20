# Tipos de contenido y elementos propios del tema

Guía para quien no conoce WordPress a fondo: qué piezas «propias» tiene el tema de arandadeduero.es (más allá de las noticias y
las páginas normales), **de dónde sacan sus datos, dónde se ven, quién las edita y dónde en el administrador**, y si podrían
hacerse de otra manera. Complementa a [taxonomias-y-concejalias.md](taxonomias-y-concejalias.md) (concejalías, secciones, temas y
trámites), que **no se repite aquí**. Para lo ya documentado: [portada-diapositivas-con-video.md](portada-diapositivas-con-video.md),
[pagos-y-recibos.md](pagos-y-recibos.md), [para-ti.md](para-ti.md), [pagina-enlaces.md](pagina-enlaces.md) y
[cumplimiento-bloque-a.md](cumplimiento-bloque-a.md).

**Cómo se hizo y cómo leerlo.** Solo se leyó el código del repositorio (nada de la web publicada ni del administrador). Cada
afirmación lleva `fichero:línea`, comprobada leyendo el fichero. Lo **no marcado** es VERIFICADO en el código. Lo marcado
**INFERIDO** es una deducción (de cómo funciona WordPress o de cómo debe de estar la base de datos) que el código no prueba.
Lo marcado **No verificable desde el código** vive en la base de datos o en un plugin y solo se resuelve mirando el
administrador o la web real (lista completa al final).

## En pocas palabras

- **El tema solo registra con código UN tipo de contenido propio: `portada_slide`** (las diapositivas del carrusel,
  `inc/home-carousel.php:28-49`). No hay ningún otro `register_post_type`, `register_taxonomy`, `add_shortcode` ni
  `register_widget` en el tema (búsqueda en todos los `.php`, sin resultados).
- **«Especiales»** (`especial`), **trámites** y las taxonomías los define el plugin *Custom Post Type UI* y sus campos, *ACF*:
  su definición vive en la base de datos. El tema solo **los consume** (`inc/especiales.php:18-42`). **Eventos/agenda** son del plugin
  *My Calendar* (tipo `mc-events`); el tema los lee y los viste (`inc/events.php`, `single-mc-events.php`).
- **Mucho contenido está escrito a mano en PHP**, no en el administrador: los accesos frecuentes, los servicios del buscador, las
  situaciones, los colectivos de «Para ti», el pie de página, los teléfonos y horarios, las fichas de «Barrios…» y del
  «Expediente Plaza de Toros», el banner de Fiestas… Cambiarlos exige editar el tema y volver a desplegarlo (`npm run release`).
- **Hay 19 plantillas de página** (`Template Name:`). Casi todas se eligen en *Páginas → editar → Plantilla*. Tres buscan la página
  por su dirección (slug) exacta y otras dependen de títulos o rutas fijas (ver [plantillas](#plantillas-de-página)).
- **Dependencias ocultas (corregido en el PR #96):** `get_field()` de ACF se llamaba sin comprobar que existiera. Ahora todo pasa por
  `aranda_de_duero_field()` (`inc/template-functions.php`), que devuelve vacío si ACF no está activo; la portada solo pinta el
  Smart Slider de reserva si su shortcode existe (`shortcode_exists`). Probado con un test en un proceso sin ACF; **no** probado con
  el plugin realmente desactivado en la web.
- **Código muerto o sin efecto:** retirados en el PR #96 los dos ajustes del Personalizador sin lector
  (`aranda_de_duero_front_page_video`, `aranda_de_duero_default_list_image`), la barra lateral «Ayuntamiento» y
  `template-parts/content-home.php`. Quedan un tercer ajuste (`…main_section_background`) que solo valida contraste pero no pinta
  nada, y la llamada a una barra «Seccion» no registrada (ver C9 de la propuesta).

## Mapa: elemento → datos → dónde se ve → quién lo edita

| Elemento | De dónde salen sus datos | Dónde se muestra | Quién lo edita y dónde |
|---|---|---|---|
| **Carrusel de la portada** (`portada_slide`) | Tipo registrado por el tema (`inc/home-carousel.php:28`); título, imagen destacada, «Orden», enlace `_aranda_slide_url` y vídeo `_aranda_slide_video` | Arriba de la portada (`page-home.php:21-30`) | Editor del Ayuntamiento: menú **Portada → Diapositivas** (`menu_name` «Portada», `:37`). Ver [doc del vídeo](portada-diapositivas-con-video.md) |
| **Especiales** (`especial`) | Tipo de CPT UI (no está en el repo); imagen destacada, título y campo ACF `especiales_enlace` (`inc/especiales.php:32-38`) | Final de la portada (`page-home.php:314-328`) y como sugerencias del buscador (`inc/search.php:127-147`) | Editor: la entrada del menú que haya creado CPT UI (nombre **No verificable desde el código**) |
| **Agenda / eventos** (`mc-events`) | Plugin My Calendar: entradas con meta `_mc_event_data`, `_mc_event_id` y tabla `{prefijo}my_calendar_events` (`inc/events.php:111,128,155`) | Portada (bloque «AGENDA DE EVENTOS», `page-home.php:234-310`), página Agenda (`page-agenda.php`), ficha del evento (`single-mc-events.php`) | Editor: menú de My Calendar (nombre **No verificable**) |
| **Banner de plenos** | Personalizador: casilla y texto (`functions.php:587-627`) | Portada, entre el carrusel y el buscador (`page-home.php:39-59`) | Administrador: **Apariencia → Personalizar → Aranda de Duero** |
| **Banner de Fiestas** | Escrito a mano: fechas 11-20 de septiembre de 2026 y un enlace (`page-home.php:106-116`) | Portada, sobre «Últimas noticias», solo entre esas fechas | Un desarrollador (código) |
| **Buscador «¿Qué necesitas?»** | Listas en PHP (`inc/services.php`, `inc/search.php`, `inc/situations.php`) + los Especiales | Portada (`page-home.php:60-82`) y resultados de búsqueda (`search.php:22,66-71`) | Un desarrollador (código); los Especiales, el editor |
| **Accesos frecuentes** | `aranda_de_duero_quick_access()` (`inc/services.php:46-114`) | Portada (`page-home.php:84-89`) | Un desarrollador. Ver [pagos-y-recibos.md](pagos-y-recibos.md) |
| **Menú principal** | Base de datos (menú asignado a la ubicación `menu-1`, `functions.php:72-76`) | Cabecera (`header.php:117-124`) y «Mapa web» (`page-webmap.php:25-29`) | Editor: **Apariencia → Menús** |
| **Barras laterales (widgets)** | 7 áreas registradas en código (`functions.php:157-184`); su contenido está en la base de datos | Columnas izquierdas de varias plantillas (tabla más abajo) | Editor: **Apariencia → Widgets** |
| **Pie de página** | Escrito a mano en `footer.php` + enlaces por título de página + un ajuste del Personalizador | Todas las páginas | Un desarrollador; solo el «Canal de información» desde el Personalizador (`functions.php:569-585`) |
| **Datos de contacto** (teléfono, horario, cita previa, transparencia, sede) | `aranda_de_duero_contact_info()` (`inc/template-functions.php:135-146`) | Barra superior (`header.php:30-87`) y pie (`footer.php:95,121`) | Un desarrollador (con filtro `aranda_de_duero_contact_info`) |
| **Colores del Personalizador** | `get_theme_mod` (`inc/customizer-css.php:41-57`) | Todo el sitio, vía variables CSS (`functions.php:634-645`) | Administrador: Personalizar |
| **Imágenes por defecto** (cabecera, compartir) | Personalizador (`functions.php:538-567`) | Cabecera de página (`inc/template-tags.php:196`), etiquetas sociales (`inc/social-meta.php:54-76`) | Administrador: Personalizar |
| **Imagen de cabecera de cada página/término** | Campo ACF `cabecera_de_pagina` | Banda superior de casi todas las plantillas (`inc/template-tags.php:198`) | Editor: campo al editar la página o el término |
| **Bandos, Notas de prensa, Subvenciones** | Entradas normales (`post`) de las categorías `bandos`, `notas-de-prensa`, `subvenciones` | Plantillas del mismo nombre (tabla de plantillas) | Editor: **Entradas** con esa categoría |
| **Datos estructurados, Open Graph, reglas de especulación** | Se generan solos con los datos de la página | Invisibles (código `<head>`) | Nadie: son código |
| **Patrones de bloque** | Dos patrones en `inc/block-patterns.php` | Editor de bloques → Patrones → «Aranda de Duero» | Editor (los inserta); desarrollador (los define) |

## Los Especiales

**Qué son.** Las tarjetas de «contenidos destacados» al final de la portada (PGOU, catastro, calendario fiscal, cita previa…:
`inc/search.php:122-123`, `MEJORAS-OTROS-AYUNTAMIENTOS.md:29`). Antes las pintaba el plugin *Ajax Load More*; ahora las pinta el
tema, sin JavaScript ni llamadas AJAX (`inc/especiales.php:5-8`, `MODERNIZATION.md:98-103`).

**De dónde salen.** Del tipo de contenido `especial` (`inc/especiales.php:21`), **definido por Custom Post Type UI, no por el tema**.
Cada tarjeta usa tres cosas:
- el **título** de la entrada (`:35`);
- la **imagen destacada** (`:37`), que se pinta en tamaño `medium` con `alt=""` (decorativa: el nombre ya lo da el enlace)
  (`:54-62`). **Si no hay imagen, la tarjeta sale solo con el título** (`:67`);
- el campo ACF **`especiales_enlace`** (`:32`): si está vacío, la tarjeta enlaza con la propia entrada (`get_permalink`, `:36`).
  INFERIDO: eso solo funciona si el tipo `especial` es público; si no lo es, ese enlace no llevaría a una página útil.

**Orden y límite.** Las 24 entradas publicadas **más recientes por fecha de publicación** (`:22-25`): `posts_per_page => 24`, orden
`date DESC`. No existe un campo «orden»: para subir un Especial hay que cambiar su fecha de publicación en el editor. La
sección **no pagina**: pinta todas las que hay (`:78-86`). El campo antiguo `mostrar_en_home` **ya no se usa**
(`MODERNIZATION.md:100-101`), así que marcarlo no cambia nada.

**Imagen y carga.** Las seis primeras tarjetas se cargan al momento y el resto con carga diferida (`:83`: `$index >= 6`).
La tarjeta entera es un solo enlace «estirado» sobre el título (`:51,68`). **Los Especiales se abren en la misma pestaña**
(la tarjeta no lleva `target`, `:68`), aunque apunten a otro dominio.

**Buscador.** `aranda_de_duero_search_especiales()` (`inc/search.php:127-147`) convierte cada Especial en una entrada buscable
con descripción fija «Destacado en la portada.» y **sin palabras clave** (`''`, `:141`): el buscador solo los encuentra por las
palabras del **título**. Si el enlace es de otro dominio, la sugerencia se abre en pestaña nueva (`:142`). Además se mezclan en
el «pool» general (`:182`) y se eliminan los duplicados por URL (`:184-189`).

**Cosas a saber.**
- La sección **sale siempre**, también con cero Especiales: el encabezado «Especiales» y su frase no dependen de que haya
  tarjetas (`page-home.php:314-327`, sin condición).
- La consulta `get_posts` **no se cachea**: se ejecuta en cada carga de la portada y cada vez que se construye el buscador
  (`inc/search.php:182,239`). INFERIDO: poco coste (24 filas), y la caché de página de Cache Enabler lo amortigua.
- Solo hay página propia de lista si el tipo `especial` tiene archivo. El tema **no tiene `archive-especial.php`**; si lo tuviera
  activado, usaría `archive.php` genérico. **No verificable desde el código.**

**Cómo se edita (INFERIDO, depende de CPT UI).** En el administrador, la entrada de menú del tipo `especial` → «Añadir nuevo»:
título, imagen destacada, campo «enlace» (`especiales_enlace`), Publicar. Vaciar la caché de Cache Enabler (regla del proyecto).

**¿Otra manera?** Ver [Cómo se haría de otra manera](#cómo-se-haría-de-otra-manera).

## El carrusel de la portada

Ya documentado en [portada-diapositivas-con-video.md](portada-diapositivas-con-video.md). Lo que ese documento no cuenta o conviene
recordar del código:

- **Es el único tipo de contenido que registra el tema** (`inc/home-carousel.php:28-49`): `public => false` (sin direcciones
  públicas, `:39`), `show_in_rest => false` (`:41`, por lo que **se edita con el editor clásico, no con bloques**) y soporta
  título, imagen destacada y «atributos de página» (el campo «Orden», `:45`).
- **Orden:** número «Orden» ascendente y, a igualdad, el título alfabético (`:176-179`). **Máximo 12** diapositivas (`:175`).
- **Una diapositiva sin imagen destacada se omite sin avisar** (`:186-189`); por eso el doc la declara obligatoria.
- **El título de la diapositiva es el texto alternativo de la imagen** (`:226`): conviene que describa la imagen, no que sea «Diap 3».
- **Meta boxes:** una sola caja «Enlace y vídeo de la diapositiva» (`:54-63`), con el enlace opcional (`_aranda_slide_url`) y la
  lista de vídeos MP4 de la biblioteca de medios (hasta 100, los más nuevos, `:98-116`). Se guarda con comprobación de «nonce»
  y permisos (`:138-163`).
- **Dependencia oculta (plan B):** mientras no haya ninguna diapositiva publicada, la portada vuelve a pintar el plugin
  **Smart Slider 3** con el shortcode fijo `[smartslider3 slider="2"]` (`page-home.php:26-30`), y lo retoca con una función de
  `inc/performance.php:131-160` (que además mete un `<script>` en línea, `:157`). Si no hay diapositivas **y** Smart Slider está
  desactivado, INFERIDO: WordPress dejaría el texto `[smartslider3 slider="2"]` visible en la página.
- El script del carrusel solo se carga si hay carrusel (`page-home.php:23`, `inc/home-carousel.php:264-283`).

## Eventos y agenda (My Calendar)

**Quién manda.** El plugin *My Calendar* define el tipo `mc-events`, la taxonomía `mc-event-category`, la tabla
`{prefijo}my_calendar_events` y el calendario en sí. **El tema no registra nada**; solo lee y presenta. El tema da por hecho estos
nombres del plugin: `mc-events` (`inc/events.php:96`, `functions.php:257`), `_mc_event_data` con las claves `event_begin`,
`event_end` y `event_time` (`inc/events.php:111-118`, `inc/structured-data.php:101-111`), `_mc_event_id` (`:128`), la tabla
`my_calendar_events` con `occur_event_id`/`occur_id` (`:155`), el parámetro `?mc_id=` (`:166`) y los widgets
`my_calendar_upcoming_widget` y `my_calendar_today_widget` (`inc/template-functions.php:80-85`). Si el plugin cambia esos nombres, la
portada dejaría de listar eventos sin error visible (la consulta SQL fallaría en silencio, INFERIDO).

**Qué personaliza el tema** (cinco cosas):
1. **«AGENDA DE EVENTOS» en la portada** (`page-home.php:234-310`): muestra hasta **4 eventos** (`:273`) más el cartel del primero.
   Los datos vienen de `aranda_de_duero_get_upcoming_events( 50 )` (`:236`).
2. **Caché de 10 minutos** (`inc/events.php:56`), que se vacía sola al guardar, borrar o enviar a la papelera un evento
   (`:81-83`, `:65-69`). Los límites usados se guardan en una opción (`:29-35`).
3. **La página Agenda** (`page-agenda.php`): cabecera con introducción y tres consejos (`:33-43`), y luego `the_content()` (`:49`).
   **El calendario lo pinta lo que haya escrito en el contenido de la página** (probablemente un shortcode de My Calendar,
   **No verificable desde el código**).
4. **La ficha de un evento** (`single-mc-events.php:23` → `template-parts/content-mc-events.php`): enlace «Agenda» arriba, categorías
   como etiquetas (`:26-34`), título y el contenido que genera el plugin. Un script pequeño (`js/event-poster.js`) abre el cartel
   ampliado y lo deja descargar; solo se carga en la ficha (`functions.php:256-277`; el cartel original sale de
   `template-parts/content-mc-events.php:16`).
5. **Retoques menores:** los widgets «próximos eventos» y «hoy» **no se imprimen en las barras laterales** salvo que un filtro lo
   permita (`inc/template-functions.php:102-109`); la cadena inglesa «Clear Filters» se traduce a «Quitar filtros» (`:169-180`);
   datos estructurados `Event` de schema.org (`inc/structured-data.php:100-119`).

**Sorpresas y fragilidades (verificadas en el código):**
- **El recuadro grande de fecha de la agenda de la portada es el día de HOY, no el del primer evento:** usa `date_i18n( 'l' )`, `'d'`, `'F'`
  sin marca de tiempo (`page-home.php:264-266`), bajo el comentario «Current Date Display», así que parece intencionado. Lo que sobra es
  `$event_time`, que se calcula y no se usa (`:240`). Si se esperaba ver la fecha del evento, no es lo que hace.
- **Solo salen eventos cuya fecha de inicio está en el año actual o el siguiente y que no han terminado**
  (`inc/events.php:124`). Un evento que empezó el año pasado y sigue en curso, o una serie recurrente iniciada antes (INFERIDO: se
  lee `event_begin` de la serie), no saldría.
- **Se leen solo 50 entradas** (`:96`, límite pasado a `aranda_de_duero_content`, `functions.php:715-726`) sin orden explícito
  (INFERIDO: WordPress usa fecha de publicación descendente), y luego se filtran; con más de 50 entradas de eventos, las más
  antiguas por publicación (aunque tengan fecha futura) podrían quedar fuera.
- **La dirección `/agenda/` está escrita a mano** (`template-parts/content-mc-events.php:29`, `inc/search.php:96`): la página Agenda debe tener ese
  slug. En el pie, en cambio, el enlace «Agenda municipal» se busca por el **título** «Agenda» (`footer.php:45`).
- La consulta SQL directa a la tabla del plugin (`inc/events.php:153-157`) tiene permiso explícito de `phpcs`: es la única vía, el
  plugin no ofrece función para ello.

## Plantillas de página

Una plantilla se elige en *Páginas → editar → (barra lateral) Plantilla*. **Cuál tiene cada página real es un dato de la base de
datos: No verificable desde el código** (salvo lo que dicen los documentos del repositorio). «Slug» es la dirección de la página.

| Nombre en WordPress | Fichero | Para qué sirve | ¿Slug/título concreto? | Qué datos usa y de dónde | Doc propia |
|---|---|---|---|---|---|
| Pagina de Inicio | `page-home.php:4` | La portada | No lo exige el código; es la que se asigna a la página de inicio | Carrusel (`portada_slide`), Personalizador (banner), `inc/search.php`, `inc/services.php`, 9 entradas (`:92-98`), My Calendar (`:236`), Especiales (`:325`) | [portada-diapositivas…](portada-diapositivas-con-video.md), [pagos-y-recibos](pagos-y-recibos.md) |
| Plantilla Agenda | `page-agenda.php:3` | Calendario de actos | **Sí: slug `agenda`** (`template-parts/content-mc-events.php:29`, `inc/search.php:96`) | Contenido de la página (calendario de My Calendar) | No |
| Plantilla Ayuntamiento | `page-ayuntamiento.php:3` | Página con cabecera, título y contenido, **sin barra lateral** | No | Contenido de la página; cabecera ACF/Personalizador (`:14`) | No |
| Página de Bandos | `page-bandos.php:4` | Lista de bandos (12 por página, `:45`) | Slug `bandos` esperado por el buscador (`inc/search.php:97`) | Entradas con categoría `bandos` (`:53`); imagen de reserva fija `uploads/2025/11/Copia-de-Banner-ayuntamiento-5-300x107.png` (`:86`) | No |
| Barrios, Propuestas y Respuestas | `page-barrios-propuestas-respuestas.php:4` | Ficha del proceso de escucha vecinal | No | **Todo escrito a mano**: lista `$barrios` con 12 PDF y fechas (`:15-77`); **ignora el contenido de la página** (no llama a `the_content`) | No |
| Plantilla de enlaces (sin cabecera) | `page-enlaces.php:3` | Página «link en la bio» | Slug `enlaces-de-interes` (`inc/search.php:103`) | Lista `$aranda_enlaces` en el propio fichero | [pagina-enlaces.md](pagina-enlaces.md) |
| Expediente Plaza de Toros | `page-exp-plaza-de-toros.php:4` | Expediente de «La Chata» | No | **Todo escrito a mano** (textos y rutas a PDF de `uploads/2026/…`, `:16-`); título propio, no el de la página | No |
| Página legales | `page-legales.php:3` | Textos legales a ancho completo | No (el pie busca por título «Aviso legal», «Política de Privacidad», «Política de Accesibilidad»: `footer.php:199,205,211`) | Contenido de la página | No |
| Página de multimedia | `page-multimedia.php:3` | Contenido con barra lateral «Actualidad» | No | Contenido; barra `Actualidad` (`:19`) | No |
| Página de Actualidad | `page-news.php:4` | Lista de noticias (9 por página) | La portada enlaza a la página titulada **«Noticias»** + `page/2/` (`page-home.php:223`) | Entradas (`aranda_de_duero_content`, `:27-32`). Reserva columna de 9 pero **sin barra lateral** (`:24`) | No |
| Página de Notas de Prensa | `page-notas-de-prensa.php:4` | Lista de notas (12 por página, `:45`) | Slug `notas-de-prensa` (`inc/search.php:98`) | Entradas de categoría `notas-de-prensa` (`:53`) | No |
| Plantilla Pagos y recibos | `page-pagos.php:3` | Servicios de pago de la sede | **Sí: slug `pagos-y-recibos`** (`inc/services.php:32`); si no existe, el atajo va a la pasarela de la sede (`:38`) | `aranda_de_duero_payment_services()` (`inc/services.php:123`) | [pagos-y-recibos.md](pagos-y-recibos.md) |
| Plantilla Para ti | `page-para-ti.php:3` | Servicios por colectivos | **Sí: slug `para-ti`** (`inc/audiences.php:74`) | `inc/audiences.php` | [para-ti.md](para-ti.md) |
| Página sin sidebars | `page-sinsidebars.php:3` | Página normal a ancho completo | No | Contenido. **Idéntica a «Página legales»** salvo el nombre | No |
| Página de Subvenciones | `page-subvenciones.php:4` | Lista de ayudas (12 por página, `:45`) | Slug `subvenciones` (`inc/search.php:99`) | Entradas de categoría `subvenciones` (`:53`); misma imagen de reserva (`:86`) | No |
| Pagina de Trámites | `page-tramite.php:3` | Tabla de trámites | Buscador y atajos esperan `/tramites/` (`inc/search.php:106`) | CPT `tramite` (`:20-25`), **todos los trámites en una tabla (antes, solo los 24 más nuevos; PR #95)**; ACF `tramite_gestor` (`:54`); barra `Tramites` (`:33`). Ver [taxonomías](taxonomias-y-concejalias.md) | Parcial |
| Plantilla Trámites por situaciones | `page-tramites-situaciones.php:3` | Trámites por situación vital | **Sí: slug `tramites-por-situaciones`** (`inc/situations.php:63`) | `inc/situations.php`; las fichas `/tramite/<slug>/` están fijadas a mano (`:22-41`) | No (se cita en [para-ti.md](para-ti.md)) |
| Plantilla Villa de Aranda | `page-villa.php:4` | Contenido con barra lateral «Villa» | No | Contenido; barra `Villa` (`:20`) | No |
| Página de mapa web | `page-webmap.php:3` | Mapa del sitio | El pie busca por título **«Web Map»** (`footer.php:226`) | El menú de la ubicación `menu-1` (`:25-29`) | No |

Además existen plantillas **automáticas** (no se eligen): `page.php` (páginas normales, con barra lateral `sidebar-1`, `:23`),
`single.php`, `single-tramite.php`, `single-mc-events.php`, `archive.php`, `search.php`, `404.php`, `index.php`, y
`taxonomy-concejalia|seccion|tema.php` (ver [taxonomías](taxonomias-y-concejalias.md)).

## Menús, barras laterales, widgets

**Menú.** Una sola ubicación: `menu-1`, visible como «Primary» (`functions.php:72-76`), usada en la cabecera (`header.php:117-124`,
sin menú → no pinta nada, `fallback_cb => false`) y en el Mapa web. Los **elementos del menú son datos** (Apariencia → Menús), no
código. Un filtro da `href="#"` a los enlaces vacíos para que sean alcanzables con teclado (`inc/template-functions.php:121-128`).

**Barras laterales.** Siete áreas (`functions.php:159-167`): `sidebar-1` «Sidebar»,
`sidebar-villa` «Villa», `sidebar-noticias` «Noticias», `sidebar-tramites` «Tramites», `sidebar-actualidad` «Actualidad» y
`sidebar-servicios` «Servicios». Qué se ve en cada plantilla:

| Barra | Dónde se imprime |
|---|---|
| `sidebar-1` | `page.php:23` y `index.php:59` (vía `sidebar.php:10-16`; si está vacía no se pinta) |
| «Villa» | `page-villa.php:20` |
| «Actualidad» | `page-multimedia.php:19`; temas `anuncios` (`taxonomy-tema.php:32`) |
| «Tramites» / `sidebar-tramites` | `page-tramite.php:33`, `single-tramite.php:16`, temas de tabla (`taxonomy-tema.php:28-37`) |
| «Servicios» | `taxonomy-concejalia.php:24` y la de reserva de los temas (`taxonomy-tema.php:38`) |
| `sidebar-noticias` | `search.php:28` |
| `sidebar-ayuntamiento` | Retirada en el PR #96: no la pintaba ninguna plantilla |
| «Seccion» | `taxonomy-seccion.php:17` la llama, pero **no está registrada** en `functions.php:159-167`: no pinta nada |

Las plantillas llaman a veces a la barra por su **nombre** («Villa») y a veces por su **id** (`sidebar-tramites`); INFERIDO: WordPress
acepta ambos, por eso funciona. El contenido de cada barra (qué widgets hay) es de la base de datos: **No verificable**.
**No hay widgets propios** (ni `register_widget`); solo se usan los de WordPress o de plugins.

## Personalizador y cabecera

**Qué hay en *Apariencia → Personalizar → Aranda de Duero*** (`functions.php:429-436,437-627`):
- Seis **colores** (fondo, encabezados, texto, enlaces, fondo y texto de «secciones principales»), con comprobación de contraste
  WCAG AA al guardar (`validate_callback`, `:474`; lógica en `inc/color-contrast.php`). Por defecto todos son `#ffffff`/`#000000`
  (`inc/customizer-css.php:22-31`).
- **Javascript personalizado** (`:490-507`), que se ejecuta en **todas las páginas** (`:676-701`); solo puede editarlo quien tenga
  `unfiltered_html` (`:410-422`).
- Imágenes: **por defecto de listados** (`:523-536`), **de cabecera** (`:538-551`, la usa `inc/template-tags.php:196` y
  `inc/social-meta.php:66`) y **para compartir en redes** (`:553-567`, `inc/social-meta.php:54`).
- **URL del canal de información** (Ley 2/2023, `:569-585`): si se rellena, aparece el enlace en el pie (`footer.php:215-223`).
- **Banner de plenos**: casilla y texto (`:587-627`), que pintan el bloque de la portada (`page-home.php:39-59`).
- Vídeo de la portada (`:508-521`).

**Ajustes sin efecto (código muerto, VERIFICADO por búsqueda):**
- `aranda_de_duero_front_page_video` y `aranda_de_duero_default_list_image`: se registran en el Personalizador y **ningún fichero los
  lee** (solo aparecen en `functions.php`).
- `aranda_de_duero_main_section_background` (fondo de «secciones principales»): solo se usa para comprobar el contraste
  (`inc/color-contrast.php:83`); **no se imprime como variable CSS** (`inc/customizer-css.php:44-48` no lo incluye) ni hay regla en
  `css/aranda-de-duero.css`. El color de texto de esas secciones sí funciona (`css/aranda-de-duero.css:1585`).

**Cómo llegan los colores a la página.** `aranda_de_duero_customize_css()` imprime un `<style>` con variables `--aranda-*`
(`functions.php:634-645`); las reglas están en `css/aranda-de-duero.css:121-160`. Es una de las dos excepciones prácticas a la regla
«ningún `<style>`» (la otra es `inc/custom-header.php:61-65`): los valores salen de la base de datos y no pueden estar en el CSS.
Un `<script>` en línea acompaña al JS del Personalizador (`functions.php:678`).

**Cabecera personalizada (`inc/custom-header.php`).** Registra la función de WordPress `custom-header` (`:19-35`) y el color del título
del sitio; **la imagen que se ve arriba de cada página no es esa**, sino `aranda_de_duero_the_header_image()`
(`inc/template-tags.php:177-212`): campo ACF `cabecera_de_pagina` si existe (`:198`) y, si no, la imagen por defecto del Personalizador
(`:196`). Si no hay ninguna de las dos, el `<img>` sale con `src=""` (no hay comprobación, `:206`). El logotipo es el logotipo
personalizado de WordPress (`header.php:96`).

**Relacionado:** concejalías y temas heredan la imagen de cabecera de su propio término
([taxonomias-y-concejalias.md](taxonomias-y-concejalias.md)).

## Patrones de bloque

`inc/block-patterns.php` registra la categoría «Aranda de Duero» (`:17-31`) y **dos patrones** (`:33-81`):
«Aviso informativo con icono» (`:41-59`) y «Tarjeta con icono y enlace» (`:61-78`). Se insertan desde el editor de bloques →
Patrones. Ambos son un bloque «HTML personalizado» con clases de Bootstrap: sirven para copiar la maqueta, pero **el editor no puede
modificar el aviso con los controles de bloques**. Los iconos dependen de la API de iconos de WordPress 7.1: con una versión
anterior, `aranda_de_duero_icon()` devuelve vacío (`inc/icons.php:14-30`) y no hay error.

No hay `parts/` ni `templates/` de bloques: es un **tema clásico** con `theme.json` solo para paleta, tipografía (Outfit) y espaciados.

## Datos estructurados, metaetiquetas sociales y reglas de especulación

| Pieza | Qué hace | Dónde se ve | Límites |
|---|---|---|---|
| **JSON-LD** (`inc/structured-data.php:33-177`) | Grafo `GovernmentOrganization` + `WebSite` (con buscador) en todas las páginas; `NewsArticle` en noticias (`:84-99`), `Event` en eventos (`:100-119`), `WebPage` en el resto y migas de pan para páginas y noticias (`:132-152`) | Invisible, en el `<head>` | Se omite en 404, búsqueda o si hay un plugin SEO (`:162`). Los perfiles sociales están **escritos a mano** (`:15-26`) |
| **Open Graph y Twitter Card** (`inc/social-meta.php:107-172`) | Título, descripción, imagen, tipo | Al compartir en redes | Imagen: destacada → ACF `cabecera_de_pagina` → imagen para compartir → imagen de cabecera (`:30-79`). `@aytoaranda` fijo (`:163`). Se apaga si hay plugin SEO (`:15-20`) o Jetpack (`:175`) |
| **Canonical de archivos** (`inc/social-meta.php:85-102`) | `rel=canonical` en categorías, etiquetas y taxonomías | Invisible | — |
| **Reglas de especulación** (`inc/speculation-rules.php:21-31`) | Cambia la precarga de WordPress a *prerender* «moderado» | Invisible; las páginas se cargan al pasar el ratón | INFERIDO: afecta a contadores de visitas/analítica (se cuentan páginas no abiertas) |
| **Rendimiento** (`inc/performance.php`) | Quita emojis y Font Awesome del plugin; marca PDF incrustados | Invisible | Depende de los nombres de estilo del plugin Font Awesome (`:57-63`) |
| **Jetpack** (`inc/jetpack.php`) | Soporte para desplazamiento infinito, vídeos adaptables y opciones de contenido | Solo si Jetpack está activo (`functions.php:404-406`) | INFERIDO: probablemente sin uso hoy |

## Listas escritas a mano (servicios, situaciones, colectivos, buscador)

Todo es **código PHP**: para cambiar un enlace hay que editar y desplegar. Cada lista tiene su filtro para modificarla desde un plugin
propio (`aranda_de_duero_quick_access`, `…_payment_services`, `…_situations`, `…_audiences`, `…_search_suggestions`).

| Lista | Fichero:línea | Qué contiene | Se ve en |
|---|---|---|---|
| Accesos frecuentes | `inc/services.php:46-114` | 8 atajos (cita previa, pagos, padrón…) | Portada, buscador |
| Servicios de pago | `inc/services.php:123-211` | Recibos, multas, liquidaciones, calendario fiscal | «Pagos y recibos», buscador |
| Directorio de servicios | `inc/search.php:52-119` | 38 entradas fijas (sede, portales y secciones) más los trámites y las páginas «Para ti»/«Situaciones» | Buscador (no se muestra como lista) |
| Trámites del portal | `inc/situations.php:22-55` | 17 trámites con su slug `/tramite/<slug>/` | Situaciones, «Para ti», buscador |
| Situaciones | `inc/situations.php:99-165` | 7 grupos | «Trámites por situaciones» |
| Colectivos | `inc/audiences.php:84-137` | 6 grupos | «Para ti» |
| Secciones del sitio | `inc/audiences.php:21-50` | 7 rutas `/tema/…`, `/concejalia/…` y Deportes | «Para ti» |
| Sugerencias «Prueba con» | `inc/search.php:328-335` | `padrón, recibos, multas, empleo, cita previa` | Portada |
| Contacto | `inc/template-functions.php:135-146` | Teléfono 947 500 100, horario, 3 URL | Cabecera, pie |
| Pie de página | `footer.php:16-235` | Dirección, correo, redes, textos | Todas las páginas |

**Fragilidades de estas listas (verificadas):**
- Las **rutas de secciones** (`/tema/salud/`, `/concejalia/juventud/`…) y los **slugs de trámites** (`inc/situations.php:24-40`) están
  fijados: si alguien cambia el slug en el administrador, la tarjeta da 404 (ya lo avisa [para-ti.md](para-ti.md)).
- Las entradas que se piden **por título exacto** (`aranda_de_duero_service_by_title`, `inc/situations.php:74-92`) se omiten en
  silencio si el título cambia.
- **El pie y el botón «Más actualidad» buscaban páginas por título exacto (corregido en el PR #96):** ahora usan el slug con
  `aranda_de_duero_page_url()` (`inc/template-functions.php`): `agenda`, `subvenciones`, `aviso-legal`, `politica-privacidad`,
  `politica-de-accesibilidad`, `web-map` y `noticias`. El enlace «Ayudas y subvenciones» del pie salía vacío (buscaba el título «Ayudas»)
  y ahora apunta a `/subvenciones/`. Si una página se borra o se despublica, su enlace no se pinta vacío: sale `''`, y el botón de
  noticias de la portada se oculta.
- La portada enlaza a «Más actualidad» buscando la página titulada «Noticias» (`page-home.php:223`); sin ella queda `page/2/` relativo.
- **Banner de Fiestas:** las fechas están fijadas a septiembre de 2026 (`page-home.php:107`); en 2027 no aparecerá hasta que alguien
  lo cambie, y su enlace `https://fiestas.arandadeduero.dev/` es un dominio distinto (`:111`). Hoy (2026-10-20) está inactivo.
- **Banner de plenos:** las imágenes `webcamIcon.png` y `playIcon.png` salen sin `alt` (`page-home.php:47,52`).
- `template-parts/content-home.php` no se usaba en ningún sitio: borrado en el PR #96.

## Plugins y dependencias

Solo se afirma lo que el código demuestra; **qué plugins están instalados o activos hoy no se puede saber desde el código**.

| Plugin / servicio | Qué usa el tema de él | Qué se rompe si se desactiva (INFERIDO salvo indicación) |
|---|---|---|
| **ACF (Advanced Custom Fields)** | `get_field()` para `cabecera_de_pagina` (`inc/template-tags.php:198`, `inc/social-meta.php:44`) y los campos de trámite (`template-parts/content-tramite.php:64-105`, `page-tramite.php:54`); `especiales_enlace` se lee con `get_post_meta` (`inc/especiales.php:32`), no con ACF | **Error fatal** en casi todas las páginas (la cabecera se llama en la mayoría de plantillas). `get_field` no está protegido con `function_exists` (VERIFICADO) |
| **Custom Post Type UI (CPT UI)** | Define `especial`, `tramite` y las taxonomías `concejalia`, `seccion`, `tema`, `categoria_tramite` (el tema las usa: `inc/especiales.php:21`, `functions.php:757-761,776-780`, `template-parts/content-tramite.php:33-34`) | Desaparecen Especiales, trámites y listados; las plantillas de taxonomía darían 404 o vacías. Sin error fatal |
| **My Calendar** | Tipo `mc-events`, meta `_mc_event_data`, tabla propia, widgets, shortcode del calendario (en el contenido de Agenda) | Portada sin agenda; página Agenda vacía; fichas de evento 404. La consulta directa a la tabla (`inc/events.php:153-157`) no se protege si falta la tabla |
| **Smart Slider 3** | Solo como plan B: `[smartslider3 slider="2"]` (`page-home.php:29`) | Nada mientras haya diapositivas propias; sin ellas, el shortcode saldría como texto |
| **Jetpack** | Compatibilidad opcional (`functions.php:404`, `inc/jetpack.php`); se desactiva su Open Graph (`inc/social-meta.php:175`) | Nada |
| **Plugins SEO** (Yoast, Rank Math, AIOSEO, SEOPress) | Si se detecta uno, el tema deja de imprimir Open Graph y JSON-LD (`inc/social-meta.php:15-20`, `inc/structured-data.php:162`) | Se recuperan las etiquetas del tema |
| **Font Awesome (plugin)** | El tema le quita sus estilos (`inc/performance.php:57-82`) | Nada: ya no se usan |
| **Cache Enabler, Site Kit, aviso de cookies** | No se invocan desde el código del tema; los cita la documentación (`CLAUDE.md`, `pagina-enlaces.md`) | **No verificable desde el código** |
| **WordPress 7.1 (API de iconos)** | `wp_get_icon`, `wp_register_icon_collection` (`inc/icons.php:14-45`), con protección `function_exists` | Sin iconos, pero sin error |
| **Servicios externos (solo enlaces)** | `sede.arandadeduero.es`, `citaprevia.`, `transparencia.`, `turismo.`, `asociaciones.`, `fiestas…dev`, `arandactiva.com`, `face.gob.es`, Flickr y redes | Enlaces rotos si cambian; ningún recurso se carga de terceros en el navegador |

## Qué NO se puede saber desde el código

1. Si el tipo `especial` es **público** (tiene página propia, archivo) y su **nombre de menú** en el administrador.
2. Los **campos ACF** exactos y a qué tipos/taxonomías están asignados (`especiales_enlace`, `cabecera_de_pagina`, `tramite_*`).
3. Qué **plugins están activos** hoy, y si Smart Slider 3, Ajax Load More y Elementor siguen instalados.
4. Qué **plantilla tiene realmente cada página** y qué **slug**/título tienen las páginas que el código busca (`agenda`, `bandos`,
   `notas-de-prensa`, `subvenciones`, `enlaces-de-interes`, `Noticias`, `Agenda`, `Ayudas`, `Web Map`…).
5. Qué contiene la página **Agenda** (shortcode de My Calendar) y si sus botones iCal/Google vienen del plugin (`page-agenda.php:42`).
6. Qué **widgets** hay en cada barra lateral y cuál es el **menú** asignado a `menu-1`, con sus elementos.
7. Si hay más de 24 Especiales, más de 50 eventos o más de 12 diapositivas ya creados (los límites del código cortarían el resto).
8. Qué **valores** tienen los ajustes del Personalizador (banner de plenos activo, canal de información, imágenes por defecto, JS).
9. Cuántos **eventos recurrentes** tiene My Calendar y cómo guarda `event_begin` en ellos (afecta a la lista de la portada).
10. Si Cache Enabler respeta `?sin-cache=x` o cachea la portada con el banner de plenos activado (regla del proyecto, no del código).

## Cómo se haría de otra manera

Respetando las reglas del proyecto (`CLAUDE.md`: español, sin JS/CSS/recursos de terceros, sin CSS en línea, solo la paleta, PHP 7.4).

| Elemento | Alternativa real | A favor | En contra |
|---|---|---|---|
| **Especiales** | (a) **Menú de WordPress** «Especiales» (Apariencia → Menús) y pintarlo con `wp_nav_menu` y un *walker* propio | El editor reordena arrastrando; no depende de CPT UI/ACF; enlaces externos nativos | Pierde la imagen destacada por tarjeta (habría que usar un campo del menú); el menú no es un «contenido» con fecha |
| | (b) **Mantener el CPT pero registrarlo en el tema** (`register_post_type` + `register_post_meta`/meta box propios) | El tipo viaja con el tema y se prueba; se puede añadir «Orden» (`menu_order`) como en el carrusel | Hay que migrar los Especiales existentes; sale de CPT UI/ACF y cambia el flujo del editor |
| | (c) **Patrón de bloque o bloque «Cuadrícula de enlaces»** en la página de inicio | Todo se edita en la propia página, sin PHP | La portada pasaría a ser una página de bloques; el buscador no podría leer los Especiales sin analizar bloques |
| | (d) Añadir el campo **«Orden»** al CPT actual | Cambio mínimo: `orderby menu_order` en `inc/especiales.php:24` | Requiere activar «atributos de página» en CPT UI |
| **Carrusel** | Ya es la alternativa buena (tipo propio, sin plugin). Mejora: **bloque de galería/portada** | — | Perdería el vídeo con sincronía y el control de reproducción |
| **Eventos** | (a) **Tipo propio `evento`** con fecha de inicio/fin, y adiós a My Calendar | Control total, sin SQL ajeno, fechas correctas | Se pierde calendario, iCal, recurrencias y categorías: es mucho trabajo; decisión del ayuntamiento |
| | (b) **Dejar My Calendar** y arreglar la portada: mostrar la fecha **del evento**, no la de hoy; contar eventos en curso | Corrige el fallo `page-home.php:264-266` y `inc/events.php:124` | Sigue la dependencia del plugin |
| **Banner de plenos / Fiestas** | Pasar ambos al Personalizador con **fechas** de inicio/fin (o a un tipo «Aviso» con caducidad) | Se acaban los cambios de código cada año | Más campos que mantener |
| **Listas de servicios** (`inc/services.php`, `situations`, `audiences`) | (a) **Menús de WordPress** por grupo; (b) un **tipo de contenido «Servicio»** con campos | El personal municipal las edita sin desplegar | Más superficie de administración y de errores (enlaces a trámites que cambian de slug); (a) pierde las palabras clave del buscador |
| **Pie de página** | Dos **menús** (servicios, legales) + **barra lateral de widgets** para contacto | Sin buscar páginas por título; editable | Pierde el control exacto del diseño |
| **Plantillas «a mano»** (Barrios, Plaza de Toros) | **Páginas normales** con patrones de bloque «Tarjeta con icono y enlace» (ya existe: `inc/block-patterns.php:61-78`) | El editor publica sin desplegar; cabecera y pie comunes | Menos control del diseño; los PDF se enlazan a mano |
| **Bandos / Notas / Subvenciones** | Una sola plantilla **«Lista por categoría»** que lea la categoría de un campo | Elimina tres ficheros casi idénticos | Un cambio en la base del diseño afecta a las tres |
| **Páginas legales y sin barras** | Unificarlas en una (son idénticas: `page-legales.php` = `page-sinsidebars.php`) | Menos ficheros | Hay que reasignar la plantilla en las páginas que la usen |
| **Barras laterales** | Quitar `sidebar-ayuntamiento` y «Seccion» (sin uso) | Menos confusión | Si el editor ya puso widgets en ellas se perderían de la vista |
| **Personalizador** | Borrar `front_page_video`, `default_list_image` y `main_section_background` (sin efecto) o darles un lector | Quita opciones engañosas | Es un cambio visible para quien las tenga rellenas |
| **JS personalizado del Personalizador** | Quitar el campo y llevar el código al tema | Evita JS de terceros por la puerta de atrás (regla RGPD) | El ayuntamiento pierde la posibilidad de probar scripts sin desarrollador |
| **ACF** | Proteger `get_field` con `function_exists` y un valor por defecto | Evita el error fatal sin cambiar nada más | — |

## Dónde tocar para cambiar X

| Quiero… | Dónde |
|---|---|
| Añadir, quitar o reordenar un Especial | Administrador, tipo `especial` (nombre **No verificable**). Orden: fecha de publicación (`inc/especiales.php:24-25`) |
| Cambiar el máximo de Especiales (24) | `inc/especiales.php:23` |
| Cambiar el tamaño de la imagen de los Especiales | `inc/especiales.php:55` |
| Cambiar el enlace de un Especial | Campo ACF `especiales_enlace` de la entrada |
| Añadir o cambiar una diapositiva o su vídeo | **Portada → Diapositivas** ([doc](portada-diapositivas-con-video.md)) |
| Cambiar cuántas diapositivas entran (12) | `inc/home-carousel.php:175` |
| Cambiar cuántos eventos salen en la portada (4) | `page-home.php:273` (y la lectura de 50: `:236`) |
| Cambiar cuánto dura la caché de eventos (10 min) | `inc/events.php:56` |
| Corregir la fecha que muestra la agenda de la portada | `page-home.php:264-266` |
| Mostrar el banner de plenos | Personalizar → Aranda de Duero → casilla y texto |
| Cambiar fechas o enlace del banner de Fiestas | `page-home.php:107,111` |
| Cambiar un acceso frecuente o un servicio del buscador | `inc/services.php:46`, `inc/search.php:60` |
| Cambiar los grupos de «Para ti» o las situaciones | `inc/audiences.php:84`, `inc/situations.php:99` |
| Cambiar teléfono, horario o URL de cita previa / sede | `inc/template-functions.php:136-143` |
| Cambiar textos y enlaces del pie | `footer.php` (y los títulos de página que busca) |
| Cambiar el menú principal | Apariencia → Menús (ubicación «Primary») |
| Cambiar widgets de una barra lateral | Apariencia → Widgets |
| Cambiar la imagen de cabecera de una página | Campo ACF `cabecera_de_pagina` de la página (o imagen por defecto en Personalizar) |
| Cambiar la imagen al compartir en redes | Personalizar → «Imagen por defecto para compartir en redes sociales» |
| Añadir un colectivo o página a «Para ti» | [para-ti.md](para-ti.md) |
| Cambiar las fichas de Barrios | Lista `$barrios` (`page-barrios-propuestas-respuestas.php:15-77`) |
| Cambiar los perfiles sociales del JSON-LD | `inc/structured-data.php:15-26` |
| Cambiar la agresividad de la precarga | `inc/speculation-rules.php:26-27` |
| Crear una página que el código espera | Slug exacto de la tabla de plantillas (`agenda`, `pagos-y-recibos`, `tramites-por-situaciones`, `para-ti`) |

## Dudas que solo se resuelven en la web real o el administrador

Ver [Qué NO se puede saber desde el código](#qué-no-se-puede-saber-desde-el-código). Las más útiles de comprobar: nombre y visibilidad
de `especial` (1), plantilla y slug de las páginas que el código busca (4), contenido de la página Agenda (5) y si hay eventos
recurrentes que la portada no muestra (9).
