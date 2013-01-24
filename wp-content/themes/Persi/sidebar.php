	<div id="sidebar">
		<?php $show_banner_125_125 = obwp_get_meta(SHORTNAME."_show_banner_125_125"); if($show_banner_125_125!='No') : ?>
		<div id="sidebar_ads">
        
        
        

<?php include(TEMPLATEPATH."/script2/includes2.php"); ?>
        
        </div>
		<?php endif; ?>
		<div class="sidebar_widgets2">
			<ul>
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ){
				?>
				<?
				} else { ?>	
		
				<li class="widget_archive">
					<h2 class="widgettitle">Archives</h2>
					<ul>
					<?php wp_get_archives('type=monthly'); ?>
					</ul>
				</li>
		
				<li class="widget_links">
					<h2 class="widgettitle">Links</h2>
					<ul>
					<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
					</ul>
				</li>
				
			<?php } ?>
			</ul>
		</div>
	</div>

