export async function initFilePond() {
    // Impor seluruh modul FilePond, bukan hanya default export
    const FilePondModule = await import("filepond");
    // Impor plugin-plugin yang diperlukan (default export sudah benar untuk plugin)
    const { default: FilePondPluginImagePreview } = await import(
        "filepond-plugin-image-preview"
    );
    const { default: FilePondPluginFilePoster } = await import(
        "filepond-plugin-file-poster"
    );
    const { default: FilePondPluginImageOverlay } = await import(
        "filepond-plugin-image-overlay"
    );
    const { default: FilePondPluginFileValidateSize } = await import(
        "filepond-plugin-file-validate-size"
    );
    const { default: FilePondPluginFileValidateType } = await import(
        "filepond-plugin-file-validate-type"
    );

    // Daftarkan plugin yang diperlukan menggunakan FilePondModule
    FilePondModule.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFilePoster,
        FilePondPluginImageOverlay,
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType
    );

    // Inisialisasi FilePond untuk input upload gambar
    const fileInput = $("#upload-images")[0];
    if (fileInput) {
        // Jika window.storedImagesPaths tidak ada, set sebagai array kosong
        let storedImagesPaths =
            typeof window.storedImagesPaths !== "undefined" &&
            window.storedImagesPaths
                ? window.storedImagesPaths
                : [];

        // Jika masih berupa string, lakukan parsing
        if (typeof storedImagesPaths === "string") {
            try {
                storedImagesPaths = JSON.parse(storedImagesPaths);
            } catch (e) {
                console.error("Gagal meng-parse storedImagesPaths:", e);
                storedImagesPaths = [];
            }
        }

        // Mapping data yang tersimpan menjadi format FilePond (jika ada)
        const storedImages = storedImagesPaths.map((path) => ({
            source: path,
            options: {
                type: "local",
                file: {
                    name: path.split("/").pop(),
                },
                metadata: {
                    poster: window.location.origin + "/storage/" + path,
                },
            },
        }));

        const pond = FilePondModule.create(fileInput, {
            allowMultiple: true,
            maxFiles: 5,
            acceptedFileTypes: ["image/*"],
            maxFileSize: "2MB", // Ubah ke '8MB' jika diperlukan
            storeAsFile: true,
            files: storedImages,
            server: {
                load: (source, load, error, progress, abort, headers) => {
                    const url = window.location.origin + "/storage/" + source;
                    fetch(url)
                        .then((res) => res.blob())
                        .then(load)
                        .catch(() => error("Gagal memuat file"));
                },
                // process: '/upload',
                // revert: '/revert'
            },
        });
        window.pondInstance = pond;

        // Fungsi untuk mengambil file dari server dan membuat File object
        async function reinitializeLocalFile(path) {
            const url = window.location.origin + "/storage/" + path;
            const response = await fetch(url);
            if (!response.ok) {
                console.warn("Gagal mengambil file:", response.statusText);
                return null; // Tidak melempar error, cukup mengembalikan null
            }
            const blob = await response.blob();
            const fileName = path.split("/").pop();
            return new File([blob], fileName, { type: blob.type });
        }

        // Re-inisialisasi file lokal agar menjadi File object dan aktifkan overlay
        (async function () {
            const localFiles = pond
                .getFiles()
                .filter(
                    (file) => file.origin === FilePondModule.FileOrigin.LOCAL
                );
            for (const fileItem of localFiles) {
                try {
                    const newFile = await reinitializeLocalFile(
                        fileItem.source
                    );
                    pond.removeFile(fileItem.id);
                    await pond.addFile(newFile);
                } catch (e) {
                    console.error(
                        "Error reinitializing file:",
                        fileItem.source,
                        e
                    );
                }
            }
        })();
    }
}
