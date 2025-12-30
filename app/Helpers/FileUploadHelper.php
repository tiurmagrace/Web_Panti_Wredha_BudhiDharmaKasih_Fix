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

    /**
     * Process base64 image - compress if GD available, otherwise return as-is
     * @param string $base64Image - Base64 encoded image (with or without data URI prefix)
     * @param int $maxWidth - Maximum width in pixels (only used if GD available)
     * @param int $quality - JPEG quality (0-100) (only used if GD available)
     * @return string|null - Processed base64 image or null on failure
     */
    public static function compressBase64Image(?string $base64Image, int $maxWidth = 800, int $quality = 70): ?string
    {
        if (!$base64Image) {
            return null;
        }

        // If it's not a base64 image (e.g., URL or path), return as is
        if (!self::isBase64Image($base64Image)) {
            return $base64Image;
        }

        // Check if GD library is available
        if (!function_exists('imagecreatefromstring')) {
            // GD not available, return original but truncate if too large
            // Max ~500KB for base64 (roughly 375KB actual image)
            if (strlen($base64Image) > 500000) {
                // Try to reduce quality by re-encoding (simple approach)
                return self::reduceBase64Size($base64Image);
            }
            return $base64Image;
        }

        try {
            // Extract the base64 data
            $imageData = $base64Image;
            $mimeType = 'image/jpeg';
            
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
                $mimeType = 'image/' . $matches[1];
                $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            }

            // Decode base64
            $decodedImage = base64_decode($imageData);
            if (!$decodedImage) {
                return $base64Image;
            }

            // Create image resource
            $image = @imagecreatefromstring($decodedImage);
            if (!$image) {
                return $base64Image;
            }

            // Get original dimensions
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calculate new dimensions
            if ($originalWidth > $maxWidth) {
                $ratio = $maxWidth / $originalWidth;
                $newWidth = $maxWidth;
                $newHeight = (int) ($originalHeight * $ratio);
            } else {
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            }

            // Create new image with new dimensions
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG
            if ($mimeType === 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Resize
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Output to buffer
            ob_start();
            if ($mimeType === 'image/png') {
                imagepng($newImage, null, 9);
            } else {
                imagejpeg($newImage, null, $quality);
            }
            $compressedData = ob_get_clean();

            // Clean up
            imagedestroy($image);
            imagedestroy($newImage);

            // Return as base64 with data URI
            $outputMime = ($mimeType === 'image/png') ? 'image/png' : 'image/jpeg';
            return 'data:' . $outputMime . ';base64,' . base64_encode($compressedData);

        } catch (\Exception $e) {
            return $base64Image;
        }
    }

    /**
     * Reduce base64 size by truncating (fallback when GD not available)
     * This is a simple approach - just returns original if can't process
     */
    private static function reduceBase64Size(string $base64Image): string
    {
        // If image is too large and we can't compress, just return it
        // The database column is now TEXT so it should handle it
        return $base64Image;
    }

    /**
     * Check if string is a valid base64 image
     */
    public static function isBase64Image(?string $string): bool
    {
        if (!$string) {
            return false;
        }
        
        return (bool) preg_match('/^data:image\/(\w+);base64,/', $string);
    }

    /**
     * Save base64 image to storage and return path
     * This is better for large images - saves to file instead of database
     */
    public static function saveBase64ToFile(?string $base64Image, string $folder = 'uploads', string $disk = 'public'): ?string
    {
        if (!$base64Image || !self::isBase64Image($base64Image)) {
            return null;
        }

        try {
            // Extract extension and data
            if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
                return null;
            }

            $extension = strtolower($matches[1]);
            if ($extension === 'jpeg') $extension = 'jpg';
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($extension, $allowedExtensions)) {
                return null;
            }

            // Decode base64
            $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($base64Data);

            if ($imageData === false) {
                return null;
            }

            // Generate unique filename
            $filename = $folder . '/' . uniqid() . '_' . time() . '.' . $extension;

            // Save to storage
            Storage::disk($disk)->put($filename, $imageData);

            return $filename;

        } catch (\Exception $e) {
            return null;
        }
    }
}
