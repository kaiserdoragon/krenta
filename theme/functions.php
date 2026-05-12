<?php

if (!defined('ABSPATH')) {
  exit;
}

/*------------------------------------*\
  初期設定
\*------------------------------------*/

if (!isset($content_width)) {
  $content_width = 1000;
}

function origintheme_setup()
{
  load_theme_textdomain('origintheme', get_template_directory() . '/languages');

  add_theme_support('post-thumbnails');
  add_image_size('custom-size', 300, 200, true);

  add_theme_support('title-tag');

  register_nav_menus(array(
    'global-menu' => 'グローバルナビゲーション',
  ));
}
add_action('after_setup_theme', 'origintheme_setup');

function origintheme_document_title_separator($sep)
{
  return '|';
}
add_filter('document_title_separator', 'origintheme_document_title_separator');

function origintheme_document_title_parts($title)
{
  unset($title['tagline']);
  return $title;
}
add_filter('document_title_parts', 'origintheme_document_title_parts');

/*------------------------------------*\
  head整理・不要出力の抑制
  ※ wp_enqueue_scripts / wp_print_scripts は外さない
\*------------------------------------*/

function origintheme_cleanup_head()
{
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'wp_shortlink_wp_head', 10);
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

  // oEmbedを使わない前提ならheadとJSを削減
  remove_action('wp_head', 'wp_oembed_add_discovery_links');
  remove_action('wp_head', 'wp_oembed_add_host_js');

  // 絵文字関連の出力を削減
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('admin_print_styles', 'print_emoji_styles');
  add_filter('emoji_svg_url', '__return_false');

  add_filter('tiny_mce_plugins', 'origintheme_disable_tinymce_emoji');
}
add_action('init', 'origintheme_cleanup_head');

function origintheme_disable_tinymce_emoji($plugins)
{
  return is_array($plugins) ? array_diff($plugins, array('wpemoji')) : array();
}

function origintheme_dequeue_embed_script()
{
  if (!is_admin()) {
    wp_dequeue_script('wp-embed');
  }
}
add_action('wp_enqueue_scripts', 'origintheme_dequeue_embed_script', 100);

function origintheme_remove_dashicons_for_guests()
{
  if (!is_user_logged_in()) {
    wp_deregister_style('dashicons');
  }
}
add_action('wp_enqueue_scripts', 'origintheme_remove_dashicons_for_guests', 100);

/*------------------------------------*\
  ブロックエディタCSSの削減
  ※ ブロックの見た目を自前CSSで管理している前提。
  ※ フォームやブロックを使うページは除外する。
\*------------------------------------*/

function origintheme_dequeue_block_styles()
{
  if (is_admin()) {
    return;
  }

  // ブロックCSSを残したいページスラッグ。必要に応じて追加してください。
  $keep_block_css_pages = apply_filters(
    'origintheme_keep_block_css_pages',
    array('contact', 'inquiry', 'thanks')
  );

  if (is_page($keep_block_css_pages)) {
    return;
  }

  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles');
}
add_action('wp_enqueue_scripts', 'origintheme_dequeue_block_styles', 100);

/*------------------------------------*\
  外部ファイルの読み込み
\*------------------------------------*/

function origintheme_require_template($relative_path)
{
  $path = locate_template($relative_path, false, false);

  if ($path && file_exists($path)) {
    require_once $path;
  }
}

origintheme_require_template('block/functions-include.php');
origintheme_require_template('settings/tgmpa.php');
origintheme_require_template('settings/ogp.php');
origintheme_require_template('settings/settings-import.php');
origintheme_require_template('settings/sample-post.php');

/*------------------------------------*\
  CSS読み込み
\*------------------------------------*/

function origintheme_asset_version($file)
{
  $path = get_theme_file_path($file);
  return file_exists($path) ? (string) filemtime($path) : null;
}

