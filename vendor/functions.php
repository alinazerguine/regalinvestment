<?php

function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess','thumbnail');

    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}

function changedateformat($date,$from_format,$to_format){
		$date=date_create_from_format($from_format,$date);
		$date=date_format($date,$to_format);
		return $date;	
}
function getYearType($book_type,$investing_reason=0){
	$txt_year=$txt_level='';
	switch($book_type){
		case 1;
		$txt_year="15 years";
		$txt_level="Risk level: <span style='color:green;'>1</span>";
		break;
		case 2;
		$txt_year="2 years";
		$txt_level="Risk level: <span style='color:green;'>2</span>";
		break;
		case 3;
		$txt_year="1 year";
		$txt_level="Risk level: <span style='color:yellow;'>3</span>";
		break;
		case 4;
		$txt_year="1 year";
		$txt_level="Risk level: <span style='color:green;'>1</span>";
		break;
		case 5;
		if($investing_reason==1){
			$txt_year="1 year";
			$txt_level="Risk level: <span style='color:red;'>5</span>";
		}
		if($investing_reason==2){
			$txt_year="1 year";
			$txt_level="Risk level: <span style='color:red;'>4</span>";
		}
		if($investing_reason==3){
			$txt_year="1 year";
			$txt_level="Risk level: <span style='color:yellow;'>3</span>";
		}
		if($investing_reason==4){
			$txt_year="1 year";
			$txt_level="Risk level: <span style='color:yellow;'>3</span>";
		}
		
		break;
			
	}
	return array("txt_year"=>$txt_year,"txt_level"=>$txt_level);
}
function planstatus($status){
	switch($status){
		
		case '0': return array("status"=>'Pending',"labelclass"=>'label label-primary'); break;
		case '1': return  array("status"=>'Approved',"labelclass"=>'label label-success'); break;
		case '2': return  array("status"=>'Rejected',"labelclass"=>'label label-danger'); break;
		case '3': return  array("status"=>'Expired',"labelclass"=>'label label-warning'); break;
		default: return  array("status"=>'Nothing',"labelclass"=>'label label-default'); break;
		
		
	}	
}
function showPostTime($date){
	
  // 2 years, 3 months and 2 days format
$date1 = $date;
 $date2 = date('Y-m-d H:i:s') ;
  $diff = abs(strtotime($date2) - strtotime($date1));
  $years = floor($diff / (365*60*60*24));
 $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
 $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
 $hours = floor($diff / 60 /  60);
 $minutes = floor($diff / 60 );
  
 if($years){
  if($years>1)
   return $years." ".'years'; 
  else
   return $years." ".'year';
 }
 else if($months){
  if($months>1)
   return $months." ".'months';
  else
   return $months." ".'month';
 }
 else if($days){
  if($days>1)
   return $days." ".'days';
  else
   return $days." ".'day';
 }
 else if($hours){
  if($hours>1)
   return $hours." ".'hours';
  else
   return $hours." ".'hour';
 }
 else{
  if($minutes>1)
   return $minutes." ".'mins';
  else
   return $minutes." ".'min';
 } 
 
 //printf("%d years, %d months, %d days\n", $years, $months, $days);
 
 //2 days
 
}
function calculateplan($planData,$configData,$currentValue,$invest_type,$invest_opt,$isId=0){
	$site_plan_price=$configData['site_plan_price'];
	$masterPlanarray=array();
	if($currentValue >= 0){ 
			if($invest_opt==0){
				if($currentValue >= $site_plan_price){//If total investment is over $5000 
					//site_over_amount
					$planType=$configData['site_over_amount'];
				}elseif($currentValue < $site_plan_price){//If total investment is under $5000 
					//site_under_amount
					$planType=$configData['site_under_amount'];
				}
				
			}else{
				
				
				$fileds=array("site_under_investing_myself"=>"1","site_over_investing_myself"=>"1","site_under_employer_plan"=>"2","site_over_employer_plan"=>"2","site_under_investment_advisor"=>"3","site_over_investment_advisor"=>"3");	
				$is_Keys_ext=array_keys($fileds,$invest_type);
				if(!empty($is_Keys_ext)){
						if($currentValue >= $site_plan_price){//If total investment is over $5000 
							$planType=$configData[$is_Keys_ext[1]];
						}elseif($currentValue < $site_plan_price){//If total investment is under $5000 
							//site_under_amount
							$planType=$configData[$is_Keys_ext[0]];
						}			
				}
			}
			
			$ex_planData=explode(",",$planType);
			if(!empty($ex_planData)){
					foreach($ex_planData as $exKey=>$exValue){
					if(!empty($planData)){
						foreach($planData as $pKey=>$pValue){
							if($pValue['inv_pl_id']==$exValue){
								if($isId==1){
									$masterPlanarray[]=$pValue['inv_pl_id'];
								}else{
								$masterPlanarray[]=$pValue['inv_pl_title'];
								}
								break;
							}
							
						}
					}
					}
				}
			
	}
	$planname='';
	if(!empty($masterPlanarray)){
		$planname=implode(" + ",$masterPlanarray);	
	}
	return $planname;
}

