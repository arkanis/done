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
	<title><?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?></title>
	<link href="styles.css" media="screen" rel="stylesheet">
	<script src="jquery-1.7.2.min.js"></script>
	<script>
		var id = '<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>';
		var user = '<?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?>';
		var event_file = [id, '.json'].join('');
		
		var introduce = function(){
			$.ajax(event_file, { type: 'POST' });
		};
		
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
					
					if ( users.indexOf(user) == -1 )
						introduce();
					
					if ( data.users[user] == true )
						$('button#ready').attr('disabled', 'disabled');
					else
						$('button#ready').removeAttr('disabled');
					
					$('body > header > p').text(data.title || '');
				},
				error: function(){
					$('body > ul > li').remove();
					introduce();
				}
			});
		};
		
		$(document).ready(function(){
			poll();
			setInterval(poll, 1000);
			
			$('button#ready').click(function(){
				$.ajax(event_file + '/self', { type: 'PUT' });
			});
		});
	</script>
</head>
<body>

<header>
	<h1><?= htmlspecialchars($id, ENT_NOQUOTES, 'UTF-8') ?></h1>
	<p></p>
	<button id="ready">I'm done!</button>
</header>

<ul>
</ul>

</body>
</html>