<?php
/**
 * Do things in the admin area
 *
 * @since   1.0.0
 * @package Polygon_Tweaks
 */

defined( 'ABSPATH' ) || exit;





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
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'login_head', array( $this, 'change_login_logo' ) );
		add_filter( 'login_headerurl', array( $this, 'change_login_header_url' ) );
		add_filter( 'login_headertext', array( $this, 'change_login_header_text' ) );
		add_action( 'login_init', array( $this, 'force_login_redirect' ) );
		add_action( 'plugin_row_meta', array( $this, 'change_plugin_row_meta' ), 999, 4 );
		add_action( 'admin_init', array( $this, 'flush_rewrite_rules' ) );

		// Register other hooks.
		add_filter( 'gu_plugin_assets_dir', array( $this, 'git_updater_assets_dir' ) );
		add_filter( 'jetpack_just_in_time_msgs', '__return_false', 20 );    // Disable Jetpack nags and upsells.
		add_filter( 'jetpack_show_promotions', '__return_false', 20 );      // Disable Jetpack nags and upsells.
	}





	/**
	 * Register and enqueue stylesheets for the admin area.
	 *
	 * @since 1.0.5
	 * @param string $hook Hook name for the current admin page.
	 */
	public function enqueue_styles( $hook ) {
		if ( $hook === 'post.php' || $hook === 'post-new.php' ) {
			wp_register_style( 'polygon-tweaks-editor', plugins_url( 'assets/stylesheets/polygon-tweaks-editor.css', POLYGON_TWEAKS_FILE ), false, POLYGON_TWEAKS_VERSION, 'all' );
			wp_enqueue_style( 'polygon-tweaks-editor' );
		}
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
	 * Get the list of unwanted keywords that will be used to detect unwanted links
	 * in the plugin row meta.
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
			'<span class="dashicons',
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
	 * Get keyword exclusions.
	 *
	 * Get the list of keywords that will be used to detect false-positives in the
	 * process of finding unwanted links in the plugin row meta.
	 *
	 * @since 1.0.1
	 * @access protected
	 * @return array Array with case-insensitive unwanted keyword exclusions.
	 */
	protected function get_keyword_exclusions() {
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
	 * Find and remove unwanted links in the plugin row meta.
	 * It ignores our own plugins and websites/users who don't use the en_US locale.
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
		$exclusions        = $this->get_keyword_exclusions();
		$language          = get_user_locale();



		// phpcs:disable
		/*
		// Remove Github Updater icons for own plugins. Keep all other custom links.
		if ( strpos( $plugin_meta[1], 'Eusebiu Oprinoiu' ) || strpos( $plugin_meta[1], 'Polygon Themes' ) ) {
			// phpcs:disable
			if ( strpos( $plugin_meta[ count( $plugin_meta ) - 1 ], 'github-updater/assets/bitbucket-logo.svg' ) ||
				 strpos( $plugin_meta[ count( $plugin_meta ) - 1 ], 'github-updater/assets/github-logo.svg' ) ||
				 strpos( $plugin_meta[ count( $plugin_meta ) - 1 ], 'github-updater/assets/gitlab-logo.svg' ) ||
				 strpos( $plugin_meta[ count( $plugin_meta ) - 1 ], 'github-updater/assets/gitea-logo.svg' ) ) {
					// unset( $plugin_meta[ count( $plugin_meta ) - 1 ] );
			}
			// phpcs:enable

			return $plugin_meta;
		}
		*/
		// phpcs:enable



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





	/**
	 * Flush rewrite rules.
	 *
	 * If the 'flush-rewrite-rules' flag is set to 'flush', flush the rewrite rules and set
	 * the flag back to 'no-flush'. The flag is set to 'flush' when the activation function
	 * is triggered.
	 *
	 * @since 1.0.0
	 */
	public function flush_rewrite_rules() {
		$polygon_tweaks = get_option( 'polygon_tweaks' );

		if ( $polygon_tweaks['flush-rewrite-rules'] === 'flush' ) {
			flush_rewrite_rules();

			$polygon_tweaks['flush-rewrite-rules'] = 'no-flush';

			update_option( 'polygon_tweaks', $polygon_tweaks );
		}
	}





	/**
	 * Set a custom assets directory for Git Updater.
	 *
	 * Specify a custom directory for the assets used by Git Updater during updates.
	 * Use the same file names as for the WordPress repository. (icon, banner, etc)
	 *
	 * @since 2.3.0
	 */
	public function git_updater_assets_dir() {
		return 'assets/images/updates';
	}
}