function removedecimal($value){
	$price=explode(".",$value);
	if(isset($price[1]) && $price[1]>0){
		return 	$value;
	}
	return $price[0];
}


function myurl_encode($id)
{
	$passstring=$id*12345;
	$encrypted_string=encrypt_decrypt($passstring);
	$param=base64_encode($encrypted_string);
	return $param;
}
function myurl_decode($id)
{
	$passstring=base64_decode(stripslashes($id));
	$decrypted_string= encrypt_decrypt($passstring);
	$Tutors_id= $decrypted_string/12345;
	return $Tutors_id;

}
function encrypt_decrypt($string)
 {
 
		$string_length=strlen($string);
		$encrypted_string=""; 
		/**
		*For each character of the given string generate the code
		*/
		for ($position = 0; $position < $string_length;$position++){        
			$key = (($string_length+$position)+1);
			$key = (255+$key) % 255;
			$get_char_to_be_encrypted = SUBSTR($string, $position, 1);
			$ascii_char = ORD($get_char_to_be_encrypted);
			$xored_char = $ascii_char ^ $key;  //xor operation
	  
			$encrypted_char = CHR($ascii_char);
	  
			$encrypted_string .= $encrypted_char;
		} 
	   return $encrypted_string;
 }
function getDateTimeformaton($fieldTime){
	$dateFormat=date('d M,Y H:i:s',strtotime($fieldTime));		
	return $dateFormat;
}
function getDateformaton($fieldTime){
	$dateFormat=date('d M,Y',strtotime($fieldTime));		
	return $dateFormat;
}
function getDateformat($fieldTime){
	$dateFormat=date('d M,Y H:i',strtotime($fieldTime));		
	return $dateFormat;
}
	
function generatePassword($string){
	return $string." ".rand(1,9999999);
}

function image_fix_orientation($filename,$target,$new_name) {
    $exif = exif_read_data($filename);
    if (!empty($exif['Orientation'])) {
		$image = imagecreatefromjpeg($filename);
        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;

            case 6:
                $image = imagerotate($image, -90, 0);
                break;

            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }
		//echo $image;die;
		if(isset($image)){
			$newName = time().$new_name; 
			imagejpeg($image,$target.'/'.$newName);
			unlink($target.'/'.$new_name);
		}else{
			$newName = $new_name;
		}
    }else{
			$newName = $new_name;
	}
	return $newName;
}

function sort_multiarray($array,$orderby) {
	
	$sortArray = array(); 
	
	foreach($array as $person){ 
		foreach($person as $key=>$value){ 
			if(!isset($sortArray[$key])){ 
				$sortArray[$key] = array(); 
			} 
			$sortArray[$key][] = $value; 
		} 
	} 
	
	array_multisort($sortArray[$orderby],SORT_DESC,$array); 
	
	return $array; 
}


/* Match Passwordss */
function match_old_password($value){
	$loggedUser = Zend_Session::namespaceGet(ADMIN_AUTH_NAMESPACE);
 	if(isset($loggedUser['storage']) and !empty($loggedUser['storage'])){
  		return $loggedUser['storage']->user_password === md5($value);
	};
	return false ;
}

