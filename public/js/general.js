// JavaScript Document
// override jquery validate plugin defaults
$.validator.setDefaults({
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
		if(!$(element).hasClass("LoginElements")){
			$(element).closest('.form-group.has-error').css('padding-bottom','1px');
		}
		
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
		$(element).closest('.input-group').removeClass('has-error');
    },
	submitHandler: function(form){
		
		if($(form).attr("id")=='popup_box' || $(form).attr("id")=='res_popup_box'){
			form.submit();
		}else{
			$.blockUI({ 
				css: { 
					border: 'none', 
					padding: '5px', 
					width:'24%',
					left:'39%',
					backgroundColor: 'rgba(24, 24, 24, 1)', 
					opacity: .8, 
					color: '#fff',
				} ,
				message:  '<h1>Please Wait...</h1>',
			});
			
			setTimeout(function(){form.submit();},1000); 
		}
		
		
    },
	//focusInvalid: false,
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter($(element).parent());
        }
		else if($(element).attr("type")=="radio"){
			error.insertAfter($(element).parent().parent().parent().parent());		
		}
		else if($(element).attr("id")=="user_image"){
			error.insertAfter($(element).parent().parent());		
		}else if($(element).hasClass("chosen-select")){
			error.insertAfter($(".chosen-container"));		
		}
		else if (element.attr("name") == "user_overview") 
  				error.insertAfter("#cke_user_overview");	
		else if (element.attr("name") == "job_desc") 
  				error.insertAfter("#cke_job_desc");	
		else if (element.attr("name") == "jprop_desc") 
  				error.insertAfter("#cke_jprop_desc");						
		else if($(element).hasClass("AllSubCats")){
			error.insertAfter($(element).parent().parent());		
		}
		else if(element.attr("name") == "user_terms"){ 
			error.insertAfter($(element));		
		}
		else if($(element).parent("ExpInnerDiv") && controllerName=='profilecontroller'){ 
			error.insertAfter($(element));	
		}
		else {
            error.insertAfter($(element));
        }
		
    },
	invalidHandler: function(form, validator) {

		/*if($(event.target).hasClass('SiteButton')){ 
			if (!validator.numberOfInvalids())
            return;
			
			$('html, body').animate({
				scrollTop: $(validator.errorList[0].element).offset().top-150
			}, 1000);
		}*/

    }
});

/*$(window).bind("mousewheel", function() {
    $("html, body").stop();
});*/
$(".HNotificationScroll").niceScroll({cursorborder:"2px solid #363C48",cursorcolor:"#363C48",cursorwidth:3,autohidemode:'true',background:"#ffffff"});
function readnots()
		{
			$.ajax({
				url: baseUrl+'/readnotify',
				success: function(data){
					$('.HeaderNotification a span.CountBadge').html('0');
				},
			});
		}
$(".togglepassword").click(function(){
	var attribute = $(this).parent().next().attr("type");
	if(attribute=="password"){
		$(this).parent().next().attr("type","text");
		$(this).find("i").toggleClass("fa-lock fa-unlock-alt");
	}
	else{
		$(this).parent().next().attr("type","password");
		$(this).find("i").toggleClass("fa-unlock-alt fa-lock");
	}
})



$.validator.addMethod("zip", function(value, element) {
        return this.optional(element) ||/^\d{5}(?:-?)(\d{4})?$/i.test(value);
}, "Please provide a valid zip code");

$.validator.addMethod("checkYear", function(value, element) {
	if(value!='')
  		return (value.length==4 && new Date().getFullYear()>=value);
	else
		return true;	
}, "Invalid year");	

jQuery.validator.addMethod("noSpace", function(value, element) { 
  return value.indexOf(" ") < 0 && value != ""; 
}, "No Space Allowed");

$(".alert").ready(function(){ 
	$(".alert.SiteMessage").slideDown('slow');
});
window.setTimeout(function () {
	$(".alert.SiteMessage").hide("slow");
}, 10000);

jQuery.validator.addMethod("portImageValid", function(a, b, c) {
    return c = "string" == typeof c ? c.replace(/,/g, "|") : "png|jpe?g|gif", this.optional(b) || a.match(new RegExp(".(" + c + ")$", "i"))
},"Invalid Image Type");
jQuery.validator.addMethod("jobAppValid", function(a, b, c) {
    return c = "string" == typeof c ? c.replace(/,/g, "|") : "doc|docx|pdf", this.optional(b) || a.match(new RegExp(".(" + c + ")$", "i"))
},"Invalid File Type");

$.validator.addClassRules({
	portImage: {
         portImageValid:"jpg|png|jpeg|png|bmp|JPG|PNG|JPEG|BMP",
    },
});

$.validator.addClassRules({
	jobAppValid: {
         jobAppValid:"doc|docx|pdf",
    },
});
	
$.validator.addMethod("greaterThanEqualTo",
	function (value, element, param) {
	  var $min = $(param);
	  if (this.settings.onfocusout) {
		$min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
		  $(element).valid();
		});
	  }
	  return parseInt(value) >= parseInt($min.val());
}, "Max must be greater than min");

