
<div id="padd-sidebar">
	<div id="padd-sidebar-wrapper">
		<div id="padd-sidebar-interior">	

		<h2>Sidebar</h2>

	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar') ) : ?>
	


<div class="padd-box padd-box-ads">
	<h3>Sponsors</h3>
	<div class="padd-interior">
		<?php 
			padd_widget_funct_sponsors(); 
		?>
	</div>
</div>

<div class="padd-box padd-box-popular-posts">
	<h3>Popular Posts</h3>
	<div class="padd-interior">
	<?php 
		get_mostpopular('pages=0&stats_comments=1&range=all&limit=5&thumbnail_width=57&thumbnail_height=57&do_pattern=1&pattern_form={image}{title}{stats}');
	?>
	</div>
</div>

<div class="padd-box padd-box-flickr-rss" id="flickrrss">
	<h3>Featured Photos</h3>
	<div class="padd-interior">
		<?php 
			if (function_exists('get_flickrRSS')) {
				echo get_flickrRSS();
			} else {
				echo '<p class="notice">You have to install <a href="http://wordpress.org/extend/plugins/flickr-rss/">flickrRSS</a> plugin.</p>';
			}
		?>
	</div>
</div>

<div class="padd-box padd-box-featured-video">
	<h3>Featured Video</h3>
	<div class="padd-interior">
		<?php padd_widget_funct_featured_video(); ?>
	</div>
</div>

<div class="padd-box-group">
	<div class="padd-box padd-box-1">
		<h3>Pages</h3>
		<div class="padd-interior">
			<ul>
				<?php wp_list_pages('title_li=' ); ?>
			</ul>
		</div>
	</div>
	<div class="padd-box padd-box-2">
		<h3>Archives</h3>
		<div class="padd-interior">
			<ul>
				<?php wp_get_archives('title_li=&type=monthly'); ?>
			</ul>
		</div>
	</div>
	<div class="padd-clear"></div>
</div>

<div class="padd-box-group">
	<div class="padd-box padd-box-1">
		<h3>Blogroll</h3>
		<div class="padd-interior">
			<ul>
				<?php padd_theme_list_bookmarks(); ?>
			</ul>
		</div>
	</div>
	<div class="padd-box padd-box-2">
		<h3>Meta</h3>
		<div class="padd-interior">
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
				<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
				<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
				<?php wp_meta(); ?>
			</ul>
		</div>
	</div>
	<div class="padd-clear"></div>
</div>
	
	<?php endif; ?>

		</div>
	</div>
</div>


