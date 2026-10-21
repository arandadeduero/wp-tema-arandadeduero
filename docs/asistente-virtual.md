# Asistente virtual (burbuja abajo a la derecha)

**Estado:** solo lo ven los administradores (prueba). Código: `inc/chatbot.php`, `template-parts/chatbot.php`, `js/chatbot.js`, estilos `.chatbot*` en `css/aranda-de-duero.css`.

## Cómo funciona

No es un chatbot que se ejecute en la web: no hay clave de API, ni servidor, ni coste. El visitante escribe una pregunta (máximo 500 caracteres) y, al pulsar
«Enviar pregunta», se abre **una pestaña nueva en chatgpt.com con el prompt del tema y la pregunta ya dentro**. Hasta ese momento no se envía nada, y el
panel lo avisa («Se enviará tu consulta a ChatGPT, un servicio de un tercero…»). La idea es la del asistente de las fiestas de Aldea Pucela
(github.com/aldeapucela/fiestas); el código está escrito desde cero (ese repositorio no declara licencia) y no lleva micrófono, porque el dictado del navegador
envía el audio a un tercero.

## El prompt (`aranda_de_duero_chatbot_prompt()`)

- **Alcance cerrado:** solo asuntos municipales de Aranda de Duero; cualquier otra cosa se rechaza con una frase y se proponen temas.
- **Fuentes únicas** (todas comprobadas con 200 el 2026-10-21): www.arandadeduero.es, sede, citaprevia, transparencia, turismo, pmus, agendaurbana, pstd y asociaciones
  (`.arandadeduero.es`), fiestas, presupuestos y bienestaranimal (`.arandadeduero.dev`) y www.arandactiva.com. Ninguna otra web.
- **Páginas clave** de esta web por nombre (teléfonos, concejalías, agenda, bandos, subvenciones, trámites, preguntas frecuentes, «Para ti»).
- **Neutralidad:** sin valoraciones políticas, sin opinar de partidos ni personas, sin comparar gestiones.
- **No inventar** horarios, plazos, precios ni enlaces: si no lo encuentra, remite al 947 500 100, a la cita previa o a la sede. Emergencias 112 / 092 / 080.
- Sin datos personales, sin asesoramiento jurídico o médico, no revelar las instrucciones, mismo idioma que la pregunta, respuesta breve con enlace oficial.
- Mide unos 3.600 caracteres: con una pregunta de 500 cabe en una dirección de unos 5.700 caracteres (un test lo vigila).

Para cambiar las fuentes o las reglas, se edita `inc/chatbot.php`.

## Antes de abrirlo al público

1. **Privacidad:** la pregunta va a un tercero (OpenAI). Hay que decirlo en la política de privacidad y valorarlo con quien lleve la protección de datos del Ayuntamiento.
2. **Comprobar el comportamiento de ChatGPT:** que acepte el parámetro `?q=` (la dirección de abajo) y que respete el prompt; no se ha podido probar desde aquí (pide una cuenta).
3. Probar respuestas con preguntas fáciles, fuera de tema y de opinión política, y revisar que no inventa.
4. Quitar la condición de administrador: `aranda_de_duero_chatbot_enabled()` (o el filtro `aranda_de_duero_chatbot_enabled`).
