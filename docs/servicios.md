# Servicios de una concejalía: qué es y cómo usarlo

Tipo de contenido `servicio-municipal` (slug de la URL: `/servicios/<página>/`), para los servicios que dependen de una
concejalía: por ejemplo, Biblioteca o Escuela de Música dentro de Cultura. Se registra en `inc/content-types.php`,
igual que `tramite` o `especial`.

## Por qué se llama «servicio-municipal» y no «servicio»

Ya existía una **taxonomía** `servicio` con 128 términos (48 con nombre numérico sin sentido, 80 con nombre real,
mezclados entre servicios de verdad, etiquetas, eventos y documentos; usada para clasificar noticias, no para páginas
propias). WordPress no deja que un tipo de contenido y una taxonomía compartan el mismo nombre interno. Renombrar esa
taxonomía exige una consulta directa a la base de datos, a la que no tengo acceso desde aquí (solo a la API REST de
WordPress). Por eso el tipo de contenido nuevo se llama `servicio-municipal`, aunque la URL sea la natural, `/servicios/`.

La taxonomía `servicio` sigue existiendo tal cual, sin tocar; es un asunto aparte, pendiente de limpiar otro día.

## Cómo se relaciona con la concejalía

Cada servicio lleva la taxonomía `concejalia` (la misma que ya usan `tramite` y `especial`). En la página de cada
concejalía (`taxonomy-concejalia.php`), si tiene algún servicio publicado, sale una sección «Servicios» con una
tarjeta por cada uno, antes de las noticias. Si la concejalía no tiene ningún servicio, esa sección no aparece.

## Crear un servicio

1. *Entradas → Servicios → Añadir nuevo* (o desde el menú de WordPress, «Servicios»).
2. Título (el nombre del servicio, por ejemplo «Biblioteca»), contenido con la descripción, imagen destacada opcional.
3. Asignar la concejalía correspondiente (campo «Concejalía», igual que en una noticia o un trámite).
4. Publicar. Sale ya en la página de esa concejalía y en su propia URL `/servicios/<slug>/`.

## El contenido se edita con el editor, no en una plantilla fija

A diferencia de páginas como `/objetos-perdidos/`, que tienen el teléfono, la dirección y el resto de datos escritos
directamente en PHP (`aranda_de_duero_lost_found_info()`), un servicio **no tiene ninguna función de datos fija**:
todo el contenido (qué es, teléfonos, horarios…) vive en el editor de bloques de la propia entrada
(`the_content()`), igual que una noticia. El tipo ya tiene `show_in_rest` y soporte de `editor`, así que el editor es
el completo de WordPress (bloques), no un cuadro de texto plano. También lleva soporte de `excerpt`, para un resumen
corto independiente que se usa como texto de su tarjeta.

## Lista de noticias de la concejalía

En `taxonomy-concejalia.php`, la lista de noticias bajo «Servicios» usa el modo compacto de
`template-parts/term-posts.php` (`compact => true`): solo título y fecha, sin miniatura, para que quepan muchas antes
de la paginación. `taxonomy-tema.php` sigue usando las tarjetas grandes de siempre, sin cambios.

## OMIC sustituye a la antigua página `/omic/`

El servicio «OMIC» (Comercio y Consumo) sustituye a la antigua página estática `/omic/`, con la plantilla
`page-omic.php` (borrada) y la función `aranda_de_duero_omic_info()` (borrada también, ya sin uso). El contenido se
migró al cuerpo de la entrada. La página vieja se ha borrado; `/omic/` redirige (301,
`aranda_de_duero_redirect_old_paths()` en `inc/template-functions.php`, la misma que manda `/transparencia/` al portal) al
nuevo servicio.

## Secciones plegables (reutilizables)

Cualquier contenido largo del editor (un servicio como la Escuela de Música, una página o una noticia; las clases no dependen del tipo) se escribe en el editor como una introducción, un índice y un
`<details class="service-section" id="…">` por sección, con su `<summary>` y un `<div class="service-section-body">`
dentro. Todas van cerradas por defecto. El índice es un `<nav class="service-toc">` con enlaces `#id`; el JS del tema
(`setupServiceSections()` en `js/aranda-de-duero.js`) abre la sección a la que apunta la URL. Los estilos están en
`css/aranda-de-duero.css` (`.service-section`, `.service-toc`, `.service-section-body`). Sin JS siguen funcionando: solo hay que pulsar
cada título.

## Pendiente

- **Contenido de 16 de las 18 fichas, sin verificar.** Se crearon las 18 fichas aceptadas por el usuario el
  2026-10-25, cada una con su concejalía, pero solo OMIC (contenido migrado de la antigua página) y Policía Local
  (dirección y teléfono ya verificados para `/objetos-perdidos/`) llevan datos de contacto comprobados. Las otras 16
  llevan una descripción breve y genérica, sin teléfono, dirección ni horario inventados: el Ayuntamiento debe
  ampliarlas o corregirlas desde el editor.
- **La taxonomía `servicio` sigue mezclada**, sin limpiar los 48 términos numéricos ni separar lo que no es un servicio.
- No hay archivo general `/servicios/` que liste todos a la vez (`has_archive` está desactivado a propósito, para no
  tener que hacer también esa plantilla hoy); cada servicio solo se ve desde la página de su concejalía o por su URL
  directa.
