<?php get_header(); ?>
<main>
  <div class="top_mv">
    <div class="swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <section>
            <div>
              <div>
                <strong>名古屋の格安レンタカー</strong>
                <span>最安値挑戦中</span>

                <p>
                  1日当たり
                  <span>816</span><span>円～</span>
                  <span>（税込）</span>
                </p>

                <h2>
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
                  srcset="<?php echo get_template_directory_uri(); ?>/img/top/mv.webp"
                  type="image/webp"
                  width="1920"
                  height="700" />

                <!-- フォールバック（最終手段） -->
                <img
                  src="<?php echo get_template_directory_uri(); ?>/img/top/mv.jpg"
                  alt=""
                  width="1920"
                  height="700"
                  fetchpriority="high" />
              </picture>
            </div>
          </section>
        </div>
        <div class="swiper-slide">
          <img src="https://jito-site.com/wp-content/uploads/2023/01/2.png" alt="" />
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



</main>
<?php get_footer(); ?>