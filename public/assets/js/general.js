// override jquery validate plugin defaults
$.validator.setDefaults({
	highlight: function(element) {
		$(element).closest('.form-group').addClass('has-error PosRel');
		$(element).closest('.form-group.has-error').css('padding-bottom','1px');
	},
	unhighlight: function(element) {
		$(element).closest('.form-group').removeClass('has-error');
	},

	submitHandler: function(form){
		$.blockUI({ 
			css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: 'rgba(24, 24, 24, 1)', 
				opacity: .8, 
				color: '#fff',
			} ,
			message:  '<h1>Please Wait...</h1>',
		});
		
		setTimeout(function(){form.submit();},1000); 
    },
	
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
		if($(element).hasClass("multiselect-ui")){
				error.insertAfter($(element).parent().parent().children().last());
		}
        else if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
			if($(element).closest('div').hasClass('PermissionContentDiv')){
				$('label[for=user_permission]').after(error);
			}
			else if($(element).closest('div').hasClass('SubsContentDiv')){
				if(actionName=='addad')
					$('label[for=ad_position]').after(error);
				else
					$('label[for=subs_details]').after(error);	
			}
			else{
				error.insertAfter(element);
			}
            
        }
    }
});

$.validator.addMethod("lessThanEqualTo",
	function (value, element, param) {
	
	  return parseInt(value) <= parseInt(param);
}, "Max must be greater than min");


jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return parseInt(element.value) != param;
}, "Please specify a different (non-default) value");

jQuery.validator.addMethod("url", function(val, elem) {
    // if no url, don't do anything
    if (val.length == 0) { return true; }

    // if user has not entered http:// https:// or ftp:// assume they mean http://
    if(!/^(https?|ftp):\/\//i.test(val)) {
        val = 'http://'+val; // set both the value
        $(elem).val(val); // also update the form element
    }
    // now check if valid url
    // http://docs.jquery.com/Plugins/Validation/Methods/url
    // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
    return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(val);
});

/* All Aditional Functions  */
$.validator.setDefaults({ ignore: ":hidden:not(.chosen,.ckeditor,.chosen-select)"});
$('.validate').validate({
	ignore: [],
	rules:{
		page_title:{required: true},
 	},
	messages:{
       
    }
});

$('#forms-login').validate({
	
});
$('.profile_form').validate({
	rules:{
 		user_password:{minlength:6 , maxlength:16},
		pro_password:{minlength:6 , maxlength:16},
		user_rpassword:{equalTo:'#user_password' , minlength:6, maxlength:16},
		confirm_password:{equalTo:'#user_password' , minlength:6, maxlength:16},
 		user_email:{required: true,email: true},
		site_logo:{extension:"jpg|JPG|png|PNG|jpeg|JPEG"},
		home_video:{extension:"mp4|MP4"},
		home_image:{extension:"jpg|JPG|png|PNG|jpeg|JPEG"},
		ad_file:{extension:"jpg|JPG|png|PNG|jpeg|JPEG"},
		home_banner_image:{extension:"jpg|JPG|png|PNG|jpeg|JPEG"},
		business_support_image:{extension:"jpg|JPG|png|PNG|jpeg|JPEG"},
		business_strategy_image:{extension:"jpg|JPG|png|PNG|jpeg|JPEG"},
		company_professional_image:{extension:"jpg|JPG|png|PNG|jpeg|JPEG"},
		client_video_image:{extension:"jpg|JPG|png|PNG|jpeg|JPEG"},
		client_video:{extension:"mp4|MP4"},
		company_professional_video:{extension:"mp4|MP4"},
		business_strategy_video:{extension:"mp4|MP4"},
		business_support_video:{extension:"mp4|MP4"},
		job_desc:{	
			required: function() 
			{
				CKEDITOR.instances.job_desc.updateElement();
			},
			
		},
		res_desc:{	
			required: function() 
			{
				CKEDITOR.instances.res_desc.updateElement();
			},
			
		},
		page_content:{
			required: function() 
			{
				CKEDITOR.instances.page_content.updateElement();
			},
			
		},
		emailtemp_content:{
			required: function() 
			{
				CKEDITOR.instances.emailtemp_content.updateElement();
			},
		}
 	},
	messages:{
       home_video:{extension:"Please upload video file"},
	   home_image:{extension:"Please upload image file"},
	   home_banner_image:{extension:"Please upload image file"},
	   business_support_image:{extension:"Please upload image file"},
	   business_strategy_image:{extension:"Please upload image file"},
	   company_professional_image:{extension:"Please upload image file"},
	   client_video_image:{extension:"Please upload image file"},
	   client_video:{extension:"Please upload video file"},
	   company_professional_video:{extension:"Please upload video file"},
	   business_strategy_video:{extension:"Please upload video file"}, 
	   business_support_video:{extension:"Please upload video file"}, 
	},
 });

