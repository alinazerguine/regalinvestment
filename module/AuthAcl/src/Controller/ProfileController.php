<?php
namespace AuthAcl\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AuthAcl\Form;
use Zend\Session\Container;

use AuthAcl\Form\UserForm;
use AuthAcl\Form\ProfileForm;
use AuthAcl\Form\ChangePasswordForm;
use AuthAcl\Form\ImageForm;

use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;



class ProfileController extends AbstractActionController
{
    private $AbstractModel,$Adapter,$UserModel,$EmailModel,$config_data,$authService;
    public function __construct($AbstractModel,$Adapter,$UserModel,$frontSession,$EmailModel,$config_data,$authService)
    {
	
        $this->SuperModel = $AbstractModel;
		$this->Adapter = $Adapter;
		$this->UserModel = $UserModel;		
		$this->frontSession = $frontSession;
		$this->EmailModel = $EmailModel;
		$this->loggedUser = $authService->getIdentity();
		$this->SITE_CONFIG= $config_data;
		
		
    }
	
	public function removeresourceAction(){
		
		
		
		$res_id = $this->params()->fromRoute('type');
		if($res_id==''){exit();}
		$res_id=myurl_decode($res_id);
		if($res_id==''){exit();}
		
		$isDeleted=$this->SuperModel->Super_Delete("users_resource","user_res_id='".$res_id."' and user_res_userid='".$this->loggedUser->user_id."'");
		if(is_object($isDeleted) && $isDeleted->success){
			$this->frontSession['successMsg']='Your resource has been removed.';	
		}else{
			$this->frontSession['errorsMsg']='Please check information again.';
		}
		return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
	}
		
		
		
	public function removeplanAction(){
		
		
		$plan_id = $this->params()->fromRoute('type');
		if($plan_id==''){exit();}
		$plan_id=myurl_decode($plan_id);
		if($plan_id==''){exit();}
		
		$isDeleted=$this->SuperModel->Super_Delete("users_plan","user_plan_id='".$plan_id."' and user_plan_userid='".$this->loggedUser->user_id."'");
		if(is_object($isDeleted) && $isDeleted->success){
			$this->frontSession['successMsg']='Your plan has been removed.';	
		}else{
			$this->frontSession['errorsMsg']='Please check information again.';
		}
		return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
	}
	
	public function dashboardAction(){
		$page = $this->params()->fromRoute('page',1);
		$up_planData=$this->SuperModel->Super_Get("investment_plans","1","fetchall");
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		$joinarr=array(
				'0'=>array("0"=>"investment_plans","1"=>"user_plan_planid=inv_pl_id",'2'=>'Left',"3"=>array("inv_pl_maxcount",'inv_pl_type','inv_pl_title'))
		);
		
		//user_plan_isactive='1' || 
		$countofPlanArray=$this->SuperModel->Super_Get("users_plan","user_plan_userid='".$this->loggedUser->user_id."' and (user_plan_isactive='3')","fetchall",array("fields"=>array("count_plan"=>new Expression("count(user_plan_planid)"),"user_plan_planid"),"group"=>"user_plan_planid"),$joinarr);
		$notCount_Title_array=array();
		$notCount_array=array();
		if(!empty($countofPlanArray)){
			foreach($countofPlanArray as $cnt_k=>$cnt_val){
				if($cnt_val['inv_pl_type']!='0' && $cnt_val['inv_pl_maxcount'] <=$cnt_val['count_plan']){
					$notCount_array[]=$cnt_val["user_plan_planid"];
					$notCount_Title_array[]=$cnt_val["inv_pl_title"];
				}	
			}
		}
		
		$joinarr=array(
				'0'=>array("0"=>"investment_plans","1"=>"user_plan_planid=inv_pl_id",'2'=>'Left',"3"=>array("inv_pl_title"))
		);
		$getPlanData=$this->SuperModel->Super_Get("users_plan","user_plan_userid='".$this->loggedUser->user_id."' and (user_plan_isactive='0' ||user_plan_isactive='1')","fetchall",array(),$joinarr);
		
		
		$existPlanData=$this->SuperModel->Super_Get("users_plan","user_plan_userid='".$this->loggedUser->user_id."' and (user_plan_isactive='0' ||user_plan_isactive='1')","fetch",array("fields"=>array("plan_ids"=>new Expression("group_concat(user_plan_planid)"))));
	//	prd($existPlanData);
		$inv_where=$res_where='1';
		if($existPlanData['plan_ids']!=''){
			$inv_where='inv_pl_id NOT IN('.$existPlanData['plan_ids'].')'	;
		}
		if(!empty($notCount_array)){
			$nt_str=implode(",",$notCount_array);
			$inv_where.=' and inv_pl_id NOT IN('.$nt_str.')'	;
		}
		//echo $inv_where;die;
		$planData=$this->SuperModel->prepareselectoptionwhere("investment_plans","inv_pl_id","inv_pl_title",$inv_where);
		//prd($planData);
		$form = new ProfileForm();
		$form->dashboard();
		
		$form1 = new ProfileForm();
		$form1->planmodel($planData);
		
		
		$joinResarr=array(
				'0'=>array("0"=>"resource","1"=>"user_res_resid=res_id",'2'=>'Left',"3"=>array("res_title","res_desc"))
		);
		$getResourceData=$this->SuperModel->Super_Get("users_resource","user_res_userid='".$this->loggedUser->user_id."' and (user_res_isactive='0' ||user_res_isactive='1')","fetchall",array("order"=>"user_res_id desc"),$joinResarr);
		$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($getResourceData));
		$paginator->setCurrentPageNumber($page); 
		$paginator->setItemCountPerPage(RECORD_PER_PAGE);//
		
		
		$existResourceData=$this->SuperModel->Super_Get("users_resource","user_res_userid='".$this->loggedUser->user_id."' and (user_res_isactive='0' ||user_res_isactive='1')","fetch",array("fields"=>array("res_ids"=>new Expression("group_concat(user_res_resid)"))));
		if($existResourceData['res_ids']!=''){
			$res_where='res_id NOT IN('.$existResourceData['res_ids'].')'	;
		}
		
