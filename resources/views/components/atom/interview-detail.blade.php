@props(['panel'])

@if($panel == "std")
<div class="filter-panel flex-grow-1">
    <div class="row g-3 " id="shortlistContent"></div>
</div>
@else
<div id="shortlistContent" class="shortlist-content flex-grow-1 p-2"></div>
@endif


@section('scriptComponent')
<script type="module">

    window.interviewDetail = {
        current: null,
        educations: [],
        role: null,

        config: {
            recruiter: {
                render: xinterview.recruiter.mainContent,
                requiresEducation: true,
                imagePath: "{{ asset('storage/' . env('PROFILE_IMAGE_URL')) }}/",
            },

            student: {
                render: xinterview.student.mainContent,
                requiresEducation: false,
                imagePath: "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/",
            }
        },

        setRole(role) {
            if (!this.config[role]) throw new Error(`Invalid interview role: ${role}`);

            this.role = role;
            return this;
        },

        async load(id) {

            try {

                if (!this.role) throw new Error("Interview role is not configured.");

                const config = this.config[this.role];

                const response = await xApiInterview.getInterviewById(
                    "{{ route('interviews.getInterviewById', ['id' => '__ID__']) }}",
                    id
                );

                if (!response?.data) return;

                this.current = response.data;
                this.educations = [];

                if (config.requiresEducation) {

                    const receiverId = this.current.interviewee.id;

                    const educationResponse =
                        await xApiEducation.getEducationByUserProfileId(
                            "{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}",
                        receiverId);

                    this.educations = educationResponse?.data ?? [];
                }

                const imageUrl = this.getImageUrl(config);

                const html = config.render(
                    this.current,
                    imageUrl,
                    ...(config.requiresEducation ? [this.educations] : [])
                );

                $("#shortlistContent").html(html);

            } catch (error) {
                console.error("Error loading interview detail:", error);
            }
        },
        showEducations() {
            const educationList = this.educations;
            const modalBody = $("#activeEducationList");

            modalBody.empty();

            if (!educationList || educationList.length === 0) {
                modalBody.append(
                    "<p>No active educations found.</p>"
                );
            } else {
                educationList.forEach(education => {
                    const educationHtml =
                        xeducation.student.template(
                            education.programme
                        );

                    modalBody.append(educationHtml);
                });
            }

            xmodal.show("activeEducationsModal");
        },
        getImageUrl(config) {
            const current = this.current;

            if (this.role === "recruiter") {
                return config.imagePath + current.interviewee.profile_image;
            }
            if (this.role === "student") {
                return config.imagePath + current.position.organization.organization_logo;
            }
            return "";
        },

        reload(response) {
            this.current = {
                ...this.current,
                ...response,

                interviewee: {
                    ...this.current?.interviewee,
                    ...response?.interviewee
                },

                position: {
                    ...this.current?.position,
                    ...response?.position,

                    organization: {
                        ...this.current?.position?.organization,
                        ...response?.position?.organization
                    }
                }
            };

            this.render();
        },

        render() {
            if (!this.current || !this.role) return;

            const config = this.config[this.role];
            const imageUrl = this.getImageUrl(config);
            const html = config.render(
                this.current,
                imageUrl,
                ...(config.requiresEducation ? [this.educations] : [])
            );

            $("#shortlistContent").html(html);
        },

        clear() {
            this.current = null;
            this.educations = [];

            $("#shortlistContent").html(
                xcommon.noSelected("No Interview Selected Yet", "","btn-toggle-filter toggleFilter","Interview")
            );

        }

    };

</script>
@endsection
