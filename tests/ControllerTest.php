<?php

namespace Sebaks\ZendMvcControllerTest;

use Sebaks\Controller\RequestInterface;
use Sebaks\Controller\ResponseInterface;
use Sebaks\Controller\Controller as SebaksController;
use Sebaks\ZendMvcController\Controller;
use Sebaks\ZendMvcController\ErrorInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    private $request;
    private $response;
    private $viewModel;
    private $error;
    private $event;

    /**
     * @var Controller
     */
    private $controller;

    public function setUp()
    {
        $this->request = $this->prophesize(RequestInterface::class);
        $this->response = $this->prophesize(ResponseInterface::class);
        $this->viewModel = $this->prophesize('Zend\View\Model\ViewModel');
        $sebaksController = $this->prophesize(SebaksController::class);
        $this->error = $this->prophesize(ErrorInterface::class);
        $this->event = new MvcEvent();
        $this->event->setRouteMatch(new RouteMatch([]));

        $this->controller = new Controller(
            $this->request->reveal(),
            $this->response->reveal(),
            $this->viewModel->reveal(),
            $sebaksController->reveal(),
            $this->error->reveal()
        );

        $sebaksController->dispatch($this->request->reveal(), $this->response->reveal())->willReturn(null);
    }

    public function testOnDispatch()
    {
        $data = ['key' => 'value'];
        $this->response->getCriteriaErrors()->willReturn([]);
        $this->response->getChangesErrors()->willReturn([]);
        $this->response->getRedirectTo()->willReturn(null);
        $this->response->toArray()->willReturn($data);
        $this->viewModel->setVariables($data)->willReturn(null);

        $result = $this->controller->onDispatch($this->event);

        $this->assertEquals($this->viewModel->reveal(), $result);
    }

    public function testOnDispatchWithCriteriaError()
    {
        $data = ['key' => 'value'];
        $errorData = ['errorKey' => 'errorValue'];
        $this->response->getCriteriaErrors()->willReturn($errorData);
        $this->error->notFoundByRequestedCriteria($errorData)
            ->willReturn($this->viewModel->reveal());

        $this->response->getChangesErrors()->willReturn([]);
        $this->response->getRedirectTo()->willReturn(null);
        $this->response->toArray()->willReturn($data);
        $this->viewModel->setVariables($data)->willReturn(null);

        $result = $this->controller->onDispatch($this->event);

        $this->assertEquals($this->viewModel->reveal(), $result);
    }

    public function testOnDispatchWithChangesError()
    {
        $data = ['key' => 'value'];
        $errorData = ['errorKey' => 'errorValue'];
        $this->response->getCriteriaErrors()->willReturn([]);
        $this->response->getChangesErrors()->willReturn($errorData);
        $this->response->getRedirectTo()->willReturn(null);
        $this->response->toArray()->willReturn($data);
        $this->viewModel->setVariables($data)->willReturn(null);

        $result = $this->controller->onDispatch($this->event);

        $this->assertEquals($this->viewModel->reveal(), $result);
    }
}
