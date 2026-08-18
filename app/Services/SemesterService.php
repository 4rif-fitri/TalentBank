<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Semester;
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

    /**
     * Creates a new semester record
     * 
     * @param array $data
     * @throws Exception
     * @return Semester
     */
    public function createSemester(array $data): Semester
    {
        $semesterExists = Semester::where([
            'education_id' => $data['education_id'],
            'session' => $data['session']
        ])->exists();

        if ($semesterExists) {
            throw new Exception('Given session for this semester already exists.', Response::HTTP_CONFLICT);
        }

        $semester = Semester::create([
            'education_id' => $data['education_id'],
            'gpa' => $data['gpa'],
            'session' => $data['session']
        ]);

        return $semester;
    }

    /**
     * Updates existing semester based on semester ID and user profile ID
     * 
     * @param array $data
     * @param int $semesterId
     * @param int $userProfileId
     * @return bool
     */
    public function updateSemester(array $data, int $semesterId, int $userProfileId): bool
    {
        $semester = Semester::where('id', $semesterId)
            ->whereHas('education', function ($query) use ($userProfileId) {
                $query->where('user_profile_id', $userProfileId);
            })->first();

        if (!isset($semester)) {
            throw new Exception('No semester found with given ID.', Response::HTTP_NOT_FOUND);
        }

        $result = $semester->update([
            'gpa' => $data['gpa'],
            'session' => $data['session']
        ]);

        return $result;
    }
}
