<?php

/**
 * LSX Testimonials Admin Class
 *
 * @package   LSX_Testimonials
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2018 LightSpeed
 */
class LSX_Testimonials_Admin {

	public function __construct() {
		$this->load_classes();

		add_action( 'init', array( $this, 'post_type_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_setup' ) );
		add_filter( 'cmb2_admin_init', array( $this, 'field_setup' ) );
		add_filter( 'cmb2_admin_init', array( $this, 'testimonials_services_metaboxes' ) );
		add_filter( 'cmb2_admin_init', array( $this, 'testimonials_team_metaboxes' ) );
		add_filter( 'cmb2_admin_init', array( $this, 'testimonials_project_metaboxes' ) );

		add_action( 'cmb_save_custom', array( $this, 'post_relations' ), 3, 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_filter( 'type_url_form_media', array( $this, 'change_attachment_field_button' ), 20, 1 );
		add_filter( 'enter_title_here', array( $this, 'change_title_text' ) );
	}

	/**
	 * Loads the admin subclasses
	 */
	private function load_classes() {
		require_once LSX_TESTIMONIALS_PATH . 'classes/admin/class-settings.php';
		$this->settings = \lsx\testimonials\classes\admin\Settings::get_instance();

		require_once LSX_TESTIMONIALS_PATH . 'classes/admin/class-settings-theme.php';
		$this->settings_theme = \lsx\testimonials\classes\admin\Settings_Theme::get_instance();
	}

	/**
	 * Register the Testimonial and Product Tag post type
	 */
	public function post_type_setup() {
		$labels = array(
			'name'               => esc_html_x( 'Testimonials', 'post type general name', 'lsx-testimonials' ),
			'singular_name'      => esc_html_x( 'Testimonial', 'post type singular name', 'lsx-testimonials' ),
			'add_new'            => esc_html_x( 'Add New', 'post type general name', 'lsx-testimonials' ),
			'add_new_item'       => esc_html__( 'Add New Testimonial', 'lsx-testimonials' ),
			'edit_item'          => esc_html__( 'Edit Testimonial', 'lsx-testimonials' ),
			'new_item'           => esc_html__( 'New Testimonial', 'lsx-testimonials' ),
			'all_items'          => esc_html__( 'All Testimonials', 'lsx-testimonials' ),
			'view_item'          => esc_html__( 'View Testimonial', 'lsx-testimonials' ),
			'search_items'       => esc_html__( 'Search Testimonials', 'lsx-testimonials' ),
			'not_found'          => esc_html__( 'No testimonials found', 'lsx-testimonials' ),
			'not_found_in_trash' => esc_html__( 'No testimonials found in Trash', 'lsx-testimonials' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html_x( 'Testimonials', 'admin menu', 'lsx-testimonials' ),
		);

		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_icon'             => 'dashicons-editor-quote',
			'query_var'             => true,
			'rewrite'               => array(
				'slug' => 'testimonials',
			),
			'capability_type'       => 'post',
			'has_archive'           => 'testimonials',
			'hierarchical'          => false,
			'menu_position'         => null,
			'supports'              => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
			),
			'show_in_rest'          => true,
			'rest_base'             => 'testimonial',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);

		register_post_type( 'testimonial', $args );
	}

