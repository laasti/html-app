<?php

namespace Laasti\HtmlApp\Controllers;

use Laasti\Views\Template;
use Laasti\Views\TemplateRendererAwareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundController implements TemplateRendererAwareInterface
{
    use \Laasti\Views\TemplateRendererAwareTrait;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $e = $request->getAttribute('exception');
        if (!is_null($e)) {
            switch (get_class($e)) {
                case 'Laasti\Directions\Exceptions\MethodNotAllowedException' :
                    $response = $response->withStatus(405);
                    break;
                case 'Laasti\Directions\Exceptions\RouteNotFoundException' :
                    $response = $response->withStatus(404);
                    break;
                default:
                    $response = $response->withStatus(500);
                    break;
            }
        }
        
        return $this->getTemplateRenderer()->attachStream($response, new Template('notFound.mustache'));
    }
}
