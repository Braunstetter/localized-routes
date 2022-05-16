<?php

namespace Braunstetter\LocalizedRoutes\EventSubscriber;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

class LocaleRewriteSubscriber implements EventSubscriberInterface
{

    private RouteCollection $routeCollection;
    private array $supportedLocales;
    private mixed $localeRouteParam;

    public function __construct(
        RouterInterface       $router,
        ParameterBagInterface $parameterBag,
                              $localeRouteParam = '_locale',
    )
    {
        $this->routeCollection = $router->getRouteCollection();
        $this->supportedLocales = explode('|', $parameterBag->get('app_locales'));
        $this->localeRouteParam = $localeRouteParam;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        $route_exists = false; //by default assume route does not exist.

        foreach ($this->routeCollection as $routeObject) {
            /** @var Route $routeObject */
            $routePath = $routeObject->getPath();

            if ($routePath == "/{" . $this->localeRouteParam . "}" . $path) {
                $route_exists = true;
                break;
            }
        }

        //If the route does indeed exist then lets redirect there.
        if ($route_exists) {
            //Get the locale from the user's browser.
            $locale = explode('_', $request->getPreferredLanguage())[0];
            //If no locale from browser or locale not in list of known locales supported then set to defaultLocale set in config.yml
            if ($locale == "" || !$this->isLocaleSupported($locale)) {
                $locale = $request->getDefaultLocale();
            }

            $event->setResponse(new RedirectResponse("/" . $locale . $path));
        }

    }

    public function isLocaleSupported($locale): bool
    {
        $supportedLocale = array_filter($this->supportedLocales, function ($supportedLocale) use ($locale) {
            return in_array($supportedLocale, explode('_', $locale));
        });

        return !empty($supportedLocale);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}