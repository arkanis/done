<?php

require('common.php');

$id = basename($_GET['id']);
$user = str_replace("\n", '', $_SERVER['PHP_AUTH_USER']);

if ( empty($id) or empty($user) )
	die('ID and HTTP basic auth user is required');

// Add current user to the user list of this poll if he/she isn't already in there
read_and_update_data($id, function($data) use($user) {
	if ( !in_array($user, $data['users']) ){
		$data['users'][$user] = false;
		return $data;
	}
});

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
	<script src="question.js"></script>
</head>
<body>

<h1><?= h($id) ?><em></em></h1>

<ul>
</ul>

</body>
</html>