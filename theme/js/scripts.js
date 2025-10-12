(function($, root, undefined) {
  $(function() {
    //ページトップへボタン
    $('#js-totop').click(function() {
      $('html,body').animate({scrollTop: 0}, 'slow');
    });
  });
})(jQuery, this);
