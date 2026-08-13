<!-- JAVASCRIPT -->
<script src="{{URL::asset('assets/internship-assets/libs/jquery-4.0.0.min.js')}}"></script>
<script src="{{URL::asset('assets/internship-assets/libs/jquery-ui.min.js')}}"></script>
<script src="{{URL::asset('assets/internship-assets/libs/bootstrap.min.js')}}"></script>
<script src="{{URL::asset('assets/internship-assets/libs/sweetalert2@11.js')}}"></script>
<script src="{{URL::asset('assets/internship-assets/layout/newLayout.js')}}"></script>
<script>
    if (window.innerWidth > 768 && localStorage.getItem("sidebarCollapsed") === "true") {
        document.documentElement.classList.add("sidebar-collapsed-init");
    }
</script>
@yield('script')
<!-- JAVASCRIPT -->
