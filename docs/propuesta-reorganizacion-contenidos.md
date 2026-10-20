# Propuesta: ordenar concejalías, temas, secciones y especiales

Propuesta de mejora **basada en datos medidos el 2026-10-20** ([estado actual](estado-actual-de-los-contenidos.md)) y en el estudio del
código ([taxonomías y concejalías](taxonomias-y-concejalias.md), [tipos de contenido y elementos propios](tipos-de-contenido-y-elementos-propios.md)).
**Es una propuesta, no una decisión:** lo que toca al contenido, al menú o a qué concejalías existen es del ayuntamiento; lo que
es código lo puedo hacer yo en PR pequeños y reversibles.

## En pocas palabras

1. **Hay dos «concejalías» distintas y se confunden.** El menú «Ayuntamiento → Concejalías» es una página-directorio de contacto
   escrita a mano (26 departamentos), sin relación con la taxonomía `concejalia`. Esa taxonomía tiene **58 términos**, y el menú solo enlaza a 3 de ellos.
2. **De las 58, 24 están activas, 12 con poca actividad, 20 antiguas y 2 vacías**; 14 tienen solo un número por nombre («29», «57»…) y hay nombres
   duplicados (dos «Educación», dos «Cultura»). Sus páginas siguen siendo públicas.
3. **Las páginas de concejalía y de tema enseñan menos de lo que dicen:** solo listan entradas (`post`), no los trámites, especiales ni
   actividades que tengan esa concejalía, y no muestran ningún título. «Educación» figura con 2622 elementos y su archivo lista unos 1257.
4. **Hay restos de un tema anterior que el tema actual no usa:** el tipo `actividad` (2092 elementos), la taxonomía `servicio` (100 términos), `seccion`
   (258 términos, una plantilla medio rota) y `seccion-principal`. Siguen siendo públicos.
5. **Lo que recomiendo:** **no unificar ni borrar nada ahora.** Primero (a) arreglar lo que ya está mal en el código, (b) marcar automáticamente como
   «archivo histórico» lo antiguo en lugar de borrarlo, (c) decidir con el ayuntamiento qué concejalías son reales y limpiar los nombres, y (d) medir el tráfico de los restos antes de tocarlos.

## Principios

- **No romper direcciones.** Cualquier URL pública puede estar enlazada, indexada o en un PDF. Quitar un término sin redirigir da un 404.
- **No perder contenido.** Todo se reasigna o se archiva; nada se elimina sin copia ni decisión expresa.
- **El contenido lo decide el ayuntamiento;** el código se adapta y se puede deshacer con un `git revert`.
- **PR pequeños**, cada uno con su comprobación en la web real (flujo habitual del proyecto).

## 1. Concejalías: qué hacer con las 58

### El problema, con datos

- Solo 3 se enlazan desde el menú (`juventud`, `urbanismo-y-vivienda` y `cultura`, que se llama «Educación»). Las otras 55 solo se llegan por enlaces dentro de las entradas, buscadores o direcciones antiguas.
- **Antiguas (más de 3 años sin contenido nuevo), sin contar las numéricas:**

| Concejalía | Slug | Último contenido | Elementos |
|---|---|---|---|
| Hermanamientos | `hermanamientos` | 2015-01-22 | 35 |
| Oficina de la Movilidad | `oficina-de-la-movilidad` | 2016-01-18 | 4 |
| Intervención | `intervencion` | 2018-11-06 | 7 |
| Promoción, Innovación y Turismo | `promocion-innovacion-y-turismo` | 2020-11-13 | 193 |
| Medio Ambiente, Aseo Urbano, Parques y Jardines | `medio-ambiente-aseo-urbano-parques-y-jardines` | 2021-02-24 | 115 |
| Protección Civil, Seguridad Ciudadana y Bomberos | `proteccion-civil-seguridad-ciudadana-y-bomberos` | 2021-03-11 | 65 |
| Gestión Tributaria | `gestion-tributaria` | 2023-02-07 | 284 |
| Escuela de Folklore | `folklore` | 2023-05-30 | 1 |
| Protección Civil | `proteccion-civil` | 2023-10-13 | 2 |
| Seguridad Ciudadana y Bomberos | `seguridad-ciudadana-y-bomberos` | 2023-10-13 | 2 |

