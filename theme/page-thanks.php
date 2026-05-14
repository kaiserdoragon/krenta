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
            お問い合わせ
          </h1>
        </header>
      </article>
      <div class="breadcrumbs--wrap">
        <?php
        get_template_part('include/common', 'breadcrumb');
        ?>
      </div>

      <section class="thanks sec">
        <div class="container">
          <h2 class="ttl">お問い合わせありがとうございます</h2>
          <div class="thanks--inner">
            <p>
              お問い合わせありがとうございます。
            </p>
            <p>
              このたびは、お問い合わせ頂き誠にありがとうございます。
            </p>
            <p>
              お送り頂きました内容を確認の上、2～3営業日以内に折り返しご連絡させて頂きます。<br>
              また、ご記入頂いたメールアドレスへ、自動返信の確認メールをお送りしております。<br>
              しばらく経ってもメールが届かない場合は、入力頂いたメールアドレスが間違っているか、迷惑メールフォルダに振り分けられている可能性がございます。<br>
              また、ドメイン指定をされている場合は、「info@shima-car.com」からのメールが受信できるようあらかじめ設定をお願いいたします。<br>
              以上の内容をご確認の上、お手数ですがもう一度フォームよりお問い合わせ頂きますようお願い申し上げます。<br>
            </p>
            <p>
              なお、お急ぎの場合は電話でもご相談を受け付けております。<br>
              0120-995-758までご遠慮なくご相談ください。<br>
            </p>
          </div>
        </div>
      </section>
    </main>

    <script type="application/ld+json">
      <?php echo wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>