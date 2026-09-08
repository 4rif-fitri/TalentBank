<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Helpers\CheckOrgRoleHelper;
use App\Models\JobOffer;
use App\Models\Position;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class JobOfferService
{
    private const ADMINISTRATIVE_ROLES = ['Organization Admin', 'Recruiter'];
    private const LOCKED_STATUS = [
        AppConstants::JOB_OFFER_STATUS['ACCEPTED'],
        AppConstants::JOB_OFFER_STATUS['REJECTED'],
        AppConstants::JOB_OFFER_STATUS['WITHDRAWN'],
    ];

    // used to determine the columns to be returned for related models
    private const POSITION_RETURN_COLUMNS = 'positions.id,position_title,organization_id';
    private const PROFILE_RETURN_COLUMNS = 'id,name,profile_image,location,headline';
    private const ORGANIZATION_RETURN_COLUMNS = 'id,company_name,organization_logo';

    /**
     * Retrieves a job offer scoped to the given profile column/id and ensures it is still in an editable state
     *
     * @param int $jobOfferId
     * @param string $profileColumn
     * @param int $userProfileId
     * @return JobOffer
     * @throws Exception if job offer is not found, access is unauthorized, or job offer is locked
     */
    private function getJobOfferModel(int $jobOfferId, string $profileColumn, int $userProfileId): JobOffer
    {
        $jobOffer = JobOffer::with('position')
            ->where($profileColumn, $userProfileId)
            ->find($jobOfferId);

        if (!isset($jobOffer)) {
            throw new Exception('Job offer not found or access unauthorized.', Response::HTTP_NOT_FOUND);
        }

        return $jobOffer;
    }

    /**
     * Updates the offer_status of a job offer after validating ownership and admin role
     *
     * @param int $jobOfferId
     * @param string $status
     * @param string $profileColumn
     * @param int $senderId
     * @return JobOffer
     * @throws Exception if access is unauthorized
     */
    private function updateJobOfferStatus(int $jobOfferId, string $status, string $profileColumn, int $senderId): JobOffer
    {
        $jobOffer = $this->getJobOfferModel($jobOfferId, $profileColumn, $senderId);

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($senderId, self::ADMINISTRATIVE_ROLES, $jobOffer->position->organization_id);

        if (!$isUserAdmin && $profileColumn == 'sender_profile_id') {
            throw new Exception('Unauthorized access to update interview.', Response::HTTP_FORBIDDEN);
        }

        if (in_array($jobOffer->offer_status, self::LOCKED_STATUS)) {
            throw new Exception('Job offer accepted, rejected or withdrawn cannot be updated anymore.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $jobOffer->update(['offer_status' => $status]);
        return $jobOffer->unsetRelation('position');
    }

    /**
     * Retrieves job offers where the given profile is the sender
     * 
     * @param int $senderId
     * @param ?string $offerStatus
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, JobOffer>
     */
    public function getJobOffersByStatusAndSenderId(int $senderId, ?string $offerStatus): Collection
    {
        return JobOffer::with([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'receiver:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS
        ])
            ->when(isset($offerStatus), function ($query) use ($offerStatus) {
                $query->where('offer_status', $offerStatus);
            })
            ->where('sender_profile_id', $senderId)
            ->get();
    }

    /**
     * Retrieves job offers where the given profile is the receiver
     * 
     * @param int $receiverId
     * @param ?string $offerStatus
     * @return Collection<int, \stdClass>|\Illuminate\Database\Eloquent\Collection<int, JobOffer>
     */
    public function getJobOffersByStatusAndReceiverId(int $receiverId, ?string $offerStatus): Collection
    {
        return JobOffer::with([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'sender:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS
        ])
            ->when(isset($offerStatus), function ($query) use ($offerStatus) {
                $query->where('offer_status', $offerStatus);
            })
            ->where('receiver_profile_id', $receiverId)
            ->get();
    }

    /**
     * Retrieves a job offer by job offer ID, accessible to either the sender or receiver
     *
     * @param int $jobOfferId
     * @param int $userProfileId
     * @return JobOffer
     * @throws Exception if job offer is not found or access is unauthorized
     */
    public function getJobOfferById(int $jobOfferId, int $userProfileId): JobOffer
    {
        $jobOffer = JobOffer::with([
            'position',
            'sender:' . self::PROFILE_RETURN_COLUMNS,
            'receiver:' . self::PROFILE_RETURN_COLUMNS,
            'position.organization:' . self::ORGANIZATION_RETURN_COLUMNS
        ])
            ->where(function ($query) use ($userProfileId) {
                $query->where('sender_profile_id', $userProfileId)
                    ->orWhere('receiver_profile_id', $userProfileId);
            })
            ->find($jobOfferId);

        if (!isset($jobOffer)) {
            throw new Exception('Job offer not found or access unauthorized on job offer.', Response::HTTP_NOT_FOUND);
        }

        $jobOffer->user_role = $jobOffer->sender_profile_id === $userProfileId ? 'sender' : 'receiver';

        return $jobOffer;
    }

    /**
     * Creates a new job offer for the position
     *
     * @param array $data
     * @param int $senderId
     * @return JobOffer
     * @throws Exception
     */
    public function createJobOffer(array $data, int $senderId): JobOffer
    {
        $position = Position::find($data['position_id']);

        // check if current user is still an admin role of the current org
        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($senderId, self::ADMINISTRATIVE_ROLES, $position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to create job offer.', Response::HTTP_FORBIDDEN);
        }

        // create job offer
        $jobOffer = JobOffer::create([
            'position_id' => $data['position_id'],
            'sender_profile_id' => $senderId,
            'receiver_profile_id' => $data['receiver_profile_id'],
            'salary_amount' => $data['salary_amount'],
            'salary_period' => $data['salary_period'],
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'terms_and_conditions' => $data['terms_and_conditions'] ?? null,
            'benefits' => $data['benefits'] ?? null,
            'offer_status' => AppConstants::JOB_OFFER_STATUS['PENDING'],
            'expires_at' => $data['expires_at'],
        ]);

        return $jobOffer->load([
            'position:' . self::POSITION_RETURN_COLUMNS,
            'receiver:' . self::PROFILE_RETURN_COLUMNS,
        ]);
    }

    /**
     * Updates existing job offer info (excluding status), only allowed by the sender with an admin role
     *
     * @param array $data
     * @param int $jobOfferId
     * @param int $senderId
     * @return JobOffer
     * @throws Exception if access is unauthorized
     */
    public function updateJobOffer(array $data, int $jobOfferId, int $senderId): JobOffer
    {
        $jobOffer = $this->getJobOfferModel($jobOfferId, 'sender_profile_id', $senderId);

        $isUserAdmin = CheckOrgRoleHelper::userHasRoles($senderId, self::ADMINISTRATIVE_ROLES, $jobOffer->position->organization_id);

        if (!$isUserAdmin) {
            throw new Exception('Unauthorized access to update job offer.', Response::HTTP_FORBIDDEN);
        }

        if (in_array($jobOffer->offer_status, self::LOCKED_STATUS)) {
            throw new Exception('Job offer accepted, rejected or withdrawn cannot be updated anymore.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $jobOffer->update([
            'salary_amount' => $data['salary_amount'],
            'salary_period' => $data['salary_period'],
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'terms_and_conditions' => $data['terms_and_conditions'] ?? null,
            'benefits' => $data['benefits'] ?? null,
            'expires_at' => $data['expires_at'],
        ]);

        return $jobOffer->unsetRelation('position');
    }

    /**
     * Marks a job offer as accepted, scoped to the receiver
     *
     * @param int $jobOfferId
     * @param int $receiverId
     * @return JobOffer
     */
    public function acceptJobOffer(int $jobOfferId, int $receiverId): JobOffer
    {
        return $this->updateJobOfferStatus($jobOfferId, AppConstants::JOB_OFFER_STATUS['ACCEPTED'], 'receiver_profile_id', $receiverId);
    }

    /**
     * Marks a job offer as rejected, scoped to the receiver
     *
     * @param int $jobOfferId
     * @param int $receiverId
     * @return JobOffer
     */
    public function rejectJobOffer(int $jobOfferId, int $receiverId): JobOffer
    {
        return $this->updateJobOfferStatus($jobOfferId, AppConstants::JOB_OFFER_STATUS['REJECTED'], 'receiver_profile_id', $receiverId);
    }

    /**
     * Marks a job offer as withdrawn, scoped to the sender
     *
     * @param int $jobOfferId
     * @param int $senderId
     * @return JobOffer
     */
    public function withdrawJobOffer(int $jobOfferId, int $senderId): JobOffer
    {
        return $this->updateJobOfferStatus($jobOfferId, AppConstants::JOB_OFFER_STATUS['WITHDRAWN'], 'sender_profile_id', $senderId);
    }
}