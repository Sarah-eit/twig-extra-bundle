<?php

namespace Twig\Extra\TwigExtraBundle\Tests\Fixture;

use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new TwigBundle();
        yield new TwigExtraBundle();
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader): void
    {
        $config = [
            'secret' => 'S3CRET',
            'test' => true,
            'router' => ['utf8' => true],
            'http_method_override' => false,
        ];
        if (Kernel::MAJOR_VERSION >= 6 &&  Kernel::MINOR_VERSION >= 2) {
            $config['handle_all_throwables'] = true;
            $config['php_errors']['log'] = true;
        }
        $c->loadFromExtension('framework', $config);
        $c->loadFromExtension('twig', [
            'default_path' => __DIR__.'/views',
        ]);

        $c->register(StrikethroughExtension::class)->addTag('twig.markdown.league_extension');
    }

    protected function configureRoutes($routes): void
    {
    }
}
