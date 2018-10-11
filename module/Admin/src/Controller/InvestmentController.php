<?php
namespace Admin\Controller;

use Admin\Model\AdminTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Admin\Form\EmailtemplateForm;
use Admin\Form\SiteconfigurationForm;
use Admin\Form\Homecontent;
use Zend\Session\Container;
use Admin\Model\Admin;
use Admin\Form\StaticForm;
use Admin\Form\HomeForm;
use Admin\Form\InvestmentForm;
use Admin\Model\User;
use Zend\Db\Sql\Expression;
class InvestmentController extends AbstractActionController
{
    
	private $AbstractModel,$Adapter,$EmailModel;
    public function __construct($AbstractModel,Adapter $Adapter,$adminMsgsession,$EmailModel,$config_data)
    {
        $this->SuperModel = $AbstractModel;
		$this->Adapter = $Adapter;
		$this->EmailModel = $EmailModel;
		$this->adminMsgsession = $adminMsgsession;	
		$this->view = new ViewModel();
		$this->SITE_CONFIG = $config_data;
    }
	
	public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $session = $container->get(SessionContainer::class);
        $db = $container->get(DbAdapter::class);
	}
	public function removeplansAction(){
		
		$request = $this->getRequest();
			
		if ($request->isPost()) {
			 $del = $request->getPost('investment_plans');
			
			 foreach($del as $key=>$ids)
			 {  
				$isdeleted=$this->SuperModel->Super_Delete('investment_plans','inv_pl_id ="'.$ids.'"');	 
			 } 
		}
		$this->adminMsgsession['successMsg']='Plan Deleted Successfully.';
		return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/plans');
		
	}
	
	
	public function qpercentageAction(){
		$this->layout()->setVariable('pageHeading', 'Q1Percentage Values');
		$this->layout()->setVariable('pageDescription', 'Q1Percentage Values');
		$investing_reason=$this->SuperModel->Super_Get('investing_reason','1','fetchAll');
		
		$investing_reason_option=$this->SuperModel->Super_Get('investing_reason_option','1','fetchAll');
		
		$investing_reason_configs=$this->SuperModel->Super_Get("investing_reason_configs",'1','fetchAll');
		
		$investing_reason_pervalue=$this->SuperModel->Super_Get('investing_reason_pervalue',"1","fetchAll");
		//prd($investing_reason_pervalue);
		$form = new InvestmentForm();
		$form->investmentform($investing_reason_configs);
		if(!empty($investing_reason_pervalue)){
		/*$investing_reason_Data=array('1[stocks]'=>"d","1[warrenties]"=>"sd");*/
		$investing_reason_Data=array();
	
		foreach($investing_reason_pervalue as $inv_key=>$inv_value){
			$allEleList=array("stocks","warrants","forex","options","futures","monthly","returntype");
			if($inv_value['inv_res_per_resid']!='5'){
				//prd($form->getElements());
				foreach($allEleList as $aValue){
					if (array_key_exists($inv_value['inv_res_per_resid'].'['.$aValue.']',$form->getElements())){
						$subInert=$aValue;
						if($aValue=="warrants"){
							$subInert="warrenties";
						}
					$form->get($inv_value['inv_res_per_resid'].'['.$aValue.']')->setValue(removedecimal($inv_value['inv_res_per_'.$subInert]));	
					}
				}
			}else{
				
				foreach($allEleList as $aValue){
					if (array_key_exists($inv_value['inv_res_per_resid'].'['.$inv_value['inv_res_per_resoptid'].']'.'['.$aValue.']',$form->getElements())){
					$form->get($inv_value['inv_res_per_resid'].'['.$inv_value['inv_res_per_resoptid'].']'.'['.$aValue.']')->setValue(removedecimal($inv_value['inv_res_per_'.$aValue]));	
					}
				}	
			}
		}
		//prD($investing_reason_Data);
		//s$form->populateValues($investing_reason_Data);
		//$form->populateValues(array('1[stocks]'=>"s",'1[warrenties]'=>"s"));
		}
		$request = $this->getRequest();
		if($request->isPost()) {
            $form->setData($request->getPost());
			
			 if(true){//$form->isValid()
				// $Formdata = $form->getData(); 
				
				$requestedData=$request->getPost();
				$Formdata =$requestedData;
				if(!empty($requestedData)){
						foreach($requestedData as $resKey=>$resValue){
					
						if($resKey!='5'){
							
							$insData=array("inv_res_per_resid"=>$resKey,
							'inv_res_per_stocks'=>isset($resValue['stocks'])?$resValue['stocks']:0,
							'inv_res_per_warrenties'=>isset($resValue['warrants'])?$resValue['warrants']:0,
							'inv_res_per_forex'=>isset($resValue['forex'])?$resValue['forex']:0,
							'inv_res_per_futures'=>isset($resValue['futures'])?$resValue['futures']:0,
							'inv_res_per_options'=>isset($resValue['options'])?$resValue['options']:0,
							'inv_res_per_monthly'=>isset($resValue['monthly'])?$resValue['monthly']:0,
							'inv_res_per_returntype'=>isset($resValue['returntype'])?$resValue['returntype']:0,
							);
							
							$isInserted=$this->SuperModel->Super_Insert('investing_reason_pervalue',$insData,'inv_res_per_resid="'.$resKey.'"');
						}else{
							foreach($resValue as $resSubKey=>$resSubValue){	
							
							$insData=array("inv_res_per_resid"=>$resKey,
							'inv_res_per_stocks'=>isset($resSubValue['stocks'])?$resSubValue['stocks']:0,
							'inv_res_per_warrenties'=>isset($resSubValue['warrants'])?$resSubValue['warrants']:0,
							'inv_res_per_forex'=>isset($resSubValue['forex'])?$resSubValue['forex']:0,
							'inv_res_per_futures'=>isset($resSubValue['futures'])?$resSubValue['futures']:0,
							'inv_res_per_options'=>isset($resSubValue['options'])?$resSubValue['options']:0,
							'inv_res_per_monthly'=>isset($resSubValue['monthly'])?$resSubValue['monthly']:0,
							'inv_res_per_returntype'=>isset($resSubValue['returntype'])?$resSubValue['returntype']:0,
							'inv_res_per_resoptid'=>$resSubKey,
							
							);
							$isInserted=$this->SuperModel->Super_Insert('investing_reason_pervalue',$insData,'inv_res_per_resid="'.$resKey.'" and inv_res_per_resoptid="'.$resSubKey.'"');
							
							
							}
						}
							
						
							
						}
						
						$this->adminMsgsession['successMsg']='Percentage values has been updated';
						return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/qpercentage');	
				}
			 
			 }else{
				//prd($form->getMessages());	 
			}
		}
		
		$view = new ViewModel(array('page_icon'=>'fa fa-percent','pageHeading'=>'Q1Percentage Values','investing_reason'=>$investing_reason,'investing_reason_option'=>$investing_reason_option,'investing_reason_configs'=>$investing_reason_configs,'form'=>$form));
		return $view;
	}
	
	/* in vestment plans */
	public function planAction()
    {
		 return new ViewModel(array('page_icon'=>'fa  fa-paper-plane','pageHeading'=>"Investment Plans"));
    }
	public function getplansAction(){
  			
		$dbAdapter = $this->Adapter;
  
		$aColumns = array('inv_pl_id','inv_pl_title');

		$sIndexColumn = 'inv_pl_id';
		$sTable = 'investment_plans';
  		
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
			
			
			
  			$row[]=nl2br($row1['inv_pl_title']);
								
			$row[] =  '<a href="'.ADMIN_APPLICATION_URL.'/manageplan/'.$row1['inv_pl_id'].'" class="btn btn-xs btn-warning"> Edit <i class="fa fa-pencil"></i></a>';
						
  			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	public function manageplanAction(){
		
		$edit_id = $this->params()->fromRoute('plan');
	    $form = new InvestmentForm();
		$form->plan();	
		$PageHeading='Add Plan';
		if($edit_id!=''){
			$PageHeading='Edit Plan';
			$data=$this->SuperModel->Super_Get('investment_plans','inv_pl_id="'.$edit_id.'"','fetch');
			if(empty($data)){
			$this->adminMsgsession['errorMsg']='Invalid Request.';
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/plans');	
			}else{
				$form->get('inv_pl_title')->setValue($data['inv_pl_title']);
				
				
			}
		}
		  $request = $this->getRequest();
	
        if($request->isPost()) {
          
		    $form->setData($request->getPost());
			
			
            if($form->isValid()){
              
			    $Formdata = $form->getData();
				$title_where='LOWER(inv_pl_title)="'.strtolower(trim($Formdata['inv_pl_title'])).'"';
				if($edit_id!=''){$title_where.=' and inv_pl_id!="'.$edit_id.'"';}
				$getPlanData=$this->SuperModel->Super_Get('investment_plans',$title_where,'fetch'); 
				if(!empty($getPlanData)){
					$this->adminMsgsession['errorMsg']='Title already exist.';	
				}else{	
				
				
				unset($Formdata['bttnsubmit']);
				$labelis='added';
				if($edit_id!=''){
				$isInserted=$this->SuperModel->Super_Insert('investment_plans',$Formdata,"inv_pl_id='".$edit_id."'");
				$labelis='updated';
				}else{
				$isInserted=$this->SuperModel->Super_Insert('investment_plans',$Formdata);	
				}
				if(!empty($isInserted)){
					$this->adminMsgsession['successMsg']='Plan '.$labelis.' successfully.';
					return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/plans');	
				}else{
					$this->adminMsgsession['errorMsg']='Please check information again.';
				}
				
				}
            }else{
				
				$this->adminMsgsession['errorMsg']='Please check information again.';
			}
			
        }
		
		$view = new ViewModel(array('form'=>$form,'page_icon'=>'fa fa-paper-plane','pageHeading'=>$PageHeading));
		$view->setTemplate('admin/admin/add.phtml');
		return $view;
	
		
		
	}
	
	/* investment plans */
	public function logicAction(){
	
		$investment_plans=$this->SuperModel->Super_Get('investment_plans',"1","fetchAll");
		$investing_type=$this->SuperModel->Super_Get("investing_type","1","fetchAll");
		
		$forminvestment_plans=$this->SuperModel->prepareselectoptionwhere("investment_plans","inv_pl_id","inv_pl_title","1");
		$config_groupData=$this->SuperModel->Super_Get("config","config_group='SITE_PLAN'","fetchall");
	//prD(	$config_groupData);
		$form = new InvestmentForm();
		$form->logic($forminvestment_plans,$this->SITE_CONFIG['site_plan_price'],$investing_type);
		foreach($config_groupData as $ckey=>$cValue){
			$expData=explode(",",$cValue['config_value']);
			$postedElement=$expData[0];
			if(isset($expData[1]) && !empty($expData[1])){
				$postedElement=$expData;
			}
			$form->get($cValue['config_key'])->setValue($postedElement);
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
		 $form->setData($request->getPost());
		 if($form->isValid()){
			 	 $Formdata = $form->getData();
				foreach($Formdata as $fKey=>$fValue){
					$savedData=$fValue;
					if(is_array($fValue)){
						$savedData=implode(",",$fValue);
					}
					
					$this->SuperModel->Super_Insert("config",array("config_value"=>$savedData),"config_key='".$fKey."'");
				}
				$this->adminMsgsession['successMsg']='Logic Updated Successfully.';
				return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/logic');
				// prd($Formdata);
		 }
		}
		return new ViewModel(array('page_icon'=>'fa fa-puzzle-piece','pageHeading'=>"Recommendation Logics",'SITE_CONFIG'=>$this->SITE_CONFIG,'form'=>$form,'investing_type'=>$investing_type));
	}
	public function withdrawalAction(){
		$transx= $this->params()->fromRoute("transx"); 
		return new ViewModel(array('page_icon'=>'fa fa-money','pageHeading'=>"Withdrawal","transx"=>$transx));
	} 
	public function depositAction(){
		$transx= $this->params()->fromRoute("transx"); 
		return new ViewModel(array('page_icon'=>'fa fa-money','pageHeading'=>"Deposit","transx"=>$transx));
	} 
	public function getwithdrawalAction(){
  		$transx= $this->params()->fromRoute("transx"); 
		$txntype= $this->params()->fromRoute("txntype"); 	
		$dbAdapter = $this->Adapter;
  		global $currencyCode,$methodBox;
		 
		$aColumns = array('user_trans_id','user_id','user_name','user_trans_type',"user_trans_currency","user_trans_amount","user_trans_date","user_trans_method","user_trans_verify");

		$sIndexColumn = 'user_trans_id';
		$sTable = 'user_transfer';
  		
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
		if($transx!='' && $transx!=0){
			if($sWhere==''){
				$sWhere=' where user_trans_id="'.$transx.'"';
			}else{
				$sWhere.=' and user_trans_id="'.$transx.'"';
			}
			
		}
		if($txntype!=''){
			if($sWhere==''){
				$sWhere=' where user_trans_type="'.$txntype.'"';
			}else{
				$sWhere.=' and user_trans_type="'.$txntype.'"';
			}	
		}
		
		$userjoinData=' left join users on user_trans_userid=user_id';
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM  $sTable $userjoinData $sWhere $sOrder $sLimit"; //echo $sQuery;die;
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
			
			$row[]=$row1["user_name"];
			$row[]=$row1['user_trans_type']=='0'?"Deposit":"Withdraw";
			$row[]=removedecimal($currencyCode[$row1["user_trans_currency"]].$row1["user_trans_amount"]).'<br>(By '.$methodBox[$row1['user_trans_method']].')';
  			//$status = $row1['user_trans_verify']==1?"Approved":"Pending"; 	
			$row[]=getDateTimeformaton($row1['user_trans_date']);
			if($row1['user_trans_verify']=='1'){
			$row[]='<label class="label label-success" style="padding:12px;display: inline-block;">Approved</label>';
			}else{
				$row[]='<a onclick=approvetransaction("'.myurl_encode($row1['user_trans_id']).'") class="btn btn-warning" title="Click to process">Pending</a>';
			}
			/*$status = $row1['user_trans_verify']==1?"checked='checked'":" "; 	
			$row[]='<div class="onoffswitch"><input type="checkbox" class="onoffswitch-checkbox toggle status-'.(int)$row1['user_trans_verify'].'"  '.$status.'  id="'.$sTable.'-'.$row1[$sIndexColumn].'" onChange="globalStatus(this)" /><label class="onoffswitch-label" for="'.$sTable.'-'.$row1[$sIndexColumn].'"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div>';*/
						
  			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	public function approvetransactionAction(){
			$invalidbalance=0;
			$transx = $this->params()->fromRoute('transx');
			if($transx==''){return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/withdrawal');	}
			$transx=myurl_decode($transx);
			if($transx==''){return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/withdrawal');	}
			
			
			$joinArr=array(
				'0'=> array('0'=>'users','1'=>'user_trans_userid=user_id','2'=>'right','3'=>array('user_name','user_email')),
			);
			//user_balance
			$isGeted=$this->SuperModel->Super_Get('user_transfer',"user_trans_id='".$transx."'","fetch",array(),$joinArr);
			if(empty($isGeted)){return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/withdrawal');}
			$user_info =$this->SuperModel->Super_Get("users","user_id='".$isGeted['user_trans_userid']."' and user_type='user'","fetch",array("fields"=>array("user_balance")));	
			
			$exist_amount=$user_info["user_balance"];
		   $posted_amount=$isGeted["user_trans_amount"];
		   $redirecttype="deposit";
			if($isGeted['user_trans_type']=='1'){$redirecttype="withdrawal";
				if($exist_amount < $posted_amount){
								$invalidbalance=1;
					}
			}
			if($invalidbalance==1){
						 $this->adminMsgsession['errorMsg']='Balance insufficient.';
					 }else{
			$is_ordered=$this->SuperModel->Super_Insert('user_transfer',array('user_trans_verify'=>"1"),"user_trans_id='".$transx."'");
			if(is_object($is_ordered) && $is_ordered->success){
				$emaildata=array("user_name"=>$isGeted['user_name'],"user_email"=>$isGeted['user_email']);
				$notification=array("notification_user_id"=>$isGeted["user_trans_userid"],"notification_by_user_id"=>"1","notification_date"=>date("Y-m-d H:i:s"),"notification_trans_id"=>$transx);
				
				/* update in user balance */
				/*0=>credit +,1=>debit-1*/
				//0=>deposit,1=>withdraw
				$user_bal_type='1'; //debit=>withdraw
				$email_template="withdraw_approve_request";
				
				 
					 
					
				$exist_amount=$user_info["user_balance"];
				$posted_amount=$isGeted["user_trans_amount"];
				if($isGeted['user_trans_type']=='0'){
						$user_bal_type='0';//credit=>deposit
						$email_template="deposit_approve_request";
						//$emaildata=array();
						$notification["notification_type"]="9";	
						$totalmount=$exist_amount+$posted_amount;
				}else{
					$notification["notification_type"]="8";	
					if($exist_amount < $posted_amount){
								$invalidbalance=1;
					}else{	
					$totalmount=$exist_amount-$posted_amount;
					}
				}
				
				 
				
				
				$is_updated= $this->SuperModel->Super_Insert("users",array("user_balance"=>$totalmount),"user_id='".$isGeted['user_trans_userid']."'");
				$balanceData=array("user_bal_userid"=>$isGeted['user_trans_userid'],"user_bal_balance"=>$isGeted["user_trans_amount"],"user_bal_type"=>$user_bal_type,'user_bal_addedon'=>date("Y-m-d H:i:s"),'user_bal_trans_id'=>$transx);
				$ins_users_balance=$this->SuperModel->Super_Insert("users_balance",$balanceData);
				$notification["notification_user_bal_id"]=$ins_users_balance->inserted_id;	
				$this->SuperModel->Super_Insert("notifications",$notification);
				/*$emaildata=array("");
				*/
				$this->EmailModel->sendEmail($email_template, $emaildata);
				$this->adminMsgsession['successMsg']='Transaction is processed.';
				
			}else{
				$this->adminMsgsession['errorMsg']='Please check information again.';
			}
		}
		return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/'.$redirecttype);
	}
}