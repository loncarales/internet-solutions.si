=== Wordpress Portfolio Plugin (WP Portfolio) ===
Contributors: DanHarrison 
Donate link: http://www.wpdoctors.co.uk/our-wordpress-plugins/wp-portfolio/
Tags: portfolio, thumbnails, plugins, web designer, websites
Requires at least: 2.5
Tested up to: 3.0.1
Stable tag: 1.16
	
A plugin that allows you to quickly and easily show off your portfolio of websites on your wordpress blog with automatically generated thumbnails. 


== Description ==

A plugin that allows you to show off your portfolio through a single page on your wordpress blog with automatically generated thumbnails. To show 
your portfolio, create a new page and paste `[wp-portfolio]` into it.

The plugin requires you to have a free account with [Shrink The Web](http://www.shrinktheweb.com/) to generate the thumbnails. This plugin also requires PHP5 or above.


= About the Author =
Dan Harrison is a blogging fanatic, who has been running Wordpress on all of his websites for years. Dan runs a web development 
agency, called [WP Doctors](http://www.wpdoctors.co.uk), that specialises in Wordpress development and design, such as creating new Wordpress plugins and templates. 


= Problems and Support =
Please check the [frequently asked questions](http://wordpress.org/extend/plugins/wp-portfolio/faq/) page if you have any issues. As a last resort, 
please raise a problem in the [WP Portfolio Support Forum on Wordpress.org](http://wordpress.org/tags/wp-portfolio?forum_id=10), and I'll respond to the ticket as soon as 
possible. Please be aware, this might be a couple of days.


= Comments and Feedback =
If you have any comments, ideas or any other feedback on this plugin, please leave comments on the [WP Portfolio Support Forum on Wordpress.org](http://wordpress.org/tags/wp-portfolio?forum_id=10)
or contact me directly via the [WP Doctors Contact Page](http://www.wpdoctors.co.uk/contact/). We're always wanting testimonials, so if you'd like to give us a 2-3 sentence testimonial, please
 contact us via the [WP Doctors Contact Page](http://www.wpdoctors.co.uk/contact/) too.



= Requesting Features =
My schedule is extremely busy, and so I have little time to add new features to this plugin. If you are keen for a feature to be implemented, I can add new 
features in return for a small fee which helps cover my time. Due to running an agency, so my clients are my first priority. By paying a small fee, you effectively 
become a client, and therefore I can implement desired features more quickly. Please contact me via the [WP Doctors Contact Page](http://www.wpdoctors.co.uk/contact/) if 
you would like to pay to have a new feature implemented. 

If you are prepared to wait, I do welcome feature ideas, which can be left on the [WP Portfolio Support Forum on Wordpress.org](http://wordpress.org/tags/wp-portfolio?forum_id=10). 	




This plugin is licensed under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0).

	
== Installation ==

* 1) Extract the zip file and just drop the contents in the `wp-content/plugins/` directory of your WordPress installation 
* 2) Activate the plugin from Plugins page.
* 3) Edit a page that you want your portfolio to appear on and paste `[wp-portfolio]`  into it.
* 4) Add your websites in WP Portfolio within your Wordpress admin area.


== Screenshots ==

You can see the plugin in action on the author's website [Dan Harrison's Portfolio](http://www.runningawebsite.com/portfolio/), and you can see screenshots of the admin area on the [WP Portfolio plugin page](http://www.wpdoctors.co.uk/our-wordpress-plugins/wp-portfolio/).</a>.

== Changelog ==

= 1.16 =
* Fixed bug where debug table wasn't being created.
* Changed menu access level to use the 'manage_options' setting, rather than the deprecated use of a user level number.
* Fixed bug where errors reported when installing the plugin.
* Fixed minor issue when saving website order.
* Added ability to show websites by the date that they were added. e.g. **`[wp-portfolio ordertype="dateadded" orderby="desc" /]`**
* Added a new template tag to get just the thumbnail URL (**`%WEBSITE_THUMBNAIL_URL%`**), rather than a full image HTML tag (**`%WEBSITE_THUMBNAIL%`**).
* Added option to change how custom thumbnails are resized based on style requirements (match only width of custom thumbnails, match only height of website thumbnails or ensure website thumbnail is never larger than other website thumbnails).

= 1.15 =
* Added support for ShrinkTheWeb.com's new CDN and API.

= 1.14 =
* Added support for internal pages using Shrink The Web's paid-for feature for showing specific pages.
* Updated documentation to mention new website.
* Removed old style tag upgrader code.
* Added debug option that logs requests to help locate problems.

= 1.13 =
* Added paging option for showing X number of websites per page.

= 1.12 =
* Added support for website ordering.
* Added image alt tags by default to templates.
* Fixed bug to show websites by default when adding a new website to the portfolio.

= 1.11 =
* Fixed bug with adding a website with a missing description. Thanks to Adam Coulthard for finding the issue.
* Ensured compliance with Wordpress 2.9 specification.
* Added `target="_blank"` for the links in the author credit link at the bottom of any rendered portfolio. 
* Added ability to hide/show a given website without having to remove it.


= 1.10 =
* Added ability for cached thumbnails to never expire.
* Added custom thumbnails so that you can override the screenshot with your own image, such as custom graphics and photos. Custom thumbnails are automatically resized to match other thumbnails.
* Added a timeout of 10 seconds for loading thumbnail images so that pages do eventually load.
* Added new option `[wp-portfolio hidegroupinfo="1"]` so that you can hide group descriptions on only certain pages or posts.

= 1.09 =
* Changed the code that shows the portfolio to `[wp-portfolio]` to improve performance, reduce errors and to allow for new functionality.
* Added a tool to automatically upgrade the old style tags to the new style.

= 1.08 =
* Added the ability to render the portfolio from within your theme files in PHP. 
* Added PHP code to allow you to create a random selection of your portfolio from PHP.
* Moved all documentation into a single documentation page.

= 1.07 =
* Removed a debug message 
* Added silent error handling for creating cache directory

= 1.06 =
* Fixed the broken regular expression to allow the original method of showing all websites. 

= 1.05 =
* Added feature to portfolio admin section that allows to only show websites within a certain group.
* Massive cleanup of code for admin area to reduce errors and allow future features more easily.
* Added the much requested selective rending of groups. This means you can choose which groups of websites you show on any page.

= 1.04 =
* Fixed issue where default thumbnails were not showing when thumbnail is not available.

= 1.03 =
* Fixed an issue where saving the template CSS over-writes the group template code.

= 1.02 =
* Added option for using cURL rather than fopen for fetching thumbnails to handle strict server security settings.
* Moved formatting options for portfolio into separate settings section.
* Created option to enable/disable credit link back to my website.
* Now handles lack of `str_ireplace` function if using PHP4.
* Added button to empty the thumbnail cache.


= 1.01 =
* Removed test.css from header when CSS is rendered on page.

= 1.00 =
* Initial Release



== Frequently Asked Questions ==

= Troubleshooting =

**Why are my thumbnails not showing up straight away?**

The Shrink The Web (STW) servers do not create thumbnails straight away once they are requested. It typically takes up to 2 minutes for the thumbnail to be 
created and made available.



**How do I force the thumbnail to be re-captured?**

You need to visit the STW website and request it. Free users are able to do this up to 5 times per month.



**My thumbnails are not showing up? Help!**

There could be a number of reasons why the thumbnail files are not being downloaded. However, here's a list of things to check.

* Ensure the cache directory exists. Although the plugin tries to create the cache directory, some server set-ups don't let it work. So create the cache 
directory with permissions 0777 as `/wp-content/plugins/wp-portfolio/cache/`.

* Ensure you've correctly set the `STW Access Key ID` and `STW Secret Key` options in the `Portfolio Settings`.

* Check that you've added some websites to your portfolio.

* Check that you have `[wp-portfolio]` in one of your pages. You can specify specific groups later, you just want to check that all of the websites are shown.

* Ensure that the `Website HTML Template`, `Group HTML Template` and `Template CSS` fields in `Layout Settings`  contain something. If they don't, you can 
copy the default templates from lower down that page.

* Check that your web host is using PHP5 and not the outdated PHP4.



**Why are my custom thumbnails not showing up?**

* The most likely reason is that the URL for the image is incorrect. Copy and paste the image URL into your web browser, and make sure you see the image correctly. If you don't see the image correctly, then there's no way that the plugin can load the image correctly. 

* The other likely cause is that cache directory does not exist (see above), 


**I get the following error, what's going on?**

`Parse error: syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or
T_FUNCTION or T_VAR or '}' in
/home/path/to/wordpress/wp-content/plugins/wp-portfolio/wplib/utils_formbuilder.inc.php
on line 30`

WP Portfolio only supports PHP5, not PHP4. The error above is due to `function class_exists()` only existing in PHP5 and not PHP4. 

Most web hosting companies have the old PHP4 switched on by default. Just ask them to change your hosting account to PHP5. Some hosting accounts allow you to 
do this yourself from within your hosting control panel.


**When trying to show the portfolio, I get an error about a missing column. What do I do?**

This is usually due to the plugin tables not being created properly. Just deactivate and then activate the plugin. The plugin should automatically detect that the tables need upgrading and fix it for you.


**When looking at the setings page, I just get a blank page or errors.**

This has been encountered when an `open_basedir restriction in effect` security restriction is in place, typically for those with plesk-based hosting. It's probably justified for standard users as it prevents them from accessing unwanted dirs. However, it may need to be turned off by people who want to do more with their website.
The key for all those interested is to turn off "open_basedir restriction" in their Plesk hosting account. Speaking to your hosting company if you need help with this issue.


= Features and Support =

**Does WP Portfolio support paging?**

Yes it does. Full paging has now been implemented, as of V1.13. To show 3 websites per page, use `[wp-portfolio sitesperpage="3"]`, or to show all websites, just use `[wp-portfolio]` as normal. Check the documentation for full usage details.

**What is the WP Portfolio group syntax?**

* To show all groups, use `[wp-portfolio]`
* To show just the group with an ID of 1, use `[wp-portfolio groups="1"]`
* To show groups with IDs of 1, 2 and 4, use `[wp-portfolio groups="1,2,4"]`

Please note that the order of the group numbers e.g. "1,2,3" does not indicate the order in which they are shown. The order of IDs in the brackets are in fact 
ignored. The order of the groups is determined by the order value for each group in the admin area.


**In the settings, you can only use three sizes of thumbnails; Small (120 x 90), Large (200 x 150) and Extra Large (240 x 340). Is it possible to get custom 
image size of 550 x 227?**

Unfortunately not, the ShrinkTheWeb service only provides thumbnails in those sizes.


= Usage =

**How do I hide the category title and description on the portfolio page?**

Go to `Layout Settings` in the WP Portfolio admin section. Change the value of `Group HTML Template` to `&nbsp;` and save your settings. That will remove the 
category details from any page showing your portfolio of websites.

