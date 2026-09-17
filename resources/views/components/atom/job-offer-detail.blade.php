<div id="shortlistContent" class="shortlist-content flex-grow-1 p-2"></div>

@push('childScript')
<script type="module">
    window.interviewDetail = {
        currentJobOffer: null,
        currentEducation: null,

        async load(jobOfferId){
            try {
                let jobOfferResponse = await xApiJobOffer.getJobOfferById("{{ route('jobOffers.getJobOfferById', ['id' => '__ID__']) }}", jobOfferId);
                let educationResponse = await xApiEducation.getEducationByUserProfileId("{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}", jobOfferResponse.data.receiver.id);

                this.currentJobOffer = jobOfferResponse.data
                this.currentEducation = educationResponse.data
                let imageUrl = "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/" + this.currentJobOffer.receiver.profile_image

                $(".shortlist-content").html(xjobOffer.recruiter.mainContent(this.currentJobOffer, imageUrl, this.currentEducation))
            } catch (error) {
                console.log(error);
            }
        },

        education(){
            let educationList = this.currentEducation
            let education = this.currentEducation
            let modalBody = $("#activeEducationList");
            modalBody.empty();

            if (educationList.length === 0) {
                modalBody.append("<p>No active educations found.</p>");
            } else {
                educationList.forEach(education => {
                    let educationHtml = xeducation.student.template(education.programme);
                    modalBody.append(educationHtml);
                });
            }

            xmodal.show("activeEducationsModal");
        }

    };
</script>
@endpush


