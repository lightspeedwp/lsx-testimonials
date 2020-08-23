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


/**
 * Creates the feature images sizes for the REST API responses.
 *
 * @param [type] $object testimonial.
 * @param [type] $field_name name.
 * @param [type] $request request.
 */
function lsx_testimonial_get_images_urls( $object, $field_name, $request ) {
	$medium     = wp_get_attachment_image_src( get_post_thumbnail_id( $object->id ), 'medium' );
	$medium_url = $medium['0'];

	$large     = wp_get_attachment_image_src( get_post_thumbnail_id( $object->id ), 'large' );
	$large_url = $large['0'];

	return array(
		'medium' => $medium_url,
		'large'  => $large_url,
	);
}

/**
 * Modify REST API responses to get better media urls for the blocks.
 *
 * @return void
 */
function lsx_testimonial_register_images_field() {
	register_rest_field(
		'testimonial',
		'images',
		array(
			'get_callback'    => 'lsx_testimonial_get_images_urls',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'lsx_testimonial_register_images_field' );

/**
 * Creates the Additional Meta the REST API responses.
 *
 * @param [type] $object testimonial.
 * @param [type] $field_name name.
 * @param [type] $request request.
 */
function lsx_testimonial_get_additional_meta( $object, $field_name, $request ) {
	//$testimonial_tag = get_the_terms( get_the_ID(), 'testimonialtag' );
	$job_title       = get_post_meta( get_the_ID(), 'lsx_testimonial_byline', true );
	$testimonial_url = get_post_meta( get_the_ID(), 'lsx_testimonial_url', true );
	$gravatar_email  = get_post_meta( get_the_ID(), 'lsx_testimonial_email_gravatar', true );

	return array(
		//'testimonial_tag' => $testimonial_tag,
		'job_title'       => $job_title,
		'testimonial_url' => $testimonial_url,
		'gravatar_email'  => $gravatar_email,
	);
}

/**
 * Modify REST API responses to get better social urls for the blocks.
 *
 * @return void
 */
function lsx_testimonial_register_additional_meta() {
	register_rest_field(
		'testimonial',
		'additional_meta',
		array(
			'get_callback'    => 'lsx_testimonial_get_additional_meta',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'lsx_testimonial_register_additional_meta' );

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function testimonials_get_options() {
	$options = array();
	if ( function_exists( 'tour_operator' ) ) {
		$options = get_option( '_lsx-to_settings', false );
	} else {
		$options = get_option( '_lsx_settings', false );

		if ( false === $options ) {
			$options = get_option( '_lsx_lsx-settings', false );
		}
	}

	// If there are new CMB2 options available, then use those.
	$new_options = get_option( 'lsx_testimonials_options', false );
	if ( false !== $new_options ) {
		$options['display'] = $new_options;
	}
	return $options;
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function testimonials_get_option( $key = '', $default = false ) {
	$options = array();
	$value   = $default;
	if ( function_exists( 'tour_operator' ) ) {
		$options = get_option( '_lsx-to_settings', false );
	} else {
		$options = get_option( '_lsx_settings', false );

		if ( false === $options ) {
			$options = get_option( '_lsx_lsx-settings', false );
		}
	}

	// If there are new CMB2 options available, then use those.
	$new_options = get_option( 'lsx_testimonials_options', false );
	if ( false !== $new_options ) {
		$options['display'] = $new_options;
	}

	if ( isset( $options['display'] ) && isset( $options['display'][ $key ] ) ) {
		$value = $options['display'][ $key ];
	}
	return $value;
}
