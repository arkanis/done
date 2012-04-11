<?php

require('common.php');

$id = basename($_GET['id']);
$user = str_replace("\n", '', $_SERVER['PHP_AUTH_USER']);

if ( empty($id) or empty($user) )
	die('ID and HTTP basic auth user is required');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Questions</title>
	<link href="styles.css" media="screen" rel="stylesheet">
	<script src="jquery-1.7.2.min.js"></script>
	<script>
		var id = '<?= ha($id) ?>';
		var user = '<?= ha($user) ?>';
	</script>
	<script src="ready.js"></script>
</head>
<body>

<h1><?= h($id) ?><input type="text" id="title"></h1>

<ul>
</ul>

</body>
</html>