<?php
    $const = require_once("../config/global.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa</title>
    <script src=<?= BASE_LINK . "libs/js/jquery.js" ?>></script>
    <script src=<?= BASE_LINK . "libs/js/spin.js" ?>></script>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'static/main.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/spin.css' ?>>
    <script src=<?= BASE_LINK . 'static/global.js' ?>></script>
</head>

<body>

    <nav class="navbar fw-bold">
        <div class="container-fluid d-flex justify-content-between shadow-sm p-1 px-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#" style="">
                <div class="bg-primary p-1 rounded-1">
                    <img src=<?= BASE_LINK . "static/img/Logo.png" ?> alt="Logo" style="height: 50px; width:  60px;" width="30" height="24" class="d-inline-block fw-bold align-text-top">
                </div>
                Edulib
            </a>

            <a href=<?= BASE_PATH . "siswa/login.php" ?>>
                <button type="button" class="btn btn-success">Masuk</button>
            </a>
        </div>
    </nav>

    <div id="alert" class="alert alert-success w-50 out transition" role="alert">
        Anda Berhasil Membuat Akun EDULIB <a href=<?= BASE_PATH . "siswa/login.php" ?> class="alert-link">LOGIN</a>
    </div>  

    <div class="w-100 vh-100 d-flex flex-column justify-content-center align-items-center">
        <form class="p-3 w-50 d-flex flex-column bg-light gap-3 shadow-sm">
            <div class="d-flex align-items-center gap-2">
                <div class="p-1 bg-primary rounded-1">
                    <img src=<?= BASE_LINK . "static/img/Logo.png" ?> style="height: 80px;" alt="">
                </div>
                <h2 class="fs-bold" style="color: gray;">Daftar</h2>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nisn" placeholder="Nisn" required>
                <label for="nisn">Nisn</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" id="confirm-password" placeholder="Konfirmasi Password" required>
                <label for="confirm-password">Konfimasi Password</label>
            </div>

            <button type="button" id="daftar" class="btn btn-primary">
                Daftar
            </button>
        </form>
    </div>

    
    <script src=<?= BASE_LINK . "libs/js/bootstrap.js" ?>></script>
    <script>
        function spinButton() {
            if ($("#daftar").hasClass("disabled")) {
                $("#dafar").empty();
                $("#daftar").html("LOGIN");
            } else {
                $("#daftar").html("");
                var spiner = new Spin.Spinner(opts).spin($("#login")[0]);
            }

            $("#daftar").toggleClass("disabled");
            $("#daftar").toggleClass("p-4");
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#daftar").click(() => {
                spinButton();
                $.ajax({
                    url: "http://localhost:9191/edulib/auth/siswa/register",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        nisn: $("#nisn").val(),
                        password: $("#password").val(),
                        email: $("#email").val()
                    }),
                    success: function (response) {
                        $("#alert").toggleClass("out");
                        $("#alert").toggleClass("in");

                        spinButton();

                        setTimeout(() => {
                            $("#alert").toggleClass("out");
                            $("#alert").toggleClass("in");
                        }, 3000);
                    },
                    error: function(xhr, status, error) {
                        spinButton();
                    }
                });
            });

        });
    </script>
</body>
</html>