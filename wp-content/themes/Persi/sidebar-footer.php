<div id="footer_widgets">
		<div class="footer_widgets">
			<ul>
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(3) ){
				?>
				<?
				} else { ?>	
					
				<li class="widget_most_rated">
					<h2 class="widgettitle">Recent Comments</h2>
					
					   <ul>
						  <?php recent_comments(5); ?>
					   </ul>
					
				</li>
				
			<?php } ?>
			</ul>
		</div>
		<div class="footer_widgets">
			<ul>
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(4) ){
				?>
				<?
				} else { ?>	
					
				<li class="widget_most_commented">
					<h2 class="widgettitle">Most Commented</h2>
					<ul>
						<?php obwp_list_most_commented(6); ?>
					</ul>
				</li>
				
			<?php } ?>
			</ul>
		</div>
		<div class="footer_widgets">
			<ul>
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(5) ){
				?>
				<?
				} else { ?>	
					
				<li class="widget_most_viewed">
					<h2 class="widgettitle">Most Viewed</h2>
					<?php if (function_exists('get_most_viewed')): ?>
					   <ul>
						  <?php get_most_viewed('post',6); ?>
					   </ul>
					<?php endif; ?> 
				</li>
				
			<?php } ?>
			</ul>
		</div>
</div>