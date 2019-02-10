<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>Agricola Online Draft</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ff9800">
	<meta name="msapplication-TileColor" content="#ff9800">
	<meta name="theme-color" content="#ff9800">
	<meta name="description" content="Agricola Online Draftは、アグリコラのドラフトを手軽に楽しめるWebアプリです。旧版EIKはもちろん、拡張デッキにも対応しています。">
	<meta name="author" content="ぶらつき学生連盟,Arthur">
	<meta property="twitter:card" content="summary">
	<?= Html::meta([0 => ['property' => 'og:image', 'content' => Uri::create('/apple-touch-icon.png')]]); ?>
	<?= Asset::css('app.css'); ?>
</head>
<body class="grey lighten-5">
	<div id="app">
		<header-bar></header-bar>
		<router-view></router-view>
	</div>
	<?= Security::js_fetch_token(); ?>
	<?= Asset::js('materialize.js'); ?>
	<?= Asset::js('app.js'); ?>
</body>
</html>