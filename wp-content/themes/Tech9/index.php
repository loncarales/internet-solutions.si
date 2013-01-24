<?php get_header(); ?>

<div id="sow">
<div id="tagline"><h2><?php bloginfo('description'); ?></h2></div>
<div id="content">

<div id="middle">
<div id="last">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<h2><?php the_title(); ?></h2>
</div>
<div class="entry">
<?php the_content(__('Read more'));?>
<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
<div class="clearfix"></div><hr class="clear" />
<!--<?php trackback_rdf(); ?>-->
<h3><?php edit_post_link('Edit','',''); ?></h3>
<div class="cat">Tagged with <?php the_category(', ') ?>
</div>

</div>

<div id="slidebox">
            <a class="close"></a>
            <p>More in <?php the_category(' ',multiple,false,' &raquo; ') ?> (3 of <?php echo get_category(reset(get_the_category($post->ID))->cat_ID)->count; ?> articles)</p>
            <?php 
$max_articles = 3; // How many articles to display 
echo '<ul>'; 
$cnt = 0; $article_tags = get_the_tags(); 
$tags_string = ''; 
if ($article_tags) { 
foreach ($article_tags as $article_tag) { 
$tags_string .= $article_tag->slug . ','; 
} 
} 
$tag_related_posts = get_posts('exclude=' . $post->ID . '&numberposts=' . $max_articles . '&tag=' . $tags_string); 
if ($tag_related_posts) { 
foreach ($tag_related_posts as $related_post) { 
$cnt++; 
echo '<li class="child-' . $cnt . '">'; 
echo '<a href="' . get_permalink($related_post->ID) . '">'; 
echo $related_post->post_title . '</a></li>'; 
} 
} 
// Only if there's not enough tag related articles, 
// we add some from the same category 
if ($cnt < $max_articles) { 
$article_categories = get_the_category($post->ID); 
$category_string = ''; 
foreach($article_categories as $category) { 
$category_string .= $category->cat_ID . ','; 
} 
$cat_related_posts = get_posts('exclude=' . $post->ID . '&numberposts=' . $max_articles . '&category=' . $category_string); 
if ($cat_related_posts) { 
foreach ($cat_related_posts as $related_post) { 
$cnt++; 
if ($cnt > $max_articles) break; 
echo '<li class="child-' . $cnt . '">'; 
echo '<a href="' . get_permalink($related_post->ID) . '">'; 
echo $related_post->post_title . '</a></li>'; 
} 
} 
} 
echo '</ul>'; 
?>
</div>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>       

<div class="clearfix"></div><hr class="clear" />

<?php comments_template(); // Get wp-comments.php template ?>

<div class="clearfix"></div><hr class="clear" />

<div class="navigation">
<div class="alignleft"><?php previous_post('&laquo; %','','yes') ?></div>
<div class="alignright"><?php next_post(' % &raquo;','','yes') ?></div>
</div>

</div>
<?php include(TEMPLATEPATH."/inc/sidebar.php");?>
</div>
</div>
</div>
</div>
<?php get_footer(); ?>