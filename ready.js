$(document).ready(function(){
	$('button#reset').click(function(){
		$.ajax(window.location.pathname + window.location.search, { type: 'PUT' });
		$('input#title').val('').keypress();
		return false;
	});
	
	$('input#title').keypress(function(){
		var elem = $(this);
		if ( elem.data('timer') )
			clearTimeout(elem.data('timer'));
		timer = setTimeout(function(){
			$.ajax(window.location.pathname + window.location.search, { type: 'PUT', data: { title: elem.val() } });
		}, 500);
		elem.data('timer', timer);
	});
});