$('.profile_form2').validate({
	rules:{
		user_old_password:{minlength:6,maxlength:16,remote:baseUrl+"/profile/checkpassword"},
 		user_password:{minlength:6,maxlength:16,remote:baseUrl+"/profile/checkoldpass"},
		user_rpassword:{equalTo:'#user_password',minlength:6,maxlength:16},
 		user_email:{required: true,email: true,/*remote: baseUrl+"/user/checkemail"*/},
 	},
	messages:{
        user_rpassword:{
			
        },
	},
});

$(".team_form").validate({
	rules:{
		team_member_name:{required:true},
		team_member_position:{required:true},
		team_member_social_fb:{required:false,url:true},
		team_member_social_twitter:{required:false,url:true},
		team_member_social_linkedin:{required:false,url:true},
		team_member_social_instagram:{required:false,url:true}
	},
	messages:{
		
	}
});


if(controllerName=='usercontroller' && actionName=='adduser'){
	jQuery.validator.addClassRules("emailexists",{remote: baseUrl+"/user/usercheckemail/"+EcUserId});
}
jQuery.validator.addClassRules("checkemail_exclude",{remote: baseUrl+"/profile/checkemail?exclude=1"}); 

jQuery.validator.addClassRules("checkemail",{remote: baseUrl+"/profile/checkemail"});
jQuery.validator.addClassRules("checkusername",{remote: baseUrl+"/profile/checkusername"});


