<?php

declare(strict_types=1);

use Braunstetter\LocalizedRoutes\EventSubscriber\LocaleRewriteSubscriber;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {

    $services = $containerConfigurator->services();

    $services->set(LocaleRewriteSubscriber::class)
        ->args([service('router'), service('parameter_bag')])
        ->tag('kernel.event_subscriber');
};
