# Upgrade Guide (v1 to v2)

Version 2.0 introduces a cleaner, resource-oriented architecture with better modularity and separation of concerns. 
Below are the key changes and how to adapt your existing code.

## 1. PHP Version Requirement
This library now requires **PHP 8.2** or higher.

## 2. Resource Accessors
Methods are no longer called directly on the `Client` instance. Instead, they are grouped by resource (Video, Collection, Livestream, etc.).

| Feature | v1 (Old) | v2 (New) |
|---------|----------|----------|
| **List Videos** | `$client->listVideos()` | `$client->video()->list()` |
| **Get Video** | `$client->getVideo($id)` | `$client->video()->get($id)` |
| **Create Video** | `$client->createVideo(...)` | `$client->video()->create(...)` |
| **Upload Video** | `$client->uploadVideo(...)` | `$client->video()->upload(...)` |
| **List Collections** | `$client->listCollections()` | `$client->collection()->list()` |

## 3. New Features
- **Livestream Support**: Full support for Livestream endpoints via `$client->livestream()`.
- **Tus Uploads**: Native support for resumable uploads via `$client->tus()`.
- **Modern Testing**: Now using Pest.
