<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title>
<?php if ( is_home() ) { ?><? bloginfo('name'); ?> | <?php bloginfo('description'); ?><?php } ?>
<?php if ( is_404() ) { ?><? bloginfo('name'); ?> | Nothing Found<?php } ?>
<?php if ( is_search() ) { ?><? bloginfo('name'); ?> | Search Results for <?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; _e(''); echo $key; _e(' &mdash; '); echo $count . ' '; _e('articles'); wp_reset_query(); ?>
<?php } ?>
<?php if ( is_author() ) { ?><? bloginfo('name'); ?> | Author Archives<?php } ?>
<?php if ( is_single() ) { ?><?php wp_title(''); ?> | <?php
$category = get_the_category();
echo $category[0]->cat_name;
?> | <? bloginfo('name'); ?><?php } ?>
<?php if ( is_page() ) { ?><? bloginfo('name'); ?> | <?php wp_title(''); ?><?php } ?>
<?php if ( is_category() ) { ?><? bloginfo('name'); ?> | Archive | <?php single_cat_title(); ?><?php } ?>
<?php if ( is_month() ) { ?><? bloginfo('name'); ?> | Archive | <?php the_time('F'); ?><?php } ?>
<?php if ( is_day() ) { ?><? bloginfo('name'); ?> | Archive | <?php the_time('F'); ?><?php } ?>
<?php if ( is_year() ) { ?><? bloginfo('name'); ?> | Archive | <?php the_time('F'); ?><?php } ?>
<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><? bloginfo('name'); ?>
 | <?php  single_tag_title("", true); } } ?>
 </title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="shortcut icon" type="image/ico" href="<?php bloginfo('template_url'); ?>/images/favicon.png" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/mobi.css" media="handheld" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/print.css" media="print" />
<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
<![endif]-->
<!--[if IE 6]>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/mobi.css" type="text/css" title="default" media="screen" />
<![endif]-->

<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>

<?php if ( (is_home())  ) { ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/glide.js"></script>
<?php } ?>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/cufon-yui.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/DIN_500.font.js"></script>
<script type="text/javascript">
Cufon.set('fontFamily', 'DIN');
Cufon.replace('h1' , { fontWeight: '400', textShadow: '#fff 1px 1px', hover: true });
Cufon.replace('h2' , { fontWeight: '400', hover: true });
Cufon.replace('h3', { fontWeight: '400', hover: true });
Cufon.replace('h4', { fontWeight: '400', hover: true });
Cufon.replace('h5', { fontWeight: '400', hover: true });
Cufon.replace('.navigation a', { fontWeight: '400', hover: true });
Cufon.replace('.drop', { fontWeight: '400', hover: true });
Cufon.replace('.read a', { fontWeight: '400', hover: true });
</script>
    
<script type="text/javascript"><!--//--><![CDATA[//><!--
sfHover = function() {
	if (!document.getElementsByTagName) return false;
	var sfEls = document.getElementById("nav").getElementsByTagName("li");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
//--><!]]></script>

<?php if ( (is_single())  ) { ?>
<script type="text/javascript">
$(function() {
	$(window).scroll(function(){
		/* when reaching the element with id "last" we want to show the slidebox. Let's get the distance from the top to the element */
		var distanceTop = $('#last').offset().top - $(window).height();		
		if  ($(window).scrollTop() > distanceTop)
			$('#slidebox').animate({'right':'0px'},300);
		else 
			$('#slidebox').stop(true).animate({'right':'-430px'},100);	
	});	
	/* remove the slidebox when clicking the cross */
	$('#slidebox .close').bind('click',function(){
		$(this).parent().remove();
	});
});
</script>
<?php } ?>

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>
<body <?php milo_body_control(); ?>>
<div id="rapper">
<div id="wrapper">
<div id="wrap">

<div id="header">
<div class="head">
<h1><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h1>	

</div>
<?php include (TEMPLATEPATH . '/inc/nav.php'); ?>
</div>