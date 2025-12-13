<?php

declare(strict_types=1);

namespace Bunny\Stream\API;

class Livestream extends AbstractApi
{
    public function list(
        ?string $search = null,
        int $page = 1,
        int $items = 100,
        string $orderby = 'date'
    ): array {
        $query = [
            'page'         => $page,
            'itemsPerPage' => $items,
            'orderBy'      => $orderby,
        ];

        if ($search) {
            $query['search'] = $search;
        }

        return $this->requestJson(
            'GET',
            'live',
            ['query' => $query],
            'Could not list livestreams.'
        );
    }

    public function get(string $livestreamId): array
    {
        return $this->requestJson(
            'GET',
            'live/' . $livestreamId,
            [],
            'Could not get livestream.',
            $livestreamId
        );
    }

    public function create(string $title): array
    {
        return $this->requestJson(
            'POST',
            'live',
            ['json' => ['title' => $title]],
            'Could not create livestream.'
        );
    }

    public function update(string $livestreamId, array $body): array
    {
        return $this->requestJson(
            'PUT',
            'live/' . $livestreamId,
            ['json' => $body],
            'Could not update livestream.',
            $livestreamId
        );
    }

    public function delete(string $livestreamId): array
    {
        return $this->requestJson(
            'DELETE',
            'live/' . $livestreamId,
            [],
            'Could not delete livestream.',
            $livestreamId
        );
    }

    public function getPlayData(string $livestreamId): array
    {
        return $this->requestJson(
            'GET',
            'live/' . $livestreamId . '/play-data',
            [],
            'Could not get livestream play data.',
            $livestreamId
        );
    }

    public function start(string $livestreamId): array
    {
        return $this->requestJson(
            'PUT',
            'live/' . $livestreamId . '/start',
            [],
            'Could not start livestream.',
            $livestreamId
        );
    }

    public function stop(string $livestreamId): array
    {
        return $this->requestJson(
            'PUT',
            'live/' . $livestreamId . '/stop',
            [],
            'Could not stop livestream.',
            $livestreamId
        );
    }

    public function getBitrateHistory(string $livestreamId): array
    {
        return $this->requestJson(
            'GET',
            'live/' . $livestreamId . '/bitrate-history',
            [],
            'Could not get livestream bitrate history.',
            $livestreamId
        );
    }

    public function getCurrentBitrate(string $livestreamId): array
    {
        return $this->requestJson(
            'GET',
            'live/' . $livestreamId . '/current-bitrate',
            [],
            'Could not get livestream current bitrate.',
            $livestreamId
        );
    }

    public function setThumbnail(string $livestreamId, string $thumbnailUrl): array
    {
        return $this->requestJson(
            'POST',
            'live/' . $livestreamId . '/thumbnail',
            ['query' => ['thumbnailUrl' => $thumbnailUrl]],
            'Could not set livestream thumbnail.',
            $livestreamId
        );
    }

    public function getThumbnails(string $livestreamId): array
    {
        return $this->requestJson(
            'GET',
            'live/' . $livestreamId . '/thumbnail',
            [],
            'Could not get livestream thumbnails.',
            $livestreamId
        );
    }
}
