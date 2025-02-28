export default {
    initIndex() {
        console.log('Halaman Profile Index berhasil dimuat!');
    },
    initShow() {
        console.log('Halaman Profile Show berhasil dimuat!');
    },
    initCreate() {
        console.log('Halaman Profile Create berhasil dimuat!');

    },
    initEdit() {
        console.log('Halaman Profile Edit berhasil dimuat!');
        $(document).ready(function () {
            $('#produksi_id').select2({
                placeholder: "Pilih Produksi",
                allowClear: true
            });
        });
    }
};
