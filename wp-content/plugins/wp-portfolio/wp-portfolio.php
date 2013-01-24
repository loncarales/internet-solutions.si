<?php
/**
 * Plugin Name: Wordpress Portfolio Plugin
 * Plugin URI: http://wordpress.org/extend/plugins/wp-portfolio/
 * Description: A plugin that allows you to show off your portfolio through a single page on your wordpress blog with automatically generated thumbnails. To show your portfolio, create a new page and paste [wp-portfolio] into it. The plugin requires you to have a free account with <a href="http://www.shrinktheweb.com/">Shrink The Web</a> to generate the thumbnails.
 * Version: 1.16
 * Author: Dan Harrison
 * Author URI: http://www.wpdoctors.co.uk 
 */
/**
 * This plugin is licensed under the Apache 2 License
 * http://www.apache.org/licenses/LICENSE-2.0
 */

/* Libaries */
include_once('wplib/utils_formbuilder.inc.php');
include_once('wplib/utils_tablebuilder.inc.php');
include_once('wplib/utils_sql.inc.php');
include_once('lib/thumbnailer.inc.php');



/** Constant: The string used to determine when to render a group name. */
define('WPP_STR_GROUP_NAME', 					'%GROUP_NAME%');

/** Constant: The string used to determine when to render a group description. */
define('WPP_STR_GROUP_DESCRIPTION', 	 		'%GROUP_DESCRIPTION%');

/** Constant: The string used to determine when to render a website name. */
define('WPP_STR_WEBSITE_NAME', 	 				'%WEBSITE_NAME%');

/** Constant: The string used to determine when to render a website thumbnail image. */
define('WPP_STR_WEBSITE_THUMBNAIL', 	 		'%WEBSITE_THUMBNAIL%');

/** Constant: The string used to determine when to render a website thumbnail image URL. */
define('WPP_STR_WEBSITE_THUMBNAIL_URL', 	 	'%WEBSITE_THUMBNAIL_URL%');

/** Constant: The string used to determine when to render a website url. */
define('WPP_STR_WEBSITE_URL', 	 				'%WEBSITE_URL%');

/** Constant: The string used to determine when to render a website description. */
define('WPP_STR_WEBSITE_DESCRIPTION', 	 		'%WEBSITE_DESCRIPTION%');

