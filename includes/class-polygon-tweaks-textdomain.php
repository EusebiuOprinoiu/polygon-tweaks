<?php
/**
 * Prepare the plugin for translation
 *
 * @since   1.0.0
 * @package Polygon_Tweaks
 */





/**
 * Prepare the plugin for translation.
 *
 * Load and define the internationalization files making the plugin ready for
 * translation.
 *
 * @since 1.0.0
 */
class Polygon_Tweaks_Textdomain {

	/**
	 * Load plugin text-domain.
	 *
	 * Load the plugin text-domain and define the location of our translation files.
	 * See examples below:
	 *
	 * - Global /languages/ folder: wp-content/languages/plugins/polygon-tweaks-en_US.mo
	 * - Local /languages/ folder:  wp-content/plugins/polygon-tweaks/languages/polygon-tweaks-en_US.mo
	 *
	 * If no files are found in the global languages folder the plugin uses the files available in the
	 * local folder.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'locale', get_locale(), POLYGON_TWEAKS_NAME );

		// Load translation files from the global /languages/ folder.
		load_textdomain( POLYGON_TWEAKS_NAME, trailingslashit( WP_LANG_DIR ) . 'plugins/' . POLYGON_TWEAKS_NAME . '-' . $locale . '.mo' );

		// Load translation files from the local /languages/ folder.
		load_plugin_textdomain( POLYGON_TWEAKS_NAME, false, plugin_basename( POLYGON_TWEAKS_DIR_PATH ) . '/languages/' );
	}
}
