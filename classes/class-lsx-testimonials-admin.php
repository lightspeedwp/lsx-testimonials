<?php
/**
 * LSX Testimonials Admin Class
 * @package   LSX_Testimonials
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2018 LightSpeed
 */

class LSX_Testimonials_Admin {
	public function __construct() {
		if( ! class_exists( 'CMB_Meta_Box' ) ) {
			require_once( LSX_TESTIMONIALS_PATH . '/vendor/Custom-Meta-Boxes/custom-meta-boxes.php' );
		}

		if ( function_exists( 'tour_operator' ) ) {
			$this->options = get_option( '_lsx-to_settings', false );
		} else {
			$this->options = get_option( '_lsx_settings', false );

			if ( false === $this->options ) {
				$this->options = get_option( '_lsx_lsx-settings', false );
			}
		}

		add_action( 'init', array( $this, 'post_type_setup' ) );
		add_filter( 'cmb_meta_boxes', array( $this, 'field_setup' ) );
		add_action( 'cmb_save_custom', array( $this, 'post_relations' ), 3, 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_action( 'init', array( $this, 'create_settings_page' ), 100 );
		add_filter( 'lsx_framework_settings_tabs', array( $this, 'register_tabs' ), 100, 1 );

		add_filter( 'type_url_form_media', array( $this, 'change_attachment_field_button' ), 20, 1 );
		add_filter( 'enter_title_here', array( $this, 'change_title_text' ) );
	}

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
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-editor-quote',
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'testimonials',
			),
			'capability_type'    => 'post',
			'has_archive'        => 'testimonials',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
			),
		);

		register_post_type( 'testimonial', $args );
	}

	public function field_setup( $meta_boxes ) {
		$prefix = 'lsx_testimonial_';

		$fields = array(
			array(
				'name' => esc_html__( 'Featured:', 'lsx-testimonials' ),
				'id'   => $prefix . 'featured',
				'type' => 'checkbox',
			),
			array(
				'name' => esc_html__( 'Gravatar Email Address:', 'lsx-testimonials' ),
				'desc' => esc_html__( 'Enter the email address of this client to use a gravatar image', 'lsx-testimonials' ),
				'id'   => $prefix . 'email_gravatar',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Company and Job title:', 'lsx-testimonials' ),
				'desc' => esc_html__( 'Enter the company and job title of the person giving the testimonial eg. CEO of ABC enterprises', 'lsx-testimonials' ),
				'id'   => $prefix . 'byline',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'URL:', 'lsx-testimonials' ),
				'desc' => esc_html__( 'Link to the client\'s website - This adds a link to the "Company and Job title" field above', 'lsx-testimonials' ),
				'id'   => $prefix . 'url',
				'type' => 'text_url',
			),
		);

		if ( class_exists( 'LSX_Projects' ) ) {
			$fields[] = array(
				'name'       => esc_html__( 'Projects:', 'lsx-testimonials' ),
				'id'         => 'project_to_testimonial',
				'type'       => 'post_select',
				'use_ajax'   => false,
				'query'      => array(
					'post_type'      => 'project',
					'nopagin'        => true,
					'posts_per_page' => '50',
					'orderby'        => 'title',
					'order'          => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols'       => 12,
			);
		}

		if ( class_exists( 'LSX_Services' ) ) {
			$fields[] = array(
				'name'       => esc_html__( 'Services:', 'lsx-testimonials' ),
				'id'         => 'service_to_testimonial',
				'type'       => 'post_select',
				'use_ajax'   => false,
				'query'      => array(
					'post_type'      => 'service',
					'nopagin'        => true,
					'posts_per_page' => '50',
					'orderby'        => 'title',
					'order'          => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols'       => 12,
			);
		}

		if ( class_exists( 'LSX_Team' ) ) {
			$fields[] = array(
				'name'       => esc_html__( 'Team Member:', 'lsx-testimonials' ),
				'id'         => 'team_to_testimonial',
				'type'       => 'post_select',
				'use_ajax'   => false,
				'query'      => array(
					'post_type'      => 'team',
					'nopagin'        => true,
					'posts_per_page' => '50',
					'orderby'        => 'title',
					'order'          => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols'       => 12,
			);
		}

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Testimonial Details', 'lsx-testimonials' ),
			'pages'  => 'testimonial',
			'fields' => $fields,
		);

		return $meta_boxes;
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
		$ids      = explode( '_to_', $field['id'] );
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
	 * Returns the array of settings to the UIX Class
	 */
	public function create_settings_page() {
		if ( is_admin() ) {
			if ( ! class_exists( '\lsx\ui\uix' ) && ! function_exists( 'tour_operator' ) ) {
				include_once LSX_TESTIMONIALS_PATH . 'vendor/uix/uix.php';
				$pages = $this->settings_page_array();
				$uix   = \lsx\ui\uix::get_instance( 'lsx' );
				$uix->register_pages( $pages );
			}

			//@TODO this class exists condition does not work here
			//if ( function_exists( 'tour_operator' ) ) {
			add_action( 'lsx_to_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
			//} else {
			add_action( 'lsx_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
			//}
		}
	}

	/**
	 * Returns the array of settings to the UIX Class
	 */
	public function settings_page_array() {
		$tabs = apply_filters( 'lsx_framework_settings_tabs', array() );

		return array(
			'settings' => array(
				'page_title'  => esc_html__( 'Theme Options', 'lsx-testimonials' ),
				'menu_title'  => esc_html__( 'Theme Options', 'lsx-testimonials' ),
				'capability'  => 'manage_options',
				'icon'        => 'dashicons-book-alt',
				'parent'      => 'themes.php',
				'save_button' => esc_html__( 'Save Changes', 'lsx-testimonials' ),
				'tabs'        => $tabs,
			),
		);
	}

	/**
	 * Register tabs
	 */
	public function register_tabs( $tabs ) {
		$default = true;

		if ( false !== $tabs && is_array( $tabs ) && count( $tabs ) > 0 ) {
			$default = false;
		}

		if ( ! function_exists( 'tour_operator' ) ) {
			if ( ! array_key_exists( 'display', $tabs ) ) {
				$tabs['display'] = array(
					'page_title'       => '',
					'page_description' => '',
					'menu_title'       => esc_html__( 'Display', 'lsx-testimonials' ),
					'template'         => LSX_TESTIMONIALS_PATH . 'includes/settings/display.php',
					'default'          => $default,
				);

				$default = false;
			}

			if ( ! array_key_exists( 'api', $tabs ) ) {
				$tabs['api'] = array(
					'page_title'       => '',
					'page_description' => '',
					'menu_title'       => esc_html__( 'API', 'lsx-testimonials' ),
					'template'         => LSX_TESTIMONIALS_PATH . 'includes/settings/api.php',
					'default'          => $default,
				);

				$default = false;
			}
		}

		return $tabs;
	}

	/**
	 * Outputs the display tabs settings
	 *
	 * @param $tab string
	 *
	 * @return null
	 */
	public function display_settings( $tab = 'general' ) {
		if ( 'testimonials' === $tab ) {
			$this->disable_single_post_field();
			$this->placeholder_field();
		}
	}

	/**
	 * Outputs the Display flags checkbox
	 */
	public function disable_single_post_field() {
		?>
		<tr class="form-field">
			<th scope="row">
			<label for="testimonials_disable_single"><?php esc_html_e( 'Disable Single Posts', 'lsx-testimonials' ); ?></label>
            </th>
            <td>
                <input type="checkbox" {{#if testimonials_disable_single}} checked="checked" {{/if}}
                name="testimonials_disable_single" />
                <small><?php esc_html_e( 'Disable Single Posts.', 'lsx-testimonials' ); ?></small>
            </td>
        </tr>
		<?php
	}

	/**
	 * Outputs the flag position field
	 */

	public function placeholder_field() {
		?>
        <tr class="form-field">
            <th scope="row">
                <label for="banner"> <?php esc_html_e( 'Placeholder', 'lsx-testimonials' ); ?></label>
            </th>
            <td>
                <input class="input_image_id" type="hidden" {{#if testimonials_placeholder_id}}
                       value="{{testimonials_placeholder_id}}" {{/if}} name="testimonials_placeholder_id" />
                <input class="input_image" type="hidden" {{#if testimonials_placeholder}}
                       value="{{testimonials_placeholder}}" {{/if}} name="testimonials_placeholder" />
                <div class="thumbnail-preview">
                    {{#if testimonials_placeholder}}<img src="{{testimonials_placeholder}}" width="150"/>{{/if}}
                </div>
                <a {{#if testimonials_placeholder}}style="display:none;" {{/if}} class="button-secondary
                lsx-thumbnail-image-add"
                data-slug="testimonials_placeholder"><?php esc_html_e( 'Choose Image', 'lsx-testimonials' ); ?></a>
                <a {{#unless testimonials_placeholder}}style="display:none;" {{/unless}} class="button-secondary
                lsx-thumbnail-image-delete"
                data-slug="testimonials_placeholder"><?php esc_html_e( 'Delete', 'lsx-testimonials' ); ?></a>
            </td>
        </tr>
		<?php
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
