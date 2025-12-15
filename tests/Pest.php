<?php

declare(strict_types=1);

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(PHPUnit\Framework\TestCase::class)->in('Unit');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function mockGuzzleClient(array $responses, array &$container): GuzzleClient
{
    $history = Middleware::history($container);
    $mock = new MockHandler($responses);

    $handlerStack = HandlerStack::create($mock);
    $handlerStack->push($history);

    return new GuzzleClient([
        'handler' => $handlerStack,
        'base_uri' => 'https://video.bunnycdn.com/library/123/',
    ]);
}
