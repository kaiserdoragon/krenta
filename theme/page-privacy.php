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

      <section class="privacy_policy sec">
        <div class="container">
          <h2 class="ttl">プライバシーポリシー</h2>
          <p class="privacy_policy--lead">激安・格安レンタカー「ケーレンタ」のプライバシーポリシーです。</p>
          <article class="privacy_policy--content">
            <h3>個人情報の収集</h3>
            <p class="u-mb30">
              当社は､適生かつ公正な手段によって､個人情報を収集します。<br>
              お客様から個人情報を収集する時は、その目的を明確にします。
            </p>
            <ul class="u-mb30">
              <li>
                ・レンタカーの事業許可を受けた事業者として、貸渡契約締結時に貸渡証を作成するなど、<br class="is-hidden_sp">
                事業許可の条件として義務づけられている事項を完遂するため。
              </li>
              <li>
                ・お客様に、レンタカー及びこれらに関連したサービスを提供するため。
              </li>
              <li>
                ・お客様の本人確認及び審査をするため。
              </li>
              <li>
                ・個人情報を統計的に集計･分析し､個人を識別･特定できない形態に加工した統計を作成するため
              </li>
            </ul>
            <p>
              前項､各号に定めていない目的以外にお客様の個人情報を取得する場合は、<br class="is-hidden_sp">
              あらかじめその利用目的を明示して行います。
            </p>
          </article>

          <article class="privacy_policy--content">
            <h3>個人情報の管理</h3>
            <p class="u-mb30">
              当社は、個人情報の正確性を保ち、これを安全に管理します。<br>
              個人情報の紛失、改ざん及び漏洩などを防止するため､適切な情報セキュリティ管理を実施します。
            </p>
          </article>

          <article class="privacy_policy--content">
            <h3>個人情報の利用目的</h3>
            <p class="u-mb30">
              当社は、個人情報を、収集の際に示した利用目的の範囲内で、業務の遂行上必要な限りにおいて利用します。
            </p>
          </article>

          <article class="privacy_policy--content">
            <h3>個人情報の第三者提供</h3>
            <p class="u-mb30">
              当社は、法令に定める場合を除き、個人情報を本人の同意を得ることなく、第三者に提供いたしません。
            </p>
          </article>

          <article class="privacy_policy--content">
            <h3>個人情報保護の組織･体制</h3>
            <p class="u-mb30">
              個人情報保護を全社として取り組む為、個人情報保護管理者を任命し、個人情報の適正な管理を実施します。<br>
              当社は、役員及び従業員に対し、個人情報の保護および適正な管理方法についての教育・研修を実施し、<br class="is-hidden_sp">
              日常業務における個人情報の取扱いを徹底します。
            </p>
          </article>

          <article class="privacy_policy--content">
            <h3>アクセス解析ツールについて</h3>
            <p class="u-mb30">
              当サイトは、Googleが提供するアクセス解析ツール「Googleアナリティクス」を利用しています。<br>
              Googleアナリティクスは、Cookieを使用することでお客様のトラフィックデータを収集しています。<br>
              お客様はブラウザの設定でCookieを無効にすることで、トラフィックデータの収集を拒否できます。<br>
              なお、トラフィックデータからお客様個人を特定することはできません。<br>
              詳しくは <a href="https://marketingplatform.google.com/about/analytics/terms/jp/" target="_blank" rel="noopener noreferrer">Googleアナリティクス利用規約</a>をご確認ください。
            </p>
          </article>

          <article class="privacy_policy--content">
            <h3>Cookie（クッキー）について</h3>
            <p class="u-mb30">
              Cookie（クッキー）とは、お客様のサイト閲覧履歴を、<br class="is-hidden_sp">
              お客様のコンピュータにデータとして保存しておく仕組みです。<br>
              なお、Cookieに含まれる情報は当サイトや他サイトへのアクセスに関する情報のみであり、<br class="is-hidden_sp">
              氏名、住所、メール アドレス、電話番号などの個人情報は含まれません。<br>
              従って、Cookieに保存されている情報からお客様個人を特定することはできません。
            </p>
          </article>

          <article class="privacy_policy--content">
            <h3>免責事項</h3>
            <p class="u-mb30">
              当サイトに掲載されている情報・資料の内容については、万全の注意を払って掲載しておりますが、<br class="is-hidden_sp">
              掲載された情報の正確性を何ら保証するものではありません。<br>
              従いまして、当サイトに掲載された情報・資料を利用、使用、ダウンロードする等の行為に<br class="is-hidden_sp">
              起因して生じる結果に対し、一切の責任を負いません。<br>
              なお、当サイトに掲載された情報の正確性を鑑みた際に、<br class="is-hidden_sp">
              予告なしで情報の変更・削除を行う場合がございますので、予めご了承ください。
            </p>
          </article>

          <article class="privacy_policy--content">
            <h3>お問い合わせ窓口</h3>
            <p class="u-mb30">
              本ポリシーに関するお問い合わせは，下記の窓口までお願いいたします。
            </p>
            <table>
              <tr>
                <th>会社名</th>
                <td>株式会社SHIMA.GROUP</td>
              </tr>
              <tr>
                <th>住所</th>
                <td>〒497-0041 愛知県海部郡蟹江町南3丁目6</td>
              </tr>
              <tr>
                <th>電話番号</th>
                <td>0120-995-758</td>
              </tr>
              <tr>
                <th>受付時間</th>
                <td>10：00～19：00</td>
              </tr>
            </table>
          </article>

        </div>
      </section>



    </main>

    <script type="application/ld+json">
      <?php echo wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>