<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://mai-internet.de
 * @since      1.0.0
 *
 * @package    Mi_Versicherung
 * @subpackage Mi_Versicherung/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mi_Versicherung
 * @subpackage Mi_Versicherung/includes
 * @author     Michael Mai <mmai.mai.internet@gmail.com>
 */
class Mi_Versicherung {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mi_Versicherung_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'mi-versicherung';
		$this->version     = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mi_Versicherung_Loader. Orchestrates the hooks of the plugin.
	 * - Mi_Versicherung_i18n. Defines internationalization functionality.
	 * - Mi_Versicherung_Admin. Defines all hooks for the admin area.
	 * - Mi_Versicherung_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mi-versicherung-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mi-versicherung-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mi-versicherung-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mi-versicherung-public.php';

		/**
		 * The class for rendering the Versicherung-Page
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mi-versicherung-layout.php';

		$this->loader = new Mi_Versicherung_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mi_Versicherung_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mi_Versicherung_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mi_Versicherung_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_cpt_versicherung' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_taxonomies' );

		$this->loader->add_action( 'init', $plugin_admin, 'manage_columns' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_versicherung_admin' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mi_Versicherung_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'generate_rewrite_rules', $plugin_public, 'generate_versicherung_rules' );
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcode_tarifrechnerliste' );
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcode_template_fields' );
		$this->loader->add_action( 'init', $plugin_public, 'register_filter_content' );
		$this->loader->add_action( 'init', $plugin_public, 'register_query_vars' );
		$this->loader->add_action( 'init', $plugin_public, 'register_search_query' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mi_Versicherung_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	// Helper Methodes for public and admin:

	public static function mi_get_url_tarifrechner_call( $post_name ) {
		$base_url = get_site_url() . '/tarifrechner-run/';
		return add_query_arg( 'tarifrechner_call', $post_name, $base_url );
	}

	public static function getVersicherungByShortname( $title ) {
		$args     = array( "post_type" => "versicherung", "name" => $title );
		$objQuery = new WP_Query( $args );
		if ( $objQuery->have_posts() ) {
			$objQuery->the_post();
		}
	}

	public static function getImageID( $image_url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
		return $attachment[0];
	}

	/**
	 * checks if Tarifrechner URL belongs to Mr Money Tarifrechner:
	 * @param $tarifrechner
	 * @return bool
	 */
	public static function isMrMoney($tarifrechner) {
		return strpos($tarifrechner, 'mr-money.de/') !== false;
	}

	public static function getTarifrechnerURL($tarifrechner) {
		if (Mi_Versicherung::isMrMoney($tarifrechner)) {
			if (strpos(strtolower($tarifrechner), 'ur_id') === false) {
				$tarifrechner .= '&ur_iD=mobile';
			}
		}
		return $tarifrechner;
	}

	public static function hasTarifrechner(WP_Post $post) {
		$field = get_field('tarifrechner', $post->ID);
		return trim($field) != '';
	}

	public static function hasVideos(WP_Post $post) {
		$field = get_field('video_url', $post->ID);
		return trim($field) != '';
	}

	public static function isPrivatkunde(WP_Post $post) {
		$taxonomies = get_the_terms( $post , 'zielgruppe');
		if (is_array($taxonomies)) {
			foreach($taxonomies as $tax) {
				if ($tax->name == 'Privatkunden') {
					return true;
				}
			}
		}
		return false;
	}






}
