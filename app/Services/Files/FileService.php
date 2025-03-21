<?php

namespace App\Services\Files;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    protected string $disk;

    public function __construct()
    {
        $this->disk = 'public'; // Change to 'public' if you need public storage
    }

    /**
     * Upload a single file.
     */
    public function uploadFile(UploadedFile $file, string $folder): string
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, $this->disk);

        return $filename; // Store this in the database
    }

    /**
     * Upload multiple files.
     */
    public function uploadMultipleFiles(array $files, string $folder): array
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $this->uploadFile($file, $folder);
        }
        return $paths;
    }

    /**
     * Update a file (delete old and upload new one).
     */
    public function updateFile(UploadedFile $newFile, string $oldPath, string $folder): string
    {
        if (Storage::disk($this->disk)->exists($oldPath)) {
            Storage::disk($this->disk)->delete($oldPath);
        }
        return $this->uploadFile($newFile, $folder);
    }

    /**
     * Delete a file.
     */
    public function deleteFile(string $filePath): bool
    {
        return Storage::disk($this->disk)->delete($filePath);
    }

    /**
     * Get file URL (Only works if stored in 'public' disk).
     */
    public function getFileUrl(string $filePath): string
    {
        return Storage::disk($this->disk)->url($filePath);
    }
}
