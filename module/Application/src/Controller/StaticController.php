<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Application\Model\AbstractModel;
use Application\Form\StaticForm;
use Zend\Session\Container;
use Zend\Db\Sql\Expression;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;
/**
 * This is the main controller class of the Form Demo application. The 
 * controller class is used to receive user input, 
 * pass the data to the models and pass the results returned by models to the 
 * view for rendering.
 */
class StaticController extends AbstractActionController 
{
    /**
     * Mail sender.
     * @var Application\Service\MailSender
     */
    private $AbstractModel,$EmailModel,$FrontMsgsession,$front_Session,$authService;
    
    /**
     * Constructor.
     */
	public function __construct($AbstractModel,$EmailModel,$FrontMsgsession,$front_Session,$config_data,$authService)  
	{
		$authService->getAdapter()->setIdentityColumn('user_email');
		$this->EmailModel = $EmailModel;
		$this->SuperModel = $AbstractModel;
		$this->front_Session = $front_Session;
		$this->FrontMsgsession = $FrontMsgsession;
		$Front_User_Session = new Container(DEFAULT_AUTH_NAMESPACE);
		
		$this->loggedUser = $authService->getIdentity();
		$this->authService = $authService;
		$this->SITE_CONFIG = $config_data;
	}
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * Home page.
     */
	
	public function termsAction()
	{ 
		$page_content=$this->SuperModel->Super_Get("pages","page_id='8'","fetch");
		$page_content['page_content']=str_ireplace(array('{last_updated}','{img_url}','{site_path}'),array(date("d F, Y ",strtotime($page_content['page_updated'])),HTTP_IMG_PATH,APPLICATION_URL),$page_content['page_content']);
		
		
		
		$this->layout()->setVariable('page_content',$page_content);
		
		
		$view = new ViewModel();
		$this->layout()->setVariable('pageTag','terms');
		$this->layout()->setVariable('pageHeading',$page_content['page_title']);
		$view->setTemplate('application/static/index.phtml');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		
			$this->layout()->setVariable('pageHeading',$page_content['page_title']);
		
		return $view;
    }
	
	
	public function faqAction() 
	{
		$page_content=$this->SuperModel->Super_Get("pages","page_id='10'","fetch");
		$faqData=$this->SuperModel->Super_Get("faq","1","fetchAll",array('order'=>'faq_order asc'));
		
		
		
		
		$view = new ViewModel();
		$this->layout()->setVariable('pageTag','faq');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('faqData',$faqData);
		$view->setVariable('page_content',$page_content);
			$this->layout()->setVariable('pageHeading',$page_content['page_title']);
		return $view;
	}
	public function getfaqsAction(){
		if($this->getRequest()->isPost()){
			$postedData =  $this->params()->fromPost();
			
			if(!empty($postedData["num"])){
				$faq_ids=myurl_decode($postedData["num"]);	
				if($faq_ids!=''){
					$faqData=$this->SuperModel->Super_Get("faq","faq_id='".$faq_ids."'","fetch");
					$view = new ViewModel();
					$view->setVariable('faqData',$faqData);
					return $view;
					//prd($faqData);
				}
			}
		}else{
			echo 0;exit();
		}
	}
	
