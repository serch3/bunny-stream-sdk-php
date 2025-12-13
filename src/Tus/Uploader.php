<?php

declare(strict_types=1);

namespace Bunny\Stream\Tus;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Uploader
{
    private const TUS_VERSION = '1.0.0';
    private int $chunkSize = 5 * 1024 * 1024; // 5MB default

    public function __construct(
        private GuzzleClient $client,
        private string $filePath,
        private string $uploadUrl
    ) {}

    public function setChunkSize(int $bytes): self
    {
        $this->chunkSize = $bytes;
        return $this;
    }

    public function upload(): void
    {
        if (!file_exists($this->filePath)) {
            throw new \Exception("File not found: {$this->filePath}");
        }

        $fileSize = filesize($this->filePath);
        $handle = fopen($this->filePath, 'rb');
        $offset = $this->getOffset();

        while ($offset < $fileSize) {
            fseek($handle, $offset);
            $chunk = fread($handle, $this->chunkSize);
            
            try {
                $response = $this->client->request('PATCH', $this->uploadUrl, [
                    'headers' => [
                        'Tus-Resumable' => self::TUS_VERSION,
                        'Upload-Offset' => $offset,
                        'Content-Type'  => 'application/offset+octet-stream',
                    ],
                    'body' => $chunk,
                ]);

                $newOffset = $response->getHeaderLine('Upload-Offset');
                if (!$newOffset) {
                    throw new \Exception('Missing Upload-Offset header in response');
                }
                $offset = (int) $newOffset;

            } catch (GuzzleException $e) {
                throw new \Exception("Upload failed at offset $offset: " . $e->getMessage(), 0, $e);
            }
        }

        fclose($handle);
    }

    private function getOffset(): int
    {
        try {
            $response = $this->client->request('HEAD', $this->uploadUrl, [
                'headers' => [
                    'Tus-Resumable' => self::TUS_VERSION,
                ],
            ]);
            return (int) $response->getHeaderLine('Upload-Offset');
        } catch (GuzzleException $e) {
            return 0;
        }
    }
}
