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

