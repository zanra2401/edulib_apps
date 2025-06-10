<?php
    require_once "../config/global.php";
    require_once "../config/theme.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku</title>
    <script src=<?= BASE_LINK . "libs/js/jquery.js" ?>></script>
    <script src=<?= BASE_LINK . "libs/js/spin.js" ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginSiswa.js" ?>></script>
    <script src=<?= BASE_LINK . "static/global.js" ?>></script>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/spin.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'static/main.css' ?>>
    <style>
        img {
            aspect-ratio: 3/4;
            width: 150px;
            object-fit: cover;
        }

        .buku-container-1 {
            width: 49%;
        }

        .card {
            overflow: hidden;
        }

        .info-book {
            flex: 1;
        }
        
        @media only screen and (max-width: 700px) {
            #buku-container {
                display: flex;
                flex-direction: column;
            }

            .buku-container-1 {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">EDULIB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="http://localhost:8282/edulib/siswa/dashboard.php">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8282/edulib/siswa/buku.php">Buku</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8282/edulib/siswa/history.php">Riwayat</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8282/edulib/siswa/denda.php" aria-disabled="true">Denda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="http://localhost:8282/edulib/siswa/notifikasi.php" aria-disabled="true">Notifikasi</a>
                </li>
            </ul>

            <button onclick="logOut()" class="btn btn-success bg-alert" type="submit">Log Out</button>
            </div>
        </div>
    </nav>

    <div id="main-content" class="container-fluid p-4 d-flex flex-column gap-3">
        <div id="buku-container" class="d-flex flex-column flex-wrap justify-content-between gap-1">
           
        </div>
    </div>

    <div id="alert" class="alert alert-success w-50 out transition" role="alert">
        Berhasil mengajukan untuk meminjam buku
    </div>     


    <script src=<?= BASE_LINK . "libs/js/bootstrap.js" ?>></script>

    <script>
        function showNotif(bukus) {
            bukus.forEach(element => {
                $(`<div class="card">
                    <div class="card-body">
                        <h5 class="card-title">${(new Date(element[1])).toLocaleString()}</h5>
                        <span class="card-text text-danger">${element[0]}</span>
                    </div>
                </div>`).appendTo($("#buku-container"));
            });
        }
    </script>


    <script>
        $("document").ready(() => {
            $.ajax({
                url: "http://localhost:9191/edulib/user/notifikasi",
                type: "get",
                xhrFields: {
                    withCredentials: true,
                },
                success: function (response) {
                    showNotif(response.user);
                }
            });
        });

                function logOut() {
            $.ajax({
                url: "http://localhost:9191/edulib/auth/logout",
                type: "get",
                xhrFields: {
                    withCredentials: true
                },
                success: function (response) {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>