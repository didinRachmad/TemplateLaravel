// resources/js/modules/sweetalert.js
import Swal from 'sweetalert2/dist/sweetalert2.all.js';

/**
 * Menampilkan notifikasi toast.
 * @param {string} message - Pesan yang ditampilkan.
 * @param {string} type - Tipe notifikasi (contoh: 'success', 'error').
 */
export function showToast(message, type) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
}

/**
 * Menampilkan alert biasa.
 * @param {string} title - Judul alert.
 * @param {string} text - Isi pesan.
 * @param {string} type - Tipe alert (contoh: 'success', 'error').
 */
export function showAlert(title, text, type) {
    Swal.fire({
        title: title,
        text: text,
        icon: type,
    });
}

/**
 * Menampilkan dialog konfirmasi.
 * @param {string} title - Judul dialog.
 * @param {string} text - Pesan dialog.
 * @param {function} confirmCallback - Fungsi yang dipanggil ketika konfirmasi.
 */
export function showConfirmDialog(title, text, confirmCallback) {
    Swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, lanjutkan!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed && typeof confirmCallback === 'function') {
            confirmCallback();
        }
    });
}

/**
 * Menampilkan dialog input.
 * @param {string} title - Judul dialog.
 * @param {string} text - Pesan tambahan.
 * @param {function} confirmCallback - Fungsi yang menerima input (keterangan).
 */
export function showInputDialog(title, text, confirmCallback) {
    Swal.fire({
        title: title,
        html: `<p>${text}</p>
           <textarea id="inputReason" class="swal2-textarea w-100 m-0" placeholder="Masukkan keterangan..."></textarea>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Kirim',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const reason = Swal.getPopup().querySelector('#inputReason').value;
            if (!reason) {
                Swal.showValidationMessage('Harap masukkan keterangan!');
            }
            return { reason: reason };
        }
    }).then((result) => {
        if (result.isConfirmed && typeof confirmCallback === 'function') {
            confirmCallback(result.value.reason);
        }
    });
}
