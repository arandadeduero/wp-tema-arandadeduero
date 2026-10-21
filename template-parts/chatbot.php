<?php
/**
 * Bubble of the virtual assistant (bottom right of every page) and its panel. See inc/chatbot.php and js/chatbot.js.
 *
 * @package Aranda_de_Duero
 */

?>
<div class="chatbot" data-chatbot>
	<div class="chatbot-panel" id="chatbot-panel" role="dialog" aria-labelledby="chatbot-title" hidden data-chatbot-panel>
		<button type="button" class="chatbot-close" data-chatbot-close>
			<span aria-hidden="true">
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
				echo aranda_de_duero_icon( 'aranda-de-duero/xmark', array( 'size' => 18 ) );
				?>
			</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Cerrar el asistente virtual', 'aranda-de-duero' ); ?></span>
		</button>
		<p class="chatbot-kicker"><?php esc_html_e( 'Asistente virtual', 'aranda-de-duero' ); ?></p>
		<h2 class="chatbot-title" id="chatbot-title"><?php esc_html_e( 'Preguntas sobre el Ayuntamiento', 'aranda-de-duero' ); ?></h2>
		<form class="chatbot-form" data-chatbot-form>
			<label class="screen-reader-text" for="chatbot-question"><?php esc_html_e( 'Escribe tu pregunta', 'aranda-de-duero' ); ?></label>
			<textarea class="chatbot-question" id="chatbot-question" rows="4" maxlength="<?php echo esc_attr( (string) ARANDA_DE_DUERO_CHATBOT_MAX_QUESTION ); ?>" placeholder="<?php esc_attr_e( '¿Cómo pido cita previa para el padrón?', 'aranda-de-duero' ); ?>" data-chatbot-question></textarea>
			<p class="chatbot-counter" data-chatbot-counter>0 / <?php echo esc_html( (string) ARANDA_DE_DUERO_CHATBOT_MAX_QUESTION ); ?></p>
			<button type="submit" class="chatbot-send"><?php esc_html_e( 'Enviar pregunta', 'aranda-de-duero' ); ?></button>
		</form>
		<p class="chatbot-notice"><?php esc_html_e( 'Se enviará tu consulta a ChatGPT, un servicio de un tercero, y se abrirá en una pestaña nueva. Verifica la información: puede contener inexactitudes.', 'aranda-de-duero' ); ?></p>
		<p class="chatbot-test"><?php esc_html_e( 'Prueba: solo lo ven los administradores.', 'aranda-de-duero' ); ?></p>
	</div>
	<button type="button" class="chatbot-toggle" aria-expanded="false" aria-controls="chatbot-panel" data-chatbot-open>
		<span aria-hidden="true">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- aranda_de_duero_icon() returns SVG markup already sanitized by the WP 7.1 SVG Icon API (see inc/icons.php).
			echo aranda_de_duero_icon( 'aranda-de-duero/comment-dots', array( 'size' => 28 ) );
			?>
		</span>
		<span class="screen-reader-text"><?php esc_html_e( 'Abrir el asistente virtual', 'aranda-de-duero' ); ?></span>
	</button>
</div>
