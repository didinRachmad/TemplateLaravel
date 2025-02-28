// resources/js/scanQR.js

import { Html5QrcodeScanner } from "html5-qrcode";

export function startQRScan() {
    // Mengambil segment pertama dari URL, misalnya jika URL adalah "/items", maka basePath = "items"
    const basePath = window.location.pathname.split('/')[1];

    const onScanSuccess = (decodedText, decodedResult) => {
        console.log("QR Code berhasil dipindai:", decodedText);

        // Lakukan pengecekan ke backend menggunakan AJAX dengan basePath dinamis
        fetch(`/${basePath}/checkQR/${decodedText}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    // Jika data ada, arahkan ke halaman show dengan id item
                    window.location.href = `/${basePath}/show/${decodedText}`;
                } else {
                    // Jika data tidak ditemukan, tampilkan pesan error
                    showToast("Data tidak ditemukan", "error");
                }
            })
            .catch(error => {
                console.error("Terjadi kesalahan saat memeriksa data:", error);
            });
    };

    const onScanError = (errorMessage) => {
        console.warn("Error saat scan:", errorMessage);
    };

    // Inisialisasi scanner pada elemen dengan id 'reader'
    const html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess, onScanError);
}