function match_old_password_front($value){
	$logged_identity = Zend_Auth::getInstance()->getInstance();
	if($logged_identity->hasIdentity()){
		$logged_identity = $logged_identity->getIdentity();
		return $logged_identity->user_password === md5($value);
	}
	return false ;
}

function genratePassword($string){
	return $string." ".rand(1,999);
}

function formatImageName($images_name){
	return str_replace(
		array(
			"/"," ",
		),
		array(
			"-","-"
		),
		$images_name
	);
}

function getUserImage($user_image,$full_url = false,$user_type=false){
	if($user_image!="" and file_exists(PROFILE_IMAGES_PATH."/".$user_image)){
		$image_url = $user_image;
	}
	else{
		$image_url = "default.png";		
	}
	switch($full_url){		
		case '60': return $image_url = HTTP_PROFILE_IMAGES_PATH."/60/$image_url"; break;
		case '160': return  $image_url = HTTP_PROFILE_IMAGES_PATH."/160/$image_url"; break;
		case 'thumb': return  $image_url = HTTP_PROFILE_IMAGES_PATH."/thumb/$image_url"; break;			
		default: return  $image_url = HTTP_PROFILE_IMAGES_PATH."/$image_url"; break;
	}
}

function loadClass($classpath){
	$cpath = str_replace("_", "/", $classpath);
	include( $classpath);
}

function isImage($name,$type){
	switch($type){
		case "admin profile" :
  			if(!empty($name) and file_exists(ROOT_PATH."/".ADMIN_PROFILE."/".$name)){
				return $name;
			}else{
				return "avatar.png";
			}
		break ;
		case "" : break ;
		case "" : break ;
		case "" : break ;
	}
}


function MY_setLayout($layout){
	Zend_Layout::getMvcInstance()->setLayout($layout);
}

/* Return Difference Between Two Dates */
function getDifference($date1 ,$date2 , $in ='d'){
  	$diff = abs(strtotime($date2) - strtotime($date1));
 	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	$hours = floor($diff / 60 /  60);
	$minites = floor($diff / 60 );
	
	switch($in){
  		case 'd':
 			$op_days = $years*365 + $months*30 + $days; 
			return $op_days ;
 			/* Return No of Days Between Two Days*/
		break;
		
		case 'h':
 			$hours = $hours % 24;
			
			if($minites!=0){
				return  $hours +1;
			}
			return $hours ;
		/* Return No of Hours After Day */
		break;
	}
 	
	if($years){
		return $years." years " ;
	}
	elseif($months){
		return $months." months "; 
	}
	elseif($days){
		return $days." days ";
	}
	elseif($hours){
		return $hours." hours ";
	}
	else{
		return $minites." minites ";
	} 
	//printf("%d years, %d months, %d days\n", $years, $months, $days);
}

function change_to_language($str){
	return $str; 
}

function getMonthName($date , $short = false){
 	$monthNames  = array("January", "February", "March", "April", "May", "June",  "July", "August", "September", "October", "November", "December" );
	$name  = $monthNames[date("m", strtotime($date))-1] ;
	if($short)
		return substr($name,0,3);
	return $name ;
}

function getDayName($date , $short = false){
  	switch(date("D", strtotime($date))){
 		case 'Mon': return ucwords('monday');
		case 'Tue': return ucwords('tuesday');
		case 'Wed': return ucwords('wednesday');
		case 'Thu': return ucwords('thursday');
		case 'Fri': return ucwords('friday') ;
		case 'Sat': return ucwords('saturday');
		case 'Sun': return ucwords('sunday');
	}
	return ;
}

function getDMYFormat($data , $saperater="."){
	$timestamp = strtotime($data); 
	$format ="d".$saperater."m".$saperater."Y" ;
	$new_date = date($format , $timestamp);
	return $new_date ;
}

function isLogged($info=false)
{	
	$user = Zend_Auth::getInstance()->getIdentity();	 
	if($user){ 
		if($info) return $user  ;
		else return $user->user_id ;
	}else{
		return false;	
	}
}

