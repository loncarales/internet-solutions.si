<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                <div class="post_top">
					<h2><?php the_title(); ?></h2>
					<div class="post_panel">
						<div class="post_date">Posted on: <?php the_time('d-m-Y') ?></div>
						<div class="post_comm"><?php comments_popup_link('(0)', '(1)', '(%)'); ?></div>
					</div>
					<p class="post_cats"><b>Category :</b> <span><?php the_category(', ') ?></span></p>
					<?php the_tags('<p class="post_tags"><b>Tags:</b> ', ', ', '</p>'); ?>
                </div>
				<div class="entry">
					<?php obwp_google_300_ads_show(); ?>
					<?php the_content('Read the rest of this entry &raquo;'); ?>
					<?php obwp_google_468_ads_show(); ?>
				</div>
                <div class="postmetadata">
					<p><a href="<?php the_permalink() ?>">Continue Reading</a></p>
                </div>
			</div>

	<?php comments_template('', true); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

	</div>
	<?php get_sidebar(); ?>
	<?php get_sidebar('right'); ?>

<?php get_footer(); ?>
