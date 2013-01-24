<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

		<?php if (have_posts()) : ?>

		<?php $i=0; while (have_posts()) : the_post(); $i++; ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                <div class="post_top">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php if ( function_exists('the_title_attribute')) the_title_attribute(); else the_title(); ?>"><?php the_title(); ?></a></h2>
                    
                 
                    
					<div class="post_panel">  
						<div class="post_date">Posted on: <?php the_time('d-m-Y') ?></div>
						<div class="post_comm"><?php comments_popup_link('0', '1', '%'); ?></div>
					</div>
					<p class="post_cats"><b>Category :</b> <span><?php the_category(', ') ?></span></p>
					<?php the_tags('<p class="post_tags"><b>Tags:</b> ', ', ', '</p>'); ?>
                </div>
				<div class="entry">  <?php include (TEMPLATEPATH . '/thumbnail.php'); ?>
					<?php if($i==1 || $i==2) : ?>
					<?php obwp_google_468_ads_show(); ?>
					<? endif; ?>
					<?php the_content('',FALSE,''); ?>
				</div>
                <div class="postmetadata">
					<a href="<?php the_permalink() ?>">Continue Reading</a>
                </div>
			</div>

		<?php endwhile; ?>
		
		<div class="entry">
			<?php obwp_google_468_ads_show(); ?>
		</div>

		<div class="navigation">
			<?php if(!function_exists('wp_pagenavi')) : ?>
            <div class="alignleft"><?php next_posts_link('Previous') ?></div>
            <div class="alignright"><?php previous_posts_link('Next') ?></div>
            <?php else : wp_pagenavi(); endif; ?>
		</div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

	</div>
	<?php get_sidebar(); ?>
	<?php get_sidebar('right'); ?>

<?php get_footer(); ?>