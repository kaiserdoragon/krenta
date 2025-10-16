<footer class="footer">
  <div class="footer--inner">
    <div class="container">
      <div>
        <div class="footer--logo">
          <small>名古屋の格安・激安ウィークリー・マンスリーレンタカー店</small>
          <a href="<?php echo home_url('/'); ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/img/common/logo.png" alt="名古屋の格安レンタカー店「Kレンタ」" width="300" height="82">
          </a>
        </div>
        <address>
          株式会社SHIMA.GROUP<br>
          〒497-0041 愛知県海部郡蟹江町南3丁目6<br>
          電話：0120-995-758<br>
          年中無休　10:00～19:00<br>
        </address>
      </div>
      <ul>
        <li><a href="<?php echo home_url('/price'); ?>">車種・料金</a></li>
        <li><a href="<?php echo home_url('/flow'); ?>">ご利用の流れ</a></li>
        <li><a href="<?php echo home_url('/store'); ?>">店舗紹介</a></li>
        <li><a href="<?php echo home_url('/contact'); ?>">お問い合わせ</a></li>
        <li><a href="<?php echo home_url('/'); ?>">保険・補償</a></li>
        <li><a href="<?php echo home_url('/privacy'); ?>">プライバシーポリシー</a></li>
      </ul>
    </div>
  </div>
  <p class="footer--copy"><small>Copyright Kレンタ All Rights Reseved.</small></p>
</footer>
</div>
<?php wp_footer(); ?>
</body>

</html>