<?php

namespace Braunstetter\LocalizedRoutes\Tests\Functional\app\src;

use Braunstetter\LocalizedRoutes\LocalizedRoutesBundle;
use Exception;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class TestKernel extends Kernel
{

    /**
     * @param string[] $configs
     */
    public function __construct(private array $configs = [])
    {
        parent::__construct('test', true);
    }

    /**
     * @inheritDoc
     */
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new LocalizedRoutesBundle()
        ];
    }

    /**
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/Resources/config/services.yaml');
        $loader->load(__DIR__ . '/Resources/config/controller.yaml');
        $loader->load(__DIR__ . '/Resources/config/framework.yaml');

        if (empty($this->configs)) {
            $loader->load(__DIR__ . '/Resources/config/parameters.yaml');
        }

        foreach ($this->configs as $config) {
            $loader->load(__DIR__ . $config);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes)
    {
        $routes->import(__DIR__ . '/Resources/config/routes.yaml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir(): string
    {
        if (isset($_SERVER['APP_CACHE_DIR'])) {
            return $_SERVER['APP_CACHE_DIR'] . '/' . $this->environment;
        }

        return parent::getCacheDir();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir(): string
    {
        return $_SERVER['APP_LOG_DIR'] ?? parent::getLogDir();
    }
}