<?php
/**
 * The core plugin class
 *
 * @since   1.0.0
 * @package Polygon_Tweaks
 */

defined( 'ABSPATH' ) || exit;





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
	 * Get things started.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->includes();
		$this->init();
	}





	/**
	 * Load required dependencies.
	 *
	 * Load the files required to create our plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function includes() {
		require_once POLYGON_TWEAKS_DIR_PATH . 'includes/classes/class-polygon-tweaks-admin.php';
		require_once POLYGON_TWEAKS_DIR_PATH . 'includes/classes/class-polygon-tweaks-media.php';
		require_once POLYGON_TWEAKS_DIR_PATH . 'includes/classes/class-polygon-tweaks-updates.php';
	}





	/**
	 * Register hooks with WordPress.
	 *
	 * Create objects from classes and hook into actions and filters.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function init() {
		$admin = new Polygon_Tweaks_Admin();
		$admin->init();

		$media = new Polygon_Tweaks_Media();
		$media->init();

		$updates = new Polygon_Tweaks_Updates();
		$updates->init();
	}
}
