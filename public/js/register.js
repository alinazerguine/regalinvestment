var typingTimer;    
var typingTimer1;            //timer identifier
var doneTypingInterval = 500; 
$(document).ready(function() {
	 $('[data-toggle="tooltip"]').tooltip(); 
	google.maps.event.addDomListener(window, 'load', initialize);
  	$('#rootwizard').bootstrapWizard({
	onNext: function(tab, navigation, index) {
		if(index==0) {
			$('.leftelement').addClass('hide');
		}else{
			$('.leftelement').removeClass('hide');	
		}
			if(!$("#registrationForm").valid()) {
				return false;
				
			}
 		}, onTabShow: function(tab, navigation, index) {
			//var $total = navigation.find('li').length;
			if(index==0) {
			$('.leftelement').addClass('hide');
			}else{
				$('.leftelement').removeClass('hide');	
			}
			if(index==3) {
				$('.next').css('display','none');
			}else{
				$('.next').css('display','block');	
			}
			var $total =4;
			var $current = index+1;
			var $percent = ($current/$total) * 100;
			$('#rootwizard .progress-bar').css({width:$percent+'%'});
		}});
	$('input[name=book_type]').change(function(e) {
       check_book_type();
	 });
	 $('input[name=investing_reason]').change(function(e) {
       check_book_type();
	 });
	$('input[name=invest_opt]').change(function(e) {
         var invest_opt=this.value;
		if(invest_opt==1){
			$("#invest_type_block").removeClass('hide');
		   $('html, body').animate({
  				scrollTop: $("#invest_type_block").offset().top
 			}, 1000); 
			
		}else{
			
			$('input[name=invest_type]').parent().removeClass('checked');
			$('input[name=invest_type]').attr('checked', false);
			$("#invest_type_block").addClass('hide'); 
		}
		getinvest_assets();
    });
	$('input[name=invest_type]').change(function(e) {
		getinvest_assets();
    });
	$('#invest_assets').keyup(function(e) {
    		clearTimeout(typingTimer);
			clearTimeout(typingTimer1);
  			typingTimer = setTimeout(getinvest_assets, doneTypingInterval);
			typingTimer1 = setTimeout(check_book_type, doneTypingInterval);
			/*getinvest_assets();
			check_book_type();*/
			//getinvest_return();
	});
	
	$('#invest_assets').keydown(function(e) {
			clearTimeout(typingTimer);
			clearTimeout(typingTimer1);
	});	
});
function initialize() {
        var input = document.getElementById('user_location');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        document.getElementById('user_loc_latitude').value = place.geometry.location.lat();
        document.getElementById('user_loc_longitude').value = place.geometry.location.lng();
        });
 }
 /*function getinvest_return(){
	 var value=$('#invest_assets').val();
	 if(value > 0){
		 var book_type=$('input[name=book_type]:checked').val();
		 var investing_reason=$('input[name=investing_reason]:checked').val();
		 $.ajax({ 
			  url: baseUrl+"/getinvestamount",
			  type: "POST",
			  async:false,
			// dataType:'json',
			  data: {value:value,book_type:book_type,investing_reason:investing_reason},
			  success: function(data){ 
					if(data!=0 && data!=''){
						$('#invesment_return').removeClass('hide');
						$('#invesment_return_amount').html(data);
					}
			  	}
			});
		 
		 
	}
	else{
				$('#invesment_return').addClass('hide');
				$('#invesment_return_amount').html('');
			}
}*/
function getinvest_assets(){
			var value=$('#invest_assets').val();
			if(value > 0){
			var invest_type=$('input[name=invest_type]:checked').val();
			var invest_opt=$('input[name=invest_opt]:checked').val();
			$.ajax({ 
			  url: baseUrl+"/getplan",
			  type: "POST",
			  async:false,
			// dataType:'json',
			  data: {value:value,invest_type:invest_type,invest_opt:invest_opt},
			  success: function(data){ 
					if(data!=0 && data!=''){
						$('#recommended_plan').removeClass('hide');
						$('#recommended_plan_title').html(data);
					}
			  	}
			});
			}else{
				$('#recommended_plan').addClass('hide');
				$('#recommended_plan_title').html('');
			}
    	
	}
