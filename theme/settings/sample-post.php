<?php
//https://matorel.com/archives/1209

function create_pages_and_setting()
{
//$pages_array[] = array('title'=>'ページタイトル', 'name'=>'スラッグ', 'parent'=>'親スラッグ');	
//例としてお問い合わせページを入力(親ページなし)
  $pages_array[] = array('title' => 'エディタのスタイルが適切に適用されるかをテストするためのテスト投稿', 'name' => 'test-post', 'parent' => '');
  foreach ($pages_array as $value) {
    setting_pages($value);
  }
}

function setting_pages($val)
{
//親ページ判別
  if (!empty($val['parent'])) {
    $parent_id = get_page_by_path($val['parent'],OBJECT,'post');
    $parent_id = $parent_id->ID;
    $page_slug = $val['parent'] . "/" . $val['name'];
  } else {
    $parent_id = "";
    $page_slug = $val['name'];
  }
  if (empty(get_page_by_path($page_slug,OBJECT,'post'))) {
//なければ作成
    $insert_id = wp_insert_post(
      array(
        'post_title' => $val['title'],
        'post_name' => $val['name'],
        'post_status' => 'publish',
        'post_type' => 'post',
        'ping_status'=>'closed',
        'comment_status'=>'closed',
        'post_parent' => $parent_id,
        'post_content' => '<!-- wp:paragraph -->
<p>この領域は段落ブロックで作っています。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>２つめの段落ブロックです。<strong>この部分に太字のスタイルを当てます</strong><br>改行して<em>斜体のスタイルを当てます</em>　きちんとスタイルは当たっていますか？</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>新しい段落でリンクのテストをします。リンクであることが伝わりますか？</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="https://www.google.co.jp/" target="_blank" rel="noreferrer noopener">https://www.google.co.jp/</a></p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>リストのスタイルは適正ですか？</li><li>リストが階層型の時、それが伝わりますか？<ul><li>第2階層リストのテスト</li><li>第2階層のテスト</li></ul></li><li>リスト</li></ul>
<!-- /wp:list -->

<!-- wp:list {"ordered":true} -->
<ol><li>数値リストの時きちんと数値は表示されますか？</li><li>数値リストのとき階層型にしても問題ありませんか？<ol><li>テスト</li><li>テスト</li><li>テスト</li></ol></li><li>テスト</li></ol>
<!-- /wp:list -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p>ブロックエディタの動作テストのため２カラムレイアウトを確認します。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>画像とテキストはきちんと横並びになりますか？</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://wpthemetestdata.files.wordpress.com/2013/03/image-alignment-1200x4002.jpg" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:heading -->
<h2>見出しレベルh2のテスト</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>大見出しのスタイルは当たりましたか？</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>見出しレベルh3のテスト</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>中見出しのスタイルは当たりましたか？</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>見出しレベルh4のテスト</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>小見出しのスタイルは当たりましたか？</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>以下に画像表示のテストをします。</p>
<!-- /wp:paragraph -->

<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://wpthemetestdata.files.wordpress.com/2013/03/image-alignment-1200x4002.jpg" alt=""/></figure>
<!-- /wp:image -->',
      )
    );
  } else {
//すでにあれば何もしない
  }
}

add_action('after_switch_theme', 'create_pages_and_setting');