<?php
/*
 * Plugin Name: LSX Testimonials
 * Plugin URI:  https://lsx.design/product/lsx-testimonials/
 * Description: The LSX Testimonials extension adds the "Testimonials" post type.
 * Version:     1.4
 * Requires at least: 6.7-beta1
 * Requires PHP:      8.0
 * Author:      LightSpeed
 * Author URI:  https://www.lightspeedwp.agency/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-testimonials
 * Domain Path: /languages
 */


 declare( strict_types = 1 );

define( 'LSX_TESTIMONIALS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_TESTIMONIALS_CORE', __FILE__ );
define( 'LSX_TESTIMONIALS_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_TESTIMONIALS_VER', '1.4' );


if ( ! function_exists( 'lsx_team_require_if_exists' ) ) {
    /**
     * Requires a file if it exists.
     *
     * @param string $file The file to require.
     */
    function lsx_team_require_if_exists( string $file ) {
        if ( file_exists( $file ) ) {
            require_once $file;
        }
    }
}


/**
 * Register Team Post Type Archive:
 * --------------------------------
 * This plugin registers a custom block template for displaying team members and provides functionality to unregister the template.
 *
 * Functions:
 * - lsx_team_register_template: Registers the custom block template using wp_register_block_template.
 * - lsx_team_unregister_template: Unregisters the custom block template using wp_unregister_block_template.
 *
 * Hooks:
 * - init: Calls lsx_team_register_template to register the template.
 * - init: Calls lsx_team_unregister_template to unregister the template.
 *
 */

// Register the template
add_action( 'init', 'lsx_testimonials_register_template' );

function lsx_testimonials_register_template() {
    // Add calls to wp_register_block_template() here.
}

wp_register_block_template( 'lsx-testimonials/testimonials-archive', [
	'title'       => __( 'LSX Testimonials Archive', 'lsx-testimonials' ),
	'description' => __( 'Testimonials Archive template for displaying all testimonials with pagination.', 'lsx-testimonials' ),
	'content'     => '
	<!-- wp:template-part {"slug":"header","area":"header","tagName":"header"} /-->
	<!-- wp:group {"tagName":"main"} -->
	<main class="wp-block-group">
		<!-- wp:group {"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph -->
			<p>This is a plugin-registered template.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</main>
	<!-- /wp:group -->
	<!-- wp:template-part {"slug":"footer","area":"footer","tagName":"footer"} /-->',
 ] );
 

// Deregister the template
add_action( 'init', 'lsx_testimonials_unregister_template' );

function lsx_testimonials_unregister_template() {
    wp_unregister_block_template( 'lsx_testimonials//testimonials-archive' );
}

?>
