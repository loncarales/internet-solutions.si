<?php

function padd_widget_funct_socialnet() {
	$padd_sb_feedburner = unserialize(get_option(PADD_THEME_SLUG . '_sn_username_feedburner'));
	$padd_sb_twitter = unserialize(get_option(PADD_THEME_SLUG . '_sn_username_twitter'));
	$padd_sb_googlebuzz = unserialize(get_option(PADD_THEME_SLUG . '_sn_username_googlebuzz'));
	$padd_sb_facebook = unserialize(get_option(PADD_THEME_SLUG . '_sn_username_facebook'));
?>
<ul class="padd-socialnet">
	<li class="googlebuzz">
		<a href="<?php echo $padd_sb_googlebuzz; ?>" class="icon-googlebuzz" title="Google Buzz">
			<img alt="Google Buzz" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-sn-32-googlebuzz.png" />
		</a>
		<a href="<?php echo $padd_sb_googlebuzz; ?>" class="icon-googlebuzz" title="Google Buzz">
			Buzz
		</a>
	</li>
	<li class="twitter">
		<a href="<?php echo $padd_sb_twitter; ?>" class="icon-twitter" title="Twitter">
			<img alt="Twitter" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-sn-32-twitter.png" />
		</a>
		<a href="<?php echo $padd_sb_twitter; ?>" class="icon-twitter" title="Twitter">
			Twitter
		</a>
	</li>
	<li class="facebook">
		<a href="<?php echo $padd_sb_facebook; ?>" class="icon-facebook" title="Facebook Profile">
			<img alt="Facebook" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-sn-32-facebook.png" />
		</a>
		<a href="<?php echo $padd_sb_facebook; ?>" class="icon-facebook" title="Facebook Profile">
			Facebook
		</a>
	</li>
	<li class="rss">
		<a href="<?php echo $padd_sb_feedburner; ?>" title="RSS Feed">
			<img alt="RSS" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-sn-32-rss.png" />
		</a>
		<a href="<?php echo $padd_sb_feedburner; ?>" title="RSS Feed">
			RSS
		</a>
	</li>
	<li class="email">
		<a href="http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $padd_sb_feedburner->get_username(); ?>" title="RSS Email">
			<img alt="Feedburner" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-sn-32-email.png" />
		</a>
		<a href="http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $padd_sb_feedburner->get_username(); ?>" title="RSS Email">
			Email
		</a>
	</li>
</ul>
<?php
}

function padd_widget_funct_banner() {
	global $ad_default_728;
	$sqbtn_1 = unserialize(get_option(PADD_THEME_SLUG . '_ads_728090_1'));
	$sqbtn_1 = $sqbtn_1->is_empty() ? $ad_default_728 : $sqbtn_1; 
	$sqbtn_1->set_css_class('ads1');
	echo $sqbtn_1;
}

/**
 * Renders the advertisements.
 */
function padd_widget_funct_sponsors() {
	global $ad_default_125;
	$sqbtn_1 = unserialize(get_option(PADD_THEME_SLUG . '_ads_125125_1'));
	$sqbtn_1 =$sqbtn_1->is_empty()  ? $ad_default_125 : $sqbtn_1; 
	$sqbtn_1->set_css_class('ads1');
	$sqbtn_2 = unserialize(get_option(PADD_THEME_SLUG . '_ads_125125_2'));
	$sqbtn_2 = $sqbtn_2->is_empty() ? $ad_default_125 : $sqbtn_2; 
	$sqbtn_2->set_css_class('ads2');
	$sqbtn_3 = unserialize(get_option(PADD_THEME_SLUG . '_ads_125125_3'));
	$sqbtn_3 = $sqbtn_3->is_empty() ? $ad_default_125 : $sqbtn_3; 
	$sqbtn_3->set_css_class('ads3');
	$sqbtn_4 = unserialize(get_option(PADD_THEME_SLUG . '_ads_125125_4'));
	$sqbtn_4 = $sqbtn_4->is_empty() ? $ad_default_125 : $sqbtn_4; 
	$sqbtn_4->set_css_class('ads4');
	echo '<span>' . $sqbtn_1 . '</span><span>' . $sqbtn_2 . '</span><span>'. $sqbtn_3 . '</span><span>' . $sqbtn_4 . '</span>';
	echo '<div class="padd-clear"></div>';
}

function padd_widget_funct_featured_video() {
	$featured = get_option(PADD_THEME_SLUG . '_featured_video');
	echo stripslashes($featured);
}

