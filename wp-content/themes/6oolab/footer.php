<?php global $Cmn;?>
</div><!-- /.uk-grid -->
</div><!-- /.uk-container -->
</div><!-- /.tm-middle -->

<div class="tm-footer">
	<div class="uk-container uk-container-center uk-text-center">

		<ul class="uk-subnav uk-subnav-line uk-flex-center">
			<li><a href="http://wordpress.information.jp/" target="_blank">WPQ</a></li>
			<li><a href="https://teratail.com/users/ki6ool" target="_blank">teratail</a></li>
			<li><a href="https://profiles.wordpress.org/ki6ool/" target="_blank">Wordpress</a></li>
			<li><a href="https://github.com/ki6ool" target="_blank">GitHub</a></li>
			<li><a href="https://twitter.com/ki6ool" target="_blank">Twitter</a></li>
		</ul>

		<div class="uk-panel">
			<p><a href="http://6oolab.com">&copy;6oolab</a></p>
		</div>

	</div>
</div><!-- /#tm-footer -->

<div id="tm-offcanvas" class="uk-offcanvas">
	<div class="uk-offcanvas-bar">

		<ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav="{multiple:true}">

			<li class="uk-parent"><a href="#">6oolab</a>
				<ul class="uk-nav-sub">
					<li><a href="/">Home</a></li>
				</ul>
			</li>

			<li class="uk-parent"><a href="#">Category</a>
				<ul class="uk-nav-sub">
<?php if ( !empty($Cmn->cats) ): foreach ($Cmn->cats as $cat):?>
					<li><a href="/archives/category/<?php echo $cat->slug;?>"><?php echo "{$cat->name}";?></a></li>
<?php endforeach; endif;?>
					<li><a href="/archives/list/category">View All Category</a></li>
				</ul>
			</li>

			<li class="uk-parent"><a href="#">Tag</a>
				<ul class="uk-nav-sub">
<?php if ( !empty($Cmn->tags) ): foreach ($Cmn->tags as $tag):?>
					<li><a href="/archives/tag/<?php echo $tag->slug;?>"><?php echo "{$tag->name}";?></a></li>
<?php endforeach; endif;?>
					<li><a href="/archives/list/tag">View All Tag</a></li>
				</ul>
			</li>

		</ul>

	</div>
</div><!-- /#tm-offcanvas -->

<?php wp_footer();?>
</body>
</html>