/** Constant: Default HTML to render a group. */
define('WPP_DEFAULT_GROUP_TEMPLATE', 			
"<h2>%GROUP_NAME%</h2>
<p>%GROUP_DESCRIPTION%</p>");

/** Constant: Default HTML to render a website. */
define('WPP_DEFAULT_WEBSITE_TEMPLATE', 			
"<div class=\"portfolio-website\">
    <div class=\"website-thumbnail\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_THUMBNAIL%</a></div>
    <div class=\"website-name\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_NAME%</a></div>
    <div class=\"website-url\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_URL%</a></div>
    <div class=\"website-description\">%WEBSITE_DESCRIPTION%</div>
    <div class=\"website-clear\"></div>
</div>");

/** Constant: Default CSS to style the portfolio. */
define('WPP_DEFAULT_CSS',"
.portfolio-website {
	padding: 10px;
	margin-bottom: 10px;
}
.website-thumbnail {
	float: left;
	margin: 0 20px 20px 0;
}
.website-thumbnail img {
	border: 1px solid #555;
	margin: 0;
	padding: 0;
}
.website-name {
	font-size: 12pt;
	font-weight: bold;
	margin-bottom: 3px;
}
.website-name a,.website-url a {
	text-decoration: none;
}
.website-name a:hover,.website-url a:hover {
	text-decoration: underline;
}
.website-url {
	font-size: 9pt;
	font-weight: bold;
}
.website-url a {
	color: #777;
}
.website-description {
	margin-top: 15px;
}
.website-clear {
	clear: both;
}");

/** Constant: Default CSS to style the paging feature. */
define('WPP_DEFAULT_CSS_PAGING',"
.portfolio-paging {
	text-align: center;
	padding: 4px 10px 4px 10px;
	margin: 0 10px 20px 10px;
}
.portfolio-paging .page-count {
	margin-bottom: 5px;
}
.portfolio-paging .page-jump b {
	padding: 5px;
}
.portfolio-paging .page-jump a {
	text-decoration: none;
}");


/** Constant: The name of the table to store the website information. */
define('TABLE_WEBSITES', 						'WPPortfolio_websites');

/** Constant: The name of the table to store the website information. */
define('TABLE_WEBSITE_GROUPS', 					'WPPortfolio_groups');

/** Constant: The name of the table to store the debug information. */
define('TABLE_WEBSITE_DEBUG', 					'WPPortfolio_debuglog');

/** Contstant: The path to use to store the cached thumbnails. */
define('WPP_THUMBNAIL_PATH',					'wp-portfolio/cache');

/** Contstant: The path to use to store the cached thumbnails. */
define('WPP_THUMB_DEFAULTS',					'wp-portfolio/thumbnail_blank_');

/** Constant: URL location for settings page. */
define('WPP_SETTINGS', 							'admin.php?page=WPP_show_settings');

/** Constant: URL location for settings page. */
define('WPP_DOCUMENTATION', 					'admin.php?page=WPP_show_documentation');

/** Constant: URL location for website summary. */
define('WPP_WEBSITE_SUMMARY', 					'admin.php?page=wp-portfolio/wp-portfolio.php');

/** Constant: URL location for modifying a website entry. */
define('WPP_MODIFY_WEBSITE', 					'admin.php?page=WPP_modify_website');

/** Constant: URL location for showing the list of groups in the portfolio. */
define('WPP_GROUP_SUMMARY', 					'admin.php?page=WPP_website_groups');

/** Constant: URL location for modifying a group entry. */
define('WPP_MODIFY_GROUP', 						'admin.php?page=WPP_modify_group');

/** Constant: The current version of the database needed by this version of the plugin.  */
define('WPP_VERSION', 							'1.16');


/**
 * Function: WPPortfolio_menu()
 *
 * Creates the menu with all of the configuration settings.
 */
add_action('admin_menu', 'WPPortfolio_menu');
function WPPortfolio_menu()
{
	add_menu_page('WP Portfolio - Summary of Websites in your Portfolio', 'WP Portfolio', 'manage_options', __FILE__, 'WPPortfolio_show_websites');
	
	add_submenu_page(__FILE__, 'WP Portfolio - Modify Website', 	'Add New Website', 		'manage_options', 'WPP_modify_website', 'WPPortfolio_modify_website');
	add_submenu_page(__FILE__, 'WP Portfolio - Modify Group', 		'Add New Group', 		'manage_options', 'WPP_modify_group', 'WPPortfolio_modify_group');
	add_submenu_page(__FILE__, 'WP Portfolio - Groups', 			'Website Groups', 		'manage_options', 'WPP_website_groups', 'WPPortfolio_show_website_groups');		
	add_submenu_page(__FILE__, 'WP Portfolio - General Settings', 	'Portfolio Settings', 	'manage_options', 'WPP_show_settings', 'WPPortfolio_showSettingsPage');
	add_submenu_page(__FILE__, 'WP Portfolio - Layout Settings', 	'Layout Settings', 		'manage_options', 'WPP_show_layout_settings', 'WPPortfolio_showLayoutSettingsPage');
	add_submenu_page(__FILE__, 'WP Portfolio - Documentation', 		'Documentation', 		'manage_options', 'WPP_show_documentation', 'WPPortfolio_showDocumentationPage');
	
	if (get_option('WPPortfolio_setting_enable_debug') == 'on') {
		add_submenu_page(__FILE__, 'WP Portfolio - Debug Logs', 	'Debug Logs', 			'manage_options', 'WPP_show_debug_page', 'WPPortfolio_showDebugPage');
	}
}


/**
 * Determine if we're on a page just related to WP Portfolio in the admin area.
 * @return Boolean True if we're on a WP Portfolio admin page, false otherwise.
 */
function WPPortfolio_areWeOnWPPPage()
{
	if (isset($_GET) && isset($_GET['page']))
	{ 
		$currentPage = $_GET['page'];
		
		// This handles any WPPortfolio page.
		if ($currentPage == 'wp-portfolio/wp-portfolio.php' || substr($currentPage, 0, 4) == 'WPP_') {
			return true;
		}	
	}
	 
	return false;
}


/**
 * Show the main settings page.
 */
function WPPortfolio_showSettingsPage() {	
?>
	<div class="wrap">
	<div id="icon-options-general" class="icon32">
	<br/>
	</div>
	<h2>WP Portfolio - General Settings</h2>
<?php 	

	$settingsList = array(
		'setting_stw_access_key' 	=> false,
		'setting_stw_secret_key' 	=> false,
		'setting_stw_thumb_size' 	=> false,
		'setting_cache_days'	 	=> false,
		'setting_fetch_method' 		=> false,
		'setting_show_credit' 		=> false,	
		'setting_enable_debug'		=> false,
		'setting_scale_type'		=> 'scale-both',
	);
	
	// Get all the options from the database for the form
	$settings = array();
	foreach ($settingsList as $settingName => $settingDefault) {
		$settings[$settingName] = stripslashes(get_option('WPPortfolio_'.$settingName)); 
	}
		
	// If we don't have the version in the settings, we're not installed
	if (!get_option('WPPortfolio_version')) {
		WPPortfolio_showMessage("No WP Portfolio settings were found, so it appears that the plugin has been uninstalled. Please <b>deactivate</b> and then <b>activate</b> the WP Portfolio plugin again to fix this.", true);
		return false;
	}
	
		
	// Uninstall plugin?
	if (WPPortfolio_getArrayValue($_GET, 'uninstall') == "yes")
	{
		if ($_GET['confirm'] == "yes") {
			WPPortfolio_uninstall();
		}
		else {
			WPPortfolio_showMessage('Are you sure you want to delete all WP Portfolio settings and data? This action cannot be undone! </strong><br/><br/><a href="'.WPP_SETTINGS.'&uninstall=yes&confirm=yes">Yes, delete.</a> &nbsp; <a href="'.WPP_SETTINGS.'">NO!</a>');
		}
		return false;
	} // end if ($_GET['uninstall'] == "yes")	
	
	// Check if clearing cache 
	else if ( isset($_POST) && isset($_POST['clear_thumb_cache']) ) 
	{
		$actualThumbPath = WPPortfolio_getThumbPathActualDir();
		
		// Delete all contents of directory but not the root
		WPPortfolio_unlinkRecursive($actualThumbPath, false);
				
		WPPortfolio_showMessage("Thumbnail cache has now been emptied.");		
	}
	
	// Check if updated data.
	else if ( isset($_POST) && isset($_POST['update']) )
	{
		// Copy settings from $_POST
		$settings = array();
		foreach ($settingsList as $settingName => $settingDefault) {
			$settings[$settingName] = $_POST[$settingName];			
		}		
		
		// Validate keys
		if (WPPortfolio_isValidKey($settings['setting_stw_access_key']) && 
			WPPortfolio_isValidKey($settings['setting_stw_secret_key']))
		{		
			// Save settings
			foreach ($settingsList as $settingName => $settingDefault) {
				update_option('WPPortfolio_'.$settingName, $settings[$settingName]); 
			}
								
			WPPortfolio_showMessage();		
		}
		else {
			WPPortfolio_showMessage("The keys must only contain letters and numbers. Please check that they are correct.", true);
		}
	}	
	
	
	$form = new FormBuilder();
	
	$formElem = new FormElement("setting_stw_access_key", "STW Access Key ID");
	$formElem->value = $settings['setting_stw_access_key'];
	$formElem->description = "The <a href=\"".WPP_DOCUMENTATION."#doc-stw\">Shrink The Web</a> Access Key ID is around 15 characters.";
	$form->addFormElement($formElem);
	
	$formElem = new FormElement("setting_stw_secret_key", "STW Secret Key");
	$formElem->value = $settings['setting_stw_secret_key'];
	$formElem->description = "The <a href=\"".WPP_DOCUMENTATION."#doc-stw\">Shrink The Web</a> Secret Key is around 5-10 characters. This key is never shared,<br>it is only stored in your settings and used to generate thumbnails for this website.";
	$form->addFormElement($formElem);
	
	// Thumbnail sizes
	$thumbsizes = array ("sm" => "Small (120 x 90)",
						 "lg" => "Large (200 x 150)",
						 "xlg" => "Extra Large (320 x 240)");
	
	$formElem = new FormElement("setting_stw_thumb_size", "Thumbnail Size");
	$formElem->value = $settings['setting_stw_thumb_size'];
	$formElem->setTypeAsComboBox($thumbsizes);
	$form->addFormElement($formElem);
	
	// Cache days
	$cachedays = array ( "3" => "3 days",
						 "5" => "5 days",
						 "7" => "7 days",
						 "10" => "10 days",
						 "15" => "15 days",
						 "20" => "20 days",
						 "30" => "30 days",
						 "0" => "Never Expire Thumbnails"
						);
	
	$formElem = new FormElement("setting_cache_days", "Number of Days to Cache Thumbnail");
	$formElem->value = $settings['setting_cache_days'];
	$formElem->setTypeAsComboBox($cachedays);
	$formElem->description = "The number of days to hold thumbnails in the cache. Set to a longer time period if website homepages don't change very often";
	$form->addFormElement($formElem);	
	
	// Thumbnail Fetch Method
	$fetchmethod = array( "curl" => "cURL (recommended)",
						  "fopen" => "fopen");
	
	$formElem = new FormElement("setting_fetch_method", "Thumbnail Fetch Method");
	$formElem->value = $settings['setting_fetch_method'];
	$formElem->setTypeAsComboBox($fetchmethod);
	$formElem->description = "The type of HTTP call used to fetch thumbnails. fopen is usually less secure and disabled by most web hosts, hence why cURL is recommended.";
	$form->addFormElement($formElem);		
	
	// Custom Thumbnail Scale Method
	$scalemethod = array( "scale-height" => "Match height of website thumbnails",
						  "scale-width" => "Match width of website thumbnails",
						  "scale-both" => "Ensure thumbnail is same size or smaller than website thumbnails (default)");
	
	$formElem = new FormElement("setting_scale_type", "Custom Thumbnail Scale Method");
	$formElem->value = $settings['setting_scale_type'];
	$formElem->setTypeAsComboBox($scalemethod);
	$formElem->description = "How custom thumbnails are scaled to match the size of other website thumbnails. This is mostly a matter of style. The thumbnails can match either:<br/>
							  &nbsp;&nbsp;&nbsp;&nbsp;a) <strong>the height</strong> of the website thumbnails (with the width resized to keep the scale of the original image)<br/>
							  &nbsp;&nbsp;&nbsp;&nbsp;b) <strong>the width</strong> of the website thumbnails  (with the height resized to keep the scale of the original image)<br/>
							  &nbsp;&nbsp;&nbsp;&nbsp;c) <strong>the width and height</strong> of the website thumbnails, where the custom thumbnail is never larger than a website thumbnail, but still scaled correctly.<br/>
							  After changing this option, it's recommended to clear the cache so that all custom thumbnails are sized correctly.
							  ";
	$form->addFormElement($formElem);
	
	// Debug mode
	$formElem = new FormElement("setting_enable_debug", "Enable Debug Mode");
	$formElem->value = $settings['setting_enable_debug'];
	$formElem->setTypeAsCheckbox("Enable debug logging");
	$formElem->description = "Enables logging of all thumbnail requests to help locate problems.";
	$form->addFormElement($formElem);		
	
	// Show credit link
	$formElem = new FormElement("setting_show_credit", "Show Credit Link");
	$formElem->value = $settings['setting_show_credit'];
	$formElem->setTypeAsCheckbox("Creates a link back to WP Portfolio and to WPDoctors.co.uk");
	$formElem->description = "<strong>I've worked hard on this plugin, please consider keeping the link back to my website!</strong> It's the link back to my site that keeps this plugin free!";
	$form->addFormElement($formElem);	
			
	$form->addButton("clear_thumb_cache", "Clear Thumbnail Cache");
	
	echo $form->toString();
	?>
	
		
	<h2>Uninstalling WP Portfolio</h2>
	<p>If you're going to permanently uninstall WP Portfolio, you can also <a href="admin.php?page=WPP_show_settings&uninstall=yes">remove all settings and data</a>.</p>
		
	<p>&nbsp;</p>	
	<p>&nbsp;</p>
	</div>
	<?php 	
}




/**
 * Show all the documentation in one place.
 */
function WPPortfolio_showDocumentationPage() 
{

	
	?>
	<div class="wrap">
	<div id="icon-options-general" class="icon32">
	<br/>
	</div>
	
	
	<h2>WP Portfolio - Documentation</h2>	
	
	<p>All the information you need to run the plugin is available on this page.</p>	
	
	<h2>Problems and Support</h2>
	<p>Please check the <a href="http://wordpress.org/extend/plugins/wp-portfolio/faq/">Frequently Asked Questions</a> page if you have any issues. As a last resort, 
	please raise a problem in the <a href="http://wordpress.org/tags/wp-portfolio?forum_id=10">WP Portfolio Support Forum on Wordpress.org</a>, and I'll respond to the ticket as soon as 
	possible. Please be aware, this might be a couple of days.</p>
	
	<h2>Comments and Feedback</h2>
	<p>If you have any comments, ideas or any other feedback on this plugin, please leave comments on the <a href="http://wordpress.org/tags/wp-portfolio?forum_id=10">WP Portfolio Support Forum on Wordpress.org</a>
	or contact me directly via the <a href="http://www.wpdoctors.co.uk/contact/">WP Doctors Contact Page</a>. We're always wanting testimonials too, so if you'd like to give us a 2-3 sentence testimonial, please
 	contact us via the <a href="http://www.wpdoctors.co.uk/contact/">WP Doctors Contact Page</a>.</p>
	
	<h2>Requesting Features</h2>
	<p>My schedule is extremely busy, and so I have little time to add new features to this plugin. If you are keen for a feature to be implemented, I can add new 
	features in return for a small fee which helps cover my time. Due to running an agency, so my clients are my first priority. By paying a small fee, you effectively 
	become a client, and therefore I can implement desired features more quickly. Please contact me via the <a href="http://www.wpdoctors.co.uk/contact/">WP Doctors Contact Page</a> if 
	you would like to pay to have a new feature implemented. 
	
	<p>You can see the list of requested features on the <a href="http://www.wpdoctors.co.uk/our-wordpress-plugins/wp-portfolio/">WP Portfolio page</a> on the <a href="http://www.wpdoctors.co.uk">WP Doctors</a> website. If you are prepared to wait, I do welcome feature ideas, which can be left on the <a href="http://wordpress.org/tags/wp-portfolio?forum_id=10">WP Portfolio Support Forum on Wordpress.org</a>.</p>	
	
	<a name="doc-stw"></a>
	<h2>Shrink The Web - Thumbnail Service</h2>
	<p>In order to use this plugin, you'll need to create an account on <a href="http://www.shrinktheweb.com" target="_blank">Shrink The Web (STW)</a>. This is a free service that gives you a number of free thumbnails per month. The WP Portfolio plugin employs the use of caching for efficiency and to reduce the number of times the STW service is accessed. You can check your thumbnail usage on the STW website at any time.</p>
	
	
	<h2>Portfolio Syntax</h2>
	<p>You can use the following syntax for wp-portfolio within any post or page.</p>
	
	<h3>Website Groups</h3>	
	<ul class="wp-group-syntax">
		<li>To show all groups, use <tt><b>[wp-portfolio]</b></tt></li>
		<li>To show just the group with an ID of 1, use <tt><b>[wp-portfolio groups="1"]</b></tt></li>
		<li>To show groups with IDs of 1, 2 and 4, use <tt><b>[wp-portfolio groups="1,2,4"]</b></tt></li>
	</ul>
	
	<h3>Paging (Showing a portfolio on several pages)</h3>	
	<ul class="wp-group-syntax">
		<li>To show all websites without any paging, just use <tt><b>[wp-portfolio]</b></tt> as normal</li>
		<li>To show 3 websites per page, use <tt><b>[wp-portfolio sitesperpage="3"]</b></tt></li>
		<li>To show 5 websites per page, use <tt><b>[wp-portfolio sitesperpage="5"]</b></tt></li>
	</ul>
	
	<h3>Ordering By Date</h3>
	<ul class="wp-group-syntax">
		<li>To order websites by the date they were added, showing newest first (so descending order) use <tt><b>[wp-portfolio ordertype="dateadded" orderby="desc" ]</b></tt>. Group names are automatically hidden when ordering by date.</li>
		<li>To order websites by the date they were added, showing oldest first (so ascending order) use <tt><b>[wp-portfolio ordertype="dateadded" orderby="asc" ]</b></tt>. Group names are automatically hidden when ordering by date.</li>
	</ul>	
	
	<h3>Miscellaneous Options</h3>
	<ul class="wp-group-syntax">
		<li>To hide the title/description of all groups shown in a portfolio for just a single post/page without affecting other posts/pages, just use <tt><b>[wp-portfolio hidegroupinfo="1"]</b></tt></li>
		<li>To show the portfolio in reverse order, just use <tt><b>[wp-portfolio orderby="desc"]</b></tt> (desc = is short for descending order)</li>
	</ul>	
	
		
	
	<h2>Uninstalling WP Portfolio</h2>
	<p>If you're going to permanently uninstall WP Portfolio, you can also <a href="admin.php?page=WPP_show_settings&uninstall=yes">remove all settings and data</a>.</p>
		
					
	<a name="doc-layout"></a>
	<h2>Portfolio Layout Templates</h2>	
	
	<p>The default templates for the groups and websites below as a reference.</p>
	<ul style="margin-left: 30px; list-style-type: disc;">
		<li><strong><?php echo WPP_STR_GROUP_NAME; ?></strong> - Replace with the group name.</li>
		<li><strong><?php echo WPP_STR_GROUP_DESCRIPTION; ?></strong> - Replace with the group description.</li>
		<li><strong><?php echo WPP_STR_WEBSITE_NAME; ?></strong> - Replace with the website name.</li>
		<li><strong><?php echo WPP_STR_WEBSITE_URL; ?></strong> - Replace with the website url.</li>
		<li><strong><?php echo WPP_STR_WEBSITE_DESCRIPTION; ?></strong> - Replace with the website description.</li>
		<li><strong><?php echo WPP_STR_WEBSITE_THUMBNAIL; ?></strong> - Replace with the website thumbnail including the &lt;img&gt; tag.</li>
		<li><strong><?php echo WPP_STR_WEBSITE_THUMBNAIL_URL; ?></strong> - Replace with the website thumbnail URL (no HTML).</li>
	</ul>
	
	<form>
	<table class="form-table">
		<tr class="form-field">
			<th scope="row"><label for="default_template_group">Group Template</label></th>
			<td>
				<textarea name="default_template_group" rows="3"><?php echo htmlentities(WPP_DEFAULT_GROUP_TEMPLATE); ?></textarea>
			</td>
		</tr>		
		<tr class="form-field">
			<th scope="row"><label for="default_template_website">Website Template</label></th>
			<td>
				<textarea name="default_template_website" rows="8"><?php echo htmlentities(WPP_DEFAULT_WEBSITE_TEMPLATE); ?></textarea>
			</td>
		</tr>			
		<tr class="form-field">
			<th scope="row"><label for="default_template_css">Template CSS</label></th>
			<td>
				<textarea name="default_template_css" rows="8"><?php echo htmlentities(WPP_DEFAULT_CSS); ?></textarea>
			</td>
		</tr>					
		<tr class="form-field">
			<th scope="row"><label for="default_template_css_paging">Paging CSS</label></th>
			<td>
				<textarea name="default_template_css_paging" rows="8"><?php echo htmlentities(WPP_DEFAULT_CSS_PAGING); ?></textarea>
			</td>
		</tr>		
	</table>
	</form>
	<p>&nbsp;</p>
		
	<h2>Showing the Portfolio from PHP</h2>
	<h3>WPPortfolio_getAllPortfolioAsHTML()</h3>
	<p>You can show all or a part of the portfolio from within code by using the <code>WPPortfolio_getAllPortfolioAsHTML($groups, $template_website, $template_group, $sitesperpage, $showAscending, $orderBy)</code> function.</p>
	
	<p><b>Parameters</b></p>
	<ul class="wp-group-syntax">
		<li><b>$groups</b> - The comma separated list of groups to include. To show all groups, specify <code>false</code> for <code>$groups</code>. (<b>default</b> is <code>false</code>)</li>
		<li><b>$template_website</b> - The HTML template to use for rendering a single website (using the <a href="<?php echo WPP_DOCUMENTATION ?>#doc-layout">template tags above</a>). Specify <code>false</code> to use the website template stored in the settings. (<b>default</b> is <code>false</code>, i.e. use template stored in settings.)</li>
		<li><b>$template_group</b> - The HTML template to use for rendering a group description (using the <a href="<?php echo WPP_DOCUMENTATION ?>#doc-layout">template tags above</a>). Specify <code>false</code> to use the group template stored in the settings. To hide the group description, specify a single space character for <code>$template_group</code>. (<b>default</b> is <code>false</code>, i.e. use template stored in settings.)</li>
		<li><b>$sitesperpage</b> - The number of websites to show per page, set to <code>false</code> or <code>0</code> if you don't want to use paging.  (<b>default</b> is <code>false</code>, i.e. don't do any paging.)</li>
		<li><b>$showAscending</b> - If <code>true</code>, show the websites in ascending order. If <code>false</code>, show the websites in reverse order. (<b>default</b> is <code>true</code>, i.e. ascending ordering.)</li>
		<li><b>$orderBy</b> - Determine how to order the websites. (<b>default</b> is <code>'normal'</code>, i.e. normal ordering.)<ul>
			<li>If <code>'normal'</code>, show the websites in normal group order. </li>
			<li>If <code>'dateadded'</code>, show the websites ordered by date. If this mode is chosen, group names are automatically hidden.</li>
			</ul>
		</li>
	</ul>	
	
	<p>&nbsp;</p>	
	
	<p><b>Example 1 (using website template stored in settings):</b></p> 	
	<pre>
&lt;?php 
if (function_exists('WPPortfolio_getAllPortfolioAsHTML')) {
	echo WPPortfolio_getAllPortfolioAsHTML('1,3');
}
?&gt;
	</pre>
	
	<p><b>Example 2 (with custom templates):</b></p>
	<pre>
&lt;?php 
if (function_exists('WPPortfolio_getAllPortfolioAsHTML'))
{
	$website_template = '
		&lt;div class=&quot;portfolio-website&quot;&gt;
		&lt;div class=&quot;website-thumbnail&quot;&gt;&lt;a href=&quot;%WEBSITE_URL%&quot; target=&quot;_blank&quot;&gt;%WEBSITE_THUMBNAIL%&lt;/a&gt;&lt;/div&gt;
		&lt;div class=&quot;website-name&quot;&gt;&lt;a href=&quot;%WEBSITE_URL%&quot; target=&quot;_blank&quot;&gt;%WEBSITE_NAME%&lt;/a&gt;&lt;/div&gt;
		&lt;div class=&quot;website-description&quot;&gt;%WEBSITE_DESCRIPTION%&lt;/div&gt;
		&lt;div class=&quot;website-clear&quot;&gt;&lt;/div&gt;
		&lt;/div&gt;';
		
	$group_template = '
		&lt;h2&gt;%GROUP_NAME%&lt;/h2&gt;
		&lt;p&gt;%GROUP_DESCRIPTION%&lt;/p&gt;';	
	
	echo WPPortfolio_getAllPortfolioAsHTML('1,2', $website_template, $group_template);
}
?&gt;
	</pre>		
	
	<p><b>Example 3 (using stored templates, but showing 3 websites per page):</b></p> 	
	<pre>
&lt;?php 
if (function_exists('WPPortfolio_getAllPortfolioAsHTML')) {
	echo WPPortfolio_getAllPortfolioAsHTML('1,3', false, false, '3');
}
?&gt;
	</pre>	
	
	<p><b>Example 4 (using stored templates, but showing 4 websites per page, ordering by date, with the newest website first):</b></p> 	
	<pre>
&lt;?php 
if (function_exists('WPPortfolio_getAllPortfolioAsHTML')) {
	echo WPPortfolio_getAllPortfolioAsHTML('1,3', false, false, '3', false, 'dateadded');
}
?&gt;
	</pre>	
			
		
	<p>&nbsp;</p>		
	
	<h3>WPPortfolio_getRandomPortfolioSelectionAsHTML()</h3>
	<p>You can show a random selection of your portfolio from within code by using the <code>WPPortfolio_getRandomPortfolioSelectionAsHTML($groups, $count, $template_website)</code> function. Please note that there is no group information shown when this function is used.</p>
	
	<p><b>Parameters</b></p>
	<ul class="wp-group-syntax">
		<li><b>$groups</b> - The comma separated list of groups to make a random selection from. To choose from all groups, specify <code>false</code> for <code>$groups</code> (<b>default</b> is <code>false</code>).</li>
		<li><b>$count</b> - The number of websites to show in the random selection. (<b>default</b> is <code>3</code>)</li>
		<li><b>$template_website</b> - The HTML template to use for rendering a single website (using the <a href="<?php echo WPP_DOCUMENTATION ?>#doc-layout">template tags above</a>). Specify <code>false</code> to use the website template stored in the settings. (<b>default</b> is <code>false</code>, i.e. use template stored in settings.)</li>
	</ul>
	
	<p>&nbsp;</p>	
	
	<p><b>Example 1 (using website template stored in settings):</b></p> 	
	<pre>
&lt;?php 
if (function_exists('WPPortfolio_getRandomPortfolioSelectionAsHTML')) {
	echo WPPortfolio_getRandomPortfolioSelectionAsHTML('1,4', 4);
}
?&gt;
	</pre>
	
	<p><b>Example 2 (with custom template):</b></p>
	<pre>
&lt;?php 
if (function_exists('WPPortfolio_getRandomPortfolioSelectionAsHTML')) {
	$website_template = '
		&lt;div class=&quot;portfolio-website&quot;&gt;
		&lt;div class=&quot;website-thumbnail&quot;&gt;&lt;a href=&quot;%WEBSITE_URL%&quot; target=&quot;_blank&quot;&gt;%WEBSITE_THUMBNAIL%&lt;/a&gt;&lt;/div&gt;
		&lt;div class=&quot;website-name&quot;&gt;&lt;a href=&quot;%WEBSITE_URL%&quot; target=&quot;_blank&quot;&gt;%WEBSITE_NAME%&lt;/a&gt;&lt;/div&gt;
		&lt;div class=&quot;website-clear&quot;&gt;&lt;/div&gt;
		&lt;/div&gt;';
	echo WPPortfolio_getRandomPortfolioSelectionAsHTML('1,4', 4, $website_template);
}
?&gt;
	</pre>
		

	<p>&nbsp;</p>	
	<p>&nbsp;</p>
</div>
	
	<?php
}

