<?php get_header(); ?>
  <div class="eyecatch">
    <h1>お知らせ</h1>
  </div>

<?php get_template_part('include/common', 'breadcrumb'); //　Breadcrumb NavXTを使わないときは削除?>

  <div class="has_sidebar news_page">
    <main>
      <?php if(have_posts()): while(have_posts()) : the_post(); ?>
        <article class="post_single">
          <h2><?php the_title(); ?></h2>
          <div class="post_meta">
            <time class="post_meta--date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
            <ul class="post_meta--cat_list">
              <?php categories_label() ?>
            </ul>
            <p class="post_meta--tag">
              <?php echo get_the_tag_list('#', ' #', ''); ?>
            </p>
          </div>
          <div class="post_content">
            <?php the_content(); ?>
          </div>
        </article>

        <?php //comments_template(); //コメント機能を使いたい場合は利用 ?>

        <ul class="paging">
          <li class="paging--item paging--item-next">
            <?php if(get_next_post()): ?>
              <?php next_post_link('%link', '%title', false); ?>
            <?php endif; ?>
          </li>
          <li class="paging--item paging--item-gotolist">
            <a href="<?php echo home_url(); ?>/news/">一覧へ戻る</a>
          </li>
          <li class="paging--item paging--item-prev">
            <?php if(get_previous_post()): ?>
              <?php previous_post_link('%link', '%title', false); ?>
            <?php endif; ?>
          </li>
        </ul>
      <?php endwhile; ?><?php endif; ?>
    </main>
    <?php get_sidebar(); ?>
  </div>
<?php get_footer(); ?>