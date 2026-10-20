# Estado actual de los contenidos y las concejalías (medido el 2026-10-20)

Datos **medidos en la web publicada** para saber qué se usa de verdad. Sirven de base a la
[propuesta de reorganización](propuesta-reorganizacion-contenidos.md); para entender cómo funciona cada pieza en el código, ver
[taxonomías y concejalías](taxonomias-y-concejalias.md) y [tipos de contenido y elementos propios](tipos-de-contenido-y-elementos-propios.md).

## Cómo se midió (y límites)

- **Método:** API REST pública de WordPress (`/wp-json/wp/v2/…`), solo lectura, 132 peticiones secuenciales con 3 s de espera, sin
  ningún bloqueo. Se bajaron todas las entradas y los términos y los recuentos y fechas se calcularon en local. Los totales
  (58 concejalías, 2092 `actividad`, 258 `seccion`, 24 `tramite`) se volvieron a comprobar con una petición aparte y coinciden.
- **Clasificación de las concejalías (criterio mío, no oficial):** se mira la fecha del contenido **más reciente** de cada una (entrada,
  trámite, especial o actividad). *Activa*: hasta 1 año; *poca actividad*: de 1 a 3 años; *antigua*: más de 3 años; *vacía*: sin
  ningún elemento. Es una medida de uso, no de importancia: no dice si el área sigue existiendo.
- **No se midió:** el tipo `portada_slide` (no es público en la API), el campo ACF `especiales_enlace` (la API no lo expone), cuántos
  elementos lista cada página de archivo en HTML, ni los enlaces a otros dominios. Qué significan las concejalías con nombre numérico
  y por qué hay 506 entradas en 2023 son **inferencias**, no mediciones.

## Dos cosas distintas llamadas «concejalías»

1. **El menú «Ayuntamiento → Concejalías»** lleva a `/informacion-de-concejalias/`, una **página normal** que es un directorio de
   contacto de 26 departamentos (correo, dirección y teléfono; de «Aguas» a «Turismo»). **No enlaza a ninguna página de la
   taxonomía** (0 enlaces a `/concejalia/`). Está a mano, en el contenido de la página.
2. **La taxonomía `concejalia`** (58 términos) clasifica entradas, trámites, especiales y actividades. Sus páginas
   `/concejalia/<slug>/` existen y responden 200, pero **el menú principal solo enlaza a 3**: «Educación» (que apunta a `/concejalia/cultura/`),
   `juventud` y `urbanismo-y-vivienda`.

Esa confusión explica por qué «hay un menú de concejalías» pero la mayoría de las 58 no se ven desde ningún sitio.

## Tipos de contenido y taxonomías que existen

Recuento de elementos: **4072 entradas**, **2092 actividades**, **24 trámites**, **11 especiales**.

| Taxonomía | Nombre | Se aplica a | Estructura |
|---|---|---|---|
| `concejalia` | Concejalías | post, actividad, especial, tramite | plana |
| `tema` | Temas | post, actividad | plana |
| `seccion` | Secciones | post, actividad | plana |
| `servicio` | Servicios | post, actividad | plana |
| `categoria_tramite` | Categorías de Trámite | tramite | plana |
| `home_section_category` | Categorias de Sección Principal | seccion-principal | jerárquica |
| `category` | Categorías | post | jerárquica |
| `post_tag` | Etiquetas | post | plana |

- Las taxonomías propias son **planas** (sin padres ni hijos): no hay jerarquía de concejalías.
- **El tema no usa** `servicio`, `home_section_category` ni los tipos `actividad` y `seccion-principal` (ninguna referencia en el código; ver
  [taxonomías](taxonomias-y-concejalias.md)). Son restos de un tema anterior: `actividad` tiene 2092 elementos, 2091 con fecha 2021-03-22
  (parece una migración) y uno de 2023, y `servicio` tiene 100 términos. Las URL de `actividad` siguen siendo públicas
  (por ejemplo `/actividad/cine-club-duero-…/`).
- Términos: `concejalia` 58 (14 con slug numérico, 2 vacíos), `tema` 84 (16 numéricos, 3 vacíos), `seccion` 258 (63 numéricos, 9 vacíos),
  `servicio` 100 (36 numéricos, 1 vacío), `categoria_tramite` 4. Los numéricos son identificadores sin nombre legible.
- De las 4072 entradas: 182 no tienen concejalía; 1518 no tienen tema; 1127 no tienen sección; 81 tienen más de una
  concejalía; 2550 tienen tema **y** concejalía a la vez.
- Entradas por año: 2026: 131 · 2025: 158 · 2024: 164 · 2023: 506 · 2022: 113 · 2021: 105 · 2020: 179 · 2019: 232… (2023 es un pico anómalo: ~506, casi todo cargado a mediados de enero; inferencia: una importación).

## Las 58 concejalías

Resumen por mi criterio: **24 activas**, **12 con poca actividad**, **20 antiguas** y **2 vacías**.
Suman 6150 asignaciones (un elemento puede tener varias).

