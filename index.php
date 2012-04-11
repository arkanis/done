<?php
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
		$(document).ready(function(){
			$('ul > li').click(function(){
				var elem = $(this);
				var id= elem.attr('id');
				$.ajax('ready.php?check=' + id, {
					success: function(){
						elem.addClass('ready');
					}
				});
			});
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