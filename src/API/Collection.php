<?php

declare(strict_types=1);

namespace Bunny\Stream\API;

class Collection extends AbstractApi
{
    public function list(
        ?string $search = null,
        int $page = 1,
        int $items = 100,
        string $orderby = 'date',
        bool $includeThumbnails = false
    ): array {
        $query = [
            'page'              => $page,
            'itemsPerPage'      => $items,
            'includeThumbnails' => $includeThumbnails ? 'true' : 'false',
            'orderBy'           => $orderby,
        ];

        if ($search) {
            $query['search'] = $search;
        }

        return $this->requestJson(
            'GET',
            'collections',
            ['query' => $query],
            'Could not list collections.'
        );
    }

    public function get(string $collectionId, bool $includeThumbnails = false): array
    {
        $query = [
            'includeThumbnails' => $includeThumbnails ? 'true' : 'false',
        ];

        return $this->requestJson(
            'GET',
            'collections/' . $collectionId,
            ['query' => $query],
            'Could not get collection.',
            $collectionId
        );
    }

    public function create(string $name): array
    {
        return $this->requestJson(
            'POST',
            'collections',
            ['json' => ['name' => $name]],
            'Could not create collection.'
        );
    }

    public function update(string $collectionId, string $name): array
    {
        return $this->requestJson(
            'POST',
            'collections/' . $collectionId,
            ['json' => ['name' => $name]],
            'Could not update collection.',
            $collectionId
        );
    }

    public function delete(string $collectionId): array
    {
        return $this->requestJson(
            'DELETE',
            'collections/' . $collectionId,
            [],
            'Could not delete collection.',
            $collectionId
        );
    }
}
