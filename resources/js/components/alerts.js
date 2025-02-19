// alerts.js - file terpisah untuk fungsi alert/confirmation
export function showAlert(title, text, icon = 'info') {
    Swal.fire({
        title,
        text,
        icon,
        confirmButtonText: 'OK',
    });
}

export function showToast(message, type = 'success') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
    });
}

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
