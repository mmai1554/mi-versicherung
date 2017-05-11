<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mai-internet.de
 * @since      1.0.0
 *
 * @package    Mi_Versicherung
 * @subpackage Mi_Versicherung/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mi_Versicherung
 * @subpackage Mi_Versicherung/admin
 * @author     Michael Mai <mmai.mai.internet@gmail.com>
 */
class Mi_Versicherung_Admin {

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
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	private $my_database = DB_NAME;

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mi-versicherung-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mi-versicherung-admin.js', array( 'jquery' ), $this->version, false );

	}

	function register_cpt_versicherung() {

		$labels = array(
			'name'                  => _x( 'Versicherungen', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Versicherung', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Versicherung', 'text_domain' ),
			'name_admin_bar'        => __( 'Versicherung', 'text_domain' ),
			'archives'              => __( 'Versicherung Archives', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'Alle Versicherungen', 'text_domain' ),
			'add_new_item'          => __( 'Neue Versicherung', 'text_domain' ),
			'add_new'               => __( 'Neue Versicherung hinzufügen', 'text_domain' ),
			'new_item'              => __( 'Neue Versicherung', 'text_domain' ),
			'edit_item'             => __( 'Versicherung bearbeiten', 'text_domain' ),
			'update_item'           => __( 'Versicherung aktualisieren', 'text_domain' ),
			'view_item'             => __( 'Versicherung anschauen', 'text_domain' ),
			'search_items'          => __( 'Versicherungen durchsuchen', 'text_domain' ),
			'not_found'             => __( 'Versicherung nicht gefunden', 'text_domain' ),
			'not_found_in_trash'    => __( 'Versicherung nicht im Papierkorb gefunden', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Versicherungsliste', 'text_domain' ),
			'items_list_navigation' => __( 'Versicherungen list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter Versicherungen Liste', 'text_domain' ),
		);
		$args   = array(
			'label'               => __( 'Versicherung', 'text_domain' ),
			'description'         => __( 'Definiert ein Versicherungs-Objekt', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'revisions' ),
			'taxonomies'          => array( 'versicherungsgruppe', 'zielgruppe' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'versicherung', $args );
	}

	function register_taxonomies() {

		$labels = array(
			'name'                       => _x( 'Versicherungsgruppen', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Versicherungsgruppe', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Versicherungsgruppe', 'text_domain' ),
			'all_items'                  => __( 'Alle Gruppen', 'text_domain' ),
			'parent_item'                => __( 'Versicherungsgruppe (parent)', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
			'new_item_name'              => __( 'New Item Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Item', 'text_domain' ),
			'edit_item'                  => __( 'Edit Item', 'text_domain' ),
			'update_item'                => __( 'Update Item', 'text_domain' ),
			'view_item'                  => __( 'View Item', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Items', 'text_domain' ),
			'search_items'               => __( 'Search Items', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No items', 'text_domain' ),
			'items_list'                 => __( 'Items list', 'text_domain' ),
			'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'versicherungsgruppe', array( 'versicherung' ), $args );

		$labels = array(
			'name'                       => _x( 'Zielgruppen', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Zielgruppe', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Zielgruppe', 'text_domain' ),
			'all_items'                  => __( 'Alle Gruppen', 'text_domain' ),
			'parent_item'                => __( 'Zielgruppe', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
			'new_item_name'              => __( 'New Item Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Item', 'text_domain' ),
			'edit_item'                  => __( 'Edit Item', 'text_domain' ),
			'update_item'                => __( 'Update Item', 'text_domain' ),
			'view_item'                  => __( 'View Item', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Items', 'text_domain' ),
			'search_items'               => __( 'Search Items', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No items', 'text_domain' ),
			'items_list'                 => __( 'Items list', 'text_domain' ),
			'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'zielgruppe', array( 'versicherung' ), $args );

	}

	function manage_columns() {
		add_filter( 'manage_versicherung_posts_columns', function ( $columns ) {
			$arrReturn = array(
				'cb'                           => '<input type="checkbox" />',
				'title'                        => __( 'Title' ),
				'untertitel'                   => __( 'untertitel' ),
//        'date' => __('Date'),
				'taxonomy-versicherungsgruppe' => 'Versicherungsgruppen',
				'taxonomy-zielgruppe'          => 'Zielgruppen',
				'broschuere'                   => __( 'Broschuere' ),
				'tarifrechner'                 => __( 'Tarifrechner' ),
				'video_url'                    => __( 'Video-URL' )
			);

			// set breakpoint here and lookup array columns:
			return $arrReturn;
		} );

		add_action( 'manage_versicherung_posts_custom_column', function ( $column, $post_id ) {
			global $post;
			switch ( $column ) {
				case 'versicherungskategorie' :
					$terms = get_the_term_list( $post_id, 'versicherungskategorie', '', ',', '' );
					if ( is_string( $terms ) ) {
						echo $terms;
					} else {
						_e( 'Ohne Kategorie', 'mi-textdomain' );
					}
					break;
				case 'untertitel' :
					echo get_field( 'untertitel' );
					break;
				case 'broschuere' :
					if ( have_rows( 'broschuere' ) ) {
						$html = '<ul>';
						while ( have_rows( 'broschuere' ) ) {
							the_row();
							$id  = get_sub_field( 'broschuere_id' );
							$url = wp_get_attachment_url( $id );
							if ( $url === false ) {
								$titel = get_sub_field( 'broschuere_titel' );
								$titel = $titel ? $titel : 'unbekannte Datei';
								$html  .= '<span style="color:#aa0000;">Datei fehlt: ' . $titel . '</span>';
							} elseif ( ! is_numeric( $id ) ) {
								$html .= "- fehlt - ";
							} else {
								$url      = wp_get_attachment_url( $id );
								$filename = basename( get_attached_file( $id ) );
								// $titel = get_sub_field('broschuere_titel');
								$link = sprintf( '<a href="%s">%s</a>', $url, $filename );
								$html .= ( '<li>' . $link . '</li>' );
							}
						}
						$html .= '</ul>';
						echo( $html );
					} else {
						echo( '' );
					}
					break;
				case 'tarifrechner' :
					echo get_field( 'tarifrechner' );
					break;
				case 'video_url' :
					$content = get_field( 'video_url' );
					echo( htmlentities( html_entity_decode( $content ) ) );
					break;
			}
		}, 10, 2 );
		add_action( 'pre_get_posts', function ( WP_Query $query ) {
			if ( ! is_admin() ) {
				return;
			}
			$orderby = $query->get( 'orderby' );
			if ( 'plz' == $orderby ) {
				$query->set( 'meta_key', 'plz' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'ort' == $orderby ) {
				$query->set( 'meta_key', 'ort' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'strasse' == $orderby ) {
				$query->set( 'meta_key', 'ort' );
				$query->set( 'orderby', 'meta_value' );
			}
			if ( 'import_order' == $orderby ) {
				$query->set( 'meta_key', 'import_order' );
				$query->set( 'orderby', 'meta_value_num' );
			}
		} );
	}

	// Admin Panel for importing menu, etc:

	function register_versicherung_admin() {
		// Dieser Hook ist steuert die Methode, die die eigene Admin Seite aufbaut:
		add_submenu_page( 'edit.php?post_type=versicherung', 'admin', 'admin', 'administrator', 'versicherung_admin_page', array( $this, 'versicherung_admin_page' ) );
		// Dieser Hook steuert, welche Methode nach submit (action) aufgerufen wird:
		add_action( 'admin_action_versicherung_import_menu', array( $this, 'versicherung_import_menu' ) );
	}

	function versicherung_admin_page() {
		?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Admin Page for Referenzen (ACHTUNG!)</h1>

            <form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
                <input type="hidden" name="action" value="versicherung_import_menu"/>
                <input type="submit" value="Menü für Versicherungen neu aufbauen"/>
            </form>

        </div>
		<?php
	}

	/**
	 * Array
	 * (
	 * [menu-item-db-id] => 0
	 * [menu-item-object-id] => 2301
	 * [menu-item-object] => versicherung
	 * [menu-item-parent-id] => 0
	 * [menu-item-position] =>
	 * [menu-item-type] => post_type
	 * [menu-item-title] => Gewässerschadenhaftpflicht
	 * [menu-item-url] => http://dev.makler-simon.de/versicherung/gewaesserschadenhaftpflicht/
	 * [menu-item-description] =>
	 * [menu-item-attr-title] =>
	 * [menu-item-target] =>
	 * [menu-item-classes] =>
	 * [menu-item-xfn] =>
	 * )
	 */

	function versicherung_import_menu() {
		$menuname = 'mi-makler';
		wp_delete_nav_menu( $menuname );
		$menu_id = wp_create_nav_menu( $menuname );

		// Get the Blueprint:
		$terms = $this->get_the_source_menu();
		for ( $i = 0; $i < count($terms); $i ++ ) {
			$set                = $terms[ $i ];
			$arrData            = $this->map_the_data( $set, $terms );
			$item_id            = wp_update_nav_menu_item( $menu_id, 0, $arrData );
			$terms[ $i ]->mi_id = $item_id;
		}
		echo( 'Menu import done<br>' );
		exit();
	}

	protected function map_the_data( WP_Post $set, &$terms ) {
		$object  = $set->object;
		$arrData = array(
			'menu-item-object-id'   => 0,
			'menu-item-object'      => $object,
			'menu-item-parent-id'   => 0,
			'menu-item-position'    => $set->menu_order,
			'menu-item-type'        => $set->type,
			'menu-item-title'       => $set->title,
			'menu-item-url'         => $set->url,
			'menu-item-description' => $set->description,
			'menu-item-attr-title'  => $set->attr_title,
			'menu-item-target'      => $set->target,
			'menu-item-classes'     => is_array( $set->classes ) ? implode( ' ', $set->classes ) : '',
			'menu-item-xfn'         => $set->xfn,
			'menu-item-status'      => 'publish'
		);
		// get the post:
		if ( $object == 'versicherung' || $object == 'page' ) {
			$args     = array(
				"post_type"      => $object,
				"name"           => $set->mi_postname,
				'posts_per_page' => 1
			);
			$objQuery = new WP_Query( $args );
			if ( $objQuery->have_posts() ) {
				$objQuery->the_post();
				$arrData['menu-item-object-id'] = get_the_ID();
				$arrData['menu-item-url']       = get_permalink();
			} else {
				echo( $object . ' mit postname ' . $set->mi_postname . ' nicht gefunden!<br>' );
				$arrData['menu-item-object'] = 'custom';
				$arrData['menu-item-type']   = 'custom';
			}
			wp_reset_postdata();
		}
		if ( $object == 'versicherungsgruppe' ) {

		    $taxonomy = get_term_by('slug', $set->mi_postname, 'versicherungsgruppe');
		    if ($taxonomy) {
				$arrData['menu-item-object-id'] = $taxonomy->term_id;
				// $arrData['menu-item-url']       = $term->
            }
		}
		// get the right ID:
		$old_parent = (int) $set->menu_item_parent;
		$new_parent = 0;
		if ( $old_parent != 0 ) {
			foreach ( $terms as $term ) {
				if ( $term->ID == $old_parent ) {
					$new_parent = $term->mi_id;
					break;
				}
			}
			if ( $new_parent == 0 ) {
				echo( 'parent_id: ' . $old_parent . ' vorhanden aber new_parent nicht gefunden!<br>' );
			}
		}
		$arrData['menu-item-parent-id'] = $new_parent;

		// search_replace domains:
        foreach($arrData as $key => $value) {
            $arrData[$key] = str_replace('dev.ssbgmbh.de', 'dev.makler-simon.de', $value);
        }
		return $arrData;
	}

	protected function get_the_source_menu() {
		$this->switch_to_source_db();
		$terms = wp_get_nav_menu_items( 'menu_versicherungen' );
		foreach ( $terms as $i => $term ) {
			if ( $term->object == 'versicherung' || $term->object == 'page' ) {
				$post                     = get_post( $term->object_id );
				$terms[ $i ]->mi_postname = $post->post_name;
			}
			if ( $term->object == 'versicherungsgruppe' ) {
				$tax = get_term( $term->object_id );
				$terms[ $i ]->mi_postname = $tax->slug;
			}
		}
		$this->switch_to_this_db();

		return $terms;
	}

	private function switch_to_source_db() {
		global $wpdb;
		$wpdb->select( 'ssbgmbh' );
		wp_cache_flush();
	}

	private function switch_to_this_db() {
		global $wpdb;
		$wpdb->select( $this->my_database );
		wp_cache_flush();
	}


}