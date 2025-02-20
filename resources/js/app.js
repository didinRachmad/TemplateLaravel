// import './bootstrap';

import $ from 'jquery';
import select2 from "select2"
select2();

import 'bootstrap';
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import jsPDF from 'jspdf';
import JSZip from 'jszip';
// import pdfMake from 'pdfmake';
import * as XLSX from 'xlsx';

import Swal from 'sweetalert2/dist/sweetalert2.all.js';

import { Fancybox } from '@fancyapps/ui';
Fancybox.bind('[data-fancybox]');

import moment from 'moment';
import 'moment-timezone';

import { showAlert, showToast, showConfirmDialog } from './components/alerts.js';

window.$ = $;
window.jQuery = $;
// window.jsPDF = jsPDF;
// window.JSZip = JSZip;
// window.pdfMake = pdfMake;
// window.moment = moment;
// window.XLSX = XLSX;

window.Swal = Swal;

import Alpine from 'alpinejs';


window.Alpine = Alpine;

Alpine.start();

$(document).ready(function () {
    setTimeout(() => {
        $("#loading-screen").fadeOut("slow");
    }, 200); // Delay 1 detik sebelum loading hilang
});

// Mendaftarkan semua file JS di folder ini (misalnya, item.js, users.js, posts.js)
const modules = import.meta.glob('./*.js');

document.addEventListener('DOMContentLoaded', async () => {
    const page = document.body.dataset.page;   // Contoh: "item", "users", "posts"
    const action = document.body.dataset.action; // Contoh: "index", "create", "edit"

    if (page) {
        // Bentuk path yang sesuai dengan daftar modul, misalnya "./item.js"
        const modulePath = `./${page}.js`;
        if (modules[modulePath]) {
            try {
                // Memanggil modul yang sudah terdaftar dengan glob
                const module = await modules[modulePath]();
                const functionName = `init${capitalize(action)}`;
                if (module.default && typeof module.default[functionName] === 'function') {
                    module.default[functionName]();
                } else {
                    console.error(`Fungsi ${functionName} tidak ditemukan pada module ${page}`);
                }
            } catch (error) {
                console.error(`Module untuk halaman "${page}" terjadi kesalahan:`, error);
            }
        } else {
            console.error(`Module untuk halaman "${page}" tidak ditemukan`);
        }
    }
});

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}


// Ekspos ke global scope (jika diperlukan)
window.showAlert = showAlert;
window.showToast = showToast;
window.showConfirmDialog = showConfirmDialog;

document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-delete')) {
        e.preventDefault();
        const form = e.target.closest('.form-delete');
        showConfirmDialog(
            "Apakah Anda yakin?",
            "Data yang dihapus tidak bisa dikembalikan!",
            () => form.submit()
        );
    } else if (e.target.closest('.btn-approve')) {
        e.preventDefault();
        const form = e.target.closest('.form-approval');
        showConfirmDialog(
            "Apakah Anda yakin?",
            "Harap periksa kembali sebelum melakukan approve data!",
            () => form.submit()
        );
    } else if (e.target.closest('.btn-reset-password')) {
        e.preventDefault();
        const form = e.target.closest('.form-reset-password');
        showConfirmDialog(
            "Apakah Anda yakin?",
            "Password akan direset ke data awal!",
            () => form.submit()
        );
    }
});