	/**
	 * Register the Group taxonomy
	 */
	public function taxonomy_setup() {
		$labels = array(
			'name'              => esc_html_x( 'Tags', 'taxonomy general name', 'lsx-testimonials' ),
			'singular_name'     => esc_html_x( 'Tag', 'taxonomy singular name', 'lsx-testimonials' ),
			'search_items'      => esc_html__( 'Search Tags', 'lsx-testimonials' ),
			'all_items'         => esc_html__( 'All Tags', 'lsx-testimonials' ),
			'parent_item'       => esc_html__( 'Parent Tag', 'lsx-testimonials' ),
			'parent_item_colon' => esc_html__( 'Parent Tag:', 'lsx-testimonials' ),
			'edit_item'         => esc_html__( 'Edit Tag', 'lsx-testimonials' ),
			'update_item'       => esc_html__( 'Update Tag', 'lsx-testimonials' ),
			'add_new_item'      => esc_html__( 'Add New Tag', 'lsx-testimonials' ),
			'new_item_name'     => esc_html__( 'New Tag Name', 'lsx-testimonials' ),
			'menu_name'         => esc_html__( 'Tags', 'lsx-testimonials' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'query_var'             => true,
			'rewrite'               => array(
				'slug' => 'testimonial-tag',
			),
			'show_in_rest'          => true,
		);

		register_taxonomy( 'testimonial_tag', array( 'testimonial' ), $args );
	}

	/**
	 * Add metabox with custom fields to the Testimonial post type
	 */
	public function field_setup() {
		$prefix = 'lsx_testimonial_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_testimonial',
				'title'        => __( 'General', 'lsx-testimonials' ),
				'object_types' => 'testimonial',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Featured:', 'lsx-testimonials' ),
				'id'           => $prefix . 'featured',
				'type'         => 'checkbox',
				'value'        => 1,
				'default'      => 0,
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Gravatar Email Address:', 'lsx-testimonials' ),
				'desc'         => esc_html__( 'Enter the email address of this client to use a gravatar image', 'lsx-testimonials' ),
				'id'           => $prefix . 'email_gravatar',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Company and Job title:', 'lsx-testimonials' ),
				'desc'         => esc_html__( 'Enter the company and job title of the person giving the testimonial eg. CEO of ABC enterprises', 'lsx-testimonials' ),
				'id'           => $prefix . 'byline',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'URL:', 'lsx-testimonials' ),
				'desc'         => esc_html__( 'Link to the client\'s website - This adds a link to the "Company and Job title" field above', 'lsx-testimonials' ),
				'id'           => $prefix . 'url',
				'type'         => 'text_url',
				'show_in_rest' => true,
			)
		);

	}

	/**
	 * Testimonial Services Metaboxes.
	 */
	public function testimonials_services_metaboxes() {
		$prefix = 'lsx_testimonial_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_testimonial',
				'object_types' => 'testimonials',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Services:', 'lsx-testimonials' ),
				'id'           => 'service_to_testimonial',
				'type'         => 'post_search_ajax',
				'show_in_rest' => true,
				'limit'        => 15,
				'sortable'     => true,
				'query_args'   => array(
					'post_type'      => array( 'page' ),
					'post_status'    => array( 'publish' ),
					'nopagin'        => true,
					'posts_per_page' => '50',
					'orderby'        => 'title',
					'order'          => 'ASC',
				),
			)
		);
	}

	/**
	 * Testimonial Team Metaboxes.
	 */
	public function testimonials_team_metaboxes() {
		$prefix = 'lsx_testimonial_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_testimonial',
				'object_types' => 'testimonial',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		if ( class_exists( 'LSX_Team' ) ) {
			$cmb->add_field(
				array(
					'name'         => esc_html__( 'Team Member:', 'lsx-testimonials' ),
					'id'           => 'team_to_testimonial',
					'type'         => 'post_search_ajax',
					'show_in_rest' => true,
					'limit'        => 15,
					'sortable'     => true,
					'query_args'   => array(
						'post_type'      => array( 'team' ),
						'post_status'    => array( 'publish' ),
						'nopagin'        => true,
						'posts_per_page' => '50',
						'orderby'        => 'title',
						'order'          => 'ASC',
					),
				)
			);
		}
	}

	/**
	 * Testimonial Project Metaboxes.
	 */
	public function testimonials_project_metaboxes() {
		$prefix = 'lsx_testimonial_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_testimonial',
				'object_types' => 'testimonials',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		if ( class_exists( 'LSX_Projects' ) ) {
			$cmb->add_field(
				array(
					'name'         => esc_html__( 'Projects:', 'lsx-testimonials' ),
					'id'           => 'project_to_testimonial',
					'type'         => 'post_search_ajax',
					'show_in_rest' => true,
					'limit'        => 15,
					'sortable'     => true,
					'query_args'   => array(
						'post_type'      => array( 'project' ),
						'post_status'    => array( 'publish' ),
						'nopagin'        => true,
						'posts_per_page' => '50',
						'orderby'        => 'title',
						'order'          => 'ASC',
					),
				)
			);
		}
	}

	/**
	 * Sets up the "post relations".
	 */
	public function post_relations( $post_id, $field, $value ) {
		$connections = array(
			'testimonial_to_service',
			'service_to_testimonial',

			'testimonial_to_team',
			'team_to_testimonial',

			'testimonial_to_project',
			'project_to_testimonial',
		);

		if ( in_array( $field['id'], $connections ) ) {
			$this->save_related_post( $connections, $post_id, $field, $value );
		}
	}

	/**
	 * Save the reverse post relation.
	 */
	public function save_related_post( $connections, $post_id, $field, $value ) {
		$ids = explode( '_to_', $field['id'] );
		$relation = $ids[1] . '_to_' . $ids[0];

		if ( in_array( $relation, $connections ) ) {
			$previous_values = get_post_meta( $post_id, $field['id'], false );

			if ( ! empty( $previous_values ) ) {
				foreach ( $previous_values as $v ) {
					delete_post_meta( $v, $relation, $post_id );
				}
			}

			if ( is_array( $value ) ) {
				foreach ( $value as $v ) {
					if ( ! empty( $v ) ) {
						add_post_meta( $v, $relation, $post_id );
					}
				}
			}
		}
	}

	public function assets() {
		//wp_enqueue_media();
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script( 'lsx-testimonial-admin', LSX_TESTIMONIALS_URL . 'assets/js/lsx-testimonials-admin.min.js', array( 'jquery' ), LSX_TESTIMONIALS_VER, true );
		wp_enqueue_style( 'lsx-testimonial-admin', LSX_TESTIMONIALS_URL . 'assets/css/lsx-testimonials-admin.css', array(), LSX_TESTIMONIALS_VER );
	}


	/**
	 * Change the "Insert into Post" button text when media modal is used for feature images
	 */
	public function change_attachment_field_button( $html ) {
		if ( isset( $_GET['feature_image_text_button'] ) ) {
			$html = str_replace( 'value="Insert into Post"', sprintf( 'value="%s"', esc_html__( 'Select featured image', 'lsx-testimonials' ) ), $html );
		}

		return $html;
	}

	public function change_title_text( $title ) {
		$screen = get_current_screen();

		if ( 'testimonial' === $screen->post_type ) {
			$title = esc_attr__( 'Enter client\'s name or a title for this testimonial', 'lsx-testimonials' );
		}

		return $title;
	}

}

$lsx_testimonials_admin = new LSX_Testimonials_Admin();
