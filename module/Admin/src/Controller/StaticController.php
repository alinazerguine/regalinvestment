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
use Admin\Model\User;
use Zend\Db\Sql\Expression;
class StaticController extends AbstractActionController
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
	/* upload images of job*/
	public function uploadimageAction()
	{
		
		/*$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);*/
		$options = array();
		
		
		$path=RESOURCE_PATH.'/temp/files/';
		
		if (!file_exists($path)) {
 		   mkdir($path, 0777, true);
		}
		//prd($_GET);
		//prn($_GET['file']);
		//prd(HTTP_UPLOADS_PATH);
		if(isset($_GET['file']) && $_GET['file'] != ""){
			unlink($path.$_GET['file']);
		}
		
		$options['script_url'] = ADMIN_APPLICATION_URL.'/uploadimage';
		//$options['deleteUrl'] = ADMIN_APPLICATION_URL.'/uploadimage';
		
		
		$options['upload_dir'] = $path;
		$options['upload_url'] = HTTP_RESOURCE_PATH.'/temp/files/';

		$imgUpload = $this->UploadHandler();
		$imageUpload=$imgUpload->construct($options);
		
		exit;
	}
	
	public function resourcesAction(){
			
		$this->layout()->setVariable('pageHeading', 'Resources');
		$this->layout()->setVariable('pageDescription', 'Resources');
		//$request = $this->getRequest();
		// if($request->isPost()) {
			 //prd($request);
		 //}
		$view = new ViewModel(array('page_icon'=>'fa fa-file','pageHeading'=>'Resources'));
		return $view;
		
		
	}
	public function getresourcesAction()
	{
  			
		$dbAdapter = $this->Adapter;
		$aColumns = array('res_id','res_title','res_modified');
		
		$sIndexColumn = 'res_id';
		$sTable = 'resource';
  		
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
					$sWhere = "WHERE  ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= "".$aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
		}
		
		
		}/* End Table Setting */
		
		
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   $sTable $sWhere $sOrder $sLimit";   
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
  			$row[]=$row1['res_title'];
			
			
			

			$row[]=getDateformaton($row1['res_modified']);
								
			$row[] =  '<a href="'.ADMIN_APPLICATION_URL.'/manageresource/'.$row1['res_id'].'" class="btn btn-xs btn-warning"> Edit <i class="fa fa-pencil"></i></a>';
			
			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	
	public function manageresourceAction(){
		
		$edit_id = $this->params()->fromRoute('res');
	    $form = new StaticForm();
		$form->resource();	
		$PageHeading='Add Resource';
		$title_where=1;
		if($edit_id!=''){
			$PageHeading='Edit Resource';
			$title_where='res_id="'.$edit_id.'"';
			$data=$this->SuperModel->Super_Get('resource',$title_where,'fetch');
			if(empty($data)){
			$this->adminMsgsession['errorMsg']='Invalid Request.';
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/resources');	
			}else{
				$form->populateValues($data);
				
				
			}
		}
		  $request = $this->getRequest();
	
        if($request->isPost()) {
          
		    $form->setData($request->getPost());
			
			
            if($form->isValid()){
            
			    $Formdata = $form->getData();  
				unset($Formdata['bttnsubmit']);
				$labelis='added';
				$Formdata['res_modified']=date("Y-m-d");
				if($edit_id!=''){
					$isInserted=$this->SuperModel->Super_Insert('resource',$Formdata,$title_where);
				    $labelis='updated';
				}else{
					$isInserted=$this->SuperModel->Super_Insert('resource',$Formdata);	
				}
				if(!empty($isInserted)){
					$this->adminMsgsession['successMsg']='Resources '.$labelis.' successfully.';
					return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/resources');	
				}else{
					$this->adminMsgsession['errorMsg']='Please check information again.';
				}
				
				
            }else{
				
				$this->adminMsgsession['errorMsg']='Please check information again.';
			}
			
        }
		
		$view = new ViewModel(array('form'=>$form,'page_icon'=>'fa fa-file','pageHeading'=>$PageHeading));
		$view->setTemplate('admin/admin/add.phtml');
		return $view;
	
		
		
	}
	public function removeresourceAction(){
		
		$request = $this->getRequest();
			
		if ($request->isPost()) {
			 $del = $request->getPost('resource');
			
			 foreach($del as $key=>$ids)
			 {  
				$isdeleted=$this->SuperModel->Super_Delete('resource','res_id ="'.$ids.'"');	 
			 } 
		}
		$this->adminMsgsession['successMsg']='Resource Deleted Successfully.';
		return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/resources');
		
	}
	
	public function emailtemplateAction(){
		$this->layout()->setVariable('pageHeading', 'Email Templates');
		$this->layout()->setVariable('pageDescription', 'Email Templates');
		$view = new ViewModel(array('page_icon'=>'fa fa-envelope','pageHeading'=>'Email Templates'));
		return $view;
	}
	
	public function getemailtemplateAction()
	{
  			
		$dbAdapter = $this->Adapter;
		$aColumns = array('emailtemp_key','emailtemp_title','emailtemp_subject','emailtemp_content','emailtemp_modified');

		$sIndexColumn = 'emailtemp_key';
		$sTable = 'email_templates';
  		
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
					$sWhere = "WHERE  ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= "".$aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
		}
		
		
		}/* End Table Setting */
		
		
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   $sTable $sWhere $sOrder $sLimit";      
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
			
  			$row[]=$row1['emailtemp_title'];
			
			$row[]=$row1['emailtemp_subject'];
			

			$row[]=getDateformaton($row1['emailtemp_modified']);
								
			$row[] =  '<a href="'.ADMIN_APPLICATION_URL.'/editemailtemplate/'.$row1['emailtemp_key'].'" class="btn btn-xs btn-warning"> Edit <i class="fa fa-pencil"></i></a>';
			
			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	
