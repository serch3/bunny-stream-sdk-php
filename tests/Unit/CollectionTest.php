<?php

use Bunny\Stream\API\Collection;
use GuzzleHttp\Psr7\Response;

describe('Collection API', function () {
    it('can list collections', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['items' => []])),
        ], $container);

        $collection = new Collection($client, 'api-key', '123');
        $collection->list();

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/collections');
    });

    it('can get a collection', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['guid' => 'collection-id'])),
        ], $container);

        $collection = new Collection($client, 'api-key', '123');
        $collection->get('collection-id');

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/collections/collection-id');
    });

    it('can create a collection', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['guid' => 'collection-id'])),
        ], $container);

        $collection = new Collection($client, 'api-key', '123');
        $collection->create('New Collection');

        expect($container[0]['request']->getMethod())->toBe('POST')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/collections')
            ->and((string) $container[0]['request']->getBody())->toContain('New Collection');
    });

    it('can update a collection', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['guid' => 'collection-id'])),
        ], $container);

        $collection = new Collection($client, 'api-key', '123');
        $collection->update('collection-id', 'Updated Collection');

        expect($container[0]['request']->getMethod())->toBe('POST')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/collections/collection-id');
    });

    it('can delete a collection', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $collection = new Collection($client, 'api-key', '123');
        $collection->delete('collection-id');

        expect($container[0]['request']->getMethod())->toBe('DELETE')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/collections/collection-id');
    });
});
