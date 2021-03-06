<?php
namespace Braunstetter\LocalizedRoutes\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class LocalizedRoutesExtension extends Extension
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $phpFileLoader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $phpFileLoader->load('services.php');
    }
}