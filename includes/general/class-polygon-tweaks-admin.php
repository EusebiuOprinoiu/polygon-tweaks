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
			if ( ! isset( $_GET['action'] ) ) {    // phpcs:ignore
				wp_safe_redirect( site_url( '/wp-admin' ) );
				exit;
			}
		}
	}





	/**
	 * Get unwanted keywords.
	 *
	 * Get the list of unwanted keywords that will be removed from theme and plugin
	 * row meta and action links.
	 *
	 * @since 1.0.1
	 * @access protected
	 * @return array Array with case-insensitive unwanted keywords.
	 */
	protected function get_unwanted_keywords() {
		$unwanted_keywords = array(
			'Docs',
			'About',
			'Donate',
			'Review',
			'Support',
			'Premium',
			'Homepage',
			'Changelog',
			'Community',
			'Documentation',
			'Upgrade to Pro',
			'Addons',
			'Add-ons',
			'Extensions',
			'Leave a review',
			'Write a review',
			'Rate this plugin',
			'<span class="dashicons>',
			'.jpeg',
			'.jpg',
			'.png',
			'.svg',
			'.webp',
		);

		// Make values lowercase.
		$unwanted_keywords = array_map( 'strtolower', $unwanted_keywords );

		return $unwanted_keywords;
	}





	/**
	 * Get unwanted keyword exclusions.
	 *
	 * Get the list of keywords that should not be removed even if they contain
	 * keywords in the list of unwanted keywords.
	 * This list should contain longer keywords than those in the unwanted list.
	 *
	 * @since 1.0.1
	 * @access protected
	 * @return array Array with case-insensitive unwanted keyword exclusions.
	 */
	protected function get_unwanted_keyword_exclusions() {
		$exclusions = array(
			'View details',
			'Check for updates',
			'Visit plugin site',
		);

		// Make values lowercase.
		$exclusions = array_map( 'strtolower', $exclusions );

		return $exclusions;
	}





	/**
	 * Change plugin row meta.
	 *
	 * Remove unwanted links from plugin meta.
	 *
	 * @since 1.0.1
	 * @param  array  $plugin_meta Array with plugin metadata, including the version, author, author URI, and plugin URI.
	 * @param  string $plugin_file Path to the plugin file relative to the plugins directory..
	 * @param  array  $plugin_data Array with plugin data.
	 * @param  string $status      Status filter currently applied to the plugin list.
	 * @return array               Array with modified plugin metadata.
	 */
	public function change_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		$unwanted_keywords = $this->get_unwanted_keywords();
		$exclusions        = $this->get_unwanted_keyword_exclusions();
		$language          = get_user_locale();



		// Don't remove links for own plugins.
		if ( strpos( $plugin_meta[1], 'Eusebiu Oprinoiu' ) || strpos( $plugin_meta[1], 'Polygon Themes' ) ) {
			return $plugin_meta;
		}

		// Don't remove links for non-english websites.
		if ( $language !== 'en_US' ) {
			return $plugin_meta;
		}



		// Remove unwanted links.
		foreach ( $plugin_meta as $meta_key => $meta_value ) {
			$meta_value = strtolower( $meta_value );

			// Skip Version and Author keys.
			if ( $meta_key !== 0 && $meta_key !== 1 ) {
				foreach ( $unwanted_keywords as $unwanted_keyword ) {
					// If the unwanted keyword is in meta and no exclusions are found, remove key.
					if ( strpos( $meta_value, $unwanted_keyword ) ) {
						$remove = true;

						foreach ( $exclusions as $exclusion ) {
							if ( strpos( $meta_value, $exclusion ) ) {
								$remove = false;
							}
						}

						// If all conditions are met, remove key and exit loop early.
						if ( $remove ) {
							unset( $plugin_meta[ $meta_key ] );
							break;
						}
					}
				}
			}
		}

		// Make consecutive keys.
		$plugin_meta = array_values( $plugin_meta );



		return $plugin_meta;
	}
}
