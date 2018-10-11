<?php
namespace Admin\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class AjaxController extends AbstractActionController
{
	public function __construct($Adapter,$AbstractModel,$Email)
    {
		$this->Adapter = $Adapter;
		$this->AbstractModel = $AbstractModel;
		$this->EmailModel = $Email;	
    }
	
	public function setstatusAction() 
	{
		$dbAdapter=$this->Adapter;
		$params =  $this->params()->fromRoute();
		$sql = new Sql($dbAdapter);
		
		$data = array(		
			"users"=>array(
			'table'      => "users",
			'field_id'      => "user_id",
			'field_status'      => 'user_status'),
			
			"project_categories" =>	array(
				'table'			=> "users",
				'field_id'		=> "user_id",
				'field_status'	=> 'user_status'
			),
			
			"project_sub_categories"	=>	array(
				'table'			=>	"project_sub_categories",
				'field_id'		=>	"psc_id",
				'field_status'	=>	'psc_status'
			),
			
			"slider"	=>	array(
				'table'			=>	"slider",
				'field_id'		=>	"slider_id",
				'field_status'	=>	'slider_status'
			),
			
		);
			
		if(isset($data[$params['type']])){
			$update_data[$data[$params['type']]['field_status']]=$params['status'];
			$updated=$this->AbstractModel->Super_Insert($data[$params['type']]['table'],
			$update_data,
			$data[$params['type']]['field_id']."=".$params['id']);						
			
		   echo json_encode(array("success"=>true,"error"=>false,"message"=> ucwords(str_replace("_"," ",$data[$params['type']]['field_status']))." Successfully Updated "  ));
		
		}else{
			echo json_encode(array("success"=>false,"error"=>true,"exception"=>false,"message"=>"Table Not Defined for the Current Request" ));
		}
			
		exit();
	}
}