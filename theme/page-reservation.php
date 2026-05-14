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

    $template_uri = get_template_directory_uri();

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

          <h1 id="page-title">
            <?php echo esc_html($title); ?>
          </h1>
        </header>
      </article>
      <div class="breadcrumbs--wrap">
        <?php
        get_template_part('include/common', 'breadcrumb');
        ?>
      </div>

      <section class="reservation sec">
        <div class="container">
          <h2 class="ttl">空車確認・レンタカー予約</h2>
          <p class="reservation--lead">
            以下にご利用期間、ご利用希望レンタカーを選択後、空車確認ボタンをクリックしてください。<br>
            レンタカーのご予約はお電話でも受付中！！
          </p>
          <a class="reservation--banner" href="tel:0120-995-758">
            <img
              src="<?php echo esc_url($template_uri . '/img/flow/belongings_banner.jpg'); ?>"
              alt="お電話でのご予約・お問い合わせ 0120-995-758"
              width="675"
              height="200"
              loading="lazy"
              decoding="async">
          </a>

          <ul class="top_introduction">
            <li>
              <img
                src="<?php echo esc_url($template_uri . '/img/reservation/reservation_01.png'); ?>"
                alt="軽自動車Sタイプのレンタカー"
                width="290"
                height="165"
                loading="lazy"
                decoding="async">
              <h3>軽自動車Sタイプ</h3>
              <dl class="top_introduction--price">
                <dt>ウィークリー</dt>
                <dd>7,500円<span>(税込)</span></dd>
              </dl>
              <dl class="top_introduction--price -month">
                <dt>マンスリー</dt>
                <dd>24,500円<span>(税込)</span></dd>
              </dl>
            </li>
            <li>
              <img
                src="<?php echo esc_url($template_uri . '/img/reservation/reservation_02.png'); ?>"
                alt="軽トラックタイプのレンタカー"
                width="265"
                height="184"
                loading="lazy"
                decoding="async">
              <h3>軽トラックタイプ</h3>
              <dl class="top_introduction--price">
                <dt>ウィークリー</dt>
                <dd>9,800円<span>(税込)</span></dd>
              </dl>
              <dl class="top_introduction--price -month">
                <dt>マンスリー</dt>
                <dd>25,800円<span>(税込)</span></dd>
              </dl>
            </li>
            <li>
              <img
                src="<?php echo esc_url($template_uri . '/img/reservation/reservation_04.png'); ?>"
                alt="軽自動車Mタイプのレンタカー"
                width="332"
                height="200"
                loading="lazy"
                decoding="async">
              <h3>軽自動車Mタイプ</h3>
              <dl class="top_introduction--price">
                <dt>ウィークリー</dt>
                <dd>9,500円<span>(税込)</span></dd>
              </dl>
              <dl class="top_introduction--price -month">
                <dt>マンスリー</dt>
                <dd>29,500円<span>(税込)</span></dd>
              </dl>
            </li>
            <li>
              <img
                src="<?php echo esc_url($template_uri . '/img/reservation/reservation_03.png'); ?>"
                alt="軽自動車Lタイプのレンタカー"
                width="309"
                height="204"
                loading="lazy"
                decoding="async">
              <h3>軽自動車Lタイプ</h3>
              <dl class="top_introduction--price">
                <dt>ウィークリー</dt>
                <dd>13,500円<span>(税込)</span></dd>
              </dl>
              <dl class="top_introduction--price -month">
                <dt>マンスリー</dt>
                <dd>37,500円<span>(税込)</span></dd>
              </dl>
            </li>
          </ul>
          <h4>注意事項</h4>
          <p class="contact--lead">
            ケーレンタをご利用いただく際は、必ず契約書・同意書・注意事項をお読みいただき、<br class="is-hidden_sp">
            ご同意いただいてからご予約いただきますようお願い申し上げます。
          </p>
          <ul class="flow_order--document">
            <li>
              <a href="<?php echo esc_url($template_uri . '/pdf/契約書.pdf'); ?>" download="契約書.pdf">
                契約書
                <span>※PDFファイルがダウンロードされます</span>
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url($template_uri . '/pdf/同意書.pdf'); ?>" download="同意書.pdf">
                同意書
                <span>※PDFファイルがダウンロードされます</span>
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url($template_uri . '/pdf/注意事項.pdf'); ?>" download="注意事項.pdf">
                注意事項
                <span>※PDFファイルがダウンロードされます</span>
              </a>
            </li>
          </ul>
          <?php echo do_blocks('<!-- wp:snow-monkey-forms/snow-monkey-form {"formId":94} /-->'); ?>
        </div>
      </section>
    </main>
    <script type="application/ld+json">
      <?php echo wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>