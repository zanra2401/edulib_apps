<?php require_once("../../config/global.php") ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Edulib</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
  <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/spin.css' ?>>
  <link rel="stylesheet" href=<?= BASE_LINK . 'static/main.css' ?>>
  <script src=<?= BASE_LINK . 'libs/js/jquery.js' ?>></script>
  <script src=<?= BASE_LINK . "static/cekLoginPustaka.js" ?>></script>
  <script src=<?= BASE_LINK . 'libs/js/spin.js' ?>></script>
  <script src=<?= BASE_LINK . 'static/global.js' ?>></script>
  <title>Login Pustakawan - Edulib</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- === [ BAGIAN CSS LENGKAP ] === -->
    <style>
        :root {
            --pustaka-primary: #C0392B;
            --pustaka-primary-dark: #A93226;
            --pustaka-bg: #FDFEFE;
            --pustaka-card-bg: #FFFFFF;
            --pustaka-font-dark: #2C3E50;
            --pustaka-border: #EAEDED;
            --pustaka-danger: #E74C3C;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: var(--pustaka-bg);
        }

        .login-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .login-left {
            width: 55%;
            background: var(--pustaka-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pustaka-card-bg);
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
            font-family: 'EB Garamond', serif;
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
            background-color: var(--pustaka-card-bg);
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
            font-family: 'EB Garamond', serif;
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 2.5rem;
            color: var(--pustaka-primary);
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--pustaka-font-dark);
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--pustaka-border);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--pustaka-primary);
            box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.15);
        }

        #login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--pustaka-primary);
            color: var(--pustaka-card-bg);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        #login-btn:hover:not(:disabled) {
            background-color: var(--pustaka-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(192, 57, 43, 0.2);
        }
        
        #login-btn:disabled {
            background-color: #BDC3C7;
            cursor: not-allowed;
        }
        
        #toast-container { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 2000; }
        .toast { padding: 1rem 1.5rem; border-radius: 8px; color: var(--pustaka-card-bg); background-color: var(--pustaka-danger); box-shadow: var(--shadow); opacity: 0; transform: translateY(20px); animation: slideUp 0.5s forwards; font-weight: 500; }
        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
        
        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo">
                <img src="<?= BASE_LINK . "static/img/logo.png" ?>" alt="Logo Edulib" />
                <h1>EDULIB</h1>
                <p>Panel Pustakawan. Kelola koleksi dan sirkulasi buku dengan efisien.</p>
            </div>
        </div>
        <div class="login-right">
            <div class="form-box">
                <h2>LOGIN PUSTAKAWAN</h2>
                <form id="login-form">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" placeholder="Masukkan Username Anda" required />
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" placeholder="Masukkan Password Anda" required />
                    </div>
                    <button type="submit" id="login-btn">Login</button>
                </form>
            </div>
        </div>
    </div>
    
    <div id="toast-container"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {
            const API_BASE_URL = "http://localhost:8080/edulib"; // Sesuaikan jika perlu

            function showToast(message) {
                $('#toast-container').empty().append(`<div class="toast">${message}</div>`);
                setTimeout(() => $('#toast-container').empty(), 4000);
            }

            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                const loginBtn = $('#login-btn');
                const originalBtnText = loginBtn.html();

                loginBtn.prop('disabled', true).text('Logging in...');

                $.ajax({
                    url: `${API_BASE_URL}/auth/login`,
                    type: "POST",
                    xhrFields: { withCredentials: true },
                    contentType: "application/json",
                    data: JSON.stringify({
                        identitas: $("#username").val(),
                        password: $("#password").val(),
                        peran: "pustakawan"
                    }),
                    success: function (response) {
                        // Jika berhasil, arahkan ke halaman dashboard buku pustakawan
                        window.location.href = "../buku/index.php"; 
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || "Username atau Password salah.";
                        showToast(errorMsg);
                    },
                    complete: function() {
                        loginBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });
        });
    </script>
</body>
</html>
