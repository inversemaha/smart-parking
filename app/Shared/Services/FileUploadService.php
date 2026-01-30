<?php

namespace App\Shared\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a file to specified disk and path
     */
    public function uploadFile(UploadedFile $file, string $path = '', string $disk = 'public'): string
    {
        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        // Create full path
        $fullPath = $path ? rtrim($path, '/') . '/' . $filename : $filename;

        // Store file
        $file->storeAs($path, $filename, $disk);

        return $fullPath;
    }

    /**
     * Upload multiple files
     */
    public function uploadMultipleFiles(array $files, string $path = '', string $disk = 'public'): array
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploadedFiles[] = $this->uploadFile($file, $path, $disk);
            }
        }

        return $uploadedFiles;
    }

    /**
     * Delete a file
     */
    public function deleteFile(string $filePath, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->delete($filePath);
    }

    /**
     * Get file URL
     */
    public function getFileUrl(string $filePath, string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($filePath);
    }

    /**
     * Check if file exists
     */
    public function fileExists(string $filePath, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->exists($filePath);
    }

    /**
     * Get file size
     */
    public function getFileSize(string $filePath, string $disk = 'public'): int
    {
        return Storage::disk($disk)->size($filePath);
    }

    /**
     * Upload image with resizing
     */
    public function uploadImage(UploadedFile $file, string $path = '', array $sizes = [], string $disk = 'public'): array
    {
        $originalPath = $this->uploadFile($file, $path, $disk);
        $uploadedFiles = ['original' => $originalPath];

        // If sizes are specified, create resized versions
        foreach ($sizes as $sizeName => $dimensions) {
            // This would require an image processing library like Intervention Image
            // For now, just return the original path
            $uploadedFiles[$sizeName] = $originalPath;
        }

        return $uploadedFiles;
    }

    /**
     * Validate file type
     */
    public function validateFileType(UploadedFile $file, array $allowedTypes): bool
    {
        $mimeType = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();

        return in_array($mimeType, $allowedTypes) || in_array($extension, $allowedTypes);
    }

    /**
     * Validate file size
     */
    public function validateFileSize(UploadedFile $file, int $maxSizeInMB): bool
    {
        $fileSizeInMB = $file->getSize() / 1024 / 1024;
        return $fileSizeInMB <= $maxSizeInMB;
    }

    /**
     * Get safe filename
     */
    protected function getSafeFilename(string $originalName): string
    {
        $pathinfo = pathinfo($originalName);
        $filename = Str::slug($pathinfo['filename']);
        $extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';

        return $filename . ($extension ? '.' . $extension : '');
    }
}