public function editemailtemplateAction(){
		$edit_id = $this->params()->fromRoute('emailtemp_key');
			
		if(!$edit_id){
			$this->adminMsgsession['errorMsg']='Invalid Request.';
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/emailtemplate');	
		}
		
        $data=$this->SuperModel->Super_Get('email_templates','emailtemp_key="'.$edit_id.'"','fetchAll');

        $form = new StaticForm();
		$form->emailtemplates();
		
		foreach($data as $key=>$data)
		{
			$form->get('emailtemp_title')->setValue($data['emailtemp_title']);
			$form->get('emailtemp_subject')->setValue($data['emailtemp_subject']);	
			$form->get('emailtemp_content')->setValue($data['emailtemp_content']);	
		}
		
        $request = $this->getRequest();
	
        if($request->isPost()) {
            $form->setData($request->getPost());
			
			
            if($form->isValid()){
              
			    $Formdata = $form->getData();
				unset($Formdata['Submit']);	
				$Formdata['emailtemp_modified'] = date('Y-m-d h:i:s');
				unset($Formdata['bttnsubmit']);
				$isInserted=$this->SuperModel->Super_Insert('email_templates',$Formdata,"emailtemp_key='".$edit_id."'");
				if(!empty($isInserted)){
					$this->adminMsgsession['successMsg']='Email Template Updated Successfully.';
				}else{
					$this->adminMsgsession['errorMsg']='Please check information again.';
				}
            }else{
				
				$this->adminMsgsession['errorMsg']='Please check information again.';
			}
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/emailtemplate');	
        }
		$view = new ViewModel(array('form'=>$form,'page_icon'=>'fa fa-edit','pageHeading'=>'Edit Email Template'));
		$view->setTemplate('admin/admin/add.phtml');
		return $view;
	}
	
	
	public function indexAction()
    {
		$this->layout()->setVariable('pageHeading', 'Site configuration');
		$this->layout()->setVariable('pageDescription', 'manage site configuration');
		$dbAdapter =$this->Adapter;
		
		$type = $this->params()->fromRoute('type');
		
		if($type=="config"){
			$data_type='SITE_CONFIG';
			$pageHeading='Site Configuration';
		}
		else if($type=="social"){
			$data_type='SOCIAL_LINKS';
			$pageHeading='Social Configuration';
		}
		else{
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/errorpage');
		}
		
		$configData=$this->SuperModel->Super_Get("config","config_group!='SITE_PLAN'","fetchAll");//prd($configData);
		
		$form = new StaticForm();
		$form->siteconfig($configData);	
		
		$homeVideo='';
		foreach($configData as $key=>$data){
			$form->get($data['config_key'])->setValue($data['config_value']);
		}
		
		$request = $this->getRequest();
		
	    if($request->isPost()) {
           $form->setData($request->getPost());
			
            if ($form->isValid()) { 
                $Formdata = $form->getData();
				$imagePlugin = $this->Image();				
				$files =  $this->getRequest()->getFiles()->toArray();

				$filename = $files['site_logo']['name'];
				$filename1 = $files['site_video']['name'];
				
				if($filename!=''){
					
					if($filename!=''){
						$imagePlugin = $this->Image();
						//unlink old video
						unlink(PROFILE_IMAGES_PATH.'/logo/'.$site_logo);							
						$is_uploaded_icon = $imagePlugin->universal_upload(array("directory"=>PROFILE_IMAGES_PATH.'/logo',"files_array"=>$files));	
											
						if(is_object($is_uploaded_icon)  and $is_uploaded_icon->success)
						{		 
							$Formdata['site_logo'] = $is_uploaded_icon->media_path;
						}	
					}
				}
				else{
					$Formdata['site_logo']=$this->SITE_CONFIG['site_logo'];
				}

				foreach($Formdata as $key=>$data)
				{
					$update_arr['config_value']=$data;
					$configData=$this->SuperModel->Super_Insert("config",$update_arr,'config_key="'.$key.'"');
				}
				
				$this->adminMsgsession['successMsg']='Information updated successfully';
                return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/static/'.$type);
            }
        }
		
		$view = new ViewModel(array('form'=>$form,'page_icon'=>'fa fa-cog','pageHeading'=>$pageHeading,'site_logo'=>$site_logo,'site_video'=>$site_video,'config_box'=>'yes'));		
		$view->setTemplate('admin/admin/add.phtml');		
		
		return $view;
	}
	/* faqs */
	public function faqsAction()
    {
		 return new ViewModel(array('page_icon'=>'fa fa-question-circle','pageHeading'=>"FAQ"));
    }
	
	public function getfaqsAction(){
  			
		$dbAdapter = $this->Adapter;
  
		$aColumns = array('faq_id','faq_order','faq_question','faq_answer','faq_added_date');

		$sIndexColumn = 'faq_id';
		$sTable = 'faq';
  		
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
			
			
  			$row[]=nl2br($row1['faq_question']);
			$row[]=nl2br($row1['faq_answer']);
			$row[]=getDateformaton($row1['faq_added_date']);
								
			$row[] =  '<a href="'.ADMIN_APPLICATION_URL.'/managefaqs/'.$row1['faq_id'].'" class="btn btn-xs btn-warning"> Edit <i class="fa fa-pencil"></i></a>';
						
  			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	
	public function managefaqsAction(){
		
		$edit_id = $this->params()->fromRoute('faq');
	    $form = new StaticForm();
		$form->faqs();	
		$PageHeading='Add Faq';
		if($edit_id!=''){
			$PageHeading='Edit Faq';
			$data=$this->SuperModel->Super_Get('faq','faq_id="'.$edit_id.'"','fetch');
			if(empty($data)){
			$this->adminMsgsession['errorMsg']='Invalid Request.';
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/faqs');	
			}else{
				$form->get('faq_question')->setValue($data['faq_question']);
				$form->get('faq_answer')->setValue($data['faq_answer']);	
				
			}
		}
		
     

    
		
		
        $request = $this->getRequest();
	
        if($request->isPost()) {
          
		    $form->setData($request->getPost());
			
			
            if($form->isValid()){
              
			    $Formdata = $form->getData();
				
				$Formdata['faq_added_date'] = date('Y-m-d h:i:s');
				unset($Formdata['bttnsubmit']);
					$labelis='added';
				if($edit_id!=''){
				$isInserted=$this->SuperModel->Super_Insert('faq',$Formdata,"faq_id='".$edit_id."'");
				$labelis='updated';
				}else{
					$getMaxOrder=$this->SuperModel->Super_Get("faq","1","fetch",array("fields"=>array("max_order"=>new Expression("max(faq_order)+1"))));
					$Formdata["faq_order"]=$getMaxOrder["max_order"];
					$isInserted=$this->SuperModel->Super_Insert('faq',$Formdata);	
				}
				if(!empty($isInserted)){
					$this->adminMsgsession['successMsg']='FAQ '.$labelis.' successfully.';
				}else{
					$this->adminMsgsession['errorMsg']='Please check information again.';
				}
            }else{
				
				$this->adminMsgsession['errorMsg']='Please check information again.';
			}
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/faqs');	
        }
		
		$view = new ViewModel(array('form'=>$form,'page_icon'=>'fa fa-question-circle','pageHeading'=>$PageHeading));
		$view->setTemplate('admin/admin/add.phtml');
		return $view;
	
		
		
	}
	public function orderfaqsAction()
	{
		
		$i=1;
		$request = $this->getRequest();
		if ($request->isPost()) {
			 $reqList = $request->getPost();
			 $sortedList=chop($reqList[0],",");
			foreach(explode(',',$sortedList) as $sortedArr){
				$this->SuperModel->Super_Insert('faq',array('faq_order'=>$i),"faq_id='".$sortedArr."'");
				$i++;	
			}
			
			//prd($del);
		}
		
		exit();
	}
	public function removefaqsAction(){
		
		$request = $this->getRequest();
			
		if ($request->isPost()) {
			 $del = $request->getPost('faq');
			
			 foreach($del as $key=>$ids)
			 {  
				$isdeleted=$this->SuperModel->Super_Delete('faq','faq_id ="'.$ids.'"');	 
			 } 
		}
		$this->adminMsgsession['successMsg']='Faq Deleted Successfully.';
		return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/faqs');
		
	}
	
	/* Jobs */
	public function removejobapplyAction(){
		
		$request = $this->getRequest();
			
		if ($request->isPost()) {
			 $del = $request->getPost('job_apply');
			
			 foreach($del as $key=>$ids)
			 {  
				$getAtttachement=$this->SuperModel->Super_Get('job_apply','job_app_id ="'.$ids.'"',"fetch");
				if(!empty($getAtttachement)){
					$imagePlugin = $this->Image();
						//unlink old video
												
					$is_uploaded_icon = $imagePlugin->universal_unlink($getAtttachement["job_app_file"],array("directory"=>APPLY_IMAGES_PATH));	
						
				}
				$isdeleted=$this->SuperModel->Super_Delete('job_apply','job_app_id ="'.$ids.'"');	 
			 } 
		}
		$this->adminMsgsession['successMsg']='Application Deleted Successfully.';
		return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/jobapply');
		
	}
	public function jobapplyAction(){
		$job_id = $this->params()->fromRoute('job');
		$apply_id = $this->params()->fromRoute('apply');
		
		return new ViewModel(array('page_icon'=>'fa fa-tasks','pageHeading'=>"Job Apply","job"=>$job_id,"apply"=>$apply_id));
	}
	public function getjobapplyAction(){
  		$job_id = $this->params()->fromRoute('job');
		$apply_id = $this->params()->fromRoute('apply');
		
		if($job_id!=''){$job_id=myurl_decode($job_id);}
		if($apply_id!=''){$apply_id=myurl_decode($apply_id);}
		$dbAdapter = $this->Adapter;
  
		$aColumns = array('job_app_id','job_app_id','job_app_name','job_app_email','job_app_phone','job_app_detail','job_app_file','job_app_appliedon');

		$sIndexColumn = 'job_app_id';
		$sTable = 'job_apply';
  		
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
		
		//
		//echo $apply_id;die;	
			
		if($job_id!='' && $job_id!=0){
			if($sWhere==''){
				$sWhere=' where job_app_jobid="'.$job_id.'"';	
			}else{
				$sWhere.=' and job_app_jobid="'.$job_id.'"';	
			}
		}
		if($apply_id!='' && $apply_id!=0){
			if($sWhere==''){
				$sWhere=' where job_app_id="'.$apply_id.'"';	
			}else{
				$sWhere.=' and job_app_id="'.$apply_id.'"';	
			}
		}
	
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
			
			
  			$row[]=$row1['job_app_name'];
			$row[]=$row1['job_app_email'];
			$row[]=$row1['job_app_phone'];
			$row[]=nl2br($row1['job_app_detail']);
			$row[]=$row1['job_app_file']!=''?'<a href="'.HTTP_APPLY_IMAGES_PATH."/".$row1['job_app_file'].'" download>Download</a>':"N/A";
			
			$row[]=getDateformaton($row1['job_app_appliedon']);
								
			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	public function jobsAction()
    { 
		 return new ViewModel(array('page_icon'=>'fa fa-tasks','pageHeading'=>"Jobs"));
    }
	public function getjobsAction(){
  			
		$dbAdapter = $this->Adapter;
  
		$aColumns = array('job_id','job_title','job_desc','job_added_date');

		$sIndexColumn = 'job_id';
		$sTable = 'job';
  		
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
			
			
  			$row[]=nl2br($row1['job_title']);
			
			$row[]=getDateformaton($row1['job_added_date']);
								
			$row[] =  '<a href="'.ADMIN_APPLICATION_URL.'/managejobs/'.$row1['job_id'].'" class="btn btn-xs btn-warning"> Edit <i class="fa fa-pencil"></i></a>&nbsp;<a href="'.ADMIN_APPLICATION_URL.'/jobapply/'.myurl_encode($row1['job_id']).'" class="btn btn-xs btn-info"> Application <i class="fa fa-tasks"></i></a>';
						
  			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	public function managejobsAction(){
		
		$edit_id = $this->params()->fromRoute('job');
		
	    $form = new StaticForm();
		$form->jobs();	
		$PageHeading='Add Job';
		if($edit_id!=''){
			$PageHeading='Edit Job';
			$data=$this->SuperModel->Super_Get('job','job_id="'.$edit_id.'"','fetch');
			if(empty($data)){
			$this->adminMsgsession['errorMsg']='Invalid Request.';
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/jobss');	
			}else{
				$form->get('job_title')->setValue($data['job_title']);
				$form->get('job_desc')->setValue($data['job_desc']);	
				
			}
		}
		
     

    
		
		
        $request = $this->getRequest();
	
        if($request->isPost()) {
          
		    $form->setData($request->getPost());
			
			
            if($form->isValid()){
              
			    $Formdata = $form->getData();
				
				$Formdata['job_added_date'] = date('Y-m-d h:i:s');
				unset($Formdata['bttnsubmit']);
					$labelis='added';
				if($edit_id!=''){
				$isInserted=$this->SuperModel->Super_Insert('job',$Formdata,"job_id='".$edit_id."'");
				$labelis='updated';
				}else{
					$isInserted=$this->SuperModel->Super_Insert('job',$Formdata);	
				}
				if(!empty($isInserted)){
					$this->adminMsgsession['successMsg']='JOB '.$labelis.' successfully.';
				}else{
					$this->adminMsgsession['errorMsg']='Please check information again.';
				}
            }else{
				
				$this->adminMsgsession['errorMsg']='Please check information again.';
			}
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/jobs');	
        }
		
		$view = new ViewModel(array('form'=>$form,'page_icon'=>'fa fa-tasks','pageHeading'=>$PageHeading));
		$view->setTemplate('admin/admin/add.phtml');
		return $view;
	
		
		
	}
	
	public function removejobsAction(){
		
		$request = $this->getRequest();
			
		if ($request->isPost()) {
			 $del = $request->getPost('job');
			
			 foreach($del as $key=>$ids)
			 {  
				$isdeleted=$this->SuperModel->Super_Delete('job','job_id ="'.$ids.'"');	 
			 } 
		}
		$this->adminMsgsession['successMsg']='Job Deleted Successfully.';
		return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/jobs');
		
	}
	
	/* pages */
	public function instructionAction()
    {
		 return new ViewModel(array('page_icon'=>'fa fa-code','pageHeading'=>"Instruction"));
    }
	public function pagesAction()
    {
		 return new ViewModel(array('page_icon'=>'fa fa-file-text-o','pageHeading'=>"Pages"));
    }
	public function getpagesAction(){
  			
		$dbAdapter = $this->Adapter;
  		$page_type = $this->params()->fromRoute('page');
	
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
		
		if($page_type==''){
			$page_type='0';
		}
		if($sWhere==''){
			$sWhere='where page_type="'.$page_type.'"';	
		}else{
			$sWhere.=' and page_type="'.$page_type.'"';
		}
		
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
			
			
			
  			$row[]=$row1['page_title'];
			
			$row[]=getDateformaton($row1['page_updated']);
								
			$row[] =  '<a href="'.ADMIN_APPLICATION_URL.'/editpages/'.$row1['page_id'].'" class="btn btn-xs btn-warning"> Edit <i class="fa fa-pencil"></i></a>';
						
  			$output['aaData'][] = $row;
			$j++;
		}	
		
		echo json_encode( $output );
		exit();
 	} 
	public function addpagesAction(){
		
		$form = new StaticForm();
		$form->pages();
		$request = $this->getRequest();
		
		 if($request->isPost()) {
            $form->setData($request->getPost());
            if($form->isValid()){
                $Formdata = $form->getData();
				$Formdata['page_slug']=str_replace("'","",str_replace(" ","_",$Formdata['page_title']));
				
				unset($Formdata['bttnsubmit']);	
				
				$isInserted=$this->SuperModel->Super_Insert('pages',$Formdata);
				if(!empty($isInserted)){
					$this->flashMessenger()->addMessage(array(
						'success' => 'Page inserted Successfully.'
					));
					return $this->redirect()->toRoute('pages', array(
						 'action' => 'pages'
					 ));
				}else{
					$this->adminMsgsession['errorMsg']='Please check information again.';
				}
            }else{
				$this->adminMsgsession['errorMsg']='Please check information again.';
			}
        }
       	$view = new ViewModel(array('form'=>$form,'page_icon'=>'fa fa-plus','pageHeading'=>'Add Page'));
		$view->setTemplate('admin/admin/add.phtml');
		return $view;
		
	}
	
	public function editpagesAction(){
		
		$page_id = (int) $this->params()->fromRoute('id', 0);
		
		if(!$page_id){
			$this->flashMessenger()->addMessage(array(
				'error' => 'Invalid Request.'
			));
			
			return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'static/pages');	
		}
		
        $data=$this->SuperModel->Super_Get('pages','page_id="'.$page_id.'"','fetchAll');
        $form = new StaticForm();
		$form->pages($page_id);
		if($page_id=='9'){
			//$form->home_videos();	
		}
		
		if($page_id==6){
			$PagesImages = $this->SuperModel->Super_Get("pages_images","page_type='why_join'","fetchAll");
		}
		
		if($page_id==5){
			$PagesImages = $this->SuperModel->Super_Get("pages_images","page_type='about_us'","fetchAll");
		}
		
		foreach($data as $key=>$data)
		{
			$form->get('page_title')->setValue($data['page_title']);
			$page_content=$data['page_content'];
			$form->get('page_content')->setValue($page_content);	
		}
		$home_mediaData=array();
		if($page_id==9){
			$home_mediaData=$this->SuperModel->Super_Get("home_media","1","fetchall");	
		}
        $request = $this->getRequest();
        if($request->isPost()) {
            $form->setData($request->getPost());
            if($form->isValid()){
                $Formdata = $form->getData();
				if($page_id===9){
					$files =  $request->getFiles()->toArray();
					if(!empty($files)){ 
						$imagePlugin = $this->Image();
						
						$is_uploaded=$imagePlugin->universal_upload(array("directory"=>ROOT_PATH.'/public/resources/home_images/',"files_array"=>$files,"multiple"=>"1", 'thumbs'=>array(
												 	'160'=>array(
														 "width"=>160,
														 "height"=>160,
													  ),
													 'thumb'=>array(
														 "width"=>1349,
														 "height"=>608,
													  ),
													  
											)));
						
						
						if($is_uploaded->success){
							foreach($is_uploaded->media_path as $key=>$value){ 
								
									if($value['media_path']!='')
									{
										$this->SuperModel->Super_Insert("home_media",array("hom_med_value"=>$value['media_path']),"hom_med_key='".$key."'");								
									}
								
							}
						}
				}
				unset($Formdata['home_banner_image'],$Formdata['business_support_image'],$Formdata['business_support_video'],$Formdata['business_strategy_image'],$Formdata['business_strategy_video'],$Formdata['company_professional_image'],$Formdata['company_professional_video'],$Formdata['client_video_image'],$Formdata['client_video']);
				}
				
				
				//$Formdata['page_slug']=str_replace("'","",str_replace(" ","_",$Formdata['page_title']));
				
				//prd($Formdata);
				if($page_id==6 || $page_id==5){
					$files =  $request->getFiles()->toArray();	
	
					if(!empty($files)){
						
						$imagePlugin = $this->Image();
						$is_uploaded=$imagePlugin->universal_upload(array("directory"=>ROOT_PATH.'/public/resources/static_pages_images/',"files_array"=>$files,"multiple"=>"1"));	
						
						if($page_id==6){
							$page_key = 'why_join';
						}else{
							$page_key = 'about_us';
						}
						
						if($is_uploaded->success){
							foreach($is_uploaded->media_path as $key=>$value){
								if($value['media_path']!=''){
									$this->SuperModel->Super_Insert('pages_images',array('page_img'=> $value['media_path']),"page_type='".$page_key."' and page_img_type='".$value['element']."'");
								}
							}
						}
					}
				}

				unset($Formdata['bttnsubmit']);	
				unset($Formdata['banner_image']);
				unset($Formdata['video']);	
				unset($Formdata['place_you_belong']);	
				unset($Formdata['activities']);	
				unset($Formdata['dating']);	
				unset($Formdata['icecream']);
				unset($Formdata['event_hosts']);
				
				$isInserted=$this->SuperModel->Super_Insert('pages',$Formdata,"page_id='".$page_id."'");
				if(!empty($isInserted)){
					$this->adminMsgsession['successMsg']='Content Updated Successfully.';
					
					if($data['page_type']=='1'){
						return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/instruction');	
					}
					return $this->redirect()->tourl(ADMIN_APPLICATION_URL.'/pages');	
				}else{
					$this->adminMsgsession['errorMsg']='Please check information again.';
					
				}
            }else{
				$this->adminMsgsession['errorMsg']='Please check information again.';
			}
        }
		
		$icon='fa fa-edit';$pag_title='Edit Page';
		if($data['page_type']=='1'){
			$icon='fa fa-code';$pag_title='Edit Instruction';
		}
		$view = new ViewModel(array('form'=>$form,'page_icon'=>$icon,'pageHeading'=>$pag_title,'PagesImages'=>$PagesImages,"page_id"=>$page_id,'home_mediaData'=>$home_mediaData));
		$view->setTemplate('admin/admin/add.phtml');
		return $view;
		
	}
	
	
	
	
	
	
	
	
}