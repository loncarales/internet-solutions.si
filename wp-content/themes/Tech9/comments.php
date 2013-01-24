<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>
				
				<p class="nocomments">This post is password protected. Enter the password to view comments.<p>
				
				<?php
				return;
            }
        }

		/* This variable is for alternating comment background */
		$oddcomment = 'alt';
?>
<!-- You can start editing here. -->
<?php $i = 0; ?>
<?php if ($comments) : ?>

	<div id="comments" class="section">
  <h4><?php comments_number('No comments', '1 Comment', '% Comments' );?>, 
  <a href="#respond" title="Leave a comment">Leave a comment</a> or <a href="<?php trackback_url(true); ?>" rel="trackback">Ping</a></h3>
  </div> 
  
	<ol class="commentslist">
	<?php foreach ($comments as $comment) : ?>
        <?php $i++; ?>
	<?php if (get_comment_type() == "comment"){ ?>
	<li class="<?php if ($comment->comment_author_email == "your@email.com") echo 'author'; else if ($comment->comment_author_email == "another@email.com") echo 'author'; else echo $oddcomment; ?> item" id="comment-<?php comment_ID() ?>">
		
    <div class="fix">
		<div class="author_meta">
		
<p class="author_meta">
<span class="user"><?php comment_author_link() ?></span> 
<span class="comment_edit"><small><?php edit_comment_link('Edit','(',')'); ?></small></span>
</p>
		</div>

<?php 
if ( !empty( $comment->comment_author_email ) ) {
	$md5 = md5( $comment->comment_author_email );
	$default = urlencode( '' );
	echo "<img style='float:right;margin-right:20px;' src='http://www.gravatar.com/avatar.php?gravatar_id=$md5&amp;size=30&amp;default=$default' alt='' />";
}
?>
<span class="count">
<?php echo $i; ?>
</span>

		<div class="comment_text">
			<?php if ($comment->comment_approved == '0') : ?>
			<em>Your comment is awaiting moderation.</em>
			<?php endif; ?>
			<?php comment_text() ?>
		</div>
		<p class="post_meta"><a href="#comment-<?php comment_ID() ?>" title="Comment Permalink"><?php comment_date('M jS, Y') ?></a></p>
		</div>
	</li>	
	<?php /* Changes every other comment to a different class */ if ('alt' == $oddcomment) $oddcomment = ''; else $oddcomment = 'alt'; ?>
	<?php } ?>
	<?php endforeach; /* end for each comment */ ?>
	</ol>
	
	<?php foreach ($comments as $comment) : ?>
	<?php if (get_comment_type() != "comment"){ ?>
		<div class="author_meta"><?php comment_author_link() ?> - <?php comment_date('M jS') ?></div>
	<?php /* Changes every other comment to a different class */ if ('alt' == $oddcomment) $oddcomment = ''; else $oddcomment = 'alt'; ?>
	<?php } ?>
	<?php endforeach; /* end for each comment */ ?>
 	<?php else : // this is displayed if there are no comments so far ?>

<h2>Add <span>your comment</span></h2>

<div id="comments" class="section">
<h3><?php comments_number('No comments', '1 Comment', '% Comments' );?>, 
<a href="#respond" title="Leave a comment">Leave a comment</a> or <a href="<?php trackback_url(true); ?>" rel="trackback">Ping</a></h3></div>
  	<?php if ('open' == $post->comment_status) : ?> 
	 <?php else : // comments are closed ?>
	<div id="comments_closed">
		<p class="nocomments">Kommentare sind nicht erlaubt.</p>
	</div>
	<?php endif; ?>
<?php endif; ?>
<?php if ('open' == $post->comment_status) : ?>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( $user_ID ) : ?>
<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. 
<a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out">log out &raquo;</a></p>
<?php else : ?>
<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label></p>
<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small>Mail <?php if ($req) echo "(required)"; ?></small></label></p>
<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small>Website</small></label></p>
<?php endif; ?>

<p><textarea name="comment" id="comment" cols="50" rows="12" tabindex="5"></textarea></p>
<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p><?php do_action('comment_form', $post->ID); ?></form>
<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>