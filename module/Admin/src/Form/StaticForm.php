<?php
namespace Admin\Form;
use Zend\Form\Form;

use Zend\Db\Sql\Sql;
use Zend\Form\Element;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;

class StaticForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('admin');

		$this->setAttribute('class','profile_form');
		
    }
	public function home_videos(){
		$this->add(array(
            'name' => 'home_banner_image',
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Banner Image&nbsp;<b>(Valid Extensions : ".IMAGE_VALID_EXTENTIONS.")</b>",
            ),
			"accept"=>"image/*",
			'attributes' =>array(        // Array of attributes
				 
				'id'=>'home_banner_image',  
			 ),
        ));	
		
		$this->add(array(
            'name' => 'business_support_image',
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Business Support Image&nbsp;<b>(Valid Extensions : ".IMAGE_VALID_EXTENTIONS.")</b>",
            ),
			'attributes' =>array(        // Array of attributes
				  
				'id'=>'business_support_image',  
			 ),
        ));	
		
		
		$this->add(array(
            'name' => 'business_support_video',
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Business Support Video&nbsp;<b>(Valid Extensions : ".VIDEO_VALID_EXTENTIONS.")</b>",
            ),
			'attributes' =>array(        // Array of attributes
				
				'id'=>'business_support_video',  
			 ),
        ));	
		
		$this->add(array(
            'name' => 'business_strategy_image',
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Business Strategy Image&nbsp;<b>(Valid Extensions : ".IMAGE_VALID_EXTENTIONS.")</b>",
            ),
			'attributes' =>array(        // Array of attributes
				
				'id'=>'business_strategy_image',  
			 ),
        ));	
		
		$this->add(array(
            'name' => 'business_strategy_video',
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Business Strategy Video&nbsp;<b>(Valid Extensions : ".VIDEO_VALID_EXTENTIONS.")</b>",
            ),
			'attributes' =>array(        // Array of attributes
				
				'id'=>'business_strategy_video',  
			 ),
        ));	
		
		$this->add(array(
            'name' => 'company_professional_image',
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Company of Professionals Image&nbsp;<b>(Valid Extensions : ".IMAGE_VALID_EXTENTIONS.")</b>",
            ),
			'attributes' =>array(        // Array of attributes
				 
				'id'=>'company_professional_image',  
			 ),
        ));	
		
		$this->add(array(
            'name' => 'company_professional_video',
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Company of Professionals Video&nbsp;<b>(Valid Extensions : ".VIDEO_VALID_EXTENTIONS.")</b>",
            ),
			'attributes' =>array(        // Array of attributes
				  
				'id'=>'company_professional_video',  
			 ),
        ));	
		
		$this->add(array(
            'name' => 'client_video_image',
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Clients Video Thumb&nbsp;<b>(Valid Extensions : ".IMAGE_VALID_EXTENTIONS.")</b>",
            ),
			'attributes' =>array(        // Array of attributes
			 
				'id'=>'client_video_image',  
			 ),
        ));	
		$this->add(array(
            'name' => 'client_video',
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Clients Video&nbsp;<b>(Valid Extensions : ".VIDEO_VALID_EXTENTIONS.")</b>",
            ),
			'attributes' =>array(        // Array of attributes
				  
				'id'=>'client_video',  
			 ),
        ));	
		
	}
	
	public function faqs()
	{
		 $inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		$this->add(array(
			'name' => 'faq_question',
			'type' => 'textarea',
			"required"=>true,
			'options' =>array(
				'label' =>'Question',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => 'Question', 
				"rows"=>"6",  
			 ),
		));	
		
		$this->add(array(
			'name' => 'faq_answer',
			'type' => 'textarea',
			"required"=>true,
			'options' =>array(
				'label' =>'Answer',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => 'Answer', 
				"rows"=>"6",  
			 ),
		));
		$this->getValidate($inputFilter,array("faq_question"=>"NotEmpty","faq_answer"=>"NotEmpty"));
		$this->submit();
	}
	public function resource()
	{
		 $inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		$this->add(array(
			'name' => 'res_title',
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				'label' =>'Title',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => 'Title', 
				 
			 ),
		));	
		
		$this->add(array(
			'name' => 'res_desc',
			'type' => 'textarea',
			"required"=>true,
			'options' =>array(
				'label' =>'Description',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required ckeditor',    
				"placeholder" => 'Description', 
				"rows"=>"6",  
			 ),
		));
		$this->getValidate($inputFilter,array("res_title"=>"NotEmpty","res_desc"=>"NotEmpty"));
		$this->submit();
	}
	public function jobs()
	{
		 $inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		$this->add(array(
			'name' => 'job_title',
			'type' => 'textarea',
			"required"=>true,
			'options' =>array(
				'label' =>'Job Title',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => 'Job Title', 
				"rows"=>"6",  
			 ),
		));	
		
		$this->add(array(
			'name' => 'job_desc',
			'type' => 'textarea',
			"required"=>true,
			'options' =>array(
				'label' =>'Job Description',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required ckeditor',    
				"placeholder" => 'Description', 
				"rows"=>"6",  
			 ),
		));
		$this->getValidate($inputFilter,array("job_title"=>"NotEmpty","job_desc"=>"NotEmpty"));
		$this->submit();
	}
	public function pages($page_id)
	{
		$this->add(array(
			'name' => 'page_title',
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				'label' =>'Page Title',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => 'Page Title', 
				"autocomplete" =>"off",    
			 ),
		));
			
		$this->add(array(
			'name' => 'page_content',
			'type' => 'textarea',
			"required"=>true,
			'options' =>array(
				'label' =>'Page Content',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required ckeditor',    
				"placeholder" => 'Page Title', 
				"autocomplete" =>"off",  
				"rows"=>"6",  
			 ),
		));	
		
		if($page_id=='9'){
			$this->home_videos();	
		}
	
		$this->add(array(
			'name' => 'bttnsubmit',
			'type' => 'submit',
			"ignore"=>true,
			'options' =>array(
				'label' =>'Submit',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'btn btn-info btn-fill pull-right',    
			 ),
		));	
		
	}
	public function emailtemplates()
	{
		$this->add(array(
			'name' => 'emailtemp_title',
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				'label' =>'Email Title',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => 'Email Title', 
				"autocomplete" =>"off",  
			 ),
		));	
		
		$this->add(array(
			'name' => 'emailtemp_subject',
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				'label' =>'Email Subject',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => 'Email Subject', 
				"autocomplete" =>"off",  
			 ),
		));	
		
		$this->add(array(
			'name' => 'emailtemp_content',
			'type' => 'textarea',
			"required"=>true,
			'options' =>array(
				'label' =>'Email Content',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control  required ckeditor',    
				"placeholder" => 'Email Content', 
				"rows" =>"6",  
			 ),
		));	
		
		$this->add(array(
			'name' => 'bttnsubmit',
			'type' => 'submit',
			"ignore"=>true,
			'options' =>array(
				'label' =>'Submit',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'btn btn-info btn-fill pull-right',   
				'style' =>'margin-top:20px',
			 ),
		));	
	}
	public function roleform(){
		 $inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		 /* slider title */
		$this->add(array("name"=>"rol_title","type"=>"text","options"=>array("label"=>"Title"),"attributes"=>array("class"=>"form-control required",'required' => 'required',)));
		$this->getValidate($inputFilter,array("rol_title"=>"NotEmpty"));
		$this->submit();
	}
	public function labelform(){
		 $inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		 /* slider title */
		$this->add(array("name"=>"lab_name","type"=>"text","options"=>array("label"=>"Title"),"attributes"=>array("class"=>"form-control required",'required' => 'required',)));
		$this->getValidate($inputFilter,array("lab_name"=>"NotEmpty"));
		$this->submit();
	}
	public function languagelist($language_array=array(),$defaultIds='language'){
		 $inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		$array_List=array(""=>"Select Language");
		 if(!empty($language_array)){
			$array_List=array(""=>"Select Language")+$language_array;	 
		}
		$this->add(array(
            'name' => $defaultIds,
            'type' => 'select',
			"required"=>true,
            'options' =>array(
				"value_options" => $array_List,    
				'label' => "Language",    
            ),
			'attributes' =>array(     
				'class'  => 'form-control required', 
				'id' => $defaultIds, 
			 )
        ));
		
	}
	
	public function localizationform($localized_array=array(),$languageData=array()){
		 $inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		 $array_List=array(""=>"Select Label");
		 if(!empty($localized_array)){
			$array_List=array(""=>"Select Label")+$localized_array;	 
		}
		 //prd($localized_array);
		 $this->add(array(
            'name' => 'local_labelid',
            'type' => 'select',
			"required"=>true,
            'options' =>array(
				"value_options" => $array_List,    
				'label' => "Label",    
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required', 
				'id' => 'local_labelid', 
			 )
        ));
		
		if(!empty($languageData)){
			foreach($languageData as $language){ //"name"=>$language['lang_id']."[]",
			$this->add(array('id'=>'lang'.$language['lang_id'],"name"=>"lang[".$language['lang_id']."]","type"=>"text","options"=>array("label"=>$language['lang_name']),"attributes"=>array("class"=>"form-control",'placeholder'=>$language['lang_name'])));
			
		
				}
		}
		$this->submit(); 
	}
	
	
	public function languageform(){
		 $inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		 /* slider title */
		$this->add(array("name"=>"lang_name","type"=>"text","options"=>array("label"=>"Language Title"),"attributes"=>array("class"=>"form-control required",'required' => 'required')));
		$this->add(array("name"=>"lang_code","type"=>"text","options"=>array("label"=>"Language Code"),"attributes"=>array("class"=>"form-control required",'required' => 'required')));
		$this->getValidate($inputFilter,array("lang_name"=>"NotEmpty","lang_code"=>"NotEmpty"));
		$this->submit();
	}
	public function submit(){
		
		
		$this->add(array(
			'name' => 'bttnsubmit',
			'type' => 'submit',
			"ignore"=>true,
			'options' =>array(
				'label' =>'Submit',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'btn btn-info btn-fill pull-right',   
				'style' =>'margin-top:20px',
			 ),
		));	
	}
	public function adminlogin()
	{
		$this->add(array(
            'name' => 'user_email',
            'type' => 'text',
			"required"=>true,
            'options' =>array(
                'label' => "Email address",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control login-frm-input required email',    
				"placeholder" => "Email address",     
			 ),
        ));
		
		$this->add(array(
            'name' => 'user_password',
            'type' => 'password',
			"required"=>true,
            'options' =>array(
                'label' => "Password",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control login-frm-input required',    
				"placeholder" => "Password",     
			 ),
        ));

	}
	
	public function forgotpasswordadmin()
	{
		$this->add(array(
			'name' => 'user_email',
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				'label' => "Email Address",
			),
			
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control login-frm-input required email',    
				"placeholder" => "Email Address",     
			 ),
		));				
	}
	
	public function resetPassword(){
		
		$this->add(array(
            'name' => 'user_password',
            'type' => 'password',
			"required"=>true,
            'options' =>array(
                'label' => "Enter Password",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control login-frm-input required',  
				'id'  => 'user_password',    
				"placeholder" => "Enter Password", 
				"autocomplete" =>"off", 
			 ),
        ));
		
		$this->add(array(
            'name' => 'user_rpassword',
            'type' => 'password',
			"required"=>true,
            'options' =>array(
                'label' => "Re Type  Password",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control login-frm-input required',  
				'id'  => 'user_rpassword',    
				"placeholder" => "Re Type  Password", 
				"autocomplete" =>"off", 
			 ),
        ));
	}
	
	public function profile_admin()
    {
        // We will ignore the name provided to the constructor
        parent::__construct();
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'profile_form');
      
		$this->add(array(
            'name' => 'user_name',
            'type' => 'text',
			"required"=>true,
            'options' =>array(
                'label' => "Full Name",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => "Full Name",     
			 ),
        ));
		
		
		$this->add(array(
            'name' => 'user_email',
            'type' => 'text',
			"required"=>true,
            'options' =>array(
                'label' => "Email",
            ),
			
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required email checkemail_exclude',    
				"placeholder" => "Email Address",     
			 ),
        ));
		
		$this->add(array(
            'name' => 'user_address',
            'type' => 'text',
			"required"=>true,
            'options' =>array(
                'label' => "Address",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => "Address",     
			 ),
        ));
		
		$this->add(array(
            'name' => 'user_phone',
            'type' => 'text',
			"required"=>true,
            'options' =>array(
                'label' => "Phone Number",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => "Phone Number",   
				'id'=>'user_phone',  
			 ),
        ));
		
		$this->add(array(
            'name' => 'user_city',
            'type' => 'text',
			"required"=>true,
            'options' =>array(
                'label' => "City",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => "City",     
			 ),
        ));
		
		$this->add(array(
            'name' => 'user_state',
            'type' => 'text',
			"required"=>true,
            'options' =>array(
                'label' => "State",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => "State",     
			 ),
        ));
		$this->add(array(
            'name' => 'user_country',
            'type' => 'text',
			"required"=>true,
            'options' =>array(
                'label' => "Country",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => "Country",     
			 ),
        ));
		
		$this->add(array(
			'name' => 'user_image',
			'type' => 'file',			
			'options' =>array(
				'label' => "Profile Image",
			),
			"accept"=>"image/*",
			'attributes' =>array(        // Array of attributes
				'class'  => 'default',  
				"id" => "user_image",  
				"accept"=>"image/*"				
			 ),
		));	
    }
	
	public function password()
	{
        parent::__construct('admin');

		$this->setAttribute('class','profile_form');
		
       
		$this->add(array(
            'name' => 'user_old_password',
            'type' => 'password',
			"ignore"=>true,
			"required"=>true,
            'options' =>array(
                'label' => "Enter Old Password",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => "Enter Old Password",     
			 ),
        ));
		
		$this->add(array(
            'name' => 'user_password',
            'type' => 'password',
			"required"=>true,
            'options' =>array(
                'label' => "Enter Password",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',  
				'id'  => 'user_password',    
				"placeholder" => "Enter Password", 
				"autocomplete" =>"off", 
			 ),
        ));
		
		$this->add(array(
            'name' => 'user_rpassword',
            'type' => 'password',
			"ignore"=>true,
			"required"=>true,
            'options' =>array(
                'label' => "Re Type  Password",
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => "Re Type  Password", 
				"autocomplete" =>"off",    
			 ),
        ));
    }
	
	public function siteconfig($configData,$Currencies)
    {
		global $paymentModeArr;
		foreach($configData as $key=>$values){	
			
			$addClass="";
			if($values['config_group']=='SOCIAL_LINKS'){
				$addClass="url";
			}
			if($values['config_key']=='site_commission' || $values['config_key']=='upload_photo_charge'){
				$addClass="number";
			}
			else if($values['config_key']=='subcat_count'){
				$addClass="digits";
			}
			
			if($values['config_key']!='site_logo' && $values['config_key']!='exchange_currency'){
				$this->add(array(
					'name' => $values['config_key'],
					'type' => 'text',
					/*"required"=>true,*/
					'options' =>array(
						'label' => $values['config_title'],
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'form-control '.$addClass,    
						"placeholder" => $values['config_title'], 
						"autocomplete" =>"off",    
					 ),
				));
			}
			
		else if($values['config_key']=='exchange_currency'){
				
				$this->add(array(
					'name' => 'exchange_currency',
					'type' => 'select',
					"required"=>true,
					'options' =>array(
					   'label' => "Select Currency",
					   'value_options'=>$Currencies
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'form-control required',   
						'id' => 'exchange_currency',
					 ),
				));
			
		
			}
			else if($values['config_key']=='paypal_payment_mode'){
				$this->add(array(
					'name' => $values['config_key'],
					'type' => 'radio',
					'options' =>array(
						'label' => $values['config_title'],
						'value_options' => $paymentModeArr,
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'required',    
						"placeholder" => $values['config_title'], 
					 ),
				));
			
		
			}else{
				$this->add(array(
					'name' => $values['config_key'],
					'type' => 'file',			
					'options' =>array(
						'label' => $values['config_title'],
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'default',  
						"id" => $values['config_key'],
					 ),
				));	
			}
		}
			
		$this->add(array(
			'name' => 'bttnsubmit',
			'type' => 'submit',
			'ignore' => true,
			'options' =>array(
				'label' => 'Submit',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control',    
				"autocomplete" =>"off",    
			 ),
		));
    }
	
	
	private function getValidate($inputFilter,$ValidateElement){
			
			$arrayValidate=array(
						"EmailAddress"=> array(
							'name' => 'EmailAddress',
							'options' => [
							  'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
							  'useMxCheck' => false,                            
							],
						),
						"StringLength"=> array(
							'name' => 'StringLength',
							 'options' => [
								'min' => 1,
								'max' => 3
							  ],
						),
						"NotEmpty"=>array(
							'name'=>"NotEmpty",
						)
						
			
					
			);
			
			
			
		 if(!empty($ValidateElement)){
			foreach($ValidateElement as $key=>$value){
				 $inputFilter->add([
       				 'name'     => $key,
        			'required' => true,
        			'filters'  => [
         				  ['name' => 'StringTrim'],                    
        				],                
        			'validators' => [
							
							$arrayValidate[$value],
						 
						  
						],
					
     				 ]
	   		 	);	
		 }
		 }
	}
	
	
	
}