$.validator.addMethod("lessThanEqualTo",
	function (value, element, param) {
		console.log(value);
		var $min = $(param);
	  	return parseInt(value) <= parseInt($min.val());
}, "Max must be greater than min");

jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value != param;
}, "Please specify a different (non-default) value");

jQuery.validator.addMethod("memberImageValid", function(a, b, c) {
    return c = "string" == typeof c ? c.replace(/,/g, "|") : "png|jpe?g|gif", this.optional(b) || a.match(new RegExp(".(" + c + ")$", "i"))
},"Invalid Image Type For Company Logo");

jQuery.validator.addMethod("quesLenValid", function(a, b, c) {
    return a.length<500;
},"Only 500 characters are allowed");

 $.validator.addClassRules({
	quesLen: {
         quesLenValid:"500",
    },
});


jQuery.validator.addMethod("phone_number", function(a, b, c) {
	var testStr=a;
	var phonepattern = /[^[0-9?.()\-+\ ]/;
	 if(testStr.match(phonepattern)!=null){
		 return false;
	 }else{
	 	return true;
	 }
},"Enter valid phone number");

jQuery.validator.addMethod("cardexpiry", function(value, element) { 
 
  	var today, someday;
	var cardValArr=value.split("/");
	var exMonth=cardValArr[0];
	var exYear=cardValArr[1];
	today = new Date();
	someday = new Date();
	someday.setFullYear(exYear, exMonth, 1);
	return someday > today;
 }, "Please enter a valid expiry date");


$('.number').keypress(function(e) {
    $('.number').closest('form').valid();
});

$.validator.addMethod("notDefaultText", function (value, element) {
   if (value == $(element).attr('placeholder')) {
      return false;
   } else {
       return true;
     }
});

jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return parseInt(element.value) != param;
}, "Please specify a different (non-default) value");

$.validator.addMethod('filesize', function(value, element, param) {
	console.log('size'+element.files[0].size);
    return this.optional(element) || (element.files[0].size <= param) 
},"Please specify a different (non-default) value");

$.validator.addMethod('filewidth', function(value, element, param) {
	console.log(element.files[0]);
    return this.optional(element) || (element.files[0].size <= param) 
},"Please specify a different (non-default) value");

//$.validator.setDefaults({ ignore: ":hidden:not(.ckeditor,#hiddenRecaptcha)"});
$('.sample_form').validate({});
$('.sample_form2').validate({});
$('.register_form').validate({
	ignore: false,
	rules: {
		user_name:{required:true},
		user_email:{required: true,notDefaultText:true,email:true},
		user_password:{minlength:6,maxlength:20,notDefaultText:true},
		user_rpassword:{equalTo:'#user_password',minlength:6, maxlength:16},
	},
	messages: {
		user_name:{nowhitespace:"White spaces are not allowed for user name"},
	}
})

$('.contact_form').validate({
	ignore: false,
	rules: {
		user_name:{required:true},
		user_email:{required: true,notDefaultText:true,email:true},
		user_password:{minlength:6,maxlength:20,notDefaultText:true},
		user_rpassword:{equalTo:'#user_password',minlength:6, maxlength:16},
	}
})


$('.forgot_form').validate({
	ignore: false,
	rules: {
		user_name:{required:true},
		user_email:{required: true,notDefaultText:true,email:true},
		user_password:{minlength:6,maxlength:20,notDefaultText:true},
		user_rpassword:{equalTo:'#user_password',minlength:6, maxlength:16},
	}
})