/**
 * Show only the settings relating to layout of the portfolio.
 */
function WPPortfolio_showLayoutSettingsPage() 
{
?>
	<div class="wrap">
	<div id="icon-themes" class="icon32">
	<br/>
	</div>
	<h2>WP Portfolio - Layout Settings</h2>
<?php 	

	// Get all the options from the database for the form
	$setting_template_website    = stripslashes(get_option('WPPortfolio_setting_template_website'));
	$setting_template_group      = stripslashes(get_option('WPPortfolio_setting_template_group'));	
	$setting_template_css        = stripslashes(get_option('WPPortfolio_setting_template_css'));
	$setting_template_css_paging = stripslashes(get_option('WPPortfolio_setting_template_css_paging'));
	
	
	// If we don't have the version in the settings, we're not installed
	if (!get_option('WPPortfolio_version')) {
		WPPortfolio_showMessage("No WP Portfolio settings were found, so it appears that the plugin has been uninstalled. Please <b>deactivate</b> and then <b>activate</b> the WP Portfolio plugin again to fix this.", true);
		return false;
	}
	
			
	// Check if updated data.
	if ( isset($_POST) && isset($_POST['update']) )
	{
		$setting_template_website 		= trim($_POST['setting_template_website']);
		$setting_template_group   		= trim($_POST['setting_template_group']);
		$setting_template_css     		= trim($_POST['setting_template_css']);
		$setting_template_css_paging    = trim($_POST['setting_template_css_paging']);
		
		update_option('WPPortfolio_setting_template_website', 		$setting_template_website);
		update_option('WPPortfolio_setting_template_group',   		$setting_template_group);			
		update_option('WPPortfolio_setting_template_css',     		$setting_template_css);
		update_option('WPPortfolio_setting_template_css_paging',    $setting_template_css_paging);
				
		WPPortfolio_showMessage();		
	}	
	
	
	$form = new FormBuilder();	
	
	$formElem = new FormElement("setting_template_website", "Website HTML Template");				
	$formElem->value = htmlentities(stripslashes($setting_template_website));
	$formElem->description = '&bull; This is the template used to render each of the websites.<br/>
							  &bull; A complete list of tags is available in the <a href="'.WPP_DOCUMENTATION.'#doc-layout">Portfolio Layout Templates</a> section in the documentation.';
	$formElem->setTypeAsTextArea(8, 70); 
	$form->addFormElement($formElem);
	
	$formElem = new FormElement("setting_template_group", "Group HTML Template");				
	$formElem->value = htmlentities(stripslashes($setting_template_group));
	$formElem->description = '&bull; This is the template used to render each of the groups that the websites belong to.<br/>
							  &bull; A complete list of tags is available in the <a href="'.WPP_DOCUMENTATION.'#doc-layout">Portfolio Layout Templates</a> section in the documentation.';
	$formElem->setTypeAsTextArea(3, 70); 
	$form->addFormElement($formElem);	
	
	$formElem = new FormElement("setting_template_css", "Template CSS");				
	$formElem->value = htmlentities(stripslashes($setting_template_css));
	$formElem->description = '&bull; This is the CSS code used to style the portfolio.<br/>
							  &bull; <strong>Advanced Tip:</strong> Once you\'re happy with the style, you should really move the CSS into your template <tt>style.css</tt>. This is so that visitor browsers can cache the stylesheet and reduce loading times. Any CSS placed here will 
							  be injected into the template &lt;head&gt; tag, which is not the most efficient method of delivering CSS.';
	$formElem->setTypeAsTextArea(10, 70); 
	$form->addFormElement($formElem);	

	$formElem = new FormElement("setting_template_css_paging", "Paging CSS");				
	$formElem->value = htmlentities(stripslashes($setting_template_css_paging));
	$formElem->description = '&bull; This is the CSS code used to style the paging area if you are showing your portfolio on several pages.<br/>
							  &bull; <strong>Advanced Tip:</strong> Once you\'re happy with the style, you should really move the CSS into your template <tt>style.css</tt>. This is so that visitor browsers can cache the stylesheet and reduce loading times. Any CSS placed here will 
							  be injected into the template &lt;head&gt; tag, which is not the most efficient method of delivering CSS.';
	$formElem->setTypeAsTextArea(6, 70); 
	$form->addFormElement($formElem);	
	
	
	echo $form->toString();
	
	?>	

	

</div>
<?php 
}

/**
 * Page that shows a list of websites in your portfolio.
 */
