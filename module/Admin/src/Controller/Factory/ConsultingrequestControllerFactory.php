<?php
namespace Admin\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\ConsultingrequestController;
use Zend\Session\Container;

class ConsultingrequestControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
	   $adminMsgsession = new Container(ADMIN_AUTH_NAMESPACE);	   
	   $AbstractModel=new \Application\Model\AbstractModel($container->get('Zend\Db\Adapter\Adapter'));	    
	   $UserModel=new \Admin\Model\User($container->get('Zend\Db\Adapter\Adapter'));	  
	   $EmailModel=new \Application\Model\Email($container->get('Zend\Db\Adapter\Adapter'));	
	   $Adapter=$container->get('Zend\Db\Adapter\Adapter');	   
	   $site_config_data=$AbstractModel->Super_Get('config','1=1','fetchAll');	
	    foreach($site_config_data as $key=>$config){
			$config_data[$config['config_key']]= $config['config_value'] ;
			$config_groups[$config['config_group']][$config['config_key']]=$config['config_value'];	
	    }	  		
       return new ConsultingrequestController($AbstractModel,$Adapter,$adminMsgsession,$EmailModel,$config_data);
    }
}




