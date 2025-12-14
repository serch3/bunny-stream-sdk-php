<?php

declare(strict_types=1);

namespace Bunny\Stream\API;

use Bunny\Stream\Tus\Uploader;

class Tus extends AbstractApi
{
    public function createUpload(
        string $videoId,
        string $filePath,
        string $fileType = 'video/mp4',
        ?string $title = null,
        ?string $collectionId = null
    ): Uploader {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: $filePath");
        }

        $fileSize = filesize($filePath);
        $fileName = basename($filePath);
        $expiration = time() + 86400; // 24 hours from now

        // Bunny.net TUS endpoint
        $url = 'https://video.bunnycdn.com/tusupload';

        // Generate Signature
        // sha256(library_id + api_key + expiration_time + video_id)
        $signatureToHash = $this->libraryId . $this->apiKey . $expiration . $videoId;
        $signature = hash('sha256', $signatureToHash);

        $metadata = [
            'filetype' => $fileType,
            'title'    => $title ?? $fileName,
        ];

        if ($collectionId) {
            $metadata['collection'] = $collectionId;
        }

        $encodedMetadata = [];
        foreach ($metadata as $key => $value) {
            $encodedMetadata[] = $key . ' ' . base64_encode((string) $value);
        }

        $response = $this->client->request('POST', $url, [
            'headers' => [
                'AuthorizationSignature' => $signature,
                'AuthorizationExpire'    => $expiration,
                'VideoId'                => $videoId,
                'LibraryId'              => $this->libraryId,
                'Tus-Resumable'          => '1.0.0',
                'Upload-Length'          => $fileSize,
                'Upload-Metadata'        => implode(',', $encodedMetadata),
            ],
        ]);

        $location = $response->getHeaderLine('Location');
        if (!$location) {
            throw new \Exception('Could not initiate TUS upload. No Location header returned.');
        }

        return new Uploader($this->client, $filePath, $location);
    }
}
