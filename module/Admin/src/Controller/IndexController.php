<?php
namespace Admin\Controller;

use Admin\Model\AdminTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Admin\Form\StaticForm;
use Admin\Form\ProfileForm;
use Zend\Session\Container;
use Admin\Model\Admin;
use Admin\Model\User;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

	   
class IndexController extends AbstractActionController
{
    // Add this property:
	private $AbstractModel,$Adapter,$UserModel,$EmailModel;
    public function __construct($AbstractModel,Adapter $Adapter,User $UserModel,$EmailModel,$adminMsgsession)
    {
        $this->SuperModel = $AbstractModel;
		$this->Adapter = $Adapter;
		$this->UserModel = $UserModel;
		$this->EmailModel = $EmailModel;
		$this->adminMsgsession = $adminMsgsession;
		$session = new Container(ADMIN_AUTH_NAMESPACE);
		$this->adminData=$session['adminData'];
    }
	
	public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $session = $container->get(SessionContainer::class);
        $db = $container->get(DbAdapter::class);
	}
   
    public function indexAction()
    {
	
		$dbAdapter = $this->Adapter;
		
		$session = new Container(ADMIN_AUTH_NAMESPACE);
		
		if(isset($session['adminData']) && !empty($session['adminData'])){
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/dashboard');
		}
		else
		{
		return $this->redirect()->toRoute('adminlogin', 
                        array('action'=>'/adminlogin'));
		}

    }
	
	public function errorpageAction()
    {
		
    }
	
	 public function pagesAction()
    {
		 return new ViewModel();
    }
	public function getpagesAction(){
  			
		$dbAdapter = $this->Adapter;
  
		$aColumns = array('page_id','page_content','page_updated','page_title');

		$sIndexColumn = 'page_id';
		$sTable = 'pages';
  		
		/*Table Setting*/{
		
		/* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".intval( $_GET['iDisplayLength'] );
		}
		
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= "".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$sWhere = "";
		if ( isset($_GET['sSearch']) and $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= "LOWER(".$aColumns[$i].") LIKE '%".strtolower(trim(addslashes($_GET["sSearch"])))."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) and $_GET['bSearchable_'.$i] == "true" and $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= "".$aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
		}
		
		
		}/* End Table Setting */
		
		
		
		
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   $sTable $sWhere $sOrder $sLimit"; //echo $sQuery;die;
		$results = $dbAdapter->query($sQuery)->execute();
		$qry=$results->getResource()->fetchAll();
		
		
 		
 		/* Data set length after filtering */
		$sQuery = "SELECT FOUND_ROWS() as fcnt";
		
		$results = $dbAdapter->query($sQuery)->execute();
		$aResultFilterTotal=$results->getResource()->fetchAll();
		$iFilteredTotal = $aResultFilterTotal[0]['fcnt'];
		
		
		/* Total data set length */
		$sQuery = "SELECT COUNT(`".$sIndexColumn."`) as cnt FROM $sTable ";
		
		$results = $dbAdapter->query($sQuery)->execute();
		$rResultTotal=$results->getResource()->fetchAll();
		$iTotal = $rResultTotal[0]['cnt'];
		
		/*
		 * Output
		 */
		 
 		$output = array(
 				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);
		
		$j=1;
		foreach($qry as $row1)
		{
			$row=array();
		
 			$row[] =$j;
			
			$row[]='<input class="elem_ids checkboxes"  type="checkbox" name="'.$sTable.'['.$row1[$sIndexColumn].']"  value="'.$row1[$sIndexColumn].'">';
			
  			$row[]=$row1['page_title'];
			
			$row[]=$row1['page_updated'];
								
			$row[] =  '<a href="'.ADMIN_APPLICATION_URL.'static/editpages?page_id='.$row1['page_id'].'" class="btn btn-xs yellow"> Edit <i class="fa fa-pencil"></i></a>';
						
  			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	
	public function dashboardAction()
	{
		$session = new Container(ADMIN_AUTH_NAMESPACE); 		
		if(!isset($session['adminData']) && empty($session['adminData']))
		{
			return $this->redirect()->toRoute('adminlogout');
		}
		
		$view = new ViewModel();
		$email_count=$this->SuperModel->Super_Get("email_templates","1","fetch",array("fields"=>array("email_count"=>new Expression("count(emailtemp_key)"))));
		
		$faq_count=$this->SuperModel->Super_Get("faq","1","fetch",array("fields"=>array("faq_count"=>new Expression("count(faq_id)"))));
		$pages_count=$this->SuperModel->Super_Get("pages","1","fetch",array("fields"=>array("pages_count"=>new Expression("count(page_id)"))));
		$plan_count=$this->SuperModel->Super_Get("investment_plans","1","fetch",array("fields"=>array("plan_count"=>new Expression("count(inv_pl_id)"))));
		//prd($lang_count);
		$user_count=$this->SuperModel->Super_Get("users","user_type='user'","fetch",array("fields"=>array("user_count"=>new Expression("count(user_id)"))));
		$view->setVariable('pageHeading','Dashboard');	
		$view->setVariable('user_count',$user_count['user_count']);	
		
		$view->setVariable('faq_count',$faq_count['faq_count']);	
		$view->setVariable('pages_count',$pages_count['pages_count']);
		$view->setVariable('email_count',$email_count['email_count']);
		$view->setVariable('plan_count',$plan_count['plan_count']);
		return $view;    
    }
	
    public function loginAction()
    {
		$this->layout('layout/login');
		$session = new Container(ADMIN_AUTH_NAMESPACE);		
		if(isset($session['adminData']) && !empty($session['adminData'])){
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/dashboard');
		}
		$view = new ViewModel();
		  
        // Create Contact Us form
        $form = new StaticForm();
		$form->adminlogin();
        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
              
				
				$admin = new Admin();
				$admin->exchangeArray2($data);
				
				$userDetails=$this->UserModel->chkLogin($admin);	
				if(!empty($userDetails))
				{
					$userdata=	$this->SuperModel->Super_Get('users',"user_id='".$userDetails[0]['user_id']."' and (user_type='admin' || user_type='subadmin')",'fetchAll');
					$session->offsetSet('admin_id', $userdata[0]['user_id']);
					$session->offsetSet('adminData', $userdata[0]);		
					$session->offsetSet('perData', explode(",",$allPermission['allPers']));		
		
			
					$this->adminMsgsession['successMsg']='Welcome To '.SITE_NAME.' Admin';
					if(isset($_GET['url'])){ 
						 return $this->redirect()->tourl(APPLICATION_URL.urldecode($_GET['url']));
					}				
					return $this->redirect()->tourl(ADMIN_APPLICATION_URL);
				}
				else
				{
					
					 $this->adminMsgsession['errorMsg']='Email and Password not valid.';
					return $this->redirect()->tourl(ADMIN_APPLICATION_URL);
				}
				
                // Redirect to "Thank You" page
                return $this->redirect()->toRoute('adminprofile',array('action'=>'/adminprofile'));
            }               
        } 
        
       	$view->setVariable('form', $form);
		$view->setVariable('pageHeading','Login Here');	
		$this->layout()->form = $form;
		return $view;    
    }
	
	public function forgotpasswordAction()
    {
		global $msg_session;
		$this->layout('layout/login');
		$session = new Container(ADMIN_AUTH_NAMESPACE);
		//prd($session['adminData']);
		
		if(isset($session['adminData']) && !empty($session['adminData'])){
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/dashboard');
		}
		
        $form = new StaticForm();
		$form->forgotpasswordadmin();
		$this->layout()->form = $form;
		
		if($this->getRequest()->isPost()) {
			$posted_data  =  $this->getRequest()->getPost();
			$form->setData($posted_data);
			if($form->isValid()){
 				$received_data = $form->getData();
				
				$checkEmail=$this->UserModel->checkAdminEmail($received_data['user_email'],false,true);
		
				if(!empty($checkEmail))
				{
					
					$user = $this->SuperModel->Super_Get('users','user_email="'.$received_data['user_email'].'" and (user_type="admin" or user_type="subadmin")','fetch');	
					$reset_password_key = md5($user['user_id']."!@#$%^".$user['user_created'].time());
					$data_to_update = array("user_email"=>$received_data['user_email'],"user_reset_status"=>"1","pass_resetkey"=>$reset_password_key);
					
			
					
					$this->SuperModel->Super_Insert('users',$data_to_update,'user_id = '.$user['user_id']);
					$user['pass_resetkey'] = $reset_password_key ;
					$user['user_reset_status'] = "1" ;
					
					$isSend = $this->EmailModel->sendEmail('reset_password',$user);
				
					
					$this->adminMsgsession['successMsg']='Mail has been sent to reset your account password';				
					return $this->redirect()->tourl(ADMIN_APPLICATION_URL);
					
				}
				else{
					$this->adminMsgsession['errorMsg']='Invalid email address..!';
				}
			}else{
				$this->adminMsgsession['errorMsg']='Invalid email address..!';
  			}
		}
		
		
		// Pass form variable to view
        return new ViewModel(array(
            'form' => $form
        ));
		
	}
	
	public function resetpasswordAction()
    {
		global $msg_session;
		
		$session = new Container(ADMIN_AUTH_NAMESPACE);
		//prd($session['adminData']);
		
		$this->layout('layout/login');
		if(isset($session['adminData']) && !empty($session['adminData'])){
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/dashboard');
		}
		
		$view = new ViewModel();
		
		$form = new StaticForm();
		$form->resetPassword();
		
		 $key = $this->params()->fromRoute('key');
		 if(empty($key)){
 			  $this->adminMsgsession['errorMsg'] = "Invalid Request for Reset Password ";
			  return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/forgot-password');
		 }
 		 $user_info = $this->SuperModel->Super_Get('users','pass_resetkey="'.$key.'"','fetch');
		
		 if(!$user_info){
			 $this->adminMsgsession['errorMsg'] = "Invalid Request for Password Reset , Please try again .";			 
			 return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/forgot-password');
		 }
		 
		 
		 
		 if($this->getRequest()->isPost()) {
			$posted_data  =  $this->getRequest()->getPost(); 
			
			$request = $this->getRequest();
			 
			$form->setData($request->getPost());
			
            if ($form->isValid()) { 
			
				$data_to_updates = $request->getPost();
				
				$data_to_update['pass_resetkey']="";
				$data_to_update['user_reset_status']="0";
				$data_to_update['user_password'] = md5($data_to_updates['user_password']);
				
				$ischeck=$this->SuperModel->Super_Insert("users",$data_to_update,'user_id="'.$user_info['user_id'].'"');
					
				if($ischeck){
					
					 $this->adminMsgsession['successMsg'] = "Password Changes Done Successfully.";
					 return $this->redirect()->tourl(ADMIN_APPLICATION_URL);
				}	
			 }else{
					$this->adminMsgsession['errorMsg'] = "Please Check Information Again.";
					
 			 }
		 }
		 
		
		
		$view->setVariable('form', $form);
		$view->setVariable('key', $key);
		$view->setVariable('pageHeading','Reset Password');	
		$this->layout()->form = $form;
		return $view;    
		
	
	}
	
	public function logoutAction()
	{
		$session = new Container(ADMIN_AUTH_NAMESPACE);
		$session->user_id = "";			
        Container::setDefaultManager(null);

		$session->offsetUnset('adminData');
		
		return $this->redirect()->tourl(ADMIN_APPLICATION_URL);		
	}
	
	

}