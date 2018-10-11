<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
/*use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Form\Element\Csrf as CsrfElement;
use Zend\Validator\Csrf as CsrfValidator;*/

class StaticForm extends Form
{
	/*protected $csrf;
	protected $inputFilter;*/
    public function __construct($name = null)
    {
		// We will ignore the name provided to the constructor
		parent::__construct('application');
		$this->setAttribute('class','profile_form');
		//$this->addInputFilter();
		
    }
	public function jobapplyform(){
		$this->setAttribute("enctype","multipart/form-data");
		$this->add(array(
			'name' => 'job_app_name',
			'type' => 'text',
			'options' =>array(
				'label' => "Name",
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'required form-control',    
				'id' => 'job_app_name',
			 ),
		));
		
		$this->add(array(
			'name' => 'job_app_email',
			'type' => 'text',
			'options' => array(
				'label' => 'Email Address',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'required form-control email',    
				 'id' => 'job_app_email',
			),
		));
		
		$this->add(array(
			'name' => 'job_app_phone',
			'type' => 'text',
			'options' => array(
				'label' => 'Phone Number',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'required form-control',    
				'id'=>'job_app_phone',
			),
		));
		
		$this->add(array(
			'name' => 'job_app_detail',
			'type' => 'textarea',
			'options' => array(
				'label'  => 'Detail',     
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'required form-control',    
				'rows'  => '6', 
				'id' => 'job_app_detail',    
			),
		));	
		
		$this->add(array(
            'name' => 'job_app_file',
			
            'type' => 'file',
			"required"=>true,
            'options' =>array(
                'label' => "Attachment&nbsp;<b>(Valid Extensions : ".DOCUMENT_VALID_EXTENTIONS.")</b>",
            ),
			'attributes' =>array(        // Array of attributes
				  'class'=>"jobAppValid",
				'id'=>'job_app_file',  
			 ),
        ));	
		
		$this->add(array( //Boton envio
			'type'=>'Zend\Form\Element\Button',
			'name'=>'bttnsubmit',
			'id' => 'bttnsubmit',
			'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn SiteButton'
			),
			'options'=> array(
				'label'=>'<span>Submit</span>',
				'label_options' => array(
                	'disable_html_escape' => true,
           	 )
				
			),
		));
		
	}
	public function contact()
    {
		
		$this->add(array(
			'name' => 'user_name',
			'type' => 'text',
			'options' =>array(
				'label' => "",
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'required form-control',    
				'id' => 'user_name',
			 ),
		));
		
		$this->add(array(
			'name' => 'user_email',
			'type' => 'text',
			'options' => array(
				'label' => 'How do we get back to you?',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'required form-control email',    
				 'id' => 'user_email',
			),
		));
		
		$this->add(array(
			'name' => 'user_phone',
			'type' => 'text',
			'options' => array(
				'label' => 'Where can we call you?',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'required form-control',    
				'id'=>'user_phone',
			),
		));
		
		
		$this->add(array(
			'name' => 'user_message',
			'type' => 'textarea',
			'options' => array(
				'label'  => 'So what we can do to help out?',     
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'required form-control',    
				'rows'  => '6', 
				'id' => 'user_message',    
			),
		));
		$this->add(array(
			'name' => 'hiddenRecaptcha',
			'type' => 'hidden',
			'options' => array(
				/*'label' => 'Where can we call you?',*/
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'hiddenRecaptcha required',    
				'id'=>'hiddenRecaptcha',
			),
		));
		
		$this->add(array( //Boton envio
			'type'=>'Zend\Form\Element\Button',
			'name'=>'bttnsubmit',
			'id' => 'bttnsubmit',
			'attributes'=> array(
				'type'=>'submit',
				'class' => 'btn SiteButton'
			),
			'options'=> array(
				'label'=>'<span>Send Message</span>',
				'label_options' => array(
                	'disable_html_escape' => true,
           	 )
				
			),
		));
    }
	
	
	
	
	 private function addInputFilter() 
	  {
		$inputFilter = new InputFilter();        
		$this->setInputFilter($inputFilter);
		
			
		$inputFilter->add([
			'name'     => 'user_email',
			'required' => true,
			'filters'  => [
			   ['name' => 'StringTrim'],                    
			],                
			'validators' => [
			   [
				'name' => 'EmailAddress',
				'options' => [
				  'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
				  'useMxCheck' => false,                            
				],
			  ],
			],
		  ]
		);
		
		$inputFilter->add([
			'name'     => 'user_name',
			'required' => true,
			'filters'  => [
			   ['name' => 'StringTrim'],
			   ['name' => 'StripTags'],			   
			],                
			'validators' => [
			   [
				'name' => 'StringLength',
				  'options' => [
					'min' => 1,
					'max' => 128
				  ],
			   ],
			],
		  ]
		);	
		$inputFilter->add([
			'name'     => 'message',
			'required' => true,
			'filters'  => [
			   ['name' => 'StringTrim'],
			   ['name' => 'StripTags'],			   
			],                
			
		  ]
		);
		
			
     } 
	
}