document.addEventListener("DOMContentLoaded", function () {
    const btnMenu = document.getElementById("btnMenu");
    const sidebar = document.querySelector(".sidebar");
    const navbar = document.querySelector(".navbar");
    const content = document.querySelector(".content");
    const overlay = document.getElementById("sidebarOverlay");
    const pantallaMovil = window.matchMedia("(max-width: 991px)");

    if (!btnMenu || !sidebar) {
        return;
    }

    function cerrarSidebarMovil() {
        sidebar.classList.remove("abierto");
        if (overlay) {
            overlay.classList.remove("activo");
        }
        document.body.classList.remove("sidebar-abierto");
    }

    btnMenu.addEventListener("click", function () {
        if (pantallaMovil.matches) {
            const abierto = sidebar.classList.toggle("abierto");
            if (overlay) {
                overlay.classList.toggle("activo", abierto);
            }
            document.body.classList.toggle("sidebar-abierto", abierto);
            return;
        }

        sidebar.classList.toggle("cerrado");
        if (navbar) {
            navbar.classList.toggle("expandido");
        }
        if (content) {
            content.classList.toggle("expandido");
        }
    });

    if (overlay) {
        overlay.addEventListener("click", cerrarSidebarMovil);
    }

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            cerrarSidebarMovil();
        }
    });

    pantallaMovil.addEventListener("change", function () {
        cerrarSidebarMovil();
        sidebar.classList.remove("cerrado");
        if (navbar) {
            navbar.classList.remove("expandido");
        }
        if (content) {
            content.classList.remove("expandido");
        }
    });
});
