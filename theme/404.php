<?php get_header(); ?>
<div class="eyecatch">
  <h1>404 NOT FOUND</h1>
  <img src="<?php echo get_template_directory_uri(); ?>/img/404/404.jpg" alt="" width="1920" height="400">
</div>
<div class="breadcrumbs--wrap">
  <?php
  get_template_part('include/common', 'breadcrumb');
  ?>
</div>
<main class="notfound_page sec">
  <div class="container">
    <h2 class="notfound_page--ttl">お探しのページは見つかりませんでした </h2>
    <p class="notfound_page--paragraph">アクセスしようとしたページが見つかりませんでした。<br>ページが移動または削除されたか、URLの入力間違いの可能性があります。 </p>
    <p class="notfound_page--link">
      <a href="<?php echo home_url(); ?>">≫トップページへ</a>
    </p>
  </div>
</main>
<?php get_footer(); ?>