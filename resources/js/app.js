import $ from 'jquery';
import select2 from 'select2';
import * as bootstrap from 'bootstrap';
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import { showToast, showAlert, showConfirmDialog, showInputDialog } from './modules/sweetalert.js';
import { Fancybox } from '@fancyapps/ui';
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
import FilePondPluginImageOverlay from 'filepond-plugin-image-overlay';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import Alpine from 'alpinejs';

// --- Inisialisasi plugin yang tidak bergantung pada DOM --- //
select2();
Fancybox.bind('[data-fancybox]');
// -----------------

window.$ = $;
window.jQuery = $;
window.bootstrap = bootstrap;
window.showToast = showToast;
window.showAlert = showAlert;
window.showConfirmDialog = showConfirmDialog;
window.showInputDialog = showInputDialog;
window.Alpine = Alpine;
Alpine.start();

// URL API dari .env
const API_URLS = {
    items: import.meta.env.VITE_API_ITEMS_SERVICE,
};
window.API_URLS = API_URLS;

const modules = import.meta.glob('./pages/**/*.js');

// Helper function untuk kapitalisasi string
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// --- Semua inisialisasi yang bergantung pada DOM --- //
$(document).ready(async function () {

    // 1. Inisialisasi tooltip Bootstrap menggunakan jQuery
    $('[data-bs-toggle="tooltip"]').each(function () {
        new bootstrap.Tooltip(this);
    });

    // 2. Fade out loading screen dengan jQuery (dengan delay 200ms)
    setTimeout(function () {
        $("#loading-screen").fadeOut("slow");
    }, 200);

    // 3. Dynamic module loading untuk halaman spesifik
    let page = $('body').data('page');    // Contoh: "master/produksi"
    let action = $('body').data('action');  // Contoh: "index", "create", "edit"
    if (page) {
        const modulePath = `./pages/${page}.js`;
        if (modules[modulePath]) {
            try {
                const mod = await modules[modulePath]();
                const functionName = `init${capitalize(action)}`;
                if (mod.default && typeof mod.default[functionName] === 'function') {
                    mod.default[functionName]();
                } else {
                    console.error(`Fungsi ${functionName} tidak ditemukan pada modul ${page}`);
                }
            } catch (error) {
                console.error(`Module untuk halaman "${page}" terjadi kesalahan:`, error);
            }
        } else {
            console.error(`Module untuk halaman "${page}" tidak ditemukan`);
        }
    }

    // 4. Show Alert
    $(document).on('click', function (e) {
        const $target = $(e.target);

        if ($target.closest('.btn-delete').length) {
            e.preventDefault();
            const $form = $target.closest('.form-delete');
            showConfirmDialog(
                "Apakah Anda yakin?",
                "Data yang dihapus tidak bisa dikembalikan!",
                () => $form.submit()
            );
        } else if ($target.closest('.btn-approve').length) {
            e.preventDefault();
            const $form = $target.closest('.form-approval');
            showConfirmDialog(
                "Apakah Anda yakin?",
                "Harap periksa kembali sebelum melakukan approve data!",
                () => $form.submit()
            );
        } else if ($target.closest('.btn-reset-password').length) {
            e.preventDefault();
            const $form = $target.closest('.form-reset-password');
            showConfirmDialog(
                "Apakah Anda yakin?",
                "Password akan direset ke data awal!",
                () => $form.submit()
            );
        } else if ($target.closest('.btn-revisi').length) {
            e.preventDefault();
            const $form = $target.closest('.form-revisi');
            showInputDialog(
                "Apakah Anda yakin?",
                "Data item akan dikembalikan untuk proses revisi, silakan tambahkan keterangan!",
                (keterangan) => {
                    const $hiddenInput = $('<input>', {
                        type: 'hidden',
                        name: 'keterangan',
                        value: keterangan
                    });
                    $form.append($hiddenInput);
                    $form.submit();
                }
            );
        } else if ($target.closest('.btn-reject').length) {
            e.preventDefault();
            const $form = $target.closest('.form-reject');
            showInputDialog(
                "Apakah Anda yakin?",
                "Data item akan direject! Silakan tambahkan alasan reject.",
                (keterangan) => {
                    const $hiddenInput = $('<input>', {
                        type: 'hidden',
                        name: 'keterangan',
                        value: keterangan
                    });
                    $form.append($hiddenInput);
                    $form.submit();
                }
            );
        }
    });

    // 5. Jika halaman mengandung "/print" pada URL, cetak halaman dan tutup setelah cetak
    if (window.location.pathname.includes("/print")) {
        window.print();
        window.onafterprint = function () {
            window.close();
        };
    }

    // 6. Inisialisasi FilePond untuk input upload gambar
    const fileInput = $("#upload-images")[0];
    if (fileInput) {
        // Daftarkan plugin-file yang diperlukan
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFilePoster,
            FilePondPluginImageOverlay,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );

        // Jika window.storedImagesPaths tidak ada, set sebagai array kosong
        let storedImagesPaths = (typeof window.storedImagesPaths !== "undefined" && window.storedImagesPaths) ? window.storedImagesPaths : [];

        // Jika masih berupa string, lakukan parsing
        if (typeof storedImagesPaths === "string") {
            try {
                storedImagesPaths = JSON.parse(storedImagesPaths);
            } catch (e) {
                console.error("Gagal meng-parse storedImagesPaths:", e);
                storedImagesPaths = [];
            }
        }

        // Mapping data yang tersimpan menjadi format FilePond (jika ada)
        const storedImages = storedImagesPaths.map(path => ({
            source: path,
            options: {
                type: 'local',
                file: {
                    name: path.split('/').pop(),
                },
                metadata: {
                    poster: window.location.origin + '/storage/' + path,
                }
            }
        }));

        const pond = FilePond.create(fileInput, {
            allowMultiple: true,
            maxFiles: 5,
            acceptedFileTypes: ["image/*"],
            maxFileSize: '2MB', // Ubah ke '8MB' jika diperlukan
            storeAsFile: true,
            files: storedImages,
            server: {
                load: (source, load, error, progress, abort, headers) => {
                    const url = window.location.origin + '/storage/' + source;
                    fetch(url)
                        .then(res => res.blob())
                        .then(load)
                        .catch(() => error('Gagal memuat file'));
                },
                // process: '/upload',
                // revert: '/revert'
            }
        });
        window.pondInstance = pond;

        // Fungsi untuk mengambil file dari server dan membuat File object
        async function reinitializeLocalFile(path) {
            const url = window.location.origin + '/storage/' + path;
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Gagal mengambil file: ' + response.statusText);
            }
            const blob = await response.blob();
            const fileName = path.split('/').pop();
            return new File([blob], fileName, { type: blob.type });
        }

        // Re-inisialisasi file lokal agar menjadi File object dan aktifkan overlay
        (async function () {
            const localFiles = pond.getFiles().filter(file => file.origin === FilePond.FileOrigin.LOCAL);
            for (const fileItem of localFiles) {
                try {
                    const newFile = await reinitializeLocalFile(fileItem.source);
                    pond.removeFile(fileItem.id);
                    await pond.addFile(newFile);
                } catch (e) {
                    console.error("Error reinitializing file:", fileItem.source, e);
                }
            }
        })();

        // // Tambahkan validasi saat submit form
        // const formElement = $("form").first()[0];
        // if (formElement) {
        //     $(formElement).on("submit", function (e) {

        //         // Dapatkan file yang tidak valid
        //         const invalidFiles = pond.getFiles().filter(file => file.status === FilePond.FileStatus.INVALID);
        //         console.log("Invalid Files:", invalidFiles);

        //         // Jika ada file invalid, tampilkan toast error
        //         if (invalidFiles.length > 0) {
        //             // Hentikan submit untuk debugging
        //             e.preventDefault();
        //             showToast("Terdapat file yang tidak sesuai validasi", "error");
        //         } else {
        //             // Jika tidak ada file invalid, submit form secara manual
        //             formElement.submit();
        //         }
        //     });
        // }
    }

    // 7. Scan QR
    const scanModalEl = $('#scanModal');
    if (scanModalEl.length) {
        scanModalEl.on('shown.bs.modal', function () {
            import('./modules/scanQR')
                .then(module => {
                    console.log("Modul scanQR.js berhasil diimport.");
                    module.startQRScan();
                })
                .catch(error => {
                    console.error("Gagal mengimport scanQR.js:", error);
                });
        });
    }
});
