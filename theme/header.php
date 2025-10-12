<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">
	<!-- <meta name="description" content="<?php if (wp_title('', false)): ?><?php bloginfo('name'); ?>の<?php echo trim(wp_title('', false)); ?>のページです。<?php endif; ?><?php bloginfo('description'); ?>"> -->
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-touch-icon.png">
	<?php wp_head(); ?>
</head>

<body>
	<div class="wrap">
		<header class="header">
			<<?php if (is_front_page()): ?>h1<?php else: ?>p<?php endif; ?> class="header--logo">
				<a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
			</<?php if (is_front_page()): ?>h1<?php else: ?>p<?php endif; ?>>
			<?php if (has_nav_menu('global-menu')) { //管理画面からメニュー登録する時用のコード
			?>
				<?php add_globalmenu(); ?>
			<?php } ?>
		</header>