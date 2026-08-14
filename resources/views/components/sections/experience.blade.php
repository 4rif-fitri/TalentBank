<section id="experience" class="d-flex flex-column gap-1">
    <h3 class="fw-bold text-sm-center text-lg-start">Experience</h3>
    <div class="icon-container">
        <a href="{{ route('profile.experience') }}" class="btn btn-secondary icon">
            <i class="fa-solid fa-pencil"></i>
        </a>
    </div>
    <hr>

    <article class="m-1">
        <div class="d-flex gap-1 align-items-center">
            <img src="{{ asset('../assets/internship-assets/images/7.jpg') }}" alt="UTeM"
                style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
            <p class="h5 fw-bold">Web Developer Intern</p>
        </div>

        <div class="col d-flex flex-column mx-1">
            <p>Wahdah Technology - Internship</p>
            <p>Jul 2026 - Present</p>
            <p>Durian Tunggal, Melaka, Malaysia</p>
            <p>Contributed to Laravel-based web applications</p>

            <div class="skills d-flex flex-wrap align-items-center">
                Skills:
                <div class="badge text-bg-secondary m-1">Software Engineering</div>
                <div class="badge text-bg-secondary m-1">System Analysis</div>
                <div class="badge text-bg-secondary m-1">Database Design</div>
                <div class="badge text-bg-secondary m-1">Web Development</div>
            </div>

            <div class="images d-flex flex-wrap">
                <div class="image rounded-1 m-1"
                    style="background-image:url('../assets/internship-assets/images/7.jpg');"></div>
                <div class="image rounded-1 m-1"
                    style="background-image:url('../assets/internship-assets/images/7.jpg');"></div>
                <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center"
                    style="background-image:url('../assets/internship-assets/images/7.jpg'); filter:brightness(.5);">
                    <h4 class="text-white m-0">+5</h4>
                </div>
            </div>

        </div>

    </article>

    <hr>

    <a href="./experience.html" class="d-flex justify-content-center align-items-center">Show All</a>
</section>

@push('scripts')
    <script></script>
@endpush
