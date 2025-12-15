<?php

use Bunny\Stream\API\Tus;
use Bunny\Stream\Tus\Uploader;
use GuzzleHttp\Psr7\Response;

describe('Tus API', function () {
    it('can create a tus upload', function () {
        $container = [];
        $client = mockGuzzleClient([
            new Response(201, ['Location' => 'http://example.com/files/123']),
        ], $container);

        $filePath = sys_get_temp_dir() . '/test_video.mp4';
        file_put_contents($filePath, 'dummy content');

        $tus = new Tus($client, 'api-key', '123');
        $uploader = $tus->createUpload('video-id', $filePath);

        expect($uploader)->toBeInstanceOf(Uploader::class)
            ->and($container[0]['request']->getMethod())->toBe('POST')
            ->and($container[0]['request']->getUri()->getPath())->toBe('/tusupload')
            ->and($container[0]['request']->getHeaderLine('Tus-Resumable'))->toBe('1.0.0')
            ->and($container[0]['request']->getHeaderLine('VideoId'))->toBe('video-id');

        unlink($filePath);
    });

    it('throws exception if file not found', function () {
        $container = [];
        $client = mockGuzzleClient([], $container);
        $tus = new Tus($client, 'api-key', '123');

        expect(fn() => $tus->createUpload('video-id', 'non-existent-file.mp4'))
            ->toThrow(Exception::class, 'File not found: non-existent-file.mp4');
    });
});
