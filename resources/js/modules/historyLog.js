export function initHistoryLog() {
    const modalElement = document.getElementById("historyModal");
    if (!modalElement) {
        console.error("Elemen dengan id 'historyModal' tidak ditemukan.");
        return;
    }

    // Asumsikan bootstrap sudah diimport secara global (misalnya lewat bootstrap-ui.js)
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
        console.warn("Tidak ada tombol dengan class .view-history ditemukan.");
    }

    historyButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const itemId = button.getAttribute("data-item-id");
            console.log("Tombol diklik untuk item id:", itemId);
            try {
                const data = await fetchActivityLogs(itemId);
                historyContent.innerHTML = data;
            } catch (error) {
                historyContent.innerHTML = "Gagal mengambil data riwayat.";
            }
            historyModal.show();
        });
    });
}