$('#registrationForm').validate({
	rules:{
		user_old_password:{minlength:6,maxlength:20},
 		user_password:{minlength:6,maxlength:20,notDefaultText:true},
		user_rpassword:{equalTo:'#user_password',minlength:6, maxlength:16},
 		user_email:{required: true,notDefaultText:true,email:true},
		user_postal_code:{digits:true,minlength:4,maxlength:6},
	
 	},
	
	messages:{
		user_rpassword:{equalTo:"Password Mismatch, please enter correct password"},
		user_password:{notDefaultText:"This field is required"},
		user_email:{notDefaultText:"This field is required"},
		user_first_name:{notDefaultText:"This field is required"},
		user_last_name:{notDefaultText:"This field is required"},
		user_address:{notDefaultText:"This field is required"},
		user_rpassword:{notDefaultText:"This field is required"},
		
	},
	
	
});
$('.profile_form').validate({
	ignore: false,
	rules:{
		user_old_password:{minlength:6,maxlength:20},
 		user_password:{minlength:6,maxlength:20,notDefaultText:true},
		user_rpassword:{equalTo:'#user_password',minlength:6, maxlength:16},
 		user_email:{required: true,notDefaultText:true,email:true},
		user_postal_code:{digits:true,minlength:4,maxlength:6},
		user_overview:{	
			required: function() 
			{
				CKEDITOR.instances.user_overview.updateElement();
			},
			minlength:100,maxlength:300
		},
		user_rate:{min:3,max:1000},
		user_skills:{maxlength:100},
		user_captcha:{remote:baseUrl+"/checkcaptch"},
		card_number:{creditcard:true},
		job_desc:{	
			required: function() 
			{
				CKEDITOR.instances.job_desc.updateElement();
			},
			maxlength:500
		},
		jprop_desc:{	
			required: function() 
			{
				CKEDITOR.instances.jprop_desc.updateElement();
			},
			maxlength:500
		},
		job_file:{extension:"jpg|JPG|png|PNG|jpeg|JPEG|pdf|doc|docx|PDF|DOC|DOCX|txt|TXT"},
		jprop_file:{extension:"jpg|JPG|png|PNG|jpeg|JPEG|pdf|doc|docx|PDF|DOC|DOCX|txt|TXT"},
		job_rate:{number:true},
		jprop_rate:{number:true},
		job_duration:{digits:true},
		withdraw_amount:{min:1},
		deposit_amount:{min:1},
		//job_squestion:{maxlength:100},
 	},
	
	messages:{
		user_rpassword:{equalTo:"Password Mismatch, please enter correct password"},
		user_password:{notDefaultText:"This field is required"},
		user_email:{notDefaultText:"This field is required"},
		user_first_name:{notDefaultText:"This field is required"},
		user_last_name:{notDefaultText:"This field is required"},
		user_address:{notDefaultText:"This field is required"},
		user_rpassword:{notDefaultText:"This field is required"},
		job_file:{extension:"Invalid file extension"},
		jprop_file:{extension:"Invalid file extension"},
	},
	
});



jQuery.validator.addClassRules("checkemail",{remote: baseUrl+"/checkemail"});
jQuery.validator.addClassRules("checkusername",{remote: baseUrl+"/checkusername"});
jQuery.validator.addClassRules("check_old_password",{remote: baseUrl+"/checkpassword"});
jQuery.validator.addClassRules("emailexists",{remote: baseUrl+"/checkemail//1"});
jQuery.validator.addClassRules("check_forgotemailexists",{remote: baseUrl+"/checkforgotemail"});
jQuery.validator.addClassRules("checkemail_exclude",{remote: baseUrl+"/checkemail?exclude=1"});

function stringifyNumber(n) {
var special = ['zeroth','first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth', 'eleventh', 'twelvth', 'thirteenth', 'fourteenth', 'fifteenth', 'sixteenth', 'seventeenth', 'eighteenth', 'nineteenth'];
var deca = ['twent', 'thirt', 'fourt', 'fift', 'sixt', 'sevent', 'eight', 'ninet'];
  if (n < 20) return special[n];
  if (n%10 === 0) return deca[Math.floor(n/10)-2] + 'ieth';
  return deca[Math.floor(n/10)-2] + 'y-' + special[n%10];
}

function capitalizeFirstLetter(string) {
    return string[0].toUpperCase() + string.slice(1);
}

$('body').on('click', function (e) {
    $('[data-toggle="tooltip"]').each(function () { 
    });
});

/*$(".SignUpMain input,.SignUpMain select,.SecondHalf input,.SecondHalf textarea,.SecondHalf select,.ContactFormDiv input,.ContactFormDiv textarea").each(function(index, element) {
	var fullName=$(element).attr('name');
	if(!$(element).hasClass('MultiBox'))
		$(element).after($('label[for='+fullName+']'));
});*/

$("input,textarea,select").focus(function(e) {
    /*var eleVal=$(this).val();
	$(this).next('label').css('top','0px');
	if(eleVal=='' && !$(this).hasClass('box')){
		$(this).css('padding-top','18px');
	}*/
});
$("input,textarea,select").blur(function(e) {
   /* var eleVal=$(this).val();
	if(eleVal==''){
		$(this).next('label').css('top','20px');
		$(this).css('padding-top','0px');
	}*/
});



setInputValue();

function setInputValue()
{
	/*$("input,textarea,select").each(function(e) {
		var eleVal=$(this).val();
		if(eleVal!=''){
			$(this).next('label').css('top','0px');
			$(this).css('padding-top','18px');
		}
	});*/
}

function callGoogleMap(type)
{
	var mapOptions = {
				zoom: 11,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: false,
				center: new google.maps.LatLng(latitude, longitude),
			};
		
			var map = new google.maps.Map(document.getElementById("privacyMapDiv"),mapOptions);
			
			if(type==1){
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(latitude, longitude),
					map: map,
					title: userlocation,
					icon: baseUrl+'/public/img/map_icon.png'
				});
			}
	
			
		if(type==1){
			var styles =[
    {
        "featureType": "administrative",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#444444"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2f2f2"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 45
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#46bcec"
            },
            {
                "visibility": "on"
            }
        ]
    }
];
		}
		else{
			
			var styles =
[
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#e9e9e9"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f5f5f5"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 18
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f5f5f5"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#dedede"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#333333"
            },
            {
                "lightness": 40
            }
        ]
    },
    {
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f2f2f2"
            },
            {
                "lightness": 19
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#fefefe"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#fefefe"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    }
];
		}
			map.setOptions({styles: styles});
}

