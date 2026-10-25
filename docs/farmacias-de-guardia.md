# Farmacias de guardia: qué hacer y cómo

Plantilla `page-farmacias.php`, lógica en `inc/pharmacies.php` y datos en `data/farmacias/`. Los avisos por correo están en
`inc/pharmacies.php` (calendario anual) y `inc/reminders.php` (delegaciones).

## Resumen

| Cuándo | Qué hacer | Quién |
|---|---|---|
| Una vez, tras el primer despliegue | Crear la página `farmacia-de-guardia` con la plantilla «Plantilla Farmacias de guardia» y enlazarla | Quien edita WordPress |
| Solo si se reactiva una caché de página | Excluir esa página de la caché (hoy Cache Enabler está desactivado y no hace falta) | Quien administra el servidor |
| Cada diciembre | Crear `data/farmacias/<año siguiente>.json` y subir el tema completo | Informática |
| Cuando cambie una farmacia (dirección, teléfono, cierre, apertura) | Editar `data/farmacias/catalogo.json` y subir el tema | Informática |

## Los datos

| Fichero | Contenido |
|---|---|
| `data/farmacias/catalogo.json` | Las 12 farmacias: `id`, nombre, dirección, teléfono, zona y coordenadas |
| `data/farmacias/<año>.json` | La farmacia de guardia de cada día de ese año (`schedule`) y las fechas poco seguras (`meta.lowConfidenceDates`) |

Fuente: Colegio Oficial de Farmacéuticos de Burgos, «Servicios de Urgencia Z.F. Aranda de Duero». Los ficheros de 2026 se
copiaron de `data/farmacias.json` y `data/farmacias-guardia-2026.json` del repositorio `api-ciudad`, mientras esa API no sea pública.

Lo que **no** traen los datos: el horario de la guardia y el horario normal de apertura. La página no los muestra. La guardia de un día va de las 9:50 a las 9:50 del día siguiente (`aranda_de_duero_pharmacy_guard_change_minutes()` en `inc/pharmacies.php`): antes de las 9:50 la página enseña la farmacia del día anterior. Esa hora la dio el Ayuntamiento el 2026-10-25 (antes se usaban las 10:00); no es un dato del Colegio.

Licencia de la fuente: figura como «no especificada, uso informativo, verificar antes de redistribución». Conviene confirmar
con el Colegio que se puede publicar en la web municipal.

## Primera puesta en marcha

1. `npm run release`, subir **el tema completo** a WordPress.
2. *Páginas → Añadir nueva*: título «Farmacia de guardia», dirección `farmacia-de-guardia` (la tarjeta de «Accesos frecuentes» de la portada busca la página por esa dirección y solo sale si está publicada), plantilla **Plantilla Farmacias de guardia** (panel «Página»), y publicar. El contenido del editor sale al final de la página, tras los datos.
3. Enlazarla donde convenga: *Apariencia → Menús* o una tarjeta de «Accesos frecuentes» (esto último es una decisión de contenido).
4. Caché de página: hoy no hay (Cache Enabler está desactivado desde 2026-10-25). Si se reactiva, hay que excluir la URL
   `/farmacia-de-guardia/` y la portada, porque la tarjeta de la portada y la página imprimen la farmacia de hoy y, con la página en
   caché, enseñarían la de ayer hasta que caduque.
5. Comprobar en la web publicada: la farmacia de hoy, que el teléfono es un enlace, la tabla de los próximos días y el
   listado de las 12 farmacias, en escritorio y móvil.

## Cada año: cargar el calendario nuevo

