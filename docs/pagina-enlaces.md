# Página de enlaces («Enlaces de interés»)

Sustituye a la página hecha con Elementor (`/enlaces-de-interes/`), que era la única que usaba ese plugin. Es una página
de enlaces tipo «link en la bio»: logotipo, título y grupos de botones, **sin la cabecera ni el menú** del sitio (con pie y
aviso de cookies). La plantilla es `page-enlaces.php`; el estilo, el bloque «Página de enlaces» de `css/aranda-de-duero.css`.

## Cómo migrar la página
1. En **Páginas → Enlaces de interés**, cambia la **plantilla** (Ajustes de la página, a la derecha) a
   «**Plantilla de enlaces (sin cabecera)**».
2. En la pestaña **Contenido**, borra el contenido de Elementor («Volver al editor de WordPress» si lo ofrece, o crea una
   página nueva con ese título y la misma dirección).
3. Abre el menú de los tres puntos (arriba a la derecha) → **Editor de código** y pega el bloque de abajo. Vuelve al
   **Editor visual** y publica.
4. Cuando la página se vea bien, desactiva **Elementor** y **Web Accessibility** (Elementor).

## Cómo se edita después
- Cada grupo es un bloque **Grupo** con la clase adicional `enlaces-grupo`, un **Encabezado** (nivel 2) y un bloque
  **Botones** con un botón por enlace. Para añadir un enlace: nuevo botón con el texto y la dirección.
- El **icono** se elige solo según la dirección: `instagram.com`, `facebook.com`, `tiktok.com`, `x.com` y `youtube.com`
  tienen el suyo; cualquier otra dirección lleva una flecha.

## Contenido (bloques de WordPress)
Basado en la página actual. **Dos enlaces de la página original no se han incluido porque están mal**:
- *Aranda ME GUSTA → Facebook* apunta a `facebook.com/search/top` (una búsqueda, no un perfil).
- *Protección Civil → «Instagram»* apunta a `facebook.com/BiblioAranda` (la Biblioteca); falta su enlace.
Añádelos cuando tengas las direcciones correctas.

```html
<!-- wp:group {"className":"enlaces-grupo","layout":{"type":"constrained"}} -->
<div class="wp-block-group enlaces-grupo"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Ayuntamiento Aranda de Duero</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.arandadeduero.es/">Página Web</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.instagram.com/ayuntamientoarandadeduero/">Instagram</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.facebook.com/aytoarandadeduero">Facebook</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.tiktok.com/@aytoarandadeduero">TikTok</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://x.com/aytoaranda">X (Twitter)</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"enlaces-grupo","layout":{"type":"constrained"}} -->
<div class="wp-block-group enlaces-grupo"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Aranda ME GUSTA</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.instagram.com/arandamegusta">Instagram</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://x.com/ArandaMeGusta">X (Twitter)</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.youtube.com/@SMArandaDeDuero">YouTube</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"enlaces-grupo","layout":{"type":"constrained"}} -->
<div class="wp-block-group enlaces-grupo"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Policía Local</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.instagram.com/policialocalad/">Instagram</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://x.com/PoliciaLocalAD">X (Twitter)</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"enlaces-grupo","layout":{"type":"constrained"}} -->
<div class="wp-block-group enlaces-grupo"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Bomberos</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.instagram.com/bomberosaranda/">Instagram</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"enlaces-grupo","layout":{"type":"constrained"}} -->
<div class="wp-block-group enlaces-grupo"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">CEAS</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.instagram.com/ceas.aranda/">Instagram</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"enlaces-grupo","layout":{"type":"constrained"}} -->
<div class="wp-block-group enlaces-grupo"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Deportes</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.instagram.com/concejaliadeportes_aranda/">Instagram</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.facebook.com/p/Arandactiva-100071367133529/">Facebook</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.tiktok.com/@concejalia.deportes">TikTok</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"enlaces-grupo","layout":{"type":"constrained"}} -->
<div class="wp-block-group enlaces-grupo"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Biblioteca</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.instagram.com/biblio.aranda/">Instagram</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.facebook.com/BiblioAranda">Facebook</a></div>
<!-- /wp:button -->
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://linktr.ee/biblioaranda">Biblioaranda (Linktree)</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
```
