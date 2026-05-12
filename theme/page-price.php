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
      'name'     => get_bloginfo('name'),
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

        <?php
        get_template_part('include/common', 'breadcrumb');
        ?>

        <div>
          <?php the_content(); ?>
        </div>

      </article>
      <section class="price_kinds sec">
        <div class="container">
          <h2 class="ttl">軽自動車専門のレンタカー店</h2>
          <p class="price_kinds--lead">
            Kレンタの格安レンタカーは<br class="is-hidden_sp">
            <span class="price_kinds--lead -orange">ウィークリーコース</span>と<span class="price_kinds--lead -blue">マンスリーコース</span>の２プランでご利用いただけます。<br>
            何れも業界トップクラスの低価格を実現しているので<br class="is-hidden_sp">
            セカンドカーとして、営業車としてお使いください。
          </p>
        </div>
      </section>
    </main>

    <script type="application/ld+json">
      <?php echo wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>