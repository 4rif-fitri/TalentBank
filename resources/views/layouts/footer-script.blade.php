<!-- JAVASCRIPT -->
<script src="{{ URL::asset('assets/internship-assets/libs/jquery-3.7.1.min.js') }}"></script>
<script src="{{ URL::asset('assets/internship-assets/libs/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('assets/internship-assets/libs/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('assets/internship-assets/libs/sweetalert2@11.js') }}"></script>
<script src="{{ URL::asset('assets/internship-assets/libs/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('assets/internship-assets/libs/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/internship-assets/libs/datatables.min.js') }}"></script>

<script>
    if (window.innerWidth > 768 && localStorage.getItem("sidebarCollapsed") === "true") {
        document.documentElement.classList.add("sidebar-collapsed-init");
    }
</script>
<script src="{{ URL::asset('assets/internship-assets/layout/newLayout.js') }}"></script>
@yield('script')
<!-- JAVASCRIPT -->
