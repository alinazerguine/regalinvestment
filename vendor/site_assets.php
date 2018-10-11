<?php 
global $_site_assets_front_admin , $_site_assets_path_front_admin; 

	$_site_assets_front_admin = array(
	'css' =>array(
		
		'Admin'=>array(
 			'guest'=>array(
				'css/base.css',
				'assets/css/style.css',
				'assets/css/theme-grey.css',
				'assets/css/responsive.css',
				'assets/css/common.css',
				'assets/css/bootstrap-extend.css',
				'assets/css/pages.css',
				'assets/css/plugins.css',
				'assets/css/widgets.css',
				'assets/css/components.css',
				'assets/css/layout.css',
				'assets/css/animate.css',
				'assets/css/bootstrap.css',
				'plugins/croppie/croppie.css',
				'plugins/croppie/demo/demo.css',
				'assets/css/font-awesome.css',
				'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
				'css/star-rating.min.css',
				'assets/css/video-js.css',
				
 			),
			'user'=>array(
				'css/base.css',
				'assets/css/style.css',
				'assets/css/theme-grey.css',
				'assets/css/responsive.css',
				'assets/css/common.css',
				'assets/css/bootstrap-extend.css',
				'assets/css/pages.css',
				'assets/css/plugins.css',
				'assets/css/widgets.css',
				'assets/css/components.css',
				'assets/css/layout.css',
				'assets/css/animate.css',
				'assets/css/bootstrap.css',
				'plugins/croppie/croppie.css',
				'plugins/croppie/demo/demo.css',
				'assets/css/font-awesome.css',
				'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
				'css/star-rating.min.css',
				'assets/css/video-js.css',
				
			),
		),
		
		'AuthAcl'=>array(
 			'guest'=>array(
				'css/design.css',
				'plugins/uniform/css/uniform.default.css',
				'plugins/font-awesome/css/font-awesome.min.css',
				'css/bootstrap.css',
			),
			'user'=>array(
				'css/design.css',
				'plugins/uniform/css/uniform.default.css',
				'plugins/bootstrap-fileinput/bootstrap-fileinput.css',
				'animate.min.css',
				'plugins/font-awesome/css/font-awesome.min.css',
				'css/bootstrap.css',
								
				"module"=>array(
					"ProfileController"=> array(
						"sendconsultrequest"=>array(
							'plugins/bootstrap-datetimepicker1/jquery.datetimepicker.css',
						),
						
					),
				)
			),
		),
		
		'Application'=>array(
			'guest'=>array(
				'plugins/bootstrap-datepicker/css/datepicker.css',
				'css/design.css',
				'css/flaticon.css',
				'plugins/uniform/css/uniform.default.css',
				'plugins/font-awesome/css/font-awesome.min.css',
				'css/bootstrap.css',	

				"module"=>array(
					"IndexController" => array(
						"index"=>array(
							'plugins/bootstrap-fileinput/bootstrap-fileinput.css',
						),
					),
				)
			),
			'user'=>array(
				'css/design.css',
				'css/flaticon.css',
				'plugins/uniform/css/uniform.default.css',
				'plugins/font-awesome/css/font-awesome.min.css',
				'css/bootstrap.css',
				
				
				
				
				
				
			),
		)
	),
	'js' =>array(
		'Admin'=>array(
 			'guest'=>array(
				
			),
			'user'=>array(
				
			),
		),
		
		'AuthAcl'=>array(
 			'guest'=>array(
				//'css/bootstrap-4/assets/js/vendor/popper.min.js',
				'js/bootstrap.min.js',
				'plugins/jquery-validation/js/jquery.validate.min.js',
				'plugins/jquery-validation/js/additional-methods.min.js',
				'js/jquery.nicescroll.js',
				'plugins/uniform/jquery.uniform.min.js',
				'plugins/blockui/jquery.blockui.min.js',
				'assets/js/bootstrap-notify.js',
				'js/sitepage.js',
				'js/general.js',
				
 
			),
			'user'=>array(
				//'css/bootstrap-4/assets/js/vendor/popper.min.js',
				'js/bootstrap.min.js',
				'plugins/jquery-validation/js/jquery.validate.min.js',
				'plugins/jquery-validation/js/additional-methods.min.js',
				
				'js/jquery.nicescroll.js',
				'plugins/ckeditor/ckeditor.js',
				'plugins/uniform/jquery.uniform.min.js',
				'plugins/bootstrap-fileinput/bootstrap-fileinput.js',
				'assets/js/bootstrap-notify.js',
				'plugins/bootstrap-fileinput/bootstrap-fileinput.js',
				'plugins/blockui/jquery.blockui.min.js',
				
				
				
				
				/*'module'	=>	array(
					"ProjectController"	=>	array(
						"index"		=>	array(
							'plugins/ckeditor/ckeditor.js',
						)
					)
				)*/
				"module"=>array(
					"ProfileController" => array(
						"sendconsultrequest"=>array(
							'plugins/bootstrap-datetimepicker1/jquery.datetimepicker.js',
						),
						"dashboard"=>array(
							'plugins/confirmation/bootstrap-confirmation.min.js',
						),
					),
				),
				
				'js/sitepage.js',
				'js/general.js',
			),
		),
		
		'Application'=>array(
 			'guest'=>array(
			
				'js/bootstrap.min.js',
				'plugins/jquery-validation/js/jquery.validate.min.js',
				'plugins/jquery-validation/js/additional-methods.min.js',
				'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
				'js/jquery.nicescroll.js',
				'assets/js/bootstrap-notify.js',
				'plugins/uniform/jquery.uniform.min.js',
				'plugins/blockui/jquery.blockui.min.js',
				'js/general.js',
				'js/sitepage.js',
				
				
			),
			'user'=>array(
				//'css/bootstrap-4/assets/js/vendor/popper.min.js',
				'js/bootstrap.min.js',
				'plugins/jquery-validation/js/jquery.validate.min.js',
				'plugins/jquery-validation/js/additional-methods.min.js',
				'js/jquery.nicescroll.js',
				'assets/js/bootstrap-notify.js',
				'plugins/uniform/jquery.uniform.min.js',
				'plugins/blockui/jquery.blockui.min.js',
				'js/sitepage.js',
				'js/general.js',
			),
		)
	),
);

$_site_assets_path_front_admin  = array(
	"css"=>array(
		'Admin'=>APPLICATION_URL."/public/",
		"AuthAcl"=>APPLICATION_URL."/public/",
		"Application"=>APPLICATION_URL."/public/",
	),
	"js"=>array(
		'Admin'=>APPLICATION_URL."/public/",
		"AuthAcl"=>APPLICATION_URL."/public/",
		"Application"=>APPLICATION_URL."/public/",
	),
);

 

//prd($_site_assets_path_front_admin);