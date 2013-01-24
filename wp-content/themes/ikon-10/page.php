<?php get_header(); ?>
  <div id="content">
  
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
    <div class="post" id="post-<?php the_ID(); ?>">
	  <div class="entrytop"><div class="entry">
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	  </div></div>
        <div class="post-content">
			<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

			<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
        </div>
    </div>
	
  <?php endwhile; endif; ?>

  </div><!--/content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>