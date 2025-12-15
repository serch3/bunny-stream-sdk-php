# Bunny Stream PHP SDK

A modern, robust PHP library to interact with the [Bunny Stream API](https://docs.bunny.net/reference/api-overview).

[![Latest Stable Version](https://poser.pugx.org/serch3/bunny-stream/v/stable)](https://packagist.org/packages/serch3/bunny-stream)
[![License](https://poser.pugx.org/serch3/bunny-stream/license)](https://packagist.org/packages/serch3/bunny-stream)

## Requirements

- PHP 8.2+
- Composer

## Installation

```shell
composer require serch3/bunny-stream
```

## Quick Start

Initialize the client with your API Key and Library ID:

```php
use Bunny\Stream\Client;

$client = new Client('YOUR_API_KEY', 'YOUR_LIBRARY_ID');
```

### Basic Usage

**List Videos:**
```php
$videos = $client->video()->list();
foreach ($videos['items'] as $video) {
    echo $video['title'] . "\n";
}
```

**Upload a Video (Resumable):**
```php
// 1. Create a video entry
$video = $client->video()->create('My Awesome Video');

// 2. Upload using Tus
$uploader = $client->tus()->createUpload(
    $video['guid'],
    '/path/to/video.mp4'
);
$uploader->upload();
```

**Manage Collections:**
```php
$client->collection()->create('New Collection');
```

## Documentation

For a complete list of available methods and parameters, please see the **[API Reference](REFERENCE.md)**.

## Upgrading

Upgrading from v1? Check out the **[Upgrade Guide](UPGRADING.md)**.

## License

MIT
