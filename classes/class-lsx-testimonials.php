<?php

/**
 * LSX Testimonials Main Class
 *
 * @package   LSX Testimonials
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2018 LightSpeed
 */
class LSX_Testimonials {
	public $columns;
	public $responsive;
	public $options;

	public function __construct() {
		$this->options = testimonials_get_options();

		add_filter( 'lsx_banner_allowed_post_types', array( $this, 'lsx_banner_allowed_post_types' ) );
	}

	/**
	 * Enable project custom post type on LSX Banners.
	 */
	public function lsx_banner_allowed_post_types( $post_types ) {
		$post_types[] = 'testimonial';

		return $post_types;
	}

	/**
	 * Return the team thumbnail.
	 */
	public function get_thumbnail( $post_id, $size, $thumbnail_class = 'img-responsive' ) {
		add_filter( 'lsx_placeholder_url', array( $this, 'placeholder' ), 10, 1 );
		add_filter( 'lsx_to_placeholder_url', array( $this, 'placeholder' ), 10, 1 );

		if ( is_numeric( $size ) ) {
			$thumb_size = array( $size, $size );
		} else {
			$thumb_size = $size;
		}

		$thumbnail  = '';
		$responsive = '';

		if ( ! empty( get_the_post_thumbnail( $post_id ) ) ) {
			$thumbnail = get_the_post_thumbnail(
				$post_id, $thumb_size, array(
					'class' => $thumbnail_class,
				)
			);
		} elseif ( ! empty( get_post_meta( $post_id, 'lsx_testimonial_email_gravatar', true ) ) ) {
			$thumbnail = get_avatar(
				get_post_meta( $post_id, 'lsx_testimonial_email_gravatar', true ), $size, $this->options['display']['testimonials_placeholder'], false, array(
					'class' => $thumbnail_class,
				)
			);
		} else {
			$thumbnail = '';
		}

		if ( empty( $thumbnail ) ) {
			if ( $this->options['display'] && ! empty( $this->options['display']['testimonials_placeholder'] ) ) {
				$thumbnail = '<img loading="lazy" class="' . $responsive . '" src="' . $this->options['display']['testimonials_placeholder'] . '" width="' . $size . '" alt="placeholder" />';
			} else {
				$thumbnail = '<img loading="lazy" class="' . $responsive . '" src="https://www.gravatar.com/avatar/none?d=mm&s=' . $size . '" width="' . $size . '" alt="placeholder" />';
			}
		}

		remove_filter( 'lsx_placeholder_url', array( $this, 'placeholder' ), 10, 1 );
		remove_filter( 'lsx_to_placeholder_url', array( $this, 'placeholder' ), 10, 1 );

		return $thumbnail;
	}

	/**
	 * Replaces the widget with Mystery Man
	 */
	public function placeholder( $image ) {
		$image = array(
			LSX_TESTIMONIALS_URL . 'assets/img/mystery-man-square.png',
			512,
			512,
			true,
		);

		return $image;
	}

