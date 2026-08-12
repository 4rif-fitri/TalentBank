<!-- App css -->
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<script>
    if (window.innerWidth > 768 && localStorage.getItem("sidebarCollapsed") === "true") {
        document.documentElement.classList.add("sidebar-collapsed-init");
    }
</script>
<link rel="stylesheet" href="{{ asset('assets/internship-assets/layout/newLayout.css') }}">
<!-- App css -->
@yield('css')
