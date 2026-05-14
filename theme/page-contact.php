<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <?php
    $post_id    = get_the_ID();
    $title      = get_the_title($post_id);
    $slug       = get_post_field('post_name', $post_id);
    $page_class = sanitize_html_class($slug) . '_page';

    $thumbnail_id  = get_post_thumbnail_id($post_id);
    $thumbnail_alt = $thumbnail_id ? get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) : '';

    if (empty($thumbnail_alt)) {
      $thumbnail_alt = $title;
    }

    $breadcrumb_items = array();
    $position = 1;

    $breadcrumb_items[] = array(
      '@type'    => 'ListItem',
      'position' => $position++,
      'name'     => 'TOP',
      'item'     => home_url('/'),
    );

    $ancestors = array_reverse(get_post_ancestors($post_id));

    foreach ($ancestors as $ancestor_id) {
      $breadcrumb_items[] = array(
        '@type'    => 'ListItem',
        'position' => $position++,
        'name'     => get_the_title($ancestor_id),
        'item'     => get_permalink($ancestor_id),
      );
    }

    $breadcrumb_items[] = array(
      '@type'    => 'ListItem',
      'position' => $position,
      'name'     => $title,
      'item'     => get_permalink($post_id),
    );

    $breadcrumb_schema = array(
      '@context'        => 'https://schema.org',
      '@type'           => 'BreadcrumbList',
      'itemListElement' => $breadcrumb_items,
    );
    ?>

    <main class="<?php echo esc_attr($page_class); ?>" aria-labelledby="page-title">
      <article id="post-<?php the_ID(); ?>" <?php post_class('page-article'); ?>>
        <header class="eyecatch">
          <?php if (has_post_thumbnail($post_id)) : ?>
            <figure>
              <?php
              echo get_the_post_thumbnail(
                $post_id,
                'full',
                array(
                  'class'         => 'eyecatch--img',
                  'alt'           => $thumbnail_alt,
                  'loading'       => 'eager',
                  'decoding'      => 'async',
                  'fetchpriority' => 'high',
                )
              );
              ?>
            </figure>
          <?php endif; ?>

          <h1>
            <?php echo esc_html($title); ?>
          </h1>
        </header>
      </article>
      <div class="breadcrumbs--wrap">
        <?php
        get_template_part('include/common', 'breadcrumb');
        ?>
      </div>

      <section class="contact sec">
        <div class="container">
          <h2 class="ttl">お問い合わせ</h2>
          <p class="contact--lead">
            ケーレンタは、初心者からご高齢の方まで、名古屋市で格安のレンタカーを安心・手軽にご利用いただけるよう、<br class="is-hidden_sp">
            車を「持つ」負担を抑え、必要なときだけ賢く使える新しいカーライフをご提供します。<br>
            短時間から長期まで、使いたい期間だけ気軽にレンタルでき、維持費や管理の手間も最小限。<br>
            予約から受け取り・返却までスタッフが丁寧にサポート。
          </p>
          <p class="contact--lead -supplement">
            お問い合わせ後、1〜2営業日以内に弊社担当者から連絡させていただきます。<br>
            また、お問い合わせはお電話でも受け付けております。<br>
            ※営業を目的としたお問合せはご遠慮ください。
          </p>
          <?php echo apply_filters('the_content', '<!-- wp:snow-monkey-forms/snow-monkey-form {"formId":83} /-->'); ?>
        </div>
      </section>



    </main>

    <script type="application/ld+json">
      <?php echo wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>