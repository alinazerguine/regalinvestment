<?php
namespace AuthAcl\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AuthAcl\Form\UserForm;
use AuthAcl\Form\Filter\LoginFilter;
use AuthAcl\Form\RegisterForm;
use AuthAcl\Form\Filter\RegisterFilter;
use AuthAcl\Form\ResetForm;
use AuthAcl\Form\ForgotForm;
use Zend\Session\Container;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;

use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class IndexController extends AbstractActionController
{
	private $AbstractModel,$Adapter,$UserModel,$EmailModel,$config_data,$authService;
	
	public function __construct($AbstractModel,$Adapter,$UserModel,$frontSession,$EmailModel,$config_data,$authService)
	{
		$authService->getAdapter()->setIdentityColumn('user_email');
    	$this->SuperModel = $AbstractModel;
		$this->Adapter = $Adapter;
		$this->UserModel = $UserModel;		
		$this->frontSession = $frontSession;
		$this->EmailModel = $EmailModel;
		$this->loggedUser = $authService->getIdentity();
		$this->authService = $authService;
		$this->site_configs = $config_data;
	}
	public function getinvestamountAction(){
			if($this->getRequest()->isPost()){
				$data = $this->getRequest()->getPost();
				if(isset($data['value']) && $data['value']!=''){
					$data['book_type'];$data['investing_reason'];
				}
			}
			echo 0;exit();	
	}
	
	public function getplanAction(){
			if($this->getRequest()->isPost()){
				$data = $this->getRequest()->getPost();
				
				if(isset($data['value']) && $data['value']!=''){
					$planData=$this->SuperModel->Super_Get("investment_plans","1","fetchall");
					$invest_type=$data["invest_type"];
					$invest_opt=$data["invest_opt"];
					$getplan=calculateplan($planData,$this->site_configs,$data['value'],$invest_type,$invest_opt,0);
					
					echo $getplan;exit();
				}
			}echo 0;exit();	
	}
	public function getpieAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$investmentamount=0;$investmentamount_years=$graphprojectionAmt_years=$industry_average_years=array();
			if(isset($data['book_type'])){
				$where_type='inv_res_per_resid="'.$data['book_type'].'"';
				if(isset($data['type']) && ($data['type']!=0 && $data['type']!='')){
					$where_type.=' and inv_res_per_resoptid="'.$data['type'].'"';
				}
				$invest_assets_year=2;
				if(isset($data['invest_assets_year']) && ($data['invest_assets_year']!=0 && $data['invest_assets_year']!='')){
					$invest_assets_year=$data['invest_assets_year'];
				}
				
				$value=$data['value'];
				
				//
				$invt_option=array();
				$investing_reason_pervalue=$this->SuperModel->Super_Get('investing_reason_pervalue',$where_type,"fetch",array("fields"=>array("inv_res_per_stocks","inv_res_per_warrenties","inv_res_per_forex","inv_res_per_futures","inv_res_per_options","inv_res_per_monthly","inv_res_per_returntype")));
				$graphprojectionAmt=$Industryaverage=0;
				$subInc=0;
				if(!empty($investing_reason_pervalue)){
					$inv_res_per_monthly=$investing_reason_pervalue['inv_res_per_monthly'];
					$inv_res_per_returntype=$investing_reason_pervalue['inv_res_per_returntype'];
					unset($investing_reason_pervalue['inv_res_per_monthly'],$investing_reason_pervalue['inv_res_per_returntype']);
					if($value > 0){
						
						
						if($data['book_type']==1 || $data['book_type']==3 || $data['book_type']==4 || $data['book_type']==5){ /*saving for retirement :not compunded */
							if($inv_res_per_returntype==1){//not compunded
								$monthlyinterest=round(($value*$inv_res_per_monthly)/100,2);
								$yearlyinterest=$monthlyinterest*12;
								$investmentamount=$yearlyinterest+$value;
							}else{
								//compunded
								for($i=1;$i<=12;$i++){
									$monthlyinterest=round(($value*$inv_res_per_monthly)/100,2);
									$value=$monthlyinterest+$value;
									
								}
								$investmentamount=$value;
							}
							
							
						}
						elseif($data['book_type']==2){
							$mastervalue=$value;//5000
							$propersperity=$value;
							$interestarray=array();
							$graphprojectionAmt=$value;
							$Industryaverage=0;
							$numberofmonth=24;
							if(isset($data['invest_assets_year']) && ($data['invest_assets_year']!=0 && $data['invest_assets_year']!='')){
								$numberofmonth=$data['invest_assets_year']*12;
							}
							for($i=1;$i<=$numberofmonth;$i++){
								
								$monthlyinterest=round(($value*$inv_res_per_monthly)/100,2);//900
								
								$interestarray[$i]=$monthlyinterest;//900
								$propersperity=$propersperity+$monthlyinterest;//900
								
								$re_investing=round(($monthlyinterest*70)/100,2);
								$value=$value+$re_investing;//5630
								$graphprojectionAmt=$graphprojectionAmt+ round(($graphprojectionAmt*7)/100,2);
								
								if($i%12==0 && $invest_assets_year >0){
									$investmentamount_years[]=$propersperity;
									$graphprojectionAmt_years[]=$graphprojectionAmt;
									
									$industry_average_years[]=round(($graphprojectionAmt*60)/100,2);
								}	
							}
							
							for($i=1;$i<=$numberofmonth;$i++){
								//
								//$indMain=round(($graphprojectionAmt*7)/100,2);
								//$Industryaverage=$Industryaverage+round(($indMain*60)/100,2);
							}
							//echo $Industryaverage;die;
							$investmentamount=$propersperity;
						}
						
						//echo 'inv_res_per_monthly--'.$inv_res_per_monthly; echo 'inv_res_per_returntype--'.$inv_res_per_returntype;die;
					}
					foreach($investing_reason_pervalue as $pkey=>$pValue){
						if($pValue!=0){
							$subData=explode('inv_res_per_',$pkey);
							if($subData[1]=='warrenties'){
								$subData[1]="warrants";
							}
							
							$invt_option[$subInc]['name']=ucfirst($subData[1]);
							$invt_option[$subInc]['y']=$pValue;
							
							$subInc++;
						}
					
					}
					$only_year_block='1';
					$years_block='1 year ';
					$RiskLevel='';
					$RiskText='';
					$joinarr=array(
						'0'=>array("0"=>"investing_risk","1"=>"inv_res_risk=inv_ris_id",'2'=>'Left',"3"=>array("inv_ris_text"))
					);
					$getinvesting_reasonData=$this->SuperModel->Super_Get('investing_reason','inv_res_id="'.$data['book_type'].'"',"fetch",array(),$joinarr);
					if(!empty($getinvesting_reasonData)){
						if($data['book_type']!='5'){
							
						$bind_year=$getinvesting_reasonData['inv_res_year'] ==1?"Year":"Years";
						$only_year_block=$getinvesting_reasonData['inv_res_year'];
						$years_block=$getinvesting_reasonData['inv_res_year'].' '.$bind_year;
						$RiskText=$getinvesting_reasonData['inv_ris_text'];
						$RiskLevel='Risk level: <span style="color:'.$getinvesting_reasonData['inv_res_color'].'" data-toggle="tooltip"  title="'.$RiskText.'">'.$getinvesting_reasonData['inv_res_risk'].'</span>';
						
						}else{
							
							if(isset($data['type']) && ($data['type']!=0 && $data['type']!='')){
							$joinarr=array(
								'0'=>array("0"=>"investing_risk","1"=>"inv_res_opt_risk=inv_ris_id",'2'=>'Left',"3"=>array("inv_ris_text"))
							);
							
							$getinvesting_reasonOptData=$this->SuperModel->Super_Get('investing_reason_option','inv_res_opt_id="'.$data['type'].'"',"fetch",array(),$joinarr);	
							
							if(!empty($getinvesting_reasonOptData)){
								$bind_year=$getinvesting_reasonOptData['inv_res_opt_restype'];
								$only_year_block=$getinvesting_reasonOptData['inv_res_year'];
								$years_block=$getinvesting_reasonOptData['inv_res_year'].' '.$bind_year;
								$RiskText=$getinvesting_reasonOptData['inv_ris_text'];
$RiskLevel='Risk level: <span style="color:'.$getinvesting_reasonOptData['inv_res_opt_color'].'" data-toggle="tooltip" title="'.$RiskText.'">'.$getinvesting_reasonOptData['inv_res_opt_risk'].'</span>';
							
								
							}
							}
						}
					}
					
					
					$invt_option[$subInc]['amount']=$investmentamount.' '.PRICE_SYMBOL_VALUE;
					$invt_option[$subInc]['px_amount']=$investmentamount;
					$invt_option[$subInc]['years_block']=$years_block;
					$invt_option[$subInc]['risk_level']=$RiskLevel;
					$invt_option[$subInc]['risk_text']=$RiskText;
					$invt_option[$subInc]['general_graph_years']=json_encode(array(round($only_year_block/2,2),round($only_year_block,2)));
					$invt_option[$subInc]['general_graph_amount']=json_encode(array(round($investmentamount/2,2),round($investmentamount,2)));
					$industry_average=round(($graphprojectionAmt*60)/100,2);
					$invt_option[$subInc]['general_graph_project_amount']=json_encode(array(round($graphprojectionAmt/2,2),round($graphprojectionAmt,2)));
					$invt_option[$subInc]['general_graph_industry_average']=json_encode(array(round($industry_average/2,2),round($industry_average,2)));
				
					//if(isset($data['invest_assets_year']) && ($data['invest_assets_year']!=0 && $data['invest_assets_year']!='')){
						if($invest_assets_year >0){                      
							$general_graph_years=array();
						for($inv_y=1; $inv_y <= $invest_assets_year;$inv_y++){
							$general_graph_years[]=	$inv_y;
						}
						$invt_option[$subInc]['general_graph_amount']=json_encode($investmentamount_years);
						$invt_option[$subInc]['general_graph_project_amount']=json_encode($graphprojectionAmt_years);
						
						$invt_option[$subInc]['general_graph_years']=json_encode($general_graph_years);
						$invt_option[$subInc]['general_graph_industry_average']=json_encode($industry_average_years);
					}
					
					echo json_encode($invt_option);exit();
				}
				echo 0;exit();
			}
		}
		echo 0;
		exit();
		
	}
	
	
	public function loginAction()
	{
		if($this->authService->hasIdentity()) {
			return $this->redirect()->tourl(APPLICATION_URL.'/profile');
		}
		
		
		
		$form = new UserForm();
		$form->login();
		$messageError = null;
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$authAdapter = $this->authService->getAdapter();
			
			
			$authAdapter->setIdentity($data['user_email']);
			$authAdapter->setCredential(md5($data['user_password']));
			
			$result = $this->authService->authenticate();
			
			if($result->isValid()){
				$data = $authAdapter->getResultRowObject();
				
				if($data->user_email_verified=='0'){
					$this->authService->clearIdentity();
					$this->frontSession['errorMsg'] = 'Please verify your email address';
					
				}
				else if($data->user_status=='0'){
					$this->authService->clearIdentity();
					$this->frontSession['errorMsg'] = 'Your account is blocked by administrator';
					
				}
				else{
					$this->authService->getStorage()->write($data);
					if(isset($_GET['url'])){
						 	return $this->redirect()->tourl(APPLICATION_URL.urldecode($_GET['url']));
						}
					return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
				}
			}
			else{
				$this->frontSession['errorMsg']='Invalid login details.';
				
			}
		}
		
		$this->layout()->setVariable('activePage','login');
		
		return new ViewModel(array(
			'form'			=>	$form,
			'messageError'	=>	$messageError,
			'pageHeading'	=>	"Log In",
			'site_configs'	=>	$this->site_configs,
			
		));
    }
	
	public function logoutAction(){
			
	    $this->SuperModel->Super_Insert("users",array("user_last_login"=>date("Y-m-d H:i:s")),"user_id='".$this->loggedUser->user_id."'");
		$this->authService->clearIdentity();
		//unset($_COOKIE['remember_me']);
		//$expire = time() - 300;
		//setcookie("remember_me", '', $expire,'/');
		 
		$this->frontSession['successMsg']='You Are Now Logged Out.';		
		return $this->redirect()->tourl(APPLICATION_URL);
        //return $this->redirect()->tourl(APPLICATION_URL.'/login');
    }
	
	public function resetpasswordAction(){
		
		if($this->authService->hasIdentity()) {
			return $this->redirect()->tourl(APPLICATION_URL.'/profile');
        }
		
		$key = $this->params()->fromRoute('key');
		$view = new ViewModel();
		
		$resetForm = new UserForm();
		$resetForm->resetpassword();
		
		$request = $this->getRequest();
		if(empty($key)){
			$this->frontSession['errorMsg']='Invalid Request For Reset Password!';	
			return $this->redirect()->toUrl(APPLICATION_URL);
		}
		
		$user_data =$this->SuperModel->Super_Get("users","pass_resetkey='".$key."'","fetch");
		if(!$user_data){			
			 $this->frontSession['errorMsg']='Invalid Request for Password Reset , Please try again.';	
			 return $this->redirect()->toUrl(APPLICATION_URL);
		}
		
	
		if ($request->isPost())
		{ 
			$data = $request->getPost();
			$resetForm->setData($data);
			
			if($resetForm->isValid())
			{
				$data_to_update = $resetForm->getData();
				if($data_to_update['user_password']==$data_to_update['user_rpassword']){
					unset($data_to_update['user_rpassword']);
					unset($data_to_update['submit']);
										
					$userDetails = $this->UserModel->resetPassword($data_to_update, $user_data);	
					//prd($userDetails);
					$this->frontSession['successMsg'] = 'Password Has Been Changed Successfully.';	
					return $this->redirect()->toUrl(APPLICATION_URL.'/login');
				}
				else{
					$this->frontSession['successMsg'] = 'Invalid Request. Password Mismatched.';	
					return $this->redirect()->toUrl(APPLICATION_URL.'reset-password/'.$key);
				}
			}
		}
		
		
		$page_content = array('page_title'=>'Reset Password');
		$view->setVariable('page_content',$page_content);
		$this->layout()->setVariable('pageHeading','Reset Password');
	 	$view->setVariable('resetForm', $resetForm);
		$view->setVariable('pageHeading','Reset Password');
		$view->setVariable('site_configs',$this->site_configs);
		
		return $view;
	}
	
	public function forgotpasswordAction(){
		if($this->authService->hasIdentity()) {
			return $this->redirect()->tourl(APPLICATION_URL.'/profile');
		}
		
		$homepageData = $this->SuperModel->Super_Get('home_content',1,'fetch');
		
		$form = new UserForm();
		$form->forgotForm();
		
		$view = new ViewModel();
		$request = $this->getRequest();
		
		if($request->isPost())
		{
			$data = $request->getPost();
			unset($data_to_update['submit']);
			
			$userDetails = $this->UserModel->forgotPassword($data);
			
			if($userDetails){
				$this->frontSession['successMsg'] = "Mail has been sent to your account to restore your password.";
				return $this->redirect()->toUrl(APPLICATION_URL.'/forgot-password');
			}
			else{
				$this->frontSession['errorMsg']="No email address exists on our system. Please check and re-enter.";
				return $this->redirect()->toUrl(APPLICATION_URL.'/forgot-password');
			}
			
			$this->frontSession['errorMsg'] = 'Please Check Information Again.';
			return $this->redirect()->toUrl(APPLICATION_URL.'/forgot-password');
		}
		
		$this->layout()->setVariable('activePage','forgotpassword');
		
		$view->setVariable('form', $form);
		$view->setVariable('pageHeading','Forgot Password');
		$view->setVariable('site_configs',$this->site_configs);
		$view->setVariable('homepageData',$homepageData);
		return $view;
	}
	
	public function registerAction()
	{
		$type = $this->params()->fromRoute('type');
		
		if($this->authService->hasIdentity()) {
			return $this->redirect()->tourl(APPLICATION_URL.'/profile');
    	}
		
		$all_investing_reason=$this->SuperModel->prepareselectoptionwhere("investing_reason","inv_res_id","inv_res_title","1");
		
		$all_investing_reason_option=$this->SuperModel->prepareselectoptionwhere("investing_reason_option","inv_res_opt_id","inv_res_opt_restype","1");
		$all_investing_type=$this->SuperModel->prepareselectoptionwhere("investing_type","inv_ty_id","inv_ty_title","1");
		$registerForm = new UserForm();
		$registerForm->user_register($all_investing_reason,$all_investing_reason_option,$all_investing_type);
		
		$view = new ViewModel();
		$dbAdapter = $this->Adapter;
		
		//$type = '';
		$request = $this->getRequest();	
		
		if($request->isPost())
		{
			$data = $request->getPost();
			$registerForm->setData($data);
			if($data['book_type']!=5){
				$registerForm->getInputFilter()->get("investing_reason")->setRequired(FALSE)->setAllowEmpty(TRUE);	
			}
			if($data['invest_opt']==0){
				$registerForm->getInputFilter()->get("invest_type")->setRequired(FALSE)->setAllowEmpty(TRUE);	
			}
			if($registerForm->isValid())
			{
			$data_to_insert = $registerForm->getData(); 
			
			$planData=$this->SuperModel->Super_Get("investment_plans","1","fetchall");
			$invest_type=$data_to_insert["invest_type"];
			$invest_opt=$data_to_insert["invest_opt"];
			$insert_getplan=calculateplan($planData,$this->site_configs,$data_to_insert['invest_assets'],$invest_type,$invest_opt,1);
			
			
			unset($data_to_insert['bttnsubmit']);
			
			$data_to_insert['user_name']		= strip_tags($data['user_first_name'].' '.$data['user_last_name']);
			$data_to_insert['user_first_name']	= strip_tags($data['user_first_name']);
			$data_to_insert['user_last_name']	= strip_tags($data['user_last_name']);
			$data_to_insert['user_email']		= strip_tags($data['user_email']);
			$data_to_insert['user_password']	= $data['user_password'];
			$data_to_insert['user_type'] = $type="user";
			$data_to_insert['user_password'] = md5($data_to_insert['user_password']);
			
			if(empty($data_to_insert['user_name'])){
				if(empty($data_to_insert['user_email'])){
					if(empty($data_to_insert['user_password'])){
						$this->frontSession['errorMsg'] = "Enter your password to continue with registration process";
						return $this->redirect()->toUrl('register');
					}
					$this->frontSession['errorMsg'] = "Enter your email to continue with registration process";
					return $this->redirect()->toUrl('register');
				}
				$this->frontSession['errorMsg'] = "Enter your username to continue with registration process";
				return $this->redirect()->toUrl('register');
			}
			
			$checkEmail = $this->SuperModel->Super_Get('users','user_email="'.$data_to_insert["user_email"].'"','fetch');
			
			if(!empty($checkEmail)){
				$this->frontSession['errorMsg'] = "The email '".$data_to_insert["user_name"]."' is already registered in our database, click on forgot password link if you have lost your password.";
				return $this->redirect()->toUrl('register');
			}
			$book_sub=array("book_type","investing_reason","invest_opt","invest_type","invest_assets");
			
			foreach($book_sub as $bk=>$bvalue){
				$data_to_insert['user_'.$bvalue]=$data_to_insert[$bvalue];
				unset($data_to_insert[$bvalue]);
			}
			unset($data_to_insert['user_rpassword']);
			if($data_to_insert['user_invest_type']==''){
				unset($data_to_insert['user_invest_type']);
			}
			
			$isInsert=$this->UserModel->add($data_to_insert);
			
			if($isInsert->success){
				if($insert_getplan!=''){
					$in_planList=explode(" + ",$insert_getplan);	
					if(!empty($in_planList)){
						foreach($in_planList as $ins_key=>$ins_value){
							$getExpDate='0000-00-00';
							if(!empty($planData)){
								foreach($planData as $mpKey=>$mpValue){
									if($mpValue['inv_pl_id']==$ins_value && $mpValue['inv_pl_type']=='1'){
										$getExpDate=date('Y-m-d', strtotime('+'.$mpValue['inv_pl_duration'].' months'));
										break;	
									}
								}	
							}
							$this->SuperModel->Super_Insert("users_plan",array("user_plan_planid"=>$ins_value,'user_plan_userid'=>$isInsert->inserted_id,"user_plan_ptype"=>"0","user_plan_date"=>date("Y-m-d"),"user_plan_isactive"=>"1","user_plan_expiredate"=>$getExpDate));
						}	
					}
				}
				
				$this->frontSession['successMsg']='Registration Done Successfully, Activation Email Has Been Sent To Your Registered Email Address.';
				return $this->redirect()->toUrl('login');
			}
			else{
				$this->frontSession['errorMsg']="Some error occurred";
				return $this->redirect()->toUrl('register');
			}
			
			}else{
				
				$this->frontSession['errorMsg']="Please check your information.";
				return $this->redirect()->toUrl('register');
			}
		}
		
		$this->layout()->setVariable('activePage','signup');
		
		$view->setVariable('site_configs',$this->site_configs);
		$view->setVariable('form',$registerForm);
		$view->setVariable('type',$type);
		$view->setVariable('all_investing_reason',$all_investing_reason);
		$view->setVariable('all_investing_reason_option',$all_investing_reason_option);
		return $view;
	}
	
	public function activateAction(){
		$key = $this->params()->fromRoute('key'); 
		
		if($key==''){
			$this->frontSession['errorMsg']='Invalid Request, Please try again.';	
			return $this->redirect()->toUrl(APPLICATION_URL.'/login');
		}
		$user_info =$this->SuperModel->Super_Get('users',"pass_resetkey = '".$key."'",'fetch');
		
		if(empty($user_info)){
			$this->frontSession['errorMsg']='Invalid Request, Please try again.';	
			return $this->redirect()->toUrl(APPLICATION_URL.'/login');
		}
		
		$data_to_update = array();
		$data_to_update['pass_resetkey'] = '';
		$data_to_update['user_reset_status'] = 0;
		$data_to_update['user_email_verified'] = 1;
		$data_to_update['user_status'] = 1;
		
		$user_update =$this->SuperModel->Super_Insert('users',$data_to_update,"user_id = '".$user_info['user_id']."'");
		
		$this->frontSession['successMsg']='Your Account Has Been Activated Successfully.';	
		return $this->redirect()->toUrl(APPLICATION_URL.'/login');
	}
	
	public function checkemailAction()
	{
		$email_address = $this->params()->fromQuery('user_email');
		$rev = $this->params()->fromQuery('rev');
		$exclude = strtolower($this->params()->fromQuery('exclude'));
		
		$user_id = false ;
		if(!empty($exclude)){ 
			$Front_User_Session = new Container(DEFAULT_AUTH_NAMESPACE);					
			$logged_user = $Front_User_Session['loggedUser'];
			
			$user = $logged_user;
			$user_id =$logged_user['user_id'];
		}
		
		if(empty($user_id)){
			$user_id = $this->params()->fromQuery('user_id');
		}
		
		if($exclude)
		{
			/*$Front_User_Session = new Container(DEFAULT_AUTH_NAMESPACE);					
			$logged_user=$Front_User_Session['loggedUser'];*/
			
			$user = $this->loggedUser;
			$user_id =$this->loggedUser->user_id;			
			$email = $this->UserModel->checkEmail($email_address,$user_id);
		}
		else
		{
			$email = $this->UserModel->checkEmail($email_address);
		}
		
		if($email)
			echo json_encode("`$email_address`"."already exists , please enter any other email address");
		else
			echo json_encode("true");
		exit();
	}
	
	public function checkusernameAction()
	{
		$username = $this->params()->fromQuery('user_name');
		$rev = $this->params()->fromQuery('rev');
		$exclude = strtolower($this->params()->fromQuery('exclude'));
		
		$user_id = false ;
		if(!empty($exclude)){ 
			$Front_User_Session = new Container(DEFAULT_AUTH_NAMESPACE);
			$logged_user = $Front_User_Session['loggedUser'];
			
			$user = $logged_user;
			$user_id =$logged_user['user_id'];
		}
		
		if(empty($user_id)){
			$user_id = $this->params()->fromQuery('user_id');
		}
		
		if($exclude)
		{
			/*$Front_User_Session = new Container(DEFAULT_AUTH_NAMESPACE);					
			$logged_user=$Front_User_Session['loggedUser'];*/
			
			$user = $this->loggedUser;
			$user_id =$this->loggedUser->user_id;			
			$name = $this->UserModel->checkUname($username,$user_id);
		}
		else
		{
			$name = $this->UserModel->checkUname($username);
		}
		
		if($name)
			echo json_encode("This username is already taken , please use any other username");
		else
			echo json_encode("true");
		exit();
	}
	
	public function checkforgotemailAction()
	{
		$email_address = $this->params()->fromQuery('user_email');
		$email = $this->UserModel->checkEmail($email_address);
		
		if(!$email)
			echo json_encode("`$email_address`"." does not exists , please enter again");
		else
			echo json_encode("true");
		exit();
	}
	
	public function checkcaptchAction(){
		$captcha_code=$this->params()->fromQuery('user_captcha');
		if(empty($_SESSION['captcha'] ) || strcasecmp($_SESSION['captcha'], $captcha_code) != 0){  
			echo json_encode(("The Validation code does not match"));	
		}else{  // Captcha verification is Correct. Final Code Execute here!	 	
			echo json_encode("true");	
		}
 		exit();

	}	
}