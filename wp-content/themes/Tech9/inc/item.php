<?php $aOptions = sobotta::initOptions(false); ?>
<div id="tagline"><h2><?php bloginfo('description'); ?></h2></div>

<div id="item">
<div id="slider">
<script type="text/javascript">
featuredcontentglider.init({
	gliderid: "glidercontent",
	contentclass: "glidecontent",
	togglerid: "togglebox",
	remotecontent: "", 
	selected: 1,
	persiststate: true,
	speed: 1000,
	direction: "leftright", 
	autorotate: true, 
	autorotateconfig: [12000, 1] //if auto rotate enabled, set [milliseconds_btw_rotations, cycles_before_stopping]
})
</script>

<div id="glidercontent" class="glidecontentwrapper">

<div class="glidecontent">
<div class="simg">
<a href="<?php echo($aOptions['featured1-link']); ?>"><img src="<?php echo($aOptions['featured1-image']); ?>" alt="welcome" /></a>
</div>
<div class="itemtxt">
<h2><a href="<?php echo($aOptions['featured1-link']); ?>"><?php echo($aOptions['featured1-title']); ?></a></h2>
<?php echo($aOptions['featured1-desc']); ?>
<div class="read"><a href="<?php echo($aOptions['featured1-link']); ?>" rel="bookmark" title="Learn more">Read more</a></div>
</div>
</div>

<div class="glidecontent">
<div class="simg">
<a href="<?php echo($aOptions['featured2-link']); ?>"><img src="<?php echo($aOptions['featured2-image']); ?>" alt="welcome" /></a>
</div>
<div class="itemtxt">
<h2><a href="<?php echo($aOptions['featured2-link']); ?>"><?php echo($aOptions['featured2-title']); ?></a></h2>
<?php echo($aOptions['featured2-desc']); ?>
<div class="read"><a href="<?php echo($aOptions['featured2-link']); ?>" rel="bookmark" title="Learn more">Read more</a></div>
</div>
</div>

<div class="glidecontent">
<div class="simg"><a href="<?php echo($aOptions['featured3-link']); ?>"><img src="<?php echo($aOptions['featured3-image']); ?>" alt="welcome" /></a>
</div>
<div class="itemtxt">
<h2><a href="<?php echo($aOptions['featured3-link']); ?>"><?php echo($aOptions['featured3-title']); ?></a></h2>
<?php echo($aOptions['featured3-desc']); ?>
<div class="read"><a href="<?php echo($aOptions['featured3-link']); ?>" rel="bookmark" title="Learn more">Read more</a></div>
</div>
</div>


<div class="glidecontent">
<div class="simg">
<a href="<?php echo($aOptions['featured4-link']); ?>"><img src="<?php echo($aOptions['featured4-image']); ?>" alt="welcome" /></a>
</div>

<div class="itemtxt">
<h2><a href="<?php echo($aOptions['featured4-link']); ?>"><?php echo($aOptions['featured4-title']); ?></a></h2>
<?php echo($aOptions['featured4-desc']); ?>
<div class="read"><a href="<?php echo($aOptions['featured4-link']); ?>" rel="bookmark" title="Learn more">Read more</a></div>
</div>
</div>

</div>

<div id="htabs">
<div id="togglebox" class="glidecontenttoggler">
<a href="#" class="prev"></a>
<a href="#" class="toc">&nbsp;&nbsp; <?php echo($aOptions['featured1-title']); ?></a>
<a href="#" class="toc">&nbsp;&nbsp; <?php echo($aOptions['featured2-title']); ?></a>
<a href="#" class="toc">&nbsp;&nbsp; <?php echo($aOptions['featured3-title']); ?></a>
<a href="#" class="toc">&nbsp;&nbsp; <?php echo($aOptions['featured4-title']); ?></a>
<a href="#" class="next"></a>
</div>
</div>

</div>
</div>