function playAbout(){
	var player = videojs('my-video1', {
	autoplay: false,
	});
	player.play();
	$('.AboutPlayerDiv .PlayIcon').addClass('hide');
	player.on("ended", function(){ 
		$('.AboutPlayerDiv .PlayIcon').removeClass('hide');
	});
}

function playHomeVideo(){ 
	var player = videojs('my-video2', {
	autoplay: false,
	});
	player.play();
	$('.RightPanelClocks #imgDiv').addClass('hide');
	$('.RightPanelClocks #my-video2').removeClass('hide');
	
	player.on("ended", function(){ 
		$('.RightPanelClocks #imgDiv').removeClass('hide');
		$('.RightPanelClocks #my-video2').addClass('hide');
	});
}

function howBarNavigation()
{
	if(controllerName=='staticcontroller' && actionName=='howitworks' && (pageId==1)){
		var companyDiv=$("#companyDiv").offset().top;
		var directoryDiv=$("#directoryDiv").offset().top-400;	
		var stepDiv=$("#stepDiv").offset().top-400;	
		var projectDiv=$("#projectDiv").offset().top-400;	
		var beliefDiv=$("#beliefDiv").offset().top-550;	
		console.log('comnpant'+companyDiv);
		console.log('direc'+directoryDiv);
		
		var scroll = $(window).scrollTop();
		console.log('scroll'+scroll);
		if($(window).innerWidth()>769){
			$(".HowWorkNav div").removeClass('active');
			$('.HowWorkNav').removeClass('hide');
			
			if(scroll<directoryDiv){
				$("#companyDivNav").addClass('active');
				$('.HowWorkNav').addClass('hide');
			}
			if(scroll>directoryDiv && scroll<stepDiv){console.log('dire');
				$("#directoryDivNav").addClass('active');
			}
			if(scroll>stepDiv && scroll<projectDiv){
				$("#stepDivNav").addClass('active');
			}
			if(scroll>projectDiv && scroll<beliefDiv){
				$("#projectDivNav").addClass('active');
			}
			if(scroll>beliefDiv){
				$("#beliefDivNav").addClass('active');
			}
			
		}
	}
}

function getSubCats(num,type)
{
	$.blockUI({ css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: 'rgba(24, 24, 24, 1)', 
				opacity: .5, 
				color: '#fff' ,
				fontSize:"25px",
			},
			message: "Please wait...", 
		 });
		
		var data1; 
		$.ajax({ 
			  url: baseUrl+"/subcats",
			  type: "POST",
			  async:false,
			  data: {num:num,type:type},
			  success: function(data){
					setTimeout($.unblockUI, 500);
					
					if(type==1){
							$('#subCatDiv').html(data);
					}
					else{
						
						data=JSON.parse(data);
						var opt = document.createElement('option');
						var select1 = document.getElementById('job_sub_cat_id');
						document.getElementById("job_sub_cat_id").innerHTML = "";
						opt.innerHTML = "Select Sub Category";
						opt.value = "";
						
						if(data.length>0){
							document.getElementById("job_sub_cat_id").innerHTML = "";
							for (var i=0; i<data.length; i++){
								var opt = document.createElement('option');
								opt.value = data[i].subcategory_id;
								opt.innerHTML = data[i].subcategory_name;
								if(jobSubCatId!='' && jobSubCatId==data[i].subcategory_id){
									opt.setAttribute("selected","selected");
								}
								select1.appendChild(opt);
							}
						}
						else{
							opt.value = "";
							opt.innerHTML = "Select Sub Category";
							select1.appendChild(opt);
						}
					}
					
			  }
		 }); 
		 
}

function checkCatCount()
{
	var totalCheck=$('#subCatDiv input:checked').length;
	$('#catCountErr').html("");
	if(parseInt(totalCheck)>parseInt(checkSubCount)){
		$('#catCountErr').html("You can select only "+checkSubCount+" sub categories.");
	}
}

function makePhotoPayment()
{
	window.location.href=baseUrl+'/setphotopayment';
}

