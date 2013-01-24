<?php
require_once(TEMPLATEPATH . '/inc/control.php'); 
require_once(TEMPLATEPATH . '/inc/panel.php'); 
require_once(TEMPLATEPATH . '/inc/image.php'); 
add_action('admin_head', 'my_custom_logo');
function my_custom_logo() {
   echo '
      <style type="text/css">
         #header-logo { background-image: url('.get_bloginfo('template_directory').'/images/favicon.png) !important; }
      </style>
   ';
} 
function custom_colors() {
   echo '<style type="text/css">#wphead{background:#fafafa !important;border-bottom:6px solid #687B72;color:#333;text-shadow:#fff 0 1px 1px;}#footer{background:#fafafa !important;border-top:6px solid #687B72;color:#333;}#user_info p,#user_info p a,#wphead a{color:#333 !important;}</style>';
}
add_action('admin_head', 'custom_colors');
function remove_footer_admin () {
    echo "Thank you for creating with miloIIIIVII.";
} 
add_filter('admin_footer_text', 'remove_footer_admin'); 
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
function my_custom_dashboard_widgets() {
   global $wp_meta_boxes;
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
   wp_add_dashboard_widget('custom_help_widget', 'Tech9 theme', 'custom_dashboard_help');
}
function custom_dashboard_help() {
   echo '<p>Tech9 theme design</p> 
        <h4>Slider options</h4>
				<p>Several items are handled via options pages,<br />
				like the slider text & links, please go to appearance, Tech9 theme options panel, <br />
        where you can add your own text.</p>
				<h4>Front post options</h4>
        <p>Frontpage text are also enabled via options, find it at<br /> 
        appearance, Tech9 theme options panel<br />
        where you can specify the frontpage text,links etc to show up.</p>
        <h4>Slider</h4>
        <p>Works auto, will load specified text with text and links.</p>
        <h4>Help</h4>
			  <p>Need more help? Contact milo317 via her <a href="http://3oneseven.com/">website</a>.</p>';
} 
// sidebar stuff
register_sidebars( 1, 
	array( 
		'name' => 'footer-column1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);

register_sidebars( 1,
	array( 
		'name' => 'footer-column2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);

register_sidebars( 1,
	array( 
		'name' => 'footer-column3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1, 
	array( 
		'name' => 'footer-column4',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1,
	array( 
		'name' => 'blog-sidebar1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1,
	array( 
		'name' => 'blog-sidebar2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
function initial_cap($content){
    // Regular Expression, matches a single letter
    // * even if it's inside a link tag.
    $searchfor = '/>(<a [^>]+>)?([^<\s])/';
    // The string we're replacing the letter for
    $replacewith = '>$1<span class="drop">$2</span>';
    // Replace it, but just once (for the very first letter of the post)
    $content = preg_replace($searchfor, $replacewith, $content, 1);
    // Return the result
    return $content;
}
// Add this function to the WordPress hook
add_filter('the_excerpt', 'initial_cap');
function custom_login() { 
echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/log/log.css" />'; 
}   
add_action('login_head', 'custom_login');
function milo_body_control() { 
global $post; 
$postclass = $post->post_name;
if (is_home()) { 
echo 'id="home"'; 
} elseif (is_single()) { 
echo 'id="single" class="' . $postclass . '"';
} elseif (is_page()) { 
echo 'id="single"';
} elseif (is_category()) { 
echo 'id="single"';
} elseif (is_archive()) { 
echo 'id="single"';
} elseif (is_404()) { 
echo 'id="single"';
} elseif (is_search()) { 
echo 'id="single"'; 
} 
} 
?>