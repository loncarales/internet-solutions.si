<?
/**
 * @package WordPress
 * @subpackage magazine_obsession
 */

/**
 * Show the General Settings for Admin oanel
 *
 * @since 2.7.0
 *
 */
function obwp_general_settings()
{
    global $themename, $options;

	$options = array (
				array(	"name" => "Ads Settings",
						"type" => "heading"),
						
				array(	"name" => "Show 125x125 ads",
						"desc" => "<br />",
			    		"id" => SHORTNAME."_show_banner_125_125",
			    		"options" => array('Yes','No'),
			    		"std" => "Yes",
			    		"type" => "select"),
						
				array(	"name" => "No. of Ads",
						"desc" => "Enter count of 125x125 banner blocks.<br />",
			    		"id" => SHORTNAME."_count_banner_125_125",
			    		"std" => "",
			    		"type" => "text"),
						
				array(	"name" => "Show 468x60 in header",
						"desc" => "",
			    		"id" => SHORTNAME."_show_ad_header_468_60",
			    		"options" => array('Yes','No'),
			    		"std" => "Yes",
			    		"type" => "select"),
						
				array(	"name" => "",
						"desc" => "",
			    		"id" => SHORTNAME."_ad_header_468_60",
			    		"std" => "",
			    		"type" => "textarea"),
				
				array(	"name" => "General Settings",
						"type" => "heading"),
						
				array(	"name" => "Twitter ID",
						"desc" => "Enter twitter id.<br />",
			    		"id" => SHORTNAME."_twitter_id",
			    		"std" => "",
			    		"type" => "text"),
						
				array(	"name" => "Technocrati URL",
						"desc" => "Enter Technocrati URL.<br />",
			    		"id" => SHORTNAME."_technocrati_id",
			    		"std" => "",
			    		"type" => "text"),
						
				array(	"name" => "Stuble URL",
						"desc" => "Enter stuble URL.<br />",
			    		"id" => SHORTNAME."_stuble_id",
			    		"std" => "",
			    		"type" => "text"),
				
				array(	"name" => "Reddit URL",
						"desc" => "Enter Reddit URL.<br />",
			    		"id" => SHORTNAME."_reddit_id",
			    		"std" => "",
			    		"type" => "text"),
						
				array(	"name" => "Flickr URL",
						"desc" => "Enter flickr URL.<br />",
			    		"id" => SHORTNAME."_flickr_id",
			    		"std" => "",
			    		"type" => "text"),
				
				array(	"name" => "Digg URL",
						"desc" => "Enter digg URL.<br />",
			    		"id" => SHORTNAME."_digg_id",
			    		"std" => "",
			    		"type" => "text"),
						
				array(	"name" => "Youtube URL",
						"desc" => "Enter youtube URL.<br />",
			    		"id" => SHORTNAME."_youtube_id",
			    		"std" => "",
			    		"type" => "text"),		
						
				array(	"name" => "Facebook URL",
						"desc" => "Enter facebook url.<br />",
			    		"id" => SHORTNAME."_facebook_id",
			    		"std" => "",
			    		"type" => "text"),
						
				array(	"name" => "Google Adsense ID",
						"desc" => "Enter google adnsense id. Example: pub-################. Enter pub- too.<br />",
			    		"id" => SHORTNAME."_google_id",
			    		"std" => "",
			    		"type" => "text"),
																														
		  );
	
	obwp_add_admin('obwp-settings.php');
}



?>