<?php

namespace NetglueMaintenanceMode;

/**
 * Autoloader
 */
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Config Provider
 */
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * BootstrapListener
 */
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;

/**
 * Console Usage
 */
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

/**
 * Mvc Event
 */
use Zend\Mvc\MvcEvent;
use Zend\Http\Request as HttpRequest;

/**
 * Controller Provider
 */
use Zend\ModuleManager\Feature\ControllerProviderInterface;


class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    BootstrapListenerInterface,
    ConsoleUsageProviderInterface,
    ControllerProviderInterface
{

    /**
     * Bootstrap Listener
     * @param EventInterface $event
     * @return void
     */
    public function onBootstrap(EventInterface $event)
    {

        $request = $event->getRequest();
		if(!$request instanceof HttpRequest) {
			return;
		}

        $application    = $event->getApplication();
        $serviceLocator = $application->getServiceManager();
        $config         = $serviceLocator->get('Config');

        if (isset($config['maintenance-mode']['lock-file'])) {
            if (!file_exists($config['maintenance-mode']['lock-file'])) {
                return;
            }
        }

        $response = $event->getResponse();

        $response->setStatusCode($config['maintenance-mode']['status-code']);
        $response->getHeaders()->addHeaders(['Content-Type' => $config['maintenance-mode']['response-type']]);
        $response->setContent(file_get_contents($config['maintenance-mode']['response-file']));
        $exit = function($event) use ($response) {
            return $response;
        };
        // Attach before routing occurs (Priority higher than 1)
        $application->getEventManager()->attach(MvcEvent::EVENT_ROUTE, $exit, 10);
    }

    /**
     * Include/Return module configuration
     * @return array
     * @implements ConfigProviderInterface
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * Return autoloader configuration
     * @link http://framework.zend.com/manual/2.0/en/user-guide/modules.html
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            AutoloaderFactory::STANDARD_AUTOLOADER => [
                StandardAutoloader::LOAD_NS => [
                    __NAMESPACE__ => __DIR__,
                ]
            ]
        ];
    }


    public function getConsoleUsage(Console $console)
    {
        return [
            'maintenance [--verbose|-v]' => 'Toggle maintenance mode',
                ['--verbose|-v', '(Optional) increase verbosity']
        ];
    }

    /**
     * Return Controller Config
     * @return array
     */
    public function getControllerConfig()
    {
        return array(
            'invokables' => array(
                'NetglueMaintenanceMode\Controller\ConsoleController' => 'NetglueMaintenanceMode\Controller\ConsoleController'
            ),
        );
    }

}