		$resourcesData=$this->SuperModel->prepareselectoptionwhere("resource","res_id","res_title",$res_where);
		$form2=new ProfileForm();
		$form2->resources($resourcesData);
		
		$form->populateValues($userData);
		$request = $this->getRequest();			
		if ($request->isPost())
		{ 
			$formObj=$form;
			$data = $request->getPost();
		
			if(isset($data['plan_ids'])){
				$formObj=$form1;
				//prD($data['plan_ids']);
			}elseif(isset($data['res_id'])){
				$formObj=$form2;
			}
			$formObj->setData($data);	
			if($formObj->isValid())
			{ 
				$data_to_update = $formObj->getData();		
				if(isset($data['plan_ids'])){
					/* plan work */
						$isAva=$this->SuperModel->Super_Get("users_plan","user_plan_planid='".$data['plan_ids']."' and user_plan_userid='".$this->loggedUser->user_id."' and user_plan_isactive='0'");
						
						if(empty($isAva)){
							$getExpDate='0000-00-00';
							if(!empty($up_planData)){
								foreach($up_planData as $mpKey=>$mpValue){
									if($mpValue['inv_pl_id']==$data['plan_ids'] && $mpValue['inv_pl_type']=='1'){
										$getExpDate=date('Y-m-d', strtotime('+'.$mpValue['inv_pl_duration'].' months'));
										break;	
									}
								}	
							}
							
						$isUpdated=$this->SuperModel->Super_Insert("users_plan",array("user_plan_planid"=>$data['plan_ids'],'user_plan_userid'=>$this->loggedUser->user_id,'user_plan_ptype'=>"1","user_plan_date"=>date("Y-m-d"),"user_plan_isactive"=>"0","user_plan_expiredate"=>$getExpDate));
						if(is_object($isUpdated) && $isUpdated->success){
							/* send mail to admin*/
							
							$is_updated_plan=$this->SuperModel->Super_Insert("notifications",array("notification_type"=>"0","notification_user_id"=>"1","notification_by_user_id"=>$this->loggedUser->user_id,"notification_date"=>date("Y-m-d H:i:s"),"notification_plan_id"=>$isUpdated->inserted_id));
							
;							$emaildata=array("user_name"=>$this->loggedUser->user_name);
							$this->EmailModel->sendEmail("admin_plan", $emaildata);
							$this->frontSession['successMsg']='Your request has been received. Your plan will become active after it is approved.';
							return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
						}
						}
				}
				elseif(isset($data['res_id'])){
					
					
					/* resource work */
						$isAva=$this->SuperModel->Super_Get("users_resource","user_res_resid='".$data['res_id']."' and user_res_userid='".$this->loggedUser->user_id."' and user_res_isactive='0'");
						
						if(empty($isAva)){
							
							
							
						$isUpdated=$this->SuperModel->Super_Insert("users_resource",array("user_res_resid"=>$data['res_id'],'user_res_userid'=>$this->loggedUser->user_id,"user_res_date"=>date("Y-m-d"),"user_res_isactive"=>"0"));
						if(is_object($isUpdated) && $isUpdated->success){
							/* send mail to admin*/
							
							$is_updated_plan=$this->SuperModel->Super_Insert("notifications",array("notification_type"=>"10","notification_user_id"=>"1","notification_by_user_id"=>$this->loggedUser->user_id,"notification_date"=>date("Y-m-d H:i:s"),"notification_resource_id"=>$isUpdated->inserted_id));
							
;							$emaildata=array("user_name"=>$this->loggedUser->user_name);
							$this->EmailModel->sendEmail("admin_resource", $emaildata);
							$this->frontSession['successMsg']='Your request has been received.Your resource will be available after it is approved.';
							return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
						}
						}
				
				}
				else{
					/* dahboard work*/				
					if(strtolower($data_to_update['user_dashboard_txt'])!=strtolower($userData['user_dashboard_txt'])){
					$isUpdated=$this->SuperModel->Super_Insert("users",array("user_dashboard_txt"=>$data_to_update['user_dashboard_txt'],'user_request_addedon'=>date("Y-m-d H:i:s")),"user_id='".$this->loggedUser->user_id."'");
						if(is_object($isUpdated) && $isUpdated->success){
							$this->frontSession['successMsg']='Information is updated.';
							return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
						}
					}else{
							return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
					}
				}
				//prd($data_to_update);
			}
			 else
		 	 {
			  $this->frontSession['errorsMsg']='Please check information again.';
			 // return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
		 	 }
		}
		
		
		$weekname1="Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec";
		$weekday=explode(",",$weekname1);
		$depositData=$withdraw_Data='';
		$year=date("Y");//$year=2017;
		for($i=0;$i<=count($weekday)-1;$i++)
		{
			$val = $i+1;
			/*$year=date("Y");*/
			$dep_where='user_bal_userid='.$this->loggedUser->user_id.' and user_bal_type="0" and EXTRACT(MONTH FROM user_bal_addedon)="'.$val.'" and EXTRACT(YEAR FROM user_bal_addedon)="'.$year.'"';
			$with_where='user_bal_userid='.$this->loggedUser->user_id.' and user_bal_type="1" and EXTRACT(MONTH FROM user_bal_addedon)="'.$val.'" and EXTRACT(YEAR FROM user_bal_addedon)="'.$year.'"';
			$weekname.="'".$weekday[$i]."',"; 
			$c_array=$this->fetchgraph($dep_where,$with_where);
			
			$depositData.=(int)$c_array['depositData'].",";
			$withdraw_Data.=(int)$c_array['withdrawData'].",";
		}
		
