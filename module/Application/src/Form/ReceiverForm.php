<?php
namespace Admin\Form;
use Zend\Form\Form;

use Zend\Db\Sql\Sql;
use Zend\Form\Element;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;

class ReceiverForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('admin');

		$this->setAttribute('class','profile_form');
		
    }
	public function receiverform(){
		$inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		$this->add(array("name"=>"pro_title","type"=>"text","options"=>array("label"=>"Title"),"attributes"=>array("class"=>"form-control required",'required' => 'required',"placeholder"=>"Title")));
		$this->add(array("name"=>"pro_email_id","type"=>"text","options"=>array("label"=>"Invitation Email ID"),"attributes"=>array("class"=>"form-control required email",'required' => 'required',"placeholder"=>"Invitation Email ID")));
		
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