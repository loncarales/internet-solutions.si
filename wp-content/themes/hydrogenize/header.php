<?php require 'functions/required/template-top.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
<?php $scheme = get_option(PADD_THEME_SLUG . '_color_scheme','blue'); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/schemes/' . $scheme . '/style.css' ?>" type="text/css" media="screen" />
<!--[if IE]>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/css/ie.css' ?>" type="text/css" media="screen" />
<![endif]-->
<?php
$icon = get_option(PADD_THEME_SLUG . '_favicon_url','');
if (!empty($icon)) {
	echo '<link rel="shortcut icon" href="' . $icon . '" />' . "\n";
}
?>
<script type="text/javascript" src="<?php echo get_option('home'); ?>/wp-includes/js/jquery/jquery.js?ver=1.3.2"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.supersubs.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.superfish.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.s3slider.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/main.loading.js"></script>
<?php
$tracker = get_option(PADD_THEME_SLUG . '_tracker_head','');
if (!empty($tracker)) {
	echo stripslashes($tracker);
}
?>
</head>

<body>
<?php
$tracker = get_option(PADD_THEME_SLUG . '_tracker_top','');
if (!empty($tracker)) {
	echo stripslashes($tracker);
}
?>
<div id="padd-container">

	<div id="padd-header">
		<div id="padd-header-wrapper">		
			<div class="padd-box padd-box-title">
				<?php if (is_home()) : ?>
				<h1><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
				<?php else : ?>
				<p class="padd-title"><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></p>
				<?php endif; ?>
				<p class="padd-descr"><?php bloginfo('description'); ?></p>
			</div>
				
			<div class="padd-box padd-box-search">
				<h3>Search</h3>
				<div class="padd-interior">
					<?php padd_widget_funct_search_form(); ?>
				</div>
			</div>
			
			<div class="padd-box padd-box-menu">
				<h3>Main Menu</h3>
				<div class="padd-interior">
					<?php wp_nav_menu(array('theme_location' => 'main','menu_class' => 'padd-mainmenu')); ?>
				</div>
			</div>
		</div>
	</div>

	<?php if (is_home()) : ?>
	<div id="padd-featured">
		<div id="padd-featured-wrapper">	
			<div class="padd-box padd-box-featured">
				<h2>Featured Posts</h2>		
				<div class="padd-interior">
					<?php padd_theme_featured_posts(); ?>
				</div>
			</div>
			<div class="padd-box padd-box-welcome">
				<?php
					$padd_welcome = get_option(PADD_THEME_SLUG . '_post_welcome_page_id',1);
					$post = get_post($padd_welcome);
				?>
				<h2>Welcome to Our Site</h2>		
				<div class="padd-interior">
					<p><?php echo padd_theme_summary($post->post_content,210); ?></p>
				</div>
			</div>
			<div class="padd-box padd-box-socialnet">
				<h2>Subscribe To Us</h2>		
				<div class="padd-interior">
					<p>Subscribe to get the latest updates from us.</p>
					<?php padd_widget_funct_socialnet(); ?>
				</div>
			</div>
		</div>
		
		<div class="padd-clear"></div>
	</div>
	
	<?php endif; ?>

	<div id="padd-body" class="<?php echo is_home() ? 'padd-body-home' : 'padd-body-rest'; ?>">
		<div id="padd-body-wrapper">
