<?php

$id = basename(@$_GET['id']);
$user = str_replace("\n", '', @$_SERVER['PHP_AUTH_USER']);

if ( empty($id) or empty($user) )
	die('ID and HTTP basic auth user is required');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Ready users in <?= htmlspecialchars($id, ENT_NOQUOTES, 'UTF-8') ?></title>
	<link href="../styles.css" media="screen" rel="stylesheet">
	<script src="../jquery-1.7.2.min.js"></script>
	<script>
		var id = '<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>';
		var user = '<?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?>';
		var event_file = ['../', id, '.json'].join('');
		
		var poll = function(){
			$.ajax(event_file, {
				ifModified: false,
				cache: false,
				dataType: 'json',
				success: function(data){
					if ( !data ) return;
					
					var users = [];
					$.each(data.users, function(name, ready){
						users.push(name);
					});
					
					var updated_list = $('<ul>');
					$.each(users.sort(), function(index, name){
						var elem = $('<li>').text(name).data('user', name);
						updated_list.append(elem);
						
						if (data.users[name])
							elem.addClass('ready');
					});
					$('body > ul').replaceWith(updated_list);
				},
				error: function(){
					$('body > ul > li').remove();
				}
			});
		};
		
		$(document).ready(function(){
			poll();
			setInterval(poll, 1000);
			
			$('button#reset').click(function(){
				$.ajax(event_file, { type: 'DELETE' });
				$('input#title').val('');
				return false;
			});
			
			$('input#title').keypress(function(){
				var elem = $(this);
				if ( elem.data('timer') )
					clearTimeout(elem.data('timer'));
				elem.data('timer', setTimeout(function(){
					$.ajax(event_file, { type: 'PUT', data: { title: elem.val() } });
				}, 500));
			});
		});
	</script>
</head>
<body>

<header>
	<h1><?= htmlspecialchars($id, ENT_NOQUOTES, 'UTF-8') ?></h1>
	<input type="text" id="title" placeholder="Title">
	<button id="reset">Reset</button>
</header>

<ul>
</ul>

</body>
</html>