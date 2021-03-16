<?php
/**
 * Media changes
 *
 * @since   1.1.0
 * @package Polygon_Tweaks
 */





/**
 * Media changes.
 *
 * This class is used to modify the standard WordPress behavior for media files.
 *
 * @since 1.1.0
 */
class Polygon_Tweaks_Media {

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
		add_image_size( 'polygon-1600', 1600, 0, false );    // Responsive Size at 1600px.
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
		$unwanted_sizes = array(
			'medium_large',
			'1536x1536',
			'2048x2048',
		);

		foreach ( $sizes as $key => $name ) {
			if ( in_array( $name, $unwanted_sizes, true ) ) {
				unset( $sizes[ $key ] );
			}
		}

		// Make consecutive keys.
		$sizes = array_values( $sizes );

		return $sizes;
	}





	/**
	 * Remove shortpixel image sizes.
	 *
	 * ShortPixel doesn't use the native WordPress function for building the list
	 * of image sizes. We need a slighty modified function to filter the returned values.
	 *
	 * @since 1.0.0
	 * @param  array $sizes Array with image sizes.
	 * @return array        New available image sizes.
	 */
	public function remove_shortpixel_image_sizes( $sizes ) {
		$unwanted_sizes = array(
			'medium_large',
			'1536x1536',
			'2048x2048',
		);

		foreach ( $sizes as $key => $value ) {
			if ( in_array( $key, $unwanted_sizes, true ) ) {
				unset( $sizes[ $key ] );
			}
		}

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
	 * Change srcset size limit.
	 *
	 * By default, WordPress has a limit of 1600px for the images included in the srcset
	 * attribute. This function changes that value based on the image size removing the limit
	 * for large image sizes and lowering it for smaller image sizes.
	 *
	 * @since 1.0.0
	 * @param  int   $max_width  Maximum width accepted in the srcset attribute.
	 * @param  array $size_array Array with the width and height of the current image.
	 * @return int               The new maximum width accepted in the srcset attribute.
	 */
	public function change_srcset_size_limit( $max_width, $size_array ) {
		return 3840;
	}





	/**
	 * Change image quality.
	 *
	 * Change the image quality for for new thumbnals.
	 * Always use lossless compression when using third-party image optimizers
	 * like ShortPixel or Imagify to prevnt double compressions.
	 *
	 * @since 1.0.0
	 * @param  int $quality Old image quality.
	 * @return int          New image quality.
	 */
	public function change_image_quality( $quality ) {
		return 100;
	}





	/**
	 * Remove attachment rewrites.
	 *
	 * Remove rewrite rules for attachment pages.
	 *
	 * @since 1.1.0
	 * @param  array $rules Old rewrite rules.
	 * @return array        New rewrite rules.
	 */
	public function remove_attachment_rewrites( $rules ) {
		foreach ( $rules as $regex => $query ) {
			if ( strpos( $regex, 'attachment' ) || strpos( $query, 'attachment' ) ) {
				unset( $rules[ $regex ] );
			}
		}

		return $rules;
	}





	/**
	 * Remove attachment query variable.
	 *
	 * Remove query variable for attachments.
	 *
	 * @since 1.1.0
	 * @param  array $vars Old query vars.
	 * @return array        New query vars.
	 */
	public function remove_attachment_query_var( $vars ) {
		if ( ! empty( $vars['attachment'] ) ) {
			$vars['page'] = '';
			$vars['name'] = $vars['attachment'];

			unset( $vars['attachment'] );
		}

		return $vars;
	}





	/**
	 * Change attachment link to file.
	 *
	 * Change the attachment link with a link to the original file that was uploaded.
	 *
	 * @since 1.1.0
	 * @param  array $link Attachment permalink.
	 * @param  int   $id   Attachment ID.
	 * @return string      The attachment URL.
	 */
	public function change_attachment_link_to_file( $link, $id ) {
		$file = wp_get_attachment_url( $id );

		if ( $file ) {
			return $file;
		} else {
			return $link;
		}
	}





	/**
	 * Redirect attachment page to file.
	 *
	 * Redirect the attachment page to the original file that was uploaded.
	 *
	 * @since 1.1.0
	 */
	public function redirect_attachment_page_to_file() {
		if ( is_attachment() ) {
			$id   = get_the_ID();
			$file = wp_get_attachment_url( $id );

			if ( $file ) {
				wp_safe_redirect( $file, 301 );
				die;
			}
		}
	}





	/**
	 * Make attachments private.
	 *
	 * Make the attachment CPT private.
	 * Right now, WordPress ignores the standard CPT arguments for attachments.
	 * This method is here with the hope that someday, WordPress may add support for them.
	 *
	 * @since 1.1.0
	 * @param array $args CPT args.
	 * @param int   $slug CPT slug.
	 * @return string     New CPT args.
	 */
	public function make_attachments_private( $args, $slug ) {
		if ( $slug === 'attachment' ) {
			$args['public']             = false;
			$args['publicly_queryable'] = false;
		}

		return $args;
	}





	/**
	 * Add attachment slug prefix.
	 *
	 * Add a prefix to all new attachment slugs making sure important slugs
	 * are available for posts and pages.
	 *
	 * The prefix can be changed via the 'polygon_attachment_slug_prefix' filter.
	 *
	 * @since 1.1.0
	 * @param  string $slug          Post slug.
	 * @param  int    $post_ID       Post ID.
	 * @param  string $post_status   Post status.
	 * @param  string $post_type     Post type.
	 * @param  int    $post_parent   Parent post ID.
	 * @param  string $original_slug Original slug.
	 * @return string                New slug.
	 *
	 * @link https://gschoppe.com/wordpress/disable-attachment-pages
	 * @link https://developer.wordpress.org/reference/functions/wp_unique_post_slug
	 */
	public function add_attachment_slug_prefix( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {
		if ( $post_ID === 0 ) {
			if ( $post_type === 'attachment' ) {
				$prefix = apply_filters( 'polygon_attachment_slug_prefix', 'media-' );

				if ( ! $prefix ) {
					return $slug;
				}

				// Remove this filter and rerun with the prefix.
				remove_filter( 'wp_unique_post_slug', array( $this, 'add_attachment_slug_prefix' ), 10 );
				$slug = wp_unique_post_slug( $prefix . $original_slug, $post_ID, $post_status, $post_type, $post_parent );
				add_filter( 'wp_unique_post_slug', array( $this, 'add_attachment_slug_prefix' ), 10, 6 );
			}
		}

		return $slug;
	}
}
