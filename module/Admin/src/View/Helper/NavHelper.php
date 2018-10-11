<?php
namespace Admin\View\Helper;
use Zend\Session\Container;
use Zend\View\Helper\AbstractHelper;

class NavHelper extends AbstractHelper {
	public function __invoke() 
	{
		$return_nav=$this->getNavArray();
		return $return_nav;
	}
	
	public function getNavArray(){
		
		$session = new Container(ADMIN_AUTH_NAMESPACE);
		$adminData=$session['adminData'];
		$session['perData']=$adminData['perData'];
		
		
			$pages = array (
			/* Dashboard */
			array(
				'label' => 'Dashboard',
				'icon' =>'fa fa-home',
				'module' => 'admin',
				'controller' => 'index',
				'action' => 'dashboard',
				'uri' => '/dashboard',	
			),
			
			/*array(
				'label'		=>	'Site Configurations',
				'icon'		=>	'fa fa-cog',
				'module'	=>	'admin',
				'controller'=>	'static',
				'action'	=>	'index',
				'uri'		=>	'/static/config',
			),*/
			
			array(
				'label'		=>	'Site Configurations',
				'icon'		=>	'fa fa-cog',
				'uri'		=>	'/static/config',
				'pages'		=>	array(
					array(
						'label'=>'Site',
						'icon' =>'fa fa-cog',
						'module' => 'admin',
						'controller' => 'static',
 						'action' => 'index',
						'route'=>'/static/config',
  					),
					
				)
			),
		array(
				'label' => 'Static Content',
				'icon' =>'fa fa-file-text-o',
				//'uri' => '/dashboard',
				'uri' => 'javascript:void(0)',
				'route' => 'default',
 				'pages' =>array(
				/*	array(
						'label' => 'Home page content',
						'icon' =>'fa fa-file-text-o',
						'module' => 'admin',
						'controller' => 'static',
						'action' => 'homepage',
						'route' => '/homepage',
					),	*/
					
					array(
						'label' => 'Pages',
						'icon' =>'fa fa-file-text-o',
						'module' => 'admin',
						'controller' => 'static',
						'action' => 'pages',
						'route' => '/pages',
						'pages' =>array(
							
						),
					),
					array(
						'label' => 'Instruction',
						'icon' =>'fa fa-code',
						'module' => 'admin',
						'controller' => 'static',
						'action' => 'instruction',
						'route' => '/instruction',
						'pages' =>array(
							
							
						),
					),
				

					array(
						'label' => 'Email Templates',
						'icon' =>'fa fa-envelope',
						'module' => 'admin',
						'controller' => 'static',
						'action' => 'emailtemplate',
						'route' => '/emailtemplate',
						'pages' =>array(
							array(
								'module' => 'admin',
								'controller' => 'static',
								'action' => 'editemailtemplate',
							),
						),
					),	
					
					
					array(
						'label' => 'FAQ',
						'icon' =>'fa fa-question-circle',
						'module' => 'admin',
						'controller' => 'static',
						'action' => 'faqs',
						'route' => '/faqs',
						'pages' =>array(
							array(
								'module' => 'admin',
								'controller' => 'static',
								'action' => 'managefaqs',
							),
							
						),
					),
     			)
			),	
			array(
				'label' => 'Resources',
				'icon' =>'fa fa-file',
				'uri' => '/resources',
				'module' => 'admin',
				'route'=>'/resources',
				'controller' => 'static',
				'action' => 'resources',
				
			),
			
			array(
				'label' => 'Percentage Values',
				'icon' =>'fa fa-percent',
				'uri' => '/qpercentage',
				'module' => 'admin',
				'route'=>'/qpercentage',
				'controller' => 'investment',
				'action' => 'qpercentage',
			),
			
			array(
						'label' => 'Investment',
						'icon' =>'fa  fa-paper-plane',
						
						'uri' => '/plans',
						'pages' =>array(
							array(
								'label'=>'Plans',
								'icon' =>'fa  fa-paper-plane',
								'module' => 'admin',
								'controller' => 'investment',
								'action' => 'plan',
								'route'=>'/plans',
								'pages' =>array(
							array(
								'module' => 'admin',
								'controller' => 'investment',
								'action' => 'manageplan',
							),
							
						),
					),
							
							array(
								'label'=>'Logic',
								'icon' =>'fa fa-puzzle-piece',
								'module' => 'admin',
								'controller' => 'investment',
								'action' => 'logic',
								'route'=>'/logic',
							
					),
						),
			),	
			
			array(
				'label' => 'Users',
				'icon' =>'fa fa-users',
				'uri' => '/users',	
 				'pages' =>array(
					array(
						'label'=>'View all Users',
						'icon' =>'fa fa-edit',
						'module' => 'admin',
						'route'=>'/users',
						'controller' => 'user',
						'action' => 'users',
						'pages' =>array(
							array(
								'label'=>'View Page',
								'icon' =>'fa fa-question',
								'module' => 'admin',
								'controller' => 'user',
								'action' => 'account',
							)
						),
  					),
					array(
						'label'=>'View Request',
						'icon' =>'fa fa-handshake-o',
						'module' => 'admin',
						'route'=>'/imprequest',
						'controller' => 'user',
						'action' => 'imprequest',
						
  					),
					array(
						'label'=>'Balance',
						'icon' =>'fa fa-money',
						'module' => 'admin',
						'route'=>'/balance',
						'controller' => 'user',
						'action' => 'balance',
						
  					),
   				)
     		),
			
			array(
				'label' => 'Consulting Request',
				'icon' =>'fa fa-puzzle-piece',
				'uri' => '/users',	
 				'pages' =>array(
					array(
						'label'=>'Manage Time Avaiability',
						'icon' =>'fa fa-clock-o',
						'module' => 'admin',
						'route'=>'/cons-availabletime-setting',
						'controller' => 'consultingrequest',
						'action' => 'consavailabletimesetting',
						
  					),
					array(
						'label'=>'View Consulting Requests',
						'icon' =>'fa fa-eye',
						'module' => 'admin',
						'route'=>'/view-consulting-request',
						'controller' => 'consultingrequest',
						'action' => 'viewconsultingrequest',
						
  					),
   				)
     		),
			
			array(
				'label' => 'Manage Jobs',
				'icon' =>'fa fa-tasks',
				'uri' => 'javascript:void(0)',	
 				'pages' =>array(
					array(
						'label' => 'Jobs',
						'icon' =>'fa fa-tasks',
						'module' => 'admin',
						'controller' => 'static',
						'action' => 'jobs',
						'route' => '/jobs',
						'pages' =>array(
							array(
								'module' => 'admin',
								'controller' => 'static',
								'action' => 'managejobs',
							),
							
						),
					),
					array(
						'label' => 'Application',
						'icon' =>'fa fa-tasks',
						'module' => 'admin',
						'controller' => 'static',
						'action' => 'jobapply',
						'route' => '/jobapply',
						
					),
					
   				)
     		),
			
			/*array(
				'label' => 'Withdrawal',
				'icon' =>'fa fa-money',
				'uri'=>'/withdrawal',
				'module' => 'admin',
				'route'=>'/withdrawal',
				'controller' => 'investment',
				'action' => 'withdrawal',
     		),
			*/
			array(
						'label' => 'Transaction',
						'icon' =>'fa fa-money',
						'uri' => '/withdrawal',
						'pages' =>array(
							array(
								'label'=>'Withdrawal',
								'icon' =>'fa fa-money',
								'module' => 'admin',
								'controller' => 'investment',
								'action' => 'withdrawal',
								'route'=>'/withdrawal',
							),
							
							array(
								'label'=>'Deposit',
								'icon' =>'fa fa-money',
								'module' => 'admin',
								'controller' => 'investment',
								'action' => 'deposit',
								'route'=>'/deposit',
							
							),
						),
			),	
		);
		return $pages;
	}
}