<?php get_header(); ?>
<div class="eyecatch">
  <h1>お知らせ</h1>
  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/news/news.jpg" alt="" width="1920" height="400">
</div>

<div class="breadcrumbs--wrap">
  <?php
  get_template_part('include/common', 'breadcrumb');
  ?>
</div>

<main>
  <?php if (have_posts()): while (have_posts()) : the_post(); ?>

      <section class="news_single">
        <div class="news_single--inner">
          <h2><?php the_title(); ?></h2>

          <time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date('Y.m.d'); ?></time>
          <?php
          $categories = get_the_category();
          if (! empty($categories)) {
            echo '<ul class="news_single--categories">';
            foreach ($categories as $category) {
              echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '"">' . esc_html($category->name) . '</a></li>';
            }
            echo '</ul>';
          }
          ?>

          <?php the_content(); ?>

          <ul class="paging">
            <li class="paging--item paging--item-next">
              <?php if (get_next_post()): ?>
                <?php next_post_link('%link', '%title', false); ?>
              <?php endif; ?>
            </li>
            <li class="paging--item paging--item-gotolist">
              <a href="<?php echo esc_url(home_url('/news/')); ?>">一覧へ戻る</a>
            </li>
            <li class="paging--item paging--item-prev">
              <?php if (get_previous_post()): ?>
                <?php previous_post_link('%link', '%title', false); ?>
              <?php endif; ?>
            </li>
          </ul>
        </div>
      </section>

      <?php endwhile; ?><?php endif; ?>
</main>
<?php get_footer(); ?>