<?php
    require_once("../config/global.php");
    require_once("../config/theme.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa</title>
    <script src=<?= BASE_LINK . "libs/js/jquery.js" ?>></script>
    <script src=<?= BASE_LINK . "libs/js/spin.js" ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginSiswa.js" ?>></script>
    <script src=<?= BASE_LINK . "static/global.js" ?>></script>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'static/main.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/spin.css' ?>>
</head>

<body>
    <nav class="navbar fw-bold">
        <div class="container-fluid d-flex justify-content-between shadow-sm p-1 px-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href=<?= BASE_PATH . "siswa/dashboard.php" ?> style="">
                <div class="bg-primary p-1 rounded-1">
                    <img src=<?= BASE_LINK . "static/img/Logo.png" ?> alt="Logo" style="height: 50px; width:  60px;" width="30" height="24" class="d-inline-block fw-bold align-text-top">
                </div>
                Edulib
            </a>

            <a href=<?= BASE_PATH . "siswa/register.php" ?>>
                <button type="button" class="btn btn-success">Daftar</button>
            </a>
        </div>
    </nav>

    <div id="alert" class="alert alert-success w-50 out transition" role="alert">
        Password atau Username Salah
    </div>     


    <div class="w-100 vh-100 d-flex flex-column justify-content-center align-items-center">
        <form class="p-3 w-50 d-flex flex-column bg-light gap-3 shadow-sm">
            <div class="d-flex align-items-center gap-2">
                <div class="p-1 bg-primary rounded-1">
                    <img src=<?= BASE_LINK . "static/img/Logo.png" ?> style="height: 80px;" alt="">
                </div>
                <h2 style="color: <?= PRIMARY_TEXT ?>;">LOGIN</h2>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nisn" placeholder="NISN" required>
                <label for="nisn">NISN</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <button type="button" id="login" class="btn btn-primary">
                LOGIN
            </button>
        </form>
    </div>
    


    <script src=<?= BASE_LINK . "libs/js/bootstrap.js" ?>></script>
    <script>
        
        function spinButton() {
            if ($("#login").hasClass("disabled")) {
                $("#login").empty();
                $("#login").html("LOGIN");
            } else {
                $("#login").html("");
                var spiner = new Spin.Spinner(opts).spin($("#login")[0]);
            }

            $("#login").toggleClass("disabled");
            $("#login").toggleClass("p-4");
        }
    </script>

    <script>
        $(document).ready(function() {

            $("#login").click(() => {
                spinButton();
                $.ajax({
                    url: "http://localhost:8080/edulib/auth/login",
                    type: "POST",
                    xhrFields: {
                        withCredentials: true
                    },
                    contentType: "application/json",
                    data: JSON.stringify({
                        identitas: $("#nisn").val(),
                        password: $("#password").val(),
                        peran: "siswa"
                    }),
                    success: function (response) {
                        spinButton();
                        window.location.href = "http://localhost:8282/edulib/siswa/dashboard.php"
                    },
                    error: function(xhr, status, error) {
                        $("#alert").toggleClass("out");
                        $("#alert").toggleClass("in");

                        spinButton();

                        setTimeout(() => {
                            $("#alert").toggleClass("out");
                            $("#alert").toggleClass("in");
                        }, 3000);
                    }
                });
            });

        });
    </script>
</body>
</html>