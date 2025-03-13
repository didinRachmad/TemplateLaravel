// Impor Bootstrap sebagai namespace
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;

// Inisialisasi Tooltip pada semua halaman
document.addEventListener("DOMContentLoaded", () => {
    const tooltipTriggerList = Array.from(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.forEach((tooltipTriggerEl) => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
