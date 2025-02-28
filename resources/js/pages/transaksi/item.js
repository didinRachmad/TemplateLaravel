// resources/js/item.js
export default {
    initIndex() {
        console.log('Halaman Item Index berhasil dimuat!');
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
        console.log('Halaman Item Show berhasil dimuat!');
    },
    initCreate() {
        console.log('Halaman Item Create berhasil dimuat!');
        $('#kode_item').select2({
            theme: 'bootstrap-5',
            ajax: {
                url: API_URLS.items,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term,
                        search: {
                            value: params.term
                        }
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                id: item.bkode, // Gunakan bkode sebagai id
                                text: item
                                    .bnama, // Gunakan bnama sebagai teks yang ditampilkan
                                bnama: item.bnama,
                                bsatuandefault: item.bsatuandefault
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Pilih Item',
            minimumInputLength: 1,
            templateResult: function (item) {
                if (item.loading) {
                    return item.text;
                }
                var $container = $(
                    "<div class='select2-result-item clearfix'>" +
                    "<div class='select2-result-item__meta'>" +
                    "<div class='select2-result-item__title'></div>" +
                    "<div class='select2-result-item__subtitle'></div>" +
                    "</div>" +
                    "</div>"
                );
                $container.find(".select2-result-item__title").text(item.id + ' - ' +
                    item.text);
                $container.find(".select2-result-item__subtitle").text(item
                    .bsatuandefault);
                return $container;
            },
            templateSelection: function (item) {
                return item.id;
            }
        });
        // Event handler untuk memperbarui input field read-only
        $('#kode_item').on('select2:select', function (e) {
            var data = e.params.data;
            $('#nama_item').val(data.text); // Perbarui nilai input read-only dengan bnama
            $('#satuan').val(data.bsatuandefault); // Perbarui nilai input read-only dengan bsatuandefault
        });

        // $('#kode_lokasi').select2({
        //     theme: 'bootstrap-5',
        //     ajax: {
        //         url: 'http://10.252.2.166/api-vms/public/api/master/location/listapi',
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             return {
        //                 term: params.term,
        //                 search: {
        //                     value: params.term
        //                 }
        //             };
        //         },
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data.data, function (location) {
        //                     return {
        //                         id: location.lkode, // Gunakan lkode sebagai id
        //                         text: location
        //                             .lnama, // Gunakan lnama sebagai teks yang ditampilkan
        //                         lnama: location.lnama,
        //                         lkodetransaksi: location.lkodetransaksi
        //                     };
        //                 })
        //             };
        //         },
        //         cache: true
        //     },
        //     placeholder: 'Pilih Lokasi',
        //     minimumInputLength: 1,
        //     templateResult: function (location) {
        //         if (location.loading) {
        //             return location.text;
        //         }
        //         var $container = $(
        //             "<div class='select2-result-location clearfix'>" +
        //             "<div class='select2-result-location__meta'>" +
        //             "<div class='select2-result-location__title'></div>" +
        //             "<div class='select2-result-location__subtitle'></div>" +
        //             "</div>" +
        //             "</div>"
        //         );
        //         $container.find(".select2-result-location__title").text(location.id +
        //             ' - ' + location.text);
        //         $container.find(".select2-result-location__subtitle").text(
        //             'Kode Transaksi: ' + location.lkodetransaksi);
        //         return $container;
        //     },
        //     templateSelection: function (location) {
        //         return location.id;
        //     }
        // });
        // // Event handler untuk memperbarui input field read-only
        // $('#kode_lokasi').on('select2:select', function (e) {
        //     var data = e.params.data;
        //     $('#nama_lokasi').val(data.text); // Perbarui nilai input read-only dengan bnama
        // });
    },
    initEdit() {
        console.log('Halaman Item Edit berhasil dimuat!');
        $('#kode_item').select2({
            theme: 'bootstrap-5',
            ajax: {
                url: API_URLS.items,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term,
                        search: {
                            value: params.term
                        }
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                id: item.bkode, // Gunakan bkode sebagai id
                                text: item
                                    .bnama, // Gunakan bnama sebagai teks yang ditampilkan
                                bnama: item.bnama,
                                bsatuandefault: item.bsatuandefault
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Pilih Item',
            minimumInputLength: 1,
            templateResult: function (item) {
                if (item.loading) {
                    return item.text;
                }
                var $container = $(
                    "<div class='select2-result-item clearfix'>" +
                    "<div class='select2-result-item__meta'>" +
                    "<div class='select2-result-item__title'></div>" +
                    "<div class='select2-result-item__subtitle'></div>" +
                    "</div>" +
                    "</div>"
                );
                $container.find(".select2-result-item__title").text(item.id + ' - ' +
                    item.text);
                $container.find(".select2-result-item__subtitle").text(item
                    .bsatuandefault);
                return $container;
            },
            templateSelection: function (item) {
                return item.id;
            }
        });
        // Event handler untuk memperbarui input field read-only
        $('#kode_item').on('select2:select', function (e) {
            var data = e.params.data;
            $('#nama_item').val(data.text); // Perbarui nilai input read-only dengan bnama
            $('#satuan').val(data.bsatuandefault); // Perbarui nilai input read-only dengan bsatuandefault
        });

        // $('#kode_lokasi').select2({
        //     theme: 'bootstrap-5',
        //     ajax: {
        //         url: 'http://10.252.2.166/api-vms/public/api/master/location/listapi',
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             return {
        //                 term: params.term,
        //                 search: {
        //                     value: params.term
        //                 }
        //             };
        //         },
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data.data, function (location) {
        //                     return {
        //                         id: location.lkode, // Gunakan lkode sebagai id
        //                         text: location
        //                             .lnama, // Gunakan lnama sebagai teks yang ditampilkan
        //                         lnama: location.lnama,
        //                         lkodetransaksi: location.lkodetransaksi
        //                     };
        //                 })
        //             };
        //         },
        //         cache: true
        //     },
        //     placeholder: 'Pilih Lokasi',
        //     minimumInputLength: 1,
        //     templateResult: function (location) {
        //         if (location.loading) {
        //             return location.text;
        //         }
        //         var $container = $(
        //             "<div class='select2-result-location clearfix'>" +
        //             "<div class='select2-result-location__meta'>" +
        //             "<div class='select2-result-location__title'></div>" +
        //             "<div class='select2-result-location__subtitle'></div>" +
        //             "</div>" +
        //             "</div>"
        //         );
        //         $container.find(".select2-result-location__title").text(location.id +
        //             ' - ' + location.text);
        //         $container.find(".select2-result-location__subtitle").text(
        //             'Kode Transaksi: ' + location.lkodetransaksi);
        //         return $container;
        //     },
        //     templateSelection: function (location) {
        //         return location.id;
        //     }
        // });
        // // Event handler untuk memperbarui input field read-only
        // $('#kode_lokasi').on('select2:select', function (e) {
        //     var data = e.params.data;
        //     $('#nama_lokasi').val(data.text); // Perbarui nilai input read-only dengan bnama
        // });
    }
};
