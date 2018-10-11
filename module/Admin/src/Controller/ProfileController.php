<?php
namespace Admin\Controller;

use Admin\Model\AdminTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\StaticForm;
use Zend\Db\Adapter\Adapter;
use Zend\Session\Container;
use Zend\Db\Sql\Sql;

use Application\Model\AbstractModel;
use Admin\Model\UserModel;
use Zend\Mvc\Plugin\FlashMessenger;

class ProfileController extends AbstractActionController
{
	// Add this property:
	private $table,$AbstractModel,$UserModel,$Adapter;
	
    // Add this constructor:
	public function __construct(AbstractModel $AbstractModel,$adminMsgsession,$UserModel,$Adapter)
	{
		$this->UserModel = $UserModel;
		//$this->table = $table;
		$this->SuperModel = $AbstractModel;
		$this->adminMsgsession = $adminMsgsession;
		$this->Adapter = $Adapter;
		$session = new Container(ADMIN_AUTH_NAMESPACE);
		$this->adminuser = $session['adminData'];
    }
	public function readnotifyAction()
	{
		
		$this->SuperModel->Super_Insert("notifications",array('notification_read_status'=>'1'),"notification_user_id='".$this->adminuser['user_id']."'");
		exit;
	}
    public function indexAction()
    {
		$form = new StaticForm();
		$form->profile_admin();
		
		try {
           
			$admindata=$this->SuperModel->Super_Get("users","user_id='".$this->adminuser['user_id']."'","fetch");
			//prd($admindata);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('adminprofile', array('action' => 'index'));
        }
		
		if($admindata)
		{
			$form->populateValues($admindata);
		}
		
		if($this->getRequest()->isPost()){
			
 			$data_post = $this->getRequest()->getPost();
			$form->setData($data_post);
			
			if($form->isValid()){
				
				$data_to_update = $form->getData() ;
				//$data_to_update = $data_post;
				$imagePlugin = $this->Image();
					
				$files =  $this->getRequest()->getFiles()->toArray();
				
				//echo PROFILE_IMAGES_PATH;die;
				global $img_extension;
				$filename = $files['user_image']['name'];
				
				if($filename)
				{
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					
					if(!in_array($ext,explode(',',IMAGE_VALID_EXTENTIONS)))
					{
						$this->adminMsgsession['errorMsg']='Please Upload Valid Image File';
						return $this->redirect()->toRoute('adminprofile', array('action' => 'index'));			
					}
					else
					{
						$imagePlugin = $this->Image();
						//prd($files);
						$is_uploaded_icon = $imagePlugin->universal_upload(array("directory"=>PROFILE_IMAGES_PATH,"files_array"=>$files));		
											
						if(is_object($is_uploaded_icon)  and $is_uploaded_icon->success)
						{		 
							$data_to_update['user_image'] = $is_uploaded_icon->media_path;
						}							
					}
				}
				else
				{
					$data_to_update['user_image'] = $admindata['user_image'];
				}

				$id = $admindata['user_id'];
				$is_update=$this->SuperModel->Super_insert("users",$data_to_update,"user_id='".$id."'");
				$admin_data = $this->SuperModel->Super_Get("users","user_id='".$this->adminuser['user_id']."'","fetch");
				$session = new Container(ADMIN_AUTH_NAMESPACE);
				$session['adminData']=$admin_data;
				
				
				if(is_object($is_update) and $is_update->success){				
					$this->adminMsgsession['successMsg'] = " Profile Information Updated Successfully ";					
					return $this->redirect()->toRoute('adminprofile', array('action' => 'index'));					
				}				
				
			}else{				
				$adminSession->errorMsg = "Please Check Information Again ...!";	
			}
  		}
		
		//prd($admindata);
		// Pass form variable to view
        return new ViewModel(array(
            'form' => $form,
			'adminData' => $admindata,
			'pageHeading' =>'Account Setting'
        ));
    }
	
	public function changepasswordAction()
    {
		//echo 'if'; die;
		$form = new StaticForm();
		$form->password();
		
		try {
			$session = new Container(ADMIN_AUTH_NAMESPACE);
			$adminuser = $session['adminData'];
            //$admin_user = $this->table->getAdminUser();
			$admindata=(array)$adminuser;
			//prd($admindata);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('adminprofile', array('action' => 'index'));
        }
		
		if($this->getRequest()->isPost()){
 			
 			$data_post = $this->getRequest()->getPost();
			$form->setData($data_post);
			if($form->isValid()){
				$data_to_update = $form->getData() ;
				unset($data_to_update['user_old_password']);
				unset($data_to_update['user_rpassword']);
				$data_to_update['user_password'] = md5($data_to_update['user_password']);
				$id = $admindata['user_id'];
				$is_update=$this->SuperModel->Super_insert("users",$data_to_update,"user_id='".$id."'");
				if(is_object($is_update) and $is_update->success){
					
						$this->adminMsgsession['successMsg']="Password Changed Successfully";
					
						return $this->redirect()->toRoute('adminpassword', array('action' => 'changepassword'));
				}
				
			}else{
				
			}
  		}
		
		// Pass form variable to view
        return new ViewModel(array(
            'form' => $form,
			'adminData' => $admindata,
			'passwordBox' => 'yes',
        ));
    }
    
