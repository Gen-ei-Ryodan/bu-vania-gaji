// App JavaScript
// CSRF Token setup for AJAX requests
document.addEventListener('DOMContentLoaded', function() {
    // Setup CSRF token for all AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios = window.axios || {};
        window.axios.defaults = window.axios.defaults || {};
        window.axios.defaults.headers = window.axios.defaults.headers || {};
        window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
    }

    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebar && toggle && overlay) {
        const openSidebar = () => {
            sidebar.classList.add('show');
            overlay.hidden = false;
            overlay.classList.add('show');
            toggle.setAttribute('aria-expanded', 'true');
            document.body.classList.add('sidebar-open');
        };

        const closeSidebar = () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            overlay.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('sidebar-open');
        };

        toggle.addEventListener('click', () => {
            if (sidebar.classList.contains('show')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        overlay.addEventListener('click', closeSidebar);

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });

        sidebar.querySelectorAll('a.nav-link').forEach((link) => {
            link.addEventListener('click', () => closeSidebar());
        });

        const mq = window.matchMedia('(min-width: 769px)');
        const onResize = () => {
            if (mq.matches) closeSidebar();
        };
        if (mq.addEventListener) mq.addEventListener('change', onResize);
        else mq.addListener(onResize);
    }
});
