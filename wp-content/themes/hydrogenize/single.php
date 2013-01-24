<?php
/*
Template Name: Single Post
*/
?>
<?php get_header(); ?>

<div id="padd-content">
	<div id="padd-content-wrapper">
	
<?php if (have_posts()) : ?>

<div class="padd-post-group padd-post-group-single">
	<div class="padd-post-list padd-post-list-single">
	<?php while (have_posts()) : ?>
		<?php the_post(); ?>
		<div class="padd-post-item padd-post-item-single" id="post-<?php the_ID(); ?>">
			<div class="padd-post-title">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			</div>
			<div class="padd-post-item-entry">
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p class="pages"><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			</div>
			<?php
				$desc = get_the_author_meta('description');
				if (!empty($desc)) {
			?>
			<div class="padd-post-box padd-post-box-about-author">
				<div class="padd-post-box-title">
					<h2>About the Author</h2>
				</div>
				<div class="padd-post-box-interior">
					<?php echo get_avatar(get_the_author_meta('email'),53) ?>
					<p><?php echo $desc; ?></p>
					<div class="clearer"></div>
				</div>
			</div>
			<?php 
				}
			?>
			<div class="padd-post-box padd-post-box-sb">
				<div class="padd-post-box-title">
					<h2>Spread The Love, Share Our Article</h2>
				</div>
				<?php
					$padd_sb_url = urlencode(get_permalink());
					$padd_sb_title = urlencode(get_the_title());
					$padd_sb_notes = urlencode(strip_tags(padd_theme_summary(get_the_content(),255)));
					$padd_img_path = get_theme_root_uri() . '/' . PADD_THEME_SLUG . '/images/icon-bm-32-%s.png';
				?>
				<div class="padd-interior">
					<ul>
						<li class="icon-delicious-mini">
							<a href="http://delicious.com/post?url=<?php echo $padd_sb_url; ?>&amp;title=<?php echo $padd_sb_title; ?>&amp;notes=<?php echo $padd_sb_notes; ?>">
								<img alt="Delicious" src="<?php printf($padd_img_path, 'delicious'); ?>" />
							</a>
						</li>
						<li class="icon-digg-mini">
							<a href="http://digg.com/submit?phase=2&amp;url=<?php echo $padd_sb_url; ?>&amp;title=<?php echo $padd_sb_title; ?>&amp;bodytext=<?php echo $padd_sb_notes; ?>">
								<img alt="Digg" src="<?php printf($padd_img_path, 'digg'); ?>" />
							</a>
						</li>
						<li class="icon-newsvine-mini">
							<a href="http://www.newsvine.com/_tools/seed&amp;save?u=<?php echo $padd_sb_url; ?>&amp;h=<?php echo $padd_sb_title; ?>">
								<img alt="NewsVine" src="<?php printf($padd_img_path, 'newsvine'); ?>" />
							</a>
						</li>
						<li class="icon-rss-mini">
							<a href="<?php bloginfo('rss2_url'); ?>">
								<img alt="RSS" src="<?php printf($padd_img_path, 'rss'); ?>" />
							</a>
						</li>
						<li class="icon-stumbleupon-mini">
							<a href="http://www.stumbleupon.com/post?url=<?php echo $padd_sb_url; ?>&amp;title=<?php echo $padd_sb_title; ?>">
								<img alt="StumbleUpon" src="<?php printf($padd_img_path, 'stumbleupon'); ?>" />
							</a>
						</li>
						<li class="icon-technorati-mini">
							<a href="http://technorati.com/faves?add=<?php echo $padd_sb_url; ?>">
								<img alt="Technorati" src="<?php printf($padd_img_path, 'technorati'); ?>" />
							</a>
						</li>
						<li class="icon-twitter-mini">
							<a href="http://twitter.com/home?status=<?php echo $padd_sb_title; ?>%20-%20<?php echo $padd_sb_url; ?>">
								<img alt="Twitter" src="<?php printf($padd_img_path, 'twitter'); ?>" />
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="padd-post-box padd-post-box-related">
				<div class="padd-post-box-title">
					<h2>Related Posts</h2>
				</div>
				<div class="padd-post-box-interior">
					<?php
						padd_related_posts(get_the_ID());
					?>
				</div>
			</div>
			<?php comments_template('',true); ?>
		</div>
	<?php endwhile; ?>
	</div>
	
</div>

<?php else : ?>	

<div class="padd-post-group padd-post-group-result padd-post-group-error">
	<div class="padd-post-group-title">
		<h1>Not Found</h1>
	</div>
	<div class="padd-post-group-descr">
		<p>Sorry, but you are looking for a category that isn't here.</p>
	</div>
</div>

<?php endif; ?>

	</div>
</div>

<?php get_sidebar(); ?>

<div class="padd-clear"></div>

<?php get_footer(); ?>
