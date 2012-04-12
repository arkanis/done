<?php

require('common.php');

$id = basename(@$_GET['id']);
$user = str_replace("\n", '', @$_SERVER['PHP_AUTH_USER']);

if ( empty($id) or empty($user) )
	die('ID and HTTP basic auth user is required');

// Users send an AJAX POST request to set the title or clear the ready list
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	if ( isset($_POST['title']) ){
		read_and_update_data($id, function($data) use($user) {
			$data['title'] = $_POST['title'];
			return $data;
		});
	} else {
		read_and_update_data($id, function($data) use($user) {
			foreach($data['users'] as $name => $ready)
				$data['users'][$name] = false;
			return $data;
		});
	}
	exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Questions</title>
	<link href="styles.css" media="screen" rel="stylesheet">
	<script src="jquery-1.7.2.min.js"></script>
	<script>
		var id = '<?= ha($id) ?>';
		var user = '<?= ha($user) ?>';
	</script>
	<script src="question.js"></script>
	<script src="ready.js"></script>
</head>
<body>

<header>
	<h1><?= h($id) ?></h1>
	<input type="text" id="title" placeholder="Title">
	<button id="reset">Reset</button>
</header>

<ul>
</ul>

</body>
</html>