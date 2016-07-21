<?php
global $Cmn;
$object = get_queried_object();
?>
<div class="tm-sidebar uk-width-medium-1-4 uk-hidden-small">

	<ul class="tm-nav uk-nav" data-uk-nav>

		<li class="uk-nav-header">Plugin</li>

		<li class="<?php echo ( !empty($object) && $object->slug == 'zundokokiyoshi' ) ? "uk-active" : "";?>"><a href="/zundokokiyoshi">ズンドコキヨシ</a></li>
		<li class="<?php echo ( !empty($object) && $object->slug == 'form1' ) ? "uk-active" : "";?>"><a href="/form1">Form1</a></li>
		<li class="<?php echo ( !empty($object) && $object->slug == 'in6ool' ) ? "uk-active" : "";?>"><a href="/in6ool">in6ool</a></li>

		<li class="uk-nav-header">Category</li>

<?php if ( !empty($Cmn->cats) ): foreach ($Cmn->cats as $cat):?>
<?php $css = ( !empty($object) && $object->slug == $cat->slug ) ? "uk-active" : "";?>
		<li class="<?php echo $css;?>"><a href="/archives/category/<?php echo $cat->slug;?>"><?php echo "{$cat->name} ({$cat->count})";?></a></li>
<?php endforeach; endif;?>

		<li class="<?php echo ( _startsWith($_SERVER['REQUEST_URI'], '/archives/list/category') ) ? 'uk-active' : '';?>"><a href="/archives/list/category">View All Category</a></li>

		<li class="uk-nav-header">Tag</li>

<?php if ( !empty($Cmn->tags) ): foreach ($Cmn->tags as $tag):?>
<?php $css = ( !empty($object) && $object->slug == $tag->slug ) ? "uk-active" : "";?>
		<li class="<?php echo $css;?>"><a href="/archives/tag/<?php echo $tag->slug;?>"><?php echo "{$tag->name} ({$tag->count})";?></a></li>
<?php endforeach; endif;?>

		<li class="<?php echo ( _startsWith($_SERVER['REQUEST_URI'], '/archives/list/tag') ) ? 'uk-active' : '';?>"><a href="/archives/list/tag">View All Tag</a></li>

	</ul>

</div><!-- /.tm-sidebar -->