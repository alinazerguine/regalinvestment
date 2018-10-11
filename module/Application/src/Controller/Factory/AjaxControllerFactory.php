<?php
namespace Application\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\AjaxController;
use Zend\Session\Container;


class AjaxControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {	
	
	   $Adapter=$container->get('Zend\Db\Adapter\Adapter');	  
	   return new AjaxController($Adapter);
    }
}




