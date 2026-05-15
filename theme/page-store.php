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
    $store_image  = $template_uri . '/img/store/shop.jpg';

    $map_src = 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d26106.24605742023!2d136.76835793423638!3d35.12466665827292!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x60039db7d51296d1%3A0xe7061392e08e0909!2z6J-55rGf6aeF44CB44CSNDk3LTAwMzIg5oSb55-l55yM5rW36YOo6YOh6J-55rGf55S65LuK5LiK5YWt5Y-N55Sw!3m2!1d35.1420039!2d136.79366339999999!4m5!1s0x60039d1dc429bd3d%3A0x7bbc7d6af5cb5385!2z44CSNDk3LTAwNDEg5oSb55-l55yM5rW36YOo6YOh6J-55rGf55S65Y2X77yT5LiB55uu77yW!3m2!1d35.108948999999996!2d136.7881775!5e0!3m2!1sja!2sjp!4v1778747641718!5m2!1sja!2sjp';

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

    $store_schema = array(
      '@context' => 'https://schema.org',
      '@type'    => 'LocalBusiness',
      '@id'      => get_permalink($post_id) . '#localbusiness',
      'name'     => 'ケーレンタ',
      'legalName' => '株式会社SHIMA.GROUP',
      'url'      => home_url('/'),
      'image'    => $store_image,
      'telephone' => '0120-995-758',
      'priceRange' => '¥',
      'address' => array(
        '@type'           => 'PostalAddress',
        'postalCode'      => '497-0041',
        'addressRegion'   => '愛知県',
        'addressLocality' => '海部郡蟹江町',
        'streetAddress'   => '南3丁目6',
        'addressCountry'  => 'JP',
      ),
      'openingHoursSpecification' => array(
        array(
          '@type' => 'OpeningHoursSpecification',
          'dayOfWeek' => array(
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
          ),
          'opens'  => '10:00',
          'closes' => '19:00',
        ),
      ),
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
      <section class="store_info sec">
        <div class="container">
          <h2 class="ttl">店舗紹介</h2>
          <img src="<?php echo esc_url($store_image); ?>" alt="ケーレンタの店舗外観" width="800" height="380" loading="lazy" decoding="async">
          <p class="store_info--txt">
            自動車販売店直営のレンタカー「ケーレンタ」です。<br>
            仕入・点検の基準を販売同等に整え、コンディションが良く、<br class="is-hidden_sp">
            格安の軽自動車をご用意しております。<br>
            適正な車両管理と透明性の高い運用を心がけています。<br>
            万一の際も販売店直営の迅速対応で安心してご利用いただけます。
          </p>
          <table>
            <tr>
              <th scope="row">
                会社名
              </th>
              <td>
                株式会社SHIMA.GROUP
              </td>
            </tr>
            <tr>
              <th scope="row">
                住所
              </th>
              <td>
                〒497-0041 愛知県海部郡蟹江町南3丁目6
              </td>
            </tr>
            <tr>
              <th scope="row">
                電話番号
              </th>
              <td>
                <a href="tel:0120-995-758">0120-995-758</a>
              </td>
            </tr>
            <tr>
              <th scope="row">
                営業時間
              </th>
              <td>
                10：00～19：00
              </td>
            </tr>
            <tr>
              <th scope="row">
                休業日
              </th>
              <td>
                年中無休
              </td>
            </tr>
          </table>
          <h3>アクセス</h3>
          <p class="store_info--access">近鉄蟹江駅より車で約10分</p>
          <div class="store--map">
            <iframe
              src="<?php echo esc_url($map_src); ?>"
              title="ケーレンタへのアクセスマップ"
              width="100%"
              height="450"
              style="border:0;"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
      </section>
    </main>

    <script type="application/ld+json">
      <?php echo wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>

    <script type="application/ld+json">
      <?php echo wp_json_encode($store_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>