	public function checkoldpassAction(){

		$session = new Container(ADMIN_AUTH_NAMESPACE);
		$admin_user = $session['adminData'];
		
		if($admin_user){
			
			$admindata=(array)$admin_user;
		 	$user_id = $admindata['user_id'];	
			$user_password = md5($_REQUEST['user_password']);
			$user =$this->UserModel->getAdminUser($user_id);
			if($user['user_password']==$user_password){
				echo json_encode("Old and New password should not be same");
			}else{
				echo json_encode("true");	
			}
		}else{ 
			echo json_encode("true");	
		}
 		exit();
	}
	
	/* 	Ajax Call For Checking the Old Password for the Logged User */
	public function checkpasswordAction(){
		
			 $user_password = md5($_REQUEST['user_old_password']);
			 
			 $session = new Container(ADMIN_AUTH_NAMESPACE);
			 $admin_user = $session['adminData'];
		   
		 	 $admindata=(array)$admin_user;
			 $user_id = $admindata['user_id'];
			
 			$user =$this->UserModel->checkoldpassword($user_password,$user_id);
			
 			if(!$user){
				echo json_encode("Old Password Mismatch , Please Enter Correct old password");
			}else{
				echo json_encode("true");	
			}
 		exit();
	}
	
	public function checkemailAction(){
		$email_address = strtolower($_REQUEST['user_email']);
		$exclude = strtolower($_REQUEST['exclude']);
		
		$user_id = false ;
		if(!empty($exclude))
		{
			$session = new Container(ADMIN_AUTH_NAMESPACE);
			$adminuser = $session['adminData'];
			
			$admindata=(array)$adminuser;
			$user_id = $admindata['user_id'];
		}
		
		$email = $this->UserModel->checkEmail($email_address,$user_id);
		
		if($email)
			echo json_encode("`$email_address` already exists , please enter any other email address ");
		else
			echo json_encode("true");
		exit();
	}
	
	public function checkusernameAction(){
		$username = $this->params()->fromQuery('user_name');
		
		$rev = $this->params()->fromQuery('rev');
		$exclude = strtolower($this->params()->fromQuery('user'));
		
		$user_id = false ;
		if(!empty($exclude)){ 
			$userData = $this->SuperModel->Super_Get("users","user_id=".$exclude,'fetch');
			$user = $userData;
			$user_id =$userData['user_id'];
		}
		
		if(empty($user_id)){
			$user_id = $this->params()->fromQuery('user_id');
		}
		
		if($exclude)
		{
			$user = $userData;
			$user_id =$userData['user_id'];
			$name = $this->UserModel->checkUname($username,$user_id);
		}
		else{
			$name = $this->UserModel->checkUname($username);
		}
		
		if($name)
			echo json_encode("This username is already taken , please use any other username");
		else
			echo json_encode("true");
		exit();
	}
	
	public function checkotherusernameAction(){
		$username = $this->params()->fromQuery('user_name');
		$exclude = strtolower($this->params()->fromQuery('user'));
		
		$user_id = false ;
		if(!empty($exclude)){ 
			$userData = $this->SuperModel->Super_Get("users","user_id=".$exclude,'fetch');
			$user = $userData;
			$user_id =$userData['user_id'];
		}
		
		if(empty($user_id)){
			$user_id = $this->params()->fromQuery('user_id');
		}
		
		if($exclude)
		{
			$user = $userData;
			$user_id =$userData['user_id'];
			$name = $this->UserModel->checkUname($username,$user_id);
		}
		else{
			$name = $this->UserModel->checkUname($username);
		}
		
		if($name)
			echo json_encode("This username is already taken , please use any other username");
		else
			echo json_encode("true");
		exit();
	}
	
	public function checkotheremailAction(){
		$email_address = strtolower($_REQUEST['user_email']);
		$exclude = strtolower($this->params()->fromQuery('user'));
		
		$user_id = false;
		if(!empty($exclude))
		{
			$userData = $this->SuperModel->Super_Get("users","user_id='".$exclude."'",'fetch');
			$user = $userData;
			$user_id =$userData['user_id'];
		}
		
		$email = $this->UserModel->checkEmail($email_address,$user_id);
		
		if($email)
			echo json_encode("`$email_address` already exists , please enter any other email address ");
		else
			echo json_encode("true");
		exit();
	}
	
}