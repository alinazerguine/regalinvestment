<?php

########## Google Settings.. Client ID, Client Secret #############
$google_client_id 		= '384965020779.apps.googleusercontent.com';
$google_client_secret 	= 'MB85uWptAQjDZu_fYFvQHT9-';
$google_redirect_url 	= 'http://tbsdemo.in/textbookvalet';
$google_developer_key 	= '384965020779@developer.gserviceaccount.com';

########## MySql details (Replace with yours) #############
$db_username = "tbsdein_textbook"; //Database Username
$db_password = "B04$8;V)U4w]"; //Database Password
$hostname = "localhost"; //Mysql Hostname
$db_name = 'tbsdein_bookvalet'; //Database Name
###################################################################

//include google api files
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_Oauth2Service.php';

//start session
session_start();

$gClient = new Google_Client();
$gClient->setApplicationName('Login to saaraan.com');
$gClient->setClientId($google_client_id);
$gClient->setClientSecret($google_client_secret);
$gClient->setRedirectUri($google_redirect_url);
$gClient->setDeveloperKey($google_developer_key);

$google_oauthV2 = new Google_Oauth2Service($gClient);

//If user wish to log out, we just unset Session variable
if (isset($_REQUEST['reset'])) 
{
  unset($_SESSION['token']);
  $gClient->revokeToken();
  header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
}

//Redirect user to google authentication page for code, if code is empty.
//Code is required to aquire Access Token from google
//Once we have access token, assign token to session variable
//and we can redirect user back to page and login.
if (isset($_GET['code'])) 
{ 
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
	return;
}


if (isset($_SESSION['token'])) 
{ 
		$gClient->setAccessToken($_SESSION['token']);
}


if ($gClient->getAccessToken()) 
{
	  //Get user details if user is logged in
	  $user 				= $google_oauthV2->userinfo->get();
	  $user_id 				= $user['id'];
	  $user_name 			= filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	  $email 				= filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	  $profile_url 			= filter_var($user['link'], FILTER_VALIDATE_URL);
	  $profile_image_url 	= filter_var($user['picture'], FILTER_VALIDATE_URL);
	  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
	  $_SESSION['token'] 	= $gClient->getAccessToken();
}
else 
{
	//get google login url
	$authUrl = $gClient->createAuthUrl();
}

//HTML page start
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<title>Login with Google</title>';
echo '</head>';
echo '<body>';
echo '<h1>Login with Google</h1>';

if(isset($authUrl)) //user is not logged in, show login button
{
	echo '<a class="login" href="'.$authUrl.'"><img src="images/google-login-button.png" /></a>';
} 
else // user logged in 
{ echo "hi"; die;
   /* connect to mysql */
    $connecDB = mysql_connect($hostname, $db_username, $db_password)or die("Unable to connect to MySQL");
    mysql_select_db($db_name,$connecDB);
	
    //compare user id in our database
    $result = mysql_query("SELECT COUNT(user_id) FROM users WHERE user_id=$user_id");
	if($result === false) { 
		die(mysql_error()); //result is false show db error and exit.
	}
	
	$UserCount = mysql_fetch_array($result);
 
    if($UserCount[0]) //user id exist in database
    {
		echo 'Welcome back '.$user_name.'!';
    }else{ //user is new
		echo 'Hi '.$user_name.', Thanks for Registering!';
		@mysql_query("INSERT INTO users (user_id, first_name, email) VALUES ($user_id, '$user_name','$email')");
	}

	
	echo '<br /><a href="'.$profile_url.'" target="_blank"><img src="'.$profile_image_url.'?sz=50" /></a>';
	echo '<br /><a class="logout" href="?reset=1">Logout</a>';
	
	//list all user details
	echo '<pre>'; 
	print_r($user);
	echo '</pre>';	
}
 
echo '</body></html>';
?>
