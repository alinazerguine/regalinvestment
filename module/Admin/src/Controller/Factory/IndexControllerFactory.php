<?php
namespace Admin\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\IndexController;
use Zend\Session\Container;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
	   $adminMsgsession = new Container(ADMIN_AUTH_NAMESPACE);	   
	   $AbstractModel=new \Application\Model\AbstractModel($container->get('Zend\Db\Adapter\Adapter'));	    
	   $UserModel=new \Admin\Model\User($container->get('Zend\Db\Adapter\Adapter'));	  
	   $EmailModel=new \Application\Model\Email($container->get('Zend\Db\Adapter\Adapter'));	
	   $Adapter=$container->get('Zend\Db\Adapter\Adapter');	   		
       return new IndexController($AbstractModel,$Adapter,$UserModel,$EmailModel,$adminMsgsession);
    }
}




