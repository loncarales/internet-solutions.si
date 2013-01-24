<?php get_header(); ?>
  <div id="content">
  
  <?php if (have_posts()) : ?>
  
  	<?php while (have_posts()) : the_post(); ?>
  
    <div class="post" id="post-<?php the_ID(); ?>">
	  <div class="entrytop"><div class="entry">
        <h2><span class="inpost-date"><?php the_time('F jS, Y') ?></span><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	  </div></div>
		<div class="post-content">
			<?php the_content('Read the rest of this entry &raquo;'); ?>
		</div>
		<div class="post-info">
			<span class="post-cat"><?php the_category(', ') ?></span> <span class="post-comments"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></span>
		</div>
	</div>
	
	<?php endwhile; ?>
	
	<div class="navigation">
	  <span class="previous-entries"><?php next_posts_link('Previous Entries') ?></span> <span class="next-entries"><?php previous_posts_link('Next Entries') ?></span>
	</div>
	
	<?php else : ?>
	
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		
  <?php endif; ?>
	
  </div><!--/content -->
  
<?php get_sidebar(); ?>

<?php get_footer(); ?>
