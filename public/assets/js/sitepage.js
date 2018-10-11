$(document).ready(function(e) {
	$('[data-toggle="tooltip"]').click(function(e) {
       //$('[data-toggle="tooltip"]').tooltip('toggle');
    });
	
	$(function () {
 		$('[data-toggle="tooltip"]').tooltip({html: true});
	});
	
	if(actionName!='login' && actionName!='forgotpassword'){
		$("button[type=submit]").removeClass('SiteButton1');
		$("button[type=submit]").addClass('SiteButton');
	}
	
	$(function () {
 		$('[data-toggle="popover"]').popover({html: true});
		$('.profilePopOver').popover({html: true});
	});
	
	if(controllerName=='profilecontroller' && actionName=='index')
	{
		$("#user_phone").mask("(999) 99?9-9999");
	}
});

window.addEventListener("orientationchange", function() {
  // Announce the new orientation number
}, false);
