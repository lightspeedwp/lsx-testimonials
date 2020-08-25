<?php
/**
 * Contains the settings class for LSX
 *
 * @package lsx-testimonials
 */

namespace lsx\testimonials\classes\admin;

class Settings {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx_testimonials\classes\admin\Settings()
	 */
	protected static $instance = null;

	/**
	 * Option key, and option page slug
	 *
	 * @var string
	 */
	protected $screen_id = 'lsx_testimonials_settings';

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'cmb2_admin_init', array( $this, 'register_settings_page' ) );
		add_action( 'lsx_testimonials_settings_page', array( $this, 'general_settings' ), 1, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object Settings()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook in and register a submenu options page for the Page post-type menu.
	 */
	public function register_settings_page() {
		$cmb = new_cmb2_box(
			array(
				'id'           => $this->screen_id,
				'title'        => esc_html__( 'Settings', 'lsx-testimonials' ),
				'object_types' => array( 'options-page' ),
				'option_key'   => 'lsx_testimonials_options', // The option key and admin menu page slug.
				'parent_slug'  => 'edit.php?post_type=testimonial', // Make options page a submenu item of the themes menu.
				'capability'   => 'manage_options', // Cap required to view options-page.
			)
		);
		do_action( 'lsx_testimonials_settings_page', $cmb );
	}

	/**
	 * Registers the general settings.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function general_settings( $cmb ) {
		$cmb->add_field(
			array(
				'id'      => 'settings_general_title',
				'type'    => 'title',
				'name'    => __( 'General', 'lsx-testimonials' ),
				'default' => __( 'General', 'lsx-testimonials' ),
			)
		);
		$cmb->add_field(
			array(
				'name'        => __( 'Disable Single Posts', 'lsx-testimonials' ),
				'id'          => 'testimonials_disable_single',
				'type'        => 'checkbox',
				'value'       => 1,
				'default'     => 0,
				'description' => __( 'Disable Single Posts.', 'lsx-testimonials' ),
			)
		);

		$cmb->add_field(
			array(
				'name'    => 'Placeholder',
				'desc'    => __( 'Choose Image.', 'lsx-testimonials' ),
				'id'      => 'testimonials_placeholder',
				'type'    => 'file',
				'options' => array(
					'url' => false, // Hide the text input for the url.
				),
				'text'    => array(
					'add_upload_file_text' => 'Choose Image',
				),
			)
		);

		$cmb->add_field(
			array(
				'id'   => 'settings_general_closing',
				'type' => 'tab_closing',
			)
		);
	}

}
