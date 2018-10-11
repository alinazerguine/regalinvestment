<?php
namespace AuthAcl\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use AuthAcl\Controller\IndexController;
use Zend\Session\Container;

use Zend\Authentication\AuthenticationService;
use Zend\Session\SessionManager;
use Zend\Authentication\AuthenticationServiceInterface;


class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
	   
		$front_Session = new Container(DEFAULT_AUTH_NAMESPACE);	   
		$AbstractModel=new \Application\Model\AbstractModel($container->get('Zend\Db\Adapter\Adapter'));	    
		$UserModel=new \AuthAcl\Model\User($container->get('Zend\Db\Adapter\Adapter'));
		$EmailModel=new \Application\Model\Email($container->get('Zend\Db\Adapter\Adapter'));	
		
		$site_config_data=$AbstractModel->Super_Get('config','1=1','fetchAll');	
		foreach($site_config_data as $key=>$config){
			$config_data[$config['config_key']]= $config['config_value'] ;
			$config_groups[$config['config_group']][$config['config_key']]=$config['config_value'];	
		}
		
		$Adapter=$container->get('Zend\Db\Adapter\Adapter');	
		$authService = $container->get('Zend\Authentication\AuthenticationServiceInterface');
		
		return new IndexController($AbstractModel,$Adapter,$UserModel,$front_Session,$EmailModel,$config_data,$authService);
    }
}