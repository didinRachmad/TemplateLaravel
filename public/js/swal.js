function showAlert(title, text, icon = 'info') {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonText: 'OK'
    });
}

// Fungsi showToast dan showConfirmDialog tetap seperti sebelumnya
function showToast(message, type = 'success') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        // timer: 4000,
        timerProgressBar: true
    });
}

function showConfirmDialog(title, text, confirmCallback) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, lanjutkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed && typeof confirmCallback === 'function') {
            confirmCallback();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('.form-delete');

            showConfirmDialog("Apakah Anda yakin?", "Data yang dihapus tidak bisa dikembalikan!", function () {
                form.submit();
            });
        });
    });
});

// Membuat fungsi-fungsi ini dapat diakses dari file lain
window.showAlert = showAlert;
window.showToast = showToast;
window.showConfirmDialog = showConfirmDialog;

