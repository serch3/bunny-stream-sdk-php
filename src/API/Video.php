<?php

declare(strict_types=1);

namespace Bunny\Stream\API;

class Video extends AbstractApi
{
    public function list(
        ?string $search = null,
        int $page = 1,
        int $items = 100,
        ?string $collection = null,
        ?string $orderby = null
    ): array {
        $query = [
            'page'         => $page,
            'itemsPerPage' => $items,
        ];

        if ($search) {
            $query['search'] = $search;
        }
        if ($collection) {
            $query['collection'] = $collection;
        }
        if ($orderby) {
            $query['orderBy'] = $orderby;
        }

        return $this->requestJson(
            'GET',
            'videos',
            ['query' => $query],
            'Could not list videos.'
        );
    }

    public function get(string $videoId): array
    {
        return $this->requestJson(
            'GET',
            'videos/' . $videoId,
            [],
            'Could not get video.',
            $videoId
        );
    }

    public function create(
        string $title,
        ?string $collectionId = null,
        ?int $thumbnailTime = null
    ): array {
        $json = ['title' => $title];
        if ($collectionId) {
            $json['collectionId'] = $collectionId;
        }
        if ($thumbnailTime) {
            $json['thumbnailTime'] = $thumbnailTime;
        }

        return $this->requestJson(
            'POST',
            'videos',
            ['json' => $json],
            'Could not create video.'
        );
    }

    public function update(string $videoId, array $body): array
    {
        return $this->requestJson(
            'POST',
            'videos/' . $videoId,
            ['json' => $body],
            'Could not update video.',
            $videoId
        );
    }

    public function delete(string $videoId): array
    {
        return $this->requestJson(
            'DELETE',
            'videos/' . $videoId,
            [],
            'Could not delete video.',
            $videoId
        );
    }

    public function upload(
        string $videoId,
        string $path,
        ?string $enabledResolutions = null
    ): array {
        if (!file_exists($path)) {
            throw new \Exception("File does not exist at given location: $path");
        }

        $fileStream = fopen($path, 'r');
        if ($fileStream === false) {
            throw new \Exception('The local file could not be opened.');
        }

        $query = [];
        if ($enabledResolutions) {
            $query['enabledResolutions'] = $enabledResolutions;
        }

        return $this->requestJson(
            'PUT',
            'videos/' . $videoId,
            [
                'query' => $query,
                'body'  => $fileStream,
            ],
            'Could not upload video.',
            $videoId
        );
    }

    public function setThumbnail(string $videoId, string $url): array
    {
        return $this->requestJson(
            'POST',
            'videos/' . $videoId . '/thumbnail',
            [
                'query' => ['thumbnailUrl' => $url],
            ],
            'Could not set video thumbnail.',
            $videoId
        );
    }

    public function getHeatmap(string $videoId): array
    {
        return $this->requestJson(
            'GET',
            'videos/' . $videoId . '/heatmap',
            [],
            'Could not get video heatmap.',
            $videoId
        );
    }

    public function getPlayData(
        string $videoId,
        ?string $token = null,
        ?int $expires = null
    ): array {
        $query = [];
        if ($token) {
            $query['token'] = $token;
        }
        if ($expires) {
            $query['expires'] = $expires;
        }

        return $this->requestJson(
            'GET',
            'videos/' . $videoId . '/play',
            ['query' => $query],
            'Could not get video play data.',
            $videoId
        );
    }

    public function reencode(string $videoId): array
    {
        return $this->requestJson(
            'POST',
            'videos/' . $videoId . '/reencode',
            [],
            'Could not reencode video.',
            $videoId
        );
    }

    public function addOutputCodec(string $videoId, int $codecId): array
    {
        if (!in_array($codecId, [0, 1, 2, 3])) {
            throw new \Exception('Invalid codec value. 0 = x264, 1 = vp9, 2 = hevc, 3 = av1.');
        }

        return $this->requestJson(
            'PUT',
            'videos/' . $videoId . '/outputs/' . $codecId,
            [],
            'Could not add output codec.',
        );
    }

    public function repackage(string $videoId, bool $keepOriginalFiles = true): array
    {
        $query = ['keepOriginalFiles' => $keepOriginalFiles ? 'true' : 'false'];

        return $this->requestJson(
            'GET',
            'videos/' . $videoId . '/repackage',
            ['query' => $query],
            'Could not repackage video.',
            $videoId
        );
    }

    public function fetch(
        string $url,
        ?string $title = null,
        ?string $collectionId = null,
        ?int $thumbnailTime = null,
        ?array $headers = null
    ): array {
        $query = [];
        if ($collectionId) {
            $query['collectionId'] = $collectionId;
        }
        if ($thumbnailTime) {
            $query['thumbnailTime'] = $thumbnailTime;
        }

        $body = [
            'url' => $url,
        ];
        if ($title) {
            $body['title'] = $title;
        }
        if ($headers) {
            $body['headers'] = $headers;
        }

        return $this->requestJson(
            'POST',
            'videos/fetch',
            [
                'query' => $query,
                'json'  => $body,
            ],
            'Could not fetch video.'
        );
    }

    public function addCaption(
        string $videoId,
        string $srclang,
        string $path,
        string $label
    ): array {
        if (!file_exists($path)) {
            throw new \Exception("Captions file does not exist at path: $path");
        }

        $body = [
            'srclang'      => $srclang,
            'captionsFile' => base64_encode(file_get_contents($path)),
        ];
        if ($label) {
            $body['label'] = $label;
        }

        return $this->requestJson(
            'POST',
            'videos/' . $videoId . '/captions/' . $srclang,
            ['json' => $body],
            'Could not add caption.',
            $videoId
        );
    }

    public function deleteCaption(string $videoId, string $srclang): array
    {
        return $this->requestJson(
            'DELETE',
            'videos/' . $videoId . '/captions/' . $srclang,
            [],
            'Could not delete caption.',
            $videoId
        );
    }

    public function transcribe(string $videoId, string $language, bool $force = false, array $options = []): array
    {
        $query = [
            'language' => $language,
            'force'    => $force ? 'true' : 'false',
        ];

        $opts = [];
        if (!empty($options)) {
            $opts = array_filter([
                'targetLanguages'     => $options['targetLanguages'] ?? null,
                'generateTitles'      => $options['generateTitles'] ?? null,
                'generateDescription' => $options['generateDescription'] ?? null,
                'sourceLanguage'     => $options['sourceLanguage'] ?? null,
            ], fn($value) => $value !== null);
        }

        return $this->requestJson(
            'POST',
            'videos/' . $videoId . '/transcribe',
            [
                'query' => $query, 
                'json'  => $opts,
            ],
            'Could not transcribe video.',
            $videoId
        );
    }

    public function getResolutions(string $videoId): array
    {
        return $this->requestJson(
            'GET',
            'videos/' . $videoId . '/resolutions',
            [],
            'Could not list video resolutions.',
            $videoId
        );
    }

    public function cleanupResolutions(string $videoId, string $resolutions, ?array $query = null): array
    {
        $query = [
            'resolutionsToDelete'       => $resolutions,
            'deleteNonConfiguredResolutions' => $query['deleteNonConfiguredResolutions'] ?? 'false',
            'deleteOriginal'            => $query['deleteOriginal'] ?? 'false',
            'deleteMp4Files'            => $query['deleteMp4Files'] ?? 'false',
            'dryRun'                    => $query['dryRun'] ?? 'false',
        ];

        return $this->requestJson(
            'POST',
            'videos/' . $videoId . '/resolutions/cleanup',
            ['query' => $query],
            'Could not cleanup video resolutions.',
            $videoId
        );
    }
}
