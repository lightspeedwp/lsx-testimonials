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
	$lsx_testimonials = new LSX_Testimonials;
	echo wp_kses_post( $lsx_testimonials->output( $args ) );
}

/**
 * Shortcode
 */
function lsx_testimonials_shortcode( $atts ) {
	$lsx_testimonials = new LSX_Testimonials;

	return $lsx_testimonials->output( $atts );
}

add_shortcode( 'lsx_testimonials', 'lsx_testimonials_shortcode' );

/**
 * Adds the post type count to the admin dashboard "At a Glance" section
 */
add_action( 'dashboard_glance_items', 'cpad_at_glance_content_table_end' );
function cpad_at_glance_content_table_end() {
	$args = array(
		'public' => true,
		'_builtin' => false,
	);
	$output = 'object';
	$operator = 'and';

	$post_types = get_post_types( $args, $output, $operator );
	foreach ( $post_types as $post_type ) {
		$num_posts = wp_count_posts( $post_type->name );
		$num = number_format_i18n( $num_posts->publish );
		$text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );
		if ( current_user_can( 'edit_posts' ) ) {
			$output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
			echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
		}
	}
}

