<?php
/**
 * The Review Schema
 *
 * @package lsx-testimonials
 */
/**
 * Returns schema Review data.
 *
 * @since 10.2
 */
class LSX_Testimonials_Schema extends LSX_Schema_Graph_Piece {
	/**
	 * Constructor.
	 *
	 * @param \WPSEO_Schema_Context $context A value object with context variables.
	 */
	public function __construct( WPSEO_Schema_Context $context ) {
		$this->post_type = 'testimonial';
		parent::__construct( $context );
	}
	/**
	 * Returns Review data.
	 *
	 * @return array $data Review data.
	 */
	public function generate() {
		$post                = get_post( $this->context->id );
		$review_author       = $post->post_title;
		$review_email        = get_post_meta( $post->ID, 'lsx_testimonial_email_gravatar', true );
		$description         = wp_strip_all_tags( get_the_content() );
		$comment_count       = get_comment_count( $this->context->id );
		$data          = array(
			'@type'            => 'Review',
			'@id'              => $this->context->canonical . '#review',
			'author'           => array(
				'@type' => 'Person',
				'@id'   => LSX_Schema_Utils::get_author_schema_id( $review_author, $review_email, $this->context ),
				'name'  => $review_author,
				'email' => $review_email,
			),
			'headline'         => get_the_title(),
			'datePublished'    => mysql2date( DATE_W3C, $post->post_date_gmt, false ),
			'dateModified'     => mysql2date( DATE_W3C, $post->post_modified_gmt, false ),
			'commentCount'     => $comment_count['approved'],
			'mainEntityOfPage' => array(
				'@id' => $this->context->canonical . WPSEO_Schema_IDs::WEBPAGE_HASH,
			),
			'reviewBody'       => $description,
		);
		if ( $this->context->site_represents_reference ) {
			$data['publisher']    = $this->context->site_represents_reference;
			$data['itemReviewed'] = $this->context->site_represents_reference;
		}
		$data = LSX_Schema_Utils::add_image( $data, $this->context );
		return $data;
	}
}
