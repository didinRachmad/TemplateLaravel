export default {
    initIndex() {
        console.log('Halaman Approval Routes Index berhasil dimuat!');
        let table;
        table = $("#datatables").DataTable({
            dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 mb-3 mb-md-0 d-flex justify-content-center align-items-center'><'col-sm-12 col-md-3 text-right'f>>" +
                "<'row py-2'<'col-sm-12 table-responsive'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            paging: true,
            responsive: true,
            pageLength: 10, // Jumlah baris per halaman secara default
            lengthMenu: [
                [10, 50, -1],
                [10, 50, "Semua"],
            ],
            order: [],
            columnDefs: [
                {
                    targets: 0, // Menargetkan kolom pertama
                    className: 'text-center', // Menambahkan kelas text-center untuk meratakan teks ke tengah
                },
            ],
            info: true,
            language: {
                sEmptyTable: "Tidak ada data yang tersedia di tabel",
                sInfo: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                sInfoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
                sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                sInfoPostFix: "",
                sInfoThousands: ".",
                sLengthMenu: "Tampilkan _MENU_ entri",
                sLoadingRecords: "Memuat...",
                sProcessing: "Sedang memproses...",
                sSearch: "Cari:",
                sZeroRecords: "Tidak ditemukan data yang cocok",
                // oPaginate: {
                //     sFirst: "Pertama",
                //     sLast: "Terakhir",
                //     sNext: "Selanjutnya",
                //     sPrevious: "Sebelumnya",
                // },
                oAria: {
                    sSortAscending: ": aktifkan untuk mengurutkan kolom secara menaik",
                    sSortDescending: ": aktifkan untuk mengurutkan kolom secara menurun",
                },
            },
        });

        table
            .on("order.dt search.dt", function () {
                table
                    .column(0, {
                        search: "applied",
                        order: "applied",
                    })
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
            })
            .draw();
    },
    initShow() {
        console.log('Halaman Approval Routes Show berhasil dimuat!');
    },
    initCreate() {
        console.log('Halaman Approval Routes Create berhasil dimuat!');

    },
    initEdit() {
        console.log('Halaman Approval Routes Edit berhasil dimuat!');

    }
};