	public function securityAction(){
		$page_content=$this->SuperModel->Super_Get("pages","page_id='12'","fetch");
		$this->layout()->setVariable('page_content',$page_content);
		$page_content['page_content']=str_ireplace(array('{last_updated}','{img_url}','{site_path}'),array(date("d F, Y ",strtotime($page_content['page_updated'])),HTTP_IMG_PATH,APPLICATION_URL),$page_content['page_content']);
		
		$this->layout()->setVariable('pageTag','security');
		
		$this->layout()->setVariable('page_content',$page_content);
		$view = new ViewModel();
		$view->setTemplate('application/static/index.phtml');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		$view->setVariable('Members',$Members);
		$view->setVariable('site_config',$this->SITE_CONFIG);
		
		$this->layout()->setVariable('pageHeading',$page_content['page_title']);
		
		return $view;
	}
	public function missionAction()
	{
		$page_content=$this->SuperModel->Super_Get("pages","page_id='11'","fetch");
		$this->layout()->setVariable('page_content',$page_content);
		$page_content['page_content']=str_ireplace(array('{last_updated}','{img_url}','{site_path}'),array(date("d F, Y ",strtotime($page_content['page_updated'])),HTTP_IMG_PATH,APPLICATION_URL),$page_content['page_content']);
		
		
		$this->layout()->setVariable('pageTag','mission');
		$this->layout()->setVariable('page_content',$page_content);
		$view = new ViewModel();
		$view->setTemplate('application/static/index.phtml');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		$view->setVariable('Members',$Members);
		$view->setVariable('site_config',$this->SITE_CONFIG);
		
		$this->layout()->setVariable('pageHeading',$page_content['page_title']);
		
		return $view;
	}
	public function careerAction()
	{	
	
		$page = $this->params()->fromRoute('page',1);
		$jobData=$this->SuperModel->Super_Get("job","1","fetchall",array("order"=>"job_added_date desc"));
		
		$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($jobData));
		$paginator->setCurrentPageNumber($page); 
		$paginator->setItemCountPerPage(RECORD_PER_PAGE);
		
		$this->layout()->setVariable('pageHeading','Career');
		$this->layout()->setVariable('pageTag','career');
		