function origintheme_enqueue_styles()
{
  $uri = function ($file) {
    return get_theme_file_uri($file);
  };

  wp_enqueue_style(
    'reset',
    $uri('/css/reset.css'),
    array(),
    origintheme_asset_version('/css/reset.css')
  );

  $theme_deps = array('reset');

  // TOPページだけで読み込む
  if (is_front_page()) {
    wp_enqueue_style(
      'swipercss',
      $uri('/css/swiper-bundle.min.css'),
      array('reset'),
      origintheme_asset_version('/css/swiper-bundle.min.css')
    );

    $theme_deps[] = 'swipercss';
  }

  wp_enqueue_style(
    'theme',
    $uri('/style.css'),
    $theme_deps,
    origintheme_asset_version('/style.css')
  );

  wp_enqueue_style(
    'custom',
    $uri('/css/style.css'),
    array('theme'),
    origintheme_asset_version('/css/style.css')
  );
}
add_action('wp_enqueue_scripts', 'origintheme_enqueue_styles', 5);

// type="text/css" を削除。速度効果は小さいが副作用も少ない。
function origintheme_remove_style_type_attribute($tag)
{
  return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}
add_filter('style_loader_tag', 'origintheme_remove_style_type_attribute', 9);



/*------------------------------------*\
  JS読み込み
\*------------------------------------*/

function origintheme_register_and_enqueue_script($handle, $relative_path, $deps = array(), $defer = true)
{
  $relative_path = ltrim($relative_path, '/');
  $full_path     = get_theme_file_path('/' . $relative_path);
  $src           = get_theme_file_uri('/' . $relative_path);
  $ver           = file_exists($full_path) ? (string) filemtime($full_path) : null;

  if ($defer && version_compare(get_bloginfo('version'), '6.3', '>=')) {
    wp_register_script(
      $handle,
      $src,
      $deps,
      $ver,
      array(
        'in_footer' => true,
        'strategy'  => 'defer',
      )
    );
  } else {
    wp_register_script($handle, $src, $deps, $ver, true);
  }

  wp_enqueue_script($handle);
}

function origintheme_enqueue_scripts()
{
  if (is_admin() || (defined('WP_CLI') && WP_CLI)) {
    return;
  }

  if (isset($GLOBALS['pagenow']) && 'wp-login.php' === $GLOBALS['pagenow']) {
    return;
  }

  origintheme_register_and_enqueue_script('mainscripts', 'js/scripts.js', array('jquery'), true);

  // TOPページだけで読み込む
  if (is_front_page()) {
    origintheme_register_and_enqueue_script('swiperjs', 'js/swiper-bundle.min.js', array(), true);
    origintheme_register_and_enqueue_script('slider', 'js/slider.js', array('jquery', 'swiperjs'), true);
  }
}
add_action('wp_enqueue_scripts', 'origintheme_enqueue_scripts', 20);

//defer付与
function origintheme_add_defer_attribute_for_legacy_wp($tag, $handle, $src)
{
  if (version_compare(get_bloginfo('version'), '6.3', '>=')) {
    return $tag;
  }

  if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
    return $tag;
  }

  $defer_handles = array('mainscripts', 'swiperjs', 'slider');

  if (!in_array($handle, $defer_handles, true)) {
    return $tag;
  }

  if (stripos($tag, ' defer') !== false || stripos($tag, ' async') !== false || stripos($tag, 'type="module"') !== false) {
    return $tag;
  }

  return preg_replace('/<script(\s)/i', '<script defer$1', $tag, 1);
}
add_filter('script_loader_tag', 'origintheme_add_defer_attribute_for_legacy_wp', 10, 3);

/*------------------------------------*\
  グローバルナビ出力
\*------------------------------------*/

function add_globalmenu()
{
  wp_nav_menu(array(
    'theme_location'  => 'global-menu',
    'menu'            => '',
    'container'       => 'nav',
    'container_class' => '',
    'container_id'    => 'gnav',
    'menu_class'      => '',
    'fallback_cb'     => 'wp_page_menu',
    'before'          => '',
    'after'           => '',
    'echo'            => true,
    'depth'           => 1,
    'walker'          => '',
  ));
}

/*------------------------------------*\
  投稿機能設定
\*------------------------------------*/

function post_has_archive($args, $post_type)
{
  if ('post' === $post_type) {
    $args['rewrite']     = true;
    $args['has_archive'] = 'news';
  }

  return $args;
}
add_filter('register_post_type_args', 'post_has_archive', 10, 2);

