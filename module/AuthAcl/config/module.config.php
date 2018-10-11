<?php
namespace AuthAcl;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Regex;
use Zend\ServiceManager\Factory\InvokableFactory;

return array(
    'router' =>array(
        'routes' => array(
             'auth_acl' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/authacl[/:action]',
                    'defaults' => array(
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'login' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'login',						
                    ),
                ),
            ),
			'front_removeplan' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/removeplan[/:type]',
					/*'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',   
                    ),*/
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'removeplan',						
                    ),
                ),
            ),
			'front_removeresource' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/removeresource[/:type]',
					/*'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',   
                    ),*/
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'removeresource',						
                    ),
                ),
            ),
			
			'front_readnotify'=>array(
					 'type' => Segment::class,
					 'options' =>array(
                    'route' => '/readnotify',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'readnotify',						
                    ),
                ),
			),
			
			'getpie' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/getpie',
					
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'getpie',						
                    ),
                ),
            ),
			
			'getplan' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/getplan',
					
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'getplan',						
                    ),
                ),
            ),
			
			
			'getinvestamount' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/getinvestamount',
					
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'getinvestamount',						
                    ),
                ),
            ),
			
			'register' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/register[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',   
                    ),
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'register',						
                    ),
                ),
            ),
			
			'forgot_password' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/forgot-password',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'forgotpassword',						
                    ),
                ),
            ),
			
			'front_resetpassword' =>array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/user-resetpassword[/:key]',
					/*'constraints' =>[
                         'key'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ],*/
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',					
                        'controller' => Controller\IndexController::class,
                        'action'     => 'resetpassword',
                    ),
                ),
            ),
						
			'front_activate' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/activate[/:key]',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'activate',						
                    ),
                ),
            ),
			
			'front_checkforgotemail' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/checkforgotemail[/:user_email]',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'checkforgotemail',						
                    ),
                ),
            ),
			
			'logout' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'logout',						
                    ),
                ),
            ),
			
			'profile' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/profile',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'index',						
                    ),
                ),
            ),
			
			'front_dashboard' => array(
            	'type' => Segment::class,
            	'options' => array(
            		'route' => '/dashboard[/:page]',
            		'defaults' =>array(
						'__NAMESPACE__' => 'AuthAcl\Controller',
                		'controller' => Controller\ProfileController::class,
                		'action'     => 'dashboard',						
                	),
            	),
            ),
			
			'front_deposit' => array(
            	'type' => Literal::class,
            	'options' => array(
            		'route' => '/deposit',
            		'defaults' =>array(
						'__NAMESPACE__' => 'AuthAcl\Controller',
                		'controller'=> Controller\TransferController::class,
                		'action'	=> 'deposit',
                	),
            	),
            ),
			'front_confirmdeposit' => array(
            	'type' => Segment::class,
            	'options' => array(
            		'route' => '/confirmdeposit[/:deposit]',
            		'defaults' =>array(
						'__NAMESPACE__' => 'AuthAcl\Controller',
                		'controller'=> Controller\TransferController::class,
                		'action'	=> 'confirmdeposit',
                	),
            	),
            ),
			'front_portfolio' => array(
            	'type' => Segment::class,
            	'options' => array(
            		'route' => '/portfolio[/:page][/:transx]',
            		'defaults' =>array(
						'__NAMESPACE__' => 'AuthAcl\Controller',
                		'controller'=> Controller\TransferController::class,
                		'action'	=> 'portfolio',
                	),
            	),
            ),
			
			'front_pendingtransaction' => array(
            	'type' => Segment::class,
            	'options' => array(
            		'route' => '/pending-transaction[/:page]',
            		'defaults' =>array(
						'__NAMESPACE__' => 'AuthAcl\Controller',
                		'controller'=> Controller\TransferController::class,
                		'action'	=> 'pendingtransaction',
                	),
            	),
            ),
			
			
			'front_withdraw' => array(
            	'type' => Literal::class,
            	'options' => array(
            		'route' => '/withdraw',
            		'defaults' =>array(
						'__NAMESPACE__' => 'AuthAcl\Controller',
                		'controller'=> Controller\TransferController::class,
                		'action'	=> 'withdraw',
                	),
            	),
            ),
			
			
			
			'front_confirmwithdraw' => array(
            	'type' => Segment::class,
            	'options' => array(
            		'route' => '/withdrawdeposit[/:deposit]',
            		'defaults' =>array(
						'__NAMESPACE__' => 'AuthAcl\Controller',
                		'controller'=> Controller\TransferController::class,
                		'action'	=> 'withdrawdeposit',
                	),
            	),
            ),
			
			'front_notifications' => array(
                'type' => Segment::class,
                'options' =>array(
                    'route' => '/notifications[/:page]',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'notifications',						
                    ),
                ),
            ),
			
			'front_markread' => array(
                'type' => Segment::class,
                'options' =>array(
                    'route' => '/markread',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'markread',						
                    ),
                ),
            ),
			
			'deletenotificationajax' => array(
                'type' => Segment::class,
                'options' =>array(
                    'route' => '/deletenotificationajax',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'deletenotificationajax',						
                    ),
                ),
            ),
			
			'changepassword' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/change-password',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'changepassword',						
                    ),
                ),
            ),
			
			'checkemail' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/checkemail',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'checkemail',						
                    ),
                ),
            ),  
			
			'checkusername' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/checkusername',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'checkusername',						
                    ),
                ),
            ),   
			
			'checkpassword' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/checkpassword',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'checkpassword',						
                    ),
                ),
            ),    
			  
			'compareoldpassword' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/compareoldpassword',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'compareoldpassword',						
                    ),
                ),
            ),   
			
			'image' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/image',
                    'defaults' => array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'image',						
                    ),
                ),
            ),
			
			'front_image' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/change-avatar',
                    'defaults' => array(
        			 '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'cropimage',      
                    ),
                ),
            ),
			'front_facebook' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/facebook',
                    'defaults' => array(
        			 '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\SocialController::class,
                        'action'     => 'fblogin',      
                    ),
                ),
            ),
			'front_twitter' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/twitter[/:oauth_verifier]',
                    'defaults' => array(
        			 '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\SocialController::class,
                        'action'     => 'twitterlogin',      
                    ),
                ),
            ),
			'front_google' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/google',
                    'defaults' => array(
        			 '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\SocialController::class,
                        'action'     => 'googlelogin',      
                    ),
                ),
            ),
			'front_twitterhandler' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/twitterhandler',
                    'defaults' => array(
        			 '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\SocialController::class,
                        'action'     => 'twitterhandler',      
                    ),
                ),
            ),
			'linkdinlogin' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/linkdinlogin',
                    'defaults' => [
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\SocialController::class,
                        'action'     => 'linkdinlogin',						
                    ],
                ],
            ],
			
			'linkdinauth' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/linkdinauth',
                    'defaults' => [
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\SocialController::class,
                        'action'     => 'linkdinauth',						
                    ],
                ],
            ],
			
			'front_subcats' => array(
                'type' => Segment::class,
                'options' =>array(
                    'route' => '/subcats',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProjectController::class,
                        'action'     => 'subcats',						
                    ),
                ),
            ),
			'front_photopayment1' => array(
                'type' => Segment::class,
                'options' =>array(
                    'route' => '/photo-payment[/:uphoto_id][/:status]',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\PaymentController::class,
                        'action'     => 'photopayment',						
                    ),
                ),
            ),
			'front_photopayment2' => array(
                'type' => Segment::class,
                'options' =>array(
                    'route' => '/setphotopayment',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'setphotopayment',						
                    ),
                ),
            ),
			'front_portcontent' => array(
                'type' => Segment::class,
                'options' =>array(
                    'route' => '/getportfoliocontent',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'getportfoliocontent',						
                    ),
                ),
            ),
			
			'front_removeportcontent' => array(
                'type' => Segment::class,
                'options' =>array(
                    'route' => '/removeportcontent[/:num][/:index]',
                    'defaults' =>array(
					    '__NAMESPACE__' => 'AuthAcl\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'removeportcontent',						
                    ),
                ),
            ),
			'front_facebook_success' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/facebook-login[/:pagetype]',
                    'defaults' => array(
                        'controller' => Controller\SocialController::class,
                        'action'     => 'fblogin',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'front_getcities' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/getcities',
                    'defaults' => array(
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'getcities',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			
			'front_getphonecode' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/getphonecode',
                    'defaults' => array(
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'getphonecode',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			
			'front_checkcaptch' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/checkcaptch',
                    'defaults' => array(
                        'controller' => Controller\IndexController::class,
                        'action'     => 'checkcaptch',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'front_property' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/my-property',
                    'defaults' => array(
                        'controller' => Controller\PropertyController::class,
                        'action'     => 'index',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'front_addproperty' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/add-property',
                    'defaults' => array(
                        'controller' => Controller\PropertyController::class,
                        'action'     => 'addproperty',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'front_editproperty' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/edit-property',
                    'defaults' => array(
                        'controller' => Controller\PropertyController::class,
                        'action'     => 'editproperty',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'manage_jobs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/manage-jobs[/:page]',
                    'defaults' => array(
                        'controller' => Controller\JobController::class,
                        'action'     => 'index',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'post_job' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/post-job',
                    'defaults' => array(
                        'controller' => Controller\JobController::class,
                        'action'     => 'addjob',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'edit_job' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/edit-job[/:job_id]',
                    'defaults' => array(
                        'controller' => Controller\JobController::class,
                        'action'     => 'addjob',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'remove_job' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/removejob[/:job_id]',
                    'defaults' => array(
                        'controller' => Controller\JobController::class,
                        'action'     => 'removejob',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'front_downloadcontent' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/downloadcontent[/:name/:type]',
                    'defaults' => array(
                        'controller' => Controller\IndexController::class,
                        'action'     => 'downloadcontent',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'invited_jobs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/invited-jobs[/:page]',
                    'defaults' => array(
                        'controller' => Controller\JobController::class,
                        'action'     => 'invitedjobs',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			'remove_job_invitation' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/removejobinvitation[/:jinvite_id]',
                    'defaults' => array(
                        'controller' => Controller\JobController::class,
                        'action'     => 'removejobinvitation',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			
			'getproposalform' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/getproposalform',
                    'defaults' => array(
                        'controller' => Controller\JobController::class,
                        'action'     => 'getproposalform',
						'__NAMESPACE__' => 'AuthAcl\Controller',
                    ),
                ),
            ),
			
			'front_send_consult_request' => array(
            	'type' => Literal::class,
            	'options' => array(
            		'route' => '/send-consult-request',
            		'defaults' =>array(
						'__NAMESPACE__' => 'AuthAcl\Controller',
                		'controller' => Controller\ProfileController::class,
                		'action'     => 'sendconsultrequest',						
                	),
            	),
            ),
			
			'front_prepare_time_slots' => array(
            	'type' => Literal::class,
            	'options' => array(
            		'route' => '/prepare-time-slots',
            		'defaults' =>array(
						'__NAMESPACE__' => 'AuthAcl\Controller',
                		'controller' => Controller\ProfileController::class,
                		'action'     => 'preparetimeslots',						
                	),
            	),
            ),
			
        ),
    ),
	 'service_manager' => array(
        'factories' => array(
          	\Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
			
        ),
    ),
	'controllers' => array(
        'factories' => array(
			Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class, 
			Controller\ProfileController::class => Controller\Factory\ProfileControllerFactory::class ,	
			Controller\SocialController::class => Controller\Factory\SocialControllerFactory::class ,	
			Controller\TransferController::class => Controller\Factory\TransferControllerFactory::class ,	
        ),
    ),
	
	'controller_plugins' => array(
		'invokables' => array(
			'Image' => 'Application\Controller\Plugin\Image',
			'ImageCrop' => 'Application\Controller\Plugin\ImageCrop',
		)
	),
	
	'view_helpers' => array(
		  'invokables' =>array(
		   'renderForm' => 'Application\View\Helper\FrontRenderForm',   
		   'GetMessages' => 'Application\View\Helper\GetMessages',   
		  ),
	 ),
 
    'view_manager' => array(
		'template_map' => array(
			 'auth-acl/index/index' => __DIR__ . '/../view/auth-acl/index/index.phtml',
			 'auth-acl/profile' => __DIR__ . '/../view',
		),
		'template_path_stack' => array(
			 __DIR__ . '/../view'
		),
    ),
);