		$view = new ViewModel();
		
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		$view->setVariable('paginator',$paginator);
		return $view;
		
	}
	
	public function jobdetailAction(){
		$job = $this->params()->fromRoute('job');$this->layout()->setVariable('pageTag','career');
		if($job==''){return $this->redirect()->tourl(APPLICATION_URL);}
		$job=myurl_decode($job); 
		$getjobData=$this->SuperModel->Super_Get("job","job_id='".$job."'","fetch");
		if(empty($getjobData)){return $this->redirect()->tourl(APPLICATION_URL);}
		
		$form = new StaticForm();
		$form->jobapplyform();
		 $request = $this->getRequest();
		if($this->getRequest()->isPost()){
			$postedData =  $this->params()->fromPost(); 
			 $form->setData($request->getPost());
			if($form->isValid()){
				 $Formdata = $form->getData();
				
				 $files =  $request->getFiles()->toArray();
				$file_name_data='';
				 if(!empty($files)){ 
				 	$imagePlugin = $this->Image();
					$is_uploaded=$imagePlugin->universal_upload(array("directory"=>APPLY_IMAGES_PATH,"files_array"=>$files,"multiple"=>"0"));
					if(is_object($is_uploaded) && $is_uploaded->success){
						$file_name_data=$is_uploaded->media_path;
						$Formdata["job_app_file"]=$file_name_data;
					}
					unset($Formdata["bttnsubmit"]);
					$Formdata['job_app_appliedon']=date("Y-m-d H:i:s");
					$Formdata['job_app_jobid']=$job;
					$is_job_apply=$this->SuperModel->Super_Insert("job_apply",$Formdata);
					if(is_object($is_job_apply) && $is_job_apply->success){
						$is_updated_transfer=$this->SuperModel->Super_Insert("notifications",array("notification_type"=>"7","notification_user_id"=>"1","notification_date"=>date("Y-m-d H:i:s"),"notification_job_app_id"=>$is_job_apply->inserted_id));
						
						$this->EmailModel->sendEmail('admin_job_apply',$postedData);
						
						$this->front_Session['successMsg']="We Thank you for submitting your request. Our staff will get in touch with you shortly";				return $this->redirect()->tourl(APPLICATION_URL.'/career');
					}else{
						 $this->front_Session['errorMsg']="Some error occurred";	
					}
				 }
			}else{
				
				
				$this->front_Session['errorMsg']='Please check information again.';
			}
		}
		$view = new ViewModel();
		$view->setVariable('pageHeading',"Job Detail");
		$view->setVariable('getjobData',$getjobData);
		$view->setVariable('form',$form);
		return $view;
	}
	public function resourcesAction(){
		
		if(empty($this->loggedUser)) {
			return $this->redirect()->tourl(APPLICATION_URL.'/login?url=/resources');
		}
		$page_content=$this->SuperModel->Super_Get("pages","page_id='22'","fetch");
		$page_content['page_content']=str_ireplace(array('{date_var}'),array(date("d, F, Y",strtotime($page_content['page_updated']))),$page_content['page_content']);
		
		$this->layout()->setVariable('pageHeading','Resources');
		$this->layout()->setVariable('pageTag','resources');
		
		$view = new ViewModel();
		$view->setTemplate('application/static/index.phtml');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		//$view->setVariable('resources',$resources);
		return $view;
		
	}
	
	
	public function overviewAction()
	{
		
		
		$page_content=$this->SuperModel->Super_Get("pages","page_id='3'","fetch");
		$this->layout()->setVariable('page_content',$page_content);
		
		$this->layout()->setVariable('pageTag','overview');
		$page_content['page_content']=str_ireplace(array('{last_updated}','{img_url}','{site_path}'),array(date("d F, Y ",strtotime($page_content['page_updated'])),HTTP_IMG_PATH,APPLICATION_URL),$page_content['page_content']);
		
		
		
		$this->layout()->setVariable('page_content',$page_content);
		$view = new ViewModel();
		$view->setTemplate('application/static/index.phtml');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		$view->setVariable('Members',$Members);
		$view->setVariable('site_config',$this->SITE_CONFIG);
		$view->setVariable('jobData',$jobData);
		
		$this->layout()->setVariable('pageHeading',$page_content['page_title']);
		
		return $view;
	}
	
	public function howitworksAction(){
		$page_content=$this->SuperModel->Super_Get("pages","page_id='10'","fetch");
		
		$this->layout()->setVariable('pageHeading','About Us');
		$this->layout()->setVariable('pageActive','howitworks');
		
		$view = new ViewModel();
		$view->setTemplate('application/static/index.phtml');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		return $view;
    }
	
	public function privacyAction()
	{ 
		$page_content=$this->SuperModel->Super_Get("pages","page_id='7'","fetch");
		$page_content['page_content']=str_ireplace(array('{date_var}'),array(date("d, F, Y",strtotime($page_content['page_updated']))),$page_content['page_content']);
		
		$this->layout()->setVariable('page_content',$page_content);
		$this->layout()->setVariable('pageActive','privacy');
		
		$view = new ViewModel();
		$view->setTemplate('application/static/index.phtml');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		
			$this->layout()->setVariable('pageHeading',$page_content['page_title']);
		
		return $view;
    }
	
	public function blessingsAction(){
		$page_content=$this->SuperModel->Super_Get("pages","page_id='14'","fetch");
		$page_content['page_content']=str_ireplace(array('{last_updated}','{img_url}','{site_path}'),array(date("d F, Y ",strtotime($page_content['page_updated'])),HTTP_IMG_PATH,APPLICATION_URL),$page_content['page_content']);
	
		$this->layout()->setVariable('page_content',$page_content);
		$this->layout()->setVariable('pageTag','blessings');
		
		if($this->getRequest()->isPost()){
			
			$postedData =  $this->params()->fromPost();
			if(trim($postedData['pw'])==trim($this->SITE_CONFIG['secure_password'])){
				unset($_COOKIE['password_isvalid']);
				  setcookie('password_isvalid', null, -1, '/');
				setcookie('password_isvalid', '1', 0); // 86400 = 1 day
				return $this->redirect()->tourl('blessings');
				
			}else{
				 $this->front_Session['errorMsg']="Invalid Password";
				unset($_COOKIE['password_isvalid']);
				setcookie('password_isvalid', null, -1, '/');
			}
		}
		
		$view = new ViewModel();
		$view->setTemplate('application/static/index.phtml');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		$this->layout()->setVariable('pageHeading',$page_content['page_title']);
		
		return $view;
    }
	
	public function servicesAction(){
		$page_content=$this->SuperModel->Super_Get("pages","page_id='13'","fetch");
		$page_content['page_content']=str_ireplace(array('{last_updated}','{img_url}','{site_path}'),array(date("d F, Y ",strtotime($page_content['page_updated'])),HTTP_IMG_PATH,APPLICATION_URL),$page_content['page_content']);
		
		$this->layout()->setVariable('page_content',$page_content);
		$this->layout()->setVariable('pageTag','services');
		
		$view = new ViewModel();
		$view->setTemplate('application/static/index.phtml');
		$view->setVariable('pageHeading',$page_content['page_title']);
		$view->setVariable('page_content',$page_content);
		$this->layout()->setVariable('pageHeading',$page_content['page_title']);
		
		return $view;
    }
	
	public function contactAction() 
	{
		
		// Create Contact Us form
		$form = new StaticForm();
		$form->contact();
		$page_content=$this->SuperModel->Super_Get("pages","page_id='2'","fetch");
		$this->layout()->setVariable('pageTag','contact');
		
		$secret = "6LcmLDcUAAAAAGT2tzowvbwj5Ix6Fs2cBv_6WfE7";
		// empty response
		$response = null;
		// check secret key
		
		if($this->getRequest()->isPost()){
			require_once ROOT_PATH.'/vendor/recaptchalib.php';	
			$postedData =  $this->params()->fromPost();
			
			if(!empty($postedData['user_name'])){
				if(filter_var($postedData['user_email'], FILTER_VALIDATE_EMAIL)) {
					if (!empty($postedData["g-recaptcha-response"])) {
					$reCaptcha = new\ReCaptcha($secret);
					$response = $reCaptcha->verifyResponse(
						$_SERVER["REMOTE_ADDR"],
						$_POST["g-recaptcha-response"]
					);
					
					if (is_array($response) || $response->success) {
						$this->EmailModel->sendEmail('admin_copy_for_contact_us',$postedData);
						$this->front_Session['successMsg']="Thank you for submitting your request. Our team will be in touch with you shortly.";							                         return $this->redirect()->tourl('contact');
					}
					}else {
					  $this->front_Session['errorMsg']="Invalid captcha";
				  }
				}
				else{
					$this->front_Session['errorMsg']="Please fill your completely valid contact information to submit request.";
					//return $this->redirect()->tourl('contact');
				}
			}
			else{
				$this->front_Session['errorMsg']="Please fill your complete contact information to submit contact request.";
				//return $this->redirect()->tourl('contact');
			}
		}
		
		// Pass form variable to view
		 return new ViewModel([
            'form' => $form,
			'page_content'=>$page_content,
			'secret'=>$secret,
			'SITE_CONFIG'=>$this->SITE_CONFIG,
			'pageHeading'=>$page_content['page_title']
        ]);
		
	}
	
	public function teamAction(){
		$teamData = $this->SuperModel->Super_Get("team","1","fetchAll");
		$this->layout()->setVariable('pageActive','team');
		$view = new ViewModel();
		$view->setVariable('pageHeading',"Team Members");
		$view->setVariable('teamData',$teamData);
		$this->layout()->setVariable('pageHeading','Team members');
		
		return $view;
	} 
	
	public function pricingAction() 
	{
	
		$page_content=$this->SuperModel->Super_Get("pages","page_id='16'","fetch");
		$this->layout()->setVariable('pageTag','pricing');
			$page_content['page_content']=str_ireplace(array('{last_updated}','{img_url}','{site_path}'),array(date("d F, Y ",strtotime($page_content['page_updated'])),HTTP_IMG_PATH,APPLICATION_URL),$page_content['page_content']);
		// Pass form variable to view
		 return new ViewModel([
			'page_content'=>$page_content,
			'pageHeading'=>$page_content['page_title']
        ]);
		
	}
}
