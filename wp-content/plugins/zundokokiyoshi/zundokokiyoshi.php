<?php /*
**************************************************************************
Plugin Name: ZundokoKiyoshi
Plugin URI: http://6oolab.com/
Description: 例のズンドコキヨシを表示します。
Version: 1.0.0
Author: 6oolab
Author URI: http://6oolab.com/
**************************************************************************
元ネタ: https://twitter.com/kumiromilk/status/707437861881180160
まとめ: http://qiita.com/shunsugai@github/items/971a15461de29563bf90
**************************************************************************/

class ZundokoKiyoshi {
	private $ZUNDOKO = ['ズン', 'ドコ'];
	private $PHRASE = '';

	function __construct() {
		do {
			$step = array_map(array($this, 'section'), range(0, 4));
		} while ('00001' != implode('', $step));
		printf('%s<strong>%s</strong>', $this->PHRASE, 'キ・ヨ・シ！');
	}

	private function section($array) {
		$rand = rand(0, 1);
		$this->PHRASE .= $this->ZUNDOKO[$rand];
		return $rand;
	}

}