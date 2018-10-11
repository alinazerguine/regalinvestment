<?php
//mail("mattmurdock882@gmail.com",'feedback cron',"Cron Execution Starts1");
session_start();
error_reporting(E_ALL);
ini_set("display_errors","on");
date_default_timezone_set("America/Los_Angeles");
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));
defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(__FILE__) . ''));
defined('ROOT_PATH_ORIGINAL') || define('ROOT_PATH_ORIGINAL', realpath(dirname(dirname(__DIR__)) . ''));
define("SITE_BASE_URL", dirname($_SERVER['PHP_SELF']));

/*if(preg_match('/192.168/',$_SERVER['REMOTE_ADDR'])){
	define("SITE_HOST_URL", "http://" . $_SERVER['HTTP_HOST']);
	define("SITE_HTTP_URL", "http://" . $_SERVER['HTTP_HOST'] . SITE_BASE_URL);
	define("APPLICATION_URL", "http://" . $_SERVER['HTTP_HOST'] . SITE_BASE_URL);
}else{*/
	define("SITE_URL","http://techdemolink.co.in/regalinvestments/");
	
	/*if($_SERVER['HTTP_HOST']=='')
	{*/
		define("SITE_HOST_URL","https://".SITE_URL);
		define("SITE_HTTP_URL","https://".SITE_URL);
		define("APPLICATION_URL","https://".SITE_URL);
	/*}else{
		define("SITE_HOST_URL","https://".$_SERVER['HTTP_HOST']);
		define("SITE_HTTP_URL","https://".$_SERVER['HTTP_HOST']);
		define("APPLICATION_URL","https://".$_SERVER['HTTP_HOST']);
	}*/
/*}*/

/*if(preg_match('/192.168/',$_SERVER['REMOTE_ADDR'])){ 
	define('SITE_STAGE','development');	 //development
	define('DB_NAME', 'regalinvestments');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'ayog123!@');
}else{*/
	define('SITE_STAGE','production');
	define('DB_NAME', 'techdemo_regalinvestments');
	define('DB_USERNAME', 'techdemo_regalin');
	define('DB_PASSWORD', '.BCQTTC74M$6');
/*}*/

define('PRICE_SYMBOL','$');
define('PRICE_SYMBOL_VALUE','USD');


try {
	$connection = new PDO("mysql:host=localhost;dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);	
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//	echo "Connected successfully";
}
catch(PDOException $e)
{
	//echo "Connection failed: " . $e->getMessage();
}


function SendEmail($from_name,$from_mail,$mailto,$subject,$message_body)
{
	
	require_once(ROOT_PATH_ORIGINAL.'/vendor/sendgrid/vendor/autoload.php');
		
	//$apiKey = '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';
	$sendgrid = new\SendGrid($apiKey); 
	$email= new\SendGrid\Email(); 
	$email->addTo($mailto,$mailto);
	
	$email->setFrom($from_mail, $from_name)
		->setFromName($from_name)
		->setSubject($subject)
		->setHtml($message_body);
	
	$res=$sendgrid->send($email);
	
	return true;
}

/* fetch data from orders table and make its status */
$currentOrdData=date("Y-m-d"); //H:i:s
//echo $currentOrdData;
$oneDayprevious=date('Y-m-d', strtotime('-1 day', strtotime($currentOrdData)));// H:i:s


/* get config data */
$configData="select * from config where 1";

$configListData = $connection->query($configData);
$all_configListData = $configListData->fetchAll(PDO::FETCH_ASSOC);



if(true){
	/* get project data */

/* set plan expire date*/	
/*$planData="select * from users_plan where user_plan_expiredate='".date("Y-m-d")."'";
$planListData = $connection->query($planData);
$all_planListData = $planListData->fetchAll(PDO::FETCH_ASSOC);*/

$UpdateQuery = "UPDATE users_plan set user_plan_isactive='3' where user_plan_expiredate='".date("Y-m-d")."'";

$stmt = $connection->prepare($UpdateQuery);
$stmt->execute();
}

die;