function WPPortfolio_show_websites()
{
?>
<div class="wrap">
	<div id="icon-themes" class="icon32">
	<br/>
	</div>
	<h2>Summary of Websites in your Portfolio</h2>
	<br>
<?php 		

    // See if a group parameter was specified, if so, use that to show websites
    // in just that group
    $groupid = false;
    if (isset($_GET['groupid'])) {
    	$groupid = $_GET['groupid'] + 0;
    }
    
	$siteid = 0;
	if (isset($_GET['siteid'])) {
		$siteid = (is_numeric($_GET['siteid']) ? $_GET['siteid'] + 0 : 0);
	}	    

	global $wpdb;
	$websites_table = $wpdb->prefix . TABLE_WEBSITES;
	$groups_table   = $wpdb->prefix . TABLE_WEBSITE_GROUPS;

	
	// ### DELETE Check if we're deleting a website
	if ($siteid > 0 && isset($_GET['delete']))
	{
		$websitedetails = WPPortfolio_getWebsiteDetails($siteid);
		
		if (isset($_GET['confirm']))
		{
			$delete_website = "DELETE FROM $websites_table WHERE siteid = '".$wpdb->escape($siteid)."' LIMIT 1";
			if ($wpdb->query( $delete_website )) {
				WPPortfolio_showMessage("Website was successfully deleted.");
			}
			else {
				WPPortfolio_showMessage("Sorry, but an unknown error occured whist trying to delete the selected website from the portfolio.", true);
			}
		}
		else
		{
			$message = 'Are you sure you want to delete "'.$websitedetails['sitename'].'" from your portfolio?<br/><br/><a href="'.WPP_WEBSITE_SUMMARY.'&delete=yes&confirm=yes&siteid='.$websitedetails['siteid'].'">Yes, delete.</a> &nbsp; <a href="'.WPP_WEBSITE_SUMMARY.'">NO!</a>';
			WPPortfolio_showMessage($message);
			return;
		}
	}		
	

	// Determine if showing only 1 group
	$WHERE_CLAUSE = false;
	if ($groupid > 0) {
		$WHERE_CLAUSE = "WHERE $groups_table.groupid = '$groupid'";
	}
	
	// Default sort method
	$sorting = "grouporder, groupname, siteorder, sitename";
	
	// Work out how to sort
	if (isset($_GET['sortby'])) {
		$sortby = strtolower($_GET['sortby']);
		
		switch ($sortby) {
			case 'sitename':
				$sorting = "sitename ASC";
				break;
			case 'siteurl':
				$sorting = "siteurl ASC";
				break;			
			case 'siteadded':
				$sorting = "siteadded DESC, sitename ASC";
				break;
		}
	}		
	
	// Get website details, merge with group details
	$SQL = "SELECT *, UNIX_TIMESTAMP(siteadded) as dateadded FROM $websites_table
			LEFT JOIN $groups_table ON $websites_table.sitegroup = $groups_table.groupid
			$WHERE_CLAUSE
			ORDER BY $sorting	 		
	 		";	
		
	
	$wpdb->show_errors();
	$websites = $wpdb->get_results($SQL, OBJECT);	
			
	// Only show table if there are websites to show
	if ($websites)
	{
		$baseSortURL = WPP_WEBSITE_SUMMARY;
		if ($groupid > 0) {
			$baseSortURL .= "&groupid=".$groupid;
		}
		
		?>
		<div class="websitecount">
			<?php
				// If just showing 1 group
				if ($groupid > 0) {
					echo sprintf('Showing <strong>%s</strong> websites in the \'%s\' group (<a href="%s" class="showall">or Show All</a>). To only show the websites in this group, use <code>[wp-portfolio groups="%s"]</code>', $wpdb->num_rows, $websites[0]->groupname, WPP_WEBSITE_SUMMARY, $groupid); 
				} else {
					echo sprintf('Showing <strong>%s</strong> websites in the portfolio.', $wpdb->num_rows);
				}							
			?>
			
		
		</div>
		
		<div class="subsubsub">
			<strong>Sort by:</strong>
			<a href="<?php echo $baseSortURL; ?>" title="Sort websites in the order you'll see them within your portfolio.">Normal Ordering</a>
			|
			<a href="<?php echo $baseSortURL; ?>&sortby=sitename" title="Sort the websites by name.">Name</a>
			|
			<a href="<?php echo $baseSortURL; ?>&sortby=siteurl" title="Sort the websites by URL.">URL</a>
			|
			<a href="<?php echo $baseSortURL; ?>&sortby=siteadded" title="Sort the websites by the date that the websites were added.">Date Added</a>
		</div>
		<br/>
		<?php 
		
		$table = new TableBuilder();
		$table->attributes = array("id" => "wpptable");

		$column = new TableColumn("ID", "id");
		$column->cellClass = "wpp-id";
		$table->addColumn($column);
		
		$column = new TableColumn("Thumbnail", "thumbnail");
		$column->cellClass = "wpp-thumbnail";
		$table->addColumn($column);
		
		$column = new TableColumn("Site Name", "sitename");
		$column->cellClass = "wpp-name";
		$table->addColumn($column);
		
		$column = new TableColumn("URL", "siteurl");
		$column->cellClass = "wpp-url";
		$table->addColumn($column);
		
		$column = new TableColumn("Date Added", "dateadded");
		$column->cellClass = "wpp-date-added";
		$table->addColumn($column);

		$column = new TableColumn("Custom Thumbnail?", "customthumb");
		$column->cellClass = "wpp-customurl";
		$table->addColumn($column);						
		
		$column = new TableColumn("Visible?", "siteactive");
		$column->cellClass = "wpp-small";
		$table->addColumn($column);

		$column = new TableColumn("Ordering", "siteorder");
		$column->cellClass = "wpp-small";
		$table->addColumn($column);
		
		$column = new TableColumn("Group", "group");
		$column->cellClass = "wpp-small";
		$table->addColumn($column);
					
		$column = new TableColumn("Action", "action");
		$column->cellClass = "wpp-small action-links";
		$column->headerClass = "action-links";		
		$table->addColumn($column);							
			
		foreach ($websites as $websitedetails)
		{
			// First part of a link to visit a website
			$websiteClickable = '<a href="'.$websitedetails->siteurl.'" target="_new" title="Visit the website \''.stripslashes($websitedetails->sitename).'\'">';
			$editClickable    = '<a href="'.WPP_MODIFY_WEBSITE.'&editmode=edit&siteid='.$websitedetails->siteid.'" title="Edit \''.stripslashes($websitedetails->sitename).'\'">';
			
			$rowdata = array();
			$rowdata['id'] 			= $websitedetails->siteid;			
			$rowdata['dateadded']	= date('D jS M Y \a\t H:i', $websitedetails->dateadded);
			
			$rowdata['sitename'] 	= stripslashes($websitedetails->sitename);			
			$rowdata['siteurl'] 	= $websiteClickable.$websitedetails->siteurl.'</a>';
			
			// Custom URL will typically not be specified, so show n/a for clarity.
			if ($websitedetails->customthumb)
			{
				// Use custom thumbnail rather than screenshot
				$rowdata['thumbnail'] 	= '<img src="'.WPPortfolio_getAdjustedCustomThumbnail($websitedetails->customthumb, "sm").'" />';
				
				$rowdata['customthumb'] = 'Yes - <a href="'.$websitedetails->customthumb.'" target="_new" title="Open custom thumbnail in a new window">View Image</a>';
			} 
			// Not using custom thumbnail
			else 
			{
				$rowdata['thumbnail'] 	= sprintf('<img src="%s" width="120" height="90"  />', 
													WPPortfolio_getThumbnail($websitedetails->siteurl, "sm", ($websitedetails->specificpage == 1))
												);
				$rowdata['customthumb'] = '-';
			}
			
			$rowdata['siteorder']   = $websitedetails->siteorder; 
			$rowdata['siteactive']  = ($websitedetails->siteactive ? 'Yes' : '-'); 
			$rowdata['group'] 		= '<a href="'.WPP_WEBSITE_SUMMARY.'&groupid='.$websitedetails->groupid.'" title="Show websites only in the \''.stripslashes($websitedetails->groupname).'\' group">'.stripslashes($websitedetails->groupname).'</a>';
			$rowdata['action'] 		= '<a href="'.WPP_WEBSITE_SUMMARY.'&delete=yes&siteid='.$websitedetails->siteid.'">Delete</a>&nbsp;|&nbsp;' .
									  $editClickable.'Edit</a>';
		
			$table->addRow($rowdata);
		}
		
		// Finally show table
		echo $table->toString();
		echo "<br/>";
		
	} // end of if websites
	else {
		WPPortfolio_showMessage("There are currently no websites in the portfolio.", true);
	}
	
	?>	
</div>
<?php 
	
}

/**
 * Show the debug logging summary page.
 */
function WPPortfolio_showDebugPage() 
{
	?>
	<div class="wrap">
	<div id="icon-tools" class="icon32">
	<br/>
	</div>
	<h2>Debug Log</h2>
	<br/>
	
	<?php 
		global $wpdb;
		$table_debug = $wpdb->prefix . TABLE_WEBSITE_DEBUG;	
		
		$SQL = "SELECT * 
				FROM $table_debug
				ORDER BY request_date DESC
				LIMIT 50
				";
		
		$wpdb->show_errors();
		$logMsgs = $wpdb->get_results($SQL, OBJECT);

		if ($logMsgs)
		{
			$table = new TableBuilder();
			$table->attributes = array("id" => "wpptable");
	
			$column = new TableColumn("ID", "id");
			$column->cellClass = "wpp-id";
			$table->addColumn($column);
			
			$column = new TableColumn("Requested URL", "request_url");
			$column->cellClass = "wpp-url";
			$table->addColumn($column);
			
			$column = new TableColumn("Type", "request_type");
			$column->cellClass = "wpp-type";
			$table->addColumn($column);
			
			$column = new TableColumn("Result", "request_result");
			$column->cellClass = "wpp-result";
			$table->addColumn($column);
			
			$column = new TableColumn("Request Date", "request_date");
			$column->cellClass = "wpp-request-date";
			$table->addColumn($column);
			
			$column = new TableColumn("Detail", "request_detail");
			$column->cellClass = "wpp-detail";
			$table->addColumn($column);

			
			foreach ($logMsgs as $logDetail)
			{
				$rowdata = array();
				$rowdata['id'] 				= $logDetail->logid;
				$rowdata['request_url'] 	= $logDetail->request_url;
				$rowdata['request_type'] 	= $logDetail->request_type;
				$rowdata['request_result'] 	= ($logDetail->request_result == 1 ? '<b>Success</b>' : 'Error');
				$rowdata['request_date'] 	= $logDetail->request_date;
				$rowdata['request_detail'] 	= $logDetail->request_detail;
				
				$table->addRow($rowdata);
			}
			
			// Finally show table
			echo $table->toString();
			echo "<br/>";
		}
		else {
			WPPortfolio_showMessage("There are currently no debug logs to show.", true);
		}
	
	?>
	
	</div><!-- end wrapper -->	
	<?php 
}



/**
 * Shows the page listing the available groups.
 */
