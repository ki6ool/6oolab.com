<?php
if ( !class_exists('FORM1_Action') ) {

	final class FORM1_Action {

		private $data = array();
		private $response = array();

		function __construct() {
			add_action('wp_ajax_form1_confirm', array($this, 'form1_confirm'));
			add_action('wp_ajax_nopriv_form1_confirm', array($this, 'form1_confirm'));
			add_action('wp_ajax_form1_send', array($this, 'form1_send'));
			add_action('wp_ajax_nopriv_form1_send', array($this, 'form1_send'));
		}

		function form1_confirm() {
			check_ajax_referer('form1');
			nocache_headers();

			global $F1;
			$F1 = FORM1();
			$this->set_data();

			if ( $this->check() ) {//ok
				$this->set_response();
				wp_send_json_success($this->response);
			} else {//error
				wp_send_json_error($this->errors);
			}
		}

		function set_response() {
			global $F1;
			$fields = $F1->fields;
			foreach ($this->data as $dk => $dv) {
				if ( $dk == 'agree' ) continue;
				$this->response[$fields[$dk]] = esc_html($dv);
			}
		}

		function set_data() {
			parse_str($_POST['form'], $form);
			$this->data = $form['form1'];
		}

		function check() {
			global $F1;
			$options = $F1->options;
			$fields = $F1->fields;
			foreach ($options as $ok => $ov) {
				if ( !isset($ov['v']) || $ov['v'] != FORM1_VALUE_ON ) continue;
				if ( $ov['r'] == FORM1_VALUE_ON ) {//required
					if ( !isset($this->data[$ok]) || empty($this->data[$ok]) ) {
						$this->errors[] = sprintf("%sは必須項目です。", $fields[$ok]);
					}
				}
				if ( isset($this->data[$ok]) && !empty($this->data[$ok]) ) {
					if ( in_array($ok, array('name', 'cname')) && mb_strlen($this->data[$ok]) > 20 ) {
						$this->errors[] = sprintf("%sは20文字以内で入力してください。", $fields[$ok]);
					} elseif ( $ok == 'kana' ) {
						if ( mb_strlen($this->data[$ok]) > 20 ) {
							$this->errors[] = sprintf("%sは20文字以内で入力してください。", $fields[$ok]);
						} elseif ( !preg_match("/^[ァ-ヶー]+$/u", $this->data[$ok]) ) {
							$this->errors[] = sprintf("%sは全角カナで入力してください。", $fields[$ok]);
						}
					} elseif ( $ok == 'email' ) {
						if ( mb_strlen($this->data[$ok]) > 100 ) {
							$this->errors[] = sprintf("%sは100文字以内で入力してください。", $fields[$ok]);
						} elseif ( !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\+._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $this->data[$ok]) ) {
							$this->errors[] = sprintf("%sを正しく入力してください。", $fields[$ok]);
						}
					} elseif ( $ok == 'zip' ) {
						if ( !preg_match("/(^\d{7}$)/", $this->data[$ok]) ) {
							$this->errors[] = sprintf("%sを正しく入力してください。", $fields[$ok]);
						}
					} elseif ( $ok == 'addr' ) {
						if ( mb_strlen($this->data[$ok]) > 100 ) {
							$this->errors[] = sprintf("%sは100文字以内で入力してください。", $fields[$ok]);
						}
					} elseif ( $ok == 'tel' ) {
						if ( !preg_match("/\A0[0-9]{9,10}\z/", $this->data[$ok]) ) {
							$this->errors[] = sprintf("%sを正しく入力してください。", $fields[$ok]);
						}
					} elseif ( $ok == 'text' ) {
						if ( mb_strlen($this->data[$ok]) > 1000 ) {
							$this->errors[] = sprintf("%sは1000文字以内で入力してください。", $fields[$ok]);
						}
					}
					$this->data[$ok] = esc_html($this->data[$ok]);
				}
			}
			return empty($this->errors);
		}

		function form1_send() {
			check_ajax_referer('form1');
			nocache_headers();

			global $F1;
			$F1 = FORM1();
			$this->set_data();

			if ( $this->check() ) {//ok
				if ( $this->save_data() ) {//save
					$this->set_response();
					if ( $this->send_mail() ) {//mail
						$options = $F1->options;
						wp_send_json_success(explode("\n", $options['mes']['send']));
					}
					$this->errors[] = 'お問い合わせの送信に失敗しました。';
				}
				$this->errors[] = 'エラーが発生しました。';
			}
			wp_send_json_error($this->errors);
		}

		private function save_data() {
			$data = array(
				'data' => serialize($this->data),
				'created' => date('Y-m-d H:i:s'),
			);
			global $wpdb;
			return $wpdb->insert("{$wpdb->prefix}form1", $data);

		}

		private function send_mail() {
			global $F1;
			$message = "";
			foreach ($this->response as $rk => $rv) {
				if ( empty($rv) ) continue;
				$message .= "【{$rk}】\n{$rv}\n\n";
			}
			$message .= "==============================\nForm1 by 6oolab ( http://6oolab.com/ )";

			$options = $F1->options;
			return wp_mail(get_option('admin_email'), $options['mail']['title'], $message);
		}

	}

}