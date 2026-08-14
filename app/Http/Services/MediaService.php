<?php

namespace App\Http\Services;

use App\Models\Media;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class MediaService
{
    private array $allowedMediaType = [
        'image/jpeg',
        'image/png',
        'image/jpg',
        'application/pdf',
    ];

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Creates a new media record in the database
     * @param array $data
     * @param UploadedFile $file
     * @return Media
     */
    public function createMedia(array $data, UploadedFile $file)
    {
        $filename = $data['file_url'];
        $filePath = $data['file_path'];
        $userProfileId = session('user_profile_id');
        $fileMimeType = $file->getMimeType();

        if (!in_array($fileMimeType, $this->allowedMediaType)) {
            throw new Exception('Invalid file type. File must either be ' . implode(', ', $this->allowedMediaType), Response::HTTP_BAD_REQUEST);
        }

        $mediaType = str_starts_with($fileMimeType, 'application') ? 'pdf' : explode('/', $fileMimeType)[0];
        $file->move($filePath, $filename);

        $media = Media::create([
            'uploaded_by_user_id' => $userProfileId,
            'source_name' => $data['source_name'],
            'source_id' => $data['source_id'],
            'media_type' => $mediaType,
            'file_url' => $filename,
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        return $media;
    }
}
