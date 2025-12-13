<?php

declare(strict_types=1);

namespace Bunny\Stream\API;

class Statistics extends AbstractApi
{
    public function get(?string $videoId = null, ?array $query = null): array
    {
        if (!$query) {
            $query = [];
        }

        if ($videoId) {
            $query['videoId'] = $videoId;
        }

        return $this->requestJson(
            'GET',
            'statistics',
            ['query' => $query]
        );
    }
}
