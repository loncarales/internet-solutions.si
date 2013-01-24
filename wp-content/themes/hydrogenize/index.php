<?php
/*
Template Name: Index Page
*/
?>
<?php get_header(); ?>

<div id="padd-content">
	<div id="padd-content-wrapper">

<div class="padd-post-group padd-post-group-index">
	<div class="padd-post-group-title">
		<h2>Home Page</h2>
	</div>
	
	<div class="padd-post-list">
	<?php while (have_posts()) : ?>
		<?php the_post(); ?>
		<div class="padd-post-item padd-post-item-result" id="post-<?php the_ID(); ?>">
			<?php
				$def = get_template_directory_uri() . '/images/thumbnail.jpg';
				$img = padd_theme_get_thumbnail('_' . PADD_THEME_SLUG . '_post_thumbnail',$def,PADD_LIST_THUMB_W,PADD_LIST_THUMB_H,get_post(get_the_ID()));
			?>	
			<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><img class="header" src="<?php echo $img; ?>" alt="<?php the_title(); ?>" /></a>
			<div class="padd-post-title">
				<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
			</div>
			<div class="padd-post-entry">
				<p>
				<?php 
					$post_method = get_option(PADD_THEME_SLUG . '_post_list_mode','teaser');
					if ('teaser' === $post_method) {
						echo padd_theme_summary(get_the_content(),350);
					} else {
						the_excerpt();
					}
				?>
				</p>
			</div>
			<p class="padd-meta">
				<span class="date-cat"><?php the_time('F j, Y'); ?> in <?php the_category(', '); ?></span><span class="padd-no-display">. </span>
				<span class="comment-count"><a href="<?php comments_link(); ?>"><?php comments_number('No comments','1 comment','% comments')?></a></span>
			</p>
		</div>
	<?php endwhile; ?>
	</div>
	
	<?php Padd_PageNavigation::render(); ?>
</div>

	</div>
</div>
<?php get_sidebar(); ?>

<div class="padd-clear"></div>	
		
<?php get_footer(); ?>