function addExperienceDiv(id,countryData)
{
	id=parseInt(id);
	var optionSel="<option value=''></option>";
	var optionSel1="<option value=''>Select Country</option>";
	var optionSel2="<option value=''>Select City</option>";
	
	countryData=JSON.parse(countryData);
	var currDate=new Date();
	for(var i=1950;i<=currDate.getFullYear();i++){
		optionSel+="<option value="+i+">"+i+"</option>";
	}
	for(var i=0;i<countryData.length;i++){
		optionSel1+="<option value="+countryData[i].country_id+">"+countryData[i].country_name+"</option>";
	}
	
	var innerhtml='<div class="ExpInnerDiv Overflow"  id="experience_'+id+'"><img src="'+baseUrl+'/public/img/close.png" onclick="removeExpContent('+id+')" class="pull-right profileToolTip Cursor RemoveImg" title="'+RemExpText+'" id="delete_'+id+'" /><div class="clearfix Clear"><div class="form-group CatDiv"><label class="AddLabel" for="org_name['+id+']">'+titleText+'&nbsp;<span class="span_required">*</span></label><input type="text" class="form-control required" id="org_name'+id+'" name="org_name['+id+']" /></div></div><div class="clearfix Clear"><div class="col-sm-6 PaddingLeftZero"><div class="form-group CatDiv"><label class="AddLabel" for="org_country['+id+']" class="optional">'+countryText+'&nbsp;<span class="span_required">*</span></label><select name="org_country['+id+']" id="org_country'+id+'" class="form-control required" onchange="getAllCountry(this.value,'+id+',"")">'+optionSel1+'</select></div></div><div class="col-sm-6 PaddingRightZero"><div class="form-group CatDiv"><label class="AddLabel" for="org_city['+id+']" class="optional">'+cityText+'&nbsp;<span class="span_required">*</span></label><select name="org_city['+id+']" id="org_city'+id+'" class="form-control required">'+optionSel2+'</select></div></div></div><div class="clearfix Clear"><div class="col-sm-6 PaddingLeftZero"><div class="form-group CatDiv"><label class="AddLabel" for="org_from['+id+']" class="optional">'+fromText+'&nbsp;<span class="span_required">*</span></label><select name="org_from['+id+']" id="org_from'+id+'" class="form-control required" onclick="setToYear(this.value,'+id+')" >'+optionSel+'</select></div></div><div class="col-sm-6 PaddingRightZero"><div class="form-group CatDiv"><label class="AddLabel" for="org_to['+id+']" class="optional">'+toText+'&nbsp;<span class="span_required">*</span></label><select name="org_to['+id+']" id="org_to'+id+'" class="form-control required">'+optionSel+'</select></div></div></div><div class="clearfix Clear"><div class="form-group CatDiv"><label class="AddLabel WorkInput" for="org_work'+id+'">I currently work in</label><input type="checkbox" class="WorkInput" id="org_work'+id+'" name="org_work'+id+'" onclick="removeAllCheck(this)" /></div></div></div></div></div>';
	
	$('#expDiv').append(innerhtml);	
	if(id==1)
	{
		$('#delete_'+id).css('display','none');
	}	
	$('#total_experience').val(id);		
	
	
}

function addPortfolioDiv(id)
{
	id=parseInt(id);
	
	var innerhtml='<div class="ExpInnerDiv Overflow"  id="portfolio_'+id+'"><img src="'+baseUrl+'/public/img/close.png" onclick="removePortContent('+id+')" class="pull-right profileToolTip Cursor RemoveImg" title="'+RemExpText+'" id="delete1_'+id+'" /><div class="clearfix Clear"><div class="form-group CatDiv"><label class="AddLabel" for="port_name['+id+']">'+titleText1+'&nbsp;<span class="span_required">*</span></label><input type="text" class="form-control required" id="port_name'+id+'" name="port_name['+id+']" /></div></div><div class="clearfix Clear"><div class="form-group CatDiv"><label class="AddLabel" for="port_image['+id+']" class="optional">'+uploadText+'&nbsp;<span class="span_required">*</span></label><input type="file" name="port_image['+id+']" id="port_image'+id+'" class="required portImage" multiple="multiple" onchange="checkImgCount(this,'+id+')" /><input type="hidden" name="port_old_image['+id+']" id="port_old_image'+id+'" /></div></div><div class="clearfix Clear"><div class="form-group CatDiv"><label class="AddLabel" for="port_desc['+id+']" class="optional">'+descText+'&nbsp;<span class="span_required">*</span></label><textarea name="port_desc['+id+']" id="port_desc'+id+'" class="required form-control PortContent" rows="5" /></textarea></div></div></div></div>';
	
	$('#portDiv').append(innerhtml);	
	if(id==1 || id==2)
	{
		$('#delete1_'+id).css('display','none');
	}	
	$('#total_portfolio').val(id);		
	
	
}

function addQuesDiv(id)
{
	id=parseInt(id);
	
	var innerhtml='<div class="ExpInnerDiv Overflow Ques"  id="question_'+id+'"><img src="'+baseUrl+'/public/img/close.png" onclick="removeQuesContent('+id+')" class="pull-right profileToolTip Cursor RemoveImg" title="'+RemJobText+'" id="delete1_'+id+'" /><div class="clearfix Clear"><div class="form-group CatDiv"><label class="AddLabel" for="ques_name['+id+']">'+titleJobText+'&nbsp;<span class="span_required">*</span></label><input type="text" class="form-control required quesLen" id="ques_name'+id+'" name="ques_name['+id+']" /></div></div></div></div></div>';
	
	$('#quesDiv').append(innerhtml);	
	if(id==1)
	{
		$('#delete1_'+id).css('display','none');
	}	
	$('#total_questions').val(id);		
	
	
}

