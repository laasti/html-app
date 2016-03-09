<?php

namespace Laasti\HtmlApp\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class WelcomeController implements \Laasti\Views\TemplateRendererAwareInterface
{
    use \Laasti\Views\TemplateRendererAwareTrait;

    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        return $this->getTemplateRenderer()->attachStream($response, new \Laasti\Views\Template('welcome.mustache'));
    }
}
