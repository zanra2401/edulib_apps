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
                    <a class="nav-link active" href="http://localhost:8282/edulib/siswa/buku.php">Buku</a>
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
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="search-input" placeholder="Cari Judul Buku" aria-label="Cari Judul Buku" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="search-button">Search</button>
        </div>

        <h4 class="text-secondary m-0">Kategori</h4>

        <div id="kat-list" class="kategori-container flex flex-wrap">
            
        </div>

        <h4 class="text-secondary">Penulis</h4>

        <div id="pen-list" class="kategori-container flex flex-wrap m-0">
           
        </div>

        <div id="buku-container" class="d-flex flex-wrap justify-content-between gap-1">
           
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
        const selectedKategori = new Set();
        const selectedPenulis = new Set();

        // Toggle kategori
        $(document).on("click", ".kategori-badge", function () {
            const text = $(this).text();
            $(this).toggleClass("badge-active");

            if (selectedKategori.has(text)) {
                selectedKategori.delete(text);
            } else {
                selectedKategori.add(text);
            }
        });

        // Toggle penulis
        $(document).on("click", ".penulis-badge", function () {
            const text = $(this).text();
            $(this).toggleClass("badge-active");

            if (selectedPenulis.has(text)) {
                selectedPenulis.delete(text);
            } else {
                selectedPenulis.add(text);
            }
        });

        // Fungsi untuk melakukan search
        function searchBuku() {
            const judul = $("#search-input").val();
            const kategori = Array.from(selectedKategori);
            const penulis = Array.from(selectedPenulis);

            $.ajax({
                url: "http://localhost:8080/edulib/buku/search",
                type: "post",
                xhrFields: {
                    withCredentials: true,
                },
                contentType: "application/json",
                data: JSON.stringify({
                    judul,
                    kategori: ((kategori.length > 0) ? kategori : null),
                    penulis: ((penulis.length > 0) ? penulis : null) 
                }),
                success: function (response) {
                    let bukus = ``;

                    response.buku.body.buku.forEach((el) => {
                        let pathGambar = el.pathGambar ? `http://localhost:8080/gambar/buku/${el.pathGambar}` : 'https://placehold.co/80x120/e0e0e0/757575?text=No+Image';

                        bukus += `
                            <div class="p-2 buku-container-1" >
                                <div class="card h-100">
                                    <div class="card-body d-flex p-0">
                                        <img
                                            src="${pathGambar}"
                                            alt="Judul Buku: Judul Buku yang Sangat Panjang Sekali"
                                            onerror="this.onerror=null;this.src='https://placehold.co/400x300/cccccc/000000?text=Gambar+Tidak+Tersedia';"
                                        >
                                        <span class="d-flex flex-column p-3 info-book">
                                            <h5 class="card-title">${el.judul}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">${el.penulis}</h6>
                                            
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge rounded-pill text-bg-dark" style="cursor: pointer;">
                                                    ${el.kategori}
                                                </span>
                                            </div>

                                            <div class="mt-auto">
                                                <button type="button" onClick="pinjamBuku(this)" data-id-buku="${el.idBuku}" class="btn pinjam-tombol btn-primary w-100 pinjam">
                                                    Pinjam
                                                </button>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    $("#buku-container").html(bukus);
                }
            });
        }

        // Event untuk tombol search
        $("#search-button").on("click", searchBuku);

        function pinjamBuku(target) {
            console.log(target.getAttribute("data-id-buku"));
            $.ajax({
                url: `http://localhost:8080/edulib/buku/pinjam/${target.getAttribute("data-id-buku")}`,
                type: "get",
                xhrFields: {
                    withCredentials: true,
                },
                success: function(response, status, xhr) {
                    if (status == "success") {
                        $("#alert").toggleClass("out");
                        $("#alert").toggleClass("in");

                        setTimeout(() => {
                            $("#alert").toggleClass("out");
                            $("#alert").toggleClass("in");
                        }, 5000);
                    }
                }
            });
        }
    </script>

    <script>
        $("document").ready(() => {
            $.ajax({
                url: "http://localhost:8080/edulib/buku/getKP",
                type: "get",
                xhrFields: {
                    withCredentials: true
                },
                success: function (response) {
                    response.kategori.forEach((e) => {
                        $(`<span class="badge rounded-pill text-bg-light kategori-badge" style="cursor: pointer;">${e[0]}</span>`)
                        .appendTo("#kat-list");
                    });

                    response.penulis.forEach((e) => {
                        $(`<span class="badge rounded-pill text-bg-light penulis-badge" style="cursor: pointer;">${e[0]}</span>`)
                        .appendTo("#pen-list");
                    });
                }
            })
            $.ajax({
                url: "http://localhost:8080/edulib/buku/buku",
                type: "get",
                xhrFields: {
                    withCredentials: true,
                },
                success: function (response) {
                    let bukus = ``;

                    response.buku.content.forEach((el) => {
                        let pathGambar = el.pathGambar ? `http://localhost:8080/gambar/buku/${el.pathGambar}` : 'https://placehold.co/80x120/e0e0e0/757575?text=No+Image';

                        bukus += `
                            <div class="p-2 buku-container-1" >
                                <div class="card h-100">
                                    <div class="card-body d-flex p-0">
                                        <img
                                            src="${pathGambar}"
                                            alt="Judul Buku: Judul Buku yang Sangat Panjang Sekali"
                                            onerror="this.onerror=null;this.src='https://placehold.co/400x300/cccccc/000000?text=Gambar+Tidak+Tersedia';"
                                        >
                                        <span class="d-flex flex-column p-3 info-book">
                                            <h5 class="card-title">${el.judul}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">${el.penulis}</h6>
                                            
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge rounded-pill text-bg-dark" style="cursor: pointer;">
                                                    ${el.kategori}
                                                </span>
                                            </div>

                                            <div class="mt-auto">
                                                <button type="button" onClick="pinjamBuku(this)" data-id-buku="${el.idBuku}" class="btn pinjam-tombol btn-primary w-100 pinjam">
                                                    Pinjam
                                                </button>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    $("#buku-container").html(bukus);
                }
            });
        });
    </script>
</body>
</html>