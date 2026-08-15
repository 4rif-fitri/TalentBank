<section id="education" class="d-flex flex-column gap-1">
    <h3 class="fw-bold text-sm-center text-lg-start">Education</h3>
    <div class="icon-container">
        <a href="{{ route('profile.education') }}" class="btn btn-secondary icon">
            <i class="fa-solid fa-pencil"></i>
        </a>
    </div>
    <hr>

    <article class="gap-2">
        <div class="d-flex gap-1 align-items-center">
            <img src="{{ asset('../assets/internship-assets/images/7.jpg') }}" alt="UTeM"
                style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
            <p class="h5 fw-bold">
                Universiti Teknikal Malaysia Melaka
                (UTeM)
            </p>
        </div>


        <div class="col d-flex flex-column mx-1 gap-1">
            <p>Bachelor of Computer Science (Software Engineering)</p>
            <p>Oct 2026 - Oct 2027</p>
            <p>Grade: 4.00</p>
            <p>Currently pursuing a degree in software engineering with emphasis on system</p>

            <div class="skills d-flex flex-wrap align-items-center">
                Skills:
                <div class="badge text-bg-secondary m-1">Software Engineering</div>
                <div class="badge text-bg-secondary m-1">System Analysis</div>
                <div class="badge text-bg-secondary m-1">Database Design</div>
                <div class="badge text-bg-secondary m-1">Web Development</div>
            </div>

            <div class="images d-flex flex-wrap">
                <div class="image rounded-1 m-1"
                    style="background-image:url('../../assets/internship-assets/images/7.jpg'); cursor:pointer;"
                    data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-slide-index="0">
                </div>
                <div class="image rounded-1 m-1"
                    style=" background-image:url('../assets/internship-assets/images/7.jpg');cursor:pointer;"
                    data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-slide-index="1">
                </div>
                <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center"
                    style="background-image:url('../assets/internship-assets/images/7.jpg'); filter:brightness(.5);cursor:pointer;"
                    data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-slide-index="2">
                    <h4 class="text-white m-0">
                        +5
                    </h4>
                </div>
            </div>
        </div>
    </article>
    <hr>

    <a href="./education.html" class="d-flex justify-content-center align-items-center">
        <span>Show All</span>
    </a>
</section>

@push('scripts')
@endpush
