<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>アグリコラonlineドラフトツール</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?= Asset::css('app.css'); ?>
</head>
<body>
	<div id="app">
		<header-bar></header-bar>
		<router-view></router-view>
	</div>
	<?= Asset::js('materialize.js'); ?>
	<?= Asset::js('app.js'); ?>
</body>
</html>