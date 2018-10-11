<?php
	defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(__FILE__) . ''));

	
	define("ADMIN_AUTH_NAMESPACE", "ADMIN_AUTH");
	define("DEFAULT_AUTH_NAMESPACE", "DEFAULT_AUTH");
	
	define('BACKEND','controlPanel');	
	define('BACKEND_NAME','controlPanel');
	
	define('SITE_NAME','REGAL INVESTMENTS');
	
	define("SITE_BASE_URL", dirname($_SERVER['PHP_SELF']));
	define('GOOGLE_MAP_API_KEY','AIzaSyB7cE4DGHkmBz2z6OcaLoun9zqm3VoPyVU');
	
	if(preg_match('/192.168/',$_SERVER['REMOTE_ADDR'])){
		define("SITE_HOST_URL","http://" . $_SERVER['HTTP_HOST']);
		define("SITE_HTTP_URL","http://" . $_SERVER['HTTP_HOST'] . SITE_BASE_URL);
		define("APPLICATION_URL","http://" . $_SERVER['HTTP_HOST'] . SITE_BASE_URL);
		define("ADMIN_APPLICATION_URL", SITE_HTTP_URL . "/".BACKEND);
		define("TEST", true);
	}else{
		define("SITE_HOST_URL","http://" . $_SERVER['HTTP_HOST']);
		define("SITE_HTTP_URL","http://" . $_SERVER['HTTP_HOST'] . SITE_BASE_URL);
		define("APPLICATION_URL","http://" . $_SERVER['HTTP_HOST'] . SITE_BASE_URL);
		define("ADMIN_APPLICATION_URL", SITE_HTTP_URL."/".BACKEND);
		define("TEST", false);
	}
	define('HTTP_RESOURCE_PATH', SITE_HTTP_URL.'/public/resources/resource_attachments');
	define('RESOURCE_PATH', ROOT_PATH.'/public/resources/resource_attachments');
	define("GOOGLE_API", 'AIzaSyB7cE4DGHkmBz2z6OcaLoun9zqm3VoPyVU');
	define("RESOURCE_EXTENSION",serialize(array("jpeg","jpg","JPG","JPEG","png","PNG","doc","DOC","docx","DOCX","pdf")));/*,"xls","xlsx","txt"*/
	define('PRICE_SYMBOL','$');	
	define('PRICE_SYMBOL_VALUE','USD');	
	define('PUBLIC_PATH', APPLICATION_URL.'/public/');	
	define('RECORD_PER_PAGE', 50);	
	
	/*define("IMAGE_VALID_EXTENTIONS","jpg,JPG,png,PNG,jpeg,JPEG");
	define("IMAGE_VALID_SIZE","25MB");*/
	
	define("DOCUMENT_VALID_EXTENTIONS","doc,docx,pdf");
	
	define("IMAGE_VALID_EXTENTIONS","jpg,JPG,png,PNG,jpeg,JPEG");
	define("IMAGE_VALID_SIZE","50");
	
	define("VIDEO_VALID_EXTENTIONS","mp4,MP4");
	define("VIDEO_VALID_SIZE","50");
	
	
	define("ADMIN_MSG_AUTH_NAMESPACE", "ADMIN_MSG_AUTH");
	define("DEFAULT_MSG_AUTH_NAMESPACE", "DEFAULT_AUTH_NAMESPACE");
	
	define("FRONT_CSS",SITE_HTTP_URL.'/public/css');
	define("FRONT_JS",SITE_HTTP_URL.'/public/js');
	define("FRONT_ASSETS",SITE_HTTP_URL.'/public/front_assets');
	define("PLUGIN_PATH",SITE_HTTP_URL.'/public/plugins');
	
	define("ASSET_CSS",SITE_HTTP_URL.'/public/assets/css');
	define("ASSET_JS",SITE_HTTP_URL.'/public/assets/js');
	
	define('HTTP_IMG_PATH', SITE_HTTP_URL.'/public/img');
	
	define('HTTP_PROFILE_IMAGES_PATH', SITE_HTTP_URL.'/public/resources/profile_images');
 	define('PROFILE_IMAGES_PATH', ROOT_PATH.'/public/resources/profile_images');
	
	define('HTTP_SLIDER_IMAGES_PATH', SITE_HTTP_URL.'/public/resources/slider_images');
 	define('SLIDER_IMAGES_PATH', ROOT_PATH.'/public/resources/slider_images');
	
	
	
	define('HTTP_HOME_IMAGES_PATH', SITE_HTTP_URL.'/public/resources/home_images');
 	define('HOME_IMAGES_PATH', ROOT_PATH.'/public/resources/home_images');
	
	define('HTTP_APPLY_IMAGES_PATH', SITE_HTTP_URL.'/public/resources/apply_data');
 	define('APPLY_IMAGES_PATH', ROOT_PATH.'/public/resources/apply_data');
	
	define("THUMB_FALSE",serialize(array(
		"pdf","txt","doc","docx","mp3","avi","csv","ppt","xls","zip","swf","flv","webm","mp4","srt"
	)));
	
	global $img_extension,$globalmonth,$globaldobyear,$globalday,$TimeArray,$globalexpyear,$memPermissionArr,$couponTypeArr;
	$current_year=date('Y');
	$img_extension=array('gif','png','jpg','jpeg','GIF','PNG','JPG','JPEG');
	
	for($m=1;$m<=31;$m++){if($m<10){ $m = "0".$m;} $globalday[$m]=$m;}
	
	$globalmonth = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October",
	"11"=>"November","12"=>"December");
	
	for($i=$current_year+1;$i<=$current_year+60;$i++){	$globalexpyear[$i]=$i;  }
	for($i=1917;$i<=$current_year;$i++){	$globaldobyear[$i]=$i;  }
	
	
	global $isiPad,$isiPhone,$isPhone,$expArr,$eduArr,$degreeArr,$yearArr,$roleArr,$engLevelArr,$adTypeArr,$adPosArr,$cardTypeArr,$jobTypeArr,$jobDelArr,$jobLangArr;

	$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	$isiPhone = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');
	
	
	for($i=1950;$i<=date('Y');$i++){
		$yearArr[$i]=$i;
	}
	
	$engLevelArr=array(
		'0'=>'Basic',
		'1'=>'Advanced',
		'2'=>'Fluent',
	);
	$adTypeArr=array(
		'0'=>'Image',
		'1'=>'Adsence Code',
	);
	
	global $clientHire;
	$clientHire=array(
	0=>'No Hires',
	1=>'1 to 9 Hires',
	2=>'10+ Hires ',
	);
	global $sort_arr;
	$sort_arr=array(
	0=>'Newest',
	1=>'Client Spending',
	2=>'Client Rating ',
	);
	$cardTypeArr=array('Visa'=> 'Visa','Maestro'=>'Maestro','Mastercard'=>'Mastercard','American Express'=>'American Express');
	$paymentModeArr=array('0'=> 'Sandbox','1'=>'Live');
	
	global $rating_arr;
	$rating_arr=array(5,4,3,2,1);
	global $currencyBox;
	$currencyBox=array("0"=>"United States dollar(USD)","1"=>"Canadian dollar(CAD)");
	global $methodBox;
	$methodBox=array("1"=>"Wire Transfer","2"=>"Check","3"=>"Interac e-transfer");
	
	global $currencyCode;
	$currencyCode=array("0"=>"USD","1"=>"CAD");