@props(['title'])

    <section class="page-header">
        <div class="page-heading">
            <h1>{{ $title }}</h1>
        </div>
        <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
            <i class="fa-solid fa-filter"></i>
            {{ $title }}
        </button>
    </section>
