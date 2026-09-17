@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/recruiter.css') }}">

<div class="content p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-lg-row gap-3">
        <h3 class="m-0 fw-bold">Positions</h3>
        <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
            <i class="fa-solid fa-filter"></i>
            Positions
        </button>
    </div>

    <div class="shortlist-layout">

        <x-atom.position-list />

        <x-atom.position-detail />

    </div>
</div>

<div class="shortlist-overlay toggleFilter"></div>
<x-modals.list-interview-modal />
<x-modals.list-invitation-modal />
<x-modals.list-jobOffer-modal />

<x-modals.position-modal />
<x-modals.invitation-modal />
<x-modals.interview-modal />
<x-modals.job-offer-modal />


@endsection

@section('script')
<script type="module">

    let myData, positionId, curruntPosition, candidateList, curruntCandidate, listInvite, currentUserId

    async function getShortlistedPositionIds(profileId, orgId) {
        try {
            let response = await xApiPosition.getShortlistedPositionIds("{{ route('shortlists.getShortlistedPositionIds',['profileId' => '__profileId__','orgId' => '__orgId__' ]) }}")
            if(!response) return


        } catch (error) {
            console.error(error);
        }
    }

    $(document).on("click", ".toggleFilter", toggle)

    $(document).ready(async function(){
        try {

            let profile = await xApiProfile.getProfileDataByProfileId(
                "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}",
                "{{ session('user_profile_id') }}"
            );

            let organizations = profile.data.organization_users;
            myData = profile.data

            let response = await Promise.all(
                organizations.map(organization => {

                    let orgId = organization.organization_id;

                    return xApiPosition.getPositionsByOrgId(
                        "{{ route('positions.getPositionsByOrgId', ['id' => '__ID__']) }}",
                        orgId
                    );

                })
            );

            positionList.init(response)
            positionDetail.init()

            invitationModal.init()
            interviewModal.init()
            jobOfferModal.init()

            listInvitationModal.init()
            listJobOfferModal.init()
            listInterviewModal.init()

            shortlistModal.init(myData)

        } catch (error) {
            console.error("Ralat semasa loadData:", error);
        }
    })


</script>
@endsection
