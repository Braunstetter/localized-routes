<?php
namespace Braunstetter\LocalizedRoutes;

use Braunstetter\LocalizedRoutes\DependencyInjection\Compiler\CompilerPass;
use Braunstetter\LocalizedRoutes\DependencyInjection\LocalizedRoutesExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LocalizedRoutesBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new LocalizedRoutesExtension();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CompilerPass());
    }


}