<?php /*
**************************************************************************
Plugin Name: in6ool
Plugin URI: http://6oolab.com/
Description: ページやパーツの表示を管理します。
Version: 1.0.6
Author: 6oolab
Author URI: http://6oolab.com/
**************************************************************************/

class in6ool {

	const NAME = 'in6ool';
	const VERSION = '1.0.6';
	const FORM_KEY = 'in6ool_options';
	const VALUE_ON = 1;
	const VALUE_OFF = 0;
	const TEXT_MAXLENGTH = 50;
	private $options;
	private $success;
	private $errors;
	private $data;
	private $dashboard = array(
			'wp_welcome_panel' => 'WordPressへようこそ！',
			'dashboard_activity' => 'アクティビティ',
			'dashboard_recent_comments' => '最近のコメント',
			'dashboard_incoming_links' => '被リンク',
			'dashboard_plugins' => 'プラグイン',
			'dashboard_quick_press' => 'クイック投稿',
			'dashboard_recent_drafts' => '最近の下書き',
			'dashboard_primary' => 'WordPressブログ',
			'dashboard_secondary' => 'WordPressフォーラム',
	);
	private $postwidget = array(
			'postcustom' => 'カスタムフィールド',
			'postexcerpt' => '抜粋',
			'commentstatusdiv' => 'ディスカッション',
			'commentsdiv' => 'コメント設定',
			'trackbacksdiv' => 'トラックバック設定',
			'revisionsdiv' => 'リビジョン表示',
			'formatdiv' => 'フォーマット設定',
			'slugdiv' => 'スラッグ設定',
			'authordiv' => '投稿者',
			'categorydiv' => 'カテゴリー',
			'tagsdiv-post_tag' => 'タグ',
	);

