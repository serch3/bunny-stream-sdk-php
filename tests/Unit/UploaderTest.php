<?php

use Bunny\Stream\Tus\Uploader;
use GuzzleHttp\Psr7\Response;

describe('Tus Uploader', function () {
    it('chunks file correctly', function () {
        // Create a dummy file larger than the chunk size (5MB default)
        // Let's set a small chunk size for testing
        $chunkSize = 1024; // 1KB
        $fileSize = 2500; // 2.5KB (3 chunks: 1024, 1024, 452)
        
        $filePath = sys_get_temp_dir() . '/test_upload.bin';
        $content = str_repeat('a', $fileSize);
        file_put_contents($filePath, $content);

        $container = [];
        $client = mockGuzzleClient([
            new Response(200, ['Upload-Offset' => '0']), // HEAD request (initial offset)
            new Response(204, ['Upload-Offset' => '1024']), // PATCH chunk 1
            new Response(204, ['Upload-Offset' => '2048']), // PATCH chunk 2
            new Response(204, ['Upload-Offset' => '2500']), // PATCH chunk 3
        ], $container);

        $uploader = new Uploader($client, $filePath, 'https://video.bunnycdn.com/tusupload/123');
        $uploader->setChunkSize($chunkSize);
        $uploader->upload();

        expect($container)->toHaveCount(4);

        // Check HEAD request
        expect($container[0]['request']->getMethod())->toBe('HEAD');
        
        // Check Chunk 1
        expect($container[1]['request']->getMethod())->toBe('PATCH')
            ->and($container[1]['request']->getHeaderLine('Upload-Offset'))->toBe('0')
            ->and($container[1]['request']->getBody()->getSize())->toBe(1024);

        // Check Chunk 2
        expect($container[2]['request']->getMethod())->toBe('PATCH')
            ->and($container[2]['request']->getHeaderLine('Upload-Offset'))->toBe('1024')
            ->and($container[2]['request']->getBody()->getSize())->toBe(1024);

        // Check Chunk 3
        expect($container[3]['request']->getMethod())->toBe('PATCH')
            ->and($container[3]['request']->getHeaderLine('Upload-Offset'))->toBe('2048')
            ->and($container[3]['request']->getBody()->getSize())->toBe(452);

        // Clean up
        unlink($filePath);
    });

    it('resumes from offset', function () {
        $chunkSize = 100;
        $fileSize = 300;
        
        $filePath = sys_get_temp_dir() . '/test_resume.bin';
        $content = str_repeat('b', $fileSize);
        file_put_contents($filePath, $content);

        $container = [];
        $client = mockGuzzleClient([
            new Response(200, ['Upload-Offset' => '100']), // HEAD request (server says we have 100 bytes)
            new Response(204, ['Upload-Offset' => '200']), // PATCH chunk 2 (100-200)
            new Response(204, ['Upload-Offset' => '300']), // PATCH chunk 3 (200-300)
        ], $container);

        $uploader = new Uploader($client, $filePath, 'https://video.bunnycdn.com/tusupload/123');
        $uploader->setChunkSize($chunkSize);
        $uploader->upload();

        expect($container)->toHaveCount(3);

        // Check HEAD request
        expect($container[0]['request']->getMethod())->toBe('HEAD');
        
        // Check Chunk 2 (First sent chunk)
        expect($container[1]['request']->getMethod())->toBe('PATCH')
            ->and($container[1]['request']->getHeaderLine('Upload-Offset'))->toBe('100') // Resumed from 100
            ->and((string)$container[1]['request']->getBody())->toBe(substr($content, 100, 100));

        // Clean up
        unlink($filePath);
    });
});
