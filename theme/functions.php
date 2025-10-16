<?php
load_theme_textdomain('origintheme', get_template_directory() . '/languages');

/*------------------------------------*\
	外部のファイル・モジュールの読み込み External files
\*------------------------------------*/
//カスタムブロック呼び出し
require_once locate_template('block/functions-include.php', true);

// 初期にインストールさせるプラグイン設定
require_once locate_template('settings/tgmpa.php', true);

// OGP設定
require_once locate_template('settings/ogp.php', true);

// あまり変更しない触らない関数たち（ウィジェットなど）
require_once locate_template('settings/settings-import.php', true);

// 通常投稿にサンプル投稿を自動追加
require_once locate_template('settings/sample-post.php', true);

/*------------------------------------*\
	テーマ機能設定 add_theme_support
\*------------------------------------*/
if (!isset($content_width)) {
  $content_width = 1000; //テーマ内任意のoEmbedsや画像の最大許容幅
}

/*------------------------------------*\
	画像のサムネイルサイズ設定 post-thumbnails
\*------------------------------------*/
if (function_exists('add_theme_support')) {
  // アップロード画像のサムネイル設定
  add_theme_support('post-thumbnails');
  // 特定の大きさのサムネイルが必要なとき用使い方→ the_post_thumbnail('custom-size');
  add_image_size('custom-size', 300, 200, true); // 任意の数値を設定

  /*------------------------------------*\
      タイトルタグ　title-tag
  \*------------------------------------*/
  //タイトルタグ使用をサポート（wp_headに自動でtitleタグが入ります）
  add_theme_support('title-tag');
  //タイトルタグ内のセパレーター設定
  function custom_document_title_separator($sep)
  {
    return '|';
  }

  add_filter('document_title_separator', 'custom_document_title_separator');
  //タイトルタグ内にサイトの説明文を表示させない
  function edit_document_title_parts($title)
  {
    unset($title['tagline']);
    return $title;
  }

  add_filter('document_title_parts', 'edit_document_title_parts');
}

/*------------------------------------*\
    読み込まれるcss/js関連　wp_enqueue_style, wp_enqueue_script
\*------------------------------------*/
// 管理画面・フロント側共通で呼び出すCSS JavaScript
// CSSは基本的にこっち
function allsite_style_script()
{
  //テーマ情報css
  wp_register_style('theme', get_template_directory_uri() . '/style.css', array());
  wp_enqueue_style('theme');

  //リセットcss
  wp_register_style('reset', get_template_directory_uri() . '/css/reset.css', array());
  wp_enqueue_style('reset');

  wp_register_style('swipercss', get_template_directory_uri() . '/css/swiper-bundle.min.css', array());
  wp_enqueue_style('swipercss');

  //プラグインやwebフォントなど追加のCSSはこの辺に書きます。

  //カスタムcss
  wp_register_style('custom', get_template_directory_uri() . '/css/style.css', array());
  wp_enqueue_style('custom');
}

add_action('wp_enqueue_scripts', 'allsite_style_script');


// フロント側のみに呼び出すCSSとJavaScript
// jsファイルは基本的にこっち
function header_style_script()
{
  if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

    //swiper-bundle.min.jsの読み込み
    wp_register_script('swiperjs', get_template_directory_uri() . '/js/swiper-bundle.min.js', array('jquery'));
    wp_enqueue_script('swiperjs');

    //テーマ用のjsファイルを読み込み
    wp_register_script('mainscripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'));
    wp_enqueue_script('mainscripts');

    //swiper用のjsファイルを読み込み
    wp_register_script('slider', get_template_directory_uri() . '/js/slider.js', array('jquery'));
    wp_enqueue_script('slider');

    // ページ専用jsの読み込みが必要な時は下記のように使う。
    //    wp_register_script('scriptname', get_template_directory_uri().'/js/scriptname.js', array('jquery'));
    //    wp_register_style('stylename', get_template_directory_uri().'/css/stylename.css', array());
    //
    //    if(is_front_page()) {
    //      wp_enqueue_script('scriptname');
    //      wp_enqueue_style('stylename');
    //    }elseif(is_page('pagenamehere')){
    //      wp_enqueue_script('scriptname');
    //      wp_enqueue_style('stylename');
    //    }
  }
}

add_action('wp_enqueue_scripts', 'header_style_script');


//指定のjsにdefer（レンダリングブロック防止の記述）をつける。
//★deferだと動作しない場合は、jquery-coreについてはdeferをやめると良い。
function add_defer_script($tag, $handle, $url)
{
  if ('jquery-migrate' === $handle || 'mainscripts' === $handle || 'slider' === $handle) {
    $tag = '<script src="' . esc_url($url) . '" defer></script>';
  }
  return $tag;
}