function removeExpContent(id)
{
	$('#experience_'+id).remove();
	var id=$('#total_experience').val();
	id=parseInt(id)-1;
	$('#total_experience').val(id);
	
}

function removePortContent(id)
{
	$('#portfolio_'+id).remove();
	var id=$('#total_portfolio').val();
	id=parseInt(id)-1;
	$('#total_portfolio').val(id);
	
}

function removeQuesContent(id)
{
	$('#question_'+id).remove();
	var id=$('#total_questions').val();
	id=parseInt(id)-1;
	$('#total_questions').val(id);
	
}

function addExpContent(pagetype)
{
	var id=$('#total_experience').val();
	id=parseInt(id)+1;
	addExperienceDiv(id,countryData);
	$('#total_experience').val(id);
	
	if(pagetype==2){
		$('html, body').animate({
			scrollTop: $("#experience_"+id).offset().top
		}, 'slow'); 
	}
}

function addPortContent(pagetype)
{
	var id=$('#total_portfolio').val();
	id=parseInt(id)+1;
	addPortfolioDiv(id);
	$('#total_portfolio').val(id);
	
	if(pagetype==2){
		$('html, body').animate({
			scrollTop: $("#portfolio_"+id).offset().top
		}, 'slow'); 
	}
}

function addQuesContent(pagetype)
{
	var id=$('#total_questions').val();
	id=parseInt(id)+1;
	addQuesDiv(id);
	$('#total_questions').val(id);
	
	if(pagetype==2){
		$('html, body').animate({
			scrollTop: $("#question_"+id).offset().top
		}, 'slow'); 
	}
}


function setToYear(value,num)
{
    var index = $('#org_from'+num).find('option:selected').index();
    $('#org_to'+num).not('#org_from'+num).find('option:lt(' + index + ')').prop('disabled', true);
	
}

function removeAllCheck(num)
{
	$('#addMoreExp').removeClass('hide');
	if($(num).prop('checked')==true){
		$('.ExpInnerDiv input[type="checkbox"]:checked').removeAttr('checked');
		$(num).prop('checked',true);
		$('#addMoreExp').addClass('hide');
	}
	
}

function removePortImg(num,index)
{
	window.location.href=baseUrl+'/removeportcontent/'+num+'/'+index;
	
}

function checkImgCount(num,index)
{
	/*var files = $(num)[0].files;
	var oldImages=$('#port_old_image'+index).val(); 
	var countVal=0;
	if(typeof oldImages!='undefined'){
		var elements = oldImages.split(',');
		countVal=elements.length;
	}
	var totalFile=files.length+parseInt(countVal);
	$('#profileFormDiv #bttnsubmit').removeAttr('disabled');
    if(totalFile<2){
		$(num).val('');
		$('#errImg'+index).html('Select Atleast two images');
		$('#profileFormDiv #bttnsubmit').attr('disabled',true);
	}*/
}

function calcFeesVal(num)
{
	var checkVal=$(num).val();
	var commVal=Math.round(checkVal-((10*checkVal)/100),2);
	$('#user_fees').val(commVal);
	
}

function howBarNavigation()
{
	if(controllerName=='staticcontroller' && actionName=='howitworks' && (pageId==1)){
		var companyDiv=$("#companyDiv").offset().top;
		var directoryDiv=$("#directoryDiv").offset().top-400;	
		var stepDiv=$("#stepDiv").offset().top-400;	
		var projectDiv=$("#projectDiv").offset().top-400;	
		var beliefDiv=$("#beliefDiv").offset().top-550;	
		
		var scroll = $(window).scrollTop();
		if($(window).innerWidth()>769){
			$(".HowWorkNav div").removeClass('active');
			$('.HowWorkNav').removeClass('hide');
			
			if(scroll<directoryDiv){
				$("#companyDivNav").addClass('active');
				$('.HowWorkNav').addClass('hide');
			}
			if(scroll>directoryDiv && scroll<stepDiv){console.log('dire');
				$("#directoryDivNav").addClass('active');
			}
			if(scroll>stepDiv && scroll<projectDiv){
				$("#stepDivNav").addClass('active');
			}
			if(scroll>projectDiv && scroll<beliefDiv){
				$("#projectDivNav").addClass('active');
			}
			if(scroll>beliefDiv){
				$("#beliefDivNav").addClass('active');
			}
			
		}
	}
}

$('.HowWorkNav div').click(function(e) {
    var targetIdArr=e.target.id;
		targetIdArr=targetIdArr.split('Nav');
		$('html, body').animate({
				   scrollTop: $('#'+targetIdArr[0]).offset().top-100
		}, 'slow');
});

