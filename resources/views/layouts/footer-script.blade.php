<!-- JAVASCRIPT -->
<script>
    if (window.innerWidth > 768 && localStorage.getItem("sidebarCollapsed") === "true") {
        document.documentElement.classList.add("sidebar-collapsed-init");
    }
</script>
@yield('script')
<!-- JAVASCRIPT -->
