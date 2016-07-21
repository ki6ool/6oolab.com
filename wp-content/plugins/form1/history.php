<?php
if ( !class_exists('WP_List_Table') ) {
	require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}

if ( !class_exists('FORM1_History') ) {
	final class FORM1_History extends WP_List_Table {

		private $success = '';
		private $error = '';
		private $row = array();

		function __construct(){
			parent::__construct( array(
					'singular'  => 'history',
					'plural'    => 'histories',
					'ajax'      => false,
			));
		}

		function column_default($item, $column_name) {
			switch ($column_name) {
				case 'ID':
					return $item['ID'];
				case 'created':
					return $item['created'];
				default:
					$data = maybe_unserialize($item['data']);
					return isset($data[$column_name]) ? $data[$column_name] : null;
			}
		}

		function column_id($item) {
			return sprintf('<a href="?page=%s&action=%s&history=%s">%s</a>',
					$_REQUEST['page'],
					'detail',
					$item['ID'],
					$item['ID']
			);
		}

		function column_cb($item) {
			return sprintf(
					'<input type="checkbox" name="%1$s[]" value="%2$s" />',
					$this->_args['singular'],
					$item['ID']
			);
		}

		function get_columns() {
			$columns = array(
					'cb' => '<input type="checkbox" />',
					'ID' => 'ID',
					'name' => '名前',
					'email' => 'メールアドレス',
					'created' => '日時',
			);
			return $columns;
		}


		function get_sortable_columns() {
			$sortable_columns = array(
					'ID' => array('ID', true),
					'created' => array('created', true),
			);
			return $sortable_columns;
		}

		function get_bulk_actions() {
			$actions = array(
					'delete' => '削除'
			);
			return $actions;
		}

		private function get_histories() {
			global $wpdb;
			$table = "{$wpdb->prefix}form1";
			if ( isset($_REQUEST['s']) ) {
				$query = "SELECT * FROM {$table} WHERE data LIKE '%%%s%%'";
				$query = $wpdb->prepare($query, esc_attr($_REQUEST['s']));
			} else {
				$query = "SELECT * FROM {$table}";
			}
			return $wpdb->get_results($query, ARRAY_A);
		}

		function prepare_items() {
			$per_page = 20;

			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array($columns, $hidden, $sortable);

			$data = $this->get_histories();

			usort($data, function ($a,$b) {
				$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID';
				$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
				$result = strcmp($a[$orderby], $b[$orderby]);
				return ($order==='asc') ? $result : -$result;
			});

			$current_page = $this->get_pagenum();
			$total_items = count($data);
			$data = array_slice($data,(($current_page-1)*$per_page),$per_page);
			$this->items = $data;

			$this->set_pagination_args(array(
					'total_items' => $total_items,
					'per_page'    => $per_page,
					'total_pages' => ceil($total_items/$per_page)
			));
		}

		function run() {
			$this->do_action();
			switch ($this->current_action()) {
				case 'detail':
					empty($this->error) ? $this->render_detail() : $this->render_list();
					break;
				default:
					$this->render_list();
					break;
			}

		}

		private function do_action() {
			global $wpdb;
			switch ($this->current_action()) {
				case 'detail':
					$id = isset($_REQUEST['history']) ? esc_attr($_REQUEST['history']) : 0;
					$query = "SELECT * FROM {$wpdb->prefix}form1 WHERE ID = '%d'";
					$this->row = $wpdb->get_row($wpdb->prepare($query, $id), ARRAY_A);
					if (empty($this->row)) $this->error = '対象の履歴データが見つかりませんでした。';
					break;
				case 'delete':
					if ( isset($_REQUEST['history']) ) {
						$ids = array_map('intval', $_REQUEST['history']);
						$deleted = 0;
						foreach ($ids as $id) {
							$del = $wpdb->delete("{$wpdb->prefix}form1", array('ID' => $id), '%d');
							if ($del) $deleted++;
						}
						$this->success = "{$deleted}件の履歴を削除しました。";
					}
					break;
				default:
					return;
			}
		}

		 private function render_list() {
			$this->prepare_items();
?>
<div class="wrap">
	<h2>Form1 履歴</h2>
	<form id="posts-filter" method="get">
	<input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']);?>" />
<?php if ( !empty($this->success) ):?>
		<div class="updated"><p><?php esc_html_e($this->success) ?></p></div>
<?php elseif ( !empty($this->error) ):?>
		<div class="error"><p><?php esc_html_e($this->error);?></p></div>
<?php endif;?>
<?php $this->search_box('検索', 'data');?>
<?php $this->display();?>
	</form>
</div>
<?php
		}

		private function render_detail() {
			$data = maybe_unserialize($this->row['data']);
			if ( empty($data) || !is_array($data) ) return;
			$F1 = FORM1();
?>
<div class="wrap">
	<h2 style="margin-bottom: 10px;">Form1 詳細</h2>
<table class="wp-list-table widefat fixed striped">
<?php foreach ($data as $k => $v):?>
<tr>
	<th><?php echo $F1->fields[$k];?></th>
	<td><?php
$v = esc_html($v);
if ( $k=='text' ) {
	echo nl2br($v);
} elseif ( $k=='agree' ) {
	echo ($v==FORM1_VALUE_ON) ? '同意する' : '同意しない';
} else {
	echo $v;
}
?></td>
</tr>
<?php endforeach;?>
</table>
</div>
<?php
		}

	}
}
