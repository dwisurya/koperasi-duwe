import './bootstrap';

import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import jQuery from 'jquery';
import DataTable from 'datatables.net-bs5';

window.$ = jQuery;

document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggle-sidebar');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            if (overlay) overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    const dt = document.getElementById('dataTable');
    if (dt) {
        new DataTable(dt, {
            language: {
                emptyTable: 'Tidak ada data yang tersedia pada tabel ini',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 entri',
                infoFiltered: '(disaring dari _MAX_ entri keseluruhan)',
                lengthMenu: 'Tampilkan _MENU_ entri',
                loadingRecords: 'Sedang memuat...',
                processing: 'Sedang memproses...',
                search: 'Cari:',
                zeroRecords: 'Tidak ditemukan data yang sesuai',
                thousands: "'",
                paginate: {
                    first: 'Pertama',
                    last: 'Terakhir',
                    next: 'Selanjutnya',
                    previous: 'Sebelumnya',
                },
                aria: {
                    sortAscending: ': aktifkan untuk mengurutkan kolom ke atas',
                    sortDescending: ': aktifkan untuk mengurutkan kolom menurun',
                },
            },
            columnDefs: [
                { targets: 'no-sort', orderable: false },
                { targets: 'no-search', searchable: false },
            ],
        });
    }
});
