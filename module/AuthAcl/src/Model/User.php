<?php
namespace AuthAcl\Model;

use Zend\Db\Sql\Sql,
    Zend\Db\Sql\Where;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Authentication\Storage\Session as SessionStorage;

use Zend\Session\Container;

use Application\Model\AbstractModel;
use Application\Model\Email;

class User extends AbstractTableGateway
{

    public $table = 'users';
    
    public function __construct(Adapter $adapter)
    { 
		$this->adapter = $adapter;					
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();		
    }
	
	public function add($data,$id = false){
		
		$password=$data['user_password'];
		
		$sql = new Sql($this->adapter);			
		$AbstractModel=new AbstractModel($this->adapter);
		
		try{
			//Profile Update
			if($id){
				$update = $sql->update();
				$update->table('users');
				$update->set($data);
				$update->where('user_id='.$id.'');
				$selectString = $sql->getSqlStringForSqlObject($update);
				
				$updated_records = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);	
				$userDetails=$this->getUsers('user_id='.$id.'');
							
				if(isset($userDetails) && !empty($userDetails) && $userDetails[0]!=''){
					$Front_User_Session = new Container(DEFAULT_AUTH_NAMESPACE);							
					$Front_User_Session->offsetSet('loggedUser',$userDetails[0]);	
					
				}else if($user_info[0]=='' || empty($user_info)){
					return $this->redirect()->toRoute(APPLICATION_URL.'front-logout');
				}
						
				return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Updated","row_affected"=>$updated_records) ;
			}		
			
			//User Registration
			$current_date=date('Y-m-d H:i:s');
			$data['user_created'] = $current_date;	
			
			//$AbstractModel=new AbstractModel($this->adapter);
			
			$check_user=$AbstractModel->Super_Get("users","user_email='".$data['user_email']."'","fetch",$extra=array('fields'=>'user_email,user_id,user_type'));
		
			if(empty($check_user))
			{
				$insert = $sql->insert('users');  
				$insert->values($data);
					
					
				$selectString = $sql->getSqlStringForSqlObject($insert);
				
					
				$results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
				
				$insertedId = $this->adapter->getDriver()->getLastGeneratedValue();
				
				$reset_password_key = md5($insertedId."!@#$%^$%&(*_+".time());			
				$pass_key['pass_resetkey'] = $reset_password_key;		
							
				$user_update = $AbstractModel->Super_Insert('users',$pass_key,"user_id='".$insertedId."'");	
					
				$email_data=array();
				$email_data['user_id'] = $insertedId;
				$email_data['user_email'] = $data['user_email'];
				$email_data['user_name'] = $data['user_name'];
				$email_data['password'] = $password;
				$email_data['key'] = $reset_password_key;
				
				$Email = new Email($this->adapter);
				$isSend = $Email->sendEmail('registration_email',$email_data);
			}
			
			return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Inserted","inserted_id"=>$insertedId) ;
 		}
		catch(Zend_Exception  $e) {/* Handle Exception Here  */
			return (object)array("success"=>false,"error"=>true,"message"=>$e->getMessage(),"exception"=>true,"exception_code"=>$e->getCode()) ;
 		}
	}
	
    public function getUsers($where = array(), $columns = array())
    {
		try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                'user' => $this->table
            ));
            
            if (count($where) > 0) {
                $select->where($where);
            }
            
            if (count($columns) > 0) {
                $select->columns($columns);
            }
            
            $statement = $sql->prepareStatementForSqlObject($select);
			
            $users = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
			
            return $users;
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }
	
	public function chkLogin($data)
    {
		$AbstractModel=new AbstractModel($this->adapter);	
		
		$resultSet =$AbstractModel->Super_Get("users","user_type!='user_admin' and user_type!='user_subadmin' and ((user_email='".mysql_escape_string(trim($data['user_email']))."') and user_status='1' and user_assword='".md5($data['user_password'])."')","fetch");	
		return $resultSet;
    }
	
	public function chkLoginId($data)
    {
		$AbstractModel=new AbstractModel($this->adapter);				
		$resultSet =$AbstractModel->Super_Get("users","user_type!='user_admin' and (user_email='".$data['user_email']."') ","fetch");		
		return $resultSet;
    }
	
	public function checkEmail($email,$id=false){	
		$AbstractModel=new AbstractModel($this->adapter);		
		if($id)
		{
			
			$query =$AbstractModel->Super_Get("users","user_email='".$email."' and user_id!='".$id."'","fetch",$extra=array('fields'=>array('user_email','user_id','user_type')));
			
		}
		else
		{
			$query =$AbstractModel->Super_Get("users","user_email='".$email."'","fetch",$extra=array('fields'=>'user_email,user_id,user_type'));
		}
		
		return  $query;	
 	} 
	
	public function checkUname($name,$id=false){	
		$AbstractModel=new AbstractModel($this->adapter);		
		if($id){
			$query =$AbstractModel->Super_Get("users","user_name='".$name."' and user_id!='".$id."'","fetch",$extra=array('fields'=>array('user_email','user_name','user_id','user_type')));
		}
		else
		{
			$query =$AbstractModel->Super_Get("users","user_name='".$name."'","fetch",$extra=array('fields'=>'user_email,user_name,user_id,user_type'));
		}
		
		return  $query;	
 	} 
	
	public function forgotPassword($data_to_update){
		$AbstractModel=new AbstractModel($this->adapter);
		
		$Email=new Email($this->adapter);
		
		$user_data =$AbstractModel->Super_Get("users","user_email='".$data_to_update['user_email']."' and user_type!='admin'","fetch",$extra=array('fields'=>'user_email,user_status,user_id,user_created,user_type,user_first_name,user_last_name'));
		
		if(!empty($user_data)){
			
			$updateData['user_reset_status']	= '1';
			$updateData['pass_resetkey']		= md5($user_data['user_id']."!@#$%^".$user_data['user_created'].time());
			
			$update = $AbstractModel->Super_Insert('users',$updateData,"user_id='".$user_data['user_id']."'");
			
			$email_data = array();
			$email_data['pass_resetkey']	= $updateData['pass_resetkey'];
			$email_data['user_fullname']	= $user_data['user_first_name'].' '.$user_data['user_last_name'];
			$email_data['user_email']		= $user_data['user_email'];
			$email_data['user_type']		= $user_data['user_type'];
			
			$send_mail = $Email->sendEmail('reset_password',$email_data);
			
			return true;
		}
		else{
			return false;
		}
	}
	
	public function resetPassword($data_to_update,$user_data){			
		$AbstractModel=new AbstractModel($this->adapter);
		
		$data_array = array(
			'user_password' =>	md5($data_to_update['user_password']),
			'user_pass'		=>	encodeText($data_to_update['user_password']),
			'pass_resetkey'	=>	''
		);
		$user_update =$AbstractModel->Super_Insert("users",$data_array,"user_id='".$user_data['user_id']."'");
		return $user_update;	
	}
	
	
	public function getAvailableTimeSlot($SelectedDate){
		$AbstractModel=new AbstractModel($this->adapter);
		
		$getConsultingReqSetting = $AbstractModel->Super_Get("consulting_available_time_setting","cons_time_setting_id='1'","fetch");
		
	 	$SelectedDateAllRequest = $AbstractModel->Super_Get("consulting_send_request","consult_request_date='".$SelectedDate."'","fetchAll");
		//prd($SelectedDateAllRequest);
		
		if(!empty($SelectedDateAllRequest)){
			$searchAndSkipTimp = array();
			foreach($SelectedDateAllRequest as $key=>$values){
				$skipTime='';
				$skipTime = date('H:i',strtotime($values['consult_start_time']));
				$searchAndSkipTimp[$skipTime].=$skipTime;
			}
		}
			
		$getTotalWorkingHours = round(abs(strtotime($getConsultingReqSetting['close_time'])-strtotime($getConsultingReqSetting['open_time']))/3600,2);
		
		$getSlotData = getTimeSlot($getConsultingReqSetting['time_slots']);
		
		$startTime = strtotime($getConsultingReqSetting['open_time']);
		$endTime = strtotime($getConsultingReqSetting['close_time']);
		
		// loop over every SlotTime(like 15min,30min,45min and 60min [Coverted time 3600sec]) between the two timestamps
		$count=1;
		for($i = $getSlotData['slotSecondes']; $i <= $endTime - $startTime; $i += $getSlotData['slotSecondes']) {
				// add the current iteration and echo it
				
				if($count==1){
					$prevStartTime = date('H:i', $startTime); 
					$EndTime=date('H:i', $startTime + $getSlotData['slotSecondes']);
					
				}else{
					$EndTime=date('H:i', $startTime + $i);
				}
				
				if(!empty($SelectedDateAllRequest)){ 
				
					$skipSerachTimeRes= array_search($prevStartTime,$searchAndSkipTimp);
	
					if(empty($skipSerachTimeRes)){ 
						$timeSlotArray[$count]['startTime']=$prevStartTime;
						$timeSlotArray[$count]['EndTime']=$EndTime;
					}	
				}else{
					$timeSlotArray[$count]['startTime']=$prevStartTime;
					$timeSlotArray[$count]['EndTime']=$EndTime;
				}
				
				if($count==1){
					$prevStartTime=date('H:i', $startTime + $getSlotData['slotSecondes']);
				}else{
					$prevStartTime = date('H:i', $startTime + $i);
				}
				$count++;
			}
			
			return $timeSlotArray;
	}
}