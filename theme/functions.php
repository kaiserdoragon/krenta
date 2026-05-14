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

  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('responsive-embeds');
  add_theme_support('automatic-feed-links');

  add_theme_support(
    'html5',
    array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'style',
      'script',
      'navigation-widgets',
    )
  );

  add_image_size('custom-size', 300, 200, true);

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
  if (!is_front_page()) {
    unset($title['tagline']);
  }

  if (is_post_type_archive('post')) {
    $title['title'] = 'お知らせ';
  }

  return $title;
}
add_filter('document_title_parts', 'origintheme_document_title_parts');

/*------------------------------------*\
  SEO: description / canonical / OGP / JSON-LD
  SEOプラグインを使わない前提でテーマ側から出力します。
\*------------------------------------*/

function origintheme_normalize_meta_text($text, $width = 160)
{
  $text = strip_shortcodes((string) $text);
  $text = wp_strip_all_tags($text, true);
  $text = html_entity_decode($text, ENT_QUOTES, get_bloginfo('charset'));
  $text = preg_replace('/\s+/u', ' ', $text);
  $text = trim($text);

  if ($text === '') {
    return '';
  }

  if (function_exists('mb_strimwidth')) {
    return mb_strimwidth($text, 0, $width, '...', 'UTF-8');
  }

  return strlen($text) > $width ? substr($text, 0, $width) . '...' : $text;
}

function origintheme_get_meta_description()
{
  if (is_front_page()) {
    $description = get_bloginfo('description');
    return origintheme_normalize_meta_text($description ? $description : get_bloginfo('name'));
  }

  if (is_singular()) {
    $post_id = get_queried_object_id();
    $post    = get_post($post_id);

    if (!$post) {
      return '';
    }

    if (has_excerpt($post_id)) {
      return origintheme_normalize_meta_text(get_the_excerpt($post_id));
    }

    return origintheme_normalize_meta_text($post->post_content);
  }

  if (is_post_type_archive('post')) {
    return origintheme_normalize_meta_text('お知らせ一覧です。最新情報を掲載しています。');
  }

  if (is_category() || is_tag() || is_tax()) {
    $description = term_description();

    if ($description) {
      return origintheme_normalize_meta_text($description);
    }

    return origintheme_normalize_meta_text(single_term_title('', false) . 'に関する記事一覧です。');
  }

  if (is_search()) {
    return origintheme_normalize_meta_text('「' . get_search_query(false) . '」の検索結果です。');
  }

  if (is_404()) {
    return origintheme_normalize_meta_text('ページが見つかりませんでした。');
  }

  return origintheme_normalize_meta_text(get_bloginfo('description'));
}

function origintheme_get_canonical_url()
{
  if (is_404()) {
    return '';
  }

  if (is_singular()) {
    $canonical = wp_get_canonical_url(get_queried_object_id());
    return $canonical ? $canonical : get_permalink(get_queried_object_id());
  }

  if (is_front_page()) {
    return home_url('/');
  }

  if (is_home()) {
    $page_for_posts = (int) get_option('page_for_posts');
    return $page_for_posts ? get_permalink($page_for_posts) : home_url('/');
  }

  if (is_post_type_archive('post')) {
    return home_url('/news/');
  }

  if (is_category() || is_tag() || is_tax()) {
    $term = get_queried_object();

    if ($term && !is_wp_error($term)) {
      $url = get_term_link($term);
      return is_wp_error($url) ? '' : $url;
    }
  }

  if (is_author()) {
    return get_author_posts_url(get_queried_object_id());
  }

  if (is_date() || is_archive()) {
    return get_pagenum_link(1);
  }

  if (is_search()) {
    return get_search_link();
  }

  return '';
}

function origintheme_get_og_image_url()
{
  if (is_singular() && has_post_thumbnail()) {
    $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_queried_object_id()), 'full');

    if (!empty($image[0])) {
      return $image[0];
    }
  }

  $candidates = array(
    '/img/ogp.jpg',
  );

  foreach ($candidates as $file) {
    if (file_exists(get_theme_file_path($file))) {
      return get_theme_file_uri($file);
    }
  }

  $site_icon = get_site_icon_url(512);
  return $site_icon ? $site_icon : '';
}

function origintheme_output_seo_meta()
{
  if (is_admin()) {
    return;
  }

  $description = origintheme_get_meta_description();
  $canonical   = origintheme_get_canonical_url();
  $title       = wp_get_document_title();
  $url         = $canonical ? $canonical : home_url(add_query_arg(array(), $GLOBALS['wp']->request));
  $site_name   = get_bloginfo('name');
  $locale      = str_replace('_', '-', get_locale());
  $og_type     = is_singular('post') ? 'article' : 'website';
  $og_image    = origintheme_get_og_image_url();

  if ($description) {
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
  }

  if ($canonical) {
    echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
  }

  echo '<meta property="og:locale" content="' . esc_attr($locale) . '">' . "\n";
  echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
  echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";

  if ($description) {
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
  }

  echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
  echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";

  if ($og_image) {
    echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
  } else {
    echo '<meta name="twitter:card" content="summary">' . "\n";
  }

  echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";

  if ($description) {
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
  }
}
add_action('wp_head', 'origintheme_output_seo_meta', 1);

