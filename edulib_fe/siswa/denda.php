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
        .badge-active {
            background-color: #0d6efd !important;
            color: white !important;
        }

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
                    <a class="nav-link active" href="http://localhost:8282/edulib/siswa/denda.php" aria-disabled="true">Denda</a>
                </li>
                
                <li class="nav-item" href="http://localhost:8282/edulib/siswa/notifikasi.php">
                    <a class="nav-link" href="http://localhost:8282/edulib/siswa/notifikasi.php" aria-disabled="true">Notifikasi</a>
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
        function logOut() {
            $.ajax({
                url: "http://localhost:8080/edulib/auth/logout",
                type: "get",
                xhrFields: {
                    withCredentials: true
                },
                success: function (response) {
                    location.reload();
                }
            });
        }
        function showBuku(bukus) {
            console.log(bukus)
            bukus.forEach(element => {
                let pathGambar = element.pathGambar ? `http://localhost:8080/gambar/buku/${element.pathGambar}` : 'https://placehold.co/80x120/e0e0e0/757575?text=No+Image';

                $(`<div class="card">
                    <div class="card-body d-flex p-0">
                        <img
                            src="${pathGambar}"
                            alt="Judul Buku: Judul Buku yang Sangat Panjang Sekali"
                            onerror="this.onerror=null;this.src='https://placehold.co/400x300/cccccc/000000?text=Gambar+Tidak+Tersedia';"
                        >
                        <span class="p-3">
                            <h5 class="card-title">${element.judul}</h5>
                            <span class="card-text text-danger">Denda yang Harus Di bayar: ${Intl.NumberFormat('id-ID', {style: 'currency',currency: 'IDR'}).format(element.denda)}</span>
                        </span>
                    </div>
                </div>`).appendTo($("#buku-container"));
            });
        }
    </script>


    <script>
        $("document").ready(() => {
            $.ajax({
                url: "http://localhost:8080/edulib/buku/denda",
                type: "get",
                xhrFields: {
                    withCredentials: true,
                },
                success: function (response) {
                    showBuku(response.buku);
                }
            });
        });
    </script>
</body>
</html>