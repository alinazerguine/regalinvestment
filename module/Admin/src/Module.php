<?php
namespace Admin;
// Add these import statements:
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleManager;

class Module implements ConfigProviderInterface
{
	public function getConfig()
	{
		return include __DIR__ . '/../config/module.config.php';
	}

	public function init(ModuleManager $mm)
    {
    	$mm->getEventManager()->getSharedManager()->attach(__NAMESPACE__,
    	'dispatch', function($e) {
    		$e->getTarget()->layout('layout/admin/layout');
    	});
	}

	public function getControllerConfig()
    {
		
        return [
            'factories' => [
                Controller\IndexController::class => function($container) {
                    return new Controller\IndexController(
                        $container->get(Model\AdminTable::class)
                    );
                },
				Controller\ProfileController::class => function($container) {
                    return new Controller\ProfileController(
                        $container->get(Model\AdminTable::class)
                    );
                },
				Controller\StaticController::class => function($container) {
                    return new Controller\StaticController(
                        $container->get(Model\AdminTable::class)
                    );
                },
				Controller\AjaxController::class => function($container) {
                    return new Controller\AjaxController(
                        $container->get(Model\AdminTable::class)
                    );
                },
				Controller\SliderController::class => function($container) {
                    return new Controller\SliderController(
                        $container->get(Model\AdminTable::class)
                    );
                },
				Controller\ConsultingrequestController::class => function($container) {
                    return new Controller\ConsultingrequestController(
                        $container->get(Model\AdminTable::class)
                    );
                },
				
            ],
        ];
    }
}