	public function output( $atts ) {
		// @codingStandardsIgnoreLineStart
		extract( // @codingStandardsIgnoreLine
			shortcode_atts(
				array(
					'columns'    => 1,
					'orderby'    => 'menu_order',
					'order'      => 'ASC',
					'limit'      => '-1',
					'include'    => '',
					'display'    => 'full',
					'size'       => '128',
					'responsive' => 'true',
					'show_image' => 'true',
					'carousel'   => 'true',
					'featured'   => 'false',
					'title'      => ' ',
				), $atts
			)
		);
		// @codingStandardsIgnoreLineEnd

		$output = '';

		if ( 'true' === $responsive || true === $responsive ) {
			$responsive = 'img-responsive';
		} else {
			$responsive = '';
		}

		$this->columns    = $columns;
		$this->responsive = $responsive;

		if ( ! empty( $include ) ) {
			$include = explode( ',', $include );

			$args = array(
				'post_type'      => 'testimonial',
				'posts_per_page' => $limit,
				'post__in'       => $include,
				'orderby'        => 'post__in',
				'order'          => $order,
			);
		} else {
			$args = array(
				'post_type'      => 'testimonial',
				'posts_per_page' => $limit,
				'orderby'        => $orderby,
				'order'          => $order,
			);

			if ( 'true' === $featured || true === $featured ) {
				$args['meta_key']   = 'lsx_testimonial_featured';
				$args['meta_value'] = 1;
			}
		}

		$testimonials = new \WP_Query( $args );

		if ( $testimonials->have_posts() ) {
			global $post;

			$count        = 0;
			$count_global = 0;

			$this->title    = $title;
			if ( ! empty( $title ) ) {
				$output .= '<h2 class="lsx-title text-center">' . $title . '</h2>';
			}

			if ( 'true' === $carousel || true === $carousel ) {
				$output .= "<div id='lsx-testimonials-slider' class='lsx-testimonials-block lsx-testimonials-shortcode' data-lsx-slick='{\"slidesToShow\": $columns, \"slidesToScroll\": $columns }'>";
			} else {
				$output .= "<div class='lsx-testimonials-shortcode'><div class='row'>";
			}

			while ( $testimonials->have_posts() ) {
				$testimonials->the_post();

				// Count.
				$count ++;
				$count_global ++;

				// Link.
				$link_open  = '';
				$link_close = '';

				if ( get_post_meta( $post->ID, 'lsx_testimonial_url', true ) ) {
					$link_open  = "<a href='" . get_post_meta( $post->ID, 'lsx_testimonial_url', true ) . "' target='_blank'>";
					$link_close = '</a>';
				}

				// Byline.
				if ( get_post_meta( $post->ID, 'lsx_testimonial_byline', true ) ) {
					$byline = get_post_meta( $post->ID, 'lsx_testimonial_byline', true );
				} else {
					$byline = '';
				}

				// Content.
				if ( 'full' === $display ) {
					$content = apply_filters( 'the_content', get_the_content( esc_html__( 'Read More', 'lsx-testimonials' ) ) );
					$content = str_replace( ']]>', ']]&gt;', $content );
				} elseif ( 'excerpt' === $display ) {
					if ( ! has_excerpt() ) {

						$excerpt_more = '<p><a class="moretag" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Continue reading', 'lsx' ) . '</a></p>';
						$content      = wp_trim_words( get_the_content(), 20 );
						$content      = '<p>' . $content . '</p>' . $excerpt_more;
					} else {
						$content = apply_filters( 'the_excerpt', get_the_excerpt() );
					}
				}

				// Image.
				if ( 'true' === $show_image || true === $show_image ) {
					$image = $this->get_thumbnail( $post->ID, $size, $responsive );
				} else {
					$image = '';
				}

				if ( 'true' === $carousel || true === $carousel ) {
					$output .= "
						<div class='lsx-testimonials-slot'>
							" . ( ! empty( $image ) ? "<figure class='lsx-testimonials-avatar'>$image</figure>" : '' ) . "
							<h5 class='lsx-testimonials-title'><a href='" . get_permalink() . "'>" . apply_filters( 'the_title', $post->post_title ) . '</a></h5>
							' . ( ! empty( $byline ) ? "<small class='lsx-testimonials-meta-wrap'><i class='fa fa-briefcase'></i> <span class='lsx-testimonials-meta'>" . esc_html__( 'Role & Company', 'lsx-testimonials' ) . ':</span> ' . $link_open . $byline . $link_close . '</small>' : '' ) . "
							<blockquote class='lsx-testimonials-content'>$content</blockquote>
						</div>";
				} elseif ( $columns >= 1 && $columns <= 4 ) {
					$md_col_width = 12 / $columns;

					$output .= "
						<div class='col-xs-12 col-md-" . $md_col_width . "'>
							<div class='lsx-testimonials-slot'>
								" . ( ! empty( $image ) ? "<figure class='lsx-testimonials-avatar'>$image</figure>" : '' ) . "
								<h5 class='lsx-testimonials-title'><a href='" . get_permalink() . "'>" . apply_filters( 'the_title', $post->post_title ) . '</a></h5>
								' . ( ! empty( $byline ) ? "<small class='lsx-testimonials-meta-wrap'><i class='fa fa-briefcase'></i> <span class='lsx-testimonials-meta'>" . esc_html__( 'Role & Company', 'lsx-testimonials' ) . ':</span> ' . $link_open . $byline . $link_close . '</small>' : '' ) . "
								<blockquote class='lsx-testimonials-content'>$content</blockquote>
							</div>
						</div>";

					if ( $count == $columns && $testimonials->post_count > $count_global ) {
						$output .= '</div>';
						$output .= '<div class="row">';
						$count   = 0;
					}
				} else {
					$output .= "
						<p class='bg-warning' style='padding: 20px;'>
							" . esc_html__( 'Invalid number of columns set. LSX Testimonials supports 1 to 4 columns.', 'lsx-testimonials' ) . '
						</p>';
				}

				 wp_reset_postdata();
			}

			if ( 'true' !== $carousel && true !== $carousel ) {
				$output .= '</div>';
			}

			$output .= '</div>';

			return $output;
		}
	}
}

global $lsx_testimonials;
$lsx_testimonials = new LSX_Testimonials();