- **Con poca actividad (1 a 3 años):** `policia` (2023-12), `recaudacion-y-tesoreria` (2023-12), `agenda-alcaldia` (2024-10, 217 elementos), `aseo-urbano` y `parques-y-jardines` (2025-01),
  `promocion` e `innovacion-y-turismo` (2025-03), `escuela-de-musica`, `concurso`, `personal` (2025-07, 93 elementos).
- **Solapes entre «padres» antiguos e «hijas» nuevas:** Promoción/Innovación/Turismo (cinco términos), Medio Ambiente/Aseo/Parques (tres), Protección Civil/Seguridad/Bomberos (tres).
- **Numéricas (14, 525 entradas):** `57`, `29`, `37`, `30`, `38`, `33`, `35`, `66`, `31`, `34`, `32`, `75`, `36`, `28`. Parecen identificadores de una importación (inferencia): no se sabe qué área representa cada una.
- **Nombres repetidos:** el término `cultura` se llama «Educación», y existen `educacion` y `cultura-2`.
- **Vacías:** `nuevas-tecnologias` y `cul`.

### Tres modelos posibles

| Modelo | Qué es | A favor | En contra |
|---|---|---|---|
| **A. Mantener la taxonomía y limpiarla** | Se conservan las 58 URLs; se renombran, fusionan y reasignan los términos erróneos; el menú enlaza solo a las áreas reales | Es lo más barato y no rompe URLs; reversible por partes | Hay que revisar a mano ~1000 entradas con nombres numéricos o duplicados |
| **B. Separar «áreas vigentes» de «histórico»** (recomendado) | La taxonomía sigue igual, pero el tema marca solo automáticamente como «archivo histórico» los términos sin contenido nuevo en 3 años, y el ayuntamiento decide cuáles son vigentes | Sin migración ni borrados; se ve a simple vista qué está viejo; las URL antiguas siguen funcionando | No elimina el desorden de fondo (58 términos); necesita la limpieza de A para los nombres |
| **C. Sustituirlas por páginas normales con jerarquía** (una página «Juventud» con hijas) | El personal las edita con el editor de bloques; el menú sale de la jerarquía | Más fácil de entender para quien edita | Se pierde el listado automático de noticias por área; hay que redirigir las 58 URLs |

**Recomiendo B y, en paralelo, A solo para lo evidente** (los 14 numéricos y los nombres repetidos), y **no C** de momento. Lo
que implica B se puede implementar sin tocar la base de datos: en la plantilla de archivo se calcula la fecha de la última entrada del término y, si pasan de 3 años, se muestra un aviso
«Contenido archivado: esta área no publica desde [año]» con enlace al buscador y a la portada.

### Qué necesito del ayuntamiento

Una tabla de decisión por concejalía (puedo prepararla en una hoja con las 58 filas ya rellenadas con los datos de arriba) con tres columnas:
**vigente / histórica / fusionar con…**. Con eso se decide el menú y qué se reasigna. **No toco ningún término sin esa decisión.**

### Cuidado con el nombre «Educación»

Cambiar el *nombre* de un término (no su dirección) no rompe ningún enlace. Pero el término `cultura` (2622 elementos) mezcla de hecho contenido de
cultura y de educación, y el menú enlaza «Educación» a `/concejalia/cultura/`. Antes de renombrarlo hay que decidir si se separa en dos y cómo se reparten
las entradas; hacerlo mal deja noticias de cultura bajo «Educación» o al revés.

## 2. Qué hace falta arreglar en el código (lo puedo hacer yo)

Son defectos del código actual, no decisiones de contenido. Orden sugerido (cada línea, un PR):