function WPPortfolio_show_website_groups()
{
?>
<div class="wrap">
	<div id="icon-edit" class="icon32">
	<br/>
	</div>
	<h2>Website Groups</h2>
	<br/>

	<?php 
	global $wpdb;
	$groups_table = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$websites_table = $wpdb->prefix . TABLE_WEBSITES;
	
    // Get group ID
    $groupid = false;
    if (isset($_GET['groupid'])) {
    	$groupid = $_GET['groupid'] + 0;
    }	
	
	// ### DELETE ### Check if we're deleting a group
	if ($groupid > 0 && isset($_GET['delete'])) 
	{				
		// Now check that ID actually relates to a real group
		$groupdetails = WPPortfolio_getGroupDetails($groupid);
		
		// If group doesn't really exist, then stop.
		if (count($groupdetails) == 0) {
			WPPortfolio_showMessage('Sorry, but no group with that ID could be found. Please click <a href="'.WPP_GROUP_SUMMARY.'">here</a> to return to the list of groups.', true);
			return;
		}
		
		// Count the number of websites in this group and how many groups exist
		$website_count = $wpdb->get_var("SELECT COUNT(*) FROM $websites_table WHERE sitegroup = '".$wpdb->escape($groupdetails['groupid'])."'");
		$group_count   = $wpdb->get_var("SELECT COUNT(*) FROM $groups_table");
		
		$groupname = stripcslashes($groupdetails['groupname']);
		
		// Check that group doesn't have a load of websites assigned to it.
		if ($website_count > 0)  {
			WPPortfolio_showMessage("Sorry, the group '$groupname' still contains <b>$website_count</b> websites. Please ensure the group is empty before deleting it.");
			return;
		}
		
		// If we're deleting the last group, don't let it happen
		if ($group_count == 1)  {
			WPPortfolio_showMessage("Sorry, but there needs to be at least 1 group in the portfolio. Please add a new group before deleting '$groupname'.");
			return;
		}
		
		// OK, got this far, confirm we want to delete.
		if (isset($_GET['confirm']))
		{
			$delete_group = "DELETE FROM $groups_table WHERE groupid = '".$wpdb->escape($groupid)."' LIMIT 1";
			if ($wpdb->query( $delete_group )) {
				WPPortfolio_showMessage("Group was successfully deleted.");
			}
			else {
				WPPortfolio_showMessage("Sorry, but an unknown error occured whist trying to delete the selected group from the portfolio.", true);
			}
		}
		else
		{
			$message = 'Are you sure you want to delete the group "'.$groupname.'" from your portfolio?<br/><br/><a href="'.WPP_GROUP_SUMMARY.'&delete=yes&confirm=yes&groupid='.$groupid.'">Yes, delete.</a> &nbsp; <a href="'.WPP_GROUP_SUMMARY.'">NO!</a>';
			WPPortfolio_showMessage($message);
			return;
		}
	}	
	
	
	
	// Get website details, merge with group details
	$SQL = "SELECT * FROM $groups_table
	 		ORDER BY grouporder, groupname";	
	
	// DEBUG Uncomment if needed
	// $wpdb->show_errors();
	$groups = $wpdb->get_results($SQL, OBJECT);
		
	
	// Only show table if there are any results.
	if ($groups)
	{					
		$table = new TableBuilder();
		$table->attributes = array("id" => "wpptable");

		$column = new TableColumn("ID", "id");
		$column->cellClass = "wpp-id";
		$table->addColumn($column);		
		
		$column = new TableColumn("Name", "name");
		$column->cellClass = "wpp-name";
		$table->addColumn($column);	

		$column = new TableColumn("Description", "description");
		$table->addColumn($column);	

		$column = new TableColumn("# Websites", "websitecount");
		$column->cellClass = "wpp-small wpp-center";
		$table->addColumn($column);			
		
		$column = new TableColumn("Ordering", "ordering");
		$column->cellClass = "wpp-small wpp-center";
		$table->addColumn($column);		
		
		$column = new TableColumn("Action", "action");
		$column->cellClass = "wpp-small action-links";
		$column->headerClass = "action-links";
		$table->addColumn($column);		
		
		?>
		<p>The websites will be rendered in groups in the order shown in the table.</p>
		<?php 
		
		foreach ($groups as $groupdetails) 
		{
			$groupClickable = '<a href="'.WPP_WEBSITE_SUMMARY.'&groupid='.$groupdetails->groupid.'" title="Show websites only in the \''.$groupdetails->groupname.'\' group">';
			
			// Count websites in this group
			$website_count = $wpdb->get_var("SELECT COUNT(*) FROM $websites_table WHERE sitegroup = '".$wpdb->escape($groupdetails->groupid)."'");
			
			$rowdata = array();
			
			$rowdata['id']			 	= $groupdetails->groupid;
			$rowdata['name']		 	= $groupClickable.stripslashes($groupdetails->groupname).'</a>';
			$rowdata['description']	 	= stripslashes($groupdetails->groupdescription);
			$rowdata['websitecount'] 	= $groupClickable.$website_count.($website_count == 1 ? ' website' : ' websites')."</a>";
			$rowdata['ordering']	 	= $groupdetails->grouporder;
			$rowdata['action']		 	= '<a href="'.WPP_GROUP_SUMMARY.'&delete=yes&groupid='.$groupdetails->groupid.'">Delete</a>&nbsp;|&nbsp;' .
										  '<a href="'.WPP_MODIFY_GROUP.'&editmode=edit&groupid='.$groupdetails->groupid.'">Edit</a></td>';
			
			$table->addRow($rowdata);
		}
		
		
		// Finally show table
		echo $table->toString();
		echo "<br/>";		
		
	} // end of if groups
	
	// No groups to show
	else {
		WPPortfolio_showMessage("There are currently no groups in the portfolio.", true);
	}
	?>
</div>
<?php 
	
}


/**
 * Shows the page that allows the details of a website to be modified or added to the portfolio.
 */
function WPPortfolio_modify_website()
{
	// Determine if we're in edit mode. Ensure we get correct mode regardless of where it is.
	$editmode = false;
	if (isset($_POST['editmode'])) {
		$editmode = ($_POST['editmode'] == 'edit');
	} else if (isset($_GET['editmode'])) {
		$editmode = ($_GET['editmode'] == 'edit');
	}	
	
	// Get the site ID. Ensure we get ID regardless of where it is.
	$siteid = 0;
	if (isset($_POST['website_siteid'])) {
		$siteid = (is_numeric($_POST['website_siteid']) ? $_POST['website_siteid'] + 0 : 0);
	} else if (isset($_GET['siteid'])) {
		$siteid = (is_numeric($_GET['siteid']) ? $_GET['siteid'] + 0 : 0);
	}	
	
	// Work out page heading
	$verb = "Add New";
	if ($editmode) { 
		$verb = "Modify";
	}
	
	?>
	<div class="wrap">
	<div id="icon-themes" class="icon32">
	<br/>
	</div>
	<h2><?php echo $verb; ?> Website Details</h2>	
	<?php 	
		
	
	// Check id is a valid number if editing $editmode
	if ($editmode && $siteid == 0) {
		WPPortfolio_showMessage('Sorry, but no website with that ID could be found. Please click <a href="'.WPP_WEBSITE_SUMMARY.'">here</a> to return to the list of websites.', true);
		return;
	}	
	

	// If we're editing, try to get the website details.
	if ($editmode && $siteid > 0)
	{
		// Get details from the database
		$websitedetails = WPPortfolio_getWebsiteDetails($siteid);

		// False alarm, couldn't find it.
		if (count($websitedetails) == 0) {
			$editmode = false;
		}		
	} // end of editing check
	
	// Add Mode, so specify defaults
	else {
		$websitedetails['siteactive'] = 1;
	}
	
	
	// Check if website is being added, if so, add to the database.
	if ( isset($_POST) && isset($_POST['update']) )
	{
		// Grab specified details
		$data = array();
		$data['siteid'] 			= $_POST['website_siteid'];
		$data['sitename'] 			= trim(strip_tags($_POST['website_sitename']));
		$data['siteurl'] 			= trim(strip_tags($_POST['website_siteurl']));
		$data['sitedescription'] 	= $_POST['website_sitedescription'];
		$data['sitegroup'] 			= $_POST['website_sitegroup'];
		$data['customthumb']		= trim(strip_tags($_POST['website_customthumb']));
		$data['siteactive']			= trim(strip_tags($_POST['website_siteactive']));
		$data['siteorder']			= trim(strip_tags($_POST['website_siteorder'])) + 0;
		$data['specificpage']	    = trim(strip_tags($_POST['website_specificpage']));		
		
		// Keep track of errors for validation
		$errors = array();
				
		// Ensure all fields have been completed
		if (!($data['sitename'] && $data['siteurl'] && $data['sitedescription']) ) {
			array_push($errors, "Please check that you have completed the site name, url and description fields.");
		}	
		
		// Continue if there are no errors
		if (count($errors) == 0)
		{
			global $wpdb;
			$table_name = $wpdb->prefix . TABLE_WEBSITES;
			
			// Change query based on add or edit
			if ($editmode) {						
				$query = arrayToSQLUpdate($table_name, $data, 'siteid');
			}

			// Add
			else {
				unset($data['siteid']); // Don't need id for an insert
				$data['siteadded'] = date("m/d/y g:i A"); // Only used if adding a website.
					
				$query = arrayToSQLInsert($table_name, $data);	
			}			
						
			// Try to put the data into the database
			$wpdb->show_errors();
			$wpdb->query($query);
			
			// When adding, clean fields so that we don't show them again.
			if ($editmode) {
				WPPortfolio_showMessage("Website details successfully updated.");
				
				// Retrieve the details from the database again
				$websitedetails = WPPortfolio_getWebsiteDetails($siteid);				
			} 
			// When adding, empty the form again
			else
			{	
				WPPortfolio_showMessage("Website details successfully added.");
					
				$data['siteid'] 			= false;
				$data['sitename'] 			= false;
				$data['siteurl'] 			= false;
				$data['sitedescription'] 	= false;
				$data['sitegroup'] 			= false;
				$data['customthumb']		= false;				
				$data['siteactive']			= 1; // The default is that the website is visible.				
				$data['siteorder']			= 0;
				$data['specificpage']	    = 0; 
			}
								
		} // end of error checking
	
		// Handle error messages
		else
		{
			$message = "Sorry, but unfortunately there were some errors. Please fix the errors and try again.<br><br>";
			$message .= "<ul style=\"margin-left: 20px; list-style-type: square;\">";
			
			// Loop through all errors in the $error list
			foreach ($errors as $errormsg) {
				$message .= "<li>$errormsg</li>";
			}
						
			$message .= "</ul>";
			WPPortfolio_showMessage($message, true);
			$websitedetails = $data;
		}
	}
		
	$form = new FormBuilder();
		
	$formElem = new FormElement("website_sitename", "Website Name");				
	$formElem->value = WPPortfolio_getArrayValue($websitedetails, 'sitename');
	$formElem->description = "The proper name of the website. <em>(Required)</em>";
	$form->addFormElement($formElem);
	
	$formElem = new FormElement("website_siteurl", "Website URL");				
	$formElem->value = WPPortfolio_getArrayValue($websitedetails, 'siteurl');
	$formElem->description = "The URL for the website, including the leading <em>http://</em>. <em>(Required)</em>";
	$form->addFormElement($formElem);	
	
	$formElem = new FormElement("website_sitedescription", "Website Description");				
	$formElem->value = WPPortfolio_getArrayValue($websitedetails, 'sitedescription');
	$formElem->description = "The description of your website. HTML is permitted. <em>(Required)</em>";
	$formElem->setTypeAsTextArea(4, 70);
	$form->addFormElement($formElem);	
	
	global $wpdb;
	$table_name = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$SQL = "SELECT * FROM $table_name ORDER BY groupname";	
	$groups = $wpdb->get_results($SQL, OBJECT);	
	$grouplist = array();
	
	foreach ($groups as $group) {
		$grouplist[$group->groupid] =  stripslashes($group->groupname);
	}	
		
	$formElem = new FormElement("website_sitegroup", "Website Group");
	$formElem->setTypeAsComboBox($grouplist);				
	$formElem->value = WPPortfolio_getArrayValue($websitedetails, 'sitegroup');
	$formElem->description = "The group you want to assign this website to.";
	$form->addFormElement($formElem);	
	
	$form->addBreak('advanced-options', '<div id="wpp-hide-show-advanced" class="hide"><a href="#">Show Advanced Settings</a></div>');
	
	$formElem = new FormElement("website_customthumb", "Custom Thumbnail URL");				
	$formElem->value = WPPortfolio_getArrayValue($websitedetails, 'customthumb');
	$formElem->cssclass = "long-text";
	$formElem->description = "If specified, the URL of a custom thumbnail to use <em>instead</em> of the screenshot of the URL above. 							  
							  <br/>&bull; The image URL must include the leading <em>http://</em>. e.g. <em>http://www.yoursite.com/wp-content/uploads/yourfile.jpg</em>							  
							  <br/>&bull; Leave this field blank to use an automatically generated screenshot of the website specified above.
							  <br/>&bull; Custom thumbnails are automatically resized to match the size of the other thumbnails. 
							  ";
	$form->addFormElement($formElem);	

	$formElem = new FormElement("website_siteactive", "Show Website?");
	$formElem->setTypeAsComboBox(array('1' => 'Show Website', '0' => 'Hide Website'));				
	$formElem->value = WPPortfolio_getArrayValue($websitedetails, 'siteactive');
	$formElem->description = "By changing this option, you can show or hide a website from the portfolio.";
	$form->addFormElement($formElem);	
	
	$formElem = new FormElement("website_siteorder", "Website Ordering");				
	$formElem->value = WPPortfolio_getArrayValue($websitedetails, 'siteorder');
	$formElem->description = "&bull; The number to use for ordering the websites. Websites are rendered in ascending order, first by this order value (lowest value first), then by website name.<br/> 
				&bull; e.g. Websites (A, B, C, D) with ordering (50, 100, 0, 50) will be rendered as (C, A, D, B).<br/>  
				&bull; If all websites have 0 for ordering, then the websites are rendered in alphabetical order by name. ";
	$form->addFormElement($formElem);	
		
	
	// Advanced Features
	$formElem = new FormElement("website_specificpage", "Use Specific Page Capture<br/><b>(Advanced Feature)</b>");
	$formElem->setTypeAsComboBox(array('0' => 'No - Homepage Only', '1' => 'Yes - Show Specific Page'));				
	$formElem->value = WPPortfolio_getArrayValue($websitedetails, 'specificpage');
	$formElem->description = "&bull; <b>Requires Shrink The Web 'Specific Page Capture' Pro (paid) feature.</b><br/>
							  &bull; If enabled show internal web page rather than website's homepage. If in doubt, select <b>'No - Homepage Only'</b>.
							  ";
	$form->addFormElement($formElem);	
	
	// Hidden Elements
	$formElem = new FormElement("website_siteid", false);				
	$formElem->value = WPPortfolio_getArrayValue($websitedetails, 'siteid');
	$formElem->setTypeAsHidden();
	$form->addFormElement($formElem);
	
	$formElem = new FormElement("editmode", false);				
	$formElem->value = ($editmode ? "edit" : "add");
	$formElem->setTypeAsHidden();
	$form->addFormElement($formElem);	
	
	
	$form->setSubmitLabel(($editmode ? "Update" : "Add"). " Website Details");		
	echo $form->toString();
			
	?>	
	<br><br>
	</div><!-- wrap -->
	<?php 	
}