function padd_widget_funct_search_form() {
?>
<form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
<p><span class="padd-search-text">Search </span><input type="text" value="" name="s" id="s" /><button type="submit"><span>Search</span></button></p>
</form>
<?php
}

/**
 * Renders the recent posts
 */
function padd_widget_funct_recent_posts($args='') {
	global $wpdb, $wp_locale;

	$defaults = array(
		'limit' => '5', 'before' => '<li>', 'after' => '</li>',
		'format' => 'F j, Y',
		'echo' => 1
	);

	$r = wp_parse_args( $args, $defaults );
	extract($r,EXTR_SKIP);

	if ( '' != $limit ) {
		$limit = absint($limit);
		$limit = ' LIMIT ' . $limit;
	}

	$where = apply_filters('getarchives_where', "WHERE post_type = 'post' AND post_status = 'publish'", $r );
	$join = apply_filters('getarchives_join', "", $r);

	$output = '';

	$orderby = "post_date DESC ";
	$query = "SELECT * FROM $wpdb->posts $join $where ORDER BY $orderby $limit";
	$key = md5($query);
	$cache = wp_cache_get('padd_widget_funct_recent_posts',PADD_THEME_SLUG);
	if (!isset($cache[$key])) {
		$result = $wpdb->get_results($query);
		$cache[$key] = $result;
		wp_cache_add('padd_widget_funct_recent_posts',$cache,PADD_THEME_SLUG);
	} else {
		$result = $cache[$key];
	}
	if ($result) {
		foreach ((array)$result as $res ) {
			if ($res->post_date != '0000-00-00 00:00:00' ) {
				$url  = get_permalink($res);
				$title = $res->post_title;
				if ($title) {
					$text = strip_tags(apply_filters('the_title',$title));
				} else {
					$text = $res->ID;
				}
				$comments = $res->comment_count;
				
				if ($comments == 0 || $comments > 1) {
					$comments = $comments . ' comments';
				} else if ($comments == 1) {
					$comments = $comments . ' comment';
				} 
				$def = get_template_directory_uri() . '/images/thumbnail.jpg';
				$img = padd_theme_get_thumbnail('paddimage',$def,32,32,$res);
				$output .= $before . '<img class="header" src="' . $img . '" alt="" />' .'<a href="' . $url . '" title="' . $text . '">' . $text . '</a> <br /> <span class="padd-date">' . get_the_time($format,$res->ID) . ' | ' . $comments. '</span>'  . $after;
			}
		}
	}

	if ($echo) {
		echo $output;
	} else {
		return $output;
	}
}

function padd_page_menu_args($args) {
	$args['show_home'] = true;
	$args['container'] = false;
	return $args;
}
add_filter('wp_page_menu_args','padd_page_menu_args');

/**
 * Renders the list of bookmarks.
 */
function padd_theme_list_bookmarks() {
	$array = array();
	$array[] = 'category_before=';
	$array[] = 'category_after=';
	$array[] = 'categorize=0';
	$array[] = 'title_li=';
	wp_list_bookmarks(implode('&',$array));
}

function padd_theme_excerpt_length($length) {
	
}
add_filter('excerpt_length', 'padd_theme_excerpt_length');

/**
 * Renders the list of comments.
 *
 * @param string $comment
 * @param string $args
 * @param string $depth
 */
