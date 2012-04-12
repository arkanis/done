function poll(){
	$.ajax('data/' + id, {
		dataType: 'json',
		ifModified: true,
		timeout: 1000,
		success: function(data){
			if (data){
				if (data.title)
					$('body > header > p').text(data.title);
				else
					$('body > header > p').text('');
				
				var this_user_ready = false;
				$.each(data.users, function(name, ready){
					if (ready && name == user)
						this_user_ready = true;
					
					var elem = $('#user_' + name);
					if (elem.length == 0){
						elem = $('<li>').text(name).attr('id', 'user_' + name);
						$('ul').append(elem);
					}
					if (ready)
						elem.addClass('ready');
					else
						elem.removeClass('ready');
				});
				
				if (this_user_ready)
					$('button#ready').attr('disabled', 'disabled');
				else
					$('button#ready').removeAttr('disabled');
			}
		}
	});
}

$(document).ready(function(){
	poll();
	setInterval(poll, 1000);
	
	$('button#ready').click(function(){
		$.ajax(window.location.pathname + '?id=' + id, { type: 'POST' });
		return false;
	});
});