var introduction_pending = false;

function send_introduction(){
	// The introduction_pending variable keeps track of the introduction request. This
	// way we avoid to send multiple introduction requests.
	if (introduction_pending)
		return;
	
	$.ajax(window.location.pathname + '?id=' + id, {
		type: 'POST',
		complete: function(){
			introduction_pending = false;
		}
	});
	introduction_pending = true;
}

function poll(){
	$.ajax('data/' + id, {
		dataType: 'json',
		ifModified: true,
		timeout: 1000,
		success: function(data){
			if (data){
				// Set the event title if present
				if (data.title)
					$('body > header > p').text(data.title);
				else
					$('body > header > p').text('');
				
				var users = [];
				var is_user_in_list = false;
				$.each(data.users, function(name, ready){
					users.push(name);
					if (name == user)
						is_user_in_list = true;
				});
				
				var list = $('<ul>');
				$.each(users.sort(), function(index, name){
					var elem = $('<li>').text(name).data('user', name);
					list.append(elem);
					
					if (data.users[name])
						elem.addClass('ready');
				});
				$('body > ul').replaceWith(list);
				
				// If we are not in the list yet send an introduction.
				if ( !is_user_in_list && !introduction_pending )
					send_introduction();
				
				if ( is_user_in_list && data.users[user] == true )
					$('button#ready').attr('disabled', 'disabled');
				else
					$('button#ready').removeAttr('disabled');
			}
		}
	});
}

$(document).ready(function(){
	send_introduction();
	poll();
	setInterval(poll, 1000);
	
	$('button#ready').click(function(){
		$.ajax(window.location.pathname + '?id=' + id, { type: 'PUT' });
		return false;
	});
});