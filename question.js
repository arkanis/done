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