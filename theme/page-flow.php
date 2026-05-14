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

          <h1>
            <?php echo esc_html($title); ?>
          </h1>
        </header>
      </article>
      <div class="breadcrumbs--wrap">
        <?php
        get_template_part('include/common', 'breadcrumb');
        ?>
      </div>
      <section class="flow_order sec">
        <div class="flow_order--inner">
          <h2 class="ttl">ご利用の流れ</h2>
          <ol>
            <li>
              <div>
                <h3>利用したいレンタカーを選ぶ</h3>
                <p>
                  車種と料金ページよりご希望の車種を探してください。<br>
                  詳細ページにて車両情報や積載部分の内寸などご確認いただけます。
                </p>
                <a href="<?php echo home_url('/price'); ?>" class="flow_order--contact">車種・料金の詳細はこちらから</a>
              </div>
              <img src="<?php echo get_template_directory_uri(); ?>/img/flow/order_01.jpg" alt="" width="350" height="300">
            </li>
            <li>
              <div>
                <h3>ご予約・申し込み</h3>
                <p>
                  本サイトまたはお電話にてご予約ください。
                </p>
                <div class="header--btn">
                  <a href="<?php echo home_url('/reservation'); ?>">
                    レンタカーのご予約はコチラ
                    <span>24時間いつでも受付中</span>
                  </a>
                  <a href="tel:0120-995-758">
                    0120-995-758
                    <span>年中無休 10：00～19：00</span>
                  </a>
                </div>
              </div>
              <img src="<?php echo get_template_directory_uri(); ?>/img/flow/order_02.jpg" alt="" width="350" height="300">
            </li>
            <li>
              <div>
                <h3>予約内容の確認と利用方法の説明</h3>
                <p>
                  ご予約後、当社スタッフにより予約内容の確認、空車状況の確認をさせていただきます。<br>
                  確認後、お客様にお手続き方法、レンタカーの引き渡し方法、注意事項等を案内させて頂きます。
                </p>
              </div>
              <img src="<?php echo get_template_directory_uri(); ?>/img/flow/order_03.jpg" alt="" width="350" height="300">
            </li>
            <li>
              <div>
                <h3>ご来店・ご契約</h3>
                <p>
                  ご予約のお時間までに必ずご来店をお願いいたします。<br>
                  ご予約時間に遅れる場合は事前に必ずご予約店舗までご連絡をお願いいたします。<br>
                  ご来店・ご契約の際は、レンタカーご契約時にご用意いただくものを必ずご用意ください。
                </p>
                <ul class="flow_order--document">
                  <li>
                    <a href="<?php echo get_template_directory_uri(); ?>/pdf/契約書.pdf" download="契約書">
                      契約書
                      <span>※PDFファイルがダウンロードされます</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo get_template_directory_uri(); ?>/pdf/同意書.pdf" download="同意書">
                      同意書
                      <span>※PDFファイルがダウンロードされます</span>
                    </a>
                  </li>
                  <li>
                    <a href=" <?php echo get_template_directory_uri(); ?>/pdf/注意事項.pdf" download="注意事項">
                      注意事項
                      <span>※PDFファイルがダウンロードされます</span>
                    </a>
                  </li>
                </ul>
              </div>
              <img src=" <?php echo get_template_directory_uri(); ?>/img/flow/order_04.jpg" alt="" width="350" height="300">
            </li>
            <li>
              <div>
                <h3>ご出発</h3>
                <p>
                  レンタカーの車体チェック、お車の操作方法、万が一の際のご対応方法を<br class="is-hidden_sp">
                  説明させていただきます。車体チェックと操作説明が完了しましたらご出発いただけます。<br>
                  ※未成年者が契約者となる場合、保護者様へご連絡させていただく場合がございます。<br>
                  予めご了承ください。
                </p>
              </div>
              <img src=" <?php echo get_template_directory_uri(); ?>/img/flow/order_05.jpg" alt="" width="350" height="300">
            </li>
            <li>
              <div>
                <h3>ご返却</h3>
                <p>
                  レンタカーご利用期間の最終日にレンタカーを当社までご返却ください。<br>
                  ご利用期間の延長やご利用期間前の返却については予めメール・電話にてご連絡ください。<br>
                  ※ ご利用期間の延長についてはレンタカーの空き状況により延長出来ない場合も御座います。
                </p>
              </div>
              <img src=" <?php echo get_template_directory_uri(); ?>/img/flow/order_06.jpg" alt="" width="350" height="300">
            </li>
          </ol>
        </div>
      </section>

      <section class="flow_belongings sec">
        <div class="flow_belongings--inner">
          <h2 class="ttl">レンタカーお引渡し時にご持参頂くもの</h2>
          <ul>
            <li>
              <img src="<?php echo get_template_directory_uri(); ?>/img/flow/belongings_01.jpg" alt="" width="320" height="240">
            </li>
            <li>
              <img src="<?php echo get_template_directory_uri(); ?>/img/flow/belongings_02.jpg" alt="" width="320" height="240">
            </li>
            <li>
              <img src="<?php echo get_template_directory_uri(); ?>/img/flow/belongings_03.jpg" alt="" width="320" height="240">
            </li>
          </ul>
          <p>
            車の引き渡し日当日は予約時間までに<br class="is-hidden_sp">
            <span>免許証・印鑑・ご利用料金・書類</span>をご持参の上、当社までお越しください。<br>
            やむを得ずご予約時間に遅れる場合は必ず電話でご連絡ください。<br>
            ダウンロードしてお持ち頂けるとお手続きは早いです。<br>
            ダウンロード出来ない場合は店舗にてご用意あります。
          </p>
          <a class="flow_belongings--banner" href="tel:0120-995-758">
            <img src="<?php echo get_template_directory_uri(); ?>/img/flow/belongings_banner.jpg" alt="" width="675" height="200">
          </a>
        </div>
      </section>

      <section class="flow_question sec" id="question">
        <div class="flow_question--inner">
          <h2 class="ttl">「保険・補償」についてのご質問とご解答</h2>
          <dl>
            <dt><span>Q</span>車の基本料金のほかに、保険に加入する必要はありますか？</dt>
            <dd><span>A</span>別途ご加入いただく必要があります。保険料など詳しくはお電話（0120-995-758）にてお問合せください。</dd>
          </dl>
          <dl>
            <dt><span>Q</span>万が一事故を起こしてしまった場合。</dt>
            <dd>
              <span>A</span>事故が発生した現場より警察への連絡と、当社への連絡を必ず行ってください。<br>
              連絡がされなかった場合、保険や補償の適応がされません。この場合、修理代金などはお客様でご負担いただきます。
            </dd>
          </dl>
          <div class="js-scrollable">
            <table>
              <thead>
                <tr>
                  <th>保険の種類</th>
                  <th>補償内容</th>
                  <th>事故免責額(お客様負担)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>対人賠償保険</td>
                  <td>無制限</td>
                  <td rowspan="3">50,000円（税込）</td>
                </tr>
                <tr>
                  <td>対人賠償保険</td>
                  <td>無制限</td>
                </tr>
                <tr>
                  <td>対人賠償保険</td>
                  <td>無制限</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="flow_question--download u-mt80">
            <p class="u-mb15">レンタカーご契約時の必要な書類は以下よりダウンロード可能です。</p>
            <ul class="flow_order--document">
              <li>
                <a href="<?php echo get_template_directory_uri(); ?>/pdf/契約書.pdf" download="契約書">
                  契約書
                  <span>※PDFファイルがダウンロードされます</span>
                </a>
              </li>
              <li>
                <a href="<?php echo get_template_directory_uri(); ?>/pdf/同意書.pdf" download="同意書">
                  同意書
                  <span>※PDFファイルがダウンロードされます</span>
                </a>
              </li>
              <li>
                <a href=" <?php echo get_template_directory_uri(); ?>/pdf/注意事項.pdf" download="注意事項">
                  注意事項
                  <span>※PDFファイルがダウンロードされます</span>
                </a>
              </li>
            </ul>
            <p>※ダウンロードしたファイルを印刷した後、ご記入の上お持ちいただければ手続きが早く済みます。</p>
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