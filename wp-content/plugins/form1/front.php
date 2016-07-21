<?php
if ( !class_exists('FORM1_Front') ) {

	final class FORM1_Front {

		private $options = array();
		public  $fields = array();
		public  $pref = array(
				'北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県',
				'埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県',
				'岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
				'鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県',
				'佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県',
		);

		function __construct() {
			$F1 = FORM1();
			if ( empty($this->options) ) $this->options = $F1->options;
			if ( empty($this->fields) ) $this->fields = $F1->fields;
			if ( empty($this->options) ) return;
			add_shortcode('form1', array($this, 'form1_callback'));
		}

		function form1_callback() {
			wp_enqueue_style('uikit-css', '//cdnjs.cloudflare.com/ajax/libs/uikit/2.23.0/css/uikit.min.css');
			wp_enqueue_script('uikit-js', '//cdnjs.cloudflare.com/ajax/libs/uikit/2.23.0/js/uikit.min.js', array('jquery'), false, true);
			wp_enqueue_script('form1-front', FORM1_URL.'front.js', array('jquery', 'uikit-js'), false, true);
			wp_localize_script('form1-front', 'F1', array(
				'url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('form1'),
				'confirm'  => 'form1_confirm',
				'send'  => 'form1_send',
			));
			return $this->template();
		}

		private function template() {
			ob_start();
			$this->form();
			$this->modal();
			$template = ob_get_contents();
			ob_end_clean();
			return $template;
		}

		function form() {
?>
<form method="post" class="uk-form uk-form-horizontal" id="form1_form">
<?php
foreach ($this->options as $ok => $ov):
	if ( !isset($ov['v']) || $ov['v'] != FORM1_VALUE_ON ) continue;
?>
<div class="uk-form-row">
	<label class="uk-form-label" for="form1_<?php echo $ok;?>">
		<?php if ($ov['r']==FORM1_VALUE_ON):?><i class="uk-icon-check-circle-o">&nbsp;</i><?php endif;?>
<?php
	if ( $ok == 'agree' ) {
		printf('<a href="%s" target="_blank">%s</a>', $ov['u'], $ov['n']);
	} else {
		echo $this->fields[$ok];
	}
?>
	</label>
	<div class="uk-form-controls uk-form-controls-text">
<?php if ( in_array($ok, array('name', 'kana', 'cname', 'email', 'addr', 'tel')) ):?>
		<input type="text" name="form1[<?php echo $ok;?>]" id="form1_<?php echo $ok;?>" value="" class="uk-width-1-1">
<?php elseif ( $ok == 'zip' ):?>
		<input type="text" name="form1[<?php echo $ok;?>]" id="form1_<?php echo $ok;?>" value="" maxlength="7">
		<button id="zip_search" type="button" class="uk-button">住所検索</button>
		<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
		<script type="text/javascript">$(function(){$('#zip_search').click(function(){AjaxZip3.zip2addr('form1[zip]', '', 'form1[pref]', 'form1[addr]');});});</script>
<?php elseif ( $ok == 'pref' ):?>
		<select name="form1[<?php echo $ok;?>]" id="form1_<?php echo $ok;?>">
			<option value="">選択して下さい</option>
		<?php foreach ($this->pref as $p):?>
			<option value="<?php echo $p;?>"><?php echo $p;?></option>
		<?php endforeach;?>
		</select>
<?php elseif ( $ok == 'text' ):?>
		<textarea name="form1[<?php echo $ok;?>]" id="form1_<?php echo $ok;?>" class="uk-width-1-1" maxlength="1000"></textarea>
<?php elseif ( $ok == 'agree' ):?>
		<label><input type="checkbox" name="form1[<?php echo $ok;?>]" id="form1_<?php echo $ok;?>" value="<?php esc_attr_e(FORM1_VALUE_ON);?>"> 同意する</label>
<?php endif;?>
	</div>
</div><!-- /.uk-form-row -->
<?php endforeach;?>

	<button class="uk-button uk-width-1-1 uk-button-primary uk-margin" type="submit">確認する</button>

</form>
<?php
		}

		private function modal() {
?>
<div class="uk-modal" id="form1_modal">
    <div class="uk-modal-dialog uk-modal-dialog-large">
    	<div class="uk-modal-header">入力内容の確認</div>
        <div class="uk-text-center"><i class="uk-icon-spinner uk-icon-spin uk-icon-large"></i></div>
        <div class="uk-modal-content"></div>
        <div class="uk-modal-footer uk-clearfix">
			<button id="form1_btn_cancel" type="button" class="uk-button uk-float-left">キャンセル</button>
			<button id="form1_btn_send" type="button" class="uk-button uk-button-primary uk-float-right" disabled>送信する</button>
		</div>
    </div>
</div>
<?php
		}

	}

}