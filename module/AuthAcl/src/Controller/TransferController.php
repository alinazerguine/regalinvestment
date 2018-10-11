<?php
namespace AuthAcl\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AuthAcl\Form;
use Zend\Session\Container;
use AuthAcl\Form\TransferForm;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class TransferController extends AbstractActionController
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
	
	public function depositAction(){
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		$instrucation=$this->SuperModel->Super_Get("pages","page_type='1' and LOWER(page_slug) IN('wire_transfer_confirmation','interac_e_transfer','check_confirmation')","fetchall");
		if(!empty($instrucation)){
			foreach($instrucation as $ins_key=>$ins_Value){
					$instrucation[$ins_key]['page_content']=str_ireplace(array("{Customer name}"),array($this->loggedUser->user_name),$ins_Value['page_content']);
			}	
		}
		
		$form = new TransferForm();
		$form->deposit();
		$request = $this->getRequest();			
		if ($request->isPost())
		{
			$data = $request->getPost();
			$form->setData($data);
			if($form->isValid())
			{ 
				$data_to_update = $form->getData();	
				$user_transferData=array("user_trans_userid"=>$this->loggedUser->user_id,"user_trans_amount"=>$data_to_update['deposit_amount'],"user_trans_currency"=>$data_to_update["deposit_currency"],"user_trans_method"=>$data_to_update["deposit_method"],"user_trans_date"=>date("Y-m-d H:i:s"));
				$is_transferred=$this->SuperModel->Super_Insert("user_transfer",$user_transferData);
				if(is_object($is_transferred) && $is_transferred->success){
					//$this->frontSession['successMsg']='Your plan has been deleted';	
			$is_updated_transfer=$this->SuperModel->Super_Insert("notifications",array("notification_type"=>"5","notification_user_id"=>"1","notification_by_user_id"=>$this->loggedUser->user_id,"notification_date"=>date("Y-m-d H:i:s"),"notification_trans_id"=>$is_transferred->inserted_id));
							
;					$emaildata=array("user_name"=>$this->loggedUser->user_name,"deposit_amount"=>$data_to_update['deposit_amount'],"deposit_currency"=>$data_to_update["deposit_currency"],"deposit_method"=>$data_to_update["deposit_method"]);
					$this->EmailModel->sendEmail("admin_deposit_amount", $emaildata); 
					
					return $this->redirect()->tourl(APPLICATION_URL.'/confirmdeposit/'.myurl_encode($is_transferred->inserted_id));
				}
				else{
					$this->frontSession['errorMsg']="Some error occurred";
				}
			} 
			else
		  	{
			  $this->frontSession['errorsMsg']='Please check information again.';
			  
		  	}
			
		}
		
		
		
		
		$viewPostArray=array('pageType'=>'deposit','pageHeading'=>'Deposit','loggedUser'=>$this->loggedUser,'userData'=>$userData,'SITE_CONFIG'=>$this->SITE_CONFIG,'pageIcon'=>'fa fa-money','form'=>$form,'instrucation_list'=>$instrucation);
		
		$view = new ViewModel($viewPostArray);
		return $view;
		
	}
	public function confirmdepositAction(){
		$deposit=$this->params()->fromRoute('deposit');
		
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		
		if($deposit==''){return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');}
		$deposit=myurl_decode($deposit);//
		$depositData = $this->SuperModel->Super_Get("user_transfer","user_trans_userid='".$this->loggedUser->user_id."' and user_trans_type='0' and user_trans_id='".$deposit."' and user_trans_note='0'","fetch");
		
		
		if(empty($depositData)){return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');}
		/* get instruction data */
		/*$page_slug='check';
		if($depositData['user_trans_method']=='1'){
			$page_slug='Wire_Transfer_Confirmation';	
		}elseif($depositData['user_trans_method']=='3'){
			$page_slug='Interact_E-Transfer_Confirmation';	
		}*/
		$page_slug='confirmation_deposit';
		global $currencyCode;
		
		
		$getPageData=$this->SuperModel->Super_Get("pages","page_type='1' and LOWER(page_slug)='".strtolower($page_slug)."'","fetch");
		
		if(empty($getPageData)){return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');}
		/* update deposit tbale data */
		$pay_currency=$currencyCode[$depositData["user_trans_currency"]];
		$pay_amount=removedecimal($depositData["user_trans_amount"]);
		$getPageData = str_ireplace(array("{pay_currency}","{pay_amount}","{Customer name}"),array($pay_currency,$pay_amount,$this->loggedUser->user_first_name.' '.$this->loggedUser->user_last_name),$getPageData);
		
		$this->SuperModel->Super_Insert("user_transfer",array("user_trans_note"=>"1"),"user_trans_id='".$deposit."'");
		$viewPostArray=array('pageType'=>'deposit','pageHeading'=>'Deposit','loggedUser'=>$this->loggedUser,'userData'=>$userData,'SITE_CONFIG'=>$this->SITE_CONFIG,'pageIcon'=>'fa fa-money','getPageData'=>$getPageData);
		
		$view = new ViewModel($viewPostArray);
		return $view;
		
	}
	
	
	
	public function withdrawAction(){
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		$form = new TransferForm();
		$form->withdraw();
		$request = $this->getRequest();	
				
		if ($request->isPost())
		{
			$data = $request->getPost();
			$form->setData($data);
			if($form->isValid())
			{ 
				$data_to_update = $form->getData();	
				
				if($data_to_update['withdraw_amount'] > $userData['user_balance']){$this->frontSession['errorMsg']="Insufficient Balance!";}else{
				$user_transferData=array("user_trans_userid"=>$this->loggedUser->user_id,"user_trans_amount"=>$data_to_update['withdraw_amount'],"user_trans_currency"=>$data_to_update["withdraw_currency"],"user_trans_method"=>$data_to_update["withdraw_method"],"user_trans_note"=>"1","user_trans_type"=>"1","user_trans_date"=>date("Y-m-d H:i:s"));
				
				$is_transferred=$this->SuperModel->Super_Insert("user_transfer",$user_transferData);
				if(is_object($is_transferred) && $is_transferred->success){
					//$this->frontSession['successMsg']='Your plan has been deleted';	
					
			$is_updated_transfer=$this->SuperModel->Super_Insert("notifications",array("notification_type"=>"6","notification_user_id"=>"1","notification_by_user_id"=>$this->loggedUser->user_id,"notification_date"=>date("Y-m-d H:i:s"),"notification_trans_id"=>$is_transferred->inserted_id));
							
;					$emaildata=array("user_name"=>$this->loggedUser->user_name,"withdraw_amount"=>$data_to_update['withdraw_amount'],"withdraw_currency"=>$data_to_update["withdraw_currency"],"withdraw_method"=>$data_to_update["withdraw_method"]);
					$this->EmailModel->sendEmail("admin_withdraw_amount", $emaildata); 
					$this->frontSession['successMsg']='Your withdrawal request has been received.';	
					return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');
				}
				else{
					$this->frontSession['errorMsg']="Some error occurred";
				}
				}
				
			} 
			else
		  	{
			  $this->frontSession['errorsMsg']='Please check information again.';
			  
		  	}
			
		}
		$viewPostArray=array('pageType'=>'withdraw','pageHeading'=>'Withdraw','loggedUser'=>$this->loggedUser,'userData'=>$userData,'SITE_CONFIG'=>$this->SITE_CONFIG,'pageIcon'=>'fa fa-money','form'=>$form);
		
		$view = new ViewModel($viewPostArray);
		return $view;
		
	}
	 public function withdrawdepositAction(){
		
		$deposit=$this->params()->fromRoute('deposit');
		
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		
		if($deposit==''){return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');}
		$deposit=myurl_decode($deposit);//and user_trans_note='0' 
		$depositData = $this->SuperModel->Super_Get("user_transfer","user_trans_userid='".$this->loggedUser->user_id."' and user_trans_type='1' and user_trans_id='".$deposit."'","fetch");
		if(empty($depositData)){return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');}
		/* get instruction data */
		$page_slug='check_withdraw';
		if($depositData['user_trans_method']=='1'){
			$page_slug='Wire_Transfer_Confirmation_withdraw';	
		}elseif($depositData['user_trans_method']=='2'){
			$page_slug='interac_e_transfer_withdraw';	
		}
		global $currencyCode;
		
		
		$getPageData=$this->SuperModel->Super_Get("pages","page_type='1' and page_slug='".$page_slug."'","fetch");
		if(empty($getPageData)){return $this->redirect()->tourl(APPLICATION_URL.'/dashboard');}
		/* update deposit tbale data */
		$pay_currency=$currencyCode[$depositData["user_trans_currency"]];
		$pay_amount=removedecimal($depositData["user_trans_amount"]);
		$getPageData = str_ireplace(array("{pay_currency}","{pay_amount}","{Customer name}"),array($pay_currency,$pay_amount,$this->loggedUser->user_first_name.' '.$this->loggedUser->user_last_name),$getPageData);
		
		$this->SuperModel->Super_Insert("user_transfer",array("user_trans_note"=>"1"),"user_trans_id='".$deposit."'");
		$viewPostArray=array('pageType'=>'withdraw','pageHeading'=>'Withdraw','loggedUser'=>$this->loggedUser,'userData'=>$userData,'SITE_CONFIG'=>$this->SITE_CONFIG,'pageIcon'=>'fa fa-money','getPageData'=>$getPageData);
		
		$view = new ViewModel($viewPostArray);
		return $view;
	}
	
	public function portfolioAction(){
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		$page = $this->params()->fromRoute('page',1);
		$transx = $this->params()->fromRoute('transx');
		$getwhere="user_bal_userid='".$this->loggedUser->user_id."'";
		if($transx!=''){ 
			$transx=myurl_decode($transx);
			$getwhere.=' and user_bal_id="'.$transx.'"';
			
		}
		$joinarr=array('0'=>array("0"=>"user_transfer","1"=>"user_bal_trans_id=user_trans_id",'2'=>'Left',"3"=>array("user_trans_id","user_trans_userid","user_trans_amount","user_trans_currency","user_trans_method","user_trans_type","user_trans_note","user_trans_verify","user_trans_date")));
		
		//user_plan_isactive='1' || 
		$transferData=$this->SuperModel->Super_Get("users_balance",$getwhere,"fetchall",
		array("order"=>"user_bal_id desc"),$joinarr);
		$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($transferData));
		$paginator->setCurrentPageNumber($page); 
		$paginator->setItemCountPerPage(RECORD_PER_PAGE);//
		$viewPostArray=array('pageType'=>'portfolio','pageHeading'=>'Portfolio','loggedUser'=>$this->loggedUser,'userData'=>$userData,'SITE_CONFIG'=>$this->SITE_CONFIG,'pageIcon'=>'fa fa-briefcase','paginator'=>$paginator);
		$view = new ViewModel($viewPostArray);
		return $view;
	}
	public function pendingtransactionAction(){
		$userData = $this->SuperModel->Super_Get("users","user_id='".$this->loggedUser->user_id."'","fetch");
		$page = $this->params()->fromRoute('page',1);
		
		
		
		
		$joinarr=array('0'=>array("0"=>"user_transfer","1"=>"user_bal_trans_id=user_trans_id",'2'=>'Left',"3"=>array("user_trans_id","user_trans_userid","user_trans_amount","user_trans_currency","user_trans_method","user_trans_type","user_trans_note","user_trans_verify","user_trans_date")));
		
		//user_plan_isactive='1' || 
		$trans_where="(user_trans_userid='".$this->loggedUser->user_id."') and NOT EXISTS (SELECT * FROM `users_balance` AS b WHERE b.user_bal_trans_id = `table`.user_trans_id) and user_trans_verify='0'";
		$transferData=$this->SuperModel->Super_Get("user_transfer",$trans_where,"fetchall",
		array("order"=>"user_trans_id desc"));
		
		$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($transferData));
		$paginator->setCurrentPageNumber($page); 
		$paginator->setItemCountPerPage(5);//
		$viewPostArray=array('pageType'=>'portfolio','pageHeading'=>'Portfolio','loggedUser'=>$this->loggedUser,'userData'=>$userData,'SITE_CONFIG'=>$this->SITE_CONFIG,'pageIcon'=>'fa fa-briefcase','paginator'=>$paginator);
		$view = new ViewModel($viewPostArray);
		return $view;
	}
	
}