<?php get_header(); ?>
<main>
  <div class="top_mv">
    メインビジュアルなど
  </div>

  <section>
    <h2>見出し</h2>
    <p>トップページに設置したい静的コンテンツはここに書きましょう </p>
  </section>

  <section class="top_info">
    <h2 class="top_info--ttl">お知らせ</h2>
    <?php
    $args = array(
      'posts_per_page' => 3,
      'post_type' => 'post', //postは通常の投稿機能
      'post_status' => 'publish'
    );
    $my_posts = get_posts($args);
    ?>
    <dl class="top_info--list">
      <?php foreach ($my_posts as $post): setup_postdata($post); ?>
        <dt class="top_info--term">
          <span class="top_info--time"><?php the_time('Y.m.j'); ?></span>
        </dt>
        <dd class="top_info--detail">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </dd>
      <?php endforeach; ?>
    </dl>
    <?php wp_reset_postdata(); ?>
  </section>

  <section class="top_about">
    <h2 class="top_about--ttl">使い方</h2>
    <p class="top_about--paragraph">テンプレートファイルの中のHTMLやCSSを変更して、オリジナルのデザインを作りましょう。<br> テンプレートは国際化しておらず、ほとんど日本語で書かれています。<br></p>
    <h3 class="top_about--ttl">テンプレート</h3>
    <p class="top_about--paragraph">小規模なコーポレートサイト用の構成です。足したり引いたりして自由に改造しましょう。</p>
    <h3 class="top_about--ttl">CSSやJavaScript</h3>
    <h4 class="top_about--ttl">CSS</h4>
    <p class="top_about--paragraph">/css/style.css 、/css/reset.css を読み込むようにしています<br> 追加のCSSファイルを読み込むときは、functions.phpを確認してください。 </p>
    <h4 class="top_about--ttl">JavaScript</h4>
    <p class="top_about--paragraph">/js/scripts.js を読み込むようにしています<br> 追加のJavaScriptファイルを読み込むときは、functions.phpを確認してください。 </p>
    <h4 class="top_about--ttl">Favicon</h4>
    <p class="top_about--paragraph">img/icon/favicon.ico　と　img/icon/apple-touch-icon.png　は変更して使ってください。 </p>
    <h3 class="top_about--ttl">プラグイン</h3>
    <p class="top_about--paragraph">テーマを適用すると、管理画面に推奨プラグインのインストールメッセージが表示されます。<br>使いたい機能があればそこから入れると簡単です。 </p>
  </section>

  <div class="swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">Slide 1</div>
      <div class="swiper-slide">Slide 2</div>
      <div class="swiper-slide">Slide 3</div>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-scrollbar"></div>
  </div>
</main>
<?php get_footer(); ?>