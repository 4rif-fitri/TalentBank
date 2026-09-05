$(document).ready(function () {
    const MOBILE_BREAKPOINT = 768;

    function loadSidebarMemory() {
        const collapsed = localStorage.getItem("sidebarCollapsed");

        if (window.innerWidth > MOBILE_BREAKPOINT && collapsed === "true") {
            $("body").addClass("sidebar-collapsed");
        }

        $("html").removeClass("sidebar-collapsed-init");
    }
    loadSidebarMemory();

    // SIDEBAR TOGGLE
    $("#menuToggle").on("click", function (e) {
        e.stopPropagation();

        /* MOBILE */
        if (window.innerWidth <= MOBILE_BREAKPOINT) {
            $("body").toggleClass("sidebar-open");
            return;
        }

        /* DESKTOP */
        $("body").toggleClass("sidebar-collapsed");
        const collapsed = $("body").hasClass("sidebar-collapsed");

        localStorage.setItem("sidebarCollapsed", collapsed);

        if (collapsed) {
            $(".sidebar-item").removeClass("open");
        }
    });

    // SUBMENU
    $(".submenu-toggle").on("click", function (e) {
        e.preventDefault();

        if (window.innerWidth > MOBILE_BREAKPOINT && $("body").hasClass("sidebar-collapsed")) {
            return;
        }

        const parent = $(this).closest(".sidebar-item");
        $(".sidebar-item.has-submenu").not(parent).removeClass("open");
        parent.toggleClass("open");
    });

    // MOBILE OVERLAY
    $(".sidebar-overlay").on("click", function () {
        $("body").removeClass("sidebar-open");
    });


    // PROFILE DROPDOWN
    $("#profileBtn").on("click", function (e) {
        e.stopPropagation();
        $("#profileDropdown").toggleClass("show");
    });

    // CLICK OUTSIDE
    $(document).on("click", function (e) {
        if (!$(e.target).closest("#profileBtn").length &&
            !$(e.target).closest("#profileDropdown").length) {

            $("#profileDropdown").removeClass("show");
        }
    });

    // CLOSE SIDEBAR AFTER MOBILE NAVIGATION
    $(".submenu-link, .sidebar-link").not(".submenu-toggle").on("click", function () {
        if (window.innerWidth <= MOBILE_BREAKPOINT) {
            $("body").removeClass("sidebar-open");
        }
    });

    // RESIZE
    $(window).on("resize", function () {
        /* Desktop */
        if (window.innerWidth > MOBILE_BREAKPOINT) {
            $("body").removeClass("sidebar-open");

            const collapsed = localStorage.getItem("sidebarCollapsed");
            if (collapsed === "true") {
                $("body").addClass("sidebar-collapsed");
                $(".sidebar-item").removeClass("open");
            } else {
                $("body").removeClass("sidebar-collapsed");
            }
        }

        /* Mobile */
        else {
            $("body").removeClass("sidebar-collapsed");
        }
    });
});
