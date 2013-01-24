<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<!--[if lte IE 7]><style media="screen,projection" type="text/css">@import "<?php bloginfo('stylesheet_directory'); ?>/style-ie.css";</style><![endif]-->
	<!--[if IE 6]>
		<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/DD_belatedPNG_0.0.7a-min.js"></script>
		<script type="text/javascript">
		  DD_belatedPNG.fix('#header_social img');
		</script>
	<![endif]-->

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>


	<!-- Main Menu -->
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.min.1.2.6.js"></script>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jqueryslidemenu/jqueryslidemenu.js"></script>
	<!-- /Main Menu -->
	<script type="text/javascript" src="<?=bloginfo('template_url')?>/js/tabs/tabcontent.js"></script>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page">

	<div id="mainmenu">
		<ul>
			<li class="first <? if(is_home()) echo 'current_page_item'; ?>"><a href="<?php echo get_option('home'); ?>/">Home</a></li>
			<?php $exclude = obwp_get_meta(SHORTNAME.'_exclude_page'); wp_list_pages2('title_li=&sort_column=menu_order&depth=0&exclude='.$exclude) ?>
		</ul>
	</div>
	<div id="header">
		<div id="logo"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a><span><?php bloginfo('description'); ?></span></div>
        
        <!-- replace it with your own banner -->
		<div id="header_banner">
			<a href=""><img src="<?=bloginfo('template_url')?>/images/banner.jpg" /></a>
		</div>
	</div>
	<div id="toppanel">
		<div id="header_social">
			<ul>
				<?php $twitter_id = obwp_get_meta(SHORTNAME.'_twitter_id'); if(!empty($twitter_id)) : ?>
				<li><a href="http://twitter.com/<?php echo $twitter_id; ?>/"><img src="<?=bloginfo('template_url')?>/images/button_twitter.png" alt="Twitter" /></a></li>
				<? endif; ?>
				<?php $technocrati_id = obwp_get_meta(SHORTNAME.'_technocrati_id'); if(!empty($technocrati_id)) : ?>
				<li><a href="<?php echo $technocrati_id; ?>"><img src="<?=bloginfo('template_url')?>/images/button_technocrati.png" alt="Technocrati" /></a></li>
				<? endif; ?>
				<?php $stuble_id = obwp_get_meta(SHORTNAME."_stuble_id"); if(!empty($stuble_id)) : ?>
				<li><a href="<?php echo $stuble_id; ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/button_stuble.png" alt="stuble" /></a></li>
				<?php endif; ?>
				<li><a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/button_rss.png" alt="rss" /></a></li>
				<?php $reddit_id = obwp_get_meta(SHORTNAME.'_reddit_id'); if(!empty($reddit_id)) : ?>
				<li><a href="<?php echo $reddit_id; ?>"><img src="<?=bloginfo('template_url')?>/images/button_reddit.png" alt="Reddit" /></a></li>
				<? endif; ?>
				<?php $flickr_id = obwp_get_meta(SHORTNAME."_flickr_id"); if(!empty($flickr_id)) : ?>
				<li><a href="<?php echo $flickr_id; ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/button_flickr.png" alt="flickr" /></a></li>
				<?php endif; ?>
				<?php $digg_id = obwp_get_meta(SHORTNAME.'_digg_id'); if(!empty($digg_id)) : ?>
				<li><a href="<?php echo $digg_id; ?>"><img src="<?=bloginfo('template_url')?>/images/button_digg.png" alt="Digg" /></a></li>
				<? endif; ?>
				<?php $youtube_id = obwp_get_meta(SHORTNAME."_youtube_id"); if(!empty($youtube_id)) : ?>
				<li><a href="<?php echo $youtube_id; ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/button_youtube.png" alt="youtube" /></a></li>
				<?php endif; ?>
				<?php $facebook_id = obwp_get_meta(SHORTNAME."_facebook_id"); if(!empty($facebook_id)) : ?>
				<li><a href="<?php echo $facebook_id; ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/button_facebook.png" alt="facebook" /></a></li>
				<?php endif; ?>
			</ul>
			<p>Join Us on Social Networks! </p>
		</div>
		
	</div>
	<div id="body">
		<div id="body_top">
			<div id="body_end">