$("#RotateImage").click(function(e) {
	$(".fileinput .thumbnail>img").removeClass("rotate-0");
	$(".fileinput .thumbnail>img").removeClass("rotate-90");
	$(".fileinput .thumbnail>img").removeClass("rotate-180");
	$(".fileinput .thumbnail>img").removeClass("rotate-270");
	
	if($("#rotate").val()==0){
		$(".fileinput .thumbnail>img").addClass("rotate-90");
		$("#rotate").val(1);
	}else if($("#rotate").val()==1){
		$(".fileinput .thumbnail>img").addClass("rotate-180");
		$("#rotate").val(2);
	}else if($("#rotate").val()==2){
		$(".fileinput .thumbnail>img").addClass("rotate-270");
		$("#rotate").val(3);
	}else if($("#rotate").val()==3){
		$(".fileinput .thumbnail>img").addClass("rotate-0");
		$("#rotate").val(0);
	}
    
});

 

$('.header_parent_li').click(function(e) {
	
    var idval=$(this).attr('id');
	if($('#'+idval+' span').length>0)
	{	
		if($('#'+idval+' .child_li').hasClass('hide'))
		{
			$('.header_parent_li span').addClass('fa-chevron-down');
			$('#'+idval+' span').removeClass('fa-chevron-down');
			$('#'+idval+' span').addClass('fa-chevron-up');
			$('.child_li').addClass('hide');
			
			$('#'+idval+' .child_li').removeClass('hide');
			
		}
		else
		{
			$('#'+idval+' span').addClass('fa-chevron-down');
			$('#'+idval+' span').removeClass('fa-chevron-up');
				$('.header_parent_li span').addClass('fa-chevron-down');
			$('.child_li').addClass('hide');
		}
		
	}
});

$('body').click(function(e) {
	
	if(window.innerWidth>769){
		$('.BrowseDiv .dropdown-menu').css('display','none');
		if($(e.target).closest('div').hasClass('BrowseDiv')){
			$('.BrowseDiv .dropdown-menu').css('display','block');
		}
	}
	else{ 
		$('.sidebar-nav .dropdown-menu').css('display','none');
		if(($(e.target).closest('ul.sidebar-nav') && (!$(e.target).hasClass('menu-toggle')))){ 
			$('.sidebar-nav .dropdown-menu').css('display','block');
		}
	}
    
		
});
$(".menu-toggle").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
});

$('.HeaderExDiv li.dropdown').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
});

function getAllCountry(value,num,cityVal)
{
	
	/*$.blockUI({ css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#0c5797', 
				opacity: .5, 
				color: '#fff' ,
				fontSize:"25px",
			},
			message: "Please wait...", 
		 });*/
		
		var data1; 
		$.ajax({ 
			  url: baseUrl+"/getcities",
			  type: "POST",
			  async:false,
			  data: {value:value},
			  success: function(data){
					//setTimeout($.unblockUI, 500);
					data=JSON.parse(data);
					var opt = document.createElement('option');
					var select1 = document.getElementById('org_city'+num);
					document.getElementById('org_city'+num).innerHTML = "";
					opt.innerHTML = "Select City";
					opt.value = "";
					
					
					if(data.length>0){
						document.getElementById('org_city'+num).innerHTML = "";
						for (var i=0; i<data.length; i++){
							var opt = document.createElement('option');
							opt.value = data[i].city_id;
							opt.innerHTML = data[i].city_name;
							if(cityVal==data[i].city_id){
								opt.setAttribute("selected", "selected");
							}
							select1.appendChild(opt);
						}
					}
					else{
						opt.value = "";
						opt.innerHTML = "Select City";
						select1.appendChild(opt);
					}
			  }
		 }); 
}
function getfaq(num){
	$.ajax({ 
		  url: baseUrl+"/getfaqs",
		  type: "POST",
		  async:false,
		  data: {num:num},
		  success: function(data){
			  if(data!=0 && data!=''){
				  $('#jag-faq-answer-1').modal("show");
				 $('#jag-faq-answer-1 .modal-content').html(data);
			  }
		  }
	 }); 
}
function getPhoneCode(num)
{

	$.ajax({ 
		  url: baseUrl+"/getphonecode",
		  type: "POST",
		  async:false,
		  data: {num:num},
		  success: function(data){
			$('#user_phonecode').val('+'+data);
		  }
	 }); 
}

$(window).resize(function(){
  if(window.innerWidth<769){
		 $('#deskHeaderDiv').remove();
	 }
	 else{
		 $('.sidebar-nav').remove();
	 }
});

$(document).ready(function(e) {
	$("input:checkbox, input:radio").uniform();
	$(":checkbox").uniform({checkboxClass: 'myCheckClass'});
});