function resetSeoUrl($str){
	$str=str_ireplace("-"," ",$str) ;
	$str=str_ireplace(".html","",($str)) ; 	
	return ucwords($str);  
}

function setFlashErrorMessage($message){	 
	$registry = Zend_Registry::getInstance();
	$registry->set("flash_error",$message);
}

function getFileExtension($filename=''){	
	$ext=@array_pop(explode(".",$filename));
	return $ext ;
}

function showDateOnly($dateTimeStr) 
{ 
    $array = explode(" ",$dateTimeStr); 
	return $array[0];
}

function formatDate($dateTimeStr){
	$dtobj=strtotime($dateTimeStr);
	$format=date("F j, Y",$dtobj) ;
	return $format ;
}

function formatDateTime($dateTimeStr){
	$dtobj=strtotime($dateTimeStr);
	$format=date("g:i a F j, Y ",$dtobj) ;
	return $format ;
}

function trimValues(&$value) 
{ 
    $value = trim($value); 
}

function shortString($str,$length=200,$addDots=false){
	$substr=substr($str,0,$length);
	
	if($addDots){return $substr.'...' ;}
	else{ return $substr ;}
}
function prn($var)
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function pr($var)
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function prd($var)
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	die;
}

function gcm($var)
{
	if (is_object($var))
		$var = get_class($var);
	echo '<pre>';
	prn(get_class_methods($var));
	echo '</pre>';
}

function getActivationKey($string)
{
	$key = md5($string);
	return $key;
}

function get_url_contents($url){
	$crl = curl_init();
	$timeout = 5;
	curl_setopt ($crl, CURLOPT_URL,$url);
	curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	$ret = curl_exec($crl);
	curl_close($crl);
	return $ret;
}
 
function format_product_name($name){
	$name = str_replace(array(' ','_'),array('-','-'),$name);
	return strtolower($name);; 
}

function stringWithDot($string,$limit)
{
 if(strlen($string)>$limit)
 {
	 $string=substr($string,0,$limit)."...";
 }
return $string;  
}

function countText($string,$limit)
{
 if(strlen($string)>$limit)
 {
	 return true;  
 }
 else
 {
	 return false;  
 }
}


function dateFormat($date)
{
	$result=date('m-d-Y',strtotime($date));
	return $result;
}
function stateName($state_id){
	$db = Zend_Registry::get('db');
	$countrydata=$db->query('select state_name from state where state_id='.$state_id)->fetch();
 	return $countrydata['state_name'];
}

function getUniqeID()
{
  $s = strtoupper(md5(uniqid(rand(),true))); 
    $guidText = 
     
        substr($s,20); 
    return $guidText;
}


function SendEmailAttach($from_name,$from_mail,$mailto,$subject,$message_body,$fileattach,$filenamepdf)
{
   
   	
  // $from_name='Forfice';
  
    $from_name = 'Forfice <dummydemo01@webdemo1.co.in>'; 
  
   $from_mail=$from_mail;
   $replyto=$from_mail;
   $message=$message_body;
   $mailto=$mailto;
  
   $file = $fileattach;
   $file_size = filesize($file);
   $handle = fopen($file, "r");
   $content = fread($handle, $file_size);
   fclose($handle);
   $content = chunk_split(base64_encode($content));
  
   $uid = md5(uniqid(time()));
   $name = basename($filenamepdf);
   
   $header = "From: ".$from_name."\n";
   $header .= "Reply-To: ".$replyto."\n";
   $header .= "MIME-Version: 1.0\n";
   $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\n\n";
 
   $emessage .= "--".$uid."\n";
   $emessage .= 'Content-type: text/html; charset=iso-8859-1' . "\n"; 
   $emessage .= "Content-Transfer-Encoding: 7bit\n\n";
   $emessage .= $message_body."\n\n";
   $emessage .= "--".$uid."\n";
   
   if(isset($file) && $file!='')
   {   
	   $emessage .= "Content-Type: application/octet-stream; name=\"".$file."\"\n"; // use different content types here
	   $emessage .= "Content-Transfer-Encoding: base64\n";
	   $emessage .= "Content-Disposition: attachment; filename=\"".$filenamepdf."\"\n\n";
   }
   $emessage .= $content."\n\n";
   $emessage .= "--".$uid."--";
   mail($mailto, $subject, $emessage, $header);
   return true;
}
function siteIdEncode($id,$type){
	
	return base64_encode($id.'_'.$type);
}

