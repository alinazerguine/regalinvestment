<?php
namespace Application\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\IndexController;
use Zend\Session\Container;
use Zend\Authentication\AuthenticationServiceInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {	   
	   $AbstractModel=new \Application\Model\AbstractModel($container->get('Zend\Db\Adapter\Adapter'));	
	   
	   $EmailModel=new \Application\Model\Email($container->get('Zend\Db\Adapter\Adapter'));	
	   $Adapter=$container->get('Zend\Db\Adapter\Adapter');	 
	  
	   $front_Session = new Container(DEFAULT_AUTH_NAMESPACE);
	   
	   $site_config_data=$AbstractModel->Super_Get('config','1=1','fetchAll');	
		foreach($site_config_data as $key=>$config){
			$config_data[$config['config_key']]= $config['config_value'] ;
			$config_groups[$config['config_group']][$config['config_key']]=$config['config_value'];	
		}
		$payPalModel=new \Application\Model\Paypalrecurring($config_data);	  
	   
	   $authService = $container->get('Zend\Authentication\AuthenticationServiceInterface');
	   return new IndexController($AbstractModel,$EmailModel,$Adapter,$front_Session,$authService,$payPalModel);
    }
}




