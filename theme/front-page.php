<?php get_header(); ?>
<main>
  <div>
    <div class="swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <article class="top_mv_01">
            <div class="top_mv_01--contents">
              <strong class="top_mv_01--lead">名古屋<span>の</span>格安レンタカー</strong>
              <div class="top_mv_01--dayprice">
                <span class="top_mv_01--mark">最安値<br>挑戦中</span>
                <p>
                  <span class="top_mv_01--one">1</span>日当たり<b>816</b><span class="top_mv_01--yen">円～</span><span class="top_mv_01--taxin">（税込）</span>
                </p>
              </div>

              <h2 class="top_mv_01--weekprice">
                <span class="top_mv_01--one">1</span>週間<strong><b><span class="top_mv_01--dots">7</span>,<span class="top_mv_01--dots">5</span><span class="top_mv_01--dots">0</span><span class="top_mv_01--dots">0</span></b><span class="top_mv_01--yen">円～</strong></span><span class="top_mv_01--taxin">（税込）</span>
              </h2>

              <ul>
                <li>用途に合わせて使い方自由自在</li>
                <li>リーズナブルなレンタカー充実</li>
                <li>ファミリーや営業車としても</li>
                <li>ビジネスにプライベートに！</li>
                <li>業界トップクラスの低価格</li>
              </ul>
            </div>
            <picture class="top_mv_01--bg">
              <!-- SP: まずWebP（500px以下の時） -->
              <source
                srcset="<?php echo get_template_directory_uri(); ?>/img/top/img_mv1_sp.webp"
                media="(max-width: 500px)"
                type="image/webp"
                width="750"
                height="1000" />

              <!-- SP: WebP非対応時のJPEG -->
              <source
                srcset="<?php echo get_template_directory_uri(); ?>/img/top/img_mv1_sp.jpg"
                media="(max-width: 768px)"
                width="375"
                height="426" />

              <!-- PC: まずWebP（メディア条件なし＝SP条件に当たらなければPC用が選ばれる） -->
              <source
                srcset="<?php echo get_template_directory_uri(); ?>/img/top/img_mv1.webp"
                type="image/webp"
                width="1920"
                height="700" />

              <!-- フォールバック（最終手段） -->
              <img
                src="<?php echo get_template_directory_uri(); ?>/img/top/img_mv1.jpg"
                alt=""
                width="1920"
                height="700"
                fetchpriority="high" />
            </picture>
          </article>
        </div>
        <div class="swiper-slide">
          <article class="top_mv_02">
            <div class="top_mv--contents">
              <strong class="top_mv--lead">名古屋<span>の</span>格安レンタカー</strong>
              <div class="top_mv--dayprice">
                <span class="top_mv--mark">最安値<br>挑戦中</span>
                <p>
                  <span class="top_mv--one">1</span>日当たり<b>816</b><span class="top_mv--yen">円～</span><span class="top_mv--taxin">（税込）</span>
                </p>
              </div>

              <h2 class="top_mv--weekprice">
                1<span>週間</span><span>7,500</span><span>円～</span><span>（税込）</span>
              </h2>

              <ul>
                <li>用途に合わせて使い方自由自在</li>
                <li>リーズナブルなレンタカー充実</li>
                <li>ファミリーや営業車としても</li>
                <li>ビジネスにプライベートに！</li>
                <li>業界トップクラスの低価格</li>
              </ul>
            </div>
            <picture class="top_mv--bg">
              <!-- SP: まずWebP（500px以下の時） -->
              <source
                srcset="<?php echo get_template_directory_uri(); ?>/img/top/mv.webp"
                media="(max-width: 500px)"
                type="image/webp"
                width="750"
                height="1000" />

              <!-- SP: WebP非対応時のJPEG -->
              <source
                srcset="<?php echo get_template_directory_uri(); ?>/img/top/mv.jpg"
                media="(max-width: 500px)"
                width="750"
                height="1000" />

              <!-- PC: まずWebP（メディア条件なし＝SP条件に当たらなければPC用が選ばれる） -->
              <source
                srcset="<?php echo get_template_directory_uri(); ?>/img/top/img_mv2.webp"
                type="image/webp"
                width="1920"
                height="700" />

              <!-- フォールバック（最終手段） -->
              <img
                src="<?php echo get_template_directory_uri(); ?>/img/top/img_mv2.jpg"
                alt=""
                width="1920"
                height="700"
                fetchpriority="high" />
            </picture>
          </article>
        </div>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </div>



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
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>


</main>
<?php get_footer(); ?>