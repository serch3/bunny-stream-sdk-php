# API Reference

This document provides a detailed reference for the Bunny Stream PHP SDK methods.

## Table of Contents

- [Videos](#videos)
- [Collections](#collections)
- [Livestreams](#livestreams)
- [Tus Uploads](#tus-uploads)

---

## Videos

Access video methods via `$client->video()`.

### List Videos
```php
$client->video()->list(
    ?string $search = null,
    int $page = 1,
    int $items = 100,
    ?string $collection = null,
    ?string $orderby = null
);
```

### Get Video
```php
$client->video()->get(string $videoId);
```

### Create Video
```php
$client->video()->create(
    string $title,
    ?string $collectionId = null,
    ?int $thumbnailTime = null
);
```

### Update Video
```php
$client->video()->update(string $videoId, array $body);
```
Example `$body`:
```php
[
    'title' => 'New Title',
    'collectionId' => '...',
    'chapters' => [...],
    'moments' => [...],
    'metaTags' => [...]
]
```

### Delete Video
```php
$client->video()->delete(string $videoId);
```

### Upload Video (Direct)
For small files, you can upload directly. For larger files, use [Tus Uploads](#tus-uploads).
```php
$client->video()->upload(
    string $videoId,
    string $path,
    ?string $enabledResolutions = null
);
```

### Set Thumbnail
```php
$client->video()->setThumbnail(string $videoId, string $url);
```

### Get Heatmap
```php
$client->video()->getHeatmap(string $videoId);
```

### Get Play Data
```php
$client->video()->getPlayData(
    string $videoId,
    ?string $token = null,
    ?int $expires = null
);
```

### Get Statistics
```php
$client->video()->getStatistics(string $videoId, ?array $query = null);
```

### Re-encode Video
```php
$client->video()->reencode(string $videoId);
```

### Add Output Codec
```php
$client->video()->addOutputCodec(string $videoId, int $codecId);
```
Codec IDs: 0 = x264, 1 = vp9, 2 = hevc, 3 = av1

### Repackage Video
```php
$client->video()->repackage(string $videoId, bool $keepOriginalFiles = true);
```

### Fetch Video (from URL)
```php
$client->video()->fetch(
    string $url,
    ?string $title = null,
    ?string $collectionId = null,
    ?int $thumbnailTime = null,
    ?array $headers = null
);
```

### Add Caption
```php
$client->video()->addCaption(
    string $videoId,
    string $srclang,
    string $path,
    string $label
);
```

### Delete Caption
```php
$client->video()->deleteCaption(string $videoId, string $srclang);
```

### Transcribe Video
```php
$client->video()->transcribe(
    string $videoId,
    string $language,
    bool $force = false,
    array $options = []
);
```

### Get Resolutions
```php
$client->video()->getResolutions(string $videoId);
```

### Cleanup Resolutions
```php
$client->video()->cleanupResolutions(
    string $videoId,
    string $resolutions, // Comma-separated string, e.g., "240p,360p"
    ?array $query = null
);
```

### Smart Generate
```php
$client->video()->smartGenerate(string $videoId);
```

---

## Collections

Access collection methods via `$client->collection()`.

### List Collections
```php
$client->collection()->list(
    ?string $search = null,
    int $page = 1,
    int $items = 100,
    string $orderby = 'date',
    bool $includeThumbnails = false
);
```

### Get Collection
```php
$client->collection()->get(string $collectionId, bool $includeThumbnails = false);
```

### Create Collection
```php
$client->collection()->create(string $name);
```

### Update Collection
```php
$client->collection()->update(string $collectionId, string $name);
```

### Delete Collection
```php
$client->collection()->delete(string $collectionId);
```

---

## Livestreams

Access livestream methods via `$client->livestream()`.

### List Livestreams
```php
$client->livestream()->list(
    ?string $search = null,
    int $page = 1,
    int $items = 100,
    string $orderby = 'date'
);
```

### Get Livestream
```php
$client->livestream()->get(string $livestreamId);
```

### Create Livestream
```php
$client->livestream()->create(string $title);
```

### Update Livestream
```php
$client->livestream()->update(string $livestreamId, array $body);
```

### Delete Livestream
```php
$client->livestream()->delete(string $livestreamId);
```

### Start/Stop Livestream
```php
$client->livestream()->start(string $livestreamId);
$client->livestream()->stop(string $livestreamId);
```

### Get Play Data
```php
$client->livestream()->getPlayData(string $livestreamId);
```

---

## Tus Uploads

For reliable, resumable uploads (recommended for large files).

```php
// 1. Create the upload
$uploader = $client->tus()->createUpload(
    string $videoId,
    string $filePath,
    string $fileType = 'video/mp4',
    ?string $title = null,
    ?string $collectionId = null
);

// 2. (Optional) Set chunk size (default 5MB)
$uploader->setChunkSize(10 * 1024 * 1024); // 10MB

// 3. Start/Resume upload
$uploader->upload();
```