| # | Cambio | Por qué | Esfuerzo | Riesgo |
|---|---|---|---|---|
| C1 | **Poner un título y un mensaje de «sin contenido» en los archivos de concejalía y de tema** | Hoy no tienen `<h1>` (medido) y la de concejalía no pinta nada si está vacía: es un problema de accesibilidad y de claridad | Bajo | Bajo |
| C2 | **Que el archivo de una concejalía liste también sus trámites y especiales** (y decidir con el ayuntamiento si las actividades antiguas) | Las consultas piden `post_type => 'post'` (`functions.php`), así que `/concejalia/cultura/` omite 1357 actividades y 7 trámites de los 2622 | Medio | Medio: cambia lo que ve el público |
| C3 | **Aviso automático de «archivo histórico»** (modelo B arriba) | Distingue lo vigente de lo antiguo sin migrar ni borrar | Bajo | Bajo |
| C4 | **Sacar las listas de slugs de `taxonomy-tema.php` a un solo sitio** y que, si un slug cambia, no cambie el diseño en silencio (idealmente un campo por término en ACF) | Hoy 5 slugs usan la tabla y 2 usan otras barras laterales; si se renombra uno vuelve al diseño de tarjetas sin avisar | Medio | Bajo |
| C5 | **Corregir la paginación del diseño de tabla** (`the_posts_navigation()` usa la consulta principal, no la de 25 entradas) y la de `page-tramite.php` (24 sin paginar; hoy hay justo 24 trámites) | Con una entrada más, una se queda sin verse | Bajo | Bajo |
| C6 | **Un test que compruebe que existen las direcciones escritas a mano** (`/tema/…`, `/concejalia/…` de «Para ti», los `/tramite/<slug>/` de «Trámites por situaciones») | Hoy un cambio de nombre en WordPress da un 404 sin aviso | Medio | Bajo (el test no toca la web) |
| C7 | **Proteger las llamadas a ACF** (`function_exists('get_field')`) y la dependencia de Smart Slider en la portada | Con ACF desactivado habría un error fatal en casi todas las páginas (INFERIDO); la portada pinta `[smartslider3 slider="2"]` si no hay diapositivas | Bajo | Bajo |
| C8 | **Retirar código muerto:** la barra `sidebar-ayuntamiento`, los ajustes del Personalizador que nadie lee, `template-parts/content-home.php` | Reduce lo que hay que mantener | Bajo | Bajo |
| C9 | **Decidir qué hacer con `taxonomy-seccion.php`** | Pide una barra lateral que no existe, no tiene paginación y nada del tema enlaza a `/seccion/`; ver el punto 3 | Bajo | Medio (SEO) |
| C10 | **Páginas de la portada que se buscan por título exacto** (pie: «Agenda», «Ayudas», «Aviso legal»…, y «Noticias») → usar el slug o el ID | Si alguien retitula una página, el enlace del pie sale vacío (ya hay un «Ayudas y subvenciones» con enlace vacío) | Bajo | Bajo |

C1, C3, C5, C7 y C8 se pueden hacer ya y juntos o por separado, sin decidir nada con nadie. C2 y C4 conviene confirmarlos antes.

Un punto aparte, para decidir: hay `<style>` y `<script>` en línea (`functions.php`, `inc/custom-header.php`) que el CLAUDE.md prohíbe, y un campo «Javascript
personalizado» en el Personalizador que permite pegar JS arbitrario en todas las páginas. Los colores del Personalizador necesitan valores en tiempo de ejecución,
así que no es trivial quitarlos del todo; conviene decidir si se mantiene como excepción documentada o se rediseña.

## 3. Los restos del tema anterior: `actividad`, `servicio`, `seccion`, `seccion-principal`

**Datos:** `actividad` 2092 elementos (2091 con fecha 2021-03-22, probable migración, y uno de 2023); `servicio` 100 términos; `seccion` 258 términos (63 numéricos, 9 vacíos);
`seccion-principal` y `home_section_category`, sin datos medidos. El tema actual no los usa para nada y el menú no los enlaza.

**Mi recomendación: medir antes de actuar.** Si Google los indexa o hay enlaces externos, quitarlos sin redirección pierde visitas. Lo que haría, en orden:

1. **Tú o quien tenga acceso a Search Console / analítica:** mirar cuántas visitas reciben `/actividad/`, `/seccion/` y `/servicio/` en los últimos 12 meses. Yo no tengo ese dato.
2. Si **tienen tráfico:** mantenerlos y, como mucho, mejorar su plantilla (hoy `seccion` está rota).
3. Si **no tienen tráfico:** ocultarlos a los buscadores (`noindex`) primero y, pasado un tiempo, desactivar el tipo en CPT UI con una **copia de seguridad previa**. Es un cambio en un plugin de la base de datos, no en el tema, y hay que
   hacerlo con cuidado: desactivar el tipo oculta las URLs (404) sin borrar el contenido.

**No recomiendo borrar nada** ahora; las 2092 actividades son contenido con histórico (Cine Club Duero, etc.) que alguien puede querer consultar.

## 4. Especiales

**Qué son:** tarjetas de la portada (11 hoy, de 2021 a 2026) que enlazan a trámites o webs; el tema las ordena solo por fecha de publicación y pinta todas.

