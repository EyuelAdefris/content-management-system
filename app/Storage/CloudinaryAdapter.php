<?php

namespace App\Storage;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Exception\ApiError;
use League\Flysystem\Config;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FileAttributes;
use League\Flysystem\PathPrefixer;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;

class CloudinaryAdapter implements FilesystemAdapter
{
    private Cloudinary $cloudinary;
    private string $cloudName;
    private PathPrefixer $prefixer;

    public function __construct(Cloudinary $cloudinary, string $cloudName, string $prefix = '')
    {
        $this->cloudinary = $cloudinary;
        $this->cloudName  = $cloudName;
        $this->prefixer   = new PathPrefixer($prefix);
    }

    /**
     * Upload a file to Cloudinary.
     */
    public function write(string $path, string $contents, Config $config): void
    {
        $tmpFile = $this->getTempFile();
        file_put_contents($tmpFile, $contents);

        try {
            $this->uploadFile($tmpFile, $path, $config);
        } finally {
            @unlink($tmpFile);
        }
    }

    /**
     * Upload a file stream to Cloudinary.
     */
    public function writeStream(string $path, $contents, Config $config): void
    {
        $tmpFile = $this->getTempFile();
        file_put_contents($tmpFile, stream_get_contents($contents));

        try {
            $this->uploadFile($tmpFile, $path, $config);
        } finally {
            @unlink($tmpFile);
        }
    }

    /**
     * Get a reliable temporary file path.
     */
    private function getTempFile(): string
    {
        $tmpDir = storage_path('framework/cache');
        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0755, true);
        }
        $tmpFile = @tempnam($tmpDir, 'cld_');
        if (!$tmpFile) {
            $tmpFile = $tmpDir . DIRECTORY_SEPARATOR . uniqid('cld_', true);
        }
        return $tmpFile;
    }

    /**
     * Core upload logic — handles both images and raw files (documents, videos).
     */
    private function uploadFile(string $tmpFile, string $path, Config $config): void
    {
        // Strip extension from public_id (Cloudinary adds it back)
        $publicId   = pathinfo($path, PATHINFO_DIRNAME) . '/' . pathinfo($path, PATHINFO_FILENAME);
        $publicId   = ltrim(str_replace('//', '/', $publicId), '/');
        $extension  = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Determine resource type
        $imageExts    = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico', 'tiff'];
        $videoExts    = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];
        $resourceType = 'raw';

        if (in_array($extension, $imageExts)) {
            $resourceType = 'image';
        } elseif (in_array($extension, $videoExts)) {
            $resourceType = 'video';
        }

        try {
            $this->cloudinary->uploadApi()->upload($tmpFile, [
                'public_id'     => $publicId,
                'resource_type' => $resourceType,
                'overwrite'     => true,
            ]);
        } catch (\Exception $e) {
            throw UnableToWriteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    /**
     * Check if a file exists.
     */
    public function fileExists(string $path): bool
    {
        try {
            $publicId     = $this->pathToPublicId($path);
            $resourceType = $this->detectResourceType($path);
            $this->cloudinary->adminApi()->asset($publicId, ['resource_type' => $resourceType]);
            return true;
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Check if a directory exists (Cloudinary uses virtual folders).
     */
    public function directoryExists(string $path): bool
    {
        return true; // Cloudinary folders are virtual
    }

    /**
     * Read a file's contents.
     */
    public function read(string $path): string
    {
        $url = $this->getUrl($path);
        $contents = @file_get_contents($url);

        if ($contents === false) {
            throw UnableToReadFile::fromLocation($path);
        }

        return $contents;
    }

    /**
     * Read a file as a stream.
     */
    public function readStream(string $path)
    {
        $url    = $this->getUrl($path);
        $stream = @fopen($url, 'rb');

        if ($stream === false) {
            throw UnableToReadFile::fromLocation($path);
        }

        return $stream;
    }

    /**
     * Delete a file from Cloudinary.
     */
    public function delete(string $path): void
    {
        try {
            $publicId     = $this->pathToPublicId($path);
            $resourceType = $this->detectResourceType($path);
            $this->cloudinary->uploadApi()->destroy($publicId, ['resource_type' => $resourceType]);
        } catch (\Exception $e) {
            throw UnableToDeleteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    /**
     * Delete a directory (and all its assets) from Cloudinary.
     */
    public function deleteDirectory(string $path): void
    {
        try {
            $this->cloudinary->adminApi()->deleteAssetsByPrefix($path);
        } catch (\Exception) {
            // Silently ignore if folder doesn't exist
        }
    }

    /**
     * Create a directory (no-op — Cloudinary creates folders automatically).
     */
    public function createDirectory(string $path, Config $config): void
    {
        // Cloudinary creates directories automatically on upload
    }

    /**
     * Set visibility (Cloudinary files are always public in free tier).
     */
    public function setVisibility(string $path, string $visibility): void
    {
        // Not applicable for Cloudinary free tier
    }

    /**
     * Get file visibility.
     */
    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path, null, 'public');
    }

    /**
     * Get file metadata.
     */
    public function mimeType(string $path): FileAttributes
    {
        return new FileAttributes($path);
    }

    public function lastModified(string $path): FileAttributes
    {
        return new FileAttributes($path);
    }

    public function fileSize(string $path): FileAttributes
    {
        return new FileAttributes($path);
    }

    /**
     * List directory contents.
     */
    public function listContents(string $path, bool $deep): iterable
    {
        return [];
    }

    /**
     * Move a file.
     */
    public function move(string $source, string $destination, Config $config): void
    {
        $srcPublicId  = $this->pathToPublicId($source);
        $destPublicId = $this->pathToPublicId($destination);
        $resourceType = $this->detectResourceType($source);

        $this->cloudinary->uploadApi()->rename($srcPublicId, $destPublicId, [
            'resource_type' => $resourceType,
        ]);
    }

    /**
     * Copy a file.
     */
    public function copy(string $source, string $destination, Config $config): void
    {
        $contents = $this->read($source);
        $this->write($destination, $contents, $config);
    }

    /**
     * Get the URL for a stored path (required by Laravel's FilesystemAdapter).
     */
    public function getUrl(string $path): string
    {
        $publicId     = $this->pathToPublicId($path);
        $extension    = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $resourceType = $this->detectResourceType($path);

        // Raw files need the original filename in the URL
        if ($resourceType === 'raw') {
            return "https://res.cloudinary.com/{$this->cloudName}/raw/upload/{$publicId}.{$extension}";
        }

        return "https://res.cloudinary.com/{$this->cloudName}/{$resourceType}/upload/{$publicId}.{$extension}";
    }

    /**
     * Convert a storage path to a Cloudinary public_id (no extension).
     */
    private function pathToPublicId(string $path): string
    {
        $withoutExt = pathinfo($path, PATHINFO_DIRNAME) . '/' . pathinfo($path, PATHINFO_FILENAME);
        return ltrim(str_replace('//', '/', $withoutExt), '/');
    }

    /**
     * Detect the Cloudinary resource type from file extension.
     */
    private function detectResourceType(string $path): string
    {
        $ext       = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico', 'tiff'];
        $videoExts = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];

        if (in_array($ext, $imageExts)) return 'image';
        if (in_array($ext, $videoExts)) return 'video';
        return 'raw';
    }
}
