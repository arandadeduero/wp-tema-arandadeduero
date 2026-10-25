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

## Pendiente

- **Los servicios reales no están creados todavía.** Hay un CSV con 80 términos de la taxonomía vieja clasificados
  (18 propuestos como servicio de verdad, el resto como eventos, etiquetas, hermanamientos o documentos), aceptado por
  el usuario el 2026-10-25, pero las páginas en sí no se han creado. Siguiente paso: crear esas ~18-28 fichas con su
  concejalía y, si hay texto aprovechable, partir de la descripción que tuviera el término viejo en la taxonomía
  `servicio` (sin comprobar si la tiene).
- **La taxonomía `servicio` sigue mezclada**, sin limpiar los 48 términos numéricos ni separar lo que no es un servicio.
- No hay archivo general `/servicios/` que liste todos a la vez (`has_archive` está desactivado a propósito, para no
  tener que hacer también esa plantilla hoy); cada servicio solo se ve desde la página de su concejalía o por su URL
  directa.
