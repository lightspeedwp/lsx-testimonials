<?php
/*
 * Plugin Name: LSX Testimonials
 * Plugin URI:  https://lsx.lsdev.biz/extensions/testimonials/
 * Description: The LSX Testimonials extension adds the "Testimonials" post type.
 * Version:     1.3.1
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-testimonials
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_TESTIMONIALS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_TESTIMONIALS_CORE', __FILE__ );
define( 'LSX_TESTIMONIALS_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_TESTIMONIALS_VER', '1.3.1' );


/* ======================= Below is the Plugin Class init ========================= */

// Template Tag and functions.
require_once LSX_TESTIMONIALS_PATH . '/includes/functions.php';

// Post Type and Custom Fields.
require_once LSX_TESTIMONIALS_PATH . '/classes/class-lsx-testimonials-admin.php';

// Frontend scripts and styles.
require_once LSX_TESTIMONIALS_PATH . '/classes/class-lsx-testimonials-frontend.php';

// Shortcode and Template Tag.
require_once LSX_TESTIMONIALS_PATH . '/classes/class-lsx-testimonials.php';

// Widget.
require_once LSX_TESTIMONIALS_PATH . '/classes/class-lsx-testimonials-widget.php';

// Post reorder.
require_once LSX_TESTIMONIALS_PATH . '/includes/class-lsx-testimonials-scpo-engine.php';
