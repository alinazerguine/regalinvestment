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
use Admin\Form\ConsultingReqForm;
use Zend\Db\Sql\Expression;
class ConsultingrequestController extends AbstractActionController
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
	
	
	/* investment plans */
	public function consavailabletimesettingAction(){
	 
			//prD(	$config_groupData);
		$ConsAvailabletimeData=$this->SuperModel->Super_Get("consulting_available_time_setting","cons_time_setting_id='1'","fetch");
		
		$form = new ConsultingReqForm();
		$form->availabletimesettingFrm();
		
		if(!empty($ConsAvailabletimeData))
		{
			$ConsAvailabletimeData['open_time']= date('H:i',strtotime($ConsAvailabletimeData['open_time']));
			$ConsAvailabletimeData['close_time']= date('H:i',strtotime($ConsAvailabletimeData['close_time']));	
			if(!empty($ConsAvailabletimeData['closing_days'])){
				$ConsAvailabletimeData['closing_days'] = explode(',',$ConsAvailabletimeData['closing_days']);
			}
			$form->populateValues($ConsAvailabletimeData);
		}
		
		$request = $this->getRequest();
		if($request->isPost()) {
		
		$postData = $request->getPost();
			if(!isset($postData['closing_days'])){
				$postData['closing_days'] = 0;
			}
			$errorInclosingDays='';
		 $form->setData($request->getPost());
			 if($form->isValid()){
					$Formdata = $form->getData();
				
					if(!empty($Formdata['closing_days'])){
						foreach($Formdata['closing_days'] as $Value){
							$currentdate=date('Y-m-d');
							
							$consult_request_date=$this->SuperModel->Super_Get("consulting_send_request","DATE_FORMAT(consult_request_date,'%d') = DATE_FORMAT('2017-12-".$Value."','%d') and consult_request_date >= '".$currentdate."'","fetch");
							if(empty($consult_request_date)){
								$CloseDays.=$Value.',';
							}else{
								if($errorInclosingDays==''){$errorInclosingDays=$consult_request_date['consult_request_date'];}
								else{$errorInclosingDays=$errorInclosingDays.','.$consult_request_date['consult_request_date'];}	
							}
						}
						
						$Formdata['closing_days']=rtrim($CloseDays,',');
					}else{
						$Formdata['closing_days']=NULL;
					}
					
					//pr($Formdata);
					$isUpdated 	= $this->SuperModel->Super_Insert("consulting_available_time_setting",$Formdata,"cons_time_setting_id='1'");
					//$test = $this->SuperModel->Super_Insert("consulting_available_time_setting",$Formdata);
					//prd($isUpdated );
					if($isUpdated->success){
						if($errorInclosingDays!=''){
							$this->adminMsgsession['infoMsg']="Bookings are available on these dates <i>".$errorInclosingDays.'</i> so time setting cannot updated.';	
						}else{
						$this->adminMsgsession['successMsg']='Consulting Availability Time Setting Updated Successfully.';
						}
						return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/cons-availabletime-setting');
					 }
					// prd($Formdata);
			 }else{
				/* pr($form->getMessages());
				 prd(gcm($form));*/
			 }
		}
		return new ViewModel(array('page_icon'=>'fa fa-clock-o','pageHeading'=>"Availability Time for Consulting Request",'SITE_CONFIG'=>$this->SITE_CONFIG,'form'=>$form,'setting_data'=>$ConsAvailabletimeData));
	}
	
	
	
	public function viewconsultingrequestAction()
    {
		$consult= $this->params()->fromRoute("consult"); 		 
		return new ViewModel(array('page_icon'=>'fa fa-eye','pageHeading'=>"View Consulting Request","consult"=>$consult));
    }
	
	public function getconsultingrequestAction(){
  			$consult= $this->params()->fromRoute("consult"); 		
		$dbAdapter = $this->Adapter;
  
		$aColumns = array(
		'consulting_send_request.consult_id',
		'consulting_send_request.consult_user_id',
		'consulting_send_request.consult_request_date',
		'consulting_send_request.consult_start_time',
		'consulting_send_request.consult_end_time',
		'consulting_send_request.consult_time_slot_type',
		'consulting_send_request.consult_status',
		'consulting_send_request.consult_req_added_on',
		'users.user_name'
		);

		$sIndexColumn = 'consult_id';
		$sTable = 'consulting_send_request';
  		
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
		if($consult!='' && $consult!=0){
			if($sWhere==''){
				$sWhere=' where consult_id="'.$consult.'"';
			}else{
				$sWhere.=' and consult_id="'.$consult.'"';
			}
			
		}
		$leftJoin = "LEFT JOIN users ON users.user_id = ".$sTable.".consult_user_id ";
		
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   $sTable $leftJoin $sWhere $sOrder $sLimit"; 
		//echo $sQuery;die;
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
			
			
			
  			//$row[]=nl2br($row1['inv_pl_title']);
			$row[]=$row1['user_name'];
			$row[]=$row1['consult_request_date'];
			$row[]=date('H:i',strtotime($row1['consult_start_time'])).' - '.date('H:i',strtotime($row1['consult_end_time']));
								
		/*<h6>	$row[] =  '<a href="'.ADMIN_APPLICATION_URL.'/manageplan/'.$row1['inv_pl_id'].'" class="btn btn-xs btn-warning"> Edit <i class="fa fa-pencil"></i></a>';</h6>*/
						
  			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	
}