		$viewPostArray=array('pageType'=>'front_dashboard','pageHeading'=>'Dashboard','loggedUser'=>$this->loggedUser,'userData'=>$userData,'SITE_CONFIG'=>$this->SITE_CONFIG,'SuperModel'=>$this->SuperModel,'pageIcon'=>'fa fa-tachometer','form'=>$form,'form1'=>$form1,'getPlanData'=>$getPlanData,'notCount_Title_array'=>$notCount_Title_array,'tot_Prosperity'=>$userData['user_prosperity'],'depositData'=>rtrim($depositData,','),'withdraw_Data'=>rtrim($withdraw_Data,','),'weekname1'=>$weekname1,'year'=>$year,'form2'=>$form2,'paginator'=>$paginator);
		
		$view = new ViewModel($viewPostArray);
		return $view;
	}
	function fetchgraph($where1,$where2)
	{
		
		$Al_depositData=$this->SuperModel->Super_Get('users_balance',$where1,'fetch',array('fields'=>array('deposit'=>new Expression('sum(user_bal_balance)'))));
		$Al_withdrawData=$this->SuperModel->Super_Get('users_balance',$where2,'fetch',array('fields'=>array('withdraw'=>new Expression('sum(user_bal_balance)'))));
		$array['depositData']=$Al_depositData['deposit'];
		$array['withdrawData']=$Al_withdrawData['withdraw'];
		return $array; 
	}
	
	public function readnotifyAction()
	{
		
		$this->SuperModel->Super_Insert("notifications",array('notification_read_status'=>'1'),"notification_user_id='".$this->loggedUser->user_id."'");
		exit;
	}
	public function indexAction()
    {
		$view = new ViewModel();	
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		$form = new ProfileForm();
		/* User */
		$form->user();
		
		$form1 = new UserForm(); 
		$form1->image();
		$form->populateValues($userData);
		
			
		$request = $this->getRequest();			
		if ($request->isPost())
		{ 
			$data = $request->getPost();
			$form->setData($data);	
			$files =  $request->getFiles()->toArray();	

			if($form->isValid())
			{ 
				$data_to_update = $form->getData();	
				$data_to_update['user_name']=$data_to_update['user_first_name'].' '.$data_to_update['user_last_name'];
				
				unset($data_to_update['bttnsubmit']);
				$property_type=array();
				if(isset($data_to_update['user_proptype'])){
						$property_type=$data_to_update['user_proptype'];
						unset($data_to_update['user_proptype']);	
				}
				if($files['user_image']['name']!=''){
				
				$imagePlugin = $this->Image();
				$is_uploaded=$imagePlugin->universal_upload(array("directory"=>PROFILE_IMAGES_PATH,"files_array"=>$files,"url"=>HTTP_PROFILE_IMAGES_PATH));	
				
				if($is_uploaded->media_path!=''){
					$data_to_update['user_image'] = $is_uploaded->media_path;
					
					$ext = getFileExtension($data_to_update['user_image']);
				
					if($ext == 'gif' or $ext == 'GIF'){
						copy(PROFILE_IMAGES_PATH."/".$data_to_update['user_image'],PROFILE_IMAGES_PATH."/60/".$data_to_update['user_image']);
						copy(PROFILE_IMAGES_PATH."/".$data_to_update['user_image'],PROFILE_IMAGES_PATH."/160/".$data_to_update['user_image']);
						copy(PROFILE_IMAGES_PATH."/".$data_to_update['user_image'],PROFILE_IMAGES_PATH."/thumb/".$data_to_update['user_image']);
					}
				}
			}else{
				$data_to_update['user_image'] = $this->loggedUser->user_image;
				}
				
				$isUpdate=$this->UserModel->add($data_to_update,$this->loggedUser->user_id);
				
				
				
				
				
				$this->frontSession['successMsg']='Profile Updated Successfully.';
				return $this->redirect()->tourl(APPLICATION_URL.'/profile');
		  }
		  else
		  {
			  $this->frontSession['errorsMsg']='Please check information again.';
			  return $this->redirect()->tourl(APPLICATION_URL.'/profile');
		  }
		}
		
		$view->setVariable('form', $form);
		$view->setVariable('form1', $form1);
		$view->setVariable('show', 'front_profile');	
		$view->setVariable('pageHeading','Profile');
		$view->setVariable('pageIcon','fa fa-heart-o');	
		$view->setVariable('pageType','account');	
		$view->setTemplate('auth-acl/profile/index.phtml');
		$view->setVariable('loggedUser',$this->loggedUser);
		$view->setVariable('userData',$userData);
		$view->setVariable('SITE_CONFIG',$this->SITE_CONFIG);
		$view->setVariable('SuperModel',$this->SuperModel);	
		return $view;
	}
	

	/* 	Ajax Call For Checking the Old Password for the Logged User  */
	public function checkpasswordAction(){
		
		if(isset($this->loggedUser) && !empty($this->loggedUser)){
			
			$user_old_password=$this->params()->fromQuery('user_old_password');
			$user_password = md5($user_old_password);
		
			$user = $this->SuperModel->Super_Get('users',"user_id='".$this->loggedUser->user_id."' and user_password='".$user_password."'","fetch");
		
			if(!$user){
				echo json_encode("Old password mismatch, Please enter correct old password");
			}else{
				echo json_encode("true");	
			}
		}else{
			echo json_encode($this->view->translate("Please login to make changes."));
		}
 		exit();
	}
	
	
	public function compareoldpasswordAction()
	{
		if(isset($this->loggedUser) && !empty($this->loggedUser)){
			
			$user_password=$this->params()->fromQuery('user_password');
			$user_password = md5($user_password);
		
			$user = $this->SuperModel->Super_Get('users',"user_id='".$this->loggedUser->user_id."' and user_password='".$user_password."'","fetch");
		
			if($user){
				echo json_encode("New password shall not be the same with the old one");
			}else{
				echo json_encode("true");	
			}
		}else{
			echo json_encode($this->view->translate("Please login to make changes."));
		}
 		exit();
	}
	
	/*public function cropimageAction()
    { 
		$form = new UserForm();
		$form->image();
		$view = new ViewModel();	

		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		
		$request = $this->getRequest();			
		if($request->isPost()){
			
			$posted_data = $this->getRequest()->getPost();
			$files =  $request->getFiles()->toArray();	
			
			if($files['user_image']['name']!=''){
				
				$imagePlugin = $this->Image();
				$is_uploaded=$imagePlugin->universal_upload(array("directory"=>PROFILE_IMAGES_PATH,"files_array"=>$files,"url"=>HTTP_PROFILE_IMAGES_PATH));	
				
				if($is_uploaded->media_path!=''){
					$data_to_insert['user_image'] = $is_uploaded->media_path;
					
					$ext = getFileExtension($data_to_insert['user_image']);
				
					if($ext == 'gif' or $ext == 'GIF'){
						copy(PROFILE_IMAGES_PATH."/".$data_to_insert['user_image'],PROFILE_IMAGES_PATH."/60/".$data_to_insert['user_image']);
						copy(PROFILE_IMAGES_PATH."/".$data_to_insert['user_image'],PROFILE_IMAGES_PATH."/160/".$data_to_insert['user_image']);
						copy(PROFILE_IMAGES_PATH."/".$data_to_insert['user_image'],PROFILE_IMAGES_PATH."/thumb/".$data_to_insert['user_image']);
					}
				}
			}else{
				$data_to_insert['user_image'] = $this->loggedUser->user_image;
			}
			
			// Rotate
			if(false){
			switch($posted_data['rotate'])
			{
			   case 0:
			   $rotate = 0;
			   break;
			   
			   case 1:
			   $rotate = -90;
			   break;
			   
			   case 2:
			   $rotate = -180;
			   break;
			   
			   case 3:
			   $rotate = -270;
			   break;
			}
			
			$source = imagecreatefromjpeg(PROFILE_IMAGES_PATH."/".$data_to_insert['user_image']);
			$rotate_image = imagerotate($source, $rotate, 0);
		   
			imagejpeg($rotate_image,PROFILE_IMAGES_PATH.'/'.$this->loggedUser->user_id."-".$data_to_insert['user_image']);
			unlink(PROFILE_IMAGES_PATH.'/'.$data_to_insert['user_image']);
			
			
			$source = imagecreatefromjpeg(PROFILE_IMAGES_PATH."/60/".$data_to_insert['user_image']);
			$rotate_image = imagerotate($source, $rotate, 0);
			
			imagejpeg($rotate_image,PROFILE_IMAGES_PATH.'/60/'.$this->loggedUser->user_id."-".$data_to_insert['user_image']);
			unlink(PROFILE_IMAGES_PATH.'/60/'.$data_to_insert['user_image']);
			
			
			$source = imagecreatefromjpeg(PROFILE_IMAGES_PATH."/160/".$data_to_insert['user_image']);
			$rotate_image = imagerotate($source, $rotate, 0);
			
			imagejpeg($rotate_image,PROFILE_IMAGES_PATH.'/160/'.$this->loggedUser->user_id."-".$data_to_insert['user_image']);
			unlink(PROFILE_IMAGES_PATH.'/160/'.$data_to_insert['user_image']);
			
			
			$source = imagecreatefromjpeg(PROFILE_IMAGES_PATH."/thumb/".$data_to_insert['user_image']);
			$rotate_image = imagerotate($source, $rotate, 0);
			
			imagejpeg($rotate_image,PROFILE_IMAGES_PATH.'/thumb/'.$this->loggedUser->user_id."-".$data_to_insert['user_image']);
			unlink(PROFILE_IMAGES_PATH.'/thumb/'.$data_to_insert['user_image']);
			}
			//$data_to_insert["user_image"] = $this->loggedUser->user_id."-".$data_to_insert['user_image'];
			
			$isUpdate=$this->UserModel->add($data_to_insert,$this->loggedUser->user_id); 
			
			$this->frontSession['successMsg']='Image uploaded successfully.';
			return $this->redirect()->tourl(APPLICATION_URL.'/profile'); 
		}
		
		
		$view->setVariable('show','front_profile');
		
		$view->setVariable('pageHeading','Change Image');	
		$view->setVariable('userData',$userData);	
		$view->setVariable('form',$form);
		$view->setVariable('pageType','change_image');	
		$view->setVariable('loggedUser',$this->loggedUser);	
		
			$view->setVariable('SuperModel',$this->SuperModel);	
		return $view;	
 	}*/
 
	public function changepasswordAction(){
		
		
		if($this->loggedUser->user_oauth_provider=='facebook' || $this->loggedUser->user_oauth_provider=='twitter' || $this->loggedUser->user_oauth_provider=='googleplus'){
		return $this->redirect()->tourl(APPLICATION_URL.'/profile'); 
		
		}
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		$view = new ViewModel();	
		$form = new Form\ChangePasswordForm('changepassword');
		
		$request = $this->getRequest();
        if ($request->isPost()) 
		{
			$data = $request->getPost();
			$form->setData($data);
			
			
            if ($form->isValid()) 
			{
				$data_to_update = $form->getData();
				
				if($data_to_update['user_password'] == $data_to_update['user_rpassword']){
					unset($data_to_update['btnsubmit']);
					$password = md5($data_to_update['user_old_password']);
					
					$isCheck = $this->SuperModel->Super_Get('users',"user_id='".$this->loggedUser->user_id."' and user_password='".$password."'","fetch");
					
					if($isCheck){
						$dataToUpdate['user_password'] = md5($data_to_update['user_password']);
						$dataToUpdate['user_pass'] = encodeText($data_to_update['user_password']);
						
						$isUpdate=$this->SuperModel->Super_Insert("users",$dataToUpdate,"user_id='".$this->loggedUser->user_id."'");	
						
						if($isUpdate->success){
							$this->frontSession['successMsg']='Password Has Been Changed Successfully.';
						    return $this->redirect()->tourl(APPLICATION_URL.'/change-password');
						}
					}
				}
			}
			else
			{				
				$this->frontSession['errorMsg']='Please Check Information Again.';
				return $this->redirect()->tourl(APPLICATION_URL.'/change-password');
			}
		}
		
	

		$view->setVariable('show','change_password');
		$view->setVariable('form',$form);
		$view->setVariable('pageIcon','fa fa-lock');	
		$view->setVariable('pageType','change_password');	
		$view->setVariable('pageHeading','Password');
		$view->setVariable('loggedUser',$this->loggedUser);	
		$view->setVariable('userData',$userData);
		$view->setVariable('SuperModel',$this->SuperModel);	
		
		return $view;	
	}

	public function getcitiesAction()
	{
		$cityData=$this->SuperModel->Super_Get("cities","country_id='".$_REQUEST['value']."'","fetchAll",array('group'=>'city_id'),array('0'=>array('0'=>'states','1'=>'city_state_id=state_id','2'=>'full','3'=>'name'),'1'=>array('0'=>'countries','1'=>'state_country_id=country_id','2'=>'full','3'=>array('country_name'))));
		print_r(json_encode($cityData));
		exit();
	}
	
	
	
	public function sendconsultrequestAction(){
		
		
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		
		$getConsultSettingData = $this->SuperModel->Super_Get("consulting_available_time_setting","1","fetch");
		 
		
		$form = new ProfileForm();
		$form->sendConsultRequest();
		
		$form->populateValues($userData);
		
		$request = $this->getRequest();			
		if ($request->isPost())
		{ 
			$formObj=$form;
			$data = $request->getPost();
						
			$formObj->setData($data);	
			if($formObj->isValid())
			{
				$getFormData = $formObj->getData();
				unset($getFormData['bttnsubmit']);
				
				$startEndTime = explode('_',$data['start_end_time_slot']);
				$timeSlotFlag=false;
				
				// To check Time slot is valid or Not
				$AvailableTimeSlotResponse = $this->UserModel->getAvailableTimeSlot(date('Y-m-d',strtotime($data['consult_request_date'])));
				//prd($AvailableTimeSlotResponse);
				foreach($AvailableTimeSlotResponse as $values){
					if($values['startTime']==$startEndTime[0] &&  $values['EndTime']==$startEndTime[1]){
						$timeSlotFlag=true;
					}
				}
				 
				if($timeSlotFlag==true){
					
					$getFormData['consult_user_id']=$this->loggedUser->user_id;	
					$getFormData['consult_request_date']=date('Y-m-d',strtotime($getFormData['consult_request_date']));
					$getFormData['consult_start_time']=$startEndTime[0];
					$getFormData['consult_end_time']=$startEndTime[1];
					$getFormData['consult_time_slot_type']=$getConsultSettingData['time_slots'];
					$getFormData['consult_req_added_on']=date("Y-m-d H:i:s");
				 
					$isInserted=$this->SuperModel->Super_Insert("consulting_send_request",$getFormData);
					if(is_object($isInserted) && $isInserted->success){
				
						$is_Send_Notification = $this->SuperModel->Super_Insert("notifications",array("notification_type"=>"4","notification_user_id"=>"1","notification_by_user_id"=>$this->loggedUser->user_id,"notification_date"=>date("Y-m-d H:i:s"),"notification_consult_id"=>$isInserted->inserted_id));
						
						if(is_object($is_Send_Notification) && $is_Send_Notification->success){
							$emaildata=array("user_name"=>$this->loggedUser->user_name);
							$is_Send_Email= $this->EmailModel->sendEmail("admin_consulting_request", $emaildata);
							
							$this->frontSession['successMsg']='Your request is sent to admin.';
							return $this->redirect()->tourl(APPLICATION_URL.'/send-consult-request');	
						}
					}
					
				}else{
					$this->frontSession['errorsMsg']='Please select valid available time slot';
					return $this->redirect()->tourl(APPLICATION_URL.'/send-consult-request');
				}
			}
			 else
		 	 {
			  $this->frontSession['errorsMsg']='Please check information again.';
			 // return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
		 	 }
		}
		
		$viewPostArray=array('pageType'=>'consulting_request','pageHeading'=>'Consulting Request','loggedUser'=>$this->loggedUser,'userData'=>$userData,'SITE_CONFIG'=>$this->SITE_CONFIG,'pageIcon'=>'fa fa-paper-plane','form'=>$form,'getConsultSettingData'=>$getConsultSettingData);
		
		$view = new ViewModel($viewPostArray);
		return $view;
	}
	
	
	public function preparetimeslotsAction(){
		$timeSlotArray = array();
		
		$SelectedDate =   date('Y-m-d',strtotime($this->params()->fromPost('selectedDate')));
		
		
		$AvailableTimeSlotResponse = $this->UserModel->getAvailableTimeSlot($SelectedDate);
		
		echo json_encode($AvailableTimeSlotResponse);exit;
		
		 
		/*if(!empty($SelectedDateAllRequest)){
			$searchAndSkipTimp = array();
			foreach($SelectedDateAllRequest as $key=>$values){
				$skipTime='';
				$skipTime = date('H:i',strtotime($values['consult_start_time']));
				$searchAndSkipTimp[$skipTime].=$skipTime;
			}
			
			prd($searchAndSkipTimp);
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
				 
				$skipSerachTimeRes= array_search($prevStartTime,$searchAndSkipTimp);

				if(empty($skipSerachTimeRes)){ 
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
			
			
		}else{
			//pr($getConsultingReqSetting);
			
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
				 
				$timeSlotArray[$count]['startTime']=$prevStartTime;
				$timeSlotArray[$count]['EndTime']=$EndTime;
				
				if($count==1){
					$prevStartTime=date('H:i', $startTime + $getSlotData['slotSecondes']);
				}else{
					$prevStartTime = date('H:i', $startTime + $i);
				}
				$count++;
			}
			//prd($timeSlotArray);
			//echo date('H:i', $startTime + $i).'<br>';
		}*/
		
		//echo json_encode($timeSlotArray);exit;
	}
	
	 
}