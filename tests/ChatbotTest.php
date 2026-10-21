<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/template-functions.php';
require_once dirname( __DIR__ ) . '/inc/chatbot.php';

class ChatbotTest extends TestCase {

	public function setUp(): void {
		parent::setUp();
		WP_Mock::userFunction( 'home_url' )->andReturnUsing(
			function ( $path = '' ) {
				return 'https://www.arandadeduero.es' . $path;
			}
		);
		WP_Mock::userFunction( 'apply_filters' )->andReturnUsing(
			function ( $tag, $value ) {
				return $value;
			}
		);
	}

	public function test_only_administrators_see_the_assistant() {
		WP_Mock::userFunction( 'is_user_logged_in' )->andReturn( true );
		WP_Mock::userFunction( 'current_user_can' )->with( 'manage_options' )->andReturn( true );

		$this->assertTrue( aranda_de_duero_chatbot_enabled() );
	}

	public function test_a_logged_in_user_without_administration_rights_does_not() {
		WP_Mock::userFunction( 'is_user_logged_in' )->andReturn( true );
		WP_Mock::userFunction( 'current_user_can' )->with( 'manage_options' )->andReturn( false );

		$this->assertFalse( aranda_de_duero_chatbot_enabled() );
	}

	public function test_the_prompt_has_the_question_once_and_names_every_official_website() {
		$prompt = aranda_de_duero_chatbot_prompt();

		$this->assertSame( 1, substr_count( $prompt, ARANDA_DE_DUERO_CHATBOT_PLACEHOLDER ) );
		foreach ( array_keys( aranda_de_duero_chatbot_sources() ) as $host ) {
			$this->assertStringContainsString( $host, $prompt );
		}
	}

	public function test_the_prompt_closes_the_scope_and_asks_for_neutrality() {
		$prompt = aranda_de_duero_chatbot_prompt();

		$this->assertStringContainsString( 'SOLO sobre asuntos municipales', $prompt );
		$this->assertStringContainsString( 'no hagas valoraciones políticas', $prompt );
		$this->assertStringContainsString( 'No inventes', $prompt );
		$this->assertStringContainsString( 'No uses, cites ni menciones ninguna otra web', $prompt );
		$this->assertStringContainsString( '947 500 100', $prompt );
	}

	public function test_the_prompt_fits_in_the_address_of_the_new_tab() {
		// A question of the maximum length, with accents as Spanish has them.
		$question = substr( str_repeat( '¿Cómo pido la cita previa para el padrón? ', 20 ), 0, ARANDA_DE_DUERO_CHATBOT_MAX_QUESTION );
		$longest  = str_replace( ARANDA_DE_DUERO_CHATBOT_PLACEHOLDER, $question, aranda_de_duero_chatbot_prompt() );

		$this->assertLessThan( 7000, strlen( rawurlencode( $longest ) ) );
	}
}