function siteIdDecode($id,$type){
		
	if($type=="creditcard" || $type=="events"){
		return str_replace('_'.$type,"",base64_decode($id));		
	}else{
		return (int)str_replace('_'.$type,"",base64_decode($id));		
	}
}

function encodeText($string)
{
	$string=base64_encode(trim($string));
	return $string;
}

function decodeText($string)
{
	$string=base64_decode($string);
	return $string;
}

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function getRouteUrl($str){
	$str=strtolower(trim($str)) ;	
	$replace=array('.',',',' ','&','_','-');
	$str=str_replace($replace,'_',$str);	
	return $str;
}

function addTimeInDate($minutes){
	
	return date('Y-m-d H:i:s', strtotime("+".$minutes." minutes"));
}

function hoursToMinutes($hours)
{
	$separatedData = explode('.', $hours);	
	$minutesInHours    = $separatedData[0] * 60;	
	if($separatedData[1]=='5')
	{
		$minutesInDecimals = $separatedData[1]*6;			
	}	
	$totalMinutes = $minutesInHours + $minutesInDecimals;
	return $totalMinutes;
}


function getCount($request_id)
{
	$db = Zend_Registry::get('db');
 	return $db->select()->from('admin')->where('admin_id=1')->query()->fetch();
}
function xml2array ( $result, $out = array () ){
  foreach ( (array) $result as $index => $node )
   $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
  return $out;
 }
 
 function getMonthsInRange($startDate, $endDate) {
$months = array();
while (strtotime($startDate) <= strtotime($endDate)) {
    $months[] = array('year' => date('Y', strtotime($startDate)), 'month' => date('M', strtotime($startDate)), 'date' => date('d', strtotime($startDate)));
    $startDate = date('d M Y', strtotime($startDate.
        '+ 1 month'));
}

return $months;
}

function encodeProfileUrl($profileName)
{
	$profileName=str_replace(" ","-",$profileName);
	$profileName=str_replace(".","-",$profileName);
	$profileName=str_replace("'","-",$profileName);
	$profileName=str_replace('"',"-",$profileName);
	$profileName=str_replace(',',"-",$profileName);
	$profileName=str_replace('/',"-",$profileName);
	$profileName=str_replace('&',"-",$profileName);
	$profileName=str_replace(':',"-",$profileName);
	return ($profileName);
}

function getTimeDifference($timedata)
{
	$years1=0;
	$months1=0;
	$days1=0;
	$day_difference1="";
	$hours1=0;
	$minutes1=0;
	
	$diff1 = strtotime(date("Y-m-d H:i:s"))-strtotime($timedata);
	$day_difference1 = ceil($diff1 / (60*60*24));
	$years = floor($diff1 / (365*60*60*24));
	$months = floor(($diff1 - $years1 * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff1 - $years1 * 365*60*60*24 - $months1*30*60*60*24)/ (60*60*24));
	$hours = floor(($diff1 - $years1 * 365*60*60*24 - $months1*30*60*60*24- $days1* 60*60*24)/(60*60));
	$minutes = floor(($diff1 - $years1 * 365*60*60*24 - $months1*30*60*60*24- $days1* 60*60*24- $hours1*60*60)/(60));
	$seconds = floor(($diff1 - $years1 * 365*60*60*24 - $months1*30*60*60*24- $days1* 60*60*24- $hours1*60*60)/(3600));
	return array($years,$months,$days,$hours,$minutes,$seconds);	
}


