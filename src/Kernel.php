<?php

/**
 * User: morontt
 * Date: 15.12.2024
 * Time: 23:20
 */

namespace TeleBot;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Bundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use TeleBot\DependencyInjection\TelegramCompilerPass;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    public function registerBundles(): array
    {
        $bundles = [
            new Bundle\MonologBundle\MonologBundle(),
            new Bundle\FrameworkBundle\FrameworkBundle(),
            new Bundle\SecurityBundle\SecurityBundle(),
            new Bundle\TwigBundle\TwigBundle(),
            new DoctrineBundle(),
            new DoctrineMigrationsBundle(),
        ];

        if ('dev' === $this->getEnvironment()) {
            $bundles[] = new Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return $bundles;
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/../var/cache/' . $this->getEnvironment();
    }

    public function getLogDir(): string
    {
        return __DIR__ . '/../var/log';
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(__DIR__ . '/../config/framework.yaml');
        $container->import(__DIR__ . '/../config/packages/*.yaml');
        $container->import(__DIR__ . '/../config/services.yaml');

        if (isset($this->bundles['WebProfilerBundle'])) {
            $container->extension('web_profiler', [
                'toolbar' => true,
                'intercept_redirects' => false,
            ]);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        if (isset($this->bundles['WebProfilerBundle'])) {
            $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.xml')->prefix('/_wdt');
            $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml')->prefix('/_profiler');
        }

        $routes->import(__DIR__ . '/Controller/', 'attribute');
        $routes->import(__DIR__ . '/../config/routes.yaml');
    }

    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TelegramCompilerPass());
    }
}