function check_book_type(){
	 var value=$('#invest_assets').val();
	$('#Highcharts_container').addClass('hide');
	  // var book_type=this.value;
	   var book_type=$('input[name=book_type]:checked').val();
	  
	   if(book_type==5){
		   $("#investing_reason_block").removeClass('hide');
		   $('html, body').animate({
  				scrollTop: $("#investing_reason_block").offset().top
 			}, 1000); 
	   }else{
		 $("#investing_reason_block").addClass('hide');   
	  }
	  var type=$('input[name=investing_reason]:checked').val();
	   if(book_type!=5){
			type=0;
		}
	if(value < 0 || value ==''){
		$('#user_inv_return').val(0);
		$('#invesment_return').addClass('hide');
		$('#invesment_return_amount').html('');
		$('#master_year_block').html('');
	}
	  if(book_type==5 && (type==0 || typeof type==='undefined')){
		
	  }else{
		 value=$('#invest_assets').val();
	  	$.ajax({ 
			  url: baseUrl+"/getpie",
			  type: "POST",
			  async:false,
			// dataType:'json',
			  data: {book_type:book_type,type:type,value:value},
			  success: function(data){ 
			 
			  subdata=JSON.parse(data);
			  //console.log(data);
			 // console.log(subdata[subdata.length-1]['risk_level']);
			  $('#master_year_block').html(subdata[subdata.length-1]['years_block']);
			  $('#risk_text').html(subdata[subdata.length-1]['risk_level']);
			  $('#user_inv_return').val(subdata[subdata.length-1]['px_amount']);
			  var amountdata= subdata[subdata.length-1]['amount'];
			  
			  //console.log($.parseJSON(subdata[subdata.length-1]['general_graph_years']));
			  var general_graph_years=$.parseJSON(subdata[subdata.length-1]['general_graph_years']);
			  var general_graph_amount=$.parseJSON(subdata[subdata.length-1]['general_graph_amount']);
			  var general_graph_project_amount=$.parseJSON(subdata[subdata.length-1]['general_graph_project_amount']);
			  var general_graph_industry_average=$.parseJSON(subdata[subdata.length-1]['general_graph_industry_average']);
			  if(value > 0){ 
			  	$('#invesment_return').removeClass('hide');
			  	$('#invesment_return_amount').html(amountdata);
			  }
			  var masterArr=[ [], [] ] ;
			 if(subdata.length-1 > 0){
				
					for (var i=0; i< subdata.length-1; i++){
					 	masterArr[i] = {"name":subdata[i]["name"],"y":parseFloat(subdata[i]["y"])};
						
					}
				$('[data-toggle="tooltip"]').tooltip(); 
			$('#Highcharts_container').removeClass('hide');
	 		Highcharts.chart('Highcharts_container', {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie',
				
			  },
			title: {
				text: 'YOUR DIRECT INVESTING FUND ALLOCATION WOULD BE'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
  		 	 plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							style: {
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
							}
						}
					}
   			 },
   			 series: [{
				name: 'Percentage',
				colorByPoint: true,
				data:masterArr
       
   			 }]
									});
								
						}
					 if(book_type==2 && value > 0){
						 $('#assets_container').removeClass('hide');
						 Highcharts.chart('assets_container', {
    chart: {
        type: 'area',
		backgroundColor: 'transparent'
    },
    title: {
        text: 'Investable assets'
    },
    subtitle: {
        //text: '<?php echo PRICE_SYMBOL?>'
    },
    xAxis: {
        categories: general_graph_years,
        tickmarkPlacement: 'on',
        title: {
            enabled: true,
			text: 'Years'
        }
    },
    yAxis: {
        title: {
           text: 'Returns on Investment'
        },
        labels: {
            formatter: function () {
               return this.value;
            }
        }
    },
    tooltip: {
        split: true,
       // valueSuffix: ' millions'
    },
    plotOptions: {
        area: {
            //stacking: 'normal',
            lineColor: '#666666',
            lineWidth: 1,
            marker: {
                lineWidth: 1,
                lineColor: '#666666'
            }
        }
    },
    series: [
	
		 {
			name: 'Returns',
			data: general_graph_amount
   		 },
		 {
			name: 'Projection',
			data: general_graph_project_amount
   		 }, 
		 {
			name: 'Industry Average',
			data: general_graph_industry_average
		 }
			
	
	]
						});
						 
						 
					 }
					
					
			  }
		 }); 
	  }	
}