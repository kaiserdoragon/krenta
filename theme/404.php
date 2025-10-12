<?php get_header(); ?>
<div class="eyecatch">
  <h1>404 NOT FOUND</h1>
</div>
<?php get_template_part('include/common', 'breadcrumb'); //　Breadcrumb NavXTを使わないときは削除?>
<main class="notfound_page">
  <div class="container">
    <h2 class="notfound_page--ttl">お探しのページは見つかりませんでした </h2>
    <p class="notfound_page--paragraph">アクセスしようとしたページが見つかりませんでした。<br>ページが移動または削除されたか、URLの入力間違いの可能性があります。 </p>
    <p class="notfound_page--link">
      <a href="<?php echo home_url(); ?>">≫トップページへ</a>
    </p>
  </div>
</main>
<?php get_footer(); ?>




