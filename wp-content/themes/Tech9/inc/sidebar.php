<div id="sidebar">

<?php include (TEMPLATEPATH . '/inc/searchform.php'); ?>

<?php if ( !function_exists('dynamic_sidebar')
	        || !dynamic_sidebar('blog-sidebar1') ) : ?>
	        

<h2>Recently</h2>
<ul>
<?php get_archives('postbypost', 4); ?>
</ul>

<h2>Categories</h2>
<ul>
<?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
</ul>
		
<h2>Archive</h2>
<ul>
<?php wp_get_archives('type=monthly&show_post_count=true'); ?>
</ul>
        
<?php endif; ?>

</div>