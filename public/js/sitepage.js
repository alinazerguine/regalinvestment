
$(window).scroll(function() {
	
}); 


$(document).ready(function(e) {
	$("input[type=hidden]").closest(".form-group").css("border","none");
	$("input[type=hidden]").closest(".form-group").css("margin-bottom","0px");
	$(".ckeditor").closest(".form-group").css("border","none");
	
	$('input,textarea').attr('spellcheck',true);
	
	
	 if(controllerName=='indexcontroller' && actionName=='login'){  
	
		$('#user_password').rules('remove','minlength');
		$('#user_password').rules('remove','maxlength');
	}
	else if(controllerName=='staticcontroller' && actionName=='contact'){
		//$('#user_message').closest('div').after($('.g-recaptcha'));
		//$("#user_phone").mask("999-999-9999");
	}
	else if(controllerName=='profilecontroller' && actionName=='dashboard'){
		 $('[data-toggle=confirmation]').confirmation({
  			rootSelector: '[data-toggle=confirmation]',
			 href: function(elem){
        		
				return baseUrl+'/removeplan/'+$(elem).data("element");
    		}
  			
		});
		 $('[data-toggle=resconfirmation]').confirmation({
  			rootSelector: '[data-toggle=resconfirmation]',
			 href: function(elem){
        		return baseUrl+'/removeresource/'+$(elem).data("element");
    		}
  			
		});
	}
	
	
	
	
	
	
	
	
});

