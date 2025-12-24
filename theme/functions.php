<?php
load_theme_textdomain('origintheme', get_template_directory() . '/languages');



/*------------------------------------*\
	headからいらない項目を削除する
\*------------------------------------*/

function removed_scripts_styles()
{
  if (!is_admin()) {
    remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);
    remove_action('wp_head', 'www-widgetapi');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    add_filter('emoji_svg_url', '__return_false');
  }
}
add_action('wp_enqueue_scripts', 'removed_scripts_styles');


/*------------------------------------*\
	絵文字無効化
\*------------------------------------*/
add_action('init', function () {
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('admin_print_styles', 'print_emoji_styles');
  add_filter('tiny_mce_plugins', function ($plugins) {
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
  });
  add_filter('wp_mail', 'wp_staticize_emoji_for_email'); // 必要なら外す
});

/*------------------------------------*\
	oEmbed のJS（wp-embed.min.js）を読み込ませない
\*------------------------------------*/
add_action('wp_footer', function () {
  wp_dequeue_script('wp-embed');
}, 100);

/*------------------------------------*\
	管理用アイコン（dashicons）をフロントで読み込まない
\*------------------------------------*/
add_action('wp_enqueue_scripts', function () {
  if (! is_user_logged_in()) {
    wp_deregister_style('dashicons');
  }
});

/*------------------------------------*\
Gutenberg用のCSSを読み込まない
\*------------------------------------*/

function my_delete_plugin_files()
{
  //IDを指定し解除
  wp_deregister_style('wp-block-library');
}
add_action('wp_enqueue_scripts', 'my_delete_plugin_files');



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
    読み込まれるcss関連
\*------------------------------------*/

add_action('wp_enqueue_scripts', function () {
  $uri  = function ($file) {
    return get_theme_file_uri($file);
  };
  $path = function ($file) {
    return get_theme_file_path($file);
  };
  $ver  = function ($file) use ($path) {
    $p = $path($file);
    return file_exists($p) ? (string) filemtime($p) : null;
  };

  wp_enqueue_style(
    'reset',
    $uri('/css/reset.css'),
    [],                                 // 依存なし（先頭で読む）
    $ver('/css/reset.css')
  );

  wp_enqueue_style(
    'theme',
    $uri('/style.css'),
    ['reset'],                           // テーマはリセットに依存
    $ver('/style.css')
  );

  wp_enqueue_style(
    'custom',
    $uri('/css/style.css'),
    ['theme'],                           // カスタムはテーマに依存
    $ver('/css/style.css')
  );

  wp_enqueue_style(
    'swipercss',
    $uri('/css/swiper-bundle.min.css'),
    [],                                // 依存なし
    $ver('/css/swiper-bundle.min.css')
  );
}, 5);

// ===== type='text/css' を削除（任意・微小な省バイト）=====
add_filter('style_loader_tag', function ($tag) {
  return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}, 9);

// ===== 非クリティカルCSSを非ブロッキング化（プリロード + noscript）=====
add_filter('style_loader_tag', function ($html, $handle, $href, $media) {
  if (is_admin()) return $html;

  // 本当に初期描画に必要なものだけを列挙
  // 例: "custom" はUI調整が多く初期描画に関与しがち
  $preload_handles = ['custom'];

  if (!in_array($handle, $preload_handles, true)) {
    return $html; // それ以外は通常のブロッキングCSSとして読み込み
  }

  $orig  = trim($html);
  $href  = esc_url($href);
  $id    = esc_attr("{$handle}-css");
  $media = $media ? ' media="' . esc_attr($media) . '"' : '';

  // rel=preload で先に取得し、onloadで stylesheet に切替
  // JS無効時は <noscript> でフォールバック
  return "<link rel=\"preload\" id=\"{$id}\" href=\"{$href}\" as=\"style\" onload=\"this.onload=null;this.rel='stylesheet'\"{$media}>\n"
    . "<noscript>{$orig}</noscript>\n";
}, 10, 4);




/*------------------------------------*\
読み込まれるjs関連
\*------------------------------------*/
if (! function_exists('theme_enqueue_js_only_optimized_assets')) {

  // フロント専用のスクリプト登録・読み込み（全て footer に配置）
  function theme_enqueue_js_only_optimized_assets()
  {
    // 管理画面・ログイン画面・REST/AJAX 等には影響させない
    if (is_admin() || (defined('WP_CLI') && WP_CLI) || $GLOBALS['pagenow'] === 'wp-login.php') {
      return;
    }

    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();

    // ヘルパー：ファイルの最終更新時刻を version に使う
    $register_script = function ($handle, $relative_path, $deps = array()) use ($theme_dir, $theme_uri) {
      $relative_path = ltrim($relative_path, '/');
      $full_path = $theme_dir . '/' . $relative_path;
      $src = $theme_uri . '/' . $relative_path;
      $ver = file_exists($full_path) ? filemtime($full_path) : null;

      // footer に読み込む（最後の引数 true）
      wp_register_script($handle, $src, $deps, $ver, true);
      wp_enqueue_script($handle);
    };

    // スクリプト登録 — 必要に応じてハンドル名／パス／依存を調整してください
    $register_script('swiperjs',    'js/swiper-bundle.min.js', array());
    $register_script('mainscripts', 'js/scripts.js',           array('jquery'));
    $register_script('animationjs', 'js/animation.js',         array('jquery'));
    $register_script('slider',      'js/slider.js',            array('jquery', 'swiperjs'));

    // ページごとの条件付き読み込み例（コメント解除して使用）
    // if ( is_front_page() ) {
    //     $register_script( 'frontpage', 'js/frontpage.js', array( 'jquery' ) );
    // }
  }
  add_action('wp_enqueue_scripts', 'theme_enqueue_js_only_optimized_assets', 20);

  // script タグに defer を付与（安全性考慮：jQuery 等は除外）
  function theme_add_defer_attribute_safe($tag, $handle, $src)
  {
    // フロント以外、Ajax、REST リクエストなどでは触らない
    if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
      return $tag;
    }

    // defer を付けたくないハンドル（コアや互換性リスクがあるもの）
    $exclude_handles = array(
      'jquery',
      'jquery-core',
      'jquery-migrate',
      'wp-emoji-release',
      'wp-embed'   // 例: 必要に応じて追加
    );

    if (in_array($handle, $exclude_handles, true)) {
      return $tag;
    }

    // defer を付けたいハンドル（ここに該当するハンドルのみ付与）
    $defer_handles = array('mainscripts', 'slider', 'animationjs', 'swiperjs');

    if (! in_array($handle, $defer_handles, true)) {
      return $tag;
    }

    // 既に defer / async / module 指定があれば二重付与しない
    if (stripos($tag, ' defer') !== false || stripos($tag, ' async') !== false || stripos($tag, 'type="module"') !== false) {
      return $tag;
    }

    // 最小限の置換で defer 属性を付与（互換性優先）
    $tag = preg_replace('/<script(\s)/i', '<script defer$1', $tag, 1);

    return $tag;
  }
  add_filter('script_loader_tag', 'theme_add_defer_attribute_safe', 10, 3);
}








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
// Snow Monkey Forms 送信完了後にサンクスページへリダイレクト
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
