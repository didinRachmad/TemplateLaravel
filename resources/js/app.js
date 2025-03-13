import "./bootstrap.js";
import "./modules/bootstrap-ui";
import $ from "jquery";
import select2 from "select2";
import "datatables.net-bs5";
import "datatables.net-buttons-bs5";
import Alpine from "alpinejs";
import { showToast } from "./modules/sweetalert";

// INIT GLOBAL
window.$ = $;
window.jQuery = $;
select2();
window.showToast = showToast;
window.Alpine = Alpine;
Alpine.start();

// URL API dari .env
const API_URLS = {
    items: import.meta.env.VITE_API_ITEMS_SERVICE,
};
window.API_URLS = API_URLS;

// Helper function untuk kapitalisasi string
const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

const modules = import.meta.glob("./pages/**/*.js");
// --- Semua inisialisasi yang bergantung pada DOM --- //
document.addEventListener("DOMContentLoaded", async () => {
    // 1. Fade out loading screen
    setTimeout(() => {
        $("#loading-screen").fadeOut("fast");
    }, 250);

    // 2. Dynamic module loading untuk halaman spesifik
    const page = document.body.dataset.page;
    const action = document.body.dataset.action;
    if (page) {
        const modulePath = `./pages/${page}.js`;
        if (modules[modulePath]) {
            try {
                const mod = await modules[modulePath]();
                const functionName = `init${capitalize(action)}`;
                if (
                    mod.default &&
                    typeof mod.default[functionName] === "function"
                ) {
                    mod.default[functionName]();
                } else {
                    console.error(
                        `Fungsi ${functionName} tidak ditemukan pada modul ${page}`
                    );
                }
            } catch (error) {
                console.error(
                    `Module untuk halaman "${page}" terjadi kesalahan:`,
                    error
                );
            }
        } else {
            console.error(`Module untuk halaman "${page}" tidak ditemukan`);
        }
    }

    // 4. Event delegation untuk tombol dengan konfirmasi
    $(document).on("click", async function (e) {
        const $target = $(e.target);

        if ($target.closest(".btn-delete").length) {
            e.preventDefault();
            const $form = $target.closest(".form-delete");
            const { showConfirmDialog } = await import("./modules/sweetalert");
            showConfirmDialog(
                "Apakah Anda yakin?",
                "Data yang dihapus tidak bisa dikembalikan!",
                () => $form.submit()
            );
        } else if ($target.closest(".btn-approve").length) {
            e.preventDefault();
            const $form = $target.closest(".form-approval");
            const { showConfirmDialog } = await import("./modules/sweetalert");
            showConfirmDialog(
                "Apakah Anda yakin?",
                "Harap periksa kembali sebelum melakukan approve data!",
                () => $form.submit()
            );
        } else if ($target.closest(".btn-reset-password").length) {
            e.preventDefault();
            const $form = $target.closest(".form-reset-password");
            const { showConfirmDialog } = await import("./modules/sweetalert");
            showConfirmDialog(
                "Apakah Anda yakin?",
                "Password akan direset ke data awal!",
                () => $form.submit()
            );
        } else if ($target.closest(".btn-revisi").length) {
            e.preventDefault();
            const $form = $target.closest(".form-revisi");
            const { showInputDialog } = await import("./modules/sweetalert");
            showInputDialog(
                "Apakah Anda yakin?",
                "Data item akan dikembalikan untuk proses revisi, silakan tambahkan keterangan!",
                (keterangan) => {
                    const $hiddenInput = $("<input>", {
                        type: "hidden",
                        name: "keterangan",
                        value: keterangan,
                    });
                    $form.append($hiddenInput);
                    $form.submit();
                }
            );
        } else if ($target.closest(".btn-reject").length) {
            e.preventDefault();
            const $form = $target.closest(".form-reject");
            const { showInputDialog } = await import("./modules/sweetalert");
            showInputDialog(
                "Apakah Anda yakin?",
                "Data item akan direject! Silakan tambahkan alasan reject.",
                (keterangan) => {
                    const $hiddenInput = $("<input>", {
                        type: "hidden",
                        name: "keterangan",
                        value: keterangan,
                    });
                    $form.append($hiddenInput);
                    $form.submit();
                }
            );
        }
    });

    // Auto print jika URL mengandung "/print"
    if (window.location.pathname.includes("/print")) {
        window.print();
        window.onafterprint = () => window.close();
    }

    // INIT SCAN QR
    let scanQRModule = null;
    const scanModalEl = document.getElementById("scanModal");
    if (scanModalEl) {
        scanModalEl.addEventListener("shown.bs.modal", async () => {
            try {
                if (!scanQRModule) {
                    scanQRModule = await import("./modules/scanQR.js");
                }
                scanQRModule.startQRScan();
            } catch (error) {
                console.error("Gagal mengimport scanQR.js:", error);
            }
        });
    }

    // INIT FILEPOND
    let filePondModule = null;
    const fileInput = document.getElementById("upload-images");
    if (fileInput) {
        if (filePondModule) {
            // Jika modul sudah pernah diimport, langsung gunakan fungsi initFilePond dari cache
            filePondModule.initFilePond();
        } else {
            // Lakukan dynamic import dan simpan hasilnya ke dalam cache
            import("./modules/filepond")
                .then(({ initFilePond }) => {
                    filePondModule = { initFilePond };
                    initFilePond();
                })
                .catch((error) => {
                    console.error("Gagal mengimport modul FilePond:", error);
                });
        }
    }

    // FUNGSI MENAMPILKAN RIWAYAT LOG
    const historyModalEl = document.getElementById("historyModal");
    if (historyModalEl) {
        import("./modules/historyLog.js")
            .then(({ initHistoryLog }) => {
                initHistoryLog();
            })
            .catch((error) => {
                console.error("Gagal mengimport modul historyLog:", error);
            });
    }
});
