<?php get_header(); ?>
<?php get_template_part( 'include/common', 'breadcrumb' ); //　Breadcrumb NavXTを使わないときは削除?>
<?php
if (have_posts()) :
    while (have_posts()) : the_post();
        the_title();
        the_content();
    endwhile;
endif;
?>
<?php wp_pagination();//ページネーション ?>
<?php get_footer(); ?>