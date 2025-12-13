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
            'livestreams',
            ['query' => $query],
            'Could not list livestreams.'
        );
    }

    public function get(string $livestreamId): array
    {
        return $this->requestJson(
            'GET',
            'livestreams/' . $livestreamId,
            [],
            'Could not get livestream.',
            $livestreamId
        );
    }

    public function create(string $title): array
    {
        return $this->requestJson(
            'POST',
            'livestreams',
            ['json' => ['title' => $title]],
            'Could not create livestream.'
        );
    }

    public function update(string $livestreamId, array $body): array
    {
        return $this->requestJson(
            'POST',
            'livestreams/' . $livestreamId,
            ['json' => $body],
            'Could not update livestream.',
            $livestreamId
        );
    }

    public function delete(string $livestreamId): array
    {
        return $this->requestJson(
            'DELETE',
            'livestreams/' . $livestreamId,
            [],
            'Could not delete livestream.',
            $livestreamId
        );
    }
}