/**
 * Shows the page that allows a group to be modified.
 */
function WPPortfolio_modify_group()
{
	// Determine if we're in edit mode. Ensure we get correct mode regardless of where it is.
	$editmode = false;
	if (isset($_POST['editmode'])) {
		$editmode = ($_POST['editmode'] == 'edit');
	} else if (isset($_GET['editmode'])) {
		$editmode = ($_GET['editmode'] == 'edit');
	}	
	
	// Get the Group ID. Ensure we get ID regardless of where it is.
	$groupid = 0;
	if (isset($_POST['group_groupid'])) {
		$groupid = (is_numeric($_POST['group_groupid']) ? $_POST['group_groupid'] + 0 : 0);
	} else if (isset($_GET['groupid'])) {
		$groupid = (is_numeric($_GET['groupid']) ? $_GET['groupid'] + 0 : 0);
	}

	$verb = "Add New";
	if ($editmode) {
		$verb = "Modify";
	}
	
	// Show title to determine action
	?>
	<div class="wrap">
	<div id="icon-edit" class="icon32">
	<br/>
	</div>
	<h2><?php echo $verb; ?> Group Details</h2>
	<?php 
	
	// Check id is a valid number if editing $editmode
	if ($editmode && $groupid == 0) {
		WPPortfolio_showMessage('Sorry, but no group with that ID could be found. Please click <a href="'.WPP_GROUP_SUMMARY.'">here</a> to return to the list of groups.', true);
		return;
	}	
	$groupdetails = false;

	// ### EDIT ### Check if we're adding or editing a group
	if ($editmode && $groupid > 0)
	{
		// Get details from the database				
		$groupdetails = WPPortfolio_getGroupDetails($groupid);

		// False alarm, couldn't find it.
		if (count($groupdetails) == 0) {
			$editmode = false;
		}
		
	} // end of editing check
			
	// Check if group is being updated/added.
	if ( isset($_POST) && isset($_POST['update']) )
	{
		// Grab specified details
		$data = array();
		$data['groupid'] 			= $groupid;	
		$data['groupname'] 		  	= strip_tags($_POST['group_groupname']);
		$data['groupdescription'] 	= $_POST['group_groupdescription'];
		$data['grouporder'] 		= $_POST['group_grouporder'] + 0; // Add zero to convert to number
						
		// Keep track of errors for validation
		$errors = array();
				
		// Ensure all fields have been completed
		if (!($data['groupname'] && $data['groupdescription'])) {
			array_push($errors, "Please check that you have completed the group name and description fields.");
		}	
		
		// Continue if there are no errors
		if (count($errors) == 0)
		{
			global $wpdb;
			$table_name = $wpdb->prefix . TABLE_WEBSITE_GROUPS;

			// Change query based on add or edit
			if ($editmode) {							
				$query = arrayToSQLUpdate($table_name, $data, 'groupid');
			}

			// Add
			else {
				unset($data['groupid']); // Don't need id for an insert	
				$query = arrayToSQLInsert($table_name, $data);	
			}
			
			// Try to put the data into the database
			$wpdb->show_errors();
			$wpdb->query($query);
			
			// When editing, show what we've just been editing.
			if ($editmode) {
				WPPortfolio_showMessage("Group details successfully updated.");
				
				// Retrieve the details from the database again
				$groupdetails = WPPortfolio_getGroupDetails($groupid);
			} 
			// When adding, empty the form again
			else {																							
				WPPortfolio_showMessage("Group details successfully added.");
				
				$groupdetails['groupid'] 			= false;
				$groupdetails['groupname'] 			= false;
				$groupdetails['groupdescription'] 	= false;
				$groupdetails['grouporder'] 		= false;
			}

		} // end of error checking
	
		// Handle error messages
		else
		{
			$message = "Sorry, but unfortunately there were some errors. Please fix the errors and try again.<br><br>";
			$message .= "<ul style=\"margin-left: 20px; list-style-type: square;\">";
			
			// Loop through all errors in the $error list
			foreach ($errors as $errormsg) {
				$message .= "<li>$errormsg</li>";
			}
						
			$message .= "</ul>";
			WPPortfolio_showMessage($message, true);
			$groupdetails = $data;
		}
	}
	
	$form = new FormBuilder();
	
	$formElem = new FormElement("group_groupname", "Group Name");				
	$formElem->value = WPPortfolio_getArrayValue($groupdetails, 'groupname');
	$formElem->description = "The name for this group of websites.";
	$form->addFormElement($formElem);	
	
	$formElem = new FormElement("group_groupdescription", "Group Description");				
	$formElem->value = WPPortfolio_getArrayValue($groupdetails, 'groupdescription');
	$formElem->description = "The description of your group. HTML is permitted.";
	$formElem->setTypeAsTextArea(4, 70);
	$form->addFormElement($formElem);		
	
	$formElem = new FormElement("group_grouporder", "Group Order");				
	$formElem->value = WPPortfolio_getArrayValue($groupdetails, 'grouporder');
	$formElem->description = "&bull; The number to use for ordering the groups. Groups are rendered in ascending order, first by this order value (lowest value first), then by group name.<br/> 
				&bull; e.g. Groups (A, B, C, D) with ordering (50, 100, 0, 50) will be rendered as (C, A, D, B).  <br/>
				&bull; If all groups have 0 for ordering, then the groups are rendered in alphabetical order. ";
	$form->addFormElement($formElem);		
	
	// Hidden Elements
	$formElem = new FormElement("group_groupid", false);				
	$formElem->value = WPPortfolio_getArrayValue($groupdetails, 'groupid');
	$formElem->setTypeAsHidden();
	$form->addFormElement($formElem);
	
	$formElem = new FormElement("editmode", false);				
	$formElem->value = ($editmode ? "edit" : "add");
	$formElem->setTypeAsHidden();
	$form->addFormElement($formElem);	
	
	
	$form->setSubmitLabel(($editmode ? "Update" : "Add"). " Group Details");		
	echo $form->toString();	
		
	?>		
	<br><br>
	</div><!-- wrap -->
	<?php 	
}


/**
 * Install the WP Portfolio plugin, initialise the default settings, and create the tables for the websites and groups.
 */
WPPortfolio_install();
function WPPortfolio_install()
{
	global $wpdb;

	// ### Create Default Settings
	if (!get_option('WPPortfolio_setting_stw_access_key'))
		update_option('WPPortfolio_setting_stw_access_key', "");
	
	if (!get_option('WPPortfolio_setting_stw_secret_key'))
		update_option('WPPortfolio_setting_stw_secret_key', "");
	
	if (!get_option('WPPortfolio_setting_stw_thumb_size'))
		update_option('WPPortfolio_setting_stw_thumb_size', "lg");
				
	if (!get_option('WPPortfolio_setting_cache_days'))
		update_option('WPPortfolio_setting_cache_days', 7);

	if (!get_option('WPPortfolio_setting_template_website'))
		update_option('WPPortfolio_setting_template_website', WPP_DEFAULT_WEBSITE_TEMPLATE);		
		
	if (!get_option('WPPortfolio_setting_template_group'))
		update_option('WPPortfolio_setting_template_group', WPP_DEFAULT_GROUP_TEMPLATE);

	if (!get_option('WPPortfolio_setting_template_css'))
		update_option('WPPortfolio_setting_template_css', WPP_DEFAULT_CSS);	

	if (!get_option('WPPortfolio_setting_template_css_paging'))
		update_option('WPPortfolio_setting_template_css_paging', WPP_DEFAULT_CSS_PAGING);			
		
	if (!get_option('WPPortfolio_setting_fetch_method'))
		update_option('WPPortfolio_setting_fetch_method', "curl");	
		
	if (!get_option('WPPortfolio_setting_show_credit'))
		update_option('WPPortfolio_setting_show_credit', "on");
		
	if (!get_option('WPPortfolio_setting_scale_type')) 
		update_option('WPPortfolio_setting_scale_type', "scale-both");
			
		
	// Check the current version of the database
	$installed_ver  = get_option("WPPortfolio_version") + 0;
	$current_ver    = WPP_VERSION + 0;
	$upgrade_tables = ($current_ver > $installed_ver);
	
			
	// Table names
	$table_websites	= $wpdb->prefix . TABLE_WEBSITES;
	$table_groups 	= $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$table_debug    = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
				
	// Check tables exist
	$table_websites_exists	= ($wpdb->get_var("SHOW TABLES LIKE '$table_websites'") == $table_websites);
	$table_groups_exists	= ($wpdb->get_var("SHOW TABLES LIKE '$table_groups'") == $table_groups);
	$table_debug_exists		= ($wpdb->get_var("SHOW TABLES LIKE '$table_debug'") == $table_debug);
	
	// Only enable if debugging	
	//$wpdb->show_errors();

	// #### Create Tables - Websites
	if (!$table_websites_exists || $upgrade_tables) 
	{
		$sql = "CREATE TABLE `$table_websites` (
  				   siteid INT(10) unsigned NOT NULL auto_increment,
				   sitename varchar(150),
				   siteurl varchar(255),
				   sitedescription TEXT,
				   sitegroup int(10) unsigned NOT NULL,
				   customthumb varchar(255),
				   siteactive TINYINT NOT NULL DEFAULT '1',
				   siteorder int(10) unsigned NOT NULL DEFAULT '0',
				   specificpage TINYINT NOT NULL DEFAULT '0',	
				   siteadded datetime default NULL,
				   PRIMARY KEY  (siteid) 
				) ";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		// Set default date if there isn't one
		$results = $wpdb->query("UPDATE `$table_websites` SET `siteadded` = NOW() WHERE `siteadded` IS NULL OR `siteadded` = '0000-00-00 00:00:00'");
	}
	
	// #### Create Tables - Groups
	if (!$table_groups_exists || $upgrade_tables)
	{
		$sql = "CREATE TABLE `$table_groups` (
  				   groupid int(10) UNSIGNED NOT NULL auto_increment,
				   groupname varchar(150),
				   groupdescription TEXT,
				   grouporder INT(1) UNSIGNED NOT NULL DEFAULT '0',
				   PRIMARY KEY  (groupid)
				) ";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		// Creating new table? Add default group that has ID of 0
		if (!$table_groups_exists)
		{
			$SQL = "INSERT INTO `$table_groups` (groupid, groupname, groupdescription) VALUES (1, 'My Websites', 'These are all my websites.')";
	 		$results = $wpdb->query($SQL);
		}
	}	
	
	
	// #### Create Tables - Debug Log
	if (!$table_debug_exists || $upgrade_tables) 
	{
		$sql = "CREATE TABLE `$table_debug` (
				  logid bigint(20) unsigned NOT NULL auto_increment,
				  request_url varchar(255) NOT NULL,
				  request_result tinyint(4) NOT NULL default '0',
				  request_detail text NOT NULL,
				  request_type varchar(25) NOT NULL,
				  request_date datetime NOT NULL,
  				  PRIMARY KEY  (logid)
				) ";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
		
	// Update the version regardless
	update_option("WPPortfolio_version", WPP_VERSION);
	
	// Create cache directory
	WPPortfolio_createCacheDirectory(); 
}
register_activation_hook(__FILE__,'WPPortfolio_install');


