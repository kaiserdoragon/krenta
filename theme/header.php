<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">
	<meta name="description" content="<?php if (wp_title('', false)): ?><?php bloginfo('name'); ?>の<?php echo trim(wp_title('', false)); ?>のページです。<?php endif; ?><?php bloginfo('description'); ?>">
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-touch-icon.png">
	<?php wp_head(); ?>
</head>

<body>
	<div class="wrap">
		<header class="header">
			<small>
				<div class="container">
					【1日当たり最安の816円！】名古屋で軽自動車専門の格安・激安レンタカーならKレンタ！～マンスリーとウィークリーのプランをご用意しています～
				</div>
			</small>
			<div class="container">
				<h1>
					<span>名古屋の格安・激安ウィークリー・マンスリーレンタカー店</span>
					<img src="<?php echo get_template_directory_uri(); ?>/img/common/logo.png" alt="名古屋の格安レンタカー店「Kレンタ」" width="300" height="82">
				</h1>
				<div class="header--btn">
					<a href="#">
						レンタカーご予約はコチラ
						<span>24時間いつでも受付中</span>
					</a>
					<a href="#">
						0120-995-758
						<span>年中無休 10：00～19：00</span>
					</a>
				</div>
			</div>
		</header>