function padd_theme_list_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="padd-comment-<?php comment_ID() ?>">
		<div class="padd-comment">
			<div class="padd-comment-details">
				<div class="padd-comment-author">
					<div class="padd-comment-avatar"><?php echo get_avatar($comment,'53'); ?></div>
					<div class="padd-comment-meta">
						<span class="padd-author"><?php echo get_comment_author_link(); ?></span>
						<span class="padd-time"><?php echo get_comment_date('F j, Y'); ?></span>
					</div>
					<div class="padd-comment-actions">
						<?php edit_comment_link(__('Edit'),'<span class="edit">','</span> | ') ?>
						<?php comment_reply_link(array_merge( $args, array('respond_id' => 'reply' ,'add_below' => 'reply', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
					</div>
					<div class="padd-clear"></div>
				</div>
				<div class="padd-comment-details-interior">
					<div class="padd-comment-details-interior-wrapper">
						<?php comment_text(); ?>
						<?php if ($comment->comment_approved == '0') : ?>
						<p class="comment-notice"><?php _e('My comment is awaiting moderation.') ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php
}

/**
 * Render the list of trackbacks.
 *
 * @param string $comment
 * @param string $args
 * @param string $depth
 */
function padd_theme_list_trackbacks($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="pings-<?php comment_ID() ?>">
		<?php comment_author_link(); ?>
	<?php
}

function padd_theme_count_comments($count) {
	if (!is_admin()) {
		global $id;
		$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
		return count($comments_by_type['comment']);
	} else {
		return $count;
	}
}
add_filter('get_comments_number', 'padd_theme_count_comments',0);


/**
 * Renders the list of children categories in a given parent category.
 *
 * @param int $cat_id
 */
function padd_theme_get_categories($cat_id) {
	if ('' != get_the_category_by_ID($cat_id)) {
		echo '<li>';
		echo '<a href="' . get_category_link($cat_id) . '">' . get_the_category_by_ID($cat_id) . '</a>';
		if ('' != (get_category_children($cat_id))) {
			echo '<ul>';
			wp_list_categories('hide_empty=0&title_li=&child_of=' . $cat_id);
			echo '</ul>';
		}
		echo '</li>';
	}
}

/**
 * Renders the list of recent comments.
 *
 * @global object $wpdb
 * @global array $comments
 * @global array $comment
 * @param int $limit
 */
function padd_theme_recent_comments($limit=5) {
	global $wpdb, $comments, $comment;

	if ( !$comments = wp_cache_get( 'recent_comments', 'widget' ) ) {
		$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date_gmt DESC LIMIT $limit");
		wp_cache_add( 'recent_comments', $comments, 'widget' );
	}
	echo '<ul class="padd-comments-recent">';
	if ( $comments ) :
		foreach ( (array) $comments as $comment) :
			echo  '<li class="padd-comments-recent"><span class="padd-wrap">' . sprintf(__('%1$s on %2$s'), get_comment_author_link(), '<a href="'. get_comment_link($comment->comment_ID) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</span></li>';
		endforeach;
	endif;
	echo '</ul>';
}

function padd_theme_summary($text='',$max=400) {
	if (!empty($text)) {
		$l = strlen($text);
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = preg_replace('#(\n?<script[^>]*?>.*?</script[^>]*?>)|(\n?<script[^>]*?/>)#is', '', $text);
		$text = strip_tags($text);
		if ($l>$max) {
			$text = substr($text,0,$max);
			$text = trim($text,' ,.?!:');
			$text .= '...';
		}
	}
	return $text;
}

/**
 * Capture the first image from the post.
 *
 * @global object $post
 * @global object $posts
 * @return string
 */
function padd_theme_capture_first_image($p=null) {
	$firstImg = '';
	if (empty($p)) {
		global $post, $posts;
		$firstImg = '';
		ob_start(); ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$firstImg = $matches[1][0];
	} else {
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $p->post_content, $matches);
		$firstImg = $matches[1][0];
	}
	return $firstImg;
}

/**
 * Returns the thumbnailed image.
 *
 * @param string $cust_field
 * @param string $def_img
 * @param int $w
 * @param int $h
 */
function padd_theme_get_thumbnail($cust_field,$def_img,$w,$h,$p=null) {
	$padd_scrp = get_template_directory_uri() . '/functions/thumb/thumb.php?';
	$padd_image = '';
	$padd_image_def = get_template_directory_uri() . '/images/thumbnail.jpg';
	$padd_image = get_post_meta($p->ID,$cust_field,true);
	if (empty($padd_image)) {
		$padd_image = padd_theme_capture_first_image($p);
	}

	if (empty($padd_image)) {
		$imgpath = $def_img;
	} else {
		$imgpath = $padd_scrp . 'src=' . $padd_image . '&amp;w=' . $w . '&amp;h=' . $h . '&amp;zc=1';
	}
	
	return $imgpath;
}

/**
 * Renders the featured posts in home page.
 */
function padd_theme_featured_posts() {
	wp_reset_query(); 
	$featured = get_option(PADD_THEME_SLUG . '_featured_cat_id','1');
	$count = get_option(PADD_THEME_SLUG . '_featured_cat_limit');
	query_posts('showposts=' . $count . '&cat=' . $featured);
	$padd_scrp = get_theme_root_uri() . '/' . PADD_THEME_SLUG . '/functions/thumb/thumb.php?';
	$i = 1;
?>
<div id="s3slider">
	<ul id="s3sliderContent">
<?php while (have_posts()) : the_post(); ?>
	<?php 
		$img = get_post_meta(get_the_ID(),'_' . PADD_THEME_SLUG . '_post_gallery',true);
		$src = get_permalink();
		if (empty($img)) {
			$img = padd_theme_capture_first_image();
			if (empty($img)) {
				$imgpath = $padd_image_def;
			} else {
				$imgpath = $padd_scrp . 'src=' . $img . '&amp;w=' . PADD_GALL_THUMB_W . '&amp;h=' . PADD_GALL_THUMB_H . '&amp;zc=1';
			}
		} else {
			$imgpath = $padd_scrp . 'src=' . $img . '&amp;w=' . PADD_GALL_THUMB_W . '&amp;h=' . PADD_GALL_THUMB_H . '&amp;zc=1';
		}
	?>
	<li class="s3sliderImage">
		<img src="<?php echo $imgpath; ?>" alt="<?php the_title(); ?>" />
		<span>
			<strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong>
			<br />
			<?php echo padd_theme_summary(get_the_content(),200); ?>
		</span>
	</li>
	<?php $i++; ?>
<?php endwhile; ?>
		 <div class="padd-clear s3sliderImage"></div>
	</ul>
</div>
<?php
	wp_reset_query();
}

/**
 * Renders the TweetMeme button.
 */
function padd_tweetmeme_generate_button($style='') {
	global $post;
	$url = '';
	if (get_post_status($post->ID) == 'publish') {   
		$url = get_permalink();
	} 
	?>
	<div class="padd-tweetmeme">
		<script type="text/javascript">
			tweetmeme_url = '<?php echo $url; ?>';
			<?php echo !empty($style) ? 'tweetmeme_style="' . $style . '"': ''; ?>
		</script>
		<script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
	</div>
	<?php
}

/**
 * Renders the Facebook Share button 
 */
function padd_facebookshare_generate_button() {
	global $post;
	$url = '';
	if (get_post_status($post->ID) == 'publish') {   
		$url = get_permalink();
		$title = get_the_title();
	} 
	?>
	<div class="padd-facebookshare">
		<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php?u=<?php echo urlencode($url); ?>&amp;t=<?php echo urlencode($title); ?>">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
	</div>
	<?php
}

/**
 * Renders the Google Buzz button 
 */
function padd_googlebuzz_generate_button() {
	global $post;
	$url = '';
	if (get_post_status($post->ID) == 'publish') {   
		$url = get_permalink();
		$title = get_the_title();
	} 
	?>
	<div class="padd-googlebuzz">
		<a href="http://www.google.com/reader/link?url=<?php echo urlencode($url); ?>&amp;title=<?php echo urlencode($title); ?>&amp;snippet=<?php echo urlencode(padd_theme_summary(get_the_content(),160)) ;?>&amp;srcURL=<?php echo urlencode(get_option('home')); ?>&amp;srcTitle=<?php urlencode(get_option('name')); ?>">Buzz it!</a>
	</div>
	<?php
}

/** 
 * Renders the related posts
 *
 * @param int|string $post_ID
 */
function padd_related_posts($post_ID) {
	$enabled = get_option(PADD_THEME_SLUG . '_rp_enable');
	if ($enabled) {
		
		$tag_ids = array();
		$cat_ids = array();
		
		$tags = wp_get_post_tags($post_ID);
		foreach($tags as $tag) {
			$tag_ids[] = $tag->term_id;
		}
		
		$cats = get_the_category($post_ID);
		if ($cats) {
			foreach($cats as $cat) {
				$cat_ids[] = $cat->term_id;
			}
		}

		$args = array(
					'post__not_in' => array($post_ID),
					'showposts' => intval(get_option(PADD_THEME_SLUG . '_rp_max',5)),
					'caller_get_posts' => 1
				); 
		if (!empty($tag_ids) && get_option(PADD_THEME_SLUG . '_consider_tags','1') === '1') {
			$args['tag__in'] = $tag_ids;
		}
		if (!empty($cat_ids) && get_option(PADD_THEME_SLUG . '_consider_categories','1') === '1') {
			$args['category__in'] = $cat_ids;
		}
		
		$rp_query = new wp_query($args);

		if ($rp_query->have_posts()) {
			echo '<ul>';
			while ($rp_query->have_posts()) {
				$rp_query->the_post();
			?>
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php
			}
			echo '</ul>';
		} else {
			echo '<p>There are no related posts on this entry.</p>';
		}
	} else {
		echo '<p>Related posts has been disabled.</p>';
	}
	// That should fix the bug in the single.php.
	wp_reset_query();
}

/** 
 * Renders the theme credits.
 */
function padd_theme_credits() {
	do_action(__FUNCTION__);
}

?>
