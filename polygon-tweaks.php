<?php
/**
 * Plugin Name:       Polygon Tweaks
 * Plugin URI:        https://polygonthemes.com
 * Author:            Polygon Themes
 * Author URI:        https://polygonthemes.com
 * Description:       A simple plugin with tweaks and fixes for a better experience on your WordPress website.
 * Version:           1.2.0
 * Requires PHP:      7.2
 * Requires at least: 5.0
 *
 * Text Domain:       polygon-tweaks
 * Domain Path:       /languages/
 *
 * License:           GPLv3 or later
 * License URI:       https://choosealicense.com/licenses/gpl-3.0
 * GitHub Plugin URI: https://github.com/EusebiuOprinoiu/polygon-tweaks
 *
 * This program is free software.
 * You can redistribute it and/or modify it under the terms of GNU General Public License,
 * as published by the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY.
 * Without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * For more details, see the GNU General Public License.
 *
 * @since   1.0.0
 * @package Polygon_Tweaks
 */

defined( 'ABSPATH' ) || exit;





define( 'POLYGON_TWEAKS_VERSION', '1.2.0' );
define( 'POLYGON_TWEAKS_SLUG', 'polygon-tweaks' );

define( 'POLYGON_TWEAKS_FILE', __FILE__ );
define( 'POLYGON_TWEAKS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'POLYGON_TWEAKS_DIR_PATH', plugin_dir_path( __FILE__ ) );

define( 'POLYGON_TWEAKS_MIN_PHP_VERSION', '7.2' );
define( 'POLYGON_TWEAKS_REC_PHP_VERSION', '8.0' );





/**
 * Code that runs during the plugin activation.
 *
 * @since 1.0.0
 * @param bool $network_wide Boolean value with the network-wide activation status.
 */
function activate_polygon_tweaks( $network_wide ) {
	require_once POLYGON_TWEAKS_DIR_PATH . 'includes/class-polygon-tweaks-activator.php';
	Polygon_Tweaks_Activator::activate( $network_wide );
}
register_activation_hook( POLYGON_TWEAKS_FILE, 'activate_polygon_tweaks' );





/**
 * Code that runs during the plugin deactivation.
 *
 * @since 1.0.0
 * @param bool $network_wide Boolean value with the network-wide activation status.
 */
function deactivate_polygon_tweaks( $network_wide ) {
	require_once POLYGON_TWEAKS_DIR_PATH . 'includes/class-polygon-tweaks-deactivator.php';
	Polygon_Tweaks_Deactivator::deactivate( $network_wide );
}
register_deactivation_hook( POLYGON_TWEAKS_FILE, 'deactivate_polygon_tweaks' );







/**
 * Load and execute if all requirements are met.
 *
 * @since 1.0.0
 */
function run_polygon_tweaks() {
	require_once POLYGON_TWEAKS_DIR_PATH . 'includes/class-polygon-tweaks.php';
	require_once POLYGON_TWEAKS_DIR_PATH . 'includes/class-polygon-tweaks-textdomain.php';
	require_once POLYGON_TWEAKS_DIR_PATH . 'includes/class-polygon-tweaks-requirements.php';

	$textdomain = new Polygon_Tweaks_Textdomain();
	$textdomain->init();

	$requirements = new Polygon_Tweaks_Requirements();
	$requirements->init();

	if ( $requirements->check() ) {
		$plugin = new Polygon_Tweaks();
		$plugin->run();
	}
}
run_polygon_tweaks();
