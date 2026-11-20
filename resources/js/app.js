import "./bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    if (window.jQuery) {
        // 1. Setup CSRF token for all jQuery AJAX calls
        const token = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
        if (token) {
            window.jQuery.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": token,
                },
            });
        }

        // 2. Initialize Bootstrap tooltips
        window.jQuery('[data-toggle="tooltip"]').tooltip();
    }
});
