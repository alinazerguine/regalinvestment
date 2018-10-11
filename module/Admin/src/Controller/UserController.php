<?php
namespace Admin\Controller;
use Admin\Model\AdminTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\PagesForm;
use Admin\Form\EmailtemplateForm;
use Admin\Form\SiteconfigurationForm;
use AuthAcl\Form\RegisterForm;
use AuthAcl\Form\ProfileForm;
use Zend\Session\Container;
use Admin\Form\InvestmentForm;
use AuthAcl\Form\UserForm;
use Zend\Db\Sql\Expression;
use Application\Model\Email;

class UserController extends AbstractActionController
{
	private $AbstractModel,$UserModel,$Adapter,$adminMsgsession,$EmailModel;
    public function __construct($AbstractModel,$UserModel,$Adapter,$adminMsgsession,$config_data,$EmailModel)
    {
        $this->SuperModel = $AbstractModel;
		$this->UserModel = $UserModel;
		$this->Adapter = $Adapter;
		$this->adminMsgsession = $adminMsgsession;		
		$this->config_data = $config_data;	
		$this->EmailModel = $EmailModel;
		$session = new Container(ADMIN_AUTH_NAMESPACE);
		$this->adminuser = $session['adminData'];	
	}
	public function balanceAction()
    {
		 $mainHead="Manage Balance";		
		 $view = new ViewModel(array('pageHeading'=>$mainHead,'page_icon'=>'fa fa-money',));
		 return $view;
    }
	