/**
 * Add the custom stylesheet for this plugin.
 */
function WPPortfolio_stylesheet()
{
	// Only show our stylesheet on a WP Portfolio page to avoid breaking other plugins.
	if (WPPortfolio_areWeOnWPPPage()) {
    	$url = WPPortfolio_getPluginPath() . 'portfolio.css';
    	echo "\n".'<link rel="stylesheet" type="text/css" href="' . $url . '" />'."\n";
	}
}
add_action('admin_head', 'WPPortfolio_stylesheet');


/** 
 * Add the scripts needed for the page for this plugin.
 */
function WPPortfolio_configurePageScripts()
{
	if (!WPPortfolio_areWeOnWPPPage()) 
		return;
		
	wp_enqueue_script('jquery');
	
	// Plugin-specific JS
	wp_enqueue_script('wpl-admin-js', WPPortfolio_getPluginPath() .  'js/wpp-admin.js', array('jquery'), '0.1' );
}
add_action('admin_print_scripts', 'WPPortfolio_configurePageScripts');


/**
 * Get the URL for the plugin path including a trailing slash.
 * @return String The URL for the plugin path.
 */
function WPPortfolio_getPluginPath() {
	return trailingslashit(trailingslashit(WP_PLUGIN_URL) . plugin_basename(dirname(__FILE__)));
}


/**
 * Method called when we want to uninstall the portfolio plugin to remove the database tables.
 */
function WPPortfolio_uninstall() 
{
	// Remove all options from the database
	delete_option('WPPortfolio_setting_stw_access_key');
	delete_option('WPPortfolio_setting_stw_secret_key');	
	delete_option('WPPortfolio_setting_stw_thumb_size');
	delete_option('WPPortfolio_setting_cache_days');
	
	delete_option('WPPortfolio_setting_template_website');
	delete_option('WPPortfolio_setting_template_group');
	delete_option('WPPortfolio_setting_template_css');
	delete_option('WPPortfolio_setting_template_css_paging');
			
	delete_option('WPPortfolio_version');
		
	
	// Remove all tables for the portfolio
	global $wpdb;
	$table_name    = $wpdb->prefix . TABLE_WEBSITES;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);
	
	$table_name    = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);
		
	$table_name    = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);
	
	
	// Remove cache
	$actualThumbPath = WPPortfolio_getThumbPathActualDir();
	WPPortfolio_unlinkRecursive($actualThumbPath);		
		
	WPPortfolio_showMessage("Deleted WP Portfolio database entries.");
}




/**
 * This method is called just before the <head> tag is closed. We inject our custom CSS into the 
 * webpage here.
 */
function WPPortfolio_renderCSS() 
{
	// Only render CSS if there's some to show.
	$setting_template_css = trim(stripslashes(get_option('WPPortfolio_setting_template_css')));
	$setting_template_css_paging = trim(stripslashes(get_option('WPPortfolio_setting_template_css_paging')));
	
	if ($setting_template_css || $setting_template_css_paging) {
		echo "\n<!-- WP Portfolio Stylesheet -->\n";
		echo "<style type=\"text/css\">\n";
		
		echo $setting_template_css;
		echo $setting_template_css_paging;
		
		echo "\n</style>";
		echo "\n<!-- WP Portfolio Stylesheet -->\n";
	}
}
add_action('wp_head', 'WPPortfolio_renderCSS');



/**
 * Turn the portfolio of websites in the database into a single page containing details and screenshots using the [wp-portfolio] shortcode.
 * @param $atts The attributes of the shortcode.
 * @return String The updated content for the post or page.
 */
function WPPortfolio_convertShortcodeToPortfolio($atts)
{	
	// Process the attributes
	extract(shortcode_atts(array(
		'groups' 		=> '',
		'hidegroupinfo' => 0,
		'sitesperpage'	=> 0,
		'orderby' 		=> 'asc',
		'ordertype'		=> 'normal'
	), $atts));
	
	// If hidegroupinfo is 1, then hide group details by passing in a blank template to the render portfolio function
	$grouptemplate = false; // If false, then default group template is used.
	if (isset($atts['hidegroupinfo']) && $atts['hidegroupinfo'] == 1) {
		$grouptemplate = "&nbsp;";
	}
	
	// Sort ASC or DESC?
	$orderAscending = true;
	if (isset($atts['orderby']) && strtolower(trim($atts['orderby'])) == 'desc') {
		$orderAscending = false;
	}
	
	// Convert order type to use normal as default
	$orderType = strtolower(trim(WPPortfolio_getArrayValue($atts, 'ordertype')));
	if ($orderType != 'dateadded') {
		$orderType = 'normal';
	}
	
	// Groups 
	$groups = false;
	if (isset($atts['groups'])) {
		$groups = $atts['groups'];
	}
	
	// Sites per page
	$sitesperpage = 0;
	if (isset($atts['sitesperpage'])) {
		$sitesperpage = $atts['sitesperpage'] + 0;
	}
	
	return WPPortfolio_getAllPortfolioAsHTML($groups, false, $grouptemplate, $sitesperpage, $orderAscending, $orderType);
}
add_shortcode('wp-portfolio', 'WPPortfolio_convertShortcodeToPortfolio');



/**
 * Method to get the portfolio using the specified list of groups and return it as HTML.
 * 
 * @param $groups The comma separated string of group IDs to show.
 * @param $template_website The template used to render each website. If false, the website template defined in the settings is used instead.
 * @param $template_group The template used to render each group header. If false, the group template defined in the settings is used instead.
 * @param $sitesperpage The number of sites to show per page, or false if showing all sites at once. 
 * @param $orderAscending Order websites in ascending order, or if false, order in descending order.
 * @param $orderBy How to order the results (choose from 'normal' or 'dateadded'). Default option is 'normal'. If 'dateadded' is chosen, group names are not shown.
 * @return String The HTML which contains the portfolio as HTML.
 */
function WPPortfolio_getAllPortfolioAsHTML($groups = '', $template_website = false, $template_group = false, $sitesperpage = false, $orderAscending = true, $orderBy = 'normal')
{
	// Get portfolio from database
	global $wpdb;
	$websites_table = $wpdb->prefix . TABLE_WEBSITES;
	$groups_table   = $wpdb->prefix . TABLE_WEBSITE_GROUPS;		
		
	// Determine if we only want to show certain groups
	$WHERE_CLAUSE = "";
	if ($groups)
	{ 
		$selectedGroups = explode(",", $groups);
		foreach ($selectedGroups as $possibleGroup)
		{
			// Some matches might be empty strings
			if ($possibleGroup > 0) {
				$WHERE_CLAUSE .= "$groups_table.groupid = '$possibleGroup' OR ";
			}
		}
	} // end of if ($groups)
		
	// Add initial where if needed
	if ($WHERE_CLAUSE)
	{
		// Remove last OR to maintain valid SQL
		if (substr($WHERE_CLAUSE, -4) == ' OR ') {
			$WHERE_CLAUSE = substr($WHERE_CLAUSE, 0, strlen($WHERE_CLAUSE)-4);
		}				
		
		// Selectively choosing groups.
		$WHERE_CLAUSE = sprintf("WHERE (siteactive = 1) AND (%s)", $WHERE_CLAUSE);
	} 
	// Showing whole portfolio, but only active sites.
	else {
		$WHERE_CLAUSE = "WHERE (siteactive = 1)";
	}

	$ORDERBY_ORDERING = "";
	if (!$orderAscending) {
		$ORDERBY_ORDERING = 'DESC';
	}
	
	// How to order the results
	if (strtolower($orderBy) == 'dateadded') {
		$ORDERBY_CLAUSE = "ORDER BY siteadded $ORDERBY_ORDERING, sitename ASC";
		$template_group = ' '; // Disable group names
	} else {
		$ORDERBY_CLAUSE = "ORDER BY grouporder $ORDERBY_ORDERING, groupname $ORDERBY_ORDERING, siteorder $ORDERBY_ORDERING, sitename $ORDERBY_ORDERING";
	}
			
	// Get website details, merge with group details
	$SQL = "SELECT * FROM $websites_table
			LEFT JOIN $groups_table ON $websites_table.sitegroup = $groups_table.groupid
			$WHERE_CLAUSE
		 	$ORDERBY_CLAUSE
		 	";			
					
	$wpdb->show_errors();
	
	$paginghtml = false; 
	
	
	// Limit the number of sites shown on a single page.
	$LIMIT_CLAUSE = false;
	$sitesperpage = $sitesperpage + 0; // Convert to a number
	if ($sitesperpage)
	{
		// How many sites do we have?
		$websites = $wpdb->get_results($SQL, OBJECT);
		$website_count = $wpdb->num_rows;
		
		// Paging is needed, as we have more websites than sites/page.
		if ($website_count > $sitesperpage)
		{
			$numofpages = ceil($website_count / $sitesperpage);
			
			// Pick up the page number from the GET variable
			$currentpage = 1;
			if (isset($_GET['portfolio-page']) && ($_GET['portfolio-page'] + 0) > 0) {
				$currentpage = $_GET['portfolio-page'] + 0;
			}			
			
			// Create the HTML for the paging section
			$paginghtml = '<div class="portfolio-paging">';
			$paginghtml .= sprintf('<div class="page-count">Showing page %s of %s</div>', $currentpage, $numofpages);
			for ($i = 1; $i <= $numofpages; $i++) 
			{
				// No link for current page.
				if ($i == $currentpage) {
					$paginghtml .= sprintf('&nbsp;<span class="page-jump page-current"><b>%s</b></span>&nbsp;', $i, $i);
				} 
				// Link for other pages 
				else  {
					// Avoid parameter if first page
					if ($i == 1) {
						$paginghtml .= sprintf('&nbsp;<span class="page-jump"><a href="?"><b>%s</b></a></span>&nbsp;', $i, $i);
					} else {
						$paginghtml .= sprintf('&nbsp;<span class="page-jump"><a href="?portfolio-page=%s"><b>%s</b></a></span>&nbsp;', $i, $i);
					}
				}				
			}
			// Add Next Jump Links
			if ($currentpage < $numofpages) {
				$paginghtml .= sprintf('&nbsp;<span class="page-jump"><a href="?portfolio-page=%s"><b>Next</b></a></span>&nbsp;', $currentpage+1);
			} else {
				$paginghtml .= sprintf('&nbsp;<span class="page-jump"><b>Next</b></span>&nbsp;', $currentpage+1);
			}
			
			
			
			$paginghtml .= "</div>";			

			// Update the SQL for the pages effect
			// Show first page and set limit to start at first record.
			if ($currentpage <= 1) {
				$LIMIT_CLAUSE = sprintf("LIMIT 0, %s", $sitesperpage);
			} 
			// Show websites only for current page for inner page
			else
			{
				$firstresult = (($currentpage - 1) * $sitesperpage);
				$LIMIT_CLAUSE = sprintf("LIMIT %s, %s", $firstresult, $sitesperpage);
			}
			
		} // end of if ($website_count > $sitesperpage)
	}
	
	
	// Add the limit clause.
	$SQL .= $LIMIT_CLAUSE;
		
	$websites = $wpdb->get_results($SQL, OBJECT);

	// If we've got websites to show, then render into HTML
	if ($websites) {
		$portfolioHTML = WPPortfolio_renderPortfolio($websites, $template_website, $template_group, $paginghtml);
	} else {
		$portfolioHTML = false;
	}
	
	return $portfolioHTML;
}




/**
 * Method to get a random selection of websites from the portfolio using the specified list of groups and return it as HTML. No group details are 
 * returned when showing a random selection of the portfolio.
 * 
 * @param $groups The comma separated string of group IDs to use to find which websites to show. If false, websites are selected from the whole portfolio.
 * @param $count The number of websites to show in the output.
 * @param $template_website The template used to render each website. If false, the website template defined in the settings is used instead.
 * @return String The HTML which contains the portfolio as HTML.
 */
