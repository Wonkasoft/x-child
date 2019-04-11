jQuery( document ).ajaxComplete(function( event, xhr, settings ) {
	var res = settings.data.split("&");
	if(res[1] == "action=friends_add_friend"){
		location.reload();
	}
});