<?php
/**
 * =================================================================
 * FILE: app/Helpers/FileUploadHelper.php
 * =================================================================
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileUploadHelper
{
    /**
     * Upload file ke storage
     */
    public static function upload(UploadedFile $file, string $directory = 'uploads', string $disk = 'public')
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, $disk);
        
        return $path;
    }

    /**
     * Upload dengan custom filename
     */
    public static function uploadAs(UploadedFile $file, string $filename, string $directory = 'uploads', string $disk = 'public')
    {
        $path = $file->storeAs($directory, $filename, $disk);
        return $path;
    }

    /**
     * Delete file dari storage
     */
    public static function delete(?string $path, string $disk = 'public')
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }

    /**
     * Get full URL dari storage path
     */
    public static function url(?string $path, string $disk = 'public')
    {
        if (!$path) {
            return null;
        }
        
        return Storage::disk($disk)->url($path);
    }

    /**
     * Check if file exists
     */
    public static function exists(?string $path, string $disk = 'public')
    {
        if (!$path) {
            return false;
        }
        
        return Storage::disk($disk)->exists($path);
    }
}
