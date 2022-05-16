<?php

namespace Braunstetter\LocalizedRoutes\Tests;

use Braunstetter\LocalizedRoutes\DependencyInjection\LocalizedRoutesExtension;
use Braunstetter\LocalizedRoutes\EventSubscriber\LocaleRewriteSubscriber;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class BundleExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [new LocalizedRoutesExtension()];
    }

    public function test_twig_extension_gets_loaded()
    {
        $this->load();
        $this->assertContainerBuilderHasService(LocaleRewriteSubscriber::class);
    }
}