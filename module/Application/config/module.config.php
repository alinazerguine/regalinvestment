<?php
namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Regex;
use Zend\ServiceManager\Factory\InvokableFactory;
use Application\Route\StaticRoute;

return [
    'router' => [
        'routes' => [
            
			'home' => [
                'type' => Segment::class,
                'options' => [
                	'route'    => '/',
                	'defaults' => [
                		'controller' => Controller\IndexController::class,
                		'action'     => 'index',
						'__NAMESPACE__' => 'Application\Controller',
                	],
                ],
            ],
			
			
			
			
			/*'home' => array(
				'type'    => Segment::class,
                'options' => array(                 
					'route'    => '/test[/:id]',
					'constraints' => array(
                     	'type'	=>	'[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'controller' => Controller\IndexController::class,
                        'action'     => 'index',
						'__NAMESPACE__' => 'Application\Controller',
                    ),
                ),
            ),*/
			
			
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],
					
			
			
			'errorpage' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/errorpage',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'errorpage',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			
			'front_resources' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/resources',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'resources',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			
			'front_faq' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/faq',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'faq',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			'front_getfaqs' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/getfaqs[/:num]',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'getfaqs',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			//
			'front_career'=>[
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/career[/:page]',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'career',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			'front_jobdetail'=>[
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/jobdetail[/:job]',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'jobdetail',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			
			
			'front_mission'=>[
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/our-mission',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'mission',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			
			'front_services'=>[
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/services',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'services',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			'front_blessings'=>[
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/blessings',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'blessings',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			
			
			'front_security'=>[
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/security',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'security',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			
			'front_overview' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/overview',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'overview',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			'front_privacy' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/privacy-policy',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'privacy',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			'front_terms' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/terms-policy',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'terms',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			
			'front_faq' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/faq',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'faq',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],	
			'front_contact' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/contact',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'contact',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],
			
			'front_pricing' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/pricing',
                    'defaults' => [
                        'controller' => Controller\StaticController::class,
                        'action'     => 'pricing',
						'__NAMESPACE__' => 'Application\Controller',
                    ],
                ],
            ],
			
			
			
        ],
    ],
	
	//Call ControllerFactory 
	'controllers' => [
        'factories' => [
			Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
			Controller\StaticController::class => Controller\Factory\StaticControllerFactory::class,
			
        ],
    ],
	
	//Call Plugin
	'controller_plugins' => array(
		'invokables' => array(			
			'Image' => 'Application\Controller\Plugin\Image',
			'ImageCrop' => 'Application\Controller\Plugin\ImageCrop',	
			'translator' => 'Application\Controller\Plugin\TranslatePlugin',		
		)
	),
	
	'view_helpers' => array(
		  'invokables' => array(   
		   'renderForm' => 'Application\View\Helper\RenderForm',   
		   'GetMessages' => 'Application\View\Helper\GetMessages',   
		  ),
	 ),
 
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_path_stack' => [
           __DIR__ . '/../view',
        ],
		'template_map' => [
			'layout/layout' 		  => __DIR__ . '/../view/layout/layout.phtml',
			'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
		],
    ],
];