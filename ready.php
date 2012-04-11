<?php

if ( isset($_GET['poll']) ){
	header('Content-Type: application/json');
	$ready_user_list = trim(file_get_contents('ready.txt'));
	if ( strlen($ready_user_list) == 0 )
		exit('[]');
	$ready_users = explode("\n", $ready_user_list);
	exit(json_encode($ready_users));
}

if ( isset($_GET['check']) ){
	var_dump($_GET['check']);
	file_put_contents('ready.txt', "\n" . $_GET['check'], FILE_APPEND);
	exit();
}

$users = explode("\n", file_get_contents('kürzel.txt'));
$question = file_get_contents('question.txt');

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Questions</title>
	<script src="jquery-1.7.2.min.js"></script>
	<script>
		function poll(){
			console.log('polling');
			$.ajax('ready.php?poll=true', {
				success: function(data){
					console.log(data);
					for(var i = 0; i < data.length; i++){
						var ready_user = data[i];
						console.log('user: ', ready_user);
						$('#' + ready_user).addClass('ready');
					}
					
					setTimeout(poll, 1000);
				}
			});
		}
		
		$(document).ready(function(){
			setTimeout(poll, 1000);
		});
	</script>
	<style>
		ul { margin: 0; padding: 0; list-style: none; }
		ul > li { float: left; width: 5em; }
		ul > li.ready { background-color: green; }
	</style>
</head>
<body>

<h1><?= $question ?></h1>

<ul>
<? foreach($users as $user): ?>
	<li id="<?= $user ?>"><?= $user ?></li>
<? endforeach ?>
</ul>

</body>
</html>
