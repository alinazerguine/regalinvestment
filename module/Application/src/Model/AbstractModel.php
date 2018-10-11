<?php
namespace Application\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Application\Model\Email;
use Zend\Db\Sql\Expression;

class AbstractModel extends AbstractTableGateway
{

    public $table = 'users';	
	public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
    
    public function fetchAll($table,$where = array(),$column)
    {
		try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                'table' => $table
            ));
            
            if (count($where) > 0) {
                $select->where($where);
            }
            
            if (count($columns) > 0) {
                $select->columns($columns);
            }
            
            $statement = $sql->prepareStatementForSqlObject($select);
            $results = $this->resultSetPrototype->initialize($statement->execute())->toArray();
            return $results;
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }
	
	public function Super_Get($table,$where = 1,$fetchMode = 'fetch',$extra = array(),$joinArr=array()){
		$sql = new Sql($this->getAdapter());		
		$select = $sql->select()->from(array(
                'table' => $table
        ));
		
		$fields = array('*');
		
		if(isset($extra['fields']) and  $extra['fields']){
			if(is_array($extra['fields'])){
				$fields = $extra['fields'];
			}else{
				$fields = explode(",",$extra['fields']);
			}		
			$select->columns($fields);
		}
		
		$select->where($where);
			//prd($joinArr);
		if(isset($joinArr))
		{
			foreach($joinArr as $newCondition)
			{ 
				if($newCondition[2]=='full')
				{
					$select->join($newCondition[0],$newCondition[1],$newCondition[3]);
				}
				else
				   $select->join($newCondition[0],$newCondition[1],$newCondition[3],'left');
			}
		}
	
		if(isset($extra['group']) and  $extra['group']){
				$select->group($extra['group']);
		}
		
		if(isset($extra['having']) and  $extra['having']){
			$select->having($extra['having']);
		}
		
		if(isset($extra['order']) and  $extra['order']){		
			
			$select->order($extra['order']);
		}
		
		if(isset($extra['limit']) and  $extra['limit']){
			$select->limit((int)$extra['limit']);
		}
		
		if(isset($extra['offset']) and  $extra['offset']){		
			$select->offset($extra['offset']);
		}
		
		if(isset($extra['pagination']) and  $extra['pagination']){
			
			$statement = $sql->prepareStatementForSqlObject($select);
			$results = $this->resultSetPrototype->initialize($statement->execute());
			return $results;
			
		}
		
		$statement = $sql->prepareStatementForSqlObject($select);
		if(isset($extra['test']) and  $extra['test']){
			//echo $select;
			prd($statement);
			
		}
		
		if($fetchMode=='fetch')
		{
			try
			{
				$results = $this->resultSetPrototype->initialize($statement->execute())->current();
				return $results;
			}
			catch (\Exception $e) {
				//prd($e->getMessage());
				return $e->getMessage();
				//throw new \Exception($e->getPrevious()->getMessage());
			}
		}
		else
		{
			try
			{
				$results = $this->resultSetPrototype->initialize($statement->execute())->toArray();
				return $results;
			}
			catch (\Exception $e) {
				//prd($e->getMessage());
				return $e->getMessage() ;
				//throw new \Exception($e->getPrevious()->getMessage());
			}
		}
		
		
	 }
	    
		//Insert Update Data
	public function Super_Insert($table,$data,$where = false){	
	
		$sql = new Sql($this->getAdapter());		
		
		try{			
			if($where){	
				
				$update = $sql->update();
				$update->table($table);
				$update->set($data);
				$update->where($where);
				$selectString = $sql->getSqlStringForSqlObject($update);
			
				$results = $this->getAdapter()->query($selectString, Adapter::QUERY_MODE_EXECUTE);	
				
				return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Updated");
			}
			
			$insert = $sql->insert($table);   
			$insert->values($data);
			
			$selectString = $sql->getSqlStringForSqlObject($insert);
			$results = $this->getAdapter()->query($selectString, Adapter::QUERY_MODE_EXECUTE);
			$lastId = $this->getAdapter()->getDriver()->getLastGeneratedValue();

 			return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Inserted","inserted_id"=>$lastId) ;
 		}
		catch (\Exception $e) {/* Handle Exception Here  */
			return (object)array("success"=>false,"error"=>true,"message"=> $e->getMessage(),"exception"=>true,"exception_code"=> $e->getMessage()) ;
 		}
	}
	
	//Delete Data
	public function Super_Delete($table_name,$where = false){	
   		
		$sql = new Sql($this->getAdapter());								
		try{			
			$delete = $sql->delete($table_name)->where($where);  
			$deleteString = $sql->getSqlStringForSqlObject($delete);
			$results = $this->getAdapter()->query($deleteString, Adapter::QUERY_MODE_EXECUTE);	
			return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Deleted") ;
  		}
		catch(Zend_Exception  $e) {/* Handle Exception Here  */
			return (object)array("success"=>false,"error"=>true,"message"=>$e->getMessage(),"exception"=>true,"exception_code"=>$e->getCode()) ;
 		}
	}
	
	public function fetchRecord($table,$feild,$where) 
    {
		$adapter =$this->getAdapter();
		$tableGateway = new TableGateway( $table , $adapter);
		
	    $rowset = $tableGateway->select(array($feild=>$where));
        $row = $rowset->current();
		
        return $row;  		
    } 
	
	
	public function Notification_Insert($data){	
		$table='messages';
		$sql = new Sql($this->getAdapter());		
		
		try{	
		
			$data['subject']=NULL;	
			$data['date_send']=date('Y-m-d h:i:s');				
			$insert = $sql->insert($table);  			
			$insert->values($data);	
						
			$selectString = $sql->getSqlStringForSqlObject($insert);		
			$results = $this->getAdapter()->query($selectString, Adapter::QUERY_MODE_EXECUTE);
			$lastId = $this->getAdapter()->getDriver()->getLastGeneratedValue();
			
			$Email=new Email($this->adapter);
			$isSend = $Email->sendEmail('candidate_message_mail',$msgdata);
			
			

 			return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Inserted","inserted_id"=>$lastId) ;
 		}
		catch(Zend_Exception  $e) {/* Handle Exception Here  */
			return (object)array("success"=>false,"error"=>true,"message"=>$e->getMessage(),"exception"=>true,"exception_code"=>$e->getCode()) ;
 		}
	}
	
	
	public function prepareselectoptionwhere($tablename,$fieldname1,$fieldname2,$where,$order=false)
	{
		
		$sql = new Sql($this->getAdapter());		
		if(!$order)
		{	
			
				$result = $sql->select()->from(array(
					'table' => $tablename
				))->where($where);
				$statement = $sql->prepareStatementForSqlObject($result);
				
				$data = $this->resultSetPrototype->initialize($statement->execute())->toArray();
				
				
		}
		else  
			$result = $sql->select()->from(array(
				'table' => $tablename
			))->where($where)->order($order);
			
			$statement = $sql->prepareStatementForSqlObject($result);
			$data = $this->resultSetPrototype->initialize($statement->execute())->toArray();
		
		
			for ($i = 0; $i < count($data); $i++)
			{ 
				if($data[$i][$fieldname2]!=''){
					if($tablename=='users'){
						$getdata[$data[$i][$fieldname1]]= $data[$i]['user_first_name'].' '.$data[$i]['user_last_name'];
					}else if($tablename=='currency'){
						
						$getdata[$data[$i][$fieldname1]]= $data[$i]['currency_title'].'('.$data[$i]['currency_code'].')';
					}else{
						$getdata[$data[$i][$fieldname1]]= $data[$i][$fieldname2];
					}
				}
			}
			return $getdata;
	}
	
	public function getNotificationContent($notifyData,$siteUrl,$type=false,$site_configs=array(),$user)
	{
		$AbstractModel=new AbstractModel($this->adapter);
		switch($notifyData['notification_type']){ 
			case 2: { // NEW JOB
				
				$jobData = $AbstractModel->Super_Get("jobs","job_id='".$notifyData['notification_type_id']."'","fetch",array());
				$userData=$AbstractModel->Super_Get("users","user_type='admin'","fetch",array("fields"=>array('user_first_name',
			'user_last_name','user_image')));
				
			//	$userData['user_image']=$site_configs['site_logo'];
			//	$userData['user_first_name']=$site_configs['site_title'];
				$notifyText = "New job posted on the website";
				$notifyMainText = $jobData['job_title']." <a href='".APPLICATION_URL."/invited-jobs#".$notifyData['notification_id']."'>View details</a>";
				if($user->user_type=='admin')
					$notifyLink = $siteUrl.'/'.BACKEND.'/notifications/';
				else
					$notifyLink = $siteUrl.'/'.'notifications#'.paramValEncode($notifyData['notification_type_id']);	
				$notifyColor='success';
				break;
			}
			
		}
		return array($notifyColor,$notifyText,$notifyMainText,$notifyLink,$userData);
	}
}