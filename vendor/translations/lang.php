<?
if(preg_match('/192.168/',$_SERVER['REMOTE_ADDR'])){
	define('SITE_STAGE','development');	 //development
	define('HOSTNAME','localhost');
	define('DB_NAME', 'freelancersouq');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'ayog123!@');
}else{
	define('SITE_STAGE','production');
	define('HOSTNAME','localhost');
	define('DB_NAME', 'freelanc_freelancersouq');
	define('DB_USERNAME', 'freelanc_freelan');
	define('DB_PASSWORD', 'R53u4}O5#CZO');
	
}
try {
	$connection = new PDO("mysql:host=".HOSTNAME.";dbname=".DB_NAME,DB_USERNAME,DB_PASSWORD);
	$connection->exec("SET CHARACTER SET utf8");	
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo "Connection failed: " . $e->getMessage();
}

$LanguageData = "select * from labels";
$LanguageData = $connection->query($LanguageData);
$LanguageData = $LanguageData->fetchAll(PDO::FETCH_ASSOC);
$LanguageArray = array();
if($_COOKIE["currentLang"]=="ar"){
	foreach($LanguageData as $lang)
	{
		$LanguageArray[$lang['label_text']]=$lang['arabic_text'];
	}
}else{
	foreach($LanguageData as $lang)
	{
		$LanguageArray[$lang['label_text']]=$lang['label_text'];
	}
}
return $LanguageArray;

?>