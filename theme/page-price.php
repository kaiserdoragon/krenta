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

    $template_uri = get_template_directory_uri();

    $breadcrumb_items = array();
    $position = 1;

    $breadcrumb_items[] = array(
      '@type'    => 'ListItem',
      'position' => $position++,
      'name'     => 'TOP',
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

          <h1 id="page-title">
            <?php echo esc_html($title); ?>
          </h1>
        </header>
      </article>
      <div class="breadcrumbs--wrap">
        <?php
        get_template_part('include/common', 'breadcrumb');
        ?>
      </div>
      <section class="price_kinds sec">
        <div class="container">
          <h2 class="ttl">軽自動車専門のレンタカー店</h2>
          <p class="price_kinds--lead">
            ケーレンタの格安レンタカーは<br class="is-hidden_sp">
            <span class="price_kinds--lead -orange">ウィークリーコース</span>と<span class="price_kinds--lead -blue">マンスリーコース</span>の2プランでご利用いただけます。<br>
            いずれも業界トップクラスの低価格を実現しているので<br class="is-hidden_sp">
            セカンドカーとして、営業車としてお使いください。
          </p>
          <ul>
            <li>
              <a href="<?php echo esc_url(home_url('/price/#price_details_01')); ?>">
                <img src="<?php echo esc_url($template_uri . '/img/price/kinds_01.png'); ?>" alt="軽乗用車Sタイプ" width="124" height="70" loading="lazy" decoding="async">
                <p>軽乗用車Sタイプ</p>
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url('/price/#price_details_02')); ?>">
                <img src="<?php echo esc_url($template_uri . '/img/price/kinds_02.png'); ?>" alt="軽トラックタイプ" width="113" height="79" loading="lazy" decoding="async">
                <p>軽トラックタイプ</p>
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url('/price/#price_details_03')); ?>">
                <img src="<?php echo esc_url($template_uri . '/img/price/kinds_03.png'); ?>" alt="軽自動車Mタイプ" width="126" height="84" loading="lazy" decoding="async">
                <p>軽自動車Mタイプ</p>
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url('/price/#price_details_04')); ?>">
                <img src="<?php echo esc_url($template_uri . '/img/price/kinds_04.png'); ?>" alt="軽自動車Lタイプ" width="124" height="70" loading="lazy" decoding="async">
                <p>軽自動車Lタイプ</p>
              </a>
            </li>
          </ul>
          <div class="container">
            <section class="price_details" id="price_details_01">
              <div class="price_details--inner">
                <h3>軽乗用車Sタイプ</h3>
                <img src="<?php echo esc_url($template_uri . '/img/price/details_01.png'); ?>" alt="軽乗用車Sタイプのレンタカー" width="290" height="165" loading="lazy" decoding="async">
                <p class="price_details--lead">
                  一時的な転勤や長期出張での営業車両として、毎日の通勤からちょっとしたお買い物、<br class="is-hidden_sp">
                  送り迎えなどにもコンパクトサイズの軽四は小回りも利くので大変便利。<br>
                  そんな軽四Sタイプの格安レンタカー！商用車として営業車としても活躍中！
                </p>
                <h4>料金</h4>
                <div class="price_details--breakdown">
                  <dl class="price_details--plan">
                    <dt>ウィークリープラン</dt>
                    <div class="price_details--content -blue">
                      <div>
                        <dd>
                          ウィークリー料金
                          <span class="price_details--num -orange">7,500円</span>
                          <span class="price_details--day">※1日当たり1,071円</span>
                        </dd>
                      </div>
                      <div>
                        <dd>
                          延長料金
                          <span class="price_details--num">1,700円</span>
                          <span class="price_details--day">※1日当たり</span>
                        </dd>
                      </div>
                    </div>
                  </dl>
                  <dl class="price_details--plan -blue">
                    <dt>マンスリープラン</dt>
                    <div class="price_details--content -blue">
                      <div>
                        <dd>
                          マンスリー料金
                          <span class="price_details--num -blue">24,500円</span>
                          <span class="price_details--day">※1日当たり816円</span>
                        </dd>
                      </div>
                      <div>
                        <dd>
                          延長料金
                          <span class="price_details--num">1,400円</span>
                          <span class="price_details--day">※1日当たり</span>
                        </dd>
                      </div>
                    </div>
                  </dl>
                </div>
                <p class="price_details--remarks">※加入任意保険、消費税込み、車両保険別、留意事項</p>
                <a href="<?php echo esc_url(home_url('/reservation/')); ?>">この車両を問い合わせする</a>
              </div>
            </section>
            <section class="price_details" id="price_details_02">
              <div class="price_details--inner">
                <h3>軽トラックタイプ</h3>
                <img src="<?php echo esc_url($template_uri . '/img/price/details_02.png'); ?>" alt="軽トラックタイプのレンタカー" width="265" height="184" loading="lazy" decoding="async">
                <p class="price_details--lead">
                  乗用車には乗らないちょっと大きめの荷物の運搬や、<br class="is-hidden_sp">
                  地元のお祭りやイベントの荷物の搬送、一人暮らしの引越しなどにも<br class="is-hidden_sp">
                  大活躍の軽トラックを激安価格でレンタルいたします！<br>
                  また、農家や工事現場の作業車としても大活躍！格安レンタカーなら経費節減にも協力！
                </p>
                <h4>料金</h4>
                <div class="price_details--breakdown">
                  <dl class="price_details--plan">
                    <dt>ウィークリープラン</dt>
                    <div class="price_details--content -blue">
                      <div>
                        <dd>
                          ウィークリー料金
                          <span class="price_details--num -orange">9,800円</span>
                          <span class="price_details--day">※1日当たり1,400円</span>
                        </dd>
                      </div>
                      <div>
                        <dd>
                          延長料金
                          <span class="price_details--num">1,500円</span>
                          <span class="price_details--day">※1日当たり</span>
                        </dd>
                      </div>
                    </div>
                  </dl>
                  <dl class="price_details--plan -blue">
                    <dt>マンスリープラン</dt>
                    <div class="price_details--content -blue">
                      <div>
                        <dd>
                          マンスリー料金
                          <span class="price_details--num -blue">25,800円</span>
                          <span class="price_details--day">※1日当たり816円</span>
                        </dd>
                      </div>
                      <div>
                        <dd>
                          延長料金
                          <span class="price_details--num">1,300円</span>
                          <span class="price_details--day">※1日当たり</span>
                        </dd>
                      </div>
                    </div>
                  </dl>
                </div>
                <p class="price_details--remarks">※加入任意保険、消費税込み、車両保険別、留意事項</p>
                <a href="<?php echo esc_url(home_url('/reservation/')); ?>">この車両を問い合わせする</a>
              </div>
            </section>
            <section class="price_details" id="price_details_03">
              <div class="price_details--inner">
                <h3>軽自動車Mタイプ</h3>
                <img src="<?php echo esc_url($template_uri . '/img/price/details_03.png'); ?>" alt="軽自動車Mタイプのレンタカー" width="309" height="204" loading="lazy" decoding="async">
                <p class="price_details--lead">
                  ファミリータイプの決定版サイズ！<br>
                  車内も広々しているのでお買い物から家族でお出掛けまでしっかりサポート。<br>
                  名古屋への転勤や長期出張でも資料など沢山積めるので営業車両としてもおススメです。
                </p>
                <h4>料金</h4>
                <div class="price_details--breakdown">
                  <dl class="price_details--plan">
                    <dt>ウィークリープラン</dt>
                    <div class="price_details--content -blue">
                      <div>
                        <dd>
                          ウィークリー料金
                          <span class="price_details--num -orange">9,500円</span>
                          <span class="price_details--day">※1日当たり1,357円</span>
                        </dd>
                      </div>
                      <div>
                        <dd>
                          延長料金
                          <span class="price_details--num">1,900円</span>
                          <span class="price_details--day">※1日当たり</span>
                        </dd>
                      </div>
                    </div>
                  </dl>
                  <dl class="price_details--plan -blue">
                    <dt>マンスリープラン</dt>
                    <div class="price_details--content -blue">
                      <div>
                        <dd>
                          マンスリー料金
                          <span class="price_details--num -blue">29,500円</span>
                          <span class="price_details--day">※1日当たり983円</span>
                        </dd>
                      </div>
                      <div>
                        <dd>
                          延長料金
                          <span class="price_details--num">1,500円</span>
                          <span class="price_details--day">※1日当たり</span>
                        </dd>
                      </div>
                    </div>
                  </dl>
                </div>
                <p class="price_details--remarks">※加入任意保険、消費税込み、車両保険別、留意事項</p>
                <a href="<?php echo esc_url(home_url('/reservation/')); ?>">この車両を問い合わせする</a>
              </div>
            </section>
            <section class="price_details" id="price_details_04">
              <div class="price_details--inner">
                <h3>軽自動車Lタイプ</h3>
                <img src="<?php echo esc_url($template_uri . '/img/price/details_04.png'); ?>" alt="軽自動車Lタイプのレンタカー" width="332" height="200" loading="lazy" decoding="async">
                <p class="price_details--lead">
                  荷物をいっぱい運びたいが雨にぬれては困ってしまう。<br>
                  そんなときに大活躍なワンボックスタイプ。<br>
                  荷物を載せて細い路地でも軽快に走れますので事業用の商用車としてもおススメです。<br>
                  格安レンタカーの貨物タイプならこれで決まり！
                </p>
                <h4>料金</h4>
                <div class="price_details--breakdown">
                  <dl class="price_details--plan">
                    <dt>ウィークリープラン</dt>
                    <div class="price_details--content -blue">
                      <div>
                        <dd>
                          ウィークリー料金
                          <span class="price_details--num -orange">13,500円</span>
                          <span class="price_details--day">※1日当たり1,928円</span>
                        </dd>
                      </div>
                      <div>
                        <dd>
                          延長料金
                          <span class="price_details--num">2,100円</span>
                          <span class="price_details--day">※1日当たり</span>
                        </dd>
                      </div>
                    </div>
                  </dl>
                  <dl class="price_details--plan -blue">
                    <dt>マンスリープラン</dt>
                    <div class="price_details--content -blue">
                      <div>
                        <dd>
                          マンスリー料金
                          <span class="price_details--num -blue">37,500円</span>
                          <span class="price_details--day">※1日当たり1,250円</span>
                        </dd>
                      </div>
                      <div>
                        <dd>
                          延長料金
                          <span class="price_details--num">1,600円</span>
                          <span class="price_details--day">※1日当たり</span>
                        </dd>
                      </div>
                    </div>
                  </dl>
                </div>
                <p class="price_details--remarks">※加入任意保険、消費税込み、車両保険別、留意事項</p>
                <a href="<?php echo esc_url(home_url('/reservation/')); ?>">この車両を問い合わせする</a>
              </div>
            </section>
          </div>
        </div>
      </section>
    </main>

    <script type="application/ld+json">
      <?php echo wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>