<?php

namespace App\Http\Services;

use App\Models\Media;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

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
        $userId = 1;
        $fileMimeType = $file->getMimeType();

        if (!in_array($fileMimeType, $this->allowedMediaType)) {
            throw new Exception('Invalid file type. File must either be ' . implode(', ', $this->allowedMediaType), Response::HTTP_BAD_REQUEST);
        }

        $mediaType = str_starts_with($fileMimeType, 'application') ? 'pdf' : explode('/', $fileMimeType)[0];
        $file->move($data['file_path'], $filename);

        $media = Media::create([
            'uploaded_by_user_id' => $userId,
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
