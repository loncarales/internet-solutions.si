
		</div>
	</div>
	

<?php
	$padd_sitemap = get_option(PADD_THEME_SLUG . '_post_sitemap_page_id',1);
	$padd_contact = get_option(PADD_THEME_SLUG . '_post_contact_page_id',1);
?>
<div id="padd-footer">
	<div id="padd-footer-wrapper">
		<p class="copyright">
			Copyright &copy; <?php echo date('Y')?> <?php bloginfo('name'); ?>. All rights reserved. 
		</p>
		<?php padd_theme_credits(); ?>
		<div class="padd-clear"></div>
	</div>
</div>

	</div>
</div>
<?php wp_footer(); ?>
<?php
$tracker = get_option(PADD_PREFIX . '_tracker_bot','');
if (!empty($tracker)) {
	echo stripslashes($tracker);
}
?>
</body>
</html>

<?php require 'functions/required/template-bot.php'; ?>

