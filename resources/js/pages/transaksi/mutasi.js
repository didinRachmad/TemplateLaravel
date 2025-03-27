// resources/js/item.js
export default {
    initIndex() {
        console.log("Halaman Mutasi Index berhasil dimuat!");
        let table;
        table = $("#datatables").DataTable({
            dom:
                "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 mb-3 mb-md-0 d-flex justify-content-center align-items-center'><'col-sm-12 col-md-3 text-right'f>>" +
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
                    className: "text-center", // Menambahkan kelas text-center untuk meratakan teks ke tengah
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
                    sSortAscending:
                        ": aktifkan untuk mengurutkan kolom secara menaik",
                    sSortDescending:
                        ": aktifkan untuk mengurutkan kolom secara menurun",
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

        // FUNGSI MENAMPILKAN RIWAYAT LOG
        const modalElement = document.getElementById("historyModal");
        if (!modalElement) {
            console.error("Elemen dengan id 'historyModal' tidak ditemukan.");
            return;
        }

        const historyModal = new bootstrap.Modal(modalElement);
        const historyContent = document.getElementById("historyContent");

        const fetchActivityLogs = async (itemId) => {
            try {
                const response = await fetch(`/activity-logs/${itemId}`);
                if (!response.ok) {
                    return `Error: ${response.status} ${response.statusText}`;
                }
                return await response.text();
            } catch (error) {
                console.error("Error fetching activity logs:", error);
                return `Gagal mengambil data riwayat. Error: ${error.message}`;
            }
        };

        const historyButtons = document.querySelectorAll(".view-history");
        if (historyButtons.length === 0) {
            console.warn(
                "Tidak ada tombol dengan class .view-history ditemukan."
            );
        }

        historyButtons.forEach((button) => {
            button.addEventListener("click", async () => {
                const itemId = button.getAttribute("data-item-id");
                console.log("Tombol diklik untuk item id:", itemId);
                try {
                    const data = await fetchActivityLogs(itemId);
                    // Masukkan langsung response partial view ke modal.
                    historyContent.innerHTML = data;
                } catch (error) {
                    historyContent.innerHTML = "Gagal mengambil data riwayat.";
                }
                historyModal.show();
            });
        });
    },
    initShow() {
        console.log("Halaman Mutasi Show berhasil dimuat!");
    },
    initCreate() {
        console.log("Halaman Mutasi Create berhasil dimuat!");
    },
    initEdit() {
        console.log("Halaman Mutasi Edit berhasil dimuat!");
    },
};
