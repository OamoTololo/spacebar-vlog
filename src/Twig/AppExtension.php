<?php

namespace App\Twig;

use App\Service\MarkdownHelper;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('cached_markdown', [$this, 'processMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    public function processMarkdown($value)
    {
        try {
            return $this
                ->container
                ->get(MarkdownHelper::class)
                ->parse($value);
        } catch (\Exception|NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return '<p>Error rendering content. Please contact admin</p>';
        }
    }

    public function doSomething($value)
    {
        // ...
    }

    public static function getSubscribedServices()
    {
        return [
            'foo' => MarkdownHelper::class,
        ];
    }
}
