<?php
namespace Admin\Form;
use Zend\Form\Form;

use Zend\Db\Sql\Sql;
use Zend\Form\Element;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;

class ConsultingReqForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('admin');

		$this->setAttribute('class','profile_form');
		
    }
	
	public function balance(){
		$this->add(array(
			'name' => 'user_amount',
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				'label' =>'Balance('.PRICE_SYMBOL.')',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required number',    
				"placeholder" => 'Balance', 
				//"value"=>$plan_price
			),
		));
		$this->add(array(
            'name' => 'user_action',
            'type' => 'hidden',
            'attributes' => array(
                'id' => 'user_action',
                'class' => 'form-control',
					
            ),
         ));
		
		//$this->submit();	
	} 
	
	public function availabletimesettingFrm(){
		
		$this->add(array(
			'name' => 'open_time',
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				//'label' =>'Amount($)',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required start_time start_end_common',    
				"placeholder" => 'Start Time',
				//"value"=>$plan_price
			),
		));	
		$this->add(array(
            'name' => 'close_time',
            'type' => 'text',
			
            'options' =>array(
				//"value_options" => $allPlans,      
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required end_time start_end_common',    
				"placeholder" => 'End Time',
			 ),
        	));	
			
			$this->add(array(
            'name' => 'time_slots',
            'type' => 'select',
			
            'options' =>array(
				"value_options" => array(""=>"Select Time Slot","1"=>15,"2"=>30,"3"=>45,"4"=>60),      
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required', 
				'id' => 'time_slots',
			 ),
        	));	
			
			$closeDayValues = array();
			$numbers=array("1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9");
			for($i=1; $i<=31;$i++){
				$checkValue = array_search($i,$numbers);
				if(!empty($checkValue)){
					$closeDayValues[$i]='0'.$i;
				}else{
					$closeDayValues[$i]=$i;
				}
			}
			
			$this->add(array(
				'type' => 'MultiCheckbox',
				'name' => 'closing_days',
				'options' => array(
					'label' => 'Close Days',
					'value_options' =>  $closeDayValues,
					'required'	=>	false,
					'disable_inarray_validator' => true
				),
				
				'attributes' =>array(        // Array of attributes
					'class'  => 'form-control', 
			 	),
				
			));
		
	}
	
	public function plan()
	{
		 $inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);
		 $this->add(array(
			'name' => 'inv_pl_title',
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				'label' =>'Plan Title',
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required',    
				"placeholder" => 'Plan Title', 
			),
		));	
		 
		$this->submit();
	}
	
	public function investmentform($investment=array())
	{
		
		
		 /*$inputFilter = new InputFilter();        
         $this->setInputFilter($inputFilter);*/
		 if(!empty($investment)){
			 $ELEMENTaRRAY=array();
		 foreach($investment as $key=>$values){	
		//prD( $values['inv_res_config_resid']);
		
		if($values['inv_res_config_resid']!=5){
		$this->add(array(
			/*'name' => $values['inv_res_config_resid'][$values['inv_res_config_id']],*/
			'name' => $values['inv_res_config_resid'].'['.strtolower($values['inv_res_config_title']).']',
			
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				'label' =>$values['inv_res_config_title'],
				
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required number',    
				"placeholder" =>$values['inv_res_config_title'], 
				'id'=>$values['inv_res_config_resid'].'['.$values['inv_res_config_id'].']',	 
			 ),
		));	
		if($values['inv_res_config_isreturn']=='1'){
		$this->add(array(
					'name' => $values['inv_res_config_resid'].'[returntype]',
					'type' => 'radio',
				
					'options' =>array(
						'label' => 'Return Type',
						'value_options' => array("0"=>"Compunded","1"=>"Not Compunded"),
						
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'required returntype',    
						
					 ),
				));
		}
		
		
		}else{
			
			$this->add(array(
			
			/*'name' => $values['inv_res_config_resid'].'['.$values['inv_res_config_optid'].']'.'['.$values['inv_res_config_id'].']',*/
			'name' => $values['inv_res_config_resid'].'['.$values['inv_res_config_optid'].']'.'['.strtolower($values['inv_res_config_title']).']',
			'type' => 'text',
			"required"=>true,
			'options' =>array(
				'label' =>$values['inv_res_config_title'],
				
			),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required number',    
				"placeholder" =>$values['inv_res_config_title'], 
				'id'=>$values['inv_res_config_resid'].'['.$values['inv_res_config_id'].']',	 
			 ),
		));	
		
		if($values['inv_res_config_isreturn']=='1'){
		$this->add(array(
					'name' => $values['inv_res_config_resid'].'['.$values['inv_res_config_optid'].']'.'[returntype]',
					'type' => 'radio',
					
					'options' =>array(
						'label' => 'Return Type',
						'value_options' => array("0"=>"Compunded","1"=>"Not Compunded"),
						
					),
					'attributes' =>array(        // Array of attributes
						'class'  => 'required returntype',    
						
					 ),
				));
		}
		
		
		
		}
		$ELEMENTaRRAY[$values['inv_res_config_resid'].'['.$values['inv_res_config_id'].']']='NotEmpty';
		}
		
			//$this->getValidate($inputFilter,$ELEMENTaRRAY);
		 	}
		
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