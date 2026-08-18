<?php

namespace App\Services;

use App\Models\Media;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class SemesterService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly MediaService $mediaService
    ) {
    }

    /**
     * Uploads a pdf file of the current semester's results
     * @param array $data
     * @param UploadedFile $file
     * @param int $semesterId
     * @param int $userProfileId
     * @return Media
     */
    public function uploadResults(array $data, UploadedFile $file, int $semesterId, int $userProfileId): Media
    {
        $filePath = config('services.uploads_file_path.semester_results');

        if (!isset($filePath)) {
            throw new Exception('No file path found for semester results uploads.', Response::HTTP_NOT_FOUND);
        }

        if (explode('/', $file->getMimeType())[1] != 'pdf') {
            throw new Exception('File must be in pdf format.', Response::HTTP_BAD_REQUEST);
        }

        $data['source_name'] = 'semester';
        $data['source_id'] = $semesterId;
        $data['file_name'] = uniqid('semester_results_') . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $data['file_path'] = config('services.uploads_file_path.semester_results');
        $data['file'] = $file;

        return $this->mediaService->createMedia($data, $userProfileId);
    }
}
