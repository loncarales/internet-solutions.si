	<div id="sidebar_right">
		<div class="widget_twitters">
			<h2 class="widgettitle">Latest Tweets</h2>
			<ul id="twitter_update_list"><li>&nbsp;</li></ul>
		</div>
		<div class="sidebar_widgets">
			<ul>
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ){
				?>
				<?
				} else { ?>	
					
				<li class="widget_categories">
					<h2 class="widgettitle">Category</h2>
					<ul>
						<?php wp_list_cats('sort_column=name&optioncount=1'); ?>
					</ul>
				</li>
					
				<li class="widget_recent_comments">
					<h2 class="widgettitle">Recent Comments</h2>
					<?php recent_comments(10); ?>
				</li>
					
				<li class="widget_recent_entries">
					<h2 class="widgettitle">Latest Posts</h2>
					<?php list_recent_posts(10); ?>
				</li>
				
			<?php } ?>
			</ul>
		</div>
	</div>

