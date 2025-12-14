<?php

declare(strict_types=1);

namespace Bunny\Stream;

use Bunny\Stream\API\Collection;
use Bunny\Stream\API\Livestream;
use Bunny\Stream\API\Statistics;
use Bunny\Stream\API\Tus;
use Bunny\Stream\API\Video;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    private const API_BASE_URL = 'https://video.bunnycdn.com/library/';
    private GuzzleClient $httpClient;

    // API Resources
    private ?Video $video = null;
    private ?Collection $collection = null;
    private ?Livestream $livestream = null;
    private ?Tus $tus = null;
    private ?Statistics $statistics = null;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $streamLibraryId,
        ?GuzzleClient $httpClient = null
    ) {
        $this->httpClient = $httpClient ?? new GuzzleClient([
            'allow_redirects' => false,
            'http_errors'     => false,
            'base_uri'        => self::API_BASE_URL . $this->streamLibraryId . '/',
            'headers'         => [
                'AccessKey' => $this->apiKey,
            ],
        ]);
    }

    // --- API Accessors ---

    public function video(): Video
    {
        return $this->video ??= new Video($this->httpClient, $this->apiKey, $this->streamLibraryId);
    }

    public function collection(): Collection
    {
        return $this->collection ??= new Collection($this->httpClient, $this->apiKey, $this->streamLibraryId);
    }

    public function livestream(): Livestream
    {
        return $this->livestream ??= new Livestream($this->httpClient, $this->apiKey, $this->streamLibraryId);
    }

    public function tus(): Tus
    {
        return $this->tus ??= new Tus($this->httpClient, $this->apiKey, $this->streamLibraryId);
    }

    public function statistics(): Statistics
    {
        return $this->statistics ??= new Statistics($this->httpClient, $this->apiKey, $this->streamLibraryId);
    }
}