	public function getbalanceAction(){
		$dbAdapter = $this->Adapter;
		$aColumns = array(
			'user_id',
			'user_image',
			'user_name',
			'user_balance',
		);
		
		$sTable = 'users';
		$sIndexColumn = 'user_id';
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
				//$sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
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
				//$sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				$sWhere .= "".$aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
		}
		
		
		}/* End Table Setting */
		$sWhere.=" where (user_type='user')";

		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM  $sTable $sWhere $sOrder group by user_id $sLimit"; 

		$results = $dbAdapter->query($sQuery)->execute();
		$qry=$results->getResource()->fetchAll();

		$sQuery = "SELECT FOUND_ROWS() as fcnt";
		
		$results = $dbAdapter->query($sQuery)->execute();
		$aResultFilterTotal=$results->getResource()->fetchAll();
		//$aResultFilterTotal =  $this->dbObj->query($sQuery)->fetchAll(); 
		$iFilteredTotal = $aResultFilterTotal[0]['fcnt'];
		
		
		/* Total data set length */
		$sQuery = "SELECT COUNT(`".$sIndexColumn."`) as cnt FROM $sTable $sWhere";
		
		$results = $dbAdapter->query($sQuery)->execute();
		$rResultTotal=$results->getResource()->fetchAll();
		//$rResultTotal = $this->dbObj->query($sQuery)->fetchAll(); 
		$iTotal = $rResultTotal[0]['cnt'];
		
		/* Output */
		 
 		$output = array(
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		$j=1;
		foreach($qry as $row1)
		{
			$row=array();
			
			$row[] = $j;
  			
			$row[]='<div style="width:60px; height:60px; background-image:url('.getUserImage($row1['user_image'],300).'); border-radius:50%; background-size:cover;"></div>';
			$row[]=$row1['user_name'];	
			
			$row[]=PRICE_SYMBOL.$row1['user_balance'];
			$row[] =  '<a href="'.APPLICATION_URL.'/'.BACKEND.'/manage-balance/'.($row1[$sIndexColumn]).'" class="btn btn-xs btn-success"> View <i class="fa fa-eye"></i></a>';
 			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	}
	
	public function managebalanceAction(){
		$mainHead="Manage Balance";	
		$user_id = (int) $this->params()->fromRoute('user_id', 0);
		
		$user_info =$this->SuperModel->Super_Get("users","user_id='".$user_id."' and user_type='user'","fetch",array());
		if(empty($user_info))
		{
			$this->adminMsgsession['infoMsg']  = "No Such User Exists in the database...!";
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/balance');
		}
		$users_balance =$this->SuperModel->Super_Get("users_balance","user_bal_userid='".$user_id."'","fetchall",array());
		
		$form = new InvestmentForm();
		$form->balance();	
		 
		$request = $this->getRequest();
		
		//$form->populateValues($userData);
        if($request->isPost()) {
				$form->setData($request->getPost());
				if($form->isValid()){
					  $Formdata = $form->getData();
					  if($Formdata['user_action']!=1 && $Formdata['user_action']!='2'){
						  $this->adminMsgsession['errorMsg']='Please check information again.';
						  return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/manage-balance/'.$user_id);
					 }
					 $user_bal_type=$Formdata['user_action']-1;
					 $invalidbalance=0;

					 $posted_amount=$Formdata["user_amount"];
					
					 $exist_amount=$user_info["user_balance"];
					 if($user_bal_type=='1'){
						/* it means miunus from account */	 
						
						if($exist_amount < $posted_amount){
								$invalidbalance=1;
						}
					}
					 if($invalidbalance==1){
						 $this->adminMsgsession['errorMsg']='Balance insufficient.';
					 }
					 else{
						 $totalmount=0;
						 if($user_bal_type=='1'){
						 	 $totalmount=$exist_amount-$posted_amount;
						 }else{
							 $totalmount=$exist_amount+$posted_amount;
						}
						 
					 $is_updated= $this->SuperModel->Super_Insert("users",array("user_balance"=>$totalmount),"user_id='".$user_id."'");
					
					 if(is_object($is_updated) && $is_updated->success){
						$this->SuperModel->Super_Insert("users_balance",array("user_bal_balance"=>$Formdata["user_amount"],"user_bal_userid"=>$user_id,"user_bal_addedon"=>date("Y-m-d"),'user_bal_type'=>$user_bal_type)); 
						
						$is_updated_plan=$this->SuperModel->Super_Insert("notifications",array("notification_type"=>"3","notification_user_id"=>$user_id,"notification_by_user_id"=>"1","notification_date"=>date("Y-m-d H:i:s")));
			
						$emaildata=array("user_name"=>$user_info['user_first_name'].''.$user_info['user_last_name'],'user_email'=>$user_info['user_email']);
						$mailType="user_balance_updated";
						$this->EmailModel->sendEmail($mailType, $emaildata);
						
						
						$this->adminMsgsession['successMsg']='Balance is updated.';
						return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/manage-balance/'.$user_id);
					}
					 }
				}else{
				
				$this->adminMsgsession['errorMsg']='Please check information again.';
			}
				
		}
		 $view = new ViewModel(array('pageHeading'=>'Account Information -'.$user_info['user_first_name'].' '.$user_info['user_last_name'],'page_icon'=>'fa fa-money',"users_balance"=>$users_balance,"user_information"=>$user_info,"form"=>$form));
		 return $view;
	}
	
	
	
	public function imprequestAction()
    {
		 $mainHead="View Request";		
		 $view = new ViewModel(array('pageHeading'=>$mainHead,'page_icon'=>'fa fa-handshake-o',));
		 return $view;
    }
	public function getimprequestAction(){
		
		
  			
		$dbAdapter = $this->Adapter;
		$type =$this->params()->fromRoute('type');
		
		$aColumns = array(
			'user_id',
			'user_image',
			'user_name',
			'user_dashboard_txt',
			'user_request_addedon'
		);
		
		$sTable = 'users';
		$sIndexColumn = 'user_id';
		//$sTable = 'users';
  		
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
				//$sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
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
				//$sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				$sWhere .= "".$aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
		}
		
		
		}/* End Table Setting */
		
		$sWhere.=" where (user_type='user' and user_dashboard_txt!='')";

		//$sOrder='ORDER BY user_request_addedon desc';
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM  $sTable $sWhere $sOrder  $sLimit"; 

		$results = $dbAdapter->query($sQuery)->execute();
		$qry=$results->getResource()->fetchAll();

		$sQuery = "SELECT FOUND_ROWS() as fcnt";
		
		$results = $dbAdapter->query($sQuery)->execute();
		$aResultFilterTotal=$results->getResource()->fetchAll();
		//$aResultFilterTotal =  $this->dbObj->query($sQuery)->fetchAll(); 
		$iFilteredTotal = $aResultFilterTotal[0]['fcnt'];
		
		
		/* Total data set length */
		$sQuery = "SELECT COUNT(`".$sIndexColumn."`) as cnt FROM $sTable $sWhere";
		
		$results = $dbAdapter->query($sQuery)->execute();
		$rResultTotal=$results->getResource()->fetchAll();
		//$rResultTotal = $this->dbObj->query($sQuery)->fetchAll(); 
		$iTotal = $rResultTotal[0]['cnt'];
		
		/* Output */
		 
 		$output = array(
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		$j=1;
		foreach($qry as $row1)
		{
			$row=array();
			
			$row[] = $j;
  			
			$row[]='<div style="width:60px; height:60px; background-image:url('.getUserImage($row1['user_image'],300).'); border-radius:50%; background-size:cover;"></div>';
			$row[]=$row1['user_name'];	
			
			$row[]=nl2br($row1['user_dashboard_txt']);
			$row[]=getDateTimeformaton($row1['user_request_addedon']);
 			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	
		
		
		
	}
	
	
	public function usersAction()
    {
		
		 
		 $mainHead="Manage Users";		
		 $view = new ViewModel(array('type'=>$type,'pageHeading'=>$mainHead,'page_icon'=>'fa fa-users',));
		 return $view;
    }
	
	
	
	public function getusersAction(){
  			
		$dbAdapter = $this->Adapter;
		$type =$this->params()->fromRoute('type');
		
		$aColumns = array(
			'user_id',
			'user_type',
			'user_image',
			'user_first_name',
			'user_email',
			'user_email_verified',
			'user_status', 			
			'user_created',
			'user_name',
			'user_last_name',
  		);
		
		$sTable = 'users';
		$sIndexColumn = 'user_id';
		//$sTable = 'users';
  		
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
				//$sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
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
				//$sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				$sWhere .= "".$aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
		}
		
		
		}/* End Table Setting */
		
		$sWhere.=" where (user_type='user')";

		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM  $sTable $sWhere $sOrder group by user_id $sLimit"; 

		$results = $dbAdapter->query($sQuery)->execute();
		$qry=$results->getResource()->fetchAll();

		$sQuery = "SELECT FOUND_ROWS() as fcnt";
		
		$results = $dbAdapter->query($sQuery)->execute();
		$aResultFilterTotal=$results->getResource()->fetchAll();
		//$aResultFilterTotal =  $this->dbObj->query($sQuery)->fetchAll(); 
		$iFilteredTotal = $aResultFilterTotal[0]['fcnt'];
		
		
		/* Total data set length */
		$sQuery = "SELECT COUNT(`".$sIndexColumn."`) as cnt FROM $sTable $sWhere";
		
		$results = $dbAdapter->query($sQuery)->execute();
		$rResultTotal=$results->getResource()->fetchAll();
		//$rResultTotal = $this->dbObj->query($sQuery)->fetchAll(); 
		$iTotal = $rResultTotal[0]['cnt'];
		
		/* Output */
		 
 		$output = array(
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		$j=1;
		
		$current_date=date('Y-m-d');
		
		foreach($qry as $row1)
		{
			$row=array();
			
			$row[] = $j;
  			$row[]='<input class="elem_ids checkboxes"  type="checkbox" name="users['.$row1[$sIndexColumn].']"  value="'.$row1[$sIndexColumn].'">';
			$row[]='<div style="width:60px; height:60px; background-image:url('.getUserImage($row1['user_image'],300).'); border-radius:50%; background-size:cover;"></div>';
			
			
			switch($row1['user_email_verified']){
				case '0':$verification_status ="<span class='btn btn-danger btn-xs'>Unverified</span>";break;
				default :$verification_status ="<span class='btn btn-info btn-xs'>Verified</span>";break;
			}
			
			$row[]=$row1['user_first_name'].' '.$row1['user_last_name']."<br />$verification_status";	
				
   			$row[]=$row1['user_email'];
			
			$row[]=date('M d, Y H:i a',strtotime($row1['user_created']));
			$status = $row1['user_status']==1?"checked='checked'":" "; 	
			$row[]='<div class="onoffswitch"><input type="checkbox" class="onoffswitch-checkbox toggle status-'.(int)$row1['user_status'].'"  '.$status.'  id="'.$sTable.'-'.$row1[$sIndexColumn].'" onChange="globalStatus(this)" /><label class="onoffswitch-label" for="'.$sTable.'-'.$row1[$sIndexColumn].'"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div>';
			
			
			$row[] =  '<a href="'.APPLICATION_URL.'/'.BACKEND.'/account/'.$row1[$sIndexColumn].'" class="btn btn-xs btn-success"> View <i class="fa fa-eye"></i></a>&nbsp;<a href="'.APPLICATION_URL.'/'.BACKEND.'/planrequest/'.$row1[$sIndexColumn].'" class="btn btn-xs btn-success"> Plan <i class="fa fa-eye"></i></a>&nbsp;<a href="'.APPLICATION_URL.'/'.BACKEND.'/resourcerequest/'.$row1[$sIndexColumn].'" class="btn btn-xs btn-success"> Resource <i class="fa fa-eye"></i></a>';
			
			
 			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	
	
	public function planrequestAction(){
		$user_id = (int) $this->params()->fromRoute('user_id', 0);
		$user_info =$this->SuperModel->Super_Get("users","user_id='".$user_id."' and user_type='user'","fetch",array());
		if(empty($user_info))
		{
			$this->adminMsgsession['infoMsg']  = "No Such User Exists in the database...!";
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/users');
		}
		$joinArr=array(
		'0'=> array('0'=>'investment_plans','1'=>'user_plan_planid=inv_pl_id','2'=>'Left','3'=>array('inv_pl_title')),
		
		
		);
		$plan_info=$this->SuperModel->Super_Get("users_plan","user_plan_userid='".$user_id."'","fetchall",array(),$joinArr);
		
		$view = new ViewModel(array('user_information'=>$user_info,'pageHeading'=>'Account Information -'.$user_info['user_first_name'].' '.$user_info['user_last_name'],'plan_info'=>$plan_info));
		return $view;
		
	}
	
	public function resourcerequestAction(){
		$user_id = (int) $this->params()->fromRoute('user_id', 0);
		$user_info =$this->SuperModel->Super_Get("users","user_id='".$user_id."' and user_type='user'","fetch",array());
		if(empty($user_info))
		{
			$this->adminMsgsession['infoMsg']  = "No Such User Exists in the database...!";
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/users');
		}
		$joinArr=array(
		'0'=> array('0'=>'resource','1'=>'user_res_resid=res_id','2'=>'Left','3'=>array('res_title')),
		);
		$res_info=$this->SuperModel->Super_Get("users_resource","user_res_userid='".$user_id."'","fetchall",array(),$joinArr);
		
		$view = new ViewModel(array('user_information'=>$user_info,'pageHeading'=>'Account Information -'.$user_info['user_first_name'].' '.$user_info['user_last_name'],'res_info'=>$res_info));
		return $view;
		
	}
	public function approverequestAction(){
		$resquest= $this->params()->fromRoute('res',0); 
		$this->setrequest($resquest,1);
		
	}
	public function rejectrequestAction(){
		$resquest= $this->params()->fromRoute('res',0); 
		$this->setrequest($resquest,2);
		
	}
	
	function setrequest($resquest,$type){ 
	
		$resquest=myurl_decode($resquest);
		
		//$type=1:approve,$type=2:reject
		if(empty($resquest)){
			$this->adminMsgsession['infoMsg']  = "Invalid Resquest";
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/users');
		}
		$joinArr=array(
		'0'=> array('0'=>'resource','1'=>'user_res_resid=res_id','2'=>'Left','3'=>array('res_title')),
		'1'=> array('0'=>'users','1'=>'user_res_userid=user_id','2'=>'Left','3'=>array('user_first_name','user_last_name','user_email')),
		);
		
		$getresourcedata=$this->SuperModel->Super_Get("users_resource","user_res_id='".$resquest."' and user_res_isactive='0'","fetch",array(),$joinArr);
		
		if(empty($getresourcedata)){
				$this->adminMsgsession['infoMsg']  = "Invalid Resquest";
				return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/users');
				
		}
		
		$isresourcedata=$this->SuperModel->Super_Insert("users_resource",array("user_res_isactive"=>$type),"user_res_id='".$resquest."' and user_res_isactive='0'");
		if(is_object($isresourcedata) && $isresourcedata->success){
			/* send mail and insert notification */
			$type='1'.$type; 
			$is_updated_plan=$this->SuperModel->Super_Insert("notifications",array("notification_type"=>$type,"notification_user_id"=>$getresourcedata['user_res_userid'],"notification_by_user_id"=>"1","notification_date"=>date("Y-m-d H:i:s"),"notification_resource_id"=>$getresourcedata['user_res_id']));
			
			$emaildata=array("user_name"=>$getresourcedata['user_first_name'].''.$getresourcedata['user_last_name'],"resource_name"=>$getresourcedata['res_title'],'user_email'=>$getresourcedata['user_email']);
			$mailType="user_resource_reject";
			if($type==11){
				$mailType="user_resource_approve";
			}
			
			
			$this->EmailModel->sendEmail($mailType, $emaildata);
			
			$this->adminMsgsession['successMsg']='Resource is updated';
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/resourcerequest/'.$getresourcedata['user_res_userid']);
		}else{
			$this->adminMsgsession['errorMsg']='Some error occurred';
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/users');
		}
	}
	
	public function approveplanAction(){
		$plan= $this->params()->fromRoute('plan',0); 
		$this->setplan($plan,1);
		
	}
	
	public function rejectplanAction(){
		$plan= $this->params()->fromRoute('plan',0); 
		$this->setplan($plan,2);
		
	}
	
	
	function setplan($plan,$type){ 
		$plan=myurl_decode($plan);
		
		//$type=1:approve,$type=2:reject
		if(empty($plan)){
			$this->adminMsgsession['infoMsg']  = "Invalid Plan";
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/users');
		}
		$joinArr=array(
		'0'=> array('0'=>'investment_plans','1'=>'user_plan_planid=inv_pl_id','2'=>'Left','3'=>array('inv_pl_title')),
		'1'=> array('0'=>'users','1'=>'user_plan_userid=user_id','2'=>'Left','3'=>array('user_first_name','user_last_name','user_email')),
		);
		
		$getplandata=$this->SuperModel->Super_Get("users_plan","user_plan_id='".$plan."' and user_plan_isactive='0'","fetch",array(),$joinArr);
		
		if(empty($getplandata)){
				$this->adminMsgsession['infoMsg']  = "Invalid Plan";
				return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/users');
				
		}
		
		$isplandata=$this->SuperModel->Super_Insert("users_plan",array("user_plan_isactive"=>$type),"user_plan_id='".$plan."' and user_plan_isactive='0'");
		if(is_object($isplandata) && $isplandata->success){
			/* send mail and insert notification */
			$is_updated_plan=$this->SuperModel->Super_Insert("notifications",array("notification_type"=>$type,"notification_user_id"=>$getplandata['user_plan_userid'],"notification_by_user_id"=>"1","notification_date"=>date("Y-m-d H:i:s"),"notification_plan_id"=>$getplandata['user_plan_id']));
			
			$emaildata=array("user_name"=>$getplandata['user_first_name'].''.$getplandata['user_last_name'],"plan_name"=>$getplandata['inv_pl_title'],'user_email'=>$getplandata['user_email']);
			$mailType="user_plan_reject";
			if($type==1){
				$mailType="user_plan_approve";
			}
			
			
			$this->EmailModel->sendEmail($mailType, $emaildata);
			
			$this->adminMsgsession['successMsg']='Plan is updated';
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/planrequest/'.$getplandata['user_plan_userid']);
		}else{
			$this->adminMsgsession['errorMsg']='Some error occurred';
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/users');
		}
	}
	
	
	public function accountAction(){
		
		$user_id = (int) $this->params()->fromRoute('user_id', 0);
		
		$joinArr=array(
		'0'=> array('0'=>'investing_reason','1'=>'inv_res_id=user_book_type','2'=>'Left','3'=>array('inv_res_title')),
		'1'=> array('0'=>'investing_reason_option','1'=>'inv_res_opt_id=user_investing_reason','2'=>'Left','3'=>array('inv_res_opt_restype')),
		'2'=> array('0'=>'investing_type','1'=>'user_invest_type=inv_ty_id','2'=>'Left','3'=>array('inv_ty_title'))
		
		);
		
		$user_info =$this->SuperModel->Super_Get("users","user_id='".$user_id."' and user_type='user'","fetch",array(),$joinArr);
		$form = new UserForm();
		$form->user_prosperity();		
		$form->populateValues($user_info);
		if(empty($user_info))
		{
			$this->adminMsgsession['infoMsg']  = "No Such User Exists in the database...!";
			return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/users');
		}
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$postedData = $request->getPost();
			$form->setData($postedData);
			if($form->isValid()){
				$formData=$form->getData();
				$isUserInserted=$this->SuperModel->Super_Insert("users",array("user_prosperity"=>$formData['user_prosperity']),'user_id="'.$user_id.'"');
				//prd($isUserInserted);
			
				
				
				if(is_object($isUserInserted) && $isUserInserted->success){
					if($formData['user_prosperity']!=$user_info['user_prosperity']){
							$this->adminMsgsession['successMsg']  = "User prosperity is updated";
							$is_updated_plan=$this->SuperModel->Super_Insert("notifications",array("notification_type"=>"13","notification_user_id"=>$user_id,"notification_by_user_id"=>"1","notification_date"=>date("Y-m-d H:i:s")));
			
						$emaildata=array("user_name"=>$user_info['user_first_name'].' '.$user_info['user_last_name'],'user_email'=>$user_info['user_email']);
						$mailType="user_prosperity_updated";
						$this->EmailModel->sendEmail($mailType, $emaildata);
							
					}else{
						$this->adminMsgsession['infoMsg']  = "New Information is same as previous one.";
					}
					
				}else{
					$this->adminMsgsession['errorMsg']  = "Some error occurred";
				}
				return $this->redirect()->toUrl(APPLICATION_URL.'/'.BACKEND.'/account/'.$user_info['user_id']);
			}
		}
		$view = new ViewModel(array('user_information'=>$user_info,'pageHeading'=>'Account Information -'.$user_info['user_first_name'].' '.$user_info['user_last_name'],'form'=>$form));
		return $view;
	}
	
	public function removeusersAction(){
		
		
		
		$request = $this->getRequest();
		
			
		if ($request->isPost()) {
			 $del = $request->getPost('users');
		
			 foreach($del as $key=>$ids)
			 {  	
				$isdeleted=$this->SuperModel->Super_Delete('users','user_id ="'.$ids.'"');	 
			 } 
		}
		
		$this->adminMsgsession['successMsg']='User account Deleted Successfully.';
		return $this->redirect()->toRoute('admin_users', array(
            'type' => $type
        ));
		
	}
	

	
	
}