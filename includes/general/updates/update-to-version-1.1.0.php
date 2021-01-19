<?php
/**
 * Update plugin to version 1.1.0
 *
 * @since   1.1.0
 * @package Polygon_Tweaks
 */





// Migrate.
$polygon_tweaks = get_option( 'polygon_tweaks' );

$polygon_tweaks['flush-rewrite-rules'] = 'flush';

update_option( 'polygon_tweaks', $polygon_tweaks );
