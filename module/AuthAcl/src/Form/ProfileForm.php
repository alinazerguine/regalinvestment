<?php
namespace AuthAcl\Form;
use Zend\Db\Sql\Sql;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

class ProfileForm extends Form
{
	
    public function __construct($name = null)
    { 
        parent::__construct('user_profile');
        $this->setAttribute('method', 'post');		
		$this->setAttribute('id', 'user_profile_form');
		$this->setAttribute('class', 'profile_form');
	}
	
	public function planmodel($planBox){
		
		if(!empty($planBox)){
			$planBox=array(""=>"Select Plan")+$planBox;
		}else{
			$planBox=array(""=>"Select Plan");
		}
		 $this->add(array(
            'name' => 'plan_ids',
            'type' => 'select',
			"required"=>true,
            'options' =>array(
			 	"value_options" => $planBox,   
				'label' => "Plan",      
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required', 
				'id' => 'plan_ids',
				//"multiple"=>"multiple" 
				
			 ),
        ));
		
		$this->add(array( //Button envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'custom_submit',	
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn SiteButton',
				'id' => 'custom_submit',		
            ),
            'options'=> array(
                'label'=>'<span>Save</span>',
				'label_options' => array(
                	'disable_html_escape' => true,
           	 )
				
            ),
        ));
		 
	}
	public function resources($resourcesBox){
		if(!empty($resourcesBox)){
			$resourcesBox=array(""=>"Select Resource")+$resourcesBox;
		}else{
			$resourcesBox=array(""=>"Select Resource");
		}
		 $this->add(array(
            'name' => 'res_id',
            'type' => 'select',
			"required"=>true,
            'options' =>array(
			 	"value_options" => $resourcesBox,   
				'label' => "Resource",      
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required', 
				'id' => 'res_id',
				//"multiple"=>"multiple" 
				
			 ),
        ));
		$this->add(array( //Button envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'resource_submit',	
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn SiteButton',
				'id' => 'resource_submit',		
            ),
            'options'=> array(
                'label'=>'<span>Save</span>',
				'label_options' => array(
                	'disable_html_escape' => true,
           	 )
				
            ),
        ));
	}
	
	
	public function dashboard(){
		$this->add(array(
            'name' => 'user_dashboard_txt',
            'type' => 'textarea',
            'attributes' => array(
                'id' => 'user_dashboard_txt',
                'class' => 'form-control required',
				'required' => 'required',	
				'rows'=>5	
            ),
            'options' => array(
                'label' => 'How can we improve this page?',	
				       
            )
        ));
		$this->add(array( //Button envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'bttnsubmit',	
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn SiteButton upload-result',
				'id' => 'bttnsubmit',		
            ),
            'options'=> array(
                'label'=>'<span>Submit</span>',
				'label_options' => array(
                	'disable_html_escape' => true,
           	 )
				
            ),
        ));
		
		$this->addInputFilter();
	}	



	public function user($propertyArr){
		
		
		
		
		
		$this->add(array(
            'name' => 'user_first_name',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_first_name',
                'class' => 'form-control required',
				'required' => 'required',			
				
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
				
            ),
            'options' => array(
                'label' => 'Last Name',				
				               
            )
        ));	
		
		$this->add(array(
            'name' => 'user_email',
            'type' => 'text',
            'attributes' => array(
                'id' => 'user_email',
                'class' => 'form-control email required checkemail_exclude',
				'required' => 'required',	
			),
            'options' => array(
                'label' => 'Email',				
				              
            )
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
		
		
		$this->add(array( //Button envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'bttnsubmit',	
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn SiteButton upload-result',
				'id' => 'bttnsubmit',		
            ),
            'options'=> array(
                'label'=>'<span>Save Profile</span>',
				'label_options' => array(
                	'disable_html_escape' => true,
           	 )
				
            ),
        ));
		
		$this->addInputFilter();
	}
	
	public function sendConsultRequest(){
		$this->add(array(
            'name' => 'consult_request_date',
            'type' => 'text',
            'attributes' => array(
                'id' => 'consult_request_date',
                'class' => 'form-control required',
				'required' => 'required',	
				//"readonly"=>true,	
            ),
            'options' => array(
                'label' => 'Select Date',	
				       
            )
        ));
		$this->add(array( //Button envio
            'type'=>'Zend\Form\Element\Button',
            'name'=>'bttnsubmit',	
            'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn SiteButton upload-result',
				'id' => 'bttnsubmit',		
            ),
            'options'=> array(
                'label'=>'<span>Send Request</span>',
				'label_options' => array(
                	'disable_html_escape' => true,
           	 )
				
            ),
        ));
		
		$this->addInputFilter();
	}	
   
	public function addInputFilter()
    {
		
		 $inputFilter = new InputFilter\InputFilter();  
		$this->setInputFilter($inputFilter);
		
		
        $this->setInputFilter($inputFilter);
        return $inputFilter;
	}
	
	
	
}