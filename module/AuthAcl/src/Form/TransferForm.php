<?php
namespace AuthAcl\Form;
use Zend\Db\Sql\Sql;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

class TransferForm extends Form
{
	
    public function __construct($name = null)
    { 
        parent::__construct('user_profile');
        $this->setAttribute('method', 'post');		
		$this->setAttribute('class', 'profile_form');
	}
	public function deposit(){
		global $currencyBox;global $methodBox;
		$this->add(array(
            'name' => 'deposit_amount',
            'type' => 'text',
            'attributes' => array(
                'id' => 'deposit_amount',
                'class' => 'form-control required number',
				
				'required' => 'required',			
				
            ),
            'options' => array(
                'label' => 'Deposit Amount',
					
				               
            )
        ));	
		
		$this->add(array(
            'name' => 'deposit_currency',
            'type' => 'select',
			"required"=>true,
            'options' =>array(
			 	"value_options" => $currencyBox,   
				'label' => "Currency",      
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required', 
				'id' => 'deposit_currency',
			),
        ));
		
		$this->add(array(
            'name' => 'deposit_method',
            'type' => 'select',
			"required"=>true,
            'options' =>array(
			 	"value_options" => $methodBox,   
				'label' => "Method",      
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required', 
				'id' => 'deposit_method',
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
                'label'=>'<span>Continue</span>',
				'label_options' => array(
                	'disable_html_escape' => true,
           	 )
				
            ),
        ));
		
		$this->addInputFilter();
	}
	
	public function withdraw(){
		global $currencyBox;global $methodBox;
		$this->add(array(
            'name' => 'withdraw_amount',
            'type' => 'text',
            'attributes' => array(
                'id' => 'withdraw_amount',
                'class' => 'form-control required number',
				
				'required' => 'required',			
				
            ),
            'options' => array(
                'label' => 'Withdraw Amount',
					
				               
            )
        ));	
		
		$this->add(array(
            'name' => 'withdraw_currency',
            'type' => 'select',
			"required"=>true,
            'options' =>array(
			 	"value_options" => $currencyBox,   
				'label' => "Currency",      
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required', 
				'id' => 'withdraw_currency',
			),
        ));
		
		$this->add(array(
            'name' => 'withdraw_method',
            'type' => 'select',
			"required"=>true,
            'options' =>array(
			 	"value_options" => $methodBox,   
				'label' => "Method",      
            ),
			'attributes' =>array(        // Array of attributes
				'class'  => 'form-control required', 
				'id' => 'withdraw_method',
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
                'label'=>'<span>Submit</span>',
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