function WPPortfolio_getRandomPortfolioSelectionAsHTML($groups = '', $count = 3, $template_website = false)
{
	// Get portfolio from database
	global $wpdb;
	$websites_table = $wpdb->prefix . TABLE_WEBSITES;
	$groups_table   = $wpdb->prefix . TABLE_WEBSITE_GROUPS;		
		
	// Validate the count is a number
	$count = $count + 0;
	if ($count == 0) {
		$count = 3;
	}
	
	// Determine if we only want to get websites from certain groups
	$WHERE_CLAUSE = "";
	if ($groups)
	{ 
		$selectedGroups = explode(",", $groups);
		foreach ($selectedGroups as $possibleGroup)
		{
			// Some matches might be empty strings
			if ($possibleGroup > 0) {
				$WHERE_CLAUSE .= "$groups_table.groupid = '$possibleGroup' OR ";
			}
		}
	} // end of if ($groups)
		
	// Add initial where if needed
	if ($WHERE_CLAUSE)
	{
		// Remove last OR to maintain valid SQL
		if (substr($WHERE_CLAUSE, -4) == ' OR ') {
			$WHERE_CLAUSE = substr($WHERE_CLAUSE, 0, strlen($WHERE_CLAUSE)-4);
		}				
		
		$WHERE_CLAUSE = "WHERE siteactive != '0' AND (". $WHERE_CLAUSE . ")";
	}
	// Always hide inactive sites
	else {
		$WHERE_CLAUSE = "WHERE siteactive != '0'";
	}
		
			
	// Get website details, merge with group details
	$SQL = "SELECT * FROM $websites_table
			LEFT JOIN $groups_table ON $websites_table.sitegroup = $groups_table.groupid
			$WHERE_CLAUSE
		 	ORDER BY RAND()
		 	LIMIT $count
		 	";			
					
	$wpdb->show_errors();
	$websites = $wpdb->get_results($SQL, OBJECT);

	// If we've got websites to show, then render into HTML. Use blank group to avoid rendering group details.
	if ($websites) {
		$portfolioHTML = WPPortfolio_renderPortfolio($websites, $template_website, ' ');
	} else {
		$portfolioHTML = false;
	}
	
	return $portfolioHTML;
}



/**
 * Convert the website details in the database object into the HTML for the portfolio.
 * @param $websites The website detail object.
 * @param $template_website The template used to render each website. If false, the website template defined in the settings is used instead.
 * @param $template_group The template used to render each group header. If false, the group template defined in the settings is used instead.
 * @param $paging_html The HTML used for paging the portfolio. False by default.
 * @return String The HTML for the portfolio page.
 */
function WPPortfolio_renderPortfolio($websites, $template_website = false, $template_group = false, $paging_html = false)
{
	if (!$websites)
		return false;
			
	// Just put some space after other content before rendering portfolio.	
	$content = "\n\n";			

	// Used to track what group we're working with.
	$prev_group = "";
	
	// Get templates to use for rendering the website details. Use the defined options if the parameters are false.
	if (!$template_website) {
		$setting_template_website = stripslashes(get_option('WPPortfolio_setting_template_website'));
	} else {
		$setting_template_website = $template_website;		
	}

	if (!$template_group) {
		$setting_template_group = stripslashes(get_option('WPPortfolio_setting_template_group'));						
	} else {
		$setting_template_group = $template_group;	
			
	}
	 	
	
	// Render all the websites, but look after different groups
	foreach ($websites as $websitedetails)
	{
		// If we're rendering a new group, then show the group name and description 
		if ($prev_group != $websitedetails->groupname)
		{
			// Replace group name and description.					
			$renderedstr = WPPortfolio_replaceString(WPP_STR_GROUP_NAME, stripslashes($websitedetails->groupname), $setting_template_group);
			$renderedstr = WPPortfolio_replaceString(WPP_STR_GROUP_DESCRIPTION, stripslashes($websitedetails->groupdescription), $renderedstr);
			
			// Update content with templated group details
			$content .= "\n\n$renderedstr\n";
		}
		
		// Get the image thumbnail and generate the image tag. 
		
		
		// Render the website details
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_NAME, 		 	stripslashes($websitedetails->sitename), $setting_template_website);
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_URL, 		 	stripslashes($websitedetails->siteurl), $renderedstr);
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_DESCRIPTION, 	stripslashes($websitedetails->sitedescription), $renderedstr);
		
		// Handle the thumbnails - use custom if provided.
		$imageURL = false;
		if ($websitedetails->customthumb) 
		{
			$imageURL = WPPortfolio_getAdjustedCustomThumbnail($websitedetails->customthumb);
			$imagetag = sprintf('<img src="%s" alt="%s"/>', $imageURL, stripslashes($websitedetails->sitename));
		} 
		// Standard thumbnail
		else {
			$imageURL = WPPortfolio_getThumbnail($websitedetails->siteurl, false, ($websitedetails->specificpage == 1));
			$imagetag = sprintf('<img src="%s" alt="%s"/>', $imageURL, stripslashes($websitedetails->sitename));
		}
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_THUMBNAIL_URL, $imageURL, $renderedstr); /// Just URLs		
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_THUMBNAIL, $imagetag, $renderedstr);  // Full image tag
		
		
		
		$content .= "\n$renderedstr\n";
		
		// If fetching thumbnails, this might take a while. So flush.
		flush();
		
		// Track the groups
		$prev_group = $websitedetails->groupname;
	}
	
	$content .= $paging_html;
	
	// Credit link on portfolio. 
	if (get_option('WPPortfolio_setting_show_credit') == "on") {				
		$content .= '<div style="clear: both;"></div><div class="wpp-creditlink" style="font-size: 8pt; font-family: Verdana; float: right; clear: both;">Created using <a href="http://wordpress.org/extend/plugins/wp-portfolio" target="_blank">WP Portfolio</a> by the <a href="http://www.wpdoctors.co.uk/" target="_blank">WordPress Doctors</a></div>';
	} 
				
	// Add some space after the portfolio HTML 
	$content .= "\n\n";
	
	return $content;
}



/**
 * Create the cache directory if it doesn't exist.
 */
function WPPortfolio_createCacheDirectory()
{
	// Cache directory
	$actualThumbPath = WPPortfolio_getThumbPathActualDir();
			
	// Create cache directory if it doesn't exist	
	if (!file_exists($actualThumbPath)) {
		@mkdir($actualThumbPath, 0777, true);		
	} else {
		// Try to make the directory writable
		@chmod($actualThumbPath, 0777);
	}
}

/**
 * Gets the full directory path for the thumbnail directory with a trailing slash.
 * @return String The full directory path for the thumbnail directory.
 */
function WPPortfolio_getThumbPathActualDir() {
	return trailingslashit(trailingslashit(WP_PLUGIN_DIR).WPP_THUMBNAIL_PATH);	
}

/**
 * Gets the full URL path for the thumbnail directory with a trailing slash.
 * @return String The full URL for the thumbnail directory.
 */
function WPPortfolio_getThumbPathURL() {
	return trailingslashit(trailingslashit(WP_PLUGIN_URL).WPP_THUMBNAIL_PATH);
}

/**
 * Get the full URL path of the pending thumbnails.
 * @return String The full URL path of the pending thumbnails.
 */
function WPPortfolio_getPendingThumbURLPath() {
	return trailingslashit(WP_PLUGIN_URL).WPP_THUMB_DEFAULTS;
}

/**
 * Shows either information or error message.
 */
function WPPortfolio_showMessage($message = "Settings saved.", $errormsg = false)
{
	if ($errormsg) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated fade">';
	}

	echo "<p><strong>$message</strong></p></div>";
}

/**
 * Function: WPPortfolio_showRedirectionMessage();
 *
 * Shows settings saved and page being redirected message.
 */
function WPPortfolio_showRedirectionMessage($message, $target, $delay)
{
?>
	<div id="message" class="updated fade">
		<p>
			<strong><?php echo $message; ?><br /><br />
			Redirecting in <?php echo $delay ?> seconds. Please click <a href="<?php echo $target; ?>">here</a> if you do not wish to wait.</strong>
		</p>
	</div>
	
	<SCRIPT language="JavaScript">
    <!--
            function getgoing() {
                     top.location="<?php echo $target; ?>";
            }

            if (top.frames.length==0) {
                setTimeout('getgoing()',<?php echo $delay * 1000 ?>);
            }
	//-->
	</SCRIPT>
	<?php
}

/**
 * Determine if the specified key is valid, i.e. containing only letters and numbers.
 * @param $key The key to check
 * @return Boolean True if the key is valid, false otherwise.
 */
function WPPortfolio_isValidKey($key) 
{
	// Ensure the key only contains letters and numbers
	return preg_match('/^[a-z0-9A-Z]+$/', $key);
}

/**
 * Recursively delete a directory
 *
 * @param string $dir Directory name
 * @param boolean $deleteRootToo Delete specified top-level directory as well
 */
function WPPortfolio_unlinkRecursive($dir, $deleteRootToo)
{
    if(!$dh = @opendir($dir)) {
        return;
    }
    while (false !== ($obj = readdir($dh)))
    {
        if($obj == '.' || $obj == '..') {
            continue;
        }

        if (!@unlink($dir . '/' . $obj)) {
            WPPortfolio_unlinkRecursive($dir.'/'.$obj, true);
        }
    }

    closedir($dh);
   
    if ($deleteRootToo) {
        @rmdir($dir);
    }
   
    return;
} 

/**
 * Replace all occurances of the search string with the replacement. Uses alternative for str_ireplace if not available.
 * @param $searchstr The string to search for.
 * @param $replacestr The string to replace the search string with.
 * @param $haystack The string to search.
 * @return String The text with the replaced string.
 */
function WPPortfolio_replaceString($searchstr, $replacestr, $haystack) {
	
	// Faster, but in PHP5.
	if (function_exists("str_ireplace")) {
		return str_ireplace($searchstr, $replacestr, $haystack);
	}
	// Slower but handles PHP4
	else { 
		return preg_replace("/$searchstr/i", $replacestr, $haystack);
	}
}





/**
 * Get the details for the specified group ID.
 * @param $groupid The ID of the group to get the details for.
 * @return Array An array of the group details.
 */
function WPPortfolio_getGroupDetails($groupid) 
{
	global $wpdb;
	$table_name = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	
	$SQL = "SELECT * FROM $table_name 
			WHERE groupid = '".$wpdb->escape($groupid)."' LIMIT 1";
	
	// We need to strip slashes for each entry.
	return WPPortfolio_cleanSlashesFromArrayData($wpdb->get_row($SQL, ARRAY_A));
}

/**
 * Get the details for the specified Website ID.
 * @param $siteid The ID of the Website to get the details for.
 * @return Array An array of the Website details.
 */
function WPPortfolio_getWebsiteDetails($siteid) 
{
	global $wpdb;
	$table_name = $wpdb->prefix . TABLE_WEBSITES;
	
	$SQL = "SELECT * FROM $table_name 
			WHERE siteid = '".$wpdb->escape($siteid)."' LIMIT 1";

	// We need to strip slashes for each entry.
	return WPPortfolio_cleanSlashesFromArrayData($wpdb->get_row($SQL, ARRAY_A));
}

/**
 * Remove all slashes from all fields from data retrieved from the database.
 * @param $data The data array from the database.
 * @return Array The cleaned array.
 */
function WPPortfolio_cleanSlashesFromArrayData($data)
{
	if (count($data) > 0) {
		foreach ($data as $datakey => $datavalue) {
			$data[$datakey] = stripslashes($datavalue);
		}
	}
	
	return $data;
}

/**
 * Safe method to get the value from an array using the specified key.
 * @param $array The array to search.
 * @param $key The key to use to index the array.
 * @param $returnSpace If true, return a space if there's nothing in the array.
 * @return String The array value.
 */
function WPPortfolio_getArrayValue($array, $key, $returnSpace = false)
{
	if ($array && isset($array[$key])) {
		return $array[$key];
	}
	
	// If returnSpace is true, then return a space rather than nothing at all.
	if ($returnSpace) {
		return '&nbsp;';
	} else {
		return false;
	}
}
?>