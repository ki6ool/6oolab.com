<!DOCTYPE html>
<html lang="ja">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://6oolab.com/feed" />
<link rel="icon" type="image/png" href="/favicon.png">
<link href='//fonts.googleapis.com/css?family=Raleway:400,300,600' rel='stylesheet' type='text/css' id='gwf'>
<script>!function(){var e=document.getElementById("gwf");e.rel="stylesheet"}();</script>
<?php wp_head();?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body>

<section class="hero is-info">
	<div class="hero-head">
		<div class="container">
			<nav class="nav">
				<div class="nav-left">
					<a class="nav-item is-brand" href="/" style="font-size: larger;">
						<?php echo get_option('blogname');?>
					</a>
				</div>
			</nav>
		</div>
	</div><!-- /.hero-head -->
<?php
get_template_part('eyecatch');
get_template_part('breadcrumbs')
?>
</section>

<?php is_home() ? get_search_form() : get_template_part('adsense');?>