# «Para ti»: los servicios por colectivos

Segunda vía para llegar a lo que se necesita, además de los departamentos y del buscador: el visitante elige quién es (familias,
personas mayores, jóvenes, empresas y comercios, nuevos vecinos o asociaciones) y ve las tarjetas que suelen hacerle falta.
**No construye ningún servicio nuevo:** cada tarjeta lleva a una página que ya existe (una concejalía o un tema del menú, un
trámite del portal o un servicio de la sede).

## La página
Plantilla `page-para-ti.php`. Está creada en WordPress con la dirección (slug) `para-ti` y la plantilla **«Plantilla Para ti»**
(comprobado el 2026-10-20: `/para-ti/` responde 200). Si hubiera que volver a crearla: *Páginas → Añadir nueva*, título «Para ti»,
slug `para-ti`, esa plantilla; el contenido de la página no se usa.

**Dónde se enlaza:**
- En la home, bajo el buscador: «O explora: Para ti · Trámites por situaciones» (`aranda_de_duero_explore_links()`, solo con las
  páginas publicadas).
- El buscador «¿Qué necesitas?» la encuentra («jóvenes», «familias»…).
- **No está en el menú principal:** añadirla ahí es decisión del ayuntamiento (*Apariencia → Menús*, enlace a la página).

## Cómo se cambia
Todo está en `inc/audiences.php`:
- **Qué tarjetas lleva cada grupo:** la lista `$groups` de `aranda_de_duero_audiences()`. Cada entrada es el **título exacto** de una
  sección, un servicio o un trámite; si ese título ya no existe, la tarjeta se omite sin romper la página.
- **Las secciones del sitio** (concejalías y temas del menú, y Deportes) están en `aranda_de_duero_site_sections()`, con su dirección.
- Los servicios y trámites salen de `inc/services.php`, `inc/search.php` y `inc/situations.php`; para añadir uno nuevo, primero allí.
- Un filtro, `aranda_de_duero_audiences`, permite cambiar los grupos sin tocar el fichero.

## Notas
- **Qué entra en cada grupo es una propuesta técnica**, pendiente de validar con el ayuntamiento, igual que las descripciones de
  las secciones, que solo repiten el título («Información y servicios de salud»).
- **Las direcciones de las secciones están escritas a mano** (`/tema/salud/`, `/concejalia/juventud/`…): si alguien cambia el nombre
  o la dirección de un tema en WordPress, su tarjeta dará 404. Conviene revisarlas de vez en cuando.
- Los tests están en `tests/AudiencesTest.php`.
