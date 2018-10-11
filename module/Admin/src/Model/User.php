<?php
namespace Admin\Model;

use Zend\Db\Sql\Sql,
    Zend\Db\Sql\Where;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Authentication\Storage\Session as SessionStorage;
use Application\Model\AbstractModel;
use Application\Model\Email;
use Zend\Session\Container;


class User extends AbstractTableGateway
{
    public $table = 'users';	
    public function __construct(Adapter $adapter)
    { 
		$this->adapter = $adapter;					
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }
	public function getAdminUser($id=false)
    {
        $id = (int) $id;
		
		$sql = new Sql($this->adapter);
		$select = $sql->select();
		$select->from('users');
		$where = new Where();
		$select->columns(array('*'));
		//pr($data);
		
		$where->equalTo('user_type','admin');
	    
		$select->where($where);
		$statement = $sql->prepareStatementForSqlObject($select);
		$res =  $statement->execute();
		
		$resultSet = $res->getResource()->fetch();	
		
		return $resultSet;
		
        $rowset = $this->adapter->select(array('user_type' => 'admin'));
		//prd($rowset);
        $row = $rowset->current();
		//prd($row);
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }
        return $row;
    }
	
	public function chkLogin(Admin $admin)
    {
		$sessionContainer = new Container('myNameSpace');
		$data = array(
            'user_email'  => $admin->user_email,
			'user_password'  => $admin->user_password ,
        );
		;
		$sql = new Sql($this->adapter);
		$select = $sql->select();
		$select->from('users');
		$where = new Where();
		$select->columns(array('user_id'));
		
		
		$where->equalTo('user_email',$data['user_email']);
		$where->equalTo('user_password',md5($data['user_password']));
	    
		$select->where($where);
		$select->where("(user_type = 'admin' OR (user_type = 'subadmin' AND user_status='1'))");
		$statement = $sql->prepareStatementForSqlObject($select);
		$res =  $statement->execute();
		
		$resultSet = $res->getResource()->fetchAll();	
		
		return $resultSet;
    }
	
	public function checkAdminEmail($email,$id=false,$is_admin=false)
    {	
		
		$sql = new Sql($this->adapter);
		$select = $sql->select();
		$select->from('users');
		$where = new Where();
		$select->columns(array('user_id'));
		
		$select->where("(user_type = 'admin' OR (user_type = 'subadmin' AND user_status='1'))");
		$where->equalTo('user_email', $email);
		
	    
		$select->where($where);
		$statement = $sql->prepareStatementForSqlObject($select);
		$res =  $statement->execute();
		
		$resultSet = $res->getResource()->fetchAll();
		
		return $resultSet;
		//
    }
	
	public function add($data,$id = false,$type=false)
	{
		$password = $data['user_pass'];
		$sql = new Sql($this->adapter);			
		$AbstractModel=new AbstractModel($this->adapter);
		
		try{
			if(isset($data['url'])){
				$getUrl = $data['url'];
				unset($data['url']);
			}
			
			//Profile Update
			if($id){ 				
				$update = $sql->update();
				$update->table('users');
				$update->set($data);
				$update->where('user_id='.$id.'');
				$selectString = $sql->getSqlStringForSqlObject($update);
				
				$updated_records = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);	
				$userDetails=$this->getUsers('user_id='.$id.'');				
				
				return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Updated","row_affected"=>$updated_records) ;
			}		
			
			//User Registration
			
				$password = time();
				
				$data['user_email'] = $data['user_email'];
				$data['user_first_name'] = $data['user_first_name'];
				$data['user_phone'] = $data['user_phone'];
				$data['user_created'] = date('Y-m-d H:i:s');
				$data['user_status'] = '1';
				$data['user_email_verified'] = '1';
				$data['user_password'] = md5($password);
				
				$insert = $sql->insert('users');
				$insert->values($data);
				
				$selectString = $sql->getSqlStringForSqlObject($insert);
				$results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
				$insertedId = $this->adapter->getDriver()->getLastGeneratedValue();
				
				$reset_password_key = md5($insertedId."!@#$%^$%&(*_+".time());			
				$pass_key['pass_resetkey'] = $reset_password_key;			
							
				$user_update = $AbstractModel->Super_Insert('users',$pass_key,"user_id='".$insertedId."'");	
				$email_data=array();
				$email_data['user_email']= $data['user_email'];
				$email_data['user_name'] = $data['user_name'];
				$email_data['user_first_name'] = $data['user_first_name'];
				$email_data['user_last_name'] = $data['user_last_name'];
				$email_data['user_pass'] = $data['user_pass'];
				$email_data['user_email'] = $data['user_email'];
				$email_data['user_type'] = $data['user_type'];
				$email_data['password'] = $password;
				$email_data['key'] = $reset_password_key;
				
				if(isset($User_id) && $User_id!=''){
					$email_data['user_id'] = $User_id;
				}
				
				$Email=new Email($this->adapter);
				$isSend = $Email->sendEmail('registration_by_admin_email',$email_data);
			
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
	
	public function checkoldpassword($user_password,$id=false)
	{	
		$sql = new Sql($this->adapter);
		$select = $sql->select();
		$select->from('users');
		$where = new Where();
		$select->columns(array('*'));
		
		$where->equalTo('user_password',$user_password);
		$where->equalTo('user_id', $id);
	
		$select->where($where);
		$statement = $sql->prepareStatementForSqlObject($select);
		$res =  $statement->execute();
		$resultSet = $res->getResource()->fetchAll();	
	
		return $resultSet;
 	}
	
	public function checkEmail($email,$id=false){
		$sql = new Sql($this->adapter);
		$select = $sql->select();
		$select->from('users');
		$where = new Where();
		$select->columns(array('*'));
		
		$where->equalTo('user_email',$email);
		
		if($id){
			$where->notequalTo('user_id', $id);
		}
		
		$select->where($where);
		$statement = $sql->prepareStatementForSqlObject($select);
		$res =  $statement->execute();
		
		$resultSet = $res->getResource()->fetchAll();	
		
		return $resultSet;
		
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
	
	public function getAverageSurveyData(){
		$sql = new Sql($this->adapter);
		
		//$data = $sql->
	}
	
	
}