function origintheme_robots($robots)
{
  if (is_search() || is_404() || is_page(array('thanks'))) {
    $robots['noindex'] = true;
    $robots['follow']  = true;
  }

  return $robots;
}
add_filter('wp_robots', 'origintheme_robots');

function origintheme_output_base_json_ld()
{
  if (is_admin()) {
    return;
  }

  $site_url = home_url('/');
  $site_id  = trailingslashit($site_url) . '#website';
  $org_id   = trailingslashit($site_url) . '#organization';
  $logo     = origintheme_get_og_image_url();

  $graph = array(
    array(
      '@type'           => 'WebSite',
      '@id'             => $site_id,
      'url'             => $site_url,
      'name'            => get_bloginfo('name'),
      'description'     => get_bloginfo('description'),
      'publisher'       => array('@id' => $org_id),
      'potentialAction' => array(
        '@type'       => 'SearchAction',
        'target'      => home_url('/?s={search_term_string}'),
        'query-input' => 'required name=search_term_string',
      ),
    ),
    array(
      '@type' => 'Organization',
      '@id'   => $org_id,
      'name'  => get_bloginfo('name'),
      'url'   => $site_url,
    ),
  );

  if ($logo) {
    $graph[1]['logo'] = array(
      '@type' => 'ImageObject',
      'url'   => $logo,
    );
  }

  $schema = array(
    '@context' => 'https://schema.org',
    '@graph'   => $graph,
  );

  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'origintheme_output_base_json_ld', 20);

/*------------------------------------*\
  パンくずリスト HTML / JSON-LD
\*------------------------------------*/

function origintheme_breadcrumb_home_title($title, $type = array(), $id = null)
{
  if (is_array($type) && in_array('home', $type, true)) {
    return 'TOP';
  }

  // サンクスページの時にパンくずを変更
  if ($id && get_post_type($id) === 'page' && get_post_field('post_name', $id) === 'thanks') {
    return 'お問い合わせありがとうございます。';
  }

  return $title;
}
add_filter('bcn_breadcrumb_title', 'origintheme_breadcrumb_home_title', 10, 3);


function origintheme_get_breadcrumb_items()
{
  if (is_front_page()) {
    return array();
  }

  $items = array(
    array(
      'name' => 'TOP',
      'url'  => home_url('/'),
    ),
  );

  if (is_home()) {
    $page_for_posts = (int) get_option('page_for_posts');

    $items[] = array(
      'name' => $page_for_posts ? get_the_title($page_for_posts) : 'お知らせ',
      'url'  => $page_for_posts ? get_permalink($page_for_posts) : home_url('/news/'),
    );

    return $items;
  }

  if (is_post_type_archive('post')) {
    $items[] = array(
      'name' => 'お知らせ',
      'url'  => home_url('/news/'),
    );

    return $items;
  }

  if (is_singular()) {
    $post_id   = get_queried_object_id();
    $post_type = get_post_type($post_id);

    if ($post_type === 'post') {
      $items[] = array(
        'name' => 'お知らせ',
        'url'  => home_url('/news/'),
      );
    } elseif ($post_type !== 'page') {
      $post_type_object = get_post_type_object($post_type);

      if ($post_type_object && !empty($post_type_object->has_archive)) {
        $items[] = array(
          'name' => $post_type_object->labels->name,
          'url'  => get_post_type_archive_link($post_type),
        );
      }
    }

    if (is_page()) {
      $ancestors = array_reverse(get_post_ancestors($post_id));

      foreach ($ancestors as $ancestor_id) {
        $items[] = array(
          'name' => get_the_title($ancestor_id),
          'url'  => get_permalink($ancestor_id),
        );
      }
    }

    $items[] = array(
      'name' => get_the_title($post_id),
      'url'  => get_permalink($post_id),
    );

    return $items;
  }

  if (is_category() || is_tag() || is_tax()) {
    $term = get_queried_object();

    if ($term && !is_wp_error($term)) {
      if (is_category() || is_tag()) {
        $items[] = array(
          'name' => 'お知らせ',
          'url'  => home_url('/news/'),
        );
      }

      if (is_taxonomy_hierarchical($term->taxonomy)) {
        $ancestors = array_reverse(get_ancestors($term->term_id, $term->taxonomy, 'taxonomy'));

        foreach ($ancestors as $ancestor_id) {
          $ancestor = get_term($ancestor_id, $term->taxonomy);

          if ($ancestor && !is_wp_error($ancestor)) {
            $items[] = array(
              'name' => $ancestor->name,
              'url'  => get_term_link($ancestor),
            );
          }
        }
      }

      $items[] = array(
        'name' => $term->name,
        'url'  => get_term_link($term),
      );
    }

    return $items;
  }

  if (is_search()) {
    $items[] = array(
      'name' => '検索結果',
      'url'  => get_search_link(),
    );

    return $items;
  }

  if (is_404()) {
    $items[] = array(
      'name' => 'ページが見つかりません',
      'url'  => '',
    );

    return $items;
  }

  return $items;
}

