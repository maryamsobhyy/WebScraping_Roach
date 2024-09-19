<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use Illuminate\Support\Facades\Log;
use RoachPHP\Extensions\LoggerExtension;
use Symfony\Component\DomCrawler\Crawler;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;

class LaravelDocsSpider extends BasicSpider
{
    public array $startUrls = [
        'https://example.com/'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        // يمكنك إضافة الميدل وير هنا إذا لزم الأمر
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): \Generator
    {
        $title = $response->filter('h1')->text();

        $subtitle = $response
            ->filter('div > p:first-of-type')
            ->text();

        yield $this->item([
            'title' => $title,
            'subtitle' => $subtitle,
        ]);
    }
}
