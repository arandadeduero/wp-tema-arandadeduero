<?php
use WP_Mock\Tools\TestCase;

require_once dirname( __DIR__ ) . '/inc/eu-funds.php';

class EuFundsTest extends TestCase {

	use Aranda_WP_Stubs;

	/**
	 * Meta of the projects of the test: post ID => meta key => value.
	 *
	 * @var array<int, array<string, string>>
	 */
	private $meta = array(
		1 => array(
			'_aranda_eu_programme' => 'PIREP · PRTR C02.I05',
			'_aranda_eu_grant'     => '1672367.45',
			'_aranda_eu_status'    => 'Terminado',
		),
		2 => array( '_aranda_eu_programme' => 'PSTD · PRTR C14.I1' ),
	);

	private function project( $id ) {
		$post     = new WP_Post();
		$post->ID = $id;

		return $post;
	}

	public function setUp(): void {
		parent::setUp();
		$this->stub_wp_basics();
		$meta = $this->meta;
		WP_Mock::userFunction( 'get_post_meta' )->andReturnUsing(
			function ( $id, $key ) use ( $meta ) {
				return $meta[ $id ][ $key ] ?? '';
			}
		);
	}

	public function test_amounts_read_as_in_spain() {
		$this->assertSame( '1.672.367,45 €', aranda_de_duero_eu_amount_text( 1672367.45 ) );
		$this->assertSame( '0,00 €', aranda_de_duero_eu_amount_text( 0 ) );
	}

	public function test_a_project_shows_only_the_fields_it_has() {
		$this->assertSame(
			array(
				'Programa'                  => 'PIREP · PRTR C02.I05',
				'Ayuda de la Unión Europea' => '1.672.367,45 €',
				'Estado'                    => 'Terminado',
			),
			aranda_de_duero_eu_project_data( 1 )
		);
	}

	public function test_the_cards_and_the_total_skip_what_is_missing() {
		WP_Mock::userFunction( 'get_the_title' )->andReturnUsing(
			function ( $post ) {
				return 1 === $post->ID ? 'Piscina cubierta' : 'PSTD';
			}
		);
		WP_Mock::userFunction( 'get_permalink' )->andReturn( 'https://www.arandadeduero.es/fondos-europeos/x/' );

		$projects = array( $this->project( 1 ), $this->project( 2 ) );
		$cards    = aranda_de_duero_eu_project_cards( $projects );

		$this->assertSame( 'PIREP · PRTR C02.I05 · Terminado · 1.672.367,45 € de la UE', $cards[0]['text'] );
		$this->assertSame( 'PSTD · PRTR C14.I1', $cards[1]['text'] );
		$this->assertEqualsWithDelta( 1672367.45, aranda_de_duero_eu_funds_total( $projects ), 0.001 );
	}
}
