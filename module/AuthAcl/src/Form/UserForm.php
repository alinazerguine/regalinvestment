<?php
namespace AuthAcl\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct($name = null)
    {
		//$name='user_form';
        parent::__construct($name);
        $this->setAttribute('method', 'post');
		$this->setAttribute('class', 'profile_form');
    }
	public function twitter_email(){
		
		$this->add(array(
            'name' => 'user_email',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_email',
                'class' => 'form-control checkemail required email',
				"required"=>"required",	
            ),
            'options' => array(
                  'label' => 'Email Address',	      
            )
        ));
		
		
		
		
		$this->add(array( //Button envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'bttnsubmit',	
			'id' => 'bttnsubmit',	
				
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn animatedbutton'
            ),
            'options'=> array(
                'label'=>'<span>Continue</span>',
            ),
        ));
		
		
		
		
	}
	
	public function login()
	{
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'profile_form');
		
		$this->add(array(
            'name' => 'user_email',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_email',
                'class' => 'form-control required',
            ),
            'options' => array(
                  'label' => 'Email Address',	      
            )
        ));
		
        
        $this->add(array(
            'name' => 'user_password',
            'type' => 'password',
            'attributes' => array(
			 	'id' => 'user_password',
                'class' => 'form-control required',
				"required"=>"required",	
            ),
            'options' => array(
				'label' => 'Password',	               
            )
        ));
		
		/* $this->add(array(
            'name' => 'remember_me',
            'type' => 'checkbox',
            'attributes' => array(
			 	'id' => 'remember_me',
            ),
            'options' => array(
				'label' => 'Remember me',	               
            )
        ));*/
		
		
		
		$this->add(array( //Button envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'bttnsubmit',	
			'id' => 'bttnsubmit',	
				
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn animatedbutton'
            ),
            'options'=> array(
                'label'=>'<span>Log In</span>',
            ),
        ));
		
	}
	public function user_prosperity(){
		$this->add(array(
            'name' => 'user_prosperity',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_prosperity',
                'class' => 'form-control required number',
				'required' => 'required',	
				'placeholder' => 'Prosperity',	
				'maxlength'=>10		
            ),
            'options' => array(
                'label' => 'Prosperity',	         
            )
        ));
		$this->add(array( //Button envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'bttnsubmit',	
			'id' => 'bttnsubmit',	
				
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn animatedbutton'
            ),
            'options'=> array(
                'label'=>'Submit',
            ),
        ));
	}

	public function user_register($all_investing_reason=array(),$all_investing_reason_option=array(),$all_investing_type=array())
	{
		$this->setAttribute('class', 'profile_form');
		$this->setAttribute('enctype', 'multipart/form-data');
		
		
		$this->add(array(
					'name' => "book_type",
					'type' => 'radio',
					'options' =>array(
						'label' => "Type",
						'value_options' => $all_investing_reason,
								'label_options' => array(
                						'disable_html_escape' => true,
           					 )
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'required',    
						/*'value'=>0*/
					 ),
		));	
		$this->add(array(
					'name' => "investing_reason",
					'type' => 'radio',
					'options' =>array(
						'label' => "Type",
						'value_options' => $all_investing_reason_option,
								'label_options' => array(
                						'disable_html_escape' => true,
           					 )
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'required',    
						'value'=>0
					 ),
		));	

		$this->add(array(
					'name' => "invest_opt",
					'type' => 'radio',
					'options' =>array(
						'label' => "Type",
						'value_options' => array("1"=>"Yes","0"=>"No"),
								'label_options' => array(
                						'disable_html_escape' => true,
           					 )
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'required',    
						'value'=>0
					 ),
		));	
		$this->add(array(
					'name' => "invest_type",
					'type' => 'radio',
					'options' =>array(
						'label' => "Type",
						'value_options' => $all_investing_type,
								'label_options' => array(
                						'disable_html_escape' => true,
           					 )
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'required',    
						'value'=>0
					 ),
		));	
		
		$this->add(array(
            'name' => 'invest_assets',
            'type' => 'text',
            'attributes' => array(
                'id' => 'invest_assets',
                'class' => 'form-control required number',
				'required' => 'required',	
				'placeholder' => 'Investable assets',			
            ),
            'options' => array(
                'label' => 'What are you investable assets?',	         
            )
        ));
		
		
		
		$this->add(array(
            'name' => 'user_inv_return',
            'type' => 'hidden',
            'attributes' => array(
                'id' => 'user_inv_return',
                'class' => 'form-control required',
				'required' => 'required',		
            ),
         ));
		$this->add(array(
            'name' => 'user_first_name',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_first_name',
                'class' => 'form-control required',
				'required' => 'required',
				'placeholder' => 'First Name',		
            ),
            'options' => array(
                'label' => 'First Name',	         
            )
        ));
		
		$this->add(array(
            'name' => 'user_last_name',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_last_name',
                'class' => 'form-control required',
				'required' => 'required',	
				'placeholder' => 'Last Name',	
            ),
            'options' => array(
                 'label' => 'Last Name',	             
            )
        ));
		/*$this->add(array(
            'name' => 'user_phoneno',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_phoneno',
                'class' => 'form-control phone_number required',
				'required' => 'required',
						
            ),
            'options' => array(
                'label' => 'Phone Number',	         
            )
        ));*/
		
		/*$this->add(array(
            'name' => 'user_business_name',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_business_name',
                'class' => 'form-control required',
				'required' => 'required',		
            ),
            'options' => array(
                 'label' => 'Business Name',	             
            )
        ));*/
	/*$this->add(array(
            'name' => 'user_business_website',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_business_website',
                'class' => 'form-control url',
					
            ),
            'options' => array(
                 'label' => 'Business Website',	             
            )
        ));*/
	
		
		
		$this->add(array(
            'name' => 'user_email',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_email',
                'class' => 'form-control checkemail',
				'placeholder'=>'Email',
            ),
            'options' => array(
                  'label' => 'Email Address',	      
            )
        ));
		
		$this->add(array(
            'name' => 'user_password',
            'type' => 'password',
            'attributes' => array(
                'id' => 'user_password',
                'class' => 'form-control required',
				'required' => 'required',
				'placeholder'=>'Password',
            ),
            'options' => array(
                   'label' => 'Password',	      
            )
        ));  
		$this->add(array(
            'name' => 'user_rpassword',
            'type' => 'password',
            'attributes' => array(
                'id' => 'confirm_password',
                'class' => 'form-control required',
                'required' => 'required',
				'placeholder'=>'Confirm Password',
            ),
            'options' => array(
                'label' => 'Confirm Password',
				
				                
            )
        ));    
		
		
		$this->add(array(
					'name' => "user_emp_status",
					'type' => 'radio',
					'options' =>array(
						'label' => "Type",
						'value_options' => array("0"=>"Currently Employed","1"=>"Not Employed"),
								'label_options' => array(
                						'disable_html_escape' => true,
           					 )
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'required',    
						/*'value'=>0*/
					 ),
		));	
	
		$this->add(array(
            'name' => 'user_location',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_location',
                'class' => 'form-control required',
				'required' => 'required',		
            ),
            'options' => array(
                'label' => 'Location',	         
            )
        ));
		$this->add(array(
            'name' => 'user_loc_latitude',
            'type' => 'hidden',
            'attributes' => array(
                'id' => 'user_loc_latitude',
                'class' => 'form-control required',
				'required' => 'required',		
            ),
         ));
		 
		 
		 $this->add(array(
            'name' => 'user_loc_longitude',
            'type' => 'hidden',
            'attributes' => array(
                'id' => 'user_loc_longitude',
                'class' => 'form-control required',
				'required' => 'required',		
            ),
         ));
		
		
		$this->add(array(
            'name' => 'user_annual_income',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_annual_income',
                'class' => 'form-control required number',
				'required' => 'required',	
				'placeholder' => 'Annual Income',			
            ),
            'options' => array(
                'label' => 'Annual Income',	         
            )
        ));
		
		
		
		$this->add(array( //Button envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'bttnsubmit',	
			'id' => 'bttnsubmit',		
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn SiteButton',
				'escape' =>false,
				'ignore'=>true,
				
            ),
            'options'=> array(
                'label'=>'<span>GET STARTED</span>',
				'label_options' => array(
                	'disable_html_escape' => true,
           	 )
				
            ),
        ));
	}
	
	public function image()
	{
		
		$this->add(array(
			'name' => 'user_image',
			'type' => 'file',
			"required"=>true,
			'options' =>array(
				'label' => "Profile Image",
			),
			"accept"=>"image/*",
			'attributes' =>array(       
				'class'  => 'default',  
				"id" => "user_image",  
				"accept"=>"image/*"
			 ),
		));	 
		
		$this->add(array( 
            'type'=>'Zend\Form\Element\Button',
            'name'=>'bttnsubmit',
			
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'SiteButton btn'
				
            ),
            'options'=> array(
                'label'=>'Submit',
            ),
        ));
	}
	
	
	public function forgotForm()
	{
		$this->add(array(
            'name' => 'user_email',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_email',
                'class' => 'form-control email required',
				'required' => 'required',
            ),
            'options' => array(
				 'label'=>'Email Address',             
            )
        ));
				
		$this->add(array( //Boton envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'submit',
			
            'attributes'=> array(
                'value'=>'Enviar',
				'type'=>'submit',
				'class' => 'submit_btn'
				
            ),
            'options'=> array(
                'label'=>'<span>Submit</span>',
            ),
        ));
	}
	
	
	public function resetpassword()
	{
		
		$this->add(array(
            'name' => 'user_password',
            'type' => 'password',
            'attributes' => array(
                'id' => 'user_password',
                'class' => 'form-control required',
				'required' =>true,
            ),
            'options' => array(
				 'label'=>'Enter Password',               
            )
        ));
		
		$this->add(array(
            'name' => 'user_rpassword',
            'type' => 'password',
            'attributes' => array(
                'id' => 'user_rpassword',
                'class' => 'form-control required',
				'required' =>true,
            ),
            'options' => array(
				'label'=>'Confirm New Password',                    
            )
        ));
		
		$this->add(array( //Boton envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'submit',
			
            'attributes'=> array(
                'value'=>'Enviar',
				'type'=>'submit',
				'class' => 'site-btn'
            ),
            'options'=> array(
                'label'=>'Reset Password',
            ),
        ));
	}
}