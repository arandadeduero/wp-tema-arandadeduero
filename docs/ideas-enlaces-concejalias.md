# Ideas: qué enlazar desde las concejalías (`/concejalia/…`)

Revisión del 2026-11-10. Estado al final del día: **A y B1 hechos**, D aparcado y el resto pendiente. Los datos salen de la API pública: 27 concejalías, 41 páginas, 20 servicios municipales, 11 especiales, 24 trámites y los títulos de 4.077 entradas.

## Cómo funciona hoy

- **Pastillas** (`link-pill`) en la descripción de la concejalía. Es contenido y se cambia desde wp-admin o la API, sin PR. Ya las usan Alcaldía, Deportes, Innovación, Movilidad, Participación, Promoción, Turismo y Urbanismo.
- **Tarjetas «Servicios»**. Solo salen las fichas del tipo *servicio municipal* que llevan esa concejalía (`aranda_de_duero_concejalia_service_cards()`). Crear una ficha es contenido; la plantilla ya existe.
- **Las páginas no pueden llevar concejalía.** La taxonomía es solo de entradas, actividades, especiales, trámites y servicios. Por eso al pasar una noticia a página desaparece del listado de la concejalía, como ha pasado hoy con el PGOU y el mapa de ruido.
- **Concejalías sin ninguna tarjeta:** Hacienda, Servicios sociales, Urbanismo, Alcaldía, Turismo, Personal, Festejos y Hermanamientos. Medio ambiente y Promoción tienen 1; Educación tiene 2.

## A. Pastillas que se pueden añadir ya, sin código

**HECHO el 2026-11-10.**
- 29 pastillas en 11 concejalías, las de la tabla.
- «Calendario fiscal» de Hacienda lleva a `/calendario-fiscal/` (B1).
- Personal lleva a la oferta de empleo de la sede (`PTS2_EMPLEO`).
- En Urbanismo, la pastilla del PGOU lleva ya a la página del Ayuntamiento (`/plan-general-de-ordenacion-urbana-de-aranda-de-duero-2024/`) y no al portal de transparencia. Se han añadido Expediente de la plaza de toros e Iluminación de edificios y fuentes.
- «Perfil del contratante» va en Contratación y en Alcaldía: Contratación no tiene entradas y no sale en el índice de concejalías.

Todas apuntan a páginas que ya existen.

| Concejalía | Pastillas propuestas |
|---|---|
| Hacienda | Pagos y recibos (`/pagos-y-recibos/`) · Ordenanzas fiscales (`/listado-completo-ordenanzas-fiscales-vigentes/`) · Calendario fiscal (ver B1) · Oficina Virtual del Catastro (`sedecatastro.gob.es`, ya es un especial) |
| Alcaldía | Corporación municipal · Comisiones y plenos · Agenda de alcaldía · Reglamentos (`/reglamentos/`) · Bandos |
| Medio ambiente | Recogida de residuos · Mapa estratégico de ruido · Entorno natural |
| Aguas (1 entrada) | Agua y abastecimiento (`/agua-y-abastecimiento/`) · Reglamentos (el del suministro de agua está ahí) |
| Seguridad ciudadana | Objetos perdidos · Teléfonos de interés |
| Comercio y consumo | Ayudas para empresas, autónomos y comercio local (`/subvenciones/`) |
| Cultura | Patrimonio · Historia · Himno de Aranda |
| Turismo | Patrimonio · Gastronomía · Entorno natural · Situación geográfica |
| Promoción | Fondos NextGenerationEU – PRTR (`/fondos-nextgenerationeu-prtr/`) |
| Personal | Ofertas de empleo (tablón de la sede, el mismo enlace del pie) |
| Servicios sociales | Farmacia de guardia · Para ti (familias y mayores) · Reglamentos (ayuda a domicilio, viviendas sociales) |
| Urbanismo | ¿Cambiar la pastilla del PGOU? Ahora va al portal de transparencia, pero el especial de la portada ya lleva a la página nueva. Faltaría la ITE (ver B2) |

Dudas resueltas por el usuario el 2026-11-10: plaza de toros e iluminación → Urbanismo; perfil del contratante → Contratación y Alcaldía.

## B. Noticias que deberían ser páginas

| # | Hoy | Propuesta | Desde |
|---|---|---|---|
| B1 | Una entrada por año: «Calendario fiscal 2012» … «Calendario Fiscal 2027» (4-11-2026) | Página fija `/calendario-fiscal/` que se actualiza cada año, con enlace al calendario de la sede. **El especial de la portada aún lleva a 2026, aunque el de 2027 ya está publicado.** | Hacienda (pastilla) y especial de portada |
| B2 | ITE: 2 entradas «I.T.E. inspección técnica edificios» (2023 y 2026), «Edificios con obligación de pasar ITE» de cada año, la modificación de la ordenanza (2024) y el censo de 2017 | Página «Inspección técnica de edificios (ITE)»: ordenanza (ya en la página de ordenanzas), trámite del registro de ITE (ya existe), listado del año y preguntas frecuentes | Urbanismo |
| B3 | «Plan especial de protección de bodegas» (2018) | Página, si sigue vigente (preguntar a Urbanismo) | Urbanismo y Turismo |
| B4 | «Aranda Jardín del Duero: Estrategia DUSI» (2017) | Ficha del tipo *proyecto europeo* (FEDER), igual que las del PRTR | Promoción y Fondos europeos |
| B5 | «Plan de Movilidad Urbana Sostenible» (2024, hoy migrado) | **No hace falta página.** Ya existe pmus.arandadeduero.es con los mismos tomos, y la pastilla de Movilidad y el especial apuntan allí. Se podría redirigir la entrada a esa web | — |
| B6 | «Planes de acción en materia de contaminación acústica» (2019) y «Aprobación plan de acción…» (2013) | Ya están en la página del mapa de ruido 2024; enlazarlos desde «Mapas anteriores» | — |

