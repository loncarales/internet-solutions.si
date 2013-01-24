<?php
/**
 * @package WordPress
 * @subpackage magazine_obsession
 */

/* Get ID of the page, if this is current page */

function obwp_get_page_id () {
	global $wp_query;

	if ( !$wp_query->is_page )
		return -1;

	$page_obj = $wp_query->get_queried_object();

	if ( isset( $page_obj->ID ) && $page_obj->ID >= 0 )
		return $page_obj->ID;

	return -1;
}

/**
 * Get Meta post/pages value
 * $type = string|int
 */
function obwp_get_meta($var, $type = 'string', $count = 0)
{
	$value = stripslashes(get_option($var));
	
	if($type=='string')
	{
		return $value;
	}
	elseif($type=='int')
	{
		$value = intval($value);
		if( !is_int($value) || $value <=0 )
		{
			$value = $count;
		}
		
		return $value;
	}
	
	return NULL;
}

/**
 * Get custom field of the current page
 * $type = string|int
 */
function obwp_getcustomfield($filedname, $page_current_id = NULL)
{
	if($page_current_id==NULL)
		$page_current_id = obwp_get_page_id();

	$value = get_post_meta($page_current_id, $filedname, true);

	return $value;
}
function the_title_limited($length = false, $before = '', $after = '', $echo = true)
{
	$title = get_the_title();

	if ( $length && is_numeric($length) )
	{
		$title = substr( $title, 0, $length );
	}
	if ( strlen($title)> 0 )
	{
		$title = apply_filters('the_title2', $before . $title . $after, $before, $after);
		if ( $echo )
			echo $title;
		else
			return $title;
	}
}

function the_content_limit($max_char, $more_link_text = '(more...)', $use_p = true, $stripteaser = 0, $more_file = '', $tags = '')
{
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = strip_tags($content,$tags);

   if (strlen($_GET['p']) > 0) {
	  if($use_p)
      	echo "<p>";
      echo $content;
	  if(!empty($more_link_text))
	  {
		  echo "&nbsp;<span class=\"more\"><a href='";
		  the_permalink();
		  echo "'>".$more_link_text."</a></span>";
	  }
	  if($use_p)
      	echo "</p>";
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
	  	if($use_p)
       		echo "<p>";
        echo $content;
        echo "...";
		if(!empty($more_link_text))
		{
        	echo "&nbsp;<span class=\"more\"><a href='";
        	the_permalink();
        	echo "'>".$more_link_text."</a></span>";
		}
	  	if($use_p)
        	echo "</p>";
   }
   else {
	  if($use_p)
      	echo "<p>";
      echo $content;
	  if(!empty($more_link_text))
	  {
		  echo "&nbsp;<span class=\"more\"><a href='";
		  the_permalink();
		  echo "'>".$more_link_text."</a></span>";
	  }
	  if($use_p)
      	echo "</p>";
   }
}


 
function theme_ads_show()
{
	global $shortname;
	$count = obwp_get_meta(SHORTNAME."_count_banner_125_125",'int');

	if($count>0)
	{
		for($i=1; $i<=$count; $i++)
		{
			$banner_url = obwp_get_meta(SHORTNAME.'_banner_125_125_url_'.$i);
			$banner_img = obwp_get_meta(SHORTNAME.'_banner_125_125_img_'.$i);
			$banner_title = obwp_get_meta(SHORTNAME.'_banner_125_125_title_'.$i);

			if(!empty($banner_url) && !empty($banner_img))
			{
			?><div><a href="<?php echo $banner_url; ?>" title="<?php echo $banner_title; ?>"><img src="<?php echo $banner_img; ?>" alt="<?php echo $banner_title; ?>" /></a></div><?php
			}
		}
	}
}

