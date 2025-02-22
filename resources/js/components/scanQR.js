import { Html5QrcodeScanner } from "html5-qrcode";

document.addEventListener("DOMContentLoaded", () => {
    // Fungsi yang dijalankan saat QR Code berhasil terbaca
    const onScanSuccess = (decodedText, decodedResult) => {
        // Menghentikan scanner agar tidak terus berjalan
        html5QrcodeScanner.clear().then(() => {
            // Mengarahkan ke route find dengan parameter kode_item
            window.location.href = `${window.location.origin}/items/find?kode_item=${encodeURIComponent(decodedText)}`;
        }).catch(error => {
            console.error("Gagal menghentikan QR Code scanner.", error);
        });
    };

    // Fungsi untuk menangani error (opsional)
    const onScanError = (errorMessage) => {
        console.warn(errorMessage);
    };

    // Inisialisasi scanner dengan konfigurasi
    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 }
    );
    html5QrcodeScanner.render(onScanSuccess, onScanError);
});
