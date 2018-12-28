<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="csrf-token" content="<?php echo \Config::get('security.csrf_token_key');?>">
	<link href="/assets/css/app.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="app"></div>
<script type="text/javascript">
    window.fuel = window.fuel || {};
    window.fuel.csrfToken = "{{csrf_token()}}";
</script>
<script src="/assets/js/app.js"></script>
</body>
</html>