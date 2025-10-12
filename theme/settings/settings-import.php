<?php


/*------------------------------------*\
    固定ページ関連 page
\*------------------------------------*/

//親ページ判定コード　is_parent_slug()
//if (is_parent_slug()=="recruit") のように使う

function is_parent_slug() {
  global $post;
  if($post->post_parent) {
    $post_data=get_post($post->post_parent);
    return $post_data->post_name;
  }
}

/*------------------------------------*\
    ウィジェット機能登録 Widget
\*------------------------------------*/
//ウィジェットを非表示
function unregister_default_widget() {
  unregister_widget('WP_Widget_Pages');            // 固定ページ
  unregister_widget('WP_Widget_Calendar');         // カレンダー
  unregister_widget('WP_Widget_Meta');             // メタ情報
  unregister_widget('WP_Widget_Recent_Comments');  // 最近のコメント
  unregister_widget('WP_Widget_RSS');              // RSS
  unregister_widget('WP_Widget_Tag_Cloud');        // タグクラウド
  unregister_widget('WP_Widget_Media_Video');        //動画
  unregister_widget('WP_Widget_Media_Gallery');        //ギャラリー
  unregister_widget('WP_Widget_Media_Image');        //画像
  unregister_widget('WP_Widget_Media_Audio');        //音声
}

add_action('widgets_init', 'unregister_default_widget');

// If Dynamic Sidebar Exists
if(function_exists('register_sidebar')) {
  // ウィジェットエリア1を定義
  register_sidebar(array('name'=>'サイドバーウィジェット', 'id'=>'widget-sidebar', 'description'=>'サイドバー用のウィジェット（sidebar.phpで使用）', 'before_widget'=>'<div id="%1$s" class="%2$s">',//ウィジェットエリアの囲み
      'after_widget'=>'</div>',//ウィジェットエリアの囲み終了
      'before_title'=>'<h3>',//ウィジェットエリアのタイトル囲み
      'after_title'=>'</h3>'//ウィジェットエリアのタイトル囲み終了
  ));
}
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)


/*------------------------------------*\
    各ページ共通の機能   common
\*------------------------------------*/
// global-stylesのインラインCSS出力を排除する
function remove_global_styles() {
  wp_dequeue_style('global-styles');
}

add_action('wp_enqueue_scripts', 'remove_global_styles');

// ディスカッション設定でコメントのスレッド表示が許可されていれば、/wp-includes/js/comment-reply.js を読み込む
function enable_threaded_comments() {
  if(!is_admin()) {
    if(is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
      wp_enqueue_script('comment-reply');
    }
  }
}

add_action('get_header', 'enable_threaded_comments');

// the_category() の出力のrel="category tag"はinvalidになるので、rel="tag"に変換
function remove_category_rel_from_category_list($thelist) {
  return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute

// Wordpressが生成するhtmlにHTML5タグの仕様を許可
add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));

/*------------------------------------*\
    管理画面・ログイン時の動作 admin
https://for-someone.com/blog/4963/
\*------------------------------------*/
// Admin bar（ログイン中に出る黒いバー）を消すか？
function remove_admin_bar() {
  return false;//消したいときは true にする
}

add_filter('show_admin_bar', 'remove_admin_bar');


//管理者メールの確認を実施せずに、メールアドレス変更
/**
 * @see http://codex.wordpress.com/Function_Reference/update_option_new_admin_email
 */
remove_action('add_option_new_admin_email', 'update_option_new_admin_email');
remove_action('update_option_new_admin_email', 'update_option_new_admin_email');
function wpdocs_update_option_new_admin_email($old_value, $value) {
  update_option('admin_email', $value);
}

add_action('add_option_new_admin_email', 'wpdocs_update_option_new_admin_email', 10, 2);
add_action('update_option_new_admin_email', 'wpdocs_update_option_new_admin_email', 10, 2);

//テーマの更新通知をしない
remove_action('load-update-core.php', 'wp_update_themes');
add_filter('pre_site_transient_update_themes', function($a) { return null; });

/*------------------------------------*\
	headタグ内に表示する情報の設定 Remove Actions
\*------------------------------------*/
remove_action('wp_head', 'rsd_link'); // EditURI linkを消す
remove_action('wp_head', 'wlwmanifest_link'); //  Windows Live Writerのリンクを消す
remove_action('wp_head', 'index_rel_link'); // Index linkを消す
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev linkを消す
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start linkを消す
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'wp_render_title_tag', 1);

// テキストエディタの絵文字に対応する為の各種出力を排除する
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

// WordPressのバージョン出力を排除する
remove_action('wp_head','wp_generator');

// JS, CSS要素のバージョン出力を排除する
function remove_cssjs_ver2( $src ) {
  if ( strpos( $src, 'ver=' ) )
    $src = remove_query_arg( 'ver', $src );
  return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver2', 9999 );
add_filter( 'script_loader_src', 'remove_cssjs_ver2', 9999 );

//RSSの出力をON
//add_theme_support('automatic-feed-links');の代わり、コメントフィード削除版
add_action('wp_head', function() {
  printf('<link rel="alternate" type="application/rss+xml" title="%s" href="%s">%s', get_bloginfo('name'), get_bloginfo('rss2_url'), "\n");
});