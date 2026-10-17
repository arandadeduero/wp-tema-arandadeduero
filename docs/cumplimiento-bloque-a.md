# Bloque A: informe de cumplimiento del portal (2026-10-17)

Estado real de cada punto del bloque A de [MEJORAS-OTROS-AYUNTAMIENTOS.md](../MEJORAS-OTROS-AYUNTAMIENTOS.md),
comprobado sobre la web publicada, y lo que queda en manos del ayuntamiento. **No es asesoramiento jurídico:**
las referencias normativas vienen de un análisis con artículos que no siempre se pudieron cotejar con el texto
literal; hay que confirmarlas con el secretario y con el delegado de protección de datos (DPD).

**Cómo se comprobó:** lectura de las páginas legales, del portal de transparencia y de la sede; inspección del
banner de cookies en un navegador; y una auditoría de accesibilidad automática (Lighthouse/axe) de la home.
Una auditoría automática **no demuestra conformidad** con WCAG: solo detecta una parte de los problemas.

## Qué hace el tema (ya implementado)

| Punto | Cambio |
|---|---|
| A1 | Campo **«Canal interno de información (URL)»** en *Personalizador → Aranda de Duero*. Cuando se rellena, aparece el enlace «Canal de información» en el pie de todas las páginas, incluida la home. Vacío, no se muestra nada: no se enlaza a una página que no existe |
| A3 | La home tiene `<main id="primary">` (antes no había ninguno y el enlace «saltar al contenido» apuntaba a un destino inexistente), un `<h1>` real oculto visualmente (el único `<h1>` era el número del día de la agenda, «17»), encabezados en orden, azul de texto con contraste suficiente y el enlace «Mi ciudad» del menú, que no tenía `href`, ahora es alcanzable con el teclado |

Medido en una copia de la home (misma red y mismas condiciones, antes y después):

| | Antes | Después |
|---|---|---|
| Accesibilidad (Lighthouse) | 92 | **100** |
| SEO | 92 | **100** |
| Auditorías fallidas | 6 | 1 (el error de consola de `stats.wp.com`, ver A2) |

El aspecto se conserva: se comparó el estilo calculado de cada encabezado antes y después (tamaño, peso, color,
altura total de la página). La única diferencia visible es el azul de los textos, de `#0083c1` a `#0073ad`.
El azul de marca `#0083c1` **no llega al 4,5:1 exigible ni sobre fondo blanco** (4,19:1); `#0073ad` da al menos
4,63:1 sobre todos los fondos del sitio. Los 11 bloques de «Especiales» (texto casi blanco sobre ese azul, 4,15:1)
se corrigen oscureciendo su fondo; esa parte no se pudo medir en la copia, porque el contenido de «Especiales» se
carga por AJAX.

> **Pendiente de verificar tras desplegar:** repetir la auditoría sobre la web real, en la home y en una página
> interior. Aquí se verificó sobre una copia de la home, no sobre la web en producción.

---

## A1 · Canal interno de información (Ley 2/2023)

**Lo que hay que hacer (del ayuntamiento):** poner en marcha el canal, nombrar al responsable del sistema y aprobar
el procedimiento de gestión. Después, pegar su URL en el Personalizador.