| Fecha | Especial |
|---|---|
| 2026-06-25 | Unidades DIR3 · XXV Certamen de Bandas |
| 2025-11 | Monográfico UBU · Programación escénica otoño 2026 |
| 2025-03 | Plan de Sostenibilidad Turística (PSTD) · PMUS · Agenda Urbana |
| 2024 | Ordenanza PGOU · Calendario fiscal 2026 |
| 2023 y 2021 | Ordenanzas Municipales · Oficina Virtual del Catastro |

**Problemas:** no hay campo de orden (para subir uno hay que cambiarle la fecha); la sección sale aunque no haya ninguno; no caducan solos («XXV Certamen de Bandas» sigue ahí; no he comprobado la fecha del certamen).
**Propuestas:**

- **E1 (código, bajo):** orden manual (el campo estándar «Orden» de WordPress o uno de ACF) y que la sección se oculte si no hay especiales.
- **E2 (decisión del ayuntamiento):** revisar cada uno cada cierto tiempo: ¿sigue vigente? Un recordatorio, igual que el del buscador, evita que se acumulen.
- **E3 (opcional):** un campo «Mostrar hasta» para que caduquen solos. Más cómodo, pero añade un campo a ACF.

Con 11 elementos, convertirlo en otra cosa (un menú, un bloque) no compensa: **recomiendo mantenerlo como tipo propio**.

## 5. Lo que NO recomiendo (por ahora)

- **Unificar `tema` y `concejalia` en una sola taxonomía.** Es lo más limpio sobre el papel, pero hay 2550 entradas con ambas, el menú enlaza a las dos y habría que migrar términos y redirigir URLs. El problema real son los nombres y los términos sobrantes, no que haya dos taxonomías.
- **Una plantilla por término** (`taxonomy-tema-<slug>.php`): es justo lo que se evitó, porque se desfasan (`taxonomy-tema.php`, comentario del propio fichero).
- **Borrar términos o tipos** sin medir tráfico y sin redirección.
- **Menú generado automáticamente desde los términos:** con 58 términos, la mayoría antiguos o numéricos, saldría un menú ilegible. Antes hay que limpiar.

## 6. Orden de trabajo propuesto

| Fase | Qué | Quién decide | Depende de |
|---|---|---|---|
| 1 | C1, C3, C5, C7, C8, C10 (arreglos de código sin decisiones de contenido) | Yo, con tu visto bueno | Nada |
| 2 | Tabla de decisión de las 58 concejalías | Ayuntamiento | Fase 1 (para que el aviso «histórico» ya esté) |
| 3 | Limpieza de términos (numéricos, nombres repetidos) y revisión del menú «Mi ciudad» | Ayuntamiento + yo | Fase 2 |
| 4 | C2 y C4 (qué lista cada archivo, diseños por campo de término) | Tú | Fase 2 |
| 5 | Medir el tráfico de los restos y decidir sobre `actividad`/`seccion`/`servicio` | Quien tenga Search Console | Nada |
| 6 | Especiales: E1 (orden) y revisión periódica | Yo + ayuntamiento | Nada |

## Preguntas abiertas

1. ¿Qué concejalías existen hoy como **áreas del ayuntamiento**? El directorio de contacto lista 26 departamentos; ¿es la lista buena para partir?
2. ¿Qué son las 14 concejalías numéricas? Alguien con acceso al administrador puede ver qué entradas tienen (por ejemplo la `29`: 131 entradas hasta 2025-10).
3. ¿Se quiere que el menú «Concejalías» del Ayuntamiento enlace a cada área (con su archivo de noticias) o que siga siendo solo el directorio de contacto?
4. ¿Se quiere separar «Educación» de «Cultura» en la taxonomía?
5. ¿Hay tráfico en `/actividad/`, `/seccion/` y `/servicio/`?
6. ¿Alguien usa los archivos de concejalía/tema desde fuera (enlaces en PDF, redes, otras webs)?

## Límites de esta propuesta

- Los datos vienen de la API pública de WordPress; no he visto el administrador, ni la configuración de CPT UI y de ACF, ni las visitas.
- Que las concejalías numéricas procedan de una importación, y lo mismo el pico de 2023, son **inferencias**.
- La clasificación activa/poca actividad/antigua usa la fecha del último contenido y es un criterio mío, no una valoración de si el área existe.
- No he comprobado qué contenido muestra cada archivo de concejalía en HTML (solo que responde 200 y su título); la afirmación de que no lista trámites sale del código.
