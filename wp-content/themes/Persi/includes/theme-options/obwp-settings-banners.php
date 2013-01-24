<?
/**
 * @package WordPress
 * @subpackage magazine_obsession
 */

/**
 * Show the Banners Settings for Admin oanel
 *
 * @since 2.7.0
 *
 */
function obwp_banners_settings()
{
    global $themename, $options;

	$options = array (
				array(	"name" => "125x125 Ad Settings",
						"type" => "heading")																														
		  );
	
	$count = obwp_get_meta(SHORTNAME.'_count_banner_125_125');
	
	if($count>0)
	{
		for($i=1; $i<=$count; $i++)
		{
			$options[] = array(	"name" => "125x125 Ad #".$i,
							"type" => "heading");
			
			$options[] = array(	"name" => "URL",
							"desc" => "Enter url of 125x125 banners. Do not forget to add http://<br /><br />",
							"id" => SHORTNAME."_banner_125_125_url_".$i,
							"std" => "",
							"type" => "text");
			
			$options[] = array(	"name" => "Image",
							"desc" => "Enter image of 125x125 banners. Do not forget to add http://<br /><br />",
							"id" => SHORTNAME."_banner_125_125_img_".$i,
							"std" => "",
							"type" => "text");
			
			$options[] = array(	"name" => "Title",
							"desc" => "Enter title of 125x125 banners.<br /><br />",
							"id" => SHORTNAME."_banner_125_125_title_".$i,
							"std" => "",
							"type" => "text");
				
			$options[] = array(
			    		"html" => '<br />',
			    		"type" => "html_tags");
		}
		
		obwp_add_admin('obwp-settings-banners.php');
	}
	else
	{
		?><p>Go to General Page and setup count of banners.</p><?php
	}
	
}


?>