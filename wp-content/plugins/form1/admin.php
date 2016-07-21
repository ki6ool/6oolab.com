<?php
if ( !class_exists('FORM1_Admin') ) {

	class FORM1_Admin {

		private $slug = 'form1';
		private $success = '';
		private $errors = array();
		private $options = array();
		private $form = array();
		public  $fields = array();
		const DEF_MAIL_TITLE = '[Form1]お問合わせがありました。';
		const DEF_SEND_MES = 'お問い合わせを送信しました。';

		function __construct() {
			add_action('admin_menu', array($this, 'add_plugin'));
			add_filter('plugin_action_links_'. plugin_basename(FORM1_DIR.'form1.php'), array($this, 'action_link'));
			add_filter('admin_footer_text', array($this, 'footer_text'));
		}

		function add_plugin() {
			add_menu_page('Form1', 'Form1', 'administrator', 'form1_settings', array($this, 'settings'), 'dashicons-email-alt');
			add_submenu_page('form1_settings', '設定', '設定', 'administrator', 'form1_settings', array($this, 'settings'));
			add_submenu_page('form1_settings', '履歴', '履歴', 'administrator', 'form1_histories', array($this, 'history'));
		}

		function action_link($links) {
			$links[] = '<a href="'.esc_url(get_admin_url(null, 'admin.php?page=form1_settings')).'">設定</a>';
			$links[] = '<a href="'.esc_url(get_admin_url(null, 'admin.php?page=form1_histories')).'">履歴</a>';
			return $links;
		}

		function settings() {
			$F1 = FORM1();
			if ( empty($this->options) ) $this->options = $F1->options;
			if ( empty($this->fields) ) $this->fields = $F1->fields;
			$this->ctrl();
			$this->view();
		}


		function ctrl() {
			if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {//submit
				if ( $this->check() ) $this->update();
			} else {//first
				$this->form = $this->options;
			}

		}

		function view() {
?>
<div class="wrap" id="form1">
	<h2 class="title">Form1 設定</h2>
	<hr>

	<form method="post">

	<?php if ( !empty($this->success) ):?>
		<div class="updated"><p><strong><?php esc_html_e($this->success) ?></strong></p></div>
	<?php elseif ( !empty($this->errors) ): foreach ($this->errors as $err):?>
		<div class="error"><p><strong><?php esc_html_e($err);?></strong></p></div>
	<?php endforeach; endif;?>

	<h2>メール</h2>
	<table class="form-table">
		<tr>
			<th>送信先</th>
			<td><?php echo "info@example.com";//get_option('admin_email');?></td>
		</tr>
		<tr>
			<th>タイトル</th>
			<td><input type="text" name="<?php echo $this->slug;?>[mail][title]" value="<?php echo (isset($this->form['mail']['title'])) ? esc_attr($this->form['mail']['title']) : self::DEF_MAIL_TITLE;?>" placeholder="<?php echo self::DEF_MAIL_TITLE;?>" class="regular-text" maxlength="20"></td>
		</tr>
	</table>

	<hr>
	<h2>文言</h2>
	<table class="form-table">
		<tr>
			<th>送信完了時</th>
			<td><textarea name="<?php echo $this->slug;?>[mes][send]" class="large-text" rows="3"><?php echo (isset($this->form['mes']['send'])) ? esc_textarea($this->form['mes']['send']) : self::DEF_SEND_MES;?></textarea></td>
		</tr>
	</table>

	<hr>
	<h2>項目</h2>
	<table class="form-table">
	<?php foreach ($this->fields as $k => $v):?>
		<tr>
			<th><?php echo $v;?></th>
			<td>
<fieldset>
	<label>表示 <input type="checkbox" name="<?php echo $this->slug; ?>[<?php echo $k;?>][v]" value="<?php echo FORM1_VALUE_ON;?>" <?php echo (isset($this->form[$k]['v']) && FORM1_VALUE_ON==$this->form[$k]['v']) ? "checked=\"checked\"": "";?>></label>
	<label>必須 <input type="checkbox" name="<?php echo $this->slug;?>[<?php echo $k;?>][r]" value="<?php echo FORM1_VALUE_ON;?>" <?php echo (isset($this->form[$k]['r']) && FORM1_VALUE_ON==$this->form[$k]['r']) ? "checked=\"checked\"": "";?>></label><br>
<?php if ( $k == 'agree' ):?>
	<?php echo $v;?>先名 <input type="text" name="<?php echo $this->slug;?>[<?php echo $k;?>][n]" value="<?php if(isset($this->form[$k]['n'])) esc_attr_e($this->form[$k]['n']);?>" placeholder="個人情報保護方針" class="regular-text"><br>
	<?php echo $v;?>先URL <input type="text" name="<?php echo $this->slug;?>[<?php echo $k;?>][u]" value="<?php if(isset($this->form[$k]['u'])) esc_attr_e($this->form[$k]['u']);?>" class="regular-text">
<?php endif;?>
</fieldset>
			</td>
		</tr>
	<?php endforeach;?>
	</table>

	<?php submit_button();?>

	</form>
</div><!-- /.wrap -->
<div class="clear"></div>
<?php
		}

		function update() {
			$this->options = $this->form;
			update_option("form1_options", $this->options);
			$this->success = "設定を保存しました。";
		}

		function check() {
			$this->form = $_POST[$this->slug];

			if ( !isset($this->form['mail']['title']) || empty($this->form['mail']['title']) ) {
				$this->errors[] = sprintf('%sを入力してください。', "メールのタイトル");
			} elseif ( mb_strlen($this->form['mail']['title']) > 20 ) {
				$this->errors[] = sprintf('%sは%d文字以下で入力してください。', "メールのタイトル", 20);
			} else {
				$this->form['mail']['title'] = esc_html(esc_attr($this->form['mail']['title']));
			}

			if ( !isset($this->form['mes']['send']) || empty($this->form['mes']['send']) ) {
				$this->errors[] = sprintf('%sを入力してください。', "文言の送信完了時");
			} elseif ( mb_strlen($this->form['mes']['send']) > 200 ) {
				$this->errors[] = sprintf('%sは%d文字以下で入力してください。', "文言の送信完了時", 200);
			} else {
				$this->form['mes']['send'] = esc_textarea($this->form['mes']['send']);
			}

			foreach ($this->fields as $k => $v) {
				if ( !isset($this->form[$k]['v']) || $this->form[$k]['v'] != FORM1_VALUE_ON ) {
					$this->form[$k]['v'] = FORM1_VALUE_OFF;
				}
				if ( !isset($this->form[$k]['r']) || $this->form[$k]['r'] != FORM1_VALUE_ON ) {
					$this->form[$k]['r'] = FORM1_VALUE_OFF;
				}
				if ( $k == 'agree' ) {
					if ( isset($this->form[$k]['n']) && !empty($this->form[$k]['n']) ) {
						$this->form[$k]['n'] = esc_html(esc_attr($this->form[$k]['n']));
						if ( mb_strlen($this->form[$k]['n']) > 10 ) {
							$this->errors[] = sprintf('%sは%d文字以下で入力してください。', "{$v}先名", 10);
						}
					} else {
						$this->form[$k]['n'] = null;
					}
					if ( isset($this->form[$k]['u']) && !empty($this->form[$k]['u']) ) {
						$this->form[$k]['u'] = esc_html(esc_attr($this->form[$k]['u']));
						if ( mb_strlen($this->form[$k]['u']) > 100 ) {
							$this->errors[] = sprintf('%sは%d文字以下で入力してください。', "{$v}先URL", 100);
						}
					} else {
						$this->form[$k]['u'] = null;
					}
				}
			}
			return empty($this->errors);
		}

		function history() {
			require_once (FORM1_DIR.'history.php');
			$His = new FORM1_History();
			$His->run();
		}

		function footer_text($original='') {
			global $current_screen;
			if ( !is_object($current_screen) || $current_screen->parent_base != 'form1_settings' ) {
				return $original;
			}
			return sprintf(
					'%s version %s by %s',
					'Form1',
					FORM1_CURRENT_VERSION,
					'<a href="http://6oolab.com/" target="_blank">6oolab</a>'
			);
		}
	}

}