function getlinkfromcontent($text)
{
	
// The Regular Expression filter
$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

// The Text you want to filter for urls

// Check if there is a url in the text
if(preg_match($reg_exUrl, $text, $url)) {

// make the urls hyper links
echo preg_replace($reg_exUrl, "<a target='_new' href='".$url[0]."' target='_blank' style='color:#166cec;text-decoration:underline;'>".$url[0]."</a>", $text);

} else {

// if no urls in the text just return the text
return $text;

}
}

function replaceFieldName($data,$key,$fsouq){
	$formData=array();
	foreach($data as $match=>$newData) {
		$newKey=str_replace($key.'_',$fsouq.'_',$match);
		$formData[$newKey]=$newData;
	}
	return $formData;
}

function paramValEncode($paramVal)
{
	$paramVal=base64_encode($paramVal);
	$paramVal=chop($paramVal,"=");
	return $paramVal;
}
function paramValDecode($paramVal)
{
	$paramVal=$paramVal."=";
	$paramVal=base64_decode($paramVal);
	return $paramVal;
}

function getUploadFileExtension($fileName)
{
	$ext = pathinfo($fileName, PATHINFO_EXTENSION);
    switch($ext){
		case 'txt': $img="txt.png";break;
		case 'TXT': $img="txt.png";break;
		case 'doc': $img="doc.png";break;
		case 'DOC': $img="doc.png";break;
		case 'docx': $img="doc.png";break;
		case 'DOCX': $img="doc.png";break;
		case 'pdf':  $img="pdf.png";break;
		case 'PDF':  $img="pdf.png";break;
		case 'png':  $img="img.png";break;
		case 'PNG':  $img="img.png";break;
		case 'jpeg': $img="img.png";break;
		case 'JPEG': $img="img.png";break;
		case 'jpg':  $img="img.png";break;
		case 'JPG':  $img="img.png";break;
		case 'avi':  $img="video.png";break;
		case 'mp4':  $img="video.png";break;
		case 'mov':  $img="video.png";break;
		case 'flv':  $img="video.png";break;
		case 'aov':  $img="video.png";break;
		case 'mpg':  $img="video.png";break;
		case 'wmv':  $img="video.png";break;
		case 'MPG':  $img="video.png";break;
		case 'WMV':  $img="video.png";break;
		case 'AVI':  $img="video.png";break;
		case 'MP4':  $img="video.png";break;
		case 'FLV':  $img="video.png";break;
		case 'AOV':  $img="video.png";break;
		case '3gp':  $img="video.png";break;
	 }
	 return $img;
}
function timeAgo($time_ago) {
    $time_ago =  strtotime($time_ago) ? strtotime($time_ago) : $time_ago;
	$time  = time() - $time_ago;

switch($time):
// seconds
case $time <= 60;
return 'less than a minute ago';
// minutes
case $time >= 60 && $time < 3600;
return (round($time/60) == 1) ? 'a minute' : round($time/60).' minutes ago';
// hours
case $time >= 3600 && $time < 86400;
return (round($time/3600) == 1) ? 'a hour ago' : round($time/3600).' hours ago';
// days
case $time >= 86400 && $time < 604800;
return (round($time/86400) == 1) ? 'a day ago' : round($time/86400).' days ago';
// weeks
case $time >= 604800 && $time < 2600640;
return (round($time/604800) == 1) ? 'a week ago' : round($time/604800).' weeks ago';
// months
case $time >= 2600640 && $time < 31207680;
return (round($time/2600640) == 1) ? 'a month ago' : round($time/2600640).' months ago';
// years
case $time >= 31207680;
return (round($time/31207680) == 1) ? 'a year ago' : round($time/31207680).' years ago' ;

endswitch;
}

function getTimeSlot($slotType){
	$SlotResponse=''; 
	 
	switch($slotType){
		case 1;
		$slotType="15";
		$slotSecondes=15*60;
		break;
		case 2;
		$slotType="30";
		$slotSecondes=30*60;
		break;
		case 3;
		$slotType="45";
		$slotSecondes=45*60;
		break;
		case 4;
		$slotType="60";
		$slotSecondes=60*60;
		break;
	}
	return array("slotType"=>$slotType,"slotSecondes"=>$slotSecondes);
}