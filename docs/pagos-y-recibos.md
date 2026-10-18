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
