<?php

namespace App\Services;

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

    private function validateFileType(mixed $file)
    {
        if (!$file instanceof UploadedFile) {
            throw new Exception('File field must be of type UploadedFile.', Response::HTTP_BAD_REQUEST);
        }
    }

    private function moveFileGetInsertRecord(array $data, string $filePath, UploadedFile $file)
    {
        // $filePath = $data['file_path'];
        $filename = uniqid($data['source_name'] . '_') . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $fileMimeType = $file->getMimeType();
        $userProfileId = session('user_profile_id');

        if (!in_array($fileMimeType, $this->allowedMediaType)) {
            throw new Exception('Invalid file type. File must either be ' . implode(', ', $this->allowedMediaType), Response::HTTP_BAD_REQUEST);
        }

        $mediaType = str_starts_with($fileMimeType, 'application') ? 'pdf' : explode('/', $fileMimeType)[0];
        $file->move($filePath, $filename);

        $insertRecord = [
            'uploaded_by_user_id' => $userProfileId,
            'source_name' => $data['source_name'],
            'source_id' => $data['source_id'],
            'media_type' => $mediaType,
            'file_name' => $filename,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return $insertRecord;
    }

    /**
     * Creates a new media record in the database
     * @param array $data
     * @return Media
     */
    public function createMedia(array $data)
    {
        if (isset($data['media']) && is_array($data['media'])) {
            // if multiple medias were sent
            $insertRecord = [];

            foreach ($data['media'] as $media) {
                $file = $media['file'];

                $this->validateFileType($file);

                $media['source_name'] = $data['source_name'];
                $media['source_id'] = $data['source_id'];

                $insertRecord[] = $this->moveFileGetInsertRecord($media, $data['file_path'], $file);
            }

            return Media::insert($insertRecord);
        } else {
            // if only one media was sent
            $file = $data['file'];

            $this->validateFileType($file);

            $insertRecord = $this->moveFileGetInsertRecord($data, $data['file_path'], $file);
        }

        $media = Media::create($insertRecord);

        return $media;
    }
}
