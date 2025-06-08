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
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/spin.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
        <style>
        img {
            aspect-ratio: 3/4;
            width: 150px;
            object-fit: cover;
        }

        .card {
            overflow: hidden;
        }

        #profile-info {
            align-items: center;

        }

        #nama {
            flex-direction: column;
        }

        #identitas {
            display: flex;
            gap: 10px;
        }

        @media only screen and (max-width: 700px) {
            #profile-info {
                flex-direction: column;
                align-items: start;
                justify-content: start;

            }


            #nama {
                flex-direction: column;
                justify-content: start;
                align-items: start;
                gap: 5px;
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
                    <a class="nav-link active" aria-current="page" href="http://localhost:8282/edulib/siswa/dashboard.php">Dashboard</a>
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
                    <a class="nav-link" href="http://localhost:8282/edulib/siswa/notifikasi.php" aria-disabled="true">Notifikasi</a>
                </li>
            </ul>

            <button onclick="logOut()" class="btn btn-success bg-alert" type="submit">Log Out</button>
            </div>
        </div>
    </nav>

    <div id="main-content" class="container-fluid p-4 d-flex flex-column gap-3">

        <div id="profile-info" class="d-flex gap-3 align-items-center">
            <div class="bg-dark d-flex align-items-center justify-content-center text-light fs-5" style="width: 80px;  border-radius: 100%; aspect-ratio: 1/1;">
                Z
            </div>
            <div class="d-flex flex-column justify-content-center gap-0">
                <span class="fw-bold" id="nisn-tag">
                    24012005    
                </span>

                <span>
                    <span id="nama-tag">
                        Zanuar Rikza Aditiya
                    </span>
                    <button type="button" class="p-0 bg-primary text-light py-1 px-2 rounded-5 border border-0" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        edit
                    </button>
                </span>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Nama</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">Nama</span>
                    <input type="text" id="nama-input" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="save()" class="btn btn-primary" id="edit-nama">Save changes</button>
            </div>
            </div>
        </div>
        </div>

        
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="http://localhost:8282/edulib/siswa/dashboard.php" aria-current="page" href="#">Dipinjam</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://localhost:8282/edulib/siswa/dashboard-pengajuan.php">Pengajuan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="http://localhost:8282/edulib/siswa/dashboard-penolakan.php">Penolakan</a>
            </li>
        </ul>

        <div id="buku-container" class="container-fluid p-0 d-flex flex-column gap-4">
            
        </div>

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

        function dipinjamBadge(dipinjam) {
              $(`<button type="button" class="btn btn-warning text-light rounded-5">
                        Dipinjam <span class="badge text-bg-primary">${dipinjam}</span>
                        </button>`).appendTo("#profile-info");
        }

        function terlambatBadge(terlambat ) {
            if (terlambat > 0) {
                $(`<button type="button" class="btn btn-danger rounded-5">
                Terlambat Dikembalikans <span class="badge text-bg-primary">${terlambat}</span>
                </button>`).appendTo("#profile-info");
            }
        }

        function peringatanTerlambat(terlambat) {
            if (terlambat > 0) {
                $(` <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Peringatan Anda Terlambat Mengembalikan Buku</h4>
                <p>Silakan segera untuk mengembalikan buku yang terlambat untuk dikembalikan</p>
                <hr>
                <p class="mb-0 fw-bold">Dendan 500 * hari terlambat mengembalikan buku</p>
                </div>`).insertAfter($("#main-content > #profile-info"));
            }
        }

        function showBuku(bukus) {
            bukus.forEach(element => {
                let pathGambar = element[3] ? `http://localhost:8080/gambar/buku/${element[3]}` : 'https://placehold.co/80x120/e0e0e0/757575?text=No+Image';
                console.log(element);
                $(`<div class="card">
                    <div class="card-body d-flex p-0 gap-2">
                        <img
                            src="${pathGambar}"
                            alt="Judul Buku: Judul Buku yang Sangat Panjang Sekali"
                            onerror="this.onerror=null;this.src='https://placehold.co/400x300/cccccc/000000?text=Gambar+Tidak+Tersedia';"
                        >
                        <span class="py-2 justify-content-between d-flex flex-column">
                            <span>
                                <h5 class="card-title">${element[1]}</h5>
                                <p class="card-text text-warning">Tenggat: ${element[2]}</p>
                            </span>
                            <span class="badge text-bg-warning text-light p-2" style="width: fit-content;">Ditolak</span>
                        </span>
                    </div>
                </div>`).appendTo($("#buku-container"));
            });
        }

    </script>

    <script>

        $.ajax({
            url: "http://localhost:8080/edulib/user/user-login",
            type: "get",
            xhrFields: {
                withCredentials: true
            },
            success: function(response) {
                $("#nisn-tag").html(response.user.nisn);
                $("#nama-tag").html(response.user.namaLengkap);
                $("#nama-input").val(response.user.namaLengkap);
            }
        })

        function save() {
            $.ajax({
                url: `http://localhost:8080/edulib/user/editnama`,
                type: "post",
                contentType: "application/json",
                xhrFields: {
                    withCredentials: true
                },
                data: $("#nama-input").val(),
                success: function () {
                    alert("berhasil mengganti nama");
                    setTimeout(() => location.reload(), 1000);
                }
            })
        }

        $("document").ready(() => {
            $.ajax({
                url: "http://localhost:8080/edulib/buku/data-user",
                type: "get",
                contentType: "application/json",
                xhrFields: {
                    withCredentials: true
                },

                success: function (response) {
                    terlambatBadge(response.totalTerlambat);
                    dipinjamBadge(response.totalDipinjam);
                    peringatanTerlambat(response.totalTerlambat);
                },

                erro: function () {

                }
            });

            $.ajax({
                url: "http://localhost:8080/edulib/buku/penolakan",
                type: "get",
                xhrFields: {
                    withCredentials: true
                },
                success: function (response) {
                    showBuku(response.buku);
                },
                error: {
                    
                }
            });
        });
    </script>
</body>
</html>