<?php

/**
 * LSX Testimonials Frontend Class
 *
 * @package   LSX Testimonials
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2018 LightSpeed
 */
class LSX_Testimonials_Frontend {
	public function __construct() {
		$this->options = testimonials_get_options();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );
		add_filter( 'wp_kses_allowed_html', array( $this, 'wp_kses_allowed_html' ), 10, 2 );
		add_filter( 'template_include', array( $this, 'single_template_include' ), 99 );
		add_filter( 'template_include', array( $this, 'archive_template_include' ), 99 );

		if ( ! empty( $this->options['display']['testimonials_disable_single'] ) ) {
			add_action( 'template_redirect', array( $this, 'disable_single' ) );
		}

		if ( is_admin() ) {
			add_filter( 'lsx_customizer_colour_selectors_body', array(
				$this,
				'customizer_body_colours_handler',
			), 15, 2 );
		}

		add_filter( 'wpseo_schema_graph_pieces', array( $this, 'add_graph_pieces' ), 11, 2 );

		add_filter( 'lsx_fonts_css', array( $this, 'customizer_fonts_handler' ), 15 );
		add_filter( 'lsx_banner_title', array( $this, 'lsx_banner_archive_title' ), 15 );
		add_filter( 'get_the_archive_title', array( $this, 'archive_title' ), 100 );

