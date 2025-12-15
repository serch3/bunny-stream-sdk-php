<?php

declare(strict_types=1);

use Bunny\Stream\API\Video;
use GuzzleHttp\Psr7\Response;

describe('Video API', function (): void {
    it('can list videos', function (): void {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['items' => []])),
        ], $container);

        $video = new Video($client, 'api-key', '123');
        $result = $video->list();

        expect($result)->toBeArray()
            ->and($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/videos');
    });

    it('can get a video', function (): void {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['guid' => 'video-id'])),
        ], $container);

        $video = new Video($client, 'api-key', '123');
        $video->get('video-id');

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/videos/video-id');
    });

    it('can create a video', function (): void {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['guid' => 'video-id'])),
        ], $container);

        $video = new Video($client, 'api-key', '123');
        $video->create('New Video');

        expect($container[0]['request']->getMethod())->toBe('POST')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/videos')
            ->and((string) $container[0]['request']->getBody())->toContain('New Video');
    });

    it('can update a video', function (): void {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode(['guid' => 'video-id'])),
        ], $container);

        $video = new Video($client, 'api-key', '123');
        $video->update('video-id', ['title' => 'Updated']);

        expect($container[0]['request']->getMethod())->toBe('POST')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/videos/video-id');
    });

    it('can delete a video', function (): void {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $video = new Video($client, 'api-key', '123');
        $video->delete('video-id');

        expect($container[0]['request']->getMethod())->toBe('DELETE')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/videos/video-id');
    });

    it('can repackage a video', function (): void {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $video = new Video($client, 'api-key', '123');
        $video->repackage('video-id');

        expect($container[0]['request']->getMethod())->toBe('POST')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/videos/video-id/repackage');
    });

    it('can trigger smart generate', function (): void {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $video = new Video($client, 'api-key', '123');
        $video->smartGenerate('video-id');

        expect($container[0]['request']->getMethod())->toBe('POST')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/videos/video-id/smart');
    });

    it('can get heatmap data', function (): void {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $video = new Video($client, 'api-key', '123');
        $video->getHeatmapData('video-id');

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/videos/video-id/play/heatmap');
    });

    it('can get statistics', function (): void {
        $container = [];
        $client = mockGuzzleClient([
            new Response(200, [], json_encode([])),
        ], $container);

        $video = new Video($client, 'api-key', '123');
        $video->getStatistics('video-id');

        expect($container[0]['request']->getMethod())->toBe('GET')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/library/123/statistics')
            ->and($container[0]['request']->getUri()->getQuery())->toContain('videoGuid=video-id');
    });
});
