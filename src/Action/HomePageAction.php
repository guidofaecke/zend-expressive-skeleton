<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;

class HomePageAction
{
    private $router;

    private $template;

    public function __construct(Router\RouterInterface $router, Template\TemplateInterface $template = null)
    {
        $this->router   = $router;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = [];

        if ($this->router instanceof Router\AuraRouter) {
            $data['routerName'] = 'Aura.Router';
            $data['routerDocs'] = 'http://auraphp.com/packages/Aura.Router/';
        } elseif ($this->router instanceof Router\FastRouteRouter) {
            $data['routerName'] = 'FastRoute';
            $data['routerDocs'] = 'https://github.com/nikic/FastRoute';
        } elseif ($this->router instanceof Router\Zf2Router) {
            $data['routerName'] = 'ZF2 Router';
            $data['routerDocs'] = 'http://framework.zend.com/manual/current/en/modules/zend.mvc.routing.html';
        }

        if ($this->template instanceof Template\Plates) {
            $data['templateName'] = 'Plates';
            $data['templateDocs'] = 'http://platesphp.com/';
        } elseif ($this->template instanceof Template\Twig) {
            $data['templateName'] = 'Twig';
            $data['templateDocs'] = 'http://twig.sensiolabs.org/documentation';
        } elseif ($this->template instanceof Template\ZendView) {
            $data['templateName'] = 'Zend View';
            $data['templateDocs'] = 'http://framework.zend.com/manual/current/en/modules/zend.view.quick-start.html';
        }

        if (!$this->template) {
            return new JsonResponse([
                'welcome'  => 'Congratulations! You have installed the zend-expressive skeleton application.',
                'docs'     => 'https://zend-expressive.readthedocs.org/en/latest/',
                'router'   => sprintf('You installed %s', $data['routerName']),
                'template' => sprintf('You installed %s template engine.', $data['templateName'])
            ]);
        }

        return new HtmlResponse($this->template->render('app::home-page', $data));
    }
}
