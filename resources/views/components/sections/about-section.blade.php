<section id="about">
    <h3 class="fw-bold text-sm-center text-lg-start">About</h3>
    <hr>

    <p id="aboutText"></p>

    @if (array_intersect(session('roles') ?? [], ['Student']))
    <div class="icon-container">
        <button type="button" class="icon btn btn-secondary" id="btnEditAbout">
            <i class="fa-solid fa-pencil"></i>
        </button>
    </div>
    @endif
</section>

@section('childScript')

<script>
    $(document).ready(function (){
        $(document).on("profile:aboutUpdated", function(event,data){
            console.log(event);
            console.log(data);
        })
    })
</script>
@endsection