add_filter('script_loader_tag', 'add_defer_script', 10, 3);


// 登録したcssの出力時に 'text/css' は消す。
function style_type_remove($tag)
{
  return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

add_filter('style_loader_tag', 'style_type_remove');


//指定のCSSを非同期で読み込む
function load_css_async_top($html, $handle, $href, $media)
{
  if ('wp-block-library' === $handle) {
    //元の link 要素の HTML（改行が含まれているようなので前後の空白文字を削除）
    $default_html = trim($html);
    //HTML を変更
    $html = <<<EOT
<link rel="stylesheet" id="{$handle}-css" href="$href" media="print" onload="this.media='all'">
<noscript>{$default_html}</noscript>\n
EOT;
  }
  return $html;
}

add_filter('style_loader_tag', 'load_css_async_top', 10, 4);


/*------------------------------------*\
    管理画面で変更可能なメニュー機能
\*------------------------------------*/
// メニューの場所名登録（管理画面に表示する名前）
function register_menu()
{
  register_nav_menus(array( //メニューを追加する場合は行を追加
    'global-menu' => "グローバルナビゲーション",
  ));
}

add_action('init', 'register_menu'); // Add HTML5 Blank Menu

// 出力されるメニューのHTMLタグ設定 add_globalmenu();をテンプレート側に書いて表示
function add_globalmenu()
{
  wp_nav_menu(array(
    'theme_location' => 'global-menu', //メニューの位置（どのメニューか）
    'menu' => '',
    'container' => 'nav', // ulを囲う要素を指定。div or nav。なしの場合には false
    'container_class' => '', // containerに適用するCSSクラス名
    'container_id' => 'gnav', // コンテナに適用するCSS ID名
    'menu_class' => '', // メニューを構成するul要素につけるCSSクラス名
    'fallback_cb' => 'wp_page_menu', // メニューが存在しない場合にコールバック関数を呼び出す
    'before' => '', // メニューアイテムのリンクの前に挿入するテキスト
    'after' => '', // メニューアイテムのリンクの後に挿入するテキスト
    'echo' => true, // メニューをHTML出力する（true）かPHPの値で返す（false）か
    'depth' => 1, // 何階層まで表示するか。0は全階層、1は親メニューまで、2は子メニューまで…という感じ
    'walker' => '', // カスタムウォーカーを使用する場合
  ));
}


/*------------------------------------*\
    投稿機能設定 post functions
\*------------------------------------*/
// ====== newsを通常投稿のアーカイブページにする ======
/*
 * 投稿にアーカイブ(投稿一覧)を持たせるようにします。
 * ※ 記載後にパーマリンク設定で「変更を保存」してください。
 */
function post_has_archive($args, $post_type)
{
  if ('post' == $post_type) {
    $args['rewrite'] = true;
    $args['has_archive'] = 'news'; // ページ名
  }
  return $args;
}

add_filter('register_post_type_args', 'post_has_archive', 10, 2);
// 投稿記事のURLに/news/を含めたい場合は https://yamatonamiki.com/blog/178/ 参照の上用変更

// ページネーション表示
function wp_pagination()
{
  global $wp_query;
  $big = 999999999;
  echo paginate_links(array('base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))), 'format' => '?paged=%#%', 'current' => max(1, get_query_var('paged')), 'prev_text' => '<span>≪</span>', 'next_text' => '<span>≫</span>', 'total' => $wp_query->max_num_pages));
}

add_action('init', 'wp_pagination');


/*------------------------------------*\
    抜粋表示設定 the_excerpt();
\*------------------------------------*/
remove_filter('the_excerpt', 'wpautop'); // 自動挿入のpタグを抜粋欄から消す

// 抜粋表示時のリンク表示を設定
function custom_view_more($more)
{
  global $post;
  return '... <a class="link_more" href="' . get_permalink($post->ID) . '">' . '続きを読む' . '</a>';
}

add_filter('excerpt_more', 'custom_view_more');

// 抜粋文字数設定（不具合時は WP Multibyte Patch プラグインを入れる）
function custom_excerpt_length($length)
{
  return 20; //単語数：日本語の場合は2倍の文字数
}

add_filter('excerpt_length', 'custom_excerpt_length', 999);


/*------------------------------------*\
  プラグイン関連設定  settings for plugin
\*------------------------------------*/

/* Breadcrumb NavXT https://ja.wordpress.org/plugins/breadcrumb-navxt/ */
if (function_exists('bcn_display_list')) {
  //デフォルトのHOMEパンくずを除去
  add_action('bcn_after_fill', 'foo_pop');
  function foo_pop($trail)
  {
    array_pop($trail->breadcrumbs);
  }

  //静的にパンくずを追加
  add_action('bcn_after_fill', 'my_static_breadcrumb_adder');
  function my_static_breadcrumb_adder($breadcrumb_trail)
  {
    if (is_post_type_archive('post') || is_singular('post')) {
      //投稿タイプ post の時、2番目に/news/のパンくず
      $breadcrumb_trail->add(new bcn_breadcrumb('お知らせ', '<a title="%ftitle%." href="%link%">%htitle%</a>', array(), '/news/'));
    }
    //1つめ
    $breadcrumb_trail->add(new bcn_breadcrumb('TOP', '<a title="%ftitle%." href="%link%">%htitle%</a>', array('home'), home_url()));
  }
}


/*------------------------------------*\
  カスタム追加設定 additional functions
\*------------------------------------*/

//category-label　カテゴリslugをclass名として出力
function categories_label()
{
  $cats = get_the_category();
  foreach ($cats as $cat) {
    echo '<li><a href="' . get_category_link($cat->term_id) . '" ';
    echo 'class="cat_label cat_' . esc_attr($cat->slug) . '">';
    echo esc_html($cat->name);
    echo '</a></li>';
  }
}

// -------------------------------------
// カスタム投稿表示件数変更
// -------------------------------------
// function change_posts_per_page($query) {
//   if ( is_admin() || ! $query->is_main_query() )
//       return;
//   if ( $query->is_post_type_archive('post') ) {  // カスタム投稿タイプを指定
//       $query->set( 'posts_per_page', '10' );  // 表示件数を指定
//   }
// }
// add_action( 'pre_get_posts', 'change_posts_per_page' );


// -------------------------------------
// お知らせページ名称変更
// -------------------------------------
function custom_gettext($translated, $text, $domain)
{
  $custom_translates = array(
    'default' => array(
      '投稿' => 'お知らせ',
      '投稿編集' => 'お知らせ編集',
      '投稿一覧' => 'お知らせ一覧',
      '投稿を検索' => 'お知らせを検索',
      '投稿を表示' => 'お知らせを表示',
      '投稿は見つかりませんでした。' => 'お知らせは見つかりませんでした。',
      'ゴミ箱内に投稿が見つかりませんでした。' => 'ゴミ箱内にお知らせは見つかりませんでした。',
      '投稿を更新しました。<a href="%s">投稿を表示する</a>' => 'お知らせを更新しました。<a href="%s">お知らせを表示する</a>',
      'この投稿を先頭に固定表示' => 'このお知らせを先頭に固定表示'
    )
  );
  if (isset($custom_translates[$domain][$translated])) {
    $translated = $custom_translates[$domain][$translated];
  }
  return $translated;
}

add_filter('gettext', 'custom_gettext', 10, 3);

function trans_custom_gettext()
{
  $args = func_get_args();
  $translated = $args[0];
  $text = $args[1];
  $domain = array_pop($args);
  $translated = custom_gettext($translated, $text, $domain);
  return $translated;
}

add_filter('gettext_with_context', 'trans_custom_gettext', 10, 4);
add_filter('ngettext', 'trans_custom_gettext', 10, 5);
add_filter('ngettext_with_context', 'trans_custom_gettext', 10, 6);


// -------------------------------------
// ショートコードでphpファイルを呼び出す
// -------------------------------------
// includeフォルダ内にphpを追加　例）include/shortcode.php
// [myphp file="shortcode.php"]を記述
function Include_my_php($params = array())
{
  extract(shortcode_atts(array(
    'file' => 'default'
  ), $params));
  ob_start();
  include(get_theme_root() . '/' . get_template() . "/include/$file.php");
  return ob_get_clean();
}
add_shortcode('myphp', 'Include_my_php');



// -------------------------------------
// Snow Monkey Form 送信完了後にサンクスページへリダイレクト
// -------------------------------------
add_action(
  'wp_enqueue_scripts',
  function () {
    // セキュリティ: 管理画面では実行しない
    if (is_admin()) {
      return;
    }

    // JavaScriptコードをバッファリング開始
    ob_start();
?>
  <script>
    window.addEventListener(
      'load', // ページ全体の読み込み完了後に実行
      function() {
        // 対象のフォーム要素を取得（'snow-monkey-form-9' の部分は実際のフォームIDに合わせてください）
        const form = document.getElementById('snow-monkey-form-9');

        // フォーム要素が存在する場合のみ処理を実行
        if (form) {
          // Snow Monkey Forms の送信イベントを監視
          form.addEventListener(
            'smf.submit', // Snow Monkey Forms が発行するカスタムイベント
            function(event) {
              // セキュリティ: イベントオブジェクトの検証
              if (!event || !event.detail || typeof event.detail.status !== 'string') {
                return;
              }

              // 送信ステータスが 'complete' (完了) の場合のみ処理を実行
              if ('complete' === event.detail.status) {
                // 指定したサンクスページへリダイレクト
                // '/thanks/' の部分は実際のサンクスページのスラッグ等に合わせてください
                window.location.href = '<?php echo esc_url(home_url("/thanks/")); ?>';
              }
            }
          );
        }
      }
    );
  </script>
<?php
    // バッファリングしたJavaScriptコードを取得
    $data = ob_get_clean();

    // セキュリティ: データの検証
    if (empty($data)) {
      return;
    }

    // <script> タグを除去（wp_add_inline_script が自動で追加するため）
    $data = str_replace(['<script>', '</script>'], '', $data);

    // snow-monkey-forms スクリプトの後に追加
    wp_add_inline_script(
      'snow-monkey-forms', // Snow Monkey Forms のスクリプトハンドル名
      $data,
      'after' // snow-monkey-forms スクリプトの後に出力
    );
  },
  11 // 優先度を少し高く設定 (デフォルトは10)
);



// -------------------------------------
// ログインURLを変更する
// -------------------------------------

if (!class_exists('Login_Security')) :

  class Login_Security
  {
    // デフォルトのログインページ名を設定
    const DEFAULT_LOGIN_NAME = 'wp-default-login';

    public function __construct()
    {
      // フックを登録
      $this->initialize_hooks();
    }

    private function initialize_hooks()
    {
      // ユーザーがログインページへアクセスしようとしたときのリダイレクト処理
      add_action('template_redirect', [$this, 'redirect_login']);
      // 直接 wp-login.php へアクセスした場合のブロック処理
      add_action('login_init', [$this, 'block_default_login_url']);
      // サイト内のログインURLを変更
      add_filter('site_url', [$this, 'modify_login_url'], 10, 2);
      // リダイレクト時のURLを変更
      add_filter('wp_redirect', [$this, 'modify_login_redirect']);
    }

    // 直接 wp-login.php へアクセスした場合は404エラーを返す
    public static function block_default_login_url()
    {
      if (!defined('LOGIN_CHANGE') || sha1('page_changed') !== LOGIN_CHANGE) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        get_template_part(404);
        exit;
      }
    }

    // ログインURLをカスタムURLに変更
    public static function modify_login_url($url, $path)
    {
      $login_name = apply_filters('login_endpoint_name', self::DEFAULT_LOGIN_NAME);

      // wp-login.php へのリンクをカスタムURLに置き換える
      if (strpos($path, 'wp-login.php') !== false && (is_user_logged_in() || strpos($_SERVER['REQUEST_URI'], $login_name) !== false)) {
        return str_replace('wp-login.php', $login_name, $url);
      }
      return $url;
    }

    // ログインリダイレクト時のURLを変更
    public static function modify_login_redirect($location)
    {
      $login_name = apply_filters('login_endpoint_name', self::DEFAULT_LOGIN_NAME);

      // リダイレクトURLの wp-login.php をカスタムURLに変更
      if (strpos($_SERVER['REQUEST_URI'], $login_name) !== false) {
        return str_replace('wp-login.php', $login_name, $location);
      }
      return $location;
    }

    // ユーザーがカスタムログインページへアクセスした場合、ログインページを表示
    public static function redirect_login()
    {
      $current_url = (is_ssl() ? 'https' : 'http') . '://' . $_SERVER["HTTP_HOST"] . strtok($_SERVER["REQUEST_URI"], '?');
      $login_name = apply_filters('login_endpoint_name', self::DEFAULT_LOGIN_NAME);

      // 指定されたカスタムログインURLの場合、WordPressのログイン画面を表示
      if ($current_url === home_url('/') . $login_name) {
        header("HTTP/1.1 200 LOGIN PAGE");
        define('LOGIN_CHANGE', sha1('page_changed'));
        require_once(ABSPATH . '/wp-login.php');
        exit;
      }
    }
  }

  // クラスのインスタンスを作成して適用
  new Login_Security;

endif;

// ログインURLのカスタマイズ（デフォルトは 'wp-custom-login'）
add_filter('login_endpoint_name', fn() => 'wp-custom-login');
