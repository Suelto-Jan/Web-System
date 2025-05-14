<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Upload an image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null
     */
    public function uploadImage(UploadedFile $file, string $folder = 'student-photos'): ?string
    {
        try {
            // Upload the image to Cloudinary
            $result = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder,
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ]
            ]);

            // Return the secure URL
            return $result->getSecurePath();
        } catch (\Exception $e) {
            Log::error('Cloudinary upload error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }

    /**
     * Delete an image from Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public function deleteImage(string $publicId): bool
    {
        try {
            // Extract public ID from URL if needed
            if (strpos($publicId, 'http') === 0) {
                $parts = explode('/', $publicId);
                $filename = end($parts);
                $publicId = pathinfo($filename, PATHINFO_FILENAME);
            }

            // Delete the image from Cloudinary
            $result = Cloudinary::destroy($publicId);
            
            return $result->getResult() === 'ok';
        } catch (\Exception $e) {
            Log::error('Cloudinary delete error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * Get the public ID from a Cloudinary URL
     *
     * @param string $url
     * @return string|null
     */
    public function getPublicIdFromUrl(string $url): ?string
    {
        try {
            // Parse the URL to extract the public ID
            $parsedUrl = parse_url($url);
            $path = $parsedUrl['path'] ?? '';
            
            // Remove version number if present
            $pathParts = explode('/', $path);
            $filename = end($pathParts);
            
            // Remove file extension
            $publicId = pathinfo($filename, PATHINFO_FILENAME);
            
            return $publicId;
        } catch (\Exception $e) {
            Log::error('Error extracting public ID: ' . $e->getMessage());
            return null;
        }
    }
}
