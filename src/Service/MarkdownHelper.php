<?php

namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $logger;
    private $isDebug;

    public function __construct(
        AdapterInterface $cache,
        MarkdownParserInterface $markdown,
        LoggerInterface $markdownLogger,
        bool $isDebug
    ) {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
        $this->isDebug = $isDebug;
    }

    public function parse(string $source): string
    {
        if (false !== stripos($source, 'bacon')) {
            $this->logger->info('The are talking about bacon again!');
        }

        if ($this->isDebug) {
            return $this->markdown->transformMarkdown($source);
        }

        $item = $this->cache->getItem('markdown_'.md5($source));

        if (!$item->isHit()) {
            $item->set($this->markdown->transformMarkdown($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}
