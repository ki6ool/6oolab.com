<?php /*
**************************************************************************
Plugin Name: Form1
Plugin URI: https://github.com/ki6ool/Form1
Description: シンプルなお問い合わせフォームを設置します。履歴も管理できます。
Version: 1.0.2
Author: 6oolab
Author URI: http://6oolab.com/
**************************************************************************
Thanks to:
* UIkit: http://getuikit.com/
* ajaxzip3: https://github.com/ajaxzip3/ajaxzip3.github.io
**************************************************************************/

if ( !defined('FORM1_CURRENT_VERSION') ) {
	define('FORM1_CURRENT_VERSION', '1.0.2');
}

if ( !class_exists('Form1') ) {

	class Form1 {

		private static $instance = null;
		public $options = null;
		public $frontend = null;
		public $backend = null;
		public $action = null;
		public $history = null;
		const OPTION_NAME = 'form1_options';

		public $fields = array(
				'name'  => '名前',
				'kana'  => 'フリガナ',
				'cname' => '会社名',
				'email' => 'メールアドレス',
				'zip'   => '郵便番号',
				'pref'  => '都道府県',
				'addr'  => '住所',
				'tel'   => '電話番号',
				'text'  => 'お問い合わせ内容',
				'agree' => '同意',
		);

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
				self::$instance->setup();
				self::$instance->options = get_option(self::OPTION_NAME);
			}
			return self::$instance;
		}

		function setup() {
			if ( function_exists('register_activation_hook') ) {
				register_activation_hook(__FILE__, array(self::$instance, 'activation'));
			}
			if ( function_exists('register_uninstall_hook') ) {
				register_uninstall_hook(__FILE__, array(self::$instance, 'uninstall'));
			}

			if ( !defined('FORM1_DIR') ) {
				define('FORM1_DIR', plugin_dir_path(__FILE__));
			}
			if ( !defined('FORM1_URL') ) {
				define('FORM1_URL', str_replace(home_url(), '', plugin_dir_url(__FILE__)));
			}
			if ( !defined('FORM1_VALUE_ON') ) {
				define('FORM1_VALUE_ON', '1');
			}
			if ( !defined('FORM1_VALUE_OFF') ) {
				define('FORM1_VALUE_OFF', '0');
			}

			add_action('init', array(self::$instance, 'load'));
		}

		function load() {
			if ( is_admin() ) {
				require_once (FORM1_DIR.'admin.php');
				self::$instance->backend = new FORM1_Admin();
			} else {
				require_once (FORM1_DIR.'front.php');
				self::$instance->frontend = new FORM1_Front();
			}
			if ( defined('DOING_AJAX') && DOING_AJAX ) {
				require_once (FORM1_DIR.'action.php');
				self::$instance->action = new FORM1_Action();
			}
		}

		function activation() {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$query = <<<EOT
CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}form1` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data` mediumtext,
  `created` DATETIME NULL
) {$charset_collate};
EOT;
			require_once (ABSPATH.'wp-admin/includes/upgrade.php');
			dbDelta($query);
		}

		function uninstall() {
			global $wpdb;
			$query = "DROP TABLE IF EXISTS `{$wpdb->prefix}form1`";
			$wpdb->query($wpdb->prepare($query, null));
			delete_option('form1_options');
		}

	}
}

function FORM1() {
	return FORM1::instance();
}

FORM1();