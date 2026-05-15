<?php get_header(); ?>

<div class="eyecatch">
  <h1>お知らせ</h1>
  <img src="<?php echo get_template_directory_uri(); ?>/img/news/news.jpg" alt="" width="1920" height="400">
</div>

<div class="breadcrumbs--wrap">
  <?php
  get_template_part('include/common', 'breadcrumb');
  ?>
</div>

<main>
  <div class="news_archive--wrap">
    <section class="news_archive">
      <h2 class="ttl">お知らせ</h2>
      <div class="news_archive--inner">
        <?php if (have_posts()) : ?>
          <?php while (have_posts()) : the_post(); ?>
            <div class="news_archive--contents">
              <div class="news_archive--info">
                <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
                <?php
                $categories = get_the_category();
                if (! empty($categories)) {
                  echo '<span>';
                  echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                  echo '</span>';
                }
                ?>
              </div>
              <h2>
                <a href="<?php the_permalink(); ?>">
                  <?php
                  $title = get_the_title();
                  $limit = 20; // 表示したい上限文字数

                  if (mb_strlen($title) > $limit) {
                    $title = mb_substr($title, 0, $limit) . '...';
                  }
                  echo esc_html($title);
                  ?>
                </a>
              </h2>
            </div>
          <?php endwhile; ?>
        <?php else : ?>
          <p>該当する記事が見つかりませんでした。</p>
        <?php endif; ?>
      </div>
    </section>
    <div class="pagination">
      <?php wp_pagination(); ?>
    </div>
  </div>
</main>


<?php get_footer(); ?>