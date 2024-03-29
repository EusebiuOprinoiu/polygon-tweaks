<?php
/**
 * Check plugin requirements
 *
 * @since   1.0.0
 * @package Polygon_Tweaks
 */

defined( 'ABSPATH' ) || exit;





/**
 * Check plugin requirements.
 *
 * This class checks if the minimum requirements are met.
 * For this plugin we only check the PHP version.
 *
 * @since 1.0.0
 */
class Polygon_Tweaks_Requirements {

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		if ( ! $this->check_php() ) {
			add_action( 'network_admin_notices', array( $this, 'php_requirements_not_met' ) );
			add_action( 'admin_notices', array( $this, 'php_requirements_not_met' ) );
		}
	}





	/**
	 * Check all plugin requirements.
	 *
	 * Check if all the requirements are met. If the function returns true,
	 * the plugin can run without problems.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function check() {
		if ( $this->check_php() ) {
			return true;
		} else {
			return false;
		}
	}





	/**
	 * Check PHP requirements.
	 *
	 * Check if the server runs on a supported version of PHP.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function check_php() {
		return version_compare( PHP_VERSION, POLYGON_TWEAKS_MIN_PHP_VERSION ) >= 0;
	}





	/**
	 * Display PHP warning.
	 *
	 * If the server is using an outdated version of PHP advise users to upgrade.
	 * Allow people to disable the plugin straight from the notification, without going
	 * to the Plugins page.
	 *
	 * @since 1.0.0
	 */
	public function php_requirements_not_met() {
		if ( current_user_can( 'manage_options' ) ) {
			// Deactivate the plugin if the button Disable Plugin is clicked.
			$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_title_with_dashes( wp_unslash( $_REQUEST['_wpnonce'] ) ) : null;

			if ( $nonce && wp_verify_nonce( $nonce, 'disable-polygon-tweaks' ) ) {
				if ( isset( $_GET['disable_polygon_tweaks'] ) && ( $_GET['disable_polygon_tweaks'] === 'true' ) ) {
					deactivate_plugins( plugin_basename( POLYGON_TWEAKS_FILE ) );

					return; // Do not display the notice on page reload.
				}
			}



			// Display the actual notice.
			if (
				! is_multisite() ||
				( is_multisite() && is_super_admin() ) ||
				( is_multisite() && ! is_super_admin() && ! is_plugin_active_for_network( plugin_basename( POLYGON_TWEAKS_FILE ) ) ) ) {
					$disable_button = true;
			} else {
				$disable_button = false;
			}

			?>
				<div class="notice notice-error">
					<p></p>
					<p>
						<b><?php echo esc_html__( 'WARNING: You server is running outdated software!', 'polygon-tweaks' ); ?></b>
					</p>
					<p>
						<?php // phpcs:ignore
							printf( esc_html__( 'Polygon Tweaks doesn\'t run on PHP versions older than %1$s. You are running on version %2$s which has serious security and performance issues.', 'polygon-tweaks' ), POLYGON_TWEAKS_MIN_PHP_VERSION, PHP_VERSION );
						?>
						<br>
						<?php // phpcs:ignore
							printf( esc_html__( 'Please ask your hosting provider to help you upgrade. We recommend PHP %1$s or newer.', 'polygon-tweaks' ), POLYGON_TWEAKS_REC_PHP_VERSION );
						?>
					</p>
					<?php if ( $disable_button ) { ?>
						<p>
							<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'disable_polygon_tweaks', 'true' ), 'disable-polygon-tweaks' ) ); ?>">
								<b><?php echo esc_html__( 'Disable Plugin', 'polygon-tweaks' ); ?></b>
							</a>
						</p>
					<?php } ?>
					<p></p>
				</div>
			<?php
		}
	}
}
