<?php

namespace Braunstetter\LocalizedRoutes\EventSubscriber;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

class LocaleRewriteSubscriber implements EventSubscriberInterface
{

    private RouteCollection $routeCollection;
    private ?array $enabledLocales;
    private mixed $localeRouteParam;

    public function __construct(RouterInterface $router, array|null $enabledLocales = null)
    {
        $this->routeCollection = $router->getRouteCollection();
        $this->localeRouteParam = '_locale';
        $this->enabledLocales = $enabledLocales;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        //Get the locale from the user's browser.
        $locale = $this->getPreferredLanguage($request);

        //If no locale from browser or locale not in list of known locales supported then set to defaultLocale set in config.yml
        if (!$this->isLocaleSupported($locale)) {
            $locale = $this->getDefaultLocale($request);
        }

        if ($this->routeExists($path) && $locale) {
            $event->setResponse(new RedirectResponse("/" . $locale . $path));
        }
    }

    private function isLocaleSupported($locale): bool
    {

        if ($this->enabledLocales === null) {
            return true;
        }

        if ($locale) {
            return $this->isLocaleEnabled($locale);
        }

        return false;
    }

    private function getPreferredLanguage(Request $request): ?string
    {
        return $request->getPreferredLanguage() ? explode('_', $request->getPreferredLanguage())[0] : null;
    }

    private function routeExists(string $path): bool
    {
        return !empty(array_filter(iterator_to_array($this->routeCollection->getIterator()), function($routeObject) use ($path) {
            return $routeObject->getPath() === "/{" . $this->localeRouteParam . "}" . $path;
        }));
    }

    private function getDefaultLocale(Request $request): ?string
    {
        return $this->isLocaleEnabled($request->getDefaultLocale()) ? $request->getDefaultLocale() : null;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 64]
        ];
    }

    private function isLocaleEnabled(string $locale): bool
    {
        if ($this->enabledLocales === null) {
            return true;
        }

        return !empty(array_filter($this->enabledLocales, function($supportedLocale) use ($locale) {
            return in_array($supportedLocale, explode('_', $locale));
        }));
    }

}