<?php get_header(); ?>

<div id="sow">
<div id="tagline"><h2><?php bloginfo('description'); ?></h2></div>
<div id="content">

<div id="middle">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<h2><?php the_title(); ?></h2>

<div class="entry">
<?php the_content(__('Read more'));?>
</div>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>
</div>

<?php include(TEMPLATEPATH."/inc/pagebar.php");?>
</div>
</div>
</div>

</div>
<?php get_footer(); ?>