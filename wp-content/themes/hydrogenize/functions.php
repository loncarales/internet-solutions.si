<?php

define('PADD_THEME_NAME','Hydrogenize');
define('PADD_THEME_VERS','1.0');
define('PADD_THEME_SLUG','hydrogenize');
define('PADD_GALL_THUMB_W',645);
define('PADD_GALL_THUMB_H',240);
define('PADD_LIST_THUMB_W',240);
define('PADD_LIST_THUMB_H',160);
define('PADD_YTUBE_W',250);
define('PADD_YTUBE_H',238);
define('PADD_THEME_FWVER','2.0');

define('PADD_THEME_PATH',get_theme_root() . DIRECTORY_SEPARATOR . PADD_THEME_SLUG);
define('PADD_FUNCT_PATH',PADD_THEME_PATH . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR);

automatic_feed_links();
remove_action('wp_head','wp_generator');

register_nav_menus(array('main' => 'Main Menu',));

if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Sidebar',
		'before_widget' => '<div id="%1$s" class="padd-box %2$s">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3>',
		'after_title' => '</h3><div class="padd-interior">',
	));
}

$_PADD_BGS = array(
	'blue' => 'Blue',
	'brown' => 'Brown',
	'cyan' => 'Cyan',
	'green' => 'Green',
	'red' => 'Red',
);

require PADD_FUNCT_PATH . 'library.php';

require PADD_FUNCT_PATH . 'classes' . DIRECTORY_SEPARATOR . 'socialnetwork.php';
require PADD_FUNCT_PATH . 'classes' . DIRECTORY_SEPARATOR . 'advertisement.php';
require PADD_FUNCT_PATH . 'classes' . DIRECTORY_SEPARATOR . 'widgets.php';
require PADD_FUNCT_PATH . 'classes' . DIRECTORY_SEPARATOR . 'twitter.php';
require PADD_FUNCT_PATH . 'classes' . DIRECTORY_SEPARATOR . 'pagination.php';
require PADD_FUNCT_PATH . 'classes' . DIRECTORY_SEPARATOR . 'input' . DIRECTORY_SEPARATOR . 'input-option.php';
require PADD_FUNCT_PATH . 'classes' . DIRECTORY_SEPARATOR . 'input' . DIRECTORY_SEPARATOR . 'input-socialnetwork.php';
require PADD_FUNCT_PATH . 'classes' . DIRECTORY_SEPARATOR . 'input' . DIRECTORY_SEPARATOR . 'input-advertisement.php';

require PADD_FUNCT_PATH . 'defaults.php';

require PADD_FUNCT_PATH . 'administration' . DIRECTORY_SEPARATOR . 'options-functions.php';
require PADD_FUNCT_PATH . 'administration' . DIRECTORY_SEPARATOR . 'posting-functions.php';