function origintheme_breadcrumb()
{
  $items = origintheme_get_breadcrumb_items();

  if (count($items) < 2) {
    return;
  }

  echo '<nav class="breadcrumb" aria-label="パンくずリスト">';
  echo '<ol class="breadcrumb__list">';

  $last_index = count($items) - 1;

  foreach ($items as $index => $item) {
    $name = isset($item['name']) ? $item['name'] : '';
    $url  = isset($item['url']) ? $item['url'] : '';

    if ($name === '') {
      continue;
    }

    echo '<li class="breadcrumb__item">';

    if ($index !== $last_index && $url) {
      echo '<a href="' . esc_url($url) . '">' . esc_html($name) . '</a>';
    } else {
      echo '<span aria-current="page">' . esc_html($name) . '</span>';
    }

    echo '</li>';
  }

  echo '</ol>';
  echo '</nav>';
}

function origintheme_output_breadcrumb_json_ld()
{
  if (is_admin()) {
    return;
  }

  $items = origintheme_get_breadcrumb_items();

  if (count($items) < 2) {
    return;
  }

  $list = array();

  foreach ($items as $index => $item) {
    if (empty($item['name'])) {
      continue;
    }

    $list_item = array(
      '@type'    => 'ListItem',
      'position' => $index + 1,
      'name'     => $item['name'],
    );

    if (!empty($item['url'])) {
      $list_item['item'] = $item['url'];
    }

    $list[] = $list_item;
  }

  if (count($list) < 2) {
    return;
  }

  $schema = array(
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => $list,
  );

  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'origintheme_output_breadcrumb_json_ld', 21);



/*------------------------------------*\
  head整理・不要出力の抑制
  wp_enqueue_scripts / wp_print_scripts は外さない
\*------------------------------------*/

function origintheme_cleanup_head()
{
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'wp_shortlink_wp_head', 10);
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
  remove_action('wp_head', 'rel_canonical');

  remove_action('wp_head', 'wp_oembed_add_discovery_links');
  remove_action('wp_head', 'wp_oembed_add_host_js');

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
  ブロックの見た目を自前CSSで管理している前提。
\*------------------------------------*/

function origintheme_dequeue_block_styles()
{
  if (is_admin()) {
    return;
  }

  $keep_block_css_pages = apply_filters(
    'origintheme_keep_block_css_pages',
    array(
      'contact',
      'inquiry',
      'thanks',
      'service',
      'services',
      'price',
      'about',
    )
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
  OGPはこのfunctions.phpで出すため settings/ogp.php は読み込まない。
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
    'scrollhintcss',
    $uri('/css/scroll-hint.css'),
    array('reset'),
    origintheme_asset_version('/css/scroll-hint.css')
  );

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

  if (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php') {
    return;
  }

  origintheme_register_and_enqueue_script('scrollhintjs', 'js/scroll-hint.min.js', array('jquery'), true);
  origintheme_register_and_enqueue_script('mainscripts', 'js/scripts.js', array('jquery', 'scrollhintjs'), true);

  if (is_front_page()) {
    origintheme_register_and_enqueue_script('swiperjs', 'js/swiper-bundle.min.js', array(), true);
    origintheme_register_and_enqueue_script('slider', 'js/slider.js', array('jquery', 'swiperjs'), true);
  }
}
add_action('wp_enqueue_scripts', 'origintheme_enqueue_scripts', 20);

function origintheme_add_defer_attribute_for_legacy_wp($tag, $handle, $src)
{
  if (version_compare(get_bloginfo('version'), '6.3', '>=')) {
    return $tag;
  }

  if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
    return $tag;
  }

  $defer_handles = array('mainscripts', 'scrollhintjs', 'swiperjs', 'slider');

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
    'theme_location'       => 'global-menu',
    'container'            => 'nav',
    'container_id'         => 'gnav',
    'container_aria_label' => 'グローバルナビゲーション',
    'menu_class'           => 'gnav__list',
    'fallback_cb'          => false,
    'echo'                 => true,
    'depth'                => 1,
  ));
}

/*------------------------------------*\
  投稿機能設定
\*------------------------------------*/

function post_has_archive($args, $post_type)
{
  if ($post_type === 'post') {
    $args['rewrite']     = true;
    $args['has_archive'] = 'news';
    $args['label']       = 'お知らせ';
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
      // 修正箇所：文字列結合を使用し、esc_urlで無害化、alt属性にテキストを付与
      'prev_text' => '<span><img src="' . esc_url(get_template_directory_uri()) . '/img/news/icon_prev.png" alt="前のページ" width="15" height="15"></span>',
      'next_text' => '<span><img src="' . esc_url(get_template_directory_uri()) . '/img/news/icon_next.png" alt="次のページ" width="15" height="15"></span>',
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

function origintheme_include_my_php($params = array())
{
  $atts = shortcode_atts(array(
    'file' => 'default',
  ), $params, 'myphp');

  $file = preg_replace('/\.php$/', '', (string) $atts['file']);

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
add_shortcode('myphp', 'origintheme_include_my_php');




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
        const form = document.getElementById('snow-monkey-form-83');

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
