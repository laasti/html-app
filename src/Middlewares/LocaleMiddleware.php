<?php

namespace Laasti\HtmlApp\Middlewares;

class LocaleMiddleware implements \Laasti\SymfonyTranslationProvider\TranslatorAwareInterface
{
    use \Laasti\SymfonyTranslationProvider\TranslatorAwareTrait;

    protected $supportedLocales;
    protected $defaultLocale;

    public function __construct($supportedLocales = ['en'], $defaultLocale = 'en')
    {
        $this->supportedLocales = $supportedLocales;
        $this->defaultLocale = $defaultLocale;
    }

    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, callable $next = null)
    {
        $uriParts = explode('/', trim($request->getAttribute('pathinfo'), '/'));
        $locale = reset($uriParts);
        if (in_array($locale, $this->supportedLocales)) {
            $this->getTranslator()->setLocale($locale);
        } else {
            $this->getTranslator()->setLocale($this->defaultLocale);
        }
        return $next($request, $response);
    }
}
