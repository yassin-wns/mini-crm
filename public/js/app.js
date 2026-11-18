/**
 * Mini CRM — Minimal Vanilla JavaScript
 * Lightweight, accessible interactions for mobile navigation & password visibility.
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const mobileToggleBtn = document.getElementById('mobile-sidebar-toggle');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('open');
        if (backdrop) backdrop.classList.add('active');
        document.body.classList.add('sidebar-locked');
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('open');
        if (backdrop) backdrop.classList.remove('active');
        document.body.classList.remove('sidebar-locked');
    }

    if (mobileToggleBtn) {
        mobileToggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            openSidebar();
        });
    }

    if (sidebarCloseBtn) {
        sidebarCloseBtn.addEventListener('click', function (e) {
            e.preventDefault();
            closeSidebar();
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', closeSidebar);
    }

    // 2. Password Visibility Toggle
    const togglePasswordBtn = document.getElementById('toggle-password-btn');
    const passwordInput = document.getElementById('password');

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            const eyeOpen = togglePasswordBtn.querySelector('.icon-eye');
            const eyeClosed = togglePasswordBtn.querySelector('.icon-eye-closed');

            if (eyeOpen && eyeClosed) {
                if (isPassword) {
                    eyeOpen.style.display = 'none';
                    eyeClosed.style.display = 'inline-block';
                    togglePasswordBtn.setAttribute('aria-label', 'Hide password');
                } else {
                    eyeOpen.style.display = 'inline-block';
                    eyeClosed.style.display = 'none';
                    togglePasswordBtn.setAttribute('aria-label', 'Show password');
                }
            }
        });
    }

    // 3. Alert Dismissal
    document.querySelectorAll('.alert-close').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const alert = this.closest('.alert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-6px)';
                setTimeout(function () {
                    alert.remove();
                }, 200);
            }
        });
    });
});