function wp_list_pages2($args) {
	$defaults = array(
		'depth' => 0, 'show_date' => '',
		'date_format' => get_option('date_format'),
		'child_of' => 0, 'exclude' => '',
		'title_li' => __('Pages'), 'echo' => 1,
		'authors' => '', 'sort_column' => 'menu_order, post_title',
		'link_before' => '', 'link_after' => ''
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$output = '';
	$current_page = 0;

	// sanitize, mostly to keep spaces out
	$r['exclude'] = preg_replace('/[^0-9,]/', '', $r['exclude']);

	// Allow plugins to filter an array of excluded pages
	$r['exclude'] = implode(',', apply_filters('wp_list_pages_excludes', explode(',', $r['exclude'])));

	// Query pages.
	$r['hierarchical'] = 0;
	$pages = get_pages($r);

	if ( !empty($pages) ) {
		if ( $r['title_li'] )
			$output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';

		global $wp_query;
		if ( is_page() || $wp_query->is_posts_page )
			$current_page = $wp_query->get_queried_object_id();
		$output .= walk_page_tree($pages, $r['depth'], $current_page, $r);

		if ( $r['title_li'] )
			$output .= '</ul></li>';
	}

	$output = apply_filters('wp_list_pages', $output);

	if ( $r['echo'] )
		echo $output;
	else
		return $output;
}

function get_string_limit($output, $max_char)
{
    $output = str_replace(']]>', ']]&gt;', $output);
    $output = strip_tags($output);

  	if ((strlen($output)>$max_char) && ($espacio = strpos($output, " ", $max_char )))
	{
        $output = substr($output, 0, $espacio).'...';
		return $output;
   }
   else
   {
      return $output;
   }
}

function obwp_list_most_commented($no_posts = 5, $before = '<li>', $after = '</li>', $show_pass_post = false, $duration=''){
    global $wpdb;
	
	$list_most_commented = wp_cache_get('list_most_commented');
	if ($list_most_commented === false) {
		$request = "SELECT ID, post_title, comment_count FROM $wpdb->posts";
		$request .= " WHERE post_status = 'publish'";
		if (!$show_pass_post) $request .= " AND post_password =''";
	
		if ($duration !="") $request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	
		$request .= " ORDER BY comment_count DESC LIMIT $no_posts";
		$posts = $wpdb->get_results($request);

		if ($posts) {
			foreach ($posts as $post) {
				$post_title = htmlspecialchars($post->post_title);
				$comment_count = $post->comment_count;
				$permalink = get_permalink($post->ID);
				$list_most_commented .= $before . '<a href="' . $permalink . '" title="' . $post_title.'">' . $post_title . '</a> (' . $comment_count.')' . $after;
			}
		} else {
			$list_most_commented .= $before . "None found" . $after;
		}
	
		wp_cache_set('list_most_commented', $list_most_commented);
	} 

    echo $list_most_commented;
}

function theme_twitter_show($count = 1)
{
	$id = obwp_get_meta(SHORTNAME."_twitter_id");
	if(!empty($id))
	{
	?>
	<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
	<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $id; ?>.json?callback=twitterCallback2&amp;count=<?php echo $count; ?>"></script>
	<?php
	}
}
function list_pings($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	?>
	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
	<?php 
}

function list_recent_posts($number = 10) {

	$r = new WP_Query("showposts=$number&what_to_show=posts&nopaging=0&post_status=publish");
	if ($r->have_posts()) :
?>
		<ul>
			<?php $i=0;  while ($r->have_posts()) : $r->the_post(); $i++; ?>
			<li <?php if($i==$number) echo 'class="last"'; ?>><a href="<?php the_permalink() ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?> </a></li>
			<?php endwhile; ?>
		</ul>
<?php
		wp_reset_query();  // Restore global post data stomped by the_post().
	endif;

}
function recent_comments($number = 10) {
	global $wpdb, $comments, $comment;

	$comments = $wpdb->get_results("SELECT comment_author, comment_author_url, comment_ID, comment_post_ID FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date_gmt DESC LIMIT $number");
?>

        <ul><?php
		 $i=0;
		 $last = '';
        if ( $comments ) : foreach ($comments as $comment) :
		 $i++;
		if($i==$number) $last = 'last';
        echo  '<li class="recentcomments '.$last.'">' . sprintf(__('<b>%1$s</b> on %2$s'), get_comment_author_link(), '<a href="'. get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
        endforeach; endif;?></ul>
<?php
}

function obwp_google_468_ads_show()
{
	$id = obwp_get_meta(SHORTNAME."_google_id");
	if(!empty($id))
	{
		echo $code = '<div class="banner"><script type="text/javascript"><!--
google_ad_client = "'.$id.'";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image"; 
google_color_border = "c5c5c5";
google_color_bg = "ffffff";
google_color_link = "9d080d";
google_color_url = "9d080d";
google_color_text = "000000"; 
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';
	}
}

function obwp_google_300_ads_show()
{
	$id = obwp_get_meta(SHORTNAME."_google_id");
	if(!empty($id))
	{
		echo $code = '<div class="banner_left"><script type="text/javascript"><!--
google_ad_client = "'.$id.'";
google_ad_width = 300;
google_ad_height = 250;
google_ad_format = "300x250_as";
google_ad_type = "text_image"; 
google_color_border = "c5c5c5";
google_color_bg = "ffffff";
google_color_link = "9d080d";
google_color_url = "9d080d";
google_color_text = "000000"; 
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';
	}
}


?>