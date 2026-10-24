# Recogida de residuos: qué hacer y cómo

Plantilla `page-residuos.php`, datos de contacto en `inc/waste.php` y días sin depósito en `data/residuos/dias-sin-deposito.json`.

## Resumen

| Qué | Dónde |
|---|---|
| La página | `/recogida-de-residuos/`, con la plantilla «Plantilla Recogida de residuos» |
| Tarjeta «Recogida de residuos» de la portada | `aranda_de_duero_quick_access()` en `inc/services.php`; solo sale si la página está publicada |
| Teléfono de enseres y otros contactos | `aranda_de_duero_waste_info()` en `inc/waste.php` |
| Días en los que no se puede depositar basura | `data/residuos/dias-sin-deposito.json` |
| Textos de los contenedores, punto limpio y atención | `page-residuos.php` |
| Búsqueda: «Recogida de residuos» y «Recogida de enseres» | La tarjeta lleva sus palabras clave; la de enseres está en `aranda_de_duero_service_directory()` de `inc/search.php` |

Fuente del contenido: folleto «La separación de residuos empieza desde casa» (Concejalía de Medio Ambiente), publicado en la noticia
`/reciclaje-la-separacion-de-residuos-empieza-en-casa/`. Los horarios, el punto limpio y los teléfonos salen de ese folleto.

## Primera puesta en marcha

1. `npm run release` y subir **el tema completo**.
2. *Páginas → Añadir nueva*: título «Recogida de residuos», dirección `recogida-de-residuos`, plantilla **Plantilla Recogida de residuos**, y publicar. El contenido del editor sale al final de la página.
3. Comprobar la página, la tarjeta de la portada (tercera o cuarta posición de «Accesos frecuentes») y que el buscador encuentra «basura», «contenedores», «muebles» o «enseres».

## Un día en el que no se puede depositar basura

Añadir el día a `data/residuos/dias-sin-deposito.json` y subir el tema completo:

```json
{
  "days": [
    { "date": "2026-12-25", "reason": "Navidad: no hay recogida" },
    { "date": "2027-01-01" }
  ]
}
```

- `date` (`AAAA-MM-DD`) es obligatorio; `reason` es opcional.
- Ese día, la página y la tarjeta de la portada se ponen en rojo claro con «HOY PROHIBIDO DEPOSITAR BASURA» (y el motivo en la página).
- La página lista los próximos 5 días sin depósito.
- Todavía no se ha facilitado ningún día: el fichero está vacío y nunca se muestra el aviso.
- No hay caché de página desde 2026-10-25, así que el cambio de día es inmediato; con una caché habría que excluir la portada y esta página.

## Cambiar textos o teléfonos

- Teléfonos y correo: `aranda_de_duero_waste_info()` en `inc/waste.php` (los usan la página, la tarjeta y la búsqueda).
- Contenedores, horarios, punto limpio y atención: `page-residuos.php`.
- Sinónimos de la búsqueda: `keywords` de la tarjeta (`inc/services.php`) y de la entrada de enseres (`inc/search.php`), sin acentos.

## Pendiente

- Mapa de contenedores, que se integrará en la propia página (la página ya avisa de que llegará); sección `#mapa` de `page-residuos.php`.
- Los días sin depósito y cualquier otra información que facilite el Ayuntamiento.
