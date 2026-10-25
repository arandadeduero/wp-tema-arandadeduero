# Servicios cotidianos: qué hacer y cómo

Sección «Servicios cotidianos» de la portada (`aranda_de_duero_everyday_services()` en `inc/everyday-services.php`), que
agrupa la farmacia de guardia, la recogida de residuos, la OMIC, los objetos perdidos y el autobús urbano, aparte de los
trámites del Ayuntamiento de «Accesos frecuentes».

## Resumen

| Servicio | Página | Datos |
|---|---|---|
| Farmacia de guardia | `/farmacia-de-guardia/` | `inc/pharmacies.php`, `data/farmacias/` (ver `docs/farmacias-de-guardia.md`) |
| Recogida de residuos | `/recogida-de-residuos/` | `inc/waste.php`, `data/residuos/` (ver `docs/recogida-de-residuos.md`) |
| OMIC | `/omic/`, plantilla `page-omic.php` | `aranda_de_duero_omic_info()` en `inc/everyday-services.php` |
| Objetos perdidos | `/objetos-perdidos/`, plantilla `page-objetos-perdidos.php` | `aranda_de_duero_lost_found_info()` en `inc/everyday-services.php` |
| Autobús urbano | La ficha `/servicios/transporte-urbano/` (Movilidad) si está publicada; si no, `https://busurbanoaranda.com/` | `aranda_de_duero_page_url( 'transporte-urbano', 'servicio-municipal' )` y `aranda_de_duero_transport_url()` |

Cada página (farmacia, residuos, OMIC, objetos perdidos) solo aparece en la tarjeta de la portada y en el buscador
cuando está publicada, para que nunca lleve a un 404. El autobús siempre aparece, porque enlaza a un sitio externo.

## Primera puesta en marcha de OMIC y objetos perdidos

1. `npm run release` y subir **el tema completo**.
2. *Páginas → Añadir nueva*: título «OMIC: información al consumidor», dirección `omic`, plantilla **Plantilla OMIC**, publicar.
3. *Páginas → Añadir nueva*: título «Objetos perdidos», dirección `objetos-perdidos`, plantilla **Plantilla Objetos perdidos**, publicar.
4. Comprobar la portada («Servicios cotidianos»), el buscador (`omic`, `reclamación`, `objetos perdidos`, `llaves`, `autobús`) y que
   `/la-omic-que-es-y-que-funciones-tiene/` redirige a `/omic/` (ver más abajo).

## OMIC

- **Qué es y funciones:** contenido fijo de `page-omic.php`, con la base legal del artículo 25 de la Ley 2/2015, de 4 de marzo,
  por la que se aprueba el Estatuto del Consumidor de Castilla y León (BOE-A-2015-3280).
- **Contacto:** `aranda_de_duero_omic_info()` en `inc/everyday-services.php` — dos teléfonos (947 50 86 66 y 947 50 92 45) y la
  dirección (Plaza Mayor, 13). **Fuente:** directorio de OMIC de la Junta de Castilla y León (consumo.jcyl.es), 2026-10-25. No
  es el teléfono general del Ayuntamiento.
- **Redirección del artículo antiguo:** `/la-omic-que-es-y-que-funciones-tiene/` (una noticia, no una página fija) redirige con
  301 a `/omic/` en cuanto esta página existe (`aranda_de_duero_redirect_omic_article()`, enganchado a `template_redirect`). Si
  algún día cambia el slug de esa noticia, actualizar `aranda_de_duero_omic_article_redirect_target()`.

## Objetos perdidos

- **Contacto:** `aranda_de_duero_lost_found_info()` — teléfono 947 51 26 46, correo `policialocalad@arandadeduero.es` y la
  dirección Calle Valladolid, 4. **Fuente:** la propia página de concejalías y departamentos del Ayuntamiento
  (`/informacion-de-concejalias/`), 2026-10-25. La Policía Local se mudó a esta dirección en 2021, desde la antigua de
  Plaza Jardines de Don Diego.
- Si el Ayuntamiento confirma o cambia estos datos, se edita solo `aranda_de_duero_lost_found_info()`.

## Autobús urbano

La tarjeta lleva a la ficha de servicio «Transporte urbano» (`/servicios/transporte-urbano/`, Movilidad), que enlaza la web del
operador, el visor de rutas e itinerarios y el mapa. Si esa ficha no está publicada, la tarjeta vuelve a ir directa a
`https://busurbanoaranda.com/` (`aranda_de_duero_transport_url()`), en otra pestaña.

## Toda la tarjeta lleva a la página

La tarjeta de farmacia y la de residuos tienen dentro otros enlaces (teléfono, mapa), así que el título no puede ser el único
`<a>` de toda la tarjeta sin que quede invalido el HTML. Se usa el mismo truco que ya tenía «Especiales»
(`.stretched-link` de Bootstrap): el título estira su enlace a toda la tarjeta, y los enlaces de teléfono y mapa van por
encima (`z-index`) para seguir siendo clicables.

## Dónde tocar si se quiere cambiar algo

| Quiero… | Fichero |
|---|---|
| Cambiar teléfonos, correo o dirección de la OMIC o de objetos perdidos | `inc/everyday-services.php` |
| Cambiar el orden o los textos de las tarjetas de «Servicios cotidianos» | `aranda_de_duero_everyday_services()` en `inc/everyday-services.php` |
| Cambiar el contenido de las páginas de OMIC u objetos perdidos | `page-omic.php` / `page-objetos-perdidos.php` |
| Cambiar los sinónimos de búsqueda | `inc/search.php` (OMIC, objetos perdidos, autobús) o `inc/everyday-services.php` (las tarjetas) |

## No verificado

- El aspecto de las dos páginas nuevas en la web real (se comprueba tras el primer despliegue).
- Que `busurbanoaranda.com` sea el sitio oficial del operador (solo se comprobó que responde).