- **14 tienen nombre y slug solo numéricos** («29», «57», «37»…), con 525 entradas entre todas y su página de
  archivo pública con el título literal «29 – Ayuntamiento de Aranda de Duero». Su contenido es variado (actividades, cursos, conciertos): parecen identificadores de una
  importación y no una concejalía real (inferencia).
- **Nombres repetidos:** el término `cultura` se llama **«Educación»** (2622 elementos) y existen además `educacion` («Educación») y
  `cultura-2` («Cultura»): hay dos «Educación» y dos «Cultura». El menú enlaza «Educación» a `/concejalia/cultura/`.
- **Padres antiguos y sus hijas modernas** conviven: `promocion-innovacion-y-turismo` (antigua) junto a `promocion`,
  `innovacion-y-turismo`, `promocion-y-turismo` y `turismo`; lo mismo con medio ambiente, protección civil y acción social/mujer/salud.
- **Vacías:** `nuevas-tecnologias`, `cul`; aun así su archivo `/concejalia/nuevas-tecnologias/` responde 200 (una página vacía, sin título visible).

| Concejalía | Slug | Elementos | Tipos | Último contenido | Estado | En el menú |
|---|---|---|---|---|---|---|
| participación ciudadana | `participacion-ciudadana` | 1 | post 1 | 2026-09-24 | activa |  |
| Festejos | `festejos` | 295 | post 67, actividad 228 | 2026-09-09 | activa |  |
| barrios | `barrios` | 1 | post 1 | 2026-08-14 | activa |  |
| Secretaría general | `secretaria-general` | 86 | post 78, actividad 7, tramite 1 | 2026-07-10 | activa |  |
| Acción Social, Mujer, Salud e Igualdad de Oportunidades | `accion-social-mujer-salud-e-igualdad-de-oportunidades` | 398 | post 360, actividad 38 | 2026-06-26 | activa |  |
| Cultura | `cultura-2` | 6 | post 5, especial 1 | 2026-06-25 | activa |  |
| Juventud | `juventud` | 396 | post 172, actividad 224 | 2026-06-17 | activa | sí |
| Educación | `cultura` | 2622 | post 1257, actividad 1357, tramite 7, especial 1 | 2026-06-10 | activa | sí |
| Movilidad | `movilidad` | 2 | post 2 | 2026-05-20 | activa |  |
| Medio Ambiente | `medio-ambiente` | 32 | post 32 | 2026-05-06 | activa |  |
| Servicios sociales | `servicios-sociales` | 20 | post 20 | 2026-05-04 | activa |  |
| Turismo | `turismo` | 241 | post 164, actividad 77 | 2026-04-14 | activa |  |
| Educación | `educacion` | 5 | post 5 | 2026-04-14 | activa |  |
| Promoción y Turismo | `promocion-y-turismo` | 2 | post 2 | 2026-04-08 | activa |  |
| Alcaldía | `alcaldia` | 2 | post 2 | 2026-03-23 | activa |  |
| Deportes | `deportes` | 140 | post 139, actividad 1 | 2026-03-17 | activa |  |
| Comercio y Consumo | `comercio-y-consumo` | 59 | post 55, actividad 4 | 2026-03-13 | activa |  |
| Urbanismo y vivienda | `urbanismo-y-vivienda` | 92 | post 81, actividad 1, tramite 10 | 2026-03-04 | activa | sí |
| Acción Social | `accion-social` | 49 | post 49 | 2025-11-14 | activa |  |
| Mujer | `mujer` | 45 | post 45 | 2025-11-14 | activa |  |
| Salud e Igualdad de Oportunidades | `salud-e-igualdad-de-oportunidades` | 44 | post 44 | 2025-11-14 | activa |  |
| 57 | `57` | 81 | post 81 | 2025-11-06 | activa |  |
| 29 | `29` | 131 | post 131 | 2025-10-28 | activa |  |
| hacienda | `hacienda` | 1 | post 1 | 2025-10-21 | activa |  |
| Personal | `personal` | 93 | post 93 | 2025-07-31 | poca actividad |  |
| Concurso | `concurso` | 3 | post 3 | 2025-05-29 | poca actividad |  |
| Escuela de Música | `escuela-de-musica` | 1 | post 1 | 2025-03-27 | poca actividad |  |
| Promoción | `promocion` | 18 | post 15, especial 3 | 2025-03-23 | poca actividad |  |
| Innovación y Turismo | `innovacion-y-turismo` | 17 | post 15, especial 2 | 2025-03-23 | poca actividad |  |
| 37 | `37` | 98 | post 98 | 2025-01-23 | poca actividad |  |
| Aseo Urbano | `aseo-urbano` | 9 | post 9 | 2025-01-15 | poca actividad |  |
| Parques y Jardines | `parques-y-jardines` | 9 | post 9 | 2025-01-15 | poca actividad |  |
| 30 | `30` | 34 | post 34 | 2024-11-28 | poca actividad |  |
| Agenda (Alcaldía) | `agenda-alcaldia` | 217 | post 108, actividad 108, tramite 1 | 2024-10-28 | poca actividad |  |
| Recaudación y Tesorería | `recaudacion-y-tesoreria` | 4 | post 4 | 2023-12-18 | poca actividad |  |
| Policía | `policia` | 7 | post 7 | 2023-12-12 | poca actividad |  |
| Protección Civil | `proteccion-civil` | 2 | post 2 | 2023-10-13 | antigua |  |
| Seguridad Ciudadana y Bomberos | `seguridad-ciudadana-y-bomberos` | 2 | post 2 | 2023-10-13 | antigua |  |
| Escuela de Folklore | `folklore` | 1 | post 1 | 2023-05-30 | antigua |  |
| 38 | `38` | 17 | post 17 | 2023-02-15 | antigua |  |
| Gestión Tributaria | `gestion-tributaria` | 284 | post 273, actividad 5, tramite 5, especial 1 | 2023-02-07 | antigua |  |
| 33 | `33` | 23 | post 23 | 2023-02-07 | antigua |  |
| 35 | `35` | 40 | post 40 | 2023-01-19 | antigua |  |
| 66 | `66` | 8 | post 8 | 2023-01-12 | antigua |  |
| 31 | `31` | 79 | post 79 | 2023-01-12 | antigua |  |
| 34 | `34` | 6 | post 6 | 2023-01-12 | antigua |  |
| 32 | `32` | 2 | post 2 | 2023-01-12 | antigua |  |
| 75 | `75` | 1 | post 1 | 2023-01-12 | antigua |  |
| 36 | `36` | 2 | post 2 | 2023-01-12 | antigua |  |
| 28 | `28` | 3 | post 3 | 2023-01-12 | antigua |  |
| Protección Civil, Seguridad Ciudadana y Bomberos | `proteccion-civil-seguridad-ciudadana-y-bomberos` | 65 | post 62, actividad 3 | 2021-03-11 | antigua |  |
| Medio Ambiente, Aseo Urbano, Parques y Jardines | `medio-ambiente-aseo-urbano-parques-y-jardines` | 115 | post 102, actividad 13 | 2021-02-24 | antigua |  |
| Promoción, Innovación y Turismo | `promocion-innovacion-y-turismo` | 193 | post 178, actividad 15 | 2020-11-13 | antigua |  |
| Intervención | `intervencion` | 7 | post 7 | 2018-11-06 | antigua |  |
| Oficina de la Movilidad | `oficina-de-la-movilidad` | 4 | post 4 | 2016-01-18 | antigua |  |
| Hermanamientos | `hermanamientos` | 35 | post 26, actividad 9 | 2015-01-22 | antigua |  |
| Nuevas Tecnologías | `nuevas-tecnologias` | 0 | — | — | vacía |  |
| Cul | `cul` | 0 | — | — | vacía |  |

