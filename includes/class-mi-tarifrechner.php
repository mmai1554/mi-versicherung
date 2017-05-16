<?php


/**
 * The Business Object Tarifrechner
 *
 * loading and maintaing Tarifrechner Data
 *
 * @since      1.0.0
 * @package    Mi_Tarifrechner
 * @subpackage Mi_Tarifrechner/includes
 * @author     Michael Mai <mmai.mai.internet@gmail.com>
 */
class Mi_Tarifrechner {


	private $objVersicherung = null;
	private $title = '';
	private $tarifrechner = '';
	private $init = false;
	private $found = null;

	public function __construct() {

	}

	public function getByCall($slug) {
		$this->init = true;
		// $slug = get_query_var( 'tarifrechner_call', '' );
		if ( $slug ) {
			$args = [
				'post_type'      => 'versicherung',
				'posts_per_page' => 1,
				'post_name__in'  => [ $slug ]
			];
			/**
			 * @var WP_Post $objVersicherung
			 */
			$this->objVersicherung = array_pop( get_posts( $args ) );
			if ( $this->objVersicherung instanceof WP_Post ) {
				$this->title        = 'Tarifrechner für ' . $objVersicherung->post_title;
				$this->tarifrechner = get_field( 'tarifrechner', $objVersicherung->ID );
				$this->found = true;
			} else {
				$this->found = false;
			}
		} {
			$this->found = false;
		}
	}

	public static function getIFrame() {

	}


}
