<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

if (!function_exists('uploadFile')) {
    /**
     * Handles file uploads for single or multiple files.
     *
     * @param mixed $files The file(s) to upload (single or array).
     * @param string $path The directory where the file(s) should be uploaded.
     * @param string|null $oldFile The old file path for deletion (optional).
     * @return array|string|null The uploaded file path(s) or null on failure.
     */
    function uploadFile($files, $path, $oldFile = null)
    {
        // Delete the old file if provided
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }

        // Handle multiple files
        if (is_array($files)) {
            $uploadedPaths = [];
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $uploadedPaths[] = $file->store($path, 'public');
                }
            }
            return $uploadedPaths;
        }

        // Handle single file
        if ($files instanceof UploadedFile) {
            return $files->store($path, 'public');
        }

        return null;
    }
}

// unlink file common function
function unlinkFile($filePath) {
    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    }
    return false;
}