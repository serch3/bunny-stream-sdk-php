<?php

declare(strict_types=1);

use Bunny\Stream\Client;
use Bunny\Stream\API\Video;
use Bunny\Stream\API\Collection;
use Bunny\Stream\API\Livestream;
use Bunny\Stream\API\Statistics;
use Bunny\Stream\API\Tus;
use GuzzleHttp\Client as GuzzleClient;

describe('Client', function (): void {
    it('can be instantiated', function (): void {
        $client = new Client('api-key', '123');
        expect($client)->toBeInstanceOf(Client::class);
    });

    it('exposes API instances', function (): void {
        $client = new Client('api-key', '123');

        expect($client->video())->toBeInstanceOf(Video::class)
            ->and($client->collection())->toBeInstanceOf(Collection::class)
            ->and($client->livestream())->toBeInstanceOf(Livestream::class)
            ->and($client->statistics())->toBeInstanceOf(Statistics::class)
            ->and($client->tus())->toBeInstanceOf(Tus::class);
    });

    it('can accept custom guzzle client', function (): void {
        $guzzle = new GuzzleClient(['base_uri' => 'http://custom.com']);
        $client = new Client('api-key', '123', $guzzle);

        expect($client)->toBeInstanceOf(Client::class);
    });
});
