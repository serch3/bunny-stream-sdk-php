<?php

declare(strict_types=1);

namespace Bunny\Stream\API;

use Bunny\Stream\AuthenticationException;
use Bunny\Stream\CollectionNotFoundException;
use Bunny\Stream\VideoNotFoundException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

abstract class AbstractApi
{
    public function __construct(
        protected GuzzleClient $client,
        protected string $apiKey,
        protected string $libraryId
    ) {}

    protected function requestJson(
        string $method,
        string $uri,
        array $options = [],
        string $failureMsg = 'Request failed.',
        ?string $notFoundRef = null
    ): array {
        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            throw new \Exception("Guzzle request error: " . $e->getMessage(), $e->getCode(), $e);
        }

        $code = $response->getStatusCode();
        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        if ($code >= 200 && $code < 300) {
            return is_array($data) ? $data : [];
        }

        match ($code) {
            401 => throw new AuthenticationException($this->apiKey),
            404 => $this->handleNotFound($uri, $notFoundRef),
            400 => $this->handleBadRequest(is_array($data) ? $data : []),
            default => null,
        };

        throw new \Exception(
            sprintf('%s (HTTP %d). Response: %s', $failureMsg, $code, $body),
            $code
        );
    }

    private function handleNotFound(string $uri, ?string $notFoundRef): void
    {
        if ($notFoundRef && str_contains($uri, 'collections')) {
            throw new CollectionNotFoundException($notFoundRef);
        }
        if ($notFoundRef) {
            throw new VideoNotFoundException($notFoundRef);
        }
        throw new \Exception('Resource not found.');
    }

    private function handleBadRequest(array $data): void
    {
        $errorMessage = $data['message'] ?? 'Bad Request';
        if (!empty($data['data']['errorList']) && is_array($data['data']['errorList'])) {
            $errorDetails = implode(', ', $data['data']['errorList']);
            $errorMessage .= ' - ' . $errorDetails;
        }
        throw new \Exception($errorMessage, 400);
    }
}
