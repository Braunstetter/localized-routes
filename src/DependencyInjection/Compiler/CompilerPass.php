<?php

namespace Braunstetter\LocalizedRoutes\DependencyInjection\Compiler;

use Braunstetter\LocalizedRoutes\EventSubscriber\LocaleRewriteSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {

        if (!$container->hasDefinition(LocaleRewriteSubscriber::class)) {
            return;
        }

        $frameworkConfig = $container->getExtensionConfig('framework');
        $subscriberDefinition = $container->getDefinition(LocaleRewriteSubscriber::class);
        $subscriberDefinition->replaceArgument(1, $this->getConfigValue('enabled_locales', $frameworkConfig));

        if ($defaultLocale = $this->getConfigValue('default_locale', $frameworkConfig)) {
            $container->setParameter('default_locale', $defaultLocale);
        }

        if ($enabledLocales = $this->getConfigValue('enabled_locales', $frameworkConfig)) {
            $container->setParameter('enabled_locales', $enabledLocales);
            $container->setParameter('enabled_locales_string', implode('|', $enabledLocales));
        }
    }

    private function getConfigValue(string $key, array $config)
    {
        $value = null;

        foreach ($config as $configItem) {
            if (array_key_exists($key, $configItem)) {
                $value = $configItem[$key];
                break;
            }
        }

        return $value;
    }
}