1. Conseguir el calendario del año siguiente en el Colegio de Farmacéuticos (https://www.cofburgos.es), zona de Aranda de Duero.
2. Copiar `data/farmacias/<año anterior>.json` a `data/farmacias/<año nuevo>.json` y cambiar su contenido. Formato:

   ```json
   {
     "meta": {
       "year": 2027,
       "source": "Colegio Oficial de Farmacéuticos de Burgos",
       "retrievedAt": "2026-12-10",
       "lowConfidenceDates": ["2027-12-24", "2027-12-25"]
     },
     "schedule": [
       { "date": "2027-01-01", "pharmacyId": 3 },
       { "date": "2027-01-02", "pharmacyId": 4 }
     ]
   }
   ```

   - Una entrada por día, con `date` (`AAAA-MM-DD`) y `pharmacyId`: el `id` de esa farmacia en `catalogo.json`.
   - Solo `schedule` es obligatorio; `meta` es informativo salvo `lowConfidenceDates`.
   - Si un día no está claro en el documento, ponerlo en `lowConfidenceDates`: la página lo marca «por confirmar» y avisa de que se contraste con el Colegio.
3. Comprobar que el fichero es JSON válido y que cada `pharmacyId` existe: `composer run test` lo vigila para 2026 (el test de calendario completo); para el año nuevo, copiar ese test o revisar a mano.
4. `npm run release`, subir el tema completo y vaciar la caché.

No hay que tocar PHP. Si falta el fichero del año en curso, la página muestra un aviso con enlace al Colegio en lugar de la farmacia.

## Cuando cambia una farmacia

Editar su entrada en `catalogo.json` (los `id` no se cambian ni se reutilizan, porque los calendarios apuntan a ellos). Una
farmacia nueva lleva el siguiente `id` libre y hay que usarlo en los calendarios desde la fecha en que entre en guardia.

## Avisos por correo

Un aviso de WP-Cron diario por cada tarea. Los destinatarios son `informatica@arandadeduero.es` y `glopez@arandadeduero.es`
(`aranda_de_duero_reminder_recipients()` en `inc/reminders.php`; se pueden cambiar con el filtro `aranda_de_duero_reminder_recipients`).

| Aviso | Cuándo | Qué dice |
|---|---|---|
| Calendario de farmacias del año siguiente | A los 15, 10 y 5 días del 31 de diciembre, mientras no exista `data/farmacias/<año siguiente>.json`. Deja de avisar al subir el fichero | Cómo cargar el calendario (los pasos de arriba) |
| Posibles cambios en las delegaciones | 1 de enero y 1 de julio | Qué comprobar de las concejalías: concejal, teléfono, correo, ubicación, menú «Áreas y servicios», `docs/concejalias-vigentes-y-terminos.md` y el horario de atención del Ayuntamiento |
| Refrescar el catálogo de trámites del buscador | El día 1 de cada mes | Ejecutar `node scripts/update-sede-catalog.js`, revisar `git diff data/sede/tramites.json` y, si hay cambios, subir el tema completo |
| Corporación municipal vigente | 15 de junio de 2027 | Revisar alcalde o alcaldesa, concejales, delegaciones y textos que citan al alcalde por su nombre |

Cómo funciona:

- Cada aviso se envía una sola vez, el día señalado o en la semana siguiente (el cron solo corre cuando hay visitas). Uno cuya fecha ya pasó hace más de una semana no se envía, así que desplegar el tema no dispara los antiguos.
- Se apunta como enviado solo si `wp_mail()` dice que salió. Si falla, se escribe una línea en el registro de errores de PHP (`aranda-de-duero: wp_mail() no pudo enviar…`) y se reintenta al día siguiente.
- Si el sitio no tiene visitas varios días, los avisos de farmacias pendientes salen juntos en un solo correo.
- Qué avisos se enviaron: opciones `aranda_pharmacy_reminders_sent` y `aranda_reminders_sent` (no cargan en cada página).

**No está verificado** que el correo llegue con la configuración de correo del servidor ni que WP-Cron se dispare en esta
instalación. Tras el primer despliegue conviene comprobarlo, por ejemplo con el plugin WP Crontrol (el evento
`aranda_de_duero_pharmacy_reminder` y `aranda_de_duero_periodic_reminders` deben aparecer programados a diario) y enviando un
correo de prueba desde WordPress a esas dos direcciones.

## Dónde tocar si se quiere cambiar algo

| Quiero… | Fichero |
|---|---|
| Cambiar los días de aviso del calendario (15, 10 y 5) | `aranda_de_duero_pharmacy_reminder_days()` en `inc/pharmacies.php` |
| Cambiar las fechas o textos de los avisos de delegaciones | `aranda_de_duero_periodic_reminders()` en `inc/reminders.php` |
| Añadir otro aviso con fecha | Una entrada más en esa misma función, con un `id` único |
| Cambiar la página (textos, orden, cuántos días siguientes) | `page-farmacias.php` |
