<?php

use Bunny\Stream\API\Livestream;
use GuzzleHttp\Psr7\Response;

describe('Livestream API', function () {
    it('can list livestreams', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['items' => []])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->list();

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live');
    });

    it('can get a livestream', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['id' => 'stream-id'])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->get('stream-id');

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id');
    });

    it('can create a livestream', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['id' => 'stream-id'])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->create('New Stream');

        expect($container[0]['request']->getMethod())->toBe('POST')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live')
            ->and((string) $container[0]['request']->getBody())->toContain('New Stream');
    });

    it('can update a livestream', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['id' => 'stream-id'])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->update('stream-id', ['title' => 'Updated Stream']);

        expect($container[0]['request']->getMethod())->toBe('PUT')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id');
    });

    it('can delete a livestream', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->delete('stream-id');

        expect($container[0]['request']->getMethod())->toBe('DELETE')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id');
    });

    it('can get play data', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->getPlayData('stream-id');

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id/play-data');
    });

    it('can start a livestream', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->start('stream-id');

        expect($container[0]['request']->getMethod())->toBe('PUT')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id/start');
    });

    it('can stop a livestream', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->stop('stream-id');

        expect($container[0]['request']->getMethod())->toBe('PUT')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id/stop');
    });

    it('can get bitrate history', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->getBitrateHistory('stream-id');

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id/bitrate-history');
    });

    it('can get current bitrate', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->getCurrentBitrate('stream-id');

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id/current-bitrate');
    });

    it('can set thumbnail', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->setThumbnail('stream-id', 'http://example.com/thumb.jpg');

        expect($container[0]['request']->getMethod())->toBe('POST')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id/thumbnail')
            ->and($container[0]['request']->getUri()->getQuery())->toBe('thumbnailUrl=http%3A%2F%2Fexample.com%2Fthumb.jpg');
    });

    it('can get thumbnails', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $livestream = new Livestream($client, 'api-key', '123');
        $livestream->getThumbnails('stream-id');

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/live/stream-id/thumbnail');
    });
});
