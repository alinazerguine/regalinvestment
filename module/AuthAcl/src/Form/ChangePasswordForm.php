<?php
namespace AuthAcl\Form;
use Zend\Db\Sql\Sql;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;

class ChangePasswordForm extends Form
{

    public function __construct($name = null, $options = array())
    { 
	
        parent::__construct('changepassword');
        $this->setAttribute('method', 'post');
		 $this->setAttribute('class', 'profile_form');
		
		
		 $this->add(array(
            'name' => 'user_old_password',
            'type' => 'password',
            'attributes' => array(
			 	'id' => 'user_old_password',
                'class' => 'form-control required check_old_password',               
            ),
            'options' => array(
				'label' => 'Password',	               
            )
        ));  
		
		 $this->add(array(
            'name' => 'user_password',
            'type' => 'password',
            'attributes' => array(
			 	'id' => 'user_password',
                'class' => 'form-control required compareoldpassword',               
            ),
            'options' => array(
				'label' => 'New Password',	               
            ),
			'validators' => array(
				array(
					'name' => 'user_password',
					'options' => array(
						'token' => 'user_new_password',
					),
				),
			),
        ));  
		
		$this->add(array(
            'name' => 'user_rpassword',
            'type' => 'password',
            'attributes' => array(
			 	'id' => 'user_rpassword',
                'class' => 'form-control required compareoldpassword',               
            ),
            'options' => array(
				'label' => 'Confirm New Password',	               
            )
        ));  
		
		$this->add(array(
            'name' => 'btnsubmit',
			'options' => array(
                'label' => 'Update Password',
				             
            ),
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit',
                'class' => 'btn-custom hvr-radial-in'
            )
        ));  
    }
	
	 public function addInputFilter()
    {
		
        $inputFilter = new InputFilter\InputFilter();
		
		$user_old_password = new InputFilter\Input('user_old_password');
        $user_old_password->setRequired(true);
        $inputFilter->add($user_old_password);
		
		$user_rpassword = new InputFilter\Input('user_rpassword');
        $user_rpassword->setRequired(true);
        $inputFilter->add($user_rpassword);
		
		$user_password = new InputFilter\Input('user_password');
        $user_password->setRequired(true);
        $inputFilter->add($user_password);		
		
        return $inputFilter;
	}
	
}