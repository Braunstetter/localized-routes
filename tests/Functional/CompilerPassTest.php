<?php

namespace Braunstetter\LocalizedRoutes\Tests\Functional;

use Braunstetter\LocalizedRoutes\DependencyInjection\Compiler\CompilerPass;
use Braunstetter\LocalizedRoutes\EventSubscriber\LocaleRewriteSubscriber;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Routing\RouterInterface;

class CompilerPassTest extends AbstractCompilerPassTestCase
{

    /**
     * @inheritDoc
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
       $container->addCompilerPass(new CompilerPass());
    }

    public function test_compiler_pass_replaces_arguments_and_sets_parameters()
    {
        $this->container->prependExtensionConfig('framework', [
            'default_locale' => 'es',
            'enabled_locales' => ['es', 'en']
        ]);

        $subscriberDefinition = new Definition();
        $subscriberDefinition->setTags(['kernel.event_subscriber']);
        $subscriberDefinition->setArguments([$this->getMockBuilder(RouterInterface::class)->getMock(), null, null]);

        $this->setDefinition(LocaleRewriteSubscriber::class, $subscriberDefinition);
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(LocaleRewriteSubscriber::class, 1, ['es', 'en']);
        $this->assertContainerBuilderHasParameter('default_locale');
        $this->assertContainerBuilderHasParameter('enabled_locales');
        $this->assertContainerBuilderHasParameter('enabled_locales_string');

        $this->assertIsString($this->container->getParameter('default_locale'));
        $this->assertIsArray($this->container->getParameter('enabled_locales'));
        $this->assertIsString($this->container->getParameter('enabled_locales_string'));
    }


    public function test_compiler_pass_dont_process_if_subscriber_is_not_set()
    {
        $this->compile();
        $this->assertContainerBuilderNotHasService(LocaleRewriteSubscriber::class);
    }
}