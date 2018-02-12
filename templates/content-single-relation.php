<?php
/**
 * @package lsx-testimonials
 */
?>

<article class="entry-relation-slot">
	<div class="row">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="col-xs-12 col-md-3">
				<figure class="entry-relation-thumbnail">
					<?php lsx_thumbnail( 'lsx-thumbnail-single' ); ?>
				</figure>
			</div>
		<?php endif; ?>

		<div class="col-xs-12<?php if ( has_post_thumbnail() ) echo ' col-md-9'; ?>">
			<h4 class="entry-relation-name"><?php the_title(); ?></h4>
			<div class="entry-relation-content"><?php the_excerpt(); ?></div>
		</div>
	</div>
</article>
