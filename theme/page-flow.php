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

        <div class="breadcrumbs--wrap">
          <?php get_template_part('include/common', 'breadcrumb'); ?>
        </div>

        <section class="flow_order sec" aria-labelledby="flow-order-title">
          <div class="flow_order--inner">
            <h2 id="flow-order-title" class="ttl">ご予約からご返却までの流れ</h2>

            <ol>
              <li>
                <div>
                  <h3>利用したいレンタカーを選ぶ</h3>
                  <img
                    src="<?php echo esc_url($template_uri . '/img/flow/order_01.jpg'); ?>"
                    alt="車種と料金ページから利用したいレンタカーを選ぶイメージ"
                    width="350"
                    height="300"
                    class="is-hidden_pc"
                    loading="lazy"
                    decoding="async">
                  <p>
                    車種と料金ページよりご希望の車種をお探しください。<br>
                    詳細ページにて車両情報や積載部分の内寸などをご確認いただけます。
                  </p>
                  <a href="<?php echo esc_url(home_url('/price/')); ?>" class="flow_order--contact">
                    レンタカーの車種・料金を見る
                  </a>
                </div>
                <img
                  src="<?php echo esc_url($template_uri . '/img/flow/order_01.jpg'); ?>"
                  alt="車種と料金ページから利用したいレンタカーを選ぶイメージ"
                  width="350"
                  height="300"
                  class="is-hidden_sp"
                  loading="lazy"
                  decoding="async">
              </li>

              <li>
                <div>
                  <h3>ご予約・お申し込み</h3>
                  <img
                    src="<?php echo esc_url($template_uri . '/img/flow/order_02.jpg'); ?>"
                    alt="レンタカーを本サイトまたは電話で予約するイメージ"
                    width="350"
                    height="300"
                    class="is-hidden_pc"
                    loading="lazy"
                    decoding="async">
                  <p>
                    本サイトまたはお電話にてご予約ください。
                  </p>
                  <div class="header--btn">
                    <a href="<?php echo esc_url(home_url('/reservation/')); ?>">
                      レンタカーを予約する
                      <span>24時間いつでも受付中</span>
                    </a>
                    <a href="tel:0120-995-758">
                      0120-995-758
                      <span>年中無休 10：00～19：00</span>
                    </a>
                  </div>
                </div>
                <img
                  src="<?php echo esc_url($template_uri . '/img/flow/order_02.jpg'); ?>"
                  alt="レンタカーを本サイトまたは電話で予約するイメージ"
                  width="350"
                  height="300"
                  class="is-hidden_sp"
                  loading="lazy"
                  decoding="async">
              </li>

              <li>
                <div>
                  <h3>予約内容の確認と利用方法のご案内</h3>
                  <img
                    src="<?php echo esc_url($template_uri . '/img/flow/order_03.jpg'); ?>"
                    alt="スタッフが予約内容と空車状況を確認するイメージ"
                    width="350"
                    height="300"
                    class="is-hidden_pc"
                    loading="lazy"
                    decoding="async">
                  <p>
                    ご予約後、当社スタッフが予約内容と空車状況を確認いたします。<br>
                    確認後、お手続き方法、レンタカーの引き渡し方法、注意事項などをご案内いたします。
                  </p>
                </div>
                <img
                  src="<?php echo esc_url($template_uri . '/img/flow/order_03.jpg'); ?>"
                  alt="スタッフが予約内容と空車状況を確認するイメージ"
                  width="350"
                  height="300"
                  class="is-hidden_sp"
                  loading="lazy"
                  decoding="async">
              </li>

              <li>
                <div>
                  <h3>ご来店・ご契約</h3>
                  <img
                    src="<?php echo esc_url($template_uri . '/img/flow/order_04.jpg'); ?>"
                    alt="店舗でレンタカーの契約手続きを行うイメージ"
                    width="350"
                    height="300"
                    class="is-hidden_pc"
                    loading="lazy"
                    decoding="async">
                  <p>
                    ご予約のお時間までにご来店をお願いいたします。<br>
                    ご予約時間に遅れる場合は、事前に必ずご予約店舗までご連絡ください。<br>
                    ご来店・ご契約の際は、レンタカーご契約時に必要なものをご用意ください。
                  </p>

                  <ul class="flow_order--document">
                    <li>
                      <a href="<?php echo esc_url($template_uri . '/pdf/契約書.pdf'); ?>" download="契約書.pdf">
                        契約書
                        <span>※PDFファイルがダウンロードされます</span>
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo esc_url($template_uri . '/pdf/同意書.pdf'); ?>" download="同意書.pdf">
                        同意書
                        <span>※PDFファイルがダウンロードされます</span>
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo esc_url($template_uri . '/pdf/注意事項.pdf'); ?>" download="注意事項.pdf">
                        注意事項
                        <span>※PDFファイルがダウンロードされます</span>
                      </a>
                    </li>
                  </ul>
                </div>
                <img
                  src="<?php echo esc_url($template_uri . '/img/flow/order_04.jpg'); ?>"
                  alt="店舗でレンタカーの契約手続きを行うイメージ"
                  width="350"
                  height="300"
                  class="is-hidden_sp"
                  loading="lazy"
                  decoding="async">
              </li>

              <li>
                <div>
                  <h3>ご出発</h3>
                  <img
                    src="<?php echo esc_url($template_uri . '/img/flow/order_05.jpg'); ?>"
                    alt="車体チェックと操作説明を受けて出発するイメージ"
                    width="350"
                    height="300"
                    class="is-hidden_pc"
                    loading="lazy"
                    decoding="async">
                  <p>
                    レンタカーの車体チェック、お車の操作方法、万が一の際の対応方法を<br class="is-hidden_sp">
                    ご説明いたします。車体チェックと操作説明が完了しましたらご出発いただけます。<br>
                    ※未成年者が契約者となる場合、保護者様へご連絡させていただく場合がございます。<br>
                    あらかじめご了承ください。
                  </p>
                </div>
                <img
                  src="<?php echo esc_url($template_uri . '/img/flow/order_05.jpg'); ?>"
                  alt="車体チェックと操作説明を受けて出発するイメージ"
                  width="350"
                  height="300"
                  class="is-hidden_sp"
                  loading="lazy"
                  decoding="async">
              </li>

              <li>
                <div>
                  <h3>ご返却</h3>
                  <img
                    src="<?php echo esc_url($template_uri . '/img/flow/order_06.jpg'); ?>"
                    alt="レンタカーを店舗へ返却するイメージ"
                    width="350"
                    height="300"
                    class="is-hidden_pc"
                    loading="lazy"
                    decoding="async">
                  <p>
                    レンタカーご利用期間の最終日に、レンタカーを当社までご返却ください。<br>
                    ご利用期間の延長やご利用期間前の返却については、あらかじめメールまたはお電話にてご連絡ください。<br>
                    ※ご利用期間の延長については、レンタカーの空き状況により対応できない場合がございます。
                  </p>
                </div>
                <img
                  src="<?php echo esc_url($template_uri . '/img/flow/order_06.jpg'); ?>"
                  alt="レンタカーを店舗へ返却するイメージ"
                  width="350"
                  height="300"
                  class="is-hidden_sp"
                  loading="lazy"
                  decoding="async">
              </li>
            </ol>
          </div>
        </section>

        <section class="flow_belongings sec" aria-labelledby="flow-belongings-title">
          <div class="flow_belongings--inner">
            <h2 id="flow-belongings-title" class="ttl">レンタカーお引き渡し時にご持参いただくもの</h2>

            <ul>
              <li>
                <img
                  src="<?php echo esc_url($template_uri . '/img/flow/belongings_01.jpg'); ?>"
                  alt="レンタカー契約時に必要な免許証のイメージ"
                  width="320"
                  height="240"
                  loading="lazy"
                  decoding="async">
              </li>
              <li>
                <img
                  src="<?php echo esc_url($template_uri . '/img/flow/belongings_02.jpg'); ?>"
                  alt="レンタカー契約時に必要な印鑑のイメージ"
                  width="320"
                  height="240"
                  loading="lazy"
                  decoding="async">
              </li>
              <li>
                <img
                  src="<?php echo esc_url($template_uri . '/img/flow/belongings_03.jpg'); ?>"
                  alt="レンタカー契約時に必要な書類のイメージ"
                  width="320"
                  height="240"
                  loading="lazy"
                  decoding="async">
              </li>
            </ul>

            <p>
              車の引き渡し日当日は、予約時間までに<br class="is-hidden_sp">
              <span>免許証・印鑑・ご利用料金・書類</span>をご持参の上、当社までお越しください。<br>
              やむを得ずご予約時間に遅れる場合は、必ずお電話でご連絡ください。<br>
              事前に書類をダウンロード・印刷してご持参いただくと、お手続きがスムーズです。<br>
              ダウンロードできない場合は、店舗にてご用意いたします。
            </p>

            <a class="flow_belongings--banner" href="tel:0120-995-758">
              <img
                src="<?php echo esc_url($template_uri . '/img/flow/belongings_banner.jpg'); ?>"
                alt="お電話でのご予約・お問い合わせ 0120-995-758"
                width="675"
                height="200"
                loading="lazy"
                decoding="async">
            </a>
          </div>
        </section>

        <section class="flow_question sec" id="question" aria-labelledby="flow-question-title">
          <div class="flow_question--inner">
            <h2 id="flow-question-title" class="ttl">保険・補償についてのよくあるご質問</h2>

            <dl>
              <dt>
                <span>Q</span>
                <p>車の基本料金のほかに、保険に加入する必要はありますか？</p>
              </dt>
              <dd>
                <span>A</span>
                <p>別途ご加入いただく必要があります。保険料など詳しくはお電話（0120-995-758）にてお問い合わせください。</p>
              </dd>
            </dl>

            <dl>
              <dt>
                <span>Q</span>
                <p>万が一事故を起こしてしまった場合はどうすればよいですか？</p>
              </dt>
              <dd>
                <span>A</span>
                <p>
                  事故が発生した場合は、現場より警察への連絡と当社への連絡を必ず行ってください。<br>
                  ご連絡がない場合、保険や補償が適用されない場合があります。この場合、修理代金などはお客様のご負担となります。
                </p>
              </dd>
            </dl>

            <div class="js-scrollable">
              <table>
                <thead>
                  <tr>
                    <th scope="col">保険の種類</th>
                    <th scope="col">補償内容</th>
                    <th scope="col">事故免責額（お客様負担）</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>対人賠償保険</td>
                    <td>無制限</td>
                    <td rowspan="3">50,000円（税込）</td>
                  </tr>
                  <tr>
                    <td>対物賠償保険</td>
                    <td>無制限</td>
                  </tr>
                  <tr>
                    <td>車両補償</td>
                    <td>内容をご確認ください</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="flow_question--download u-mt80">
              <p class="u-mb15">レンタカーご契約時に必要な書類は以下よりダウンロード可能です。</p>

              <ul class="flow_order--document">
                <li>
                  <a href="<?php echo esc_url($template_uri . '/pdf/契約書.pdf'); ?>" download="契約書.pdf">
                    契約書
                    <span>※PDFファイルがダウンロードされます</span>
                  </a>
                </li>
                <li>
                  <a href="<?php echo esc_url($template_uri . '/pdf/同意書.pdf'); ?>" download="同意書.pdf">
                    同意書
                    <span>※PDFファイルがダウンロードされます</span>
                  </a>
                </li>
                <li>
                  <a href="<?php echo esc_url($template_uri . '/pdf/注意事項.pdf'); ?>" download="注意事項.pdf">
                    注意事項
                    <span>※PDFファイルがダウンロードされます</span>
                  </a>
                </li>
              </ul>

              <p>※ダウンロードしたファイルを印刷し、ご記入の上でお持ちいただければ、お手続きがスムーズです。</p>
            </div>
          </div>
        </section>

      </article>
    </main>

    <script type="application/ld+json">
      <?php echo wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>