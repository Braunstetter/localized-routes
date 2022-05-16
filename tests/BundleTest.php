<?php

namespace Braunstetter\LocalizedRoutes\Tests;

use Nyholm\BundleTest\AppKernel;
use Braunstetter\LocalizedRoutes\DependencyInjection\LocalizedRoutesExtension;
use Braunstetter\LocalizedRoutes\LocalizedRoutesBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class BundleTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return AppKernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        /**
         * @var AppKernel $kernel
         */
        $kernel = parent::createKernel($options);
        $kernel->addBundle(LocalizedRoutesBundle::class);

        return $kernel;
    }

    public function testInitBundle(): void
    {
        self::bootKernel();
        $bundle = self::$kernel->getBundle('LocalizedRoutesBundle');
        $this->assertInstanceOf(LocalizedRoutesBundle::class, $bundle);
        $this->assertInstanceOf(LocalizedRoutesExtension::class, $bundle->getContainerExtension());
    }

}