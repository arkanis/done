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