jQuery.validator.addMethod("phone_number", function(a, b, c) {
	var testStr=a;
	
	
	var phonepattern = /[^[0-9?.()\-+\ ]/;
	 if(testStr.match(phonepattern)!=null){
		 return false;
	 }else{
	 	return true;
	 }
},"Enter valid phone number");

$('.block_ui').click(function(e){$.blockUI();});

function showFlashMessage(msg,err){
	
	$('body').animate({ scrollTop: 0 }, 500);
	
 	$("#flash-message").show('slow');
	
 	if(err){
		$("#flash-message").addClass("alert-danger");
	}else{
		$("#flash-message").removeClass("alert-danger");
		$("#flash-message").addClass("alert-info");
	}
	
  	$("#flash-message").html(msg);
 	
	
	$("#flash-message").slideDown(function(){
		setTimeout(	'$("#flash-message").hide("slow")' ,3000);
	});
}

$(window).load(function(){
    $('.preloader').fadeOut(1000);
});

// override jquery validate plugin defaults

function ValidateNumber(event) {
	if((event.keyCode || event.which)!=8 && (event.keyCode || event.which)!=37 && (event.keyCode || event.which)!=39) {
		var regex = new RegExp("^[0-9\-?=.*!+@#()$%^&*]+$");
		var key = String.fromCharCode(event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
			event.preventDefault();
			return false;
		}
	}
}

var handleChoosenSelect = function () 
{
	if (!jQuery().chosen) {
		return;
	}
	$(".chosen").chosen();

	$(".chosen-with-diselect").chosen({
		allow_single_deselect: true
	})
}

<!-- For Datatables  -->
function re_init(){
	
	$('.group-checkable').removeAttr('checked');
	$('.group-checkable').parent().removeClass('checked');
}

$('.mix-link-delete').click(function(e) {
	var $confirm = confirm("Please Confim Your Delete Request !");
	if($confirm){
		return true; 
	}
	return false; 
});

function checkNewsSelects(){
 	var checkedRecords=false;	
	var Ids = '';
	$(".elem_ids").each(function(index, element) {
        if(this.checked==true){
			checkedRecords=true;
			if(Ids==''){
				Ids=$(this).val();
			}else{
				Ids=$(this).val()+","+Ids;
			}
		}
    });
	
	$("#SubIds").val(Ids);
	
	if(!checkedRecords){
		showFlashMessage("No subscribers selected",1);
		return false;	
	}else{
		$("#NewsletterModal").modal("show");
		return false;	
	}
}

function checkSelects(){
 	var checkedRecords=false;	
	
	$(".elem_ids").each(function(index, element) {
        if(this.checked==true){
			checkedRecords=true;
		}
    });
 	
	if(!checkedRecords){
		showFlashMessage("No Records Selected for Delete , Please Select Records to delete ",1);
		return false;	
	}else{
		if(!confirm("Are you sure you want to delete")){
			return false;
		}
		//$.blockUI();
	}
}

function checkSelects1(){
 	var checkedRecords=false;	
	
	$(".elem_ids").each(function(index, element) {
        if(this.checked==true){
			checkedRecords=true;
		}
    });
	
	if(!checkedRecords){
		showFlashMessage("No Records Selected for Delete , Please Select Records to delete ",1);
		return false;	
	}else{
		if(!confirm("Do you want to continue")){
			return false;
		}
		$.blockUI();
	}
}
	
$('#deletebcchk').click(function(e) {
	var current_checked_status = $.trim($('.group-checkable').attr('checked'));
	if(current_checked_status!='checked'){
		 $('.checkboxes').removeAttr('checked');
		 $('.checkboxes').parent().removeClass('checked');;
	}
	else{
		
		$('.checkboxes').attr('checked','checked');
		$('.checkboxes').parent().addClass('checked');;
	}
	
});

function globalStatus(chkAll){
	
	
	var d = new Date();
	var n = d.getTime();
	 
 	if($(chkAll).hasClass("status-1")){
			var status=0
			$(chkAll).addClass("status-0");
			$(chkAll).removeClass("status-1");
	}else{
		var status=1
			$(chkAll).addClass("status-1");
			$(chkAll).removeClass("status-0");
	}
	
	vars=	$(chkAll).attr("id").split("-");
	title=	$(chkAll).attr("title") ;
	
		scriptUrl=baseUrl+"/ajaxsetstatus/"+vars[0]+"/"+vars[1]+"/"+status+"?time="+n;	
		$.ajax({
			url: scriptUrl,
			dataType:"json",
			success: function(data) {
				if(data.success){
					showFlashMessage(data.message);	
				}else{
					showFlashMessage(data.message,1);
				}
		},
		error : function(data) {
			
		}
	});
}

function ucwords(str){
	return  str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
    	return letter.toUpperCase();
	});
}

function setAdType(num,pagetype)
{
	if(num==0){ /* IMAGE */
		$('#ad_content').closest('div').addClass('hide');
		$('#ad_content').removeClass('required');
		$('#ad_file').closest('div').removeClass('hide');
		$('#ad_file').addClass('required');
	}
	else{ /* ADSENCE CODE */
		$('#ad_file').closest('div').addClass('hide');
		$('#ad_file').removeClass('required');
		$('#ad_content').closest('div').removeClass('hide');
		$('#ad_content').addClass('required');
	}
	if(pagetype==1)
		$('#ad_content').val('');
	
}