## El menú principal (31 enlaces de www comprobados: todos 200)

- **Ayuntamiento:** corporación municipal, **Concejalías (el directorio de contacto)**, teléfonos, agenda institucional, comisiones y plenos, imagen institucional, iluminación.
- **Turismo:** enlace externo (`turismo.arandadeduero.es`).
- **Ciudadanía:** barrios, ayudas y subvenciones, preguntas frecuentes, **tributos** (`/tema/tributos/`), trámites y oposiciones (sede).
- **Mi ciudad** (12 entradas): agua, consumo y comercio, **educación**, **cultura**, deportes, **juventud**, movilidad, participación ciudadana, salud, seguridad ciudadana, servicios sociales, urbanismo y vivienda. De ellas, **6 apuntan a `/tema/…`** (consumo, cultura, movilidad, salud, seguridad ciudadana, servicios sociales) y **3 a `/concejalia/…`**; el resto, a páginas propias o a otras webs. Con «Tributos», en «Ciudadanía», son 7 las de `/tema/`.
- **Actualidad:** agenda, bandos, notas de prensa, noticias, expediente de la plaza de toros. Después, «Fondos europeos (PRTR)».
- El menú **nunca enlaza** a `/seccion/` ni a `/tramite/`. En el pie hay un «Ayudas y subvenciones» con el enlace vacío, y una entrada de menú sin texto (apunta a `#`).

## Especiales y trámites

- **Especiales:** hay **11**, de 2021-08 a 2026-06; la portada muestra hasta 24, así que salen todos. Según el HTML de la portada, 1 va a un dominio
  externo (Catastro), 3 a subdominios del propio ayuntamiento y 7 a `www`. Cuatro no tienen concejalía.
- **Trámites:** hay **24**, de 2019 a 2024. La plantilla `page-tramite.php` lista como máximo 24 y no pagina: hoy caben justo, y con uno más
  se quedaría uno fuera (no se ha comprobado qué página de la web usa esa plantilla).