**B1 HECHO el 2026-11-10:** página 35537 `/calendario-fiscal/` con las tablas de 2026 (año en curso) y 2027 (sacada del BOP). El especial de la portada (ID 24085) y la pastilla de Hacienda apuntan ya allí. **Cada año hay que actualizar la tabla** cuando salga el nuevo calendario en el BOP (normalmente a finales de octubre).

**B2 HECHO el 2026-11-10:** página 35546 `/inspeccion-tecnica-de-edificios/` (qué edificios, censo 2025-2026, cómo se presenta, normativa y censos anteriores), con pastilla en Urbanismo. Las noticias «I.T.E. inspección técnica edificios» (4399 y 28576) siguen publicadas.

**B5 y B6 HECHOS el 2026-11-10:**
- La noticia del PMUS (24667) está en borrador y su URL da un 301 a pmus.arandadeduero.es (PR #154). Los 4 PDF del PMUS subidos ese día a WordPress (ID 35518-35521) se borraron después: los tomos están en pmus.arandadeduero.es y los originales en `/archivos/documentos/PMUS/`.
- La página del mapa de ruido 2024 tiene ahora el apartado «Mapas y planes de acción anteriores», con los planes de acción de 2013 y 2019.

## C. Tarjetas nuevas: fichas de *servicio municipal*

Los datos de contacto ya están en `/informacion-de-concejalias/`. Los horarios hay que confirmarlos con cada servicio.

| Concejalía | Ficha propuesta | De dónde sale |
|---|---|---|
| Servicios sociales | CEAS / Centros de Acción Social | directorio de concejalías |
| Servicios sociales | Ayuda a domicilio y teleasistencia | reglamento y ordenanza ya publicados |
| Servicios sociales | Viviendas sociales y alojamientos de emergencia | reglamento y tasa |
| Medio ambiente | Punto limpio | «Horario de Punto Limpio» (2020); la página de residuos lo menciona |
| Medio ambiente | Oficina de Medio Ambiente | entrada de 2023 |
| Turismo | Oficina de Turismo / Centro de Recepción de Visitantes | «Horario turismo 2026» y la noticia de INTUR (9-11-2026) |
| Urbanismo | Oficina técnica (arquitectos municipales) | «Horarios arquitectos municipales» (2023) |
| Hacienda | Gestión tributaria y recaudación | directorio y Pagos y recibos |
| Aguas | Servicio municipal de aguas | página Agua y abastecimiento |
| Cementerio (0 entradas) | Cementerio municipal | reglamento de 2025 |
| Cultura | Museos municipales: Casa de las Bolas, Arte Sacro (San Juan) y quizá el Museo del Tren | reglamento de museos (2022) |
| Educación | Ludotecas municipales | entrada «horario octubre y noviembre» (2026) |

## D. Con código (PR): tarjetas también para páginas

**APARCADO el 2026-11-10** por decisión del usuario («para más adelante»). Guardado en la memoria.

Hoy una página no puede salir en una concejalía. Hay dos opciones:
1. **Registrar la taxonomía `concejalia` también para `page`.** Las páginas con concejalía saldrían como tarjetas en un bloque «Información» encima de las noticias, sin mezclarse con ellas. **Recomendada:** es lo más sencillo y lo edita el Ayuntamiento como siempre. Hay que revisar `term-posts`, el recuento de las concejalías y el buscador (`inc/search.php`).
2. Un campo en la concejalía con una lista de páginas destacadas (ACF o metadatos del término). Es más control, pero un campo más que mantener.

## E. Detectado de paso (sin tocar)

- ~~**Calendario fiscal:** el especial de portada seguía en 2026~~ — resuelto en B1.
- **Concurso «Empecinado» 2025:** la noticia del VIII concurso enlaza las **bases del V concurso (2022)**.
- **Concejalías con 0 entradas:** cementerio, comunicación, contratación, nuevas tecnologías, procesos y transición digital. Con 1 entrada: aguas, barrios y participación. ¿Se ocultan o se rellenan?
- **Noticias que se pueden despublicar o redirigir**, por obsoletas o duplicadas:
  - «Trenes destinos, horarios y tarifas» (2010)
  - «Autobuses urbanos destinos horarios y tarifas» (2016), que ya tiene la ficha «Transporte urbano»
  - «Ayuntamiento teléfonos de contacto» (2020), duplicada con `/informacion-de-concejalias/` y Teléfonos de interés
  - «Guía para la creación de empresas» (2011)
- **Noticias repetidas**, por ejemplo «Matrículas en la Escuela municipal de folklore» (5 entradas con el mismo título).
