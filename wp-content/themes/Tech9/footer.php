<div id="footer">
<div class="inner">

<div class="clearfix"></div><hr class="clear" />

<div class="col11">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column1') ) : ?>		        

<h3><?php _e("Popular"); ?></h3>
<?php $result = $wpdb->get_results("SELECT comment_count,ID,post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 , 3"); 
foreach ($result as $post) { 
setup_postdata($post);
$postid = $post->ID; 
$title = $post->post_title; 
$commentcount = $post->comment_count; 
if ($commentcount != 0) { ?> 

<ul>
<li><a href="<?php echo get_permalink($postid); ?>" title="<?php echo $title ?>">
<?php echo $title ?></a> <small>with <?php echo $commentcount ?> Comments</small></li>
</ul>
<?php } } ?>

<?php endif; ?>
</div>

<div class="col12">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column2') ) : ?>		        

<h3><?php _e("Categories"); ?></h3>
<ul><?php wp_list_categories('orderby=id&limit=5&show_count=0&sort_column=name&title_li=&depth=1'); ?></ul>

<?php endif; ?>	
</div>

<div class="col13">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column3') ) : ?>
		        
<h3>Popular Tags</h3>
<?php wp_tag_cloud('format=list&number=10&orderby=count&unit=12'); ?>

<?php endif; ?>
</div>

<div class="col14">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column4') ) : ?>

<h3><?php _e("Pages"); ?></h3>
<ul><?php wp_list_pages('title_li=&depth=1&sort_column=menu_order'); ?></ul>

<?php endif; ?>
</div>

<div class="clearfix"></div><hr class="clear" />
<div class="fix"></div>

<div class="creditl">
<ul>
<li><a title="Home is where the heart is" href="<?php echo get_settings('home'); ?>">Home</a></li>
<?php wp_list_pages('title_li=&exclude=183657917,183657912,183657914,183657918&depth=1'); ?>
</ul>
</div>

<div class="credits"> </div>

<div class="copy">
Copyright &copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?> All Rights reserved | Created by <a title="The support page for your theme." href="http://3oneseven.com/">miloIIIIVII</a>
</div>

</div>
</div>
</div>
<?php do_action('wp_footer'); ?>

</body>
</html>