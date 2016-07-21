<?php /*
**************************************************************************
Plugin Name: LINE BOTTER
Plugin URI: http://6oolab.com/
Description: LINE BOTの管理。
Version: 1.0.0
Author: 6oolab
Author URI: http://6oolab.com/
**************************************************************************/

class LINE_BOTTER {

	const NAME = 'LINE BOTTER';
	const VERSION = '1.0.0';
	const OPTIONS_KEY = 'line_botter_options';
	const VALUE_ON = 1;
	const VALUE_OFF = 0;
	private $options;
	private $success;
	private $errors;

	private $end_point      = 'https://trialbot-api.line.me';
	private $to_channel     = '1383378250';
	private $event_types = [
		'138311608800106203',//semd messages
		'140177271400161403',//send multiple messages
	];

	function __construct() {
		if ( is_admin() ) {
			$this->options = get_option(self::OPTIONS_KEY);
			add_action('init', array($this, 'init'));
			add_action('admin_menu', array($this, 'add_plugin'));
		}
	}

	function init() {
		add_action('wp_ajax_line_botter_post', array($this, 'line_botter_post'));
		add_action('wp_ajax_nopriv_line_botter_post', array($this, 'line_botter_post'));
	}

	function add_plugin() {
		add_menu_page('LINE BOT', 'LINE BOT', 'administrator', 'line_botter', array($this, 'settings'), 'dashicons-email-alt');
		add_submenu_page('line_botter', '設定', '設定', 'administrator', 'line_botter', array($this, 'settings'));
		add_submenu_page('line_botter', '投稿', '投稿', 'administrator', 'line_botter_post', array($this, 'post'));
	}

	function check() {
		$form = $_POST[self::OPTIONS_KEY];
		$this->options = $form;
		return empty($this->errors);
	}

	function update() {
		update_option(self::OPTIONS_KEY, $this->options);
		$this->success = "設定を保存しました。";
	}

	function settings_form() {
?>
<div class="wrap">
	<h2 class="title"><?php echo $this::NAME;?> 設定</h2>
	<form method="post">
	<?php if ( !empty($this->success) ):?>
		<div class="updated"><p><strong><?php esc_html_e($this->success) ?></strong></p></div>
	<?php elseif ( !empty($this->errors) ): foreach ($this->errors as $err):?>
		<div class="error"><p><strong><?php esc_html_e($err);?></strong></p></div>
	<?php endforeach; endif;?>
<style>
input[type='text'] { width: 100%; }
</style>
<table class="form-table">
	<tr>
		<th>callback_url</th>
		<td>
<input type="text" name="<?php echo self::OPTIONS_KEY;?>[callback_url]" value="<?php if(isset($this->options['callback_url'])) esc_attr_e($this->options['callback_url']);?>" maxlength="100">
		</td>
	</tr>
	<tr>
		<th>channel_id</th>
		<td>
<input type="text" name="<?php echo self::OPTIONS_KEY;?>[channel_id]" value="<?php if(isset($this->options['channel_id'])) esc_attr_e($this->options['channel_id']);?>" maxlength="100">
		</td>
	</tr>
	<tr>
		<th>channel_secret</th>
		<td>
<input type="text" name="<?php echo self::OPTIONS_KEY;?>[channel_secret]" value="<?php if(isset($this->options['channel_secret'])) esc_attr_e($this->options['channel_secret']);?>" maxlength="100">
		</td>
	</tr>
	<tr>
		<th>mid</th>
		<td>
<input type="text" name="<?php echo self::OPTIONS_KEY;?>[mid]" value="<?php if(isset($this->options['mid'])) esc_attr_e($this->options['mid']);?>" maxlength="100">
		</td>
	</tr>
</table>
<?php submit_button();?>
	</form>
</div><!-- /.wrap -->
<div class="clear"></div>
<?php
	}

	function settings_ctrl() {
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {//submit
			if ( $this->check() ) $this->update();
		} else {//first
			$this->form = $this->options;
		}
	}

	function settings() {
		$this->settings_ctrl();
		$this->settings_form();
	}

	function post() {
		if ( !isset($this->options['callback_url']) ) {
			$this->settings();
			return;
		}
		$this->post_ctrl();
		$this->post_form();
	}

	function post_ctrl() {
	}

	function post_form() {
?>
<div class="wrap">
	<h2 class="title"><?php echo $this::NAME;?> 設定</h2>
	<form method="post" id="form">
	<?php if ( !empty($this->success) ):?>
		<div class="updated"><p><strong><?php esc_html_e($this->success) ?></strong></p></div>
	<?php elseif ( !empty($this->errors) ): foreach ($this->errors as $err):?>
		<div class="error"><p><strong><?php esc_html_e($err);?></strong></p></div>
	<?php endforeach; endif;?>
<style>
input[type='text'] { width: 100%; }
</style>
<table class="form-table">
	<tr>
		<th>テキスト</th>
		<td>
<input type="text" name="text" value="" maxlength="100">
		</td>
	</tr>
</table>
<p class="submit"><input type="submit" id="submit" class="button button-primary" value="送信"></p>
	</form>
</div><!-- /.wrap -->
<div class="clear"></div>
<script>
jQuery('#form').submit(function() {
	jQuery.ajax({
		type: "POST",
		url: "<?php esc_attr_e($this->options['callback_url']);?>",
		timeout: 30000,
		cache: false,
		data:{
			form: jQuery(this).serialize()
		},
		beforeSend: function(x, s) {
        },
		success:function(r) {
			//console.log(r);
			jQuery("input[type='text']").val("");
		},
        error: function() {
        }
	});
    return false;
});
</script>
<?php
	}

	function line_botter_post() {
		check_ajax_referer('line_botter');
		nocache_headers();
	}

}// class

$LB = new LINE_BOTTER();