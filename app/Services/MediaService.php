<?php

namespace App\Services;

use App\Models\Media;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class MediaService
{
    private array $allowedMediaType = [
        'image/jpeg',
        'image/png',
        'image/jpg',
        'application/pdf',
    ];

    private function validateFileType(mixed $file): void
    {
        if (!$file instanceof UploadedFile) {
            throw new Exception('File field must be of type UploadedFile.', Response::HTTP_BAD_REQUEST);
        }
    }

    private function moveFileGetInsertRecord(array $data, string $filePath, UploadedFile $file, int $userProfileId): array
    {
        $filename = uniqid($data['source_name'] . '_') . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $fileMimeType = $file->getMimeType();

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
     * 
     * @param array $data
     * @return Media|bool
     */
    public function createMedia(array $data, int $userProfileId): Media|bool
    {
        if (isset($data['media']) && is_array($data['media'])) {
            // if multiple medias were sent
            $insertRecord = [];

            foreach ($data['media'] as $media) {
                $file = $media['file'];

                $this->validateFileType($file);

                $media['source_name'] = $data['source_name'];
                $media['source_id'] = $data['source_id'];

                $insertRecord[] = $this->moveFileGetInsertRecord($media, $data['file_path'], $file, $userProfileId);
            }

            return Media::insert($insertRecord);
        } else {
            // if only one media was sent
            $file = $data['file'];

            $this->validateFileType($file);

            $insertRecord = $this->moveFileGetInsertRecord($data, $data['file_path'], $file, $userProfileId);
        }

        $media = Media::create($insertRecord);

        return $media;
    }

    /**
     * Deletes the media records and media files in uploads folder
     * 
     * @param string $sourceName
     * @param int $sourceId
     * @param string $filePath
     * @throws Exception
     * @return bool
     */
    public function deleteMediaBySource(string $sourceName, int $sourceId, string $filePath): bool
    {
        $media = Media::where([
            'source_name' => $sourceName,
            'source_id' => $sourceId
        ])->get();

        if ($media->isEmpty()) {
            throw new Exception('No media found with given source name and source ID.', Response::HTTP_NOT_FOUND);
        }

        // delete all the files associated with this media
        foreach ($media as $m) {
            if (File::exists($filePath . $m->file_name)) {
                File::delete($filePath . $m->file_name);
            }
        }

        // delete data from database after deleting the file in uploads folder
        $result = $media = Media::where([
            'source_name' => $sourceName,
            'source_id' => $sourceId
        ])->delete();

        return $result;
    }
}
