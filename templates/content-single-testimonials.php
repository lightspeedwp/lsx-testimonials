<?php
/**
 * @package lsx-testimonials
 */
?>

<?php
	global $lsx_testimonials;

	$thumbnail = $lsx_testimonials->get_thumbnail( get_the_ID(), 128 );

	$byline = get_post_meta( get_the_ID(), 'lsx_testimonial_byline', true );
	$url = get_post_meta( get_the_ID(), 'lsx_testimonial_url', true );

	// Tabs

	$tabs_output = array();

	// Tab Projects

	$tab_project['post_type'] = 'project';
	$tab_project['icon'] = 'folder-open';
	$tab_project['title'] = esc_html__( 'Projects', 'lsx-testimonials' );
	$tab_project['posts'] = get_post_meta( get_the_ID(), 'project_to_testimonial', false );

	if ( ! empty( $tab_project['posts'][0] ) ) {
		$post_ids = join( ',', $tab_project['posts'][0] );
		// $tab_project['shortcode'] = '[lsx_projects columns="3" include="' . $post_ids . '"]';

		$tab_project['posts_html'] = '';

		$args = array(
			'post_type'              => 'project',
			'post__in'               => $tab_project['posts'][0],
			'orderby'                => 'post__in',
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		$projects_ = new \WP_Query( $args );

		if ( $projects_->have_posts() ) {
			$tab_project['posts_html'] = array();

			while ( $projects_->have_posts() ) {
				$projects_->the_post();
				$tab_project['posts_html'][] = '<a href="' . get_permalink() . '">' . the_title( '', '', false ) . '</a>';
				wp_reset_postdata();
			}

			$tab_project['posts_html'] = join( ', ', $tab_project['posts_html'] );
		}

		$tabs_output[] = $tab_project;
	}

	// Tab Services

	$tab_service['post_type'] = 'page';
	$tab_service['icon'] = 'wrench';
	$tab_service['title'] = esc_html__( 'Services', 'lsx-testimonials' );
	$tab_service['posts'] = get_post_meta( get_the_ID(), 'service_to_testimonial', false );

	if ( ! empty( $tab_service['posts'][0] ) ) {
		$post_ids = join( ',', $tab_service['posts'][0] );
		// $tab_service['shortcode'] = '[lsx_services columns="3" include="' . $post_ids . '"]';

		$tab_service['posts_html'] = '';

		$args = array(
			'post_type'              => 'page',
			'post__in'               => $tab_service['posts'][0],
			'orderby'                => 'post__in',
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		$services_ = new \WP_Query( $args );

		if ( $services_->have_posts() ) {
			$tab_service['posts_html'] = array();

			while ( $services_->have_posts() ) {
				$services_->the_post();
				$tab_service['posts_html'][] = '<a href="' . get_permalink() . '">' . the_title( '', '', false ) . '</a>';
				wp_reset_postdata();
			}

			$tab_service['posts_html'] = join( ', ', $tab_service['posts_html'] );
		}

		$tabs_output[] = $tab_service;
	}

	// Tab Team

	$tab_team['post_type'] = 'team';
	$tab_team['icon'] = 'users';
	$tab_team['title'] = esc_html__( 'Team', 'lsx-testimonials' );
	$tab_team['posts'] = get_post_meta( get_the_ID(), 'team_to_testimonial', false );

	if ( ! empty( $tab_team['posts'][0] ) ) {
		if ( is_array( $tab_team['posts'][0] ) ) {
			$post_ids = $tab_team['posts'][0];
		} else {
			$post_ids = array( $tab_team['posts'][0] );
		}
		// $tab_team['shortcode'] = '[lsx_team columns="4" include="' . $post_ids . '" show_social="false" show_desc="false" show_link="true"]';

		$tab_team['posts_html'] = '';

		$args = array(
			'post_type'              => 'team',
			'post__in'               => $post_ids,
			'orderby'                => 'post__in',
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		$team_ = new \WP_Query( $args );

		if ( $team_->have_posts() ) {
			$tab_team['posts_html'] = array();

			while ( $team_->have_posts() ) {
				$team_->the_post();
				$tab_team['posts_html'][] = '<a href="' . get_permalink() . '">' . the_title( '', '', false ) . '</a>';
				wp_reset_postdata();
			}

			$tab_team['posts_html'] = join( ', ', $tab_team['posts_html'] );
		}

		$tabs_output[] = $tab_team;
	}
?>

<?php lsx_entry_before(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php lsx_entry_top(); ?>

	<figure class="lsx-testimonials-avatar">
		<?php echo wp_kses_post( $thumbnail ); ?>
	</figure>

	<h1 class="lsx-testimonials-title" style="text-align:center;"><?php the_title(); ?></h1>

	<?php if ( ! empty( $byline ) ) : ?>
		<small class="lsx-testimonials-meta-wrap">
			<i class="fa fa-briefcase"></i> <span class="lsx-testimonials-meta"><?php esc_html_e( 'Role & Company', 'lsx-testimonials' ); ?>:</span>

			<?php if ( ! empty( $url ) ) : ?>
				<a href="<?php echo esc_url( $url ); ?>" target="_blank"><?php echo esc_html( $byline ); ?></a>
			<?php else : ?>
				<?php echo esc_html( $byline ); ?>
			<?php endif; ?>
		</small>
	<?php endif; ?>

	<?php if ( count( $tabs_output ) > 0 ) : ?>
		<?php foreach ( $tabs_output as $i => $tab_output ) :
			if ( $tab_output['posts_html'] ) {
			?>
			<small class="lsx-testimonials-meta-wrap">
				<i class="fa fa-<?php echo esc_attr( $tab_output['icon'] ); ?>"></i> <span class="lsx-testimonials-meta"><?php echo esc_html( $tab_output['title'] ); ?>:</span>
				<?php echo wp_kses_post( $tab_output['posts_html'] ); ?>
			</small>
			<?php
			}
		endforeach; ?>
	<?php endif; ?>

	<blockquote class="lsx-testimonials-content"><?php the_content(); ?></blockquote>

	<?php lsx_entry_bottom(); ?>

</article><!-- #post-## -->

<?php
lsx_entry_after();
