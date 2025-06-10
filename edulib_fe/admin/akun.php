<?php 
    require_once("../config/global.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDULIB - Dashboard Akun</title>
    <script src=<?= BASE_LINK . "libs/js/jquery.js" ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginAdmin.js" ?>></script>
    <script src=<?= BASE_LINK . "static/global.js" ?>></script>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/spin.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables untuk kemudahan kustomisasi */
        :root {
            --primary-color: #8E44AD; /* Ungu */
            --primary-hover: #7D3C98;
            --secondary-color: #F4F6F7;
            --font-color: #34495E;
            --light-gray: #BDC3C7;
            --border-color: #EAECEE;
            --white: #FFFFFF;
            --danger-color: #E74C3C;
            --danger-hover: #C0392B;
            --success-color: #2ECC71;
            --font-primary: 'Inter', sans-serif;
            --font-headings: 'EB Garamond', serif;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--secondary-color);
            color: var(--font-color);
            display: flex;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 260px;
            background-color: var(--primary-color);
            color: var(--white);
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            height: 100vh;
            position: fixed;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            overflow: hidden;
        }
        .logo img { width: 100%; height: 100%; object-fit: cover; }

        .brand-name {
            font-family: var(--font-headings);
            font-size: 1.8rem;
            font-weight: 700;
        }

        .menu-items .nav-item {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 700;
        }

        .nav-item, .menu-item {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .logout {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .logout:hover { background-color: rgba(0,0,0,0.2); }

        /* --- Main Content --- */
        .main-content {
            margin-left: 260px; /* Lebar sidebar */
            flex-grow: 1;
            padding: 2rem 3rem;
            width: calc(100% - 260px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-title {
            font-family: var(--font-headings);
            font-size: 2.5rem;
            color: var(--primary-color);
        }
        .header-actions { display: flex; gap: 1rem; }

        .add-button {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }
        .add-button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        /* --- Tables --- */
        .table-container {
            background-color: var(--white);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        .table-container h2 { margin-bottom: 1.5rem; font-size: 1.5rem; }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        thead th {
            font-weight: 600;
            color: #797979;
        }
        tbody tr:hover { background-color: var(--secondary-color); }

        .action-buttons { display: flex; gap: 0.5rem; }
        .btn-edit, .btn-delete {
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            color: var(--white);
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }
        .btn-edit { background-color: var(--success-color); }
        .btn-edit:hover { background-color: #27AE60; }
        .btn-delete { background-color: var(--danger-color); }
        .btn-delete:hover { background-color: var(--danger-hover); }

        /* --- Modal Form --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-container {
            background: var(--white);
            padding: 2rem 3rem;
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transform: translateY(-50px);
            transition: transform 0.3s ease;
        }
        .modal-overlay.active .modal-container {
            transform: translateY(0);
        }

        .modal-container h1 { text-align: center; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; }
        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-family: var(--font-primary);
            font-size: 1rem;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(142, 68, 173, 0.2);
        }
        .form-group small {
            font-size: 0.8rem;
            color: var(--light-gray);
            margin-top: 4px;
        }

        .form-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-primary { background-color: var(--primary-color); color: var(--white); }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-secondary { background-color: var(--border-color); color: var(--font-color); }
        .btn-secondary:hover { background-color: var(--light-gray); }

        /* --- Toast Notification --- */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2000;
        }
        .toast {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: var(--white);
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
            opacity: 0;
            transform: translateX(100%);
            animation: slideIn 0.5s forwards;
        }
        @keyframes slideIn {
            to { opacity: 1; transform: translateX(0); }
        }
        .toast.success { background-color: var(--success-color); }
        .toast.error { background-color: var(--danger-color); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <div class="logo"><img src="Logo.png" alt="Logo EDULIB"></div>
            <div class="brand-name">EDULIB</div>
        </div>
        <div class="menu-items">
            <div class="nav-item">Akun</div>
            <div class="menu-item" data-url="laporan_perpustakaan.php">Laporan Perpustakaan</div>
            <div class="menu-item" data-url="denda_siswa.php">Denda Siswa</div>
        </div>
        <div class="logout" id="logout-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span>Logout</span>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="page-title">Manajemen Akun</h1>
            <div class="header-actions">
                <button class="add-button" data-peran="siswa">Tambah Siswa</button>
                <button class="add-button" data-peran="pustakawan">Tambah Pustakawan</button>
                <button class="add-button" data-peran="admin">Tambah Admin</button>
            </div>
        </div>

        <div class="table-container">
            <h2>Siswa</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>NISN</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="siswa-table-body">
                    </tbody>
            </table>
        </div>

        <div class="table-container">
            <h2>Admin</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="admin-table-body">
                     </tbody>
            </table>
        </div>

        <div class="table-container">
            <h2>Pustakawan</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pustakawan-table-body">
                     </tbody>
            </table>
        </div>
    </div>

    <div class="modal-overlay" id="form-modal-overlay">
        <div class="modal-container">
            <h1 id="modal-title">Tambah Akun</h1>
            <form id="account-form">
                <input type="hidden" id="peran">
                <input type="hidden" id="action">
                <input type="hidden" id="id">

                <div class="form-group" id="group-username">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username">
                </div>
                <div class="form-group" id="group-nama">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="form-group" id="group-password">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <small id="warning-p">Kosongkan jika tidak ingin mengubah password.</small>
                </div>
                <div class="form-group" id="group-nisn">
                    <label for="nisn">NISN</label>
                    <input type="text" id="nisn" name="nisn">
                </div>
                <div class="form-group" id="group-email">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-buttons">
                    <button type="button" class="btn btn-secondary" id="close-modal-btn">Batal</button>
                    <button type="submit" class="btn btn-primary" id="simpan-btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="toast-container"></div>

    <script>
        $(document).ready(function() {
        const API_BASE_URL = "http://localhost:9191/edulib";

        // --- UTILITIES ---
        /**
         * Menampilkan notifikasi toast.
         * @param {string} message - Pesan yang akan ditampilkan.
         * @param {string} type - 'success' atau 'error'.
         */
        function showToast(message, type = 'success') {
            const toast = $(`<div class="toast ${type}">${message}</div>`);
            $('#toast-container').append(toast);
            setTimeout(() => toast.remove(), 4000);
        }

        /**
         * Fungsi generik untuk mengambil data pengguna.
         * @param {string} role - 'siswas', 'admins', atau 'pustakawans'.
         * @param {string} tableBodyId - ID dari tbody tabel target.
         */
        function fetchUsers(role, tableBodyId) {
            $.ajax({
                url: `${API_BASE_URL}/user/${role}`,
                type: "GET",
                xhrFields: { withCredentials: true },
                success: function(response) {
                    const tableBody = $(`#${tableBodyId}`);
                    tableBody.empty(); 
                    response.user.forEach(user => {
                        let row;
                        if (role === 'siswas') {
                            row = `
                                <tr>
                                    <td>${user.namaLengkap || '-'}</td>
                                    <td>${user.nisn}</td>
                                    <td>${user.email}</td>
                                    <td class="action-buttons">
                                        <button class="btn-edit" data-id="${user.idUser}" data-peran="siswa">Edit</button>
                                        <button class="btn-delete" data-id="${user.idUser}">Hapus</button>
                                    </td>
                                </tr>`;
                        } else {
                            row = `
                                <tr>
                                    <td>${user.username}</td>
                                    <td>${user.namaLengkap || '-'}</td>
                                    <td>${user.email}</td>
                                    <td class="action-buttons">
                                        <button class="btn-edit" data-id="${user.idUser}" data-peran="${role === 'admins' ? 'admin' : 'pustakawan'}">Edit</button>
                                        <button class="btn-delete" data-id="${user.idUser}">Hapus</button>
                                    </td>
                                </tr>`;
                        }
                        tableBody.append(row);
                    });
                },
                error: function() {
                    showToast(`Gagal memuat data ${role}.`, 'error');
                }
            });
        }
        
        // Panggil fungsi untuk memuat semua data saat halaman dimuat
        function loadAllData() {
            fetchUsers('siswas', 'siswa-table-body');
            fetchUsers('admins', 'admin-table-body');
            fetchUsers('pustakawans', 'pustakawan-table-body');
        }
        
        loadAllData();


        // --- MODAL & FORM LOGIC ---
        const modal = $('#form-modal-overlay');

        function openModal(peran, action, id = null) {
            $('#account-form')[0].reset(); // Reset form
            $('#peran').val(peran);
            $('#action').val(action);
            $('#id').val(id);
            
            // Atur field yang relevan
            $('#group-nisn').toggle(peran === 'siswa');
            $('#group-username').toggle(peran !== 'siswa');
            $('#password').attr('placeholder', action === 'edit' ? 'Kosongkan jika tidak diubah' : 'Wajib diisi');

            if (action === 'edit') {
                $('#modal-title').text(`Edit Akun ${peran}`);
                // Ambil data user yang ada
                $.ajax({
                    url: `${API_BASE_URL}/user/${id}`,
                    type: "GET",
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function(response) {
                        const user = response.user;
                        $('#username').val(user.username);
                        $('#nisn').val(user.nisn);
                        $('#email').val(user.email);
                        $('#nama').val(user.namaLengkap);
                    },
                    error: function() {
                        showToast('Gagal mengambil data user.', 'error');
                        closeModal();
                    }
                });
            } else {
                $('#modal-title').text(`Tambah Akun ${peran}`);
            }
            modal.addClass('active');
        }

        function closeModal() {
            modal.removeClass('active');
        }

        // Event listener untuk tombol tambah
        $('.add-button').on('click', function() {
            const peran = $(this).data('peran');
            $("#warning-p").addClass("d-none");
            openModal(peran, 'tambah');
        });

        // Event listener untuk tombol edit dan hapus (menggunakan event delegation)
        $('.main-content').on('click', '.btn-edit', function() {
            console.log($(this).data());
            const id = $(this).data('id');
            const peran = $(this).data('peran');
            $("#warning-p").removeClass("d-none");
            openModal(peran, 'edit', id);
        });

        $('.main-content').on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus akun ini?')) {
                $.ajax({
                    url: `${API_BASE_URL}/user/${id}`,
                    type: 'DELETE',
                    success: function() {
                        showToast('Akun berhasil dihapus.', 'success');
                        loadAllData();
                    },
                    error: function() {
                        showToast('Gagal menghapus akun.', 'error');
                    }
                });
            }
        });


        // Event listener untuk tombol close modal dan submit form
        $('#close-modal-btn').on('click', closeModal);
        modal.on('click', function(e) {
            if (e.target === this) closeModal(); // Tutup jika klik di luar modal
        });

        $('#account-form').on('submit', function(e) {
            e.preventDefault();
            const action = $('#action').val();
            const id = $('#id').val();
            const peran = $('#peran').val();

            let url, data;

            if (action === 'edit') {
                url = `${API_BASE_URL}/user/edit/${id}`;
                data = {
                    username: (peran !== 'siswa') ? $('#username').val() : null,
                    nisn: (peran === 'siswa') ? $('#nisn').val() : null,
                    password: $('#password').val().length > 0 ? $('#password').val() : null,
                    email: $('#email').val(),
                    namaLengkap: $('#nama').val()
                };
            } else { // Tambah
                url = `${API_BASE_URL}/auth/${peran}/register`;
                data = {
                    username: (peran !== 'siswa') ? $('#username').val() : null,
                    nisn: (peran === 'siswa') ? $('#nisn').val() : null,
                    password: $('#password').val(),
                    email: $('#email').val(),
                    nama: $('#nama').val() // API Anda menggunakan 'nama' untuk register
                };
            }

            $.ajax({
                url: url,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function() {
                    showToast(`Akun berhasil ${action === 'edit' ? 'diperbarui' : 'ditambahkan'}.`, 'success');
                    closeModal();
                    loadAllData();
                },
                error: function(response) {
                    const errorMsg = response.responseJSON?.message || `Gagal ${action} akun.`;
                    showToast(errorMsg, 'error');
                }
            });
        });
        
        // --- SIDEBAR NAVIGATION & LOGOUT ---
        $('.menu-item').on('click', function(){
            const url = $(this).data('url');
            if(url) window.location.href = url;
        });

        $('#logout-btn').on('click', function() {
            $.ajax({
                url: `${API_BASE_URL}/auth/logout`,
                type: "GET",
                xhrFields: { withCredentials: true },
                success: function () {
                    showToast('Logout berhasil!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                },
                error: function() {
                    showToast('Logout gagal.', 'error');
                }
            });
        });
    });
    </script>
</body>
</html>