	function __construct() {
		$this->options = get_option($this::FORM_KEY);
		if ( is_admin() ) {
			add_action('admin_menu', array($this, 'add_plugin'));
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'action_link'));
			add_filter('admin_footer_text', array($this, 'in6ool_footer_text'));
		}
		add_action('init', array($this, 'run'));
	}

	function add_plugin() {
		add_options_page('in6ool', 'in6ool', 'administrator', 'in6ool', array($this, 'dispatch'));

	}

	function check() {
		$form = $_POST[$this::FORM_KEY];
		foreach ($this->data as $k => $v) {
			switch ( $v['type'] ) {
				case 'radio':
					$this->options[$k] = ( isset($form[$k]) && isset($v['options'][$form[$k]]) ) ? $form[$k] : $this::VALUE_OFF;
					break;
				case 'text':
					if ( empty($form[$k]) ) {
						$this->options[$k] = "";
					} else {
						$this->options[$k] = esc_attr($form[$k]);
						if ( mb_strlen($this->options[$k]) > $this::TEXT_MAXLENGTH ) {
							$this->errors[] = sprintf('%sは%d文字以下で入力してください。', $v['label'], $this::TEXT_MAXLENGTH);
						}
					}
					break;
				case 'checkbox':
					$this->options[$k] = empty($form[$k])  ? array() : $form[$k];
					break;
				default:
					break;
			}
		}
		return empty($this->errors);
	}

	function update() {
		update_option($this::FORM_KEY, $this->options);
		$this->success = "設定を保存しました。";
	}

	function page_init() {
		$this->errors = array();
		$this->success = "";
		$this->set_data();

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' && $this->check() ) {//submit
			$this->update();
		}
	}

	function dispatch() {
		$this->page_init();
		$this->header();

		if ( version_compare(PHP_VERSION, '5.5') >= 0 ) {
			$this->form();
		} else {
			$this->unavailable();
		}
		$this->footer();
	}

	function unavailable() {
?>
PHPのバージョンが5.5未満のサーバーでは本プラグインは正常に動作しません。<br>
残念ですが、プラグインを停止して削除してください。
<?php
	}

	function form() {
?>
<style>
#in6ool .switcher input { display: none; }
#in6ool .switcher label { display: block; float: left; cursor: pointer; width: 50px; margin: 0; padding: 5px; background: #bdc3c7; color: #869198; text-align: center; line-height: 1; transition: .2s; }
#in6ool .switcher label:first-of-type { border-radius: 2px 0 0 2px; }
#in6ool .switcher label:last-of-type { border-radius: 0 2px 2px 0; }
#in6ool .switcher input[type="radio"]:checked + .switch-1 { background-color: #e67168; color: #fff; }
#in6ool .switcher input[type="radio"]:checked + .switch-0 { background-color: #00a0d2; color: #fff; }
#in6ool input[type="text"] { width: 100%; }
</style>
<table class="wp-list-table widefat fixed striped">
	<col width="25%">
<?php
foreach ($this->data as $k => $v):
	$v['value'] = isset($this->options[$k]) ? $this->options[$k] : "";
?>
	<tr>
		<th><?php echo $v['label'];?></th>
		<td>
<?php switch ( $v['type'] ):?>
<?php case 'radio':?>
<div class="switcher">
<?php foreach ($v['options'] as $ok => $ov):?>
<input type="radio" name="<?php echo $this::FORM_KEY;?>[<?php echo $k;?>]" id="<?php echo "{$k}_{$ok}";?>" value="<?php esc_attr_e($ok);?>" <?php echo ($ok==$v['value']) ? "checked=\"checked\"": "";?>>
<label for="<?php echo "{$k}_{$ok}";?>" class="<?php echo "switch-{$ok}";?>"><?php echo $ov;?></label>
<?php endforeach;?>
</div>
<?php break;?>
<?php case 'text':?>
<input type="text" name="<?php echo $this::FORM_KEY;?>[<?php echo $k;?>]" id="<?php echo $k;?>" value="<?php esc_attr_e($v['value']);?>" maxlength="50" placeholder="<?php echo $v['placeholder'];?>">
<?php break;?>
<?php case 'checkbox':?>
<ul>
<?php foreach ($v['options'] as $ok => $ov):?>
<li><label><input type="checkbox" name="<?php echo $this::FORM_KEY;?>[<?php echo $k;?>][]" id="<?php echo "{$k}_{$ok}";?>" value="<?php esc_attr_e($ok);?>" <?php echo (is_array($v['value']) && in_array($ok, $v['value'])) ? "checked=\"checked\"": "";?>> <?php echo $ov;?></label></li>
<?php endforeach;?>
</ul>
<?php break;?>
<?php endswitch;?>
		</td>
	</tr>
<?php endforeach;?>
</table>
<?php
		submit_button();
	}

	function header() {
?>
<div class="wrap" id="<?php echo $this::NAME;?>">
	<h2><?php echo $this::NAME;?></h2>
	<form method="post">
	<?php if ( !empty($this->success) ):?>
		<div class="updated"><p><strong><?php esc_html_e($this->success) ?></strong></p></div>
	<?php endif;?>
	<?php if ( !empty($this->errors) ): foreach ($this->errors as $err):?>
		<div class="error"><p><strong><?php esc_html_e($err);?></strong></p></div>
	<?php endforeach; endif;?>
<?php
	}
	function footer() {
?>
	</form>
</div><!-- /.wrap -->
<div class="clear"></div>
<?php
	}

	function set_data() {
		$this->data = array(
				'headers' => array(
						'label' => 'head内の諸々',
						'type' => 'radio',
						'options' => array($this::VALUE_OFF => '表示', $this::VALUE_ON => '非表示'),
				),
				'admin_bar' => array(
						'label' => '公開側での管理者バー',
						'type' => 'radio',
						'options' => array($this::VALUE_OFF => '表示', $this::VALUE_ON => '非表示'),
				),
				'help' => array(
						'label' => 'ヘルプ',
						'type' => 'radio',
						'options' => array($this::VALUE_OFF => '表示', $this::VALUE_ON => '非表示'),
				),
				'date' => array(
						'label' => '日付アーカイブ',
						'type' => 'radio',
						'options' => array($this::VALUE_OFF => '表示', $this::VALUE_ON => '非表示'),
				),
				'author' => array(
						'label' => '著者アーカイブ',
						'type' => 'radio',
						'options' => array($this::VALUE_OFF => '表示', $this::VALUE_ON => '非表示'),
				),
				'feed' => array(
						'label' => 'フィード',
						'type' => 'radio',
						'options' => array($this::VALUE_OFF => '表示', $this::VALUE_ON => '非表示'),
				),
				'feed_author' => array(
						'label' => 'フィードの著者名',
						'type' => 'radio',
						'options' => array($this::VALUE_OFF => '表示', $this::VALUE_ON => '非表示'),
				),
				'pingback' => array(
						'label' => 'Pingback',
						'type' => 'radio',
						'options' => array($this::VALUE_OFF => '有効', $this::VALUE_ON => '無効'),
				),
				'footer_text' => array(
						'label' => '管理画面フッターテキスト',
						'type' => 'text',
						'placeholder' => 'Developed by 6oolab',
				),
				'dashboard' => array(
						'label' => 'ダッシュボード非表示',
						'type' => 'checkbox',
						'options' => $this->dashboard,
				),
				'postwidget' => array(
						'label' => '投稿ウィジェット非表示',
						'type' => 'checkbox',
						'options' => $this->postwidget,
				),
		);
	}

	/**
	 * 設定を反映
	 */
	function run() {
		if ( isset($this->options['headers']) && $this->options['headers'] ) {
			remove_action('wp_head', 'wp_generator');
			remove_action('wp_head', 'rsd_link');
			remove_action('wp_head', 'wlwmanifest_link');
			remove_action('wp_head', 'wp_shortlink_wp_head');
			remove_action('wp_head', 'feed_links_extra', 3);
			remove_action('wp_head', 'feed_links', 2);
			remove_action('wp_head', 'index_rel_link');
			remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
			remove_action('wp_head', 'parent_post_rel_link', 10, 0);
			remove_action('wp_head', 'start_post_rel_link', 10, 0);
			remove_action('wp_head', 'rel_canonical');
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('wp_print_styles', 'print_emoji_styles');
			remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
			remove_action('rest_api_init', 'wp_oembed_register_route');
			remove_action('wp_head', 'wp_oembed_add_discovery_links');
			remove_action('wp_head', 'wp_oembed_add_host_js');
			remove_action('wp_head', 'rest_output_link_wp_head');
			remove_action('wp_head', 'wp_oembed_add_discovery_links');
			remove_action('wp_head', 'wp_oembed_add_host_js');
			remove_action('template_redirect', 'rest_output_link_header', 11 );
		}
		if ( isset($this->options['admin_bar']) && $this->options['admin_bar'] ) {
			add_filter('show_admin_bar', '__return_false');
		}
		if ( isset($this->options['help']) && $this->options['help'] ) {
			add_action('admin_head', function() { echo '<style type="text/css">#contextual-help-link-wrap {display: none !important;}</style>'; });
		}
		add_action('parse_query', function() {
			if ( isset($this->options['date']) && $this->options['date'] && is_date() && !is_admin() ) {
				$this->die_404();
			}
		});
		add_action('parse_query', function() {
			if ( isset($this->options['author']) && $this->options['author'] && is_author() && !is_admin() ) {
				$this->die_404();
			}
		});
		add_action('parse_query', function() {
			if ( isset($this->options['feed']) && $this->options['feed'] && is_feed() ) {
				$this->die_404();
			}
		});
		add_filter('the_author', function($name) {
			return ( isset($this->options['feed_author']) && $this->options['feed_author'] & is_feed() ) ? false : $name;
		});
		add_filter('xmlrpc_methods', function($methods) {
			if ( isset($this->options['pingback']) && $this->options['pingback'] ) {
				unset($methods['pingback.ping']);
				unset($methods['pingback.extensions.getPingbacks']);
			}
			return $methods;
		});
		add_filter('wp_headers', function($headers) {
			if ( isset($this->options['pingback']) && $this->options['pingback'] ) {
				unset($headers['X-Pingback']);
			}
			return $headers;
		});
		if ( isset($this->options['footer_text']) && !empty($this->options['footer_text']) && is_admin() ) {
			add_filter('admin_footer_text', function() { return esc_html($this->options['footer_text']); });
		}
		if ( isset($this->options['dashboard']) && !empty($this->options['dashboard']) && is_admin() ) {
			add_action('wp_dashboard_setup', function() {
				global $wp_meta_boxes;
				foreach ($this->options['dashboard'] as $v) {
					if ( $v == 'wp_welcome_panel' ) {
						remove_action('welcome_panel', 'wp_welcome_panel');
					} else {
						unset($wp_meta_boxes['dashboard']['normal']['core'][$v]);
						unset($wp_meta_boxes['dashboard']['side']['core'][$v]);
					}
				}
			});
		}
		if ( isset($this->options['postwidget']) && !empty($this->options['postwidget']) && is_admin() ) {
			add_action('admin_menu', function() {
				foreach ($this->options['postwidget'] as $v) {
					remove_meta_box($v, 'post', 'normal');
				}
			});
		}
	}

	function die_404() {
		$blogname = get_option('blogname');
		wp_die(
			'<a href="'.esc_url(home_url('/')).'">'.$blogname.'</a>',
			$blogname,
			array('response' => 404, "back_link" => true)
		);
	}

	function action_link($links) {
		$links[] = '<a href="'.esc_url(get_admin_url(null, 'options-general.php?page=in6ool')).'">設定</a>';
		return $links;
	}

	function in6ool_footer_text($original='') {
		global $current_screen;
		if ( !is_object($current_screen) || 'settings_page_in6ool' != $current_screen->base ) {
			return $original;
		}
		return sprintf(
				'%s version %s by %s',
				'in6ool',
				$this::VERSION,
				'<a href="http://6oolab.com/" target="_blank">6oolab</a>'
		);
	}

}// class

$in6ool = new in6ool();