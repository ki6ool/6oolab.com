<?php
global $wp_query;
$s = isset($wp_query->query['s']) ? $wp_query->query['s'] : '';
?>
<div class="container" style="padding: 20px 0;">
	<form action="/" method="get">
		<p class="control has-addons has-addons-centered">
			<input name="s" class="input" type="text" value="<?php echo esc_attr($s);?>">
			<button type="submit" class="button is-info">
			<span class="icon" style="margin-left: 0;"><i class="fa fa-search"></i></span></button>
		</p>
	</form>
</div>


