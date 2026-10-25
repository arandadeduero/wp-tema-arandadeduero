<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/content-types.php';

class ServicioMunicipalTest extends TestCase {

	public function test_a_concejalia_with_no_services_gives_no_cards() {
		WP_Mock::userFunction( 'get_posts' )->once()->andReturn( array() );

		$this->assertSame( array(), aranda_de_duero_concejalia_service_cards( 'cultura' ) );
	}

	public function test_each_service_becomes_a_card_with_title_text_and_url() {
		$biblioteca = (object) array( 'ID' => 25 );
		$escuela    = (object) array( 'ID' => 66 );

		WP_Mock::userFunction( 'get_posts' )
			->once()
			->with(
				Mockery::on(
					function ( $args ) {
						return 'servicio-municipal' === $args['post_type']
							&& 'cultura' === $args['tax_query'][0]['terms']
							&& 'concejalia' === $args['tax_query'][0]['taxonomy'];
					}
				)
			)
			->andReturn( array( $biblioteca, $escuela ) );

		WP_Mock::userFunction( 'get_the_title' )->andReturnUsing(
			function ( $post ) {
				return 25 === $post->ID ? 'Biblioteca' : 'Escuela de Música';
			}
		);
		WP_Mock::userFunction( 'get_the_excerpt' )->andReturn( '<p>Préstamo de libros y sala de lectura.</p>' );
		WP_Mock::userFunction( 'wp_strip_all_tags' )->andReturnUsing(
			function ( $text ) {
				return trim( strip_tags( $text ) );
			}
		);
		WP_Mock::userFunction( 'get_permalink' )->andReturnUsing(
			function ( $post ) {
				return 25 === $post->ID ? 'https://www.arandadeduero.es/servicios/biblioteca/' : 'https://www.arandadeduero.es/servicios/escuela-de-musica/';
			}
		);

		$cards = aranda_de_duero_concejalia_service_cards( 'cultura' );

		$this->assertCount( 2, $cards );
		$this->assertSame( 'Biblioteca', $cards[0]['title'] );
		$this->assertSame( 'Préstamo de libros y sala de lectura.', $cards[0]['text'] );
		$this->assertSame( 'https://www.arandadeduero.es/servicios/biblioteca/', $cards[0]['url'] );
		$this->assertFalse( $cards[0]['new_tab'] );
		$this->assertSame( 'Escuela de Música', $cards[1]['title'] );
	}
}
