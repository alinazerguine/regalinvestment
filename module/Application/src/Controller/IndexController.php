<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Application\Model\AbstractModel;
use Zend\Session\Container;
use Zend\Mail\Message as MailMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Mime;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Db\Sql\Sql,
    Zend\Db\Sql\Where;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;



use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;

class IndexController extends AbstractActionController
{
	private $AbstractModel, $EmailModel, $Adapter,$front_Session,$authService;
	public function __construct($AbstractModel,$EmailModel,$Adapter,$front_Session,AuthenticationServiceInterface $authService)
    {
		$this->SuperModel = $AbstractModel;
		$this->front_Session = $front_Session;
		$this->EmailModel = $EmailModel;
		$this->Adapter = $Adapter;
		$this->loggedUser = $authService->getIdentity();
		$this->authService = $authService;
		
		$this->pageType='';
    }

    public function indexAction()
    {
		$page_content=$this->SuperModel->Super_Get("pages","page_id='9'","fetch");
		
		$page_content['page_content']=str_ireplace(array('{img_url}','{site_path}'),array(HTTP_IMG_PATH,APPLICATION_URL),$page_content['page_content']);
		$this->layout()->setVariable('pageTag','home');
		
		$page_media=$this->SuperModel->Super_Get("home_media","1","fetchall");
    	$view = new ViewModel(array('page_content'=>$page_content['page_content'],'page_media'=>$page_media));
		return $view;
	}
	
	
	
	
	
	
	public function errorpageAction()
	{
		$this->layout()->setVariable('pageHeading','error');
		
		$view = new ViewModel();		
		return $view;
	}
}
