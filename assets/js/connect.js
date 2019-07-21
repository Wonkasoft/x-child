// jQuery( document ).ajaxComplete(function( event, xhr, settings ) {
// 	var res = settings.data.split("&");
// 	if(res[1] == "action=friends_add_friend"){
// 		location.reload();
// 	}
// });

jQuery( document ).ready(function() {
var org = window.origin;
var path = location.pathname.split('/');
var followUrl = org+"/"+location.pathname.split('/')[1]+"/"+location.pathname.split('/')[2]+"/followers";
var groupUrl = org+"/"+location.pathname.split('/')[1]+"/"+location.pathname.split('/')[2]+"/groups/create/";
if (jQuery('#subnav').attr('aria-label') == 'Activity menu'){
		jQuery('#subnav .subnav').append('<li id="activity-followers-personal-li" class="bp-personal-sub-tab testing" data-bp-user-scope="followers"><a href='+followUrl+' id="activity-followers" style="outline: none;">Followers</a></li>');
        }

   if (jQuery('#subnav').attr('aria-label') == 'Groups menu'){
		jQuery('#subnav .subnav').append('<li id="create-group-personal-li" class="bp-personal-sub-tab testing" data-bp-user-scope="create"><a href='+groupUrl+' id="create" style="outline: none;">Create a Group</a></li>');
        }
  });

