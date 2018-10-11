<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;

class NavHelper extends AbstractHelper {

   public function __invoke() 
   {
       $return_nav=$this->getNavArray();
	   return $return_nav;
   }

   public function getNavArray(){
	  
  		 $pages = array (
		
			array(
				'label' => 'Dashboard',
				'icon' =>'fa fa-home',
				'module' => 'admin',
				'controller' => 'index',
 				'action' => 'dashboard',
				'uri' => '/dashboard',	
     		),
			
			array(
				'label' => 'Site Configurations',
				'icon' =>'fa fa-cog',
				'uri' => 'javascript:void(0)',
 				'pages' =>array(
					array(
						'label' => 'Site Configurations',
						'icon' =>'fa fa-cog',
						'module' => 'admin',
						'controller' => 'static',
						'action' => 'index',
						'route' => '/static',
					),
					
     			)
			),
			
			array(
				'label' => 'Manage Profile',
				'icon' =>'fa fa-user',
				'uri' => 'javascript:void(0)',				
 				'pages' =>array(
					array(
						'label'=>'Update Profile',
						'icon' =>'fa fa-edit',
						'module' => 'admin',
						'controller' => 'profile',
 						'action' => 'index',
						'route'=>'/adminprofile',
  					),
					array(
						'label'=>'Profile Image',
						'icon' =>'fa fa-file-image-o',
						'module' => 'admin',
						'controller' => 'profile',
 						'action' => 'image',
						'route'=>'/profile-image'
  					),
					array(
						'label'=>'Change Password',
						'icon' =>'fa fa-key',
						'module' => 'admin',
						'controller' => 'profile',
 						'action' => 'changepassword',
						'route'=>'/change-password'
  					),
   				)
     		),
			
			
			array(
				'label' => 'Static Content',
				'icon' =>'fa fa-file-text-o',
				'uri' => 'javascript:void(0)',
 				'pages' =>array(
					array(
						'label' => 'Manage Pages',
						'icon' =>'fa fa-file-text-o',
						'module' => 'admin',
						'controller' => 'static',
						'action' => 'pages',
						'route' => '/pages',
						'pages' =>array(
							array(
								'module' => 'admin',
								'controller' => 'static',
								'action' => 'editpages',
							),
							
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
					
									
     			)
			),
			
			
		);
		
		 return $pages;
	}
  

}
