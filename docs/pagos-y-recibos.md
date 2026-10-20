# Accesos frecuentes y «Pagos y recibos»

Dos piezas que hacen **encontrable desde arandadeduero.es** lo que ya existe en la sede electrónica (`sede.arandadeduero.es`): no
construyen ningún servicio nuevo.

## Accesos frecuentes (home)
Ocho tarjetas entre el carrusel y las noticias: cita previa, pagos y recibos, certificado de padrón, trámites, empleo público, quejas y
sugerencias, notificaciones electrónicas y tablón de anuncios. Los textos y enlaces están en `aranda_de_duero_quick_access()`
(`inc/services.php`): editar, añadir o quitar una entrada de esa lista. No están farmacias de guardia ni transporte: no hay datos.

## Pagos y recibos (página)
Plantilla `page-pagos.php`. **Hay que crear la página en WordPress:**
1. *Páginas → Añadir nueva*, título «Pagos y recibos».
2. Dirección (slug): `pagos-y-recibos`.
3. Plantilla: **«Plantilla Pagos y recibos»**. El contenido de la página es opcional y se muestra debajo de las tarjetas.

Mientras esa página no exista (o esté en borrador), la tarjeta «Pagos y recibos» de la home apunta a la **pasarela de pagos** de la sede, así
que nunca lleva a un 404; al publicarla pasa a apuntar a ella sola.

Los servicios agrupados son los de la sede: pasarela de pagos; recibos, multas, mis liquidaciones, alta de liquidaciones y objetos
tributarios (piden identificarse en la sede); calendario fiscal, «Mi calendario fiscal» y las ordenanzas fiscales. Las descripciones
siguen lo que la propia sede dice de cada servicio. Se editan en `aranda_de_duero_payment_services()`.

## Notas
- Los enlaces que piden identificación (`CarpetaPrivate/Login`) los gestiona la sede; el tema no sabe si el visitante tiene sesión.
- Si la sede cambia sus direcciones, solo hay que actualizar `inc/services.php`.
- El aspecto (tarjetas blancas con borde azul) está en el bloque «Tarjetas de servicio» de `css/aranda-de-duero.css`.

## Trámites por situaciones
Plantilla `page-tramites-situaciones.php` (datos en `inc/situations.php`). **Hay que crear la página en WordPress:** título «Trámites por
situaciones», dirección (slug) `tramites-por-situaciones` y plantilla **«Plantilla Trámites por situaciones»**. Mientras no exista, el buscador no
la ofrece. Cada situación agrupa trámites del portal (`/tramite/<slug>/`) y servicios de la sede. Para añadir un trámite: añadirlo a
`aranda_de_duero_portal_procedures()` (con su dirección exacta) y a la situación que corresponda. **Las direcciones están escritas a mano**: si
alguien renombra un trámite en WordPress, su tarjeta dará 404; hay que revisarlas de vez en cuando (ver la memoria del proyecto).

## Buscador «¿Qué necesitas?»
El campo de la home y de los resultados de búsqueda, y el bloque «Servicios y trámites» que sale encima de los resultados, leen de las mismas
listas (`inc/services.php`) más otras páginas de la sede (`aranda_de_duero_service_directory()` en `inc/search.php`). El buscador también incluye las páginas de la sede (las propias de cada persona, que piden identificarse, y las públicas), las secciones y portales del sitio (agenda, bandos, subvenciones, turismo, las fotografías de Flickr…) y los
«Especiales» de la portada (se leen del tipo de contenido en cada búsqueda: lo que se destaque allí se encuentra solo). Cada servicio puede
llevar `keywords` (sinónimos sin acentos: «oposiciones» para empleo público) para que se encuentre con más palabras. Para añadir un servicio
al buscador basta con añadirlo a una de esas listas: las sugerencias que salen al escribir (`js/search-suggest.js`) leen **la misma lista**, incluida
en la página, y filtran en el navegador sin pedir nada al servidor.

Bajo el campo aparecen dos filas de enlaces: «Prueba con» (búsquedas de ejemplo) y «O explora» (las páginas [«Para ti»](para-ti.md) y «Trámites por
situaciones», solo si están publicadas).

## Cabecera y buscador fijos (home)
Al hacer scroll se quedan a la vista la barra azul de contacto, la cabecera con el menú y, en la home, el bloque «¿Qué necesitas?»,
que se reduce al campo y al botón «Buscar» (con «O explora» debajo). Las posiciones son CSS (`position: sticky`, en
`css/aranda-de-duero.css`); `js/sticky-header.js` solo mide la altura de las barras, para apilarlas y dejar sitio a los anclajes, y
decide cuándo el bloque está pegado (clase `is-compact`). En móvil el panel del menú se desplaza dentro de la pantalla en lugar de salirse.
