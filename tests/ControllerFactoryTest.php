<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use Sebaks\Controller\ValidatorInterface;
use Sebaks\Controller\ServiceInterface;
use Sebaks\Controller\RequestInterface;
use Sebaks\Controller\ResponseInterface;
use Sebaks\ZendMvcController\Controller;
use Sebaks\ZendMvcController\ControllerFactory;
use Sebaks\ZendMvcController\ErrorInterface;

class ControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);


        $controllerManager = $this->prophesize(ControllerManager::class);
        $controllerManager->getServiceLocator()->willReturn($serviceLocator);

        $serviceLocator->get('sebaks-zend-mvc-criteria-validator-factory')
            ->willReturn($this->prophesize(ValidatorInterface::class)->reveal());

        $serviceLocator->get('sebaks-zend-mvc-changes-validator-factory')
            ->willReturn($this->prophesize(ValidatorInterface::class)->reveal());

        $serviceLocator->get('sebaks-zend-mvc-service-factory')
            ->willReturn($this->prophesize(ServiceInterface::class)->reveal());

        $serviceLocator->get('sebaks-zend-mvc-view-model-factory')
            ->willReturn($this->prophesize('Zend\View\Model\ViewModel')->reveal());

        $serviceLocator->get('sebaks-zend-mvc-request-factory')
            ->willReturn($this->prophesize(RequestInterface::class)->reveal());

        $serviceLocator->get('sebaks-zend-mvc-response-factory')
            ->willReturn($this->prophesize(ResponseInterface::class)->reveal());

        $serviceLocator->get('sebaks-zend-mvc-html-error-factory')
            ->willReturn($this->prophesize(ErrorInterface::class)->reveal());

        $serviceLocator->get('sebaks-zend-mvc-options-factory')
            ->willReturn([]);

        $factory = new ControllerFactory();

        $controller = $factory->createService($controllerManager->reveal());

        $this->assertInstanceOf(Controller::class, $controller);
    }
}
