<?php

namespace NetglueMaintenanceMode\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\View\Model\ConsoleModel;
use Zend\Console\ColorInterface;

class ConsoleController extends AbstractActionController
{

    public function toggleAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('Console environment required');
        }
        $verbose = $request->getParam('verbose') || $request->getParam('v');

        $view = new ConsoleModel;

        $config = $this->getServiceLocator()->get('Config');
        $console = $this->getServiceLocator()->get('console');

        // Check lock file location is set
        if(!isset($config['maintenance-mode']['lock-file'])) {
            $view->setErrorLevel(1);
            $console->writeLine('Lock file is not set', ColorInterface::WHITE, ColorInterface::RED);
            return $view;
        }

        $lockFile = $config['maintenance-mode']['lock-file'];

        if(!is_writable(dirname($lockFile))) {
            $view->setErrorLevel(1);
            $console->writeLine('Cannot write to parent directory '.dirname($lockFile), ColorInterface::WHITE, ColorInterface::RED);
            return $view;
        }

        if(file_exists($lockFile)) {
            unlink($lockFile);
            if($verbose) {
                $console->writeLine('Maintenance mode disabled', ColorInterface::GREEN);
            }
        } else {
            touch($lockFile);
            if($verbose) {
                $console->writeLine('Maintenance mode enabled', ColorInterface::GREEN);
            }
        }
        return $view;
    }

}
