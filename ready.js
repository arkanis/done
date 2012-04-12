$(document).ready(function(){
	$('button#clear').click(function(){
		$.ajax(window.location.pathname + window.location.search, { type: 'POST' });
		$('input#title').val('').keypress();
		return false;
	});
	
	$('input#title').keypress(function(){
		var elem = $(this);
		if ( elem.data('timer') )
			clearTimeout(elem.data('timer'));
		timer = setTimeout(function(){
			$.ajax(window.location.pathname + window.location.search, { type: 'POST', data: { title: elem.val() } });
		}, 500);
		elem.data('timer', timer);
	});
});