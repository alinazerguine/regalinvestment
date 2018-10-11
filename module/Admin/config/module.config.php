<?php
namespace Admin;
use Zend\Router\Http\Segment;

return array(
    // The following section is new and should be added to your file:
    'router' => array(
        'routes' => array(
			'adminlogin' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND,
                    'defaults' => array(
						'module' => BACKEND,
                    	'controller' => Controller\IndexController::class,
                    	'action'     => 'login',
						'__NAMESPACE__' => 'Admin\Controller',
            		),
            	),
            ),
			'admin_readnotify'=>array(
					 'type' => Segment::class,
					 'options' =>array(
                    'route' => '/'.BACKEND.'/readnotify',
                    'defaults' =>array(
						'module' => BACKEND,
					    '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'readnotify',						
                    ),
                ),
			),
			'admin_removeplans' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/removeplans',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'removeplans',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			'admin_approverequest' => array(
                'type'    => Segment::class,
                'options' => array(                   
					 'route'    => '/'.BACKEND.'/approve-request[/:res]',
					/*'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),*/
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'approverequest',
						'__NAMESPACE__' => 'Admin\Controller',
            		),
            	),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_rejectrequest' => array(
                'type'    => Segment::class,
                'options' => array(                   
					 'route'    => '/'.BACKEND.'/reject-request[/:res]',
					/*'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),*/
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'rejectrequest',
						'__NAMESPACE__' => 'Admin\Controller',
            		),
            	),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_approveplan' => array(
                'type'    => Segment::class,
                'options' => array(                   
					 'route'    => '/'.BACKEND.'/approve-plan[/:plan]',
					/*'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),*/
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'approveplan',
						'__NAMESPACE__' => 'Admin\Controller',
            		),
            	),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_rejectplan' => array(
                'type'    => Segment::class,
                'options' => array(                   
					 'route'    => '/'.BACKEND.'/reject-plan[/:plan]',
					/*'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),*/
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'rejectplan',
						'__NAMESPACE__' => 'Admin\Controller',
            		),
            	),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_uploadimage' => array(
                'type'    => Segment::class,
                'options' => array(                   
					 'route'    => '/'.BACKEND.'/uploadimage[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'uploadimage',
						'__NAMESPACE__' => 'Admin\Controller',
            		),
            	),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_removeresource' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/removeresource',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'removeresource',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			'static' => array(
                'type'    => Segment::class,
                'options' => array(                   
					 'route'    => '/'.BACKEND.'/static[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'index',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			'admin_resources' => array(
                'type'    => Segment::class,
                'options' => array(                   
					 'route'    => '/'.BACKEND.'/resources[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'resources',
						'__NAMESPACE__' => 'Admin\Controller',
            		),
            	),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_getresources' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/getresources',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'getresources',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
				
			'admin_manageresource' => array(
                'type'    => Segment::class,
                'options' => array(                   
					 'route'    => '/'.BACKEND.'/manageresource[/:res]',
					
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'manageresource',
						'__NAMESPACE__' => 'Admin\Controller',
            		),
            	),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			
			'dashboard' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/dashboard',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\IndexController::class,
                        'action'     => 'dashboard',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'forgotpassword' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/forgot-password',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\IndexController::class,
                        'action'     => 'forgotpassword',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'resetpassword' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/reset-password[/:key]',
					'constraints' => array(
                         'emailtemp_key'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\IndexController::class,
                        'action'     => 'resetpassword',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'adminlogout' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/admin-logout',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\IndexController::class,
                        'action'     => 'logout',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'adminprofile' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/adminprofile',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'index',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'emailtemplate' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/emailtemplate',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'emailtemplate',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			/* Q1 percentage value*/
			'admin_qpercentage' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/qpercentage',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'qpercentage',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			/* End Q1 percentage value*/
			/* plan start */
			'admin_plans' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/plans',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'plan',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_getplans' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/getplans',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'getplans',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_manageplans' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/manageplan[/:plan]',
					'constraints' => array(
                         'plan_key'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'manageplan',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			/* plan end */
			/* logic start */
			'admin_logic' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/logic',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'logic',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			/* withdrawal */
			
			'admin_approvetransaction'=> array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/approvetransaction[/:transx]',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'approvetransaction',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'admin_withdrawal' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/withdrawal[/:transx]',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'withdrawal',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_deposit' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/deposit[/:transx]',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'deposit',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			
			
			'admin_getwithdrawal' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/getwithdrawal[/:txntype][/:transx]',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\InvestmentController::class,
                        'action'     => 'getwithdrawal',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			
			/* faqs start */
			
			'admin_faqs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/faqs',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'faqs',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'admin_getfaqs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/getfaqs',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'getfaqs',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'admin_managefaqs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/managefaqs[/:faq]',
					'constraints' => array(
                         'emailtemp_key'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'managefaqs',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_removefaqs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/removefaqs',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'removefaqs',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'admin_orderfaqs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/orderfaqs',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'orderfaqs',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			/* faqs end */
			
			'emailtemplate' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/emailtemplate',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'emailtemplate',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'getemailtemplate' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/getemailtemplate',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'getemailtemplate',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'editemailtemplate' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/editemailtemplate[/:emailtemp_key][/:emailtemp_langid]',
					'constraints' => array(
                         'emailtemp_key'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'editemailtemplate',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			
			
			//DC293A-6072SFB31
			
			'adminpassword' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/change-password',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'changepassword',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'adminimage' => array(
            	'type'    => Segment::class,
            	'options' => array(
            		'route'    => '/'.BACKEND.'/profile-image',
                	'defaults' => array(
						'module' => BACKEND,
                    	'controller' => Controller\ProfileController::class,
                    	'action'     => 'image',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'user/usercheckemail' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/user/usercheckemail[/:user_id]',
					'constraints' => array(
                         'user_id'     => '[0-9]*',                   
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'usercheckemail',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
		
			'profile/checkemail' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/profile/checkemail',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'checkemail',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'profile/checkusername' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/profile/checkusername',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'checkusername',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'checkotherusername' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/checkotherusername',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'checkotherusername',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'checkotheremail' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/checkotheremail',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'checkotheremail',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'profile/checkpassword' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/profile/checkpassword',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'checkpassword',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'profile/checkoldpass' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/profile/checkoldpass',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'checkoldpass',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'addpages' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/addpages',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'addpages',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'instruction' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/instruction',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'instruction',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'pages' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/pages',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'pages',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'getpages' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/getpages[/:page]',
					'constraints' => array(
                         'page'     => '[0-9]+',                   
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'getpages',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'editpages' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/editpages[/:id]',
					'constraints' => array(
                         'id'     => '[0-9]+',                   
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'editpages',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'removepages' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/removepages',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'removepages',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			
			
			
			
			'ajaxsetstatus' => array(
                'type'    => 'segment',
                 'options' => array(
                     'route'    =>  '/'.BACKEND.'/ajaxsetstatus[/:type][/:id][/:status]',
                     'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',   
						 'id'     => '[0-9]+',   
						 'status'     => '[0-9]+',                   
                     ),
                     'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => Controller\AjaxController::class,
                        'action' => 'setstatus'
                     ),
                 ),
             ),
			
			'planrequest' => array(
                'type'    => Segment::class,
                'options' => array(
					'route'	=> '/'.BACKEND.'/planrequest[/:user_id]',
					'constraints' => array(
						  'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                
					),
					'defaults' => array(
						'module'		=> BACKEND,
						'controller'	=> Controller\UserController::class,
						'action'		=> 'planrequest',
						'__NAMESPACE__'	=> 'Admin\Controller',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'resourcerequest' => array(
                'type'    => Segment::class,
                'options' => array(
					'route'	=> '/'.BACKEND.'/resourcerequest[/:user_id]',
					'constraints' => array(
						  'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                
					),
					'defaults' => array(
						'module'		=> BACKEND,
						'controller'	=> Controller\UserController::class,
						'action'		=> 'resourcerequest',
						'__NAMESPACE__'	=> 'Admin\Controller',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'account' => array(
                'type'    => Segment::class,
                'options' => array(
					'route'	=> '/'.BACKEND.'/account[/:user_id]',
					'constraints' => array(
						  'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                
					),
					'defaults' => array(
						'module'		=> BACKEND,
						'controller'	=> Controller\UserController::class,
						'action'		=> 'account',
						'__NAMESPACE__'	=> 'Admin\Controller',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'admin_managebalance' => array(
                'type'    => Segment::class,
                'options' => array(
					'route'	=> '/'.BACKEND.'/manage-balance[/:user_id]',
					'constraints' => array(
						'user_id'	=> '[0-9]+',                   
					),
					'defaults' => array(
						'module'		=> BACKEND,
						'controller'	=> Controller\UserController::class,
						'action'		=> 'managebalance',
						'__NAMESPACE__'	=> 'Admin\Controller',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			
			
			
			'admin_balance' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/balance[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'balance',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'getbalance' => array(
                'type'    => Segment::class,
                'options' => array(
                   
                     'route'    => '/'.BACKEND.'/getbalance[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
					'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'getbalance',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			
			
			
			'admin_imprequest' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/imprequest[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'imprequest',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
				
            ),
			'getimprequest' => array(
                'type'    => Segment::class,
                'options' => array(
                   
                     'route'    => '/'.BACKEND.'/getimprequest[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
					'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'getimprequest',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			
			'admin_users' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/users[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'users',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'getusers' => array(
                'type'    => Segment::class,
                'options' => array(
                   
                     'route'    => '/'.BACKEND.'/getusers[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
					'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'getusers',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			'removeusers' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/removeusers[/:type]',
					'constraints' => array(
                         'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\UserController::class,
                        'action'     => 'removeusers',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			'remove' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/remove[/:recruiter_id]',
					 'constraints' => array(
									 'recruiter_id'     => '[0-9]+',                   
								 ),
								'defaults' => array(
						'module' => BACKEND,
									'controller' => Controller\UserController::class,
									'action'     => 'remove',
					   '__NAMESPACE__' => 'Admin\Controller',
								),
							),
            ),
			
			'admin_errorpage' => array(
                'type'    => Segment::class,
                'options' => array(                   
					'route'    => '/'.BACKEND.'/errorpage',					
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\IndexController::class,
                        'action'     => 'errorpage',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),	
			
			/* Cons Request Time Setting Route */
			'admin_cons_available_time' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/cons-availabletime-setting',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ConsultingrequestController::class,
                        'action'     => 'consavailabletimesetting',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'admin_view_consulting_request' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/view-consulting-request[/:consult]',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ConsultingrequestController::class,
                        'action'     => 'viewconsultingrequest',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'admin_getconsultingrequest' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/get-consulting-request[/:consult]',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\ConsultingrequestController::class,
                        'action'     => 'getconsultingrequest',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			/* JObs start */
			
			
			'admin_jobapply' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/jobapply[/:job][/:apply]',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'jobapply',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
				'admin_getjobapply' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/getjobapply[/:job][/:apply]',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'getjobapply',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_removejobapply' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/removejobapply',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'removejobapply',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			'admin_jobs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/jobs',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'jobs',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'admin_getjobs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/getjobs',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'getjobs',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			
			'admin_managejobs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/managejobs[/:job]',
					'constraints' => array(
                         'emailtemp_key'     => '[a-zA-Z][a-zA-Z0-9_-]*',                  
                     ),
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'managejobs',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
				'may_terminate' => true,
				'child_routes'  => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' =>
							'/[:controller[/:action]]',
							'constraints' => array(
								'controller' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
								'action' =>
								'[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
            ),
			'admin_removejobs' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/'.BACKEND.'/removejobs',
                    'defaults' => array(
						'module' => BACKEND,
                        'controller' => Controller\StaticController::class,
                        'action'     => 'removejobs',
						'__NAMESPACE__' => 'Admin\Controller',
                    ),
                ),
            ),
			
			
        ),
    ),
	 	
	'view_manager' => array(
		'template_path_stack' => array(
			'admin' => __DIR__ . '/../view',
		),
	),
	
	'controller_plugins' => [
		'invokables' => [
			'Image' => 'Application\Controller\Plugin\Image',
			'ImageCrop' => 'Application\Controller\Plugin\ImageCrop',
			'UploadHandler' => 'Application\Controller\Plugin\UploadHandler',
			
		]
	],
	
	'view_helpers' => array(
		'invokables' => array(	
			'NavHelper' => 'Admin\View\Helper\NavHelper',
			 
		),
	),
	
	'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class   ,
			Controller\StaticController::class => Controller\Factory\StaticControllerFactory::class ,   
			Controller\ProfileController::class => Controller\Factory\ProfileControllerFactory::class,
			Controller\AjaxController::class => Controller\Factory\AjaxControllerFactory::class ,
			Controller\InvestmentController::class => Controller\Factory\InvestmentControllerFactory::class ,
			Controller\UserController::class => Controller\Factory\UserControllerFactory::class ,
			Controller\ConsultingrequestController::class => Controller\Factory\ConsultingrequestControllerFactory::class ,
        ],
    ],
);



