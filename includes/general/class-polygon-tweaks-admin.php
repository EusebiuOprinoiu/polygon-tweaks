<?php
/**
 * Do things in the admin area
 *
 * @since   1.0.0
 * @package Polygon_Tweaks
 */





/**
 * Do things in the admin area.
 *
 * This class is used to maintain the functionality for the admin-facing side
 * of our website.
 *
 * @since 1.0.0
 */
class Polygon_Tweaks_Admin {

	/**
	 * Add image sizes.
	 *
	 * Add new image sizes for better adaptive images.
	 *
	 * @since 1.0.0
	 */
	public function add_image_sizes() {
		add_image_size( 'polygon-640', 640, 0, false );      // Responsive Size at 640px.
		add_image_size( 'polygon-960', 960, 0, false );      // Responsive Size at 960px.
		add_image_size( 'polygon-1280', 1280, 0, false );    // Responsive Size at 1280px.
		add_image_size( 'polygon-1920', 1920, 0, false );    // Responsive Size at 1920px.
		add_image_size( 'polygon-2560', 2560, 0, false );    // Responsive Size at 2560px.
	}





	/**
	 * Remove image sizes.
	 *
	 * Remove useless image sizes created by WordPress or other plugins.
	 *
	 * @since 1.0.0
	 * @param  array $sizes Array with image sizes.
	 * @return array        New available image sizes.
	 */
	public function remove_image_sizes( $sizes ) {
		foreach ( $sizes as $key => $name ) {
			if ( in_array( $name, array( 'medium_large', '1536x1536', '2048x2048' ), true ) ) {
				unset( $sizes[ $key ] );
			}
		}

		// Make consecutive keys.
		$sizes = array_values( $sizes );

		return $sizes;
	}





	/**
	 * Change big image threshold.
	 *
	 * Change threshold value for big images.
	 * Default WordPress value is 2560px.
	 *
	 * @since 1.0.0
	 * @param  int $threshold Old threshold for big images.
	 * @return int            New threshold for big images.
	 */
	public function change_big_image_threshold( $threshold ) {
		return 3840;
	}





	/**
	 * Change image quality.
	 *
	 * Set image quality for resized images to 100% to prevent
	 * double compression with plugins like ShortPixel or Imagify.
	 *
	 * @since 1.0.0
	 * @param  int $quality Old image quality.
	 * @return int          New image quality.
	 */
	public function change_image_quality( $quality ) {
		return 100;
	}





	/**
	 * Change login logo.
	 *
	 * Use a custom logo on the login page.
	 *
	 * @since 1.0.0
	 */
	public function change_login_logo() {
		/* phpcs:ignore
		echo '<style type="text/css">
			h1 a { background-image:url(//eusebiu.com/login-logo.png) !important; }
		</style>';
		*/
	}





	/**
	 * Change login header URL.
	 *
	 * Use a custom URL for the login header/logo.
	 *
	 * @since 1.0.0
	 * @return string New URL.
	 */
	public function change_login_header_url() {
		return site_url();
	}





	/**
	 * Change login logo title.
	 *
	 * Use a custom title for the login header/logo.
	 *
	 * @since 1.0.0
	 * @return string New hover title.
	 */
	public function change_login_header_text() {
		return get_bloginfo( 'name' );
	}





	/**
	 * Force login redirect.
	 *
	 * Skip login page for logged-in users when using iThemes Security
	 * and a custom login URL.
	 *
	 * @since 1.0.0
	 */
	public function force_login_redirect() {
		if ( is_user_logged_in() ) {
			if ( ! isset( $_GET['action'] ) ) {
				wp_safe_redirect( site_url( '/wp-admin' ) );
				exit;
			}
		}
	}
}
