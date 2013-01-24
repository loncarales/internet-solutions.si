<?php get_header(); ?>

<div id="sow">
<div id="tagline"><h2><?php bloginfo('description'); ?></h2></div>
<div id="content">

<div id="middle">
<h3>Search <span>Result</span> for <?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; _e(''); echo $key; _e(' &mdash; '); echo $count . ' '; _e('articles'); wp_reset_query(); ?></small></h3>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>

<div class="entry">
<?php the_excerpt(__('Read more'));?></div>
<div class="more"><a href="<?php the_permalink() ?>">Read More</a></div>
<div class="postspace"></div>


<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php endif; ?>

<div class="navigation">
<?php if(function_exists('wp_pagenavi')) { wp_pagenavi('', '', '', '', 3, false);} ?>
</div>

</div>
<?php include(TEMPLATEPATH."/inc/sidebar.php");?>
</div>
</div>
</div>

</div>
<?php get_footer(); ?>