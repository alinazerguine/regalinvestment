<?php
namespace Admin\Model;

// Add the following import statements:
use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Admin implements InputFilterAwareInterface
{
    //public $id;
    public $user_first_name;
    public $user_last_name;
	public $user_email;
	public $user_address;
	public $user_city;
	public $user_country;
	public $user_postal_code;

    // Add this property:
    private $inputFilter;

    public function exchangeArray2(array $data)
    {
		//prd();
        $this->user_email     = !empty($data['user_email']) ? $data['user_email'] : null;
		$this->user_password =  !empty($data['user_password']) ? $data['user_password'] : null;
    }


    public function exchangeArray(array $data)
    {
		//prd();
        $this->user_id     = !empty($data['user_id']) ? $data['user_id'] : null;
		$this->user_image =  !empty($data['user_image']) ? $data['user_image'] : null;
		$this->user_password =  !empty($data['user_password']) ? $data['user_password'] : null;
        $this->user_first_name = !empty($data['user_first_name']) ? $data['user_first_name'] : null;
        $this->user_last_name  = !empty($data['user_last_name']) ? $data['user_last_name'] : null;
		$this->user_email  = !empty($data['user_email']) ? $data['user_email'] : null;
		$this->user_address  = !empty($data['user_address']) ? $data['user_address'] : null;
		$this->user_city  = !empty($data['user_city']) ? $data['user_city'] : null;
		$this->user_country  = !empty($data['user_country']) ? $data['user_country'] : null;
		$this->user_postal_code  = !empty($data['user_postal_code']) ? $data['user_postal_code'] : null;
    }

    /* Add the following methods: */

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
       
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
	
}