function wp_pagination()
{
  global $wp_query;

  if (empty($wp_query) || $wp_query->max_num_pages <= 1) {
    return;
  }

  $big = 999999999;

  echo wp_kses_post(
    paginate_links(array(
      'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
      'format'    => '?paged=%#%',
      'current'   => max(1, get_query_var('paged')),
      'prev_text' => '<span>≪</span>',
      'next_text' => '<span>≫</span>',
      'total'     => $wp_query->max_num_pages,
    ))
  );
}

/*------------------------------------*\
  抜粋表示設定
\*------------------------------------*/

remove_filter('the_excerpt', 'wpautop');

function custom_view_more($more)
{
  global $post;

  if (!$post) {
    return '...';
  }

  return '... <a class="link_more" href="' . esc_url(get_permalink($post->ID)) . '">続きを読む</a>';
}
add_filter('excerpt_more', 'custom_view_more');

function custom_excerpt_length($length)
{
  return 20;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

/*------------------------------------*\
  Breadcrumb NavXT
\*------------------------------------*/

function origintheme_breadcrumb_remove_default_home($trail)
{
  if (!empty($trail->breadcrumbs)) {
    array_pop($trail->breadcrumbs);
  }
}

function origintheme_breadcrumb_add_static_items($breadcrumb_trail)
{
  if (!class_exists('bcn_breadcrumb')) {
    return;
  }

  if (is_post_type_archive('post') || is_singular('post')) {
    $breadcrumb_trail->add(new bcn_breadcrumb('お知らせ', '<a title="%ftitle%." href="%link%">%htitle%</a>', array(), home_url('/news/')));
  }

  $breadcrumb_trail->add(new bcn_breadcrumb('TOP', '<a title="%ftitle%." href="%link%">%htitle%</a>', array('home'), home_url('/')));
}

if (function_exists('bcn_display_list')) {
  add_action('bcn_after_fill', 'origintheme_breadcrumb_remove_default_home');
  add_action('bcn_after_fill', 'origintheme_breadcrumb_add_static_items');
}

/*------------------------------------*\
  カテゴリラベル出力
\*------------------------------------*/

function categories_label()
{
  $cats = get_the_category();

  if (empty($cats) || is_wp_error($cats)) {
    return;
  }

  foreach ($cats as $cat) {
    echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '" class="cat_label cat_' . esc_attr($cat->slug) . '">';
    echo esc_html($cat->name);
    echo '</a></li>';
  }
}

/*------------------------------------*\
  管理画面の投稿名称変更
\*------------------------------------*/

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
      'この投稿を先頭に固定表示' => 'このお知らせを先頭に固定表示',
    ),
  );

  if (isset($custom_translates[$domain][$translated])) {
    return $custom_translates[$domain][$translated];
  }

  return $translated;
}

function trans_custom_gettext()
{
  $args       = func_get_args();
  $translated = $args[0];
  $text       = isset($args[1]) ? $args[1] : '';
  $domain     = array_pop($args);

  return custom_gettext($translated, $text, $domain);
}

if (is_admin()) {
  add_filter('gettext', 'custom_gettext', 10, 3);
  add_filter('gettext_with_context', 'trans_custom_gettext', 10, 4);
  add_filter('ngettext', 'trans_custom_gettext', 10, 5);
  add_filter('ngettext_with_context', 'trans_custom_gettext', 10, 6);
}

/*------------------------------------*\
  ショートコードでincludeフォルダ内のPHPを呼び出す
  例: [myphp file="shortcode"] => include/shortcode.php
\*------------------------------------*/

function Include_my_php($params = array())
{
  $atts = shortcode_atts(array(
    'file' => 'default',
  ), $params, 'myphp');

  $file = preg_replace('/\.php$/', '', (string) $atts['file']);

  // ファイル名のみ許可。サブディレクトリや ../ は許可しない。
  if (!preg_match('/\A[a-zA-Z0-9_-]+\z/', $file)) {
    return '';
  }

  $base_dir  = get_theme_file_path('/include/');
  $base_real = realpath($base_dir);
  $target    = realpath($base_dir . $file . '.php');

  if (!$base_real || !$target || !file_exists($target)) {
    return '';
  }

  $base_real = trailingslashit(wp_normalize_path($base_real));
  $target    = wp_normalize_path($target);

  if (strpos($target, $base_real) !== 0) {
    return '';
  }

  ob_start();
  include $target;
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
