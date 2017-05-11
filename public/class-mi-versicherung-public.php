<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://mai-internet.de
 * @since      1.0.0
 *
 * @package    Mi_Versicherung
 * @subpackage Mi_Versicherung/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mi_Versicherung
 * @subpackage Mi_Versicherung/public
 * @author     Michael Mai <mmai.mai.internet@gmail.com>
 */
class Mi_Versicherung_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mi_Versicherung_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mi_Versicherung_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mi-versicherung-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mi_Versicherung_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mi_Versicherung_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mi-versicherung-public.js', array( 'jquery' ), $this->version, false );

	}

	function generate_versicherung_rules() {
		add_rewrite_rule( '^versicherung/([^/]*)/([^/]*)/?',
			'index.php?zielgruppe=$matches[1]&versicherungsgruppe=$matches[2]',
			'top' );
	}

	function register_shortcode_tarifrechnerliste() {
		add_shortcode( 'mi_versicherung_liste_tarifrechner', function ( $atts ) {
			$args       = array(
				'post_type'      => 'versicherung',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'orderby'        => 'post_title',
				'order'          => 'ASC',
			);
			$a          = shortcode_atts( array(
				'range' => 'all'
			), $atts );
			$objQuery   = new WP_Query( $args );
			$arrList    = array();
			$renderLink = function ( $row ) {
				$titel = $row['titel'];
				if ( $titel == '' ) {
					// Alte Version:
					$titel = $row['title'];
				}

				return sprintf( '<a href="%s" class="link_tarifrechner">%s</a>', $row['url'], $titel );
			};
			$renderAsUL = function ( $arrList ) {
				if ( count( $arrList ) == 0 ) {
					return '';
				} else {
					$html = '<ul>';
					foreach ( $arrList as $row ) {
						$html .= ( '<li>' . $row . '</li>' );
					}

					return $html . '</ul>';
				}
			};
			if ( $objQuery->have_posts() ) {
				// $base_url = get_site_url().'/tarifrechner-run/';
				global $post;
				while ( $objQuery->have_posts() ) {
					$objQuery->the_post();
					$tarifrechner = get_field( 'tarifrechner' );
					if ( strlen( $tarifrechner ) ) {
						$arrList[] = $renderLink( array(
							'title' => get_the_title(),
							'url'   => MI_Versicherung::mi_get_url_tarifrechner_call( $post->post_name )
						) );
					}
				}
				wp_reset_postdata();
				if ( count( $arrList ) == 0 ) {
					return '';
				} elseif ( count( $arrList ) == 1 ) {
					if ( $a['range'] == 'rest' ) {
						return '';
					} else {
						return $renderAsUL( $arrList );
					}
				} else {
					$sum   = count( $arrList );
					$first = 0;
					$last  = $sum - 1;
					$mid   = floor( $sum / 2 );
					if ( $a['range'] == 'all' ) {
						return $renderAsUL( $arrList );
					} elseif ( $a['range'] == 'first' ) {
						$arrNew = array_slice( $arrList, $first, $mid );

						return $renderAsUL( $arrNew );
					} elseif ( $a['range'] == 'last' ) {
						$arrNew = array_slice( $arrList, $mid + 1, $last );

						return $renderAsUL( $arrNew );
					} else {
						return '';
					}
				}
			} else {
				return '';
			}
		} );
	}

	function register_shortcode_template_fields() {

		add_shortcode( 'mi_versicherung', function ( $atts ) {
			$a         = shortcode_atts( array(
				'fieldname' => 'title'
			), $atts );
			$fieldname = $a['fieldname'];
			global $post;
			$arrSimple = array(
				'titel',
				'untertitel',
				'intro',
				'video_url'
			);
			if ( in_array( $fieldname, $arrSimple ) ) {
				return get_field( $fieldname );
			} elseif ( $fieldname == 'title' ) {
				return get_the_title();
			} else {
				return '';
			}
		} );


		add_shortcode( 'mi_vfield_titel', function () {
			return get_field( 'titel' );
		} );

		add_shortcode( 'mi_vfield_intro', function () {
			return get_field( 'intro' );
		} );


		add_shortcode( 'mi_vfield_broschuere', function () {
			$mi_item = '<div data-animation-delay="0.0">
<div class="">
<span class="fl-icon-wrap">
<span class="fl-icon"><a href="%s" target="_self"><i class="fa fa-file-pdf-o"></i> </a></span>
<span class="fl-icon-text"><a href="%s" target="_self">%s (%s)</a></span>
</span>
</div>
</div>';
			$html    = '';
			while ( have_rows( 'broschuere' ) ) : the_row();

				$titel    = get_sub_field( 'broschuere_titel' );
				$id       = get_sub_field( 'broschuere_id' );
				$url      = wp_get_attachment_url( $id );
				$filesize = filesize( get_attached_file( $id ) );
				$filesize = size_format( $filesize, 2 );
				$link     = sprintf(
					$mi_item,
					$url,
					$url,
					$titel,
					$filesize
				);
				$html     .= ( $link . '<br>' );
			endwhile;

			return $html;
		} );

		add_shortcode( 'mi_vfield_tarifrechner_url', function () {
			global $post;
			return MI_Versicherung::mi_get_url_tarifrechner_call( $post->post_name );
		} );

		add_shortcode( 'mi_vfield_video', function () {
			$video_field = get_field( 'video_url' );
			if ( ! strlen( $video_field ) ) {
				return '';
			}
			//
			if ( strpos( $video_field, '<iframe' ) !== false ) {
				return $video_field;
			} else {
				return sprintf( '<iframe width="600" height="550" src="%s"></iframe>', $video_field );
			}
		} );

	}

	function register_filter_content() {
		add_filter( 'the_content', function ( $content ) {
			if ( $GLOBALS['post_type'] == 'versicherung' ) {
				// Das aktuelle Posts Objekt ist nicht mehr Versicherung an dieser Stelle sondern das Tunnel-Template
				// Der Versicherungs-CPT versteckt sich im Head des posts Arrays:
				$objVersicherung = $GLOBALS['posts'][0];
				if ( $objVersicherung instanceof WP_Post ) {
					$id = $GLOBALS['posts'][0]->ID;
					if ( ! have_rows( 'broschuere', $id ) ) {
						$content = str_replace( 'mi-nop-broschuere', 'mi-hide', $content );
					}
					if ( ! strlen( get_field( 'tarifrechner', $id ) ) ) {
						$content = str_replace( 'mi-nop-tarifrechner', 'mi-hide', $content );
					}
					if ( ! strlen( get_field( 'video_url', $id ) ) ) {
						$content = str_replace( 'mi-nop-video', 'mi-hide', $content );
					}
				}
			}

			return $content;
		} );
	}

	function register_query_vars() {
		add_filter( 'query_vars', function ( $vars ) {
			$vars[] = "tarifrechner_call";

			return $vars;
		} );
	}

	function register_search_query() {
		add_action( 'pre_get_posts', function ( WP_Query $query ) {
			if ( ! $query->is_admin && $query->is_search && $query->is_main_query() ) {
				$query->set( 'post__not_in', array( 398 ) );
			}
		} );
	}



}
