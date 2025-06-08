<?php require_once("../config/global.php") ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Admin - Edulib</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- === [ BAGIAN CSS LENGKAP ] === -->
    <style>
        :root {
            --primary-color: #8E44AD;
            --primary-hover: #7D3C98;
            --secondary-color: #F4F6F7;
            --font-color: #34495E;
            --font-color-light: #797979;
            --border-color: #E0E0E0;
            --white: #FFFFFF;
            --danger-color: #E74C3C;
            --font-primary: 'Inter', sans-serif;
            --font-headings: 'EB Garamond', serif;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: var(--font-primary);
            background-color: var(--secondary-color);
        }

        .login-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .login-left {
            width: 55%;
            background: linear-gradient(45deg, var(--primary-color), #6C3483);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            flex-direction: column;
            text-align: center;
            padding: 2rem;
        }
        
        .login-left .logo img {
            width: 120px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .login-left h1 {
            font-family: var(--font-headings);
            font-size: 3.5rem;
            letter-spacing: 3px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .login-left p {
            margin-top: 1rem;
            font-size: 1.1rem;
            max-width: 400px;
            opacity: 0.9;
        }
        
        .login-right {
            width: 45%;
            background-color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-box {
            width: 100%;
            max-width: 380px;
            padding: 2rem;
        }

        .form-box h2 {
            font-family: var(--font-headings);
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 2.5rem;
            color: var(--primary-color);
        }

        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--font-color);
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.2);
        }

        #login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        #login-btn:hover:not(:disabled) {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(142, 68, 173, 0.3);
        }
        
        #login-btn:disabled {
            background-color: #BDC3C7;
            cursor: not-allowed;
        }
        
        /* Toast Notification (Sama seperti dashboard) */
        #toast-container { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 2000; }
        .toast { padding: 1rem 1.5rem; border-radius: 8px; color: var(--white); box-shadow: var(--shadow); opacity: 0; transform: translateY(20px); animation: slideUp 0.5s forwards; font-weight: 500; }
        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
        .toast.error { background-color: var(--danger-color); }
        
        /* Responsive */
        @media (max-width: 768px) {
            .login-left {
                display: none;
            }
            .login-right {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo">
                <img src="<?= BASE_LINK . "static/img/logo.png" ?>" alt="Logo Edulib" />
                <h1>EDULIB</h1>
                <p>Sistem Informasi Perpustakaan Terintegrasi untuk Kemudahan Akses dan Manajemen.</p>
            </div>
        </div>
        <div class="login-right">
            <div class="form-box">
                <h2>LOGIN ADMIN</h2>
                <form id="login-form">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" placeholder="Masukkan Username Anda" required />
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" placeholder="Masukkan Password Anda" required />
                    </div>
                    <button type="submit" id="login-btn">
                        <svg id="login-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        <span id="login-text">Login</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Toast Notification Container -->
    <div id="toast-container"></div>

    <!-- Pustaka JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- === [ BAGIAN JAVASCRIPT LENGKAP ] === -->
    <script>
        $(document).ready(function() {
            const API_BASE_URL = "http://localhost:8080/edulib";

            function showToast(message, type = 'error') {
                // Hapus toast lama jika ada
                $('#toast-container').empty();
                const toast = $(`<div class="toast ${type}">${message}</div>`);
                $('#toast-container').append(toast);
                setTimeout(() => toast.remove(), 4000);
            }

            $('#login-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit default

                const username = $('#username').val();
                const password = $('#password').val();
                const loginBtn = $('#login-btn');
                const loginText = $('#login-text');

                if (!username || !password) {
                    showToast("Username dan Password tidak boleh kosong.");
                    return;
                }
                
                // Set loading state
                loginBtn.prop('disabled', true);
                loginText.text('Logging in...');

                $.ajax({
                    url: `${API_BASE_URL}/auth/login`,
                    type: "POST",
                    xhrFields: {
                        withCredentials: true
                    },
                    contentType: "application/json",
                    data: JSON.stringify({
                        identitas: username,
                        password: password,
                        peran: "pustakawan" // Atau peran lain sesuai kebutuhan
                    }),
                    success: function (response) {
                        // Jika berhasil, redirect ke halaman dashboard
                        window.location.href = "akun.php"; 
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || "Username atau Password salah.";
                        showToast(errorMsg);
                    },
                    complete: function() {
                        // Kembalikan ke state semula setelah selesai (baik error maupun success)
                        loginBtn.prop('disabled', false);
                        loginText.text('Login');
                    }
                });
            });
        });
    </script>
</body>
</html>