function chooseRegOpt(type)
{
	$('#SignOptionDiv').css('height','auto');
	$('#footerMainDiv').removeClass('hide');
	$('#'+type+'FormDiv').removeClass('hide');
	if(type=='freelancer'){
		$('#clientMainDiv').remove();
		$('#freelancerMainDiv .InnerContent').addClass('SignSideBar');
		$('#freelancerMainDiv').removeClass('EqualW DisplayTableCell');
		$('#freelancerFormDiv').css('box-shadow','rgba(0, 0, 0, 0.5) 0px -7px 8px 2px inset');
		$('#freelancerMainDiv .Circle').removeAttr('onclick');
	}
	else{
		$('#freelancerMainDiv').remove();
		$('#clientMainDiv .InnerContent').addClass('SignSideBar');
		$('#clientMainDiv').removeClass('EqualW DisplayTableCell');
		$('#clientFormDiv').css('box-shadow','rgba(0, 0, 0, 0.5) 15px 10px 10px -15px inset');
		$('#clientMainDiv .Circle').removeAttr('onclick');
		$('#user_category').closest('div').remove();
	}
	$('#user_type').val(type);
	
	$('#user_captcha').closest('div').before($('#CaptchaDiv'));
	$('#CaptchaDiv .RefreshBtn').trigger('click');
	
	$('#'+type+'MainDiv').css('height','auto');
	if(window.innerWidth<=750){ 
		$('#'+type+'MainDiv .SignSideBar').remove();
	}
	
	
	
}
function setLoginContent()
{
	if(window.innerWidth>750){ 
		var windowHeight=$('body').innerHeight();
		var headHeight=$('#deskHeaderDiv').height();
		var footHeight=$('#footerMainDiv').height();
		var conHeight=parseInt(windowHeight)-parseInt(headHeight);
		$('#SignOptionDiv').css('height',conHeight+'px');
	}
}

function purchasePlanDiv(num)
{
	var boxWidth = $("#purchasePlanDiv").width();
	$("#purchasePlanDiv").toggle( "slide" );
	$('#plan_number').val(num);
}


function checkSelects(){
 	var checkedRecords=false;	
	$(".elem_ids").each(function(index, element) {
        if(this.checked==true){
			checkedRecords=true;
		}
    });
 	
	if(!checkedRecords){
		alert("Please select notifications");
		return false;	
	}else{
		var check=confirm("Are you sure you want to delete selected notifications?")
		if(check==true){
			$('#notificationForm').submit();
		}
	}
}

function saveJobForm(num)
{
	$('#job_draft_status').val(num);
	if($('#jobForm').valid()){
		$('#jobForm').submit();
	}
}

function checkQuesLen()
{
	var textVal=$('#job_squestion').val();
	var maxLenText=100;
	var remText=parseInt(maxLenText)-parseInt(textVal.length);
	$('.OverRemText1').html('');
	if(textVal.length<=100)
		$('.OverRemText1').html(remText+" characters remaining");
}

function deleteJob(num)
{
	var check=confirm('Are you sure you want to delete this Job?')
	if(check==true){
		window.location.href=baseUrl+'/removejob/'+num;
	}
}
function declineJob(num)
{
	var check=confirm('Are you sure you want to delete this Job invitation?This job would no longer display on this page.Click OK to continue')
	if(check==true){
		window.location.href=baseUrl+'/removejobinvitation/'+num;
	}
}
function acceptJob(num,num2)
{
	$('#blankViewModal').modal('show');
	var modalTitle="<h3><u>Submit Proposal</u></h3><br>"+$('#jobTitle'+num).html();
	$('#blankViewModal .modal-header .DisplayTableCell').html(modalTitle);
	
	
	$.blockUI({ css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: 'rgba(24, 24, 24, 1)', 
				opacity: .5, 
				color: '#fff' ,
				fontSize:"25px",
			},
			message: "Please wait...", 
		 });
		
	var data1; 
	$.ajax({ 
		  url: baseUrl+"/getproposalform",
		  type: "POST",
		  async:false,
		  data: {num:num2},
		  success: function(data){
				setTimeout($.unblockUI, 500);
				$('#blankViewModal .modal-body').html(data);
				$('#jprop_job_id').val(num2);
				/*CKEDITOR.replace('jprop_desc',{toolbar :[['Source'],['Bold','Italic','Underline'],['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],['TextColor'],
  ['Format'],['FontSize'],['Scayt']],height: 300,scayt_autoStartup: true}); */
  				$('#jprop_file').closest('div').after($('#allQuesDiv'));
			  }
	 }); 
}

jQuery.validator.addMethod('dobvalidate', function (value, element, param) 
{
	var elem=element.id;
	var elemId=elem.split('pfamily_dob_');
	var newElement=elemId[1].split('first'); 
	
	var day=$('#pfamily_dob_first'+newElement[1]).val();
	var month=$('#pfamily_dob_sec'+newElement[1]).val();
	var year=$('#pfamily_dob_third'+newElement[1]).val();
	//console.log(day+"::"+month+"::"+year);
	var result = new Date(year,month,day);
	var currDate=new Date();
	if (result!= 'Invalid Date' && year<=currDate.getFullYear()) {//console.log('hghj');
		return true;
	}
}, 'Enter valid Date of birth');

jQuery.validator.addMethod("notDefaultText", function (value, element) {
	if(value == $(element).attr('placeholder')){
		return false;
	}
	else{
		return true;
	}
});

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

jQuery.validator.addMethod("email", function(value, element) {
    return this.optional(element) || /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);
}, "Please enter a valid email address.");