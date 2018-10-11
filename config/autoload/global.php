<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;
return array(
   	'db' => array(
		 'driver'         => 'Pdo',	
		 'dsn'            => 'mysql:dbname=techdemo_regalinvestments;host=localhost',//techdemo_survey
		 'driver_options' => array(
			 PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
		 ),
	 ),
    'module_layouts' => array(
       'Application' => 'layout/layout.phtml',
       'Admin' => 'layout/admin/layout.phtml',
	   
   ),
   // Session configuration.
    'session_config' => array(
        // Session cookie will expire in 1 hour.
      //  'cookie_lifetime' => 60*60*1,     
        // Session data will be stored on server maximum for 30 days.
       // 'gc_maxlifetime'     => 60*60*24*30, 
    ),
    // Session manager configuration.
    'session_manager' => array(
        // Session validators (used for security).
        'validators' => array(
            RemoteAddr::class,
            //HttpUserAgent::class,
        )
    ),
    // Session storage configuration.
    'session_storage' => array(
        'type' => SessionArrayStorage::class
    ),
);