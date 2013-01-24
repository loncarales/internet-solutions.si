<?php get_header(); ?>
  <div id="content">
  
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  
    <div class="post" id="post-<?php the_ID(); ?>">
	  <div class="entrytop"><div class="entry">
        <h2><span class="inpost-date"><?php the_time('F jS, Y') ?></span><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	  </div></div>
			<span class="post-meta">Posted by <?php if (get_the_author_url()) { ?><a href="<?php the_author_url(); ?>"><?php the_author(); ?></a><?php } else { the_author(); } ?> in <?php the_category(', ') ?></span>

		<div class="post-content">
		<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
		
		<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
		
		<?php edit_post_link('Edit', '', ''); ?>
		
		</div>
		
		<?php comments_template(); ?>
		
			<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

	</div><!--/post -->

  </div><!--/content -->

<?php get_sidebar(); ?>
  
<?php get_footer(); ?>

