<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">
	<meta name="description" content="<?php if (wp_title('', false)): ?><?php bloginfo('name'); ?>の<?php echo trim(wp_title('', false)); ?>のページです。<?php endif; ?><?php bloginfo('description'); ?>">
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-touch-icon.png">
	<!-- <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/reset.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css<?php echo '?' . date('YmdHis'); ?>"> -->
	<?php wp_head(); ?>
</head>

<body>
	<div class="wrap">
		<header class="header">
			<small>
				<div class="container">
					【1日当たり最安の816円！】<br class="is-hidden_pc">名古屋で軽自動車専門の格安・激安レンタカーならKレンタ！<br class="is-hidden_pc">～マンスリーとウィークリーのプランをご用意しています～
				</div>
			</small>
			<div class="header--inner">
				<h1>
					<span>名古屋の格安・激安ウィークリー・マンスリーレンタカー店</span>
					<a href="<?php echo home_url('/'); ?>">
						<img src="<?php echo get_template_directory_uri(); ?>/img/common/logo.png" alt="名古屋の格安レンタカー店「Kレンタ」" width="429" height="89">
					</a>
				</h1>
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
			<div class="header--menu">
				<div class="container">
					<ul>
						<li><a href="<?php echo home_url(); ?>">ホーム</a></li>
						<li><a href="<?php echo home_url('/price'); ?>">車種・料金</a></li>
						<li><a href="<?php echo home_url('/flow'); ?>">ご利用の流れ</a></li>
						<li><a href="<?php echo home_url('/store'); ?>">店舗紹介</a></li>
						<li><a href="<?php echo home_url('/contact'); ?>">お問い合わせ</a></li>
					</ul>
				</div>
			</div>
			<nav class="gnav">
				<div class="container">
					<ul>
						<li><a href="<?php echo home_url(); ?>">ホーム</a></li>
						<li><a href="<?php echo home_url('/price'); ?>">車種・料金</a></li>
						<li><a href="<?php echo home_url('/flow'); ?>">ご利用の流れ</a></li>
						<li><a href="<?php echo home_url('/store'); ?>">店舗紹介</a></li>
						<li><a href="<?php echo home_url('/contact'); ?>">お問い合わせ</a></li>
					</ul>
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
			</nav>
			<!-- メニューボタン -->
			<button class="gnav_btn">
				<div>
					<span class="gnav_btn--line"></span>
					<span class="gnav_btn--line"></span>
					<span class="gnav_btn--line"></span>
				</div>
				<span class="gnav_btn--txt">メニュー</span>
			</button>
		</header>

		<div id="js-fixed-header" class="fixed-header">
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