		add_filter( 'excerpt_more_p', array( $this, 'change_excerpt_more' ) );
		add_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ) );
		add_filter( 'excerpt_strip_tags', array( $this, 'change_excerpt_strip_tags' ) );

	}

	public function enqueue_scripts() {
		$has_slick = wp_script_is( 'slick', 'queue' );

		if ( ! $has_slick ) {
			wp_enqueue_style( 'slick', LSX_TESTIMONIALS_URL . 'assets/css/vendor/slick.css', array(), LSX_TESTIMONIALS_VER, null );
			wp_enqueue_script( 'slick', LSX_TESTIMONIALS_URL . 'assets/js/vendor/slick.min.js', array( 'jquery' ), null, LSX_TESTIMONIALS_VER, true );
		}

		wp_enqueue_script( 'lsx-testimonials', LSX_TESTIMONIALS_URL . 'assets/js/lsx-testimonials.min.js', array(
			'jquery',
			'slick',
		), LSX_TESTIMONIALS_VER, true );

		$params = apply_filters( 'lsx_testimonials_js_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		) );

		wp_localize_script( 'lsx-testimonials', 'lsx_testimonials_params', $params );

		wp_enqueue_style( 'lsx-testimonials', LSX_TESTIMONIALS_URL . 'assets/css/lsx-testimonials.css', array(), LSX_TESTIMONIALS_VER );
		wp_style_add_data( 'lsx-testimonials', 'rtl', 'replace' );
	}

	public function wp_kses_allowed_html( $allowedtags, $context ) {
		$allowedtags['div']['data-lsx-slick'] = true;

		return $allowedtags;
	}

	/**
	 * Single template.
	 */
	public function single_template_include( $template ) {
		if ( is_main_query() && is_singular( 'testimonial' ) ) {
			if ( empty( locate_template( array( 'single-testimonials.php' ) ) ) && file_exists( LSX_TESTIMONIALS_PATH . 'templates/single-testimonials.php' ) ) {
				$template = LSX_TESTIMONIALS_PATH . 'templates/single-testimonials.php';
			}
		}

		return $template;
	}

	/**
	 * Archive template.
	 */
	public function archive_template_include( $template ) {
		if ( is_main_query() && is_post_type_archive( 'testimonial' ) ) {
			if ( empty( locate_template( array( 'archive-testimonials.php' ) ) ) && file_exists( LSX_TESTIMONIALS_PATH . 'templates/archive-testimonials.php' ) ) {
				$template = LSX_TESTIMONIALS_PATH . 'templates/archive-testimonials.php';
			}
		}

		return $template;
	}

	/**
	 * Removes access to single testimonial member posts.
	 */
	public function disable_single() {
		$queried_post_type = get_query_var( 'post_type' );

		if ( is_single() && 'testimonial' === $queried_post_type ) {
			wp_redirect( home_url(), 301 );
			exit;
		}
	}

	/**
	 * Handle fonts that might be change by LSX Customiser
	 */
	public function customizer_fonts_handler( $css_fonts ) {
		global $wp_filesystem;

		$css_fonts_file = LSX_TESTIMONIALS_PATH . '/assets/css/lsx-testimonials-fonts.css';

		if ( file_exists( $css_fonts_file ) ) {
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			if ( $wp_filesystem ) {
				$css_fonts .= $wp_filesystem->get_contents( $css_fonts_file );
			}
		}

		return $css_fonts;
	}

	/**
	 * Handle body colours that might be change by LSX Customiser
	 */
	public function customizer_body_colours_handler( $css, $colors ) {
		$css .= '
			@import "' . LSX_TESTIMONIALS_PATH . '/assets/css/scss/customizer-testimonials-body-colours";

			/**
			 * LSX Customizer - Body (LSX Testimonials)
			 */
			@include customizer-testimonials-body-colours (
				$bg: 		' . $colors['background_color'] . ',
				$breaker:  	' . $colors['body_line_color'] . ',
				$color:    	' . $colors['body_text_color'] . ',
				$link: 		' . $colors['body_link_color'] . ',
				$hover: 	' . $colors['body_link_hover_color'] . ',
				$small: 	' . $colors['body_text_small_color'] . '
			);
		';

		return $css;
	}

	/**
	 * Change the LSX Banners title for team archive.
	 */
	public function lsx_banner_archive_title( $title ) {
		if ( is_main_query() && is_post_type_archive( 'testimonial' ) ) {
			$title = '<h1 class="page-title">' . esc_html__( 'Testimonials', 'lsx-testimonials' ) . '</h1>';
		}

		return $title;
	}

	/**
	 * Remove the "continue reading" when the single is disabled.
	 */
	public function change_excerpt_more( $excerpt_more ) {
		global $post;

		if ( 'testimonial' === $post->post_type ) {

			if ( ! empty( $this->options['display']['testimonials_disable_single'] ) ) {
				$excerpt_more = '';
			}
		}

		return $excerpt_more;
	}

	/**
	 * Change the word count when crop the content to excerpt (single team relations).
	 */
	public function change_excerpt_length( $excerpt_word_count ) {
		global $post;

		if ( is_singular( 'testimonial' ) && ( 'project' === $post->post_type || 'team' === $post->post_type ) ) {
			$excerpt_word_count = 20;
		}

		return $excerpt_word_count;
	}

	/**
	 * Change the allowed tags crop the content to excerpt (single team relations).
	 */
	public function change_excerpt_strip_tags( $allowed_tags ) {
		global $post;

		if ( is_singular( 'testimonial' ) && ( 'project' === $post->post_type || 'team' === $post->post_type ) ) {
			$allowed_tags = '<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>';
		}

		return $allowed_tags;
	}

	/**
	 * Adds Pieces
	 */
	public function add_graph_pieces( $pieces, $context ) {
		// Scheme Class.
		if ( class_exists( 'LSX_Schema_Graph_Piece' ) ) {
			require_once LSX_TESTIMONIALS_PATH . '/classes/class-lsx-testimonials-schema.php';
			$pieces[] = new \LSX_Testimonials_Schema( $context );
		}
		return $pieces;
	}

	/**
	 * Change the LSX Banners title for team archive.
	 */
	public function archive_title( $title ) {
		if ( is_main_query() && is_post_type_archive( 'testimonial' ) ) {
			$title = '<h1 class="page-title">' . esc_html__( 'Testimonials', 'lsx-testimonials' ) . '</h1>';
		}

		return $title;
	}

}

$lsx_testimonials_frontend = new LSX_Testimonials_Frontend();
