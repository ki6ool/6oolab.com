=== Form1 ===
Contributors: ki6ool
Donate link: http://6oolab.com/form1
Tags: フォーム,お問合わせ,管理,確認,form,contact
Requires at least: 4.4.2
Tested up to: 4.4.2
Stable tag: trunk

お問合わせフォームを簡単に設置できます。確認画面付きで管理画面から履歴を管理できます。

== Description ==
複雑なカスタマイズや処理を必要としないサイト向けのお問い合わせフォームです。

<a href="https://github.com/ki6ool/Form1" target="_blank">詳細はこちら</a>

** ショートコード
`[form1]`

** 主な機能

* 入力項目の表示可否・必須可否の制御
* 個人情報保護などの同意先へのリンクを貼る
* フォームの確認画面
* 管理者へメール通知
* 履歴の管理

*Form1*->*設定*より各項目の表示・非表示、入力必須可否を選択して、本文内の設置したい箇所にショートコード **[form1]** を埋め込むとフォームが表示されます。

フォームはモーダルウィンドウにて動作し、画面遷移を伴いません。
確認ボタンを押下し、エラーが無い場合は確認画面が表示されます。

*Form1*->*履歴*よりお問合わせ履歴を確認できます。
削除する場合は対象履歴にチェックを入れて、一括処理から削除を選択し適用してください。
一度削除したものは復元できないので注意してください。

お問合わせがあったら、*一般設定*->*メールアドレス*宛に通知します。

== Installation ==
**プラグインをインストールする前にDBをバックアップしておくことを推奨します。**

1. *プラグイン* メニュー配下の *新規追加* をクリックしてください。
2. *Form1* で検索してください。
3. *Form1* をインストールしてください。
4. *Form1* を有効化してください。
5. *設定* メニュー配下の *Form1* より設定をご確認ください。

== Screenshots ==
1. 設定管理画面
2. 履歴管理画面
3. フォーム表示例

== Frequently Asked Questions ==
**Form1はカスタマイズを前提に作らてたものではありません。**

リプライいただければ応える*かも*しれません。
[Twitter](http://twitter.com/ki6ool/)

### 動かないです。

* header.php内に *wp_head()* が、footer.php内に *wp_footer()*が記述されているか確認してください。
* jqueryのロードが直書きではなく、wp_headからロードされているか確認してください。

### 入力項目を増やすことはできますか？

残念ながらできません。

### フォームのデザインを変更することはできますか？

残念ながらできません。

== ChangeLog ==

= Version 1.0.2 =
* 2016-03-08
* あらゆるバグを解消。

= Version 1.0.1 =
* 2016-03-03
* 説明文などを修正。

= Version 1.0.0 =
* 2016-03-03
* リリースしました。