<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\Controller\ResponseInterface;
use Sebaks\Controller\Response;

class ResponseFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('Application');
        /** @var \Zend\Mvc\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        if ($routeMatch->getParam('response')) {
            $response = $serviceLocator->get($routeMatch->getParam('response'));
            if (! $response instanceof ResponseInterface) {
                throw new \RuntimeException('Response must be instance of ' . ResponseInterface::class);
            }
        } else {
            $response = new Response();
        }

        $redirectTo = $routeMatch->getParam('redirectTo');
        if (!empty($redirectTo) && is_array($redirectTo)) {
            if (!isset($redirectTo['route'])) {
                throw new \RuntimeException('Missing required parameter route');
            }
        }

        $response->setRedirectTo($redirectTo);

        return $response;
    }
}