- Según el análisis, Aranda supera los 10.000 habitantes y el plazo de implantación ya venció. **Confirmarlo con el
  secretario.** Referencia: [BOE-A-2023-4513](https://www.boe.es/buscar/act.php?id=BOE-A-2023-4513), arts. 13 y 14 y
  disposición transitoria 2.ª (los números de artículo no se cotejaron con el texto literal) y la guía de la FAMP:
  [A4.pdf](https://famp.es/export/sites/famp/.galleries/documentos-general/A4.pdf).
- **Estado verificado:** no hay ninguna mención en la home, ni en la sede, ni en el portal de transparencia.
- Las cifras de multas que circulan salen de fuentes secundarias y el régimen para administraciones públicas es
  distinto: no usarlas.

## A2 · Banner de cookies

**Es configuración del plugin «GDPR Cookie Compliance»; el tema no puede cambiarlo.** Hallazgos verificados en un
navegador, sin interactuar:

| Observación | Detalle |
|---|---|
| Primera capa | Solo hay un botón **«Aceptar»** y un enlace/botón «ajustes». **No hay «Rechazar»** |
| Panel de ajustes | Botones «Activar todo» y «Guardar ajustes». No hay «Rechazar todo» |
| Antes de aceptar | No hay cookies instaladas, pero **se pide `stats.wp.com/e-202642.js` (Jetpack Stats), un tercero**, antes de cualquier consentimiento |
| Política de cookies | No se encontró una página propia: solo el panel del plugin y menciones en el aviso legal y la política de privacidad |

La guía de cookies de la AEPD pide que rechazar sea tan fácil como aceptar y esté al mismo nivel
([guía](https://www.aepd.es/sites/default/files/2020-07/guia-cookies.pdf)). **Qué revisar en los ajustes del plugin**
(los nombres exactos de las opciones no se pudieron verificar): un botón de rechazo en la primera capa, un
«Rechazar todo» en el panel, el bloqueo de scripts de terceros hasta que haya consentimiento, y una página de
política de cookies con su enlace. Para Jetpack Stats: desactivarlo en los ajustes de Jetpack o bloquearlo por
categoría hasta el consentimiento.

## A3 · Declaración de accesibilidad

**El texto de `/politica-de-accesibilidad/` está desactualizado y tiene errores verificables.** Es contenido del
ayuntamiento; el tema no lo cambia. Problemas encontrados en el texto publicado:

1. Cita **WCAG 2.0** y la norma **UNE 139803:2012**. El RD 1112/2018 remite a la norma EN 301 549, que exige
   **WCAG 2.1 nivel AA**.
2. Dice «**AYUNTAMIENTO DE DUERO**» (falta «Aranda de»).
3. Dice que la declaración afecta a «`https://www.2021.arandadeduero.es`», **una dirección que no es la web actual**.
4. No lleva **fecha** de la declaración ni de la última revisión.
5. No indica el **estado de cumplimiento** (total o parcial) respecto a la norma.
6. No ofrece el **mecanismo de comunicación** (contacto y vía de queja) que exige el RD 1112/2018.
7. No explica el **procedimiento de reclamación** si la respuesta no es satisfactoria.
8. No dice **cómo se evaluó** (autoevaluación, revisión de un tercero, herramientas).
9. Explica cómo ampliar el texto en **Internet Explorer**, que ya no existe.

**Estructura que debería tener** (modelo de declaración de accesibilidad del RD 1112/2018, que sigue la Decisión de
Ejecución (UE) 2018/1523; **contrastar con el texto oficial**, no se verificó aquí). Los campos entre corchetes
**solo los puede rellenar el ayuntamiento**:

1. **Declaración de accesibilidad.** Ayuntamiento de Aranda de Duero se compromete a hacer accesible su sitio web
   `https://www.arandadeduero.es` conforme al RD 1112/2018. Esta declaración se aplica a [alcance: web principal y
   subdominios que correspondan: sede, transparencia, turismo…].
2. **Estado de cumplimiento.** [Totalmente conforme / parcialmente conforme / no conforme] con [WCAG 2.1 nivel AA
   (norma UNE-EN 301549)], debido a [lo que dé la evaluación].
3. **Contenido no accesible** y motivos (incumplimiento, carga desproporcionada, fuera del ámbito).
4. **Preparación de esta declaración.** Fecha: [fecha]. Método: [autoevaluación / revisión de un tercero /
   herramientas utilizadas]. Última revisión: [fecha].
5. **Observaciones y datos de contacto** (mecanismo de comunicación): [correo, formulario y teléfono de la unidad
   responsable] para comunicar problemas de accesibilidad o solicitar información en un formato accesible.
6. **Procedimiento de aplicación.** Si la respuesta no es satisfactoria, [vía de reclamación y órgano competente].

El RD 1112/2018 también contempla la colaboración con el Observatorio de Accesibilidad Web
([BOE-A-2018-12699](https://www.boe.es/buscar/act.php?id=BOE-A-2018-12699); informe simplificado de entidades
locales de 2023: [PDF](https://administracionelectronica.gob.es/pae_Home/dam/jcr:7229a2ac-6a4a-4d90-96f3-918db692c216/2023_Informe_seguimiento_simplificado_sitios_web_EELL.pdf)).
Si Aranda está en la muestra y con qué resultado: no verificado.

## A4 · Privacidad y registro de actividades de tratamiento

**Contenido del ayuntamiento.** Lo comprobado en `/politica-privacidad/` (hay dos páginas, aviso legal y privacidad,
con textos parecidos):

- **Sí aparece:** responsable del tratamiento (con CIF), delegado de protección de datos, derechos de las personas,
  reclamación ante la Agencia Española de Protección de Datos.
- **No aparece:** mención o enlace al **registro de actividades de tratamiento**, **base jurídica**, **plazos de
  conservación** ni **cesiones**.
- El registro de actividades debe estar publicado según la Ley 19/2013, art. 6 bis (el artículo no se cotejó con el
  texto literal): [BOE-A-2013-12887](https://www.boe.es/buscar/act.php?id=BOE-A-2013-12887).

## A5 · Transparencia

El portal externo `transparencia.arandadeduero.es` existe y está enlazado desde la cabecera de todas las páginas.
Tiene siete secciones (transparencia municipal, planificación, acceso a la información, participación y
colaboración, transparencia económica, contratación y obras públicas y urbanismo), y en su home aparecen
retribuciones, subvenciones, organigrama, vehículos oficiales, gastos de viaje, resoluciones de compatibilidad y
datos del presupuesto de 2026.

**No se vio en su home** (puede estar en páginas interiores): agenda de cargos, declaraciones de bienes,
registro de actividades de tratamiento, canal de información y los enlaces legales propios del portal (aviso legal,
privacidad, cookies). Revisar contra los artículos 5 a 8 de la Ley 19/2013 y la Ley 3/2015 de Castilla y León
([BOE ELI](https://www.boe.es/eli/es-cl/l/2015/03/04/3/con)); la agenda de cargos se trata como **recomendable**, no
como obligación verificada.

## A6 · Sede electrónica, perfil del contratante y ENS

La sede (`sede.arandadeduero.es`) **ya incluye** gran parte de lo exigible y más: tablón de anuncios y edictos
electrónicos, perfil del contratante, quejas y sugerencias, validación de documentos, oficinas de asistencia en
materia de registro, catálogo de trámites, oferta pública de empleo, calendario fiscal, sesiones de pleno y de junta
de gobierno local, y una carpeta ciudadana (datos personales, padrón, expedientes, notificaciones, recibos,
multas, pasarela de pagos).

**No se vio** (puede estar en páginas interiores): la información de identificación de la sede y su acto de
creación, los sistemas de firma admitidos, el calendario y la hora oficial, y la **política de seguridad y la
declaración de conformidad con el Esquema Nacional de Seguridad** ([RD 311/2022](https://www.boe.es/buscar/act.php?id=BOE-A-2022-7191)).
Contenido mínimo de la sede: [RD 203/2021](https://www.boe.es/buscar/act.php?id=BOE-A-2021-5032) (artículos sin
cotejar). Es un sistema externo: lo revisa el ayuntamiento con su proveedor.

## A7 · Datos abiertos

No se encontró un portal ni un catálogo de datos abiertos. La Ley 37/2007 y la Directiva (UE) 2019/1024 apuntan a la
apertura por defecto, pero **el alcance exacto para un ayuntamiento no se pudo verificar**
([datos.gob.es](https://datos.gob.es/es/blog/las-claves-de-la-ley-sobre-reutilizacion-de-la-informacion-del-sector-publico-en-espana)).
La «Ley 7/2025» que se mencionó al encargar el estudio **no apareció en ninguna fuente**: no darla por cierta.
Un primer paso razonable, a decidir por el ayuntamiento, es publicar en formato reutilizable (CSV) lo que ya existe en
PDF, como las ordenanzas, el calendario fiscal o el presupuesto.
