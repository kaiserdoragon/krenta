<?php get_header(); ?>
  <div class="eyecatch">
    <h1>お知らせ</h1>
  </div>
<?php get_template_part('include/common', 'breadcrumb'); //　Breadcrumb NavXTを使わないときは削除?>
  <div class="has_sidebar news_page">
    <main>
      <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
        <section class="post_excerpt">
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="post_excerpt--img">
            <?php if(has_post_thumbnail()): // サムネイルを持っているとき ?>
              <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail(); ?>
              </a>
            <?php else: // サムネイルを持っていない ?><?php endif; ?>
          </div>
          <div class="post_excerpt--txt">
            <div class="post_meta">
			  <time class="post_meta--date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
              <ul class="post_meta--cat_list">
                <?php categories_label() ?>
              </ul>
              <p class="post_meta--tag">
                <?php echo get_the_tag_list('#', ' #', ''); ?>
              </p>
            </div>
            <?php the_excerpt(); ?>
          </div>
        </section>
      <?php endwhile; ?><?php endif; ?>
      <div class="pagination"><?php wp_pagination();//ページネーション ?></div>
    </main>
    <?php get_sidebar(); ?>
  </div>
<?php get_footer(); ?>