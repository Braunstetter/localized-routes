<?php

use Braunstetter\LocalizedRoutes\DependencyInjection\Compiler\CompilerPass;
use Braunstetter\LocalizedRoutes\LocalizedRoutesBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BundleTest extends TestCase
{
    public function test_bundle_class_adds_compiler_pass()
    {
        $container = new ContainerBuilder();
        $bundle = new LocalizedRoutesBundle();
        $bundle->build($container);
        $compilerPasses = $container->getCompilerPassConfig()->getPasses();

        $this->assertCount(1, array_filter($compilerPasses, fn($pass) => $pass instanceof CompilerPass));
    }
}