<?php
/**
 * Functions
 *
 * @package   LSX Testimonials
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2018 LightSpeed
 */

/**
 * Add our action to init to set up our vars first.
 */
function lsx_testimonials_load_plugin_textdomain() {
	load_plugin_textdomain( 'lsx-testimonials', false, basename( LSX_TESTIMONIALS_PATH ) . '/languages' );
}

add_action( 'init', 'lsx_testimonials_load_plugin_textdomain' );

/**
 * Wraps the output class in a function to be called in templates
 */

function lsx_testimonials( $args ) {
	$lsx_testimonials = new LSX_Testimonials();
	echo wp_kses_post( $lsx_testimonials->output( $args ) );
}

/**
 * Shortcode
 */
function lsx_testimonials_shortcode( $atts ) {
	$lsx_testimonials = new LSX_Testimonials();

	return $lsx_testimonials->output( $atts );
}

add_shortcode( 'lsx_testimonials', 'lsx_testimonials_shortcode' );


