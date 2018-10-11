<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
	return array(
		'Zend\Form',
		'Zend\Db',
		'Zend\Mail',
		'Zend\Session',
	   
		'Zend\Mvc\Plugin\FlashMessenger',	
		'Zend\Router',
		'Zend\Validator',
		'Application',
		'Admin',
		'AuthAcl',
		
		'Zend\Mvc\Plugin\Prg',
		'Zend\Mvc\Plugin\Identity',
		'Zend\Mvc\Plugin\FilePrg',
		'Zend\Mvc\Console',
		'Zend\Log',
		'Zend\I18n',
	);