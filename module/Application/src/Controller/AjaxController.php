<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class AjaxController extends AbstractActionController
{
 
	public function __construct($Adapter)
    {
		$this->Adapter = $Adapter;
    }
	public function setstatusAction() {
		
		$dbAdapter=$this->Adapter;
		$params =  $this->params()->fromRoute();
		
		$sql = new Sql($dbAdapter);		
		$data = array(	
					
  			);
			
		 	if(isset($data[$params['type']])){
				$update_data=array($data[$params['type']]['field_status']=>$params['status']) ;
				try{
				
					$update = $sql->update();
					$update->table($data[$params['type']]['table']);
					$update->set( $update_data);
					$update->where($data[$params['type']]['field_id'].' = '.$params['id']);
					$selectString = $sql->getSqlStringForSqlObject($update);
					
					$updated = $dbAdapter->query($selectString)->execute();						
										echo json_encode(array("success"=>true,"error"=>false,"message"=> " Status of ".ucwords(str_replace("_"," ",$data[$params['type']]['field_status']))." Successfully Updated"));
					
										
				}catch(Zend_Exception $e){
					echo json_encode(array("success"=>false,"error"=>true,"exception"=>true,"exception_code"=>$e->getCode(),"message"=>$e->getMessage() ));
				}
			}else{
				echo json_encode(array("success"=>false,"error"=>true,"exception"=>false,"message"=>"Table Not Defined for the Current Request" ));
			}
			

				
			
 			exit();
	}
	
 	
}

