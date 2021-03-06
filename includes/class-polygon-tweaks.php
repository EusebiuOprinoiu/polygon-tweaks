<?php
/**
 * The core plugin class
 *
 * @since   1.0.0
 * @package Polygon_Tweaks
 */





/**
 * The core plugin class.
 *
 * This class is used to load all dependencies, prepare the plugin for translation
 * and register all actions and filters with WordPress.
 *
 * @since 1.0.0
 */
class Polygon_Tweaks {

	/**
	 * Execute all hooks.
	 *
	 * Load dependencies, the plugin text-domain and execute all hooks
	 * we previously registered inside the function define_hooks().
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->load_dependencies();
		$this->load_textdomain();
		$this->define_hooks();
	}





	/**
	 * Load required dependencies.
	 *
	 * Load the files required to create our plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_dependencies() {
		require_once POLYGON_TWEAKS_DIR_PATH . 'includes/class-polygon-tweaks-textdomain.php';
		require_once POLYGON_TWEAKS_DIR_PATH . 'includes/general/class-polygon-tweaks-admin.php';
		require_once POLYGON_TWEAKS_DIR_PATH . 'includes/general/class-polygon-tweaks-media.php';
		require_once POLYGON_TWEAKS_DIR_PATH . 'includes/general/class-polygon-tweaks-updates.php';
	}





	/**
	 * Load plugin text-domain.
	 *
	 * Uses the Polygon_Tweaks_Textdomain class in order to set the text-domain and define
	 * the location of our translation files.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_textdomain() {
		$textdomain = new Polygon_Tweaks_Textdomain();

		add_action( 'after_setup_theme', array( $textdomain, 'load_plugin_textdomain' ) );
	}





	/**
	 * Register hooks with WordPress.
	 *
	 * Create objects from classes and register all hooks with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_hooks() {
		// Create objects from classes.
		$admin   = new Polygon_Tweaks_Admin();
		$media   = new Polygon_Tweaks_Media();
		$updates = new Polygon_Tweaks_Updates();

		// Register admin hooks.
		add_action( 'admin_enqueue_scripts', array( $admin, 'enqueue_styles' ) );
		add_action( 'login_head', array( $admin, 'change_login_logo' ) );
		add_filter( 'login_headerurl', array( $admin, 'change_login_header_url' ) );
		add_filter( 'login_headertext', array( $admin, 'change_login_header_text' ) );
		add_action( 'login_init', array( $admin, 'force_login_redirect' ) );
		add_action( 'plugin_row_meta', array( $admin, 'change_plugin_row_meta' ), 999, 4 );
		add_action( 'admin_init', array( $admin, 'flush_rewrite_rules' ) );

		// Register media hooks.
		add_action( 'after_setup_theme', array( $media, 'add_image_sizes' ) );
		add_filter( 'intermediate_image_sizes', array( $media, 'remove_image_sizes' ), 999 );
		add_filter( 'big_image_size_threshold', array( $media, 'change_big_image_threshold' ), 999 );
		add_filter( 'max_srcset_image_width', array( $media, 'change_srcset_size_limit' ), 999, 2 );
		add_filter( 'wp_editor_set_quality', array( $media, 'change_image_quality' ) );
		add_filter( 'jpeg_quality', array( $media, 'change_image_quality' ) );
		add_filter( 'rewrite_rules_array', array( $media, 'remove_attachment_rewrites' ) );
		add_filter( 'request', array( $media, 'remove_attachment_query_var' ) );
		add_filter( 'attachment_link', array( $media, 'change_attachment_link_to_file' ), 10, 2 );
		add_action( 'template_redirect', array( $media, 'redirect_attachment_page_to_file' ) );
		add_filter( 'register_post_type_args', array( $media, 'make_attachments_private' ), 10, 2 );
		add_filter( 'wp_unique_post_slug', array( $media, 'add_attachment_slug_prefix' ), 10, 6 );

		// Register other hooks.
		add_filter( 'jetpack_just_in_time_msgs', '__return_false' );    // Disable Jetpack nags and upsells.

		// Register db update hooks.
		add_action( 'plugins_loaded', array( $updates, 'maybe_run_recursive_updates' ) );
		add_action( 'wpmu_new_blog', array( $updates, 'maybe_run_activation_script' ), 10, 6 );
	}
}
