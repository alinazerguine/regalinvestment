<?php 
global $_allowed_resources ;
$_allowed_resources = array(
	'Admin'=>array('IndexController'=>array('login','forgotpassword','resetpassword','errorpage')),
	'AuthAcl'=>array(
		'IndexController',
		'SocialController',
	),
	'Application'=>array(
		'IndexController',
		'StaticController',
		'SearchController',
	),
);

$_blocked_resources = array(
	BACKEND_NAME=>array(),
	'admin'=>array(),
);	