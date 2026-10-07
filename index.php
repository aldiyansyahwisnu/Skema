<?php
include "koneksi.php";

/*
|--------------------------------------------------------------------------
| Ambil kategori/provider untuk daftar produk
|--------------------------------------------------------------------------
*/

$query_kategori = mysqli_query(
    $conn,
    "SELECT DISTINCT kategori
     FROM produk
     WHERE kategori IS NOT NULL
     AND kategori != ''
     ORDER BY kategori ASC"
);

/*
|--------------------------------------------------------------------------
| Ambil semua masa aktif untuk FILTER
|--------------------------------------------------------------------------
*/

$query_masa_aktif = mysqli_query(
    $conn,
    "SELECT DISTINCT masa_aktif
     FROM produk
     WHERE masa_aktif IS NOT NULL
     AND masa_aktif != ''
     AND masa_aktif != '-'"
);



/*
|--------------------------------------------------------------------------
| Ambil kategori/provider untuk FILTER
|--------------------------------------------------------------------------
*/

$query_kategori_filter = mysqli_query(
    $conn,
    "SELECT DISTINCT kategori
     FROM produk
     WHERE kategori IS NOT NULL
     AND kategori != ''
     ORDER BY kategori ASC"
);


/*
|--------------------------------------------------------------------------
| Simpan masa aktif ke array
|--------------------------------------------------------------------------
*/

$daftar_masa_aktif = [];

if ($query_masa_aktif) {

    while (
        $row_masa =
        mysqli_fetch_assoc($query_masa_aktif)
    ) {

        $nilai_masa =
            trim($row_masa['masa_aktif']);

        if ($nilai_masa !== '') {

            $daftar_masa_aktif[] =
                $nilai_masa;

        }

    }

}


/*
|--------------------------------------------------------------------------
| Urutkan masa aktif
|--------------------------------------------------------------------------
|
| Contoh:
| 1 Hari
| 2 Hari
| 3 Hari
| 7 Hari
| 14 Hari
| 30 Hari
|
|--------------------------------------------------------------------------
*/

usort(
    $daftar_masa_aktif,
    function ($a, $b) {

        /*
        | Ambil angka dari masa aktif
        */

        preg_match(
            '/\d+/',
            $a,
            $angka_a
        );

        preg_match(
            '/\d+/',
            $b,
            $angka_b
        );


        $angkaA =
            isset($angka_a[0])
            ? (int) $angka_a[0]
            : 999999;


        $angkaB =
            isset($angka_b[0])
            ? (int) $angka_b[0]
            : 999999;


        /*
        | Jika sama-sama memiliki angka,
        | urutkan berdasarkan angka
        */

        if ($angkaA !== $angkaB) {

            return $angkaA <=> $angkaB;

        }


        /*
        | Jika tidak ada angka / nilainya sama,
        | urutkan berdasarkan nama
        */

        return strcasecmp(
            $a,
            $b
        );

    }
);

/*
|--------------------------------------------------------------------------
| Ambil masa aktif dari produk
|--------------------------------------------------------------------------
*/

$query_masa_aktif = mysqli_query(
    $conn,
    "SELECT DISTINCT masa_aktif
     FROM produk
     WHERE masa_aktif IS NOT NULL
     AND masa_aktif != ''
     AND masa_aktif != '-'
     ORDER BY
        CAST(
            REGEXP_SUBSTR(masa_aktif, '[0-9]+')
            AS UNSIGNED
        ) ASC,
        masa_aktif ASC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Pricelist Produk</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>

        body {
            background: #f4f6f8;
            font-family: Arial, sans-serif;
            color: #111827;
        }

        .navbar {
            background: #fff;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
        }

        .hero {
            background: #fff;
            border-radius: 15px;
            padding: 35px 20px;
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .hero p {
            color: #6b7280;
            margin: 0;
        }

        .search-box {
            background: #fff;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        /*
        |--------------------------------------------------------------------------
        | Kategori
        |--------------------------------------------------------------------------
        */

        .kategori-section {
            margin-bottom: 35px;
        }

        .kategori-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .kategori-title h3 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .kategori-line {
            flex: 1;
            height: 1px;
            background: #dee2e6;
        }

        /*
        |--------------------------------------------------------------------------
        | Card produk
        |--------------------------------------------------------------------------
        */

        .product-card {
            background: #fff;
            border: none;
            border-radius: 14px;
            height: 100%;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
            transition: 0.2s;
        }

        .product-card:hover {
            transform: translateY(-2px);
        }

        .product-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-detail {
            color: #6b7280;
            font-size: 13px;
            min-height: 20px;
        }

        /*
        |--------------------------------------------------------------------------
        | Provider + Masa Aktif
        |--------------------------------------------------------------------------
        */

        .provider-masa {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 9px;
            flex-wrap: wrap;
        }

        .provider-badge {
    color: #fff;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 11px;
    font-weight: bold;
    display: inline-block;
}

/* INDOSAT */
.provider-indosat {
    background: #ffc107;
    color: #111;
}

/* XL */
.provider-xl {
    background: #0d6efd;
}

/* AXIS */
.provider-axis {
    background: #0dcaf0;
    color: #111;
}

/* SMART */
.provider-smart {
    background: #e83e8c;
}

/* TELKOMSEL */
.provider-telkomsel {
    background: #dc3545;
}

/* TELKOM */
.provider-telkom {
    background: #dc3545;
}

/* by.U */
.provider-byu {
    background: skyblue;
}

/* TRI */
.provider-tri {
    background: #198754;
}

/* Provider lainnya */
.provider-default {
    background: #6c757d;
}

        .masa-badge {
            background: #f1f3f5;
            color: #495057;
            border: 1px solid #dee2e6;
            padding: 3px 7px;
            border-radius: 5px;
            font-size: 11px;
        }

        .product-price {
            font-size: 17px;
            font-weight: bold;
            margin-top: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Tombol Selengkapnya
        |--------------------------------------------------------------------------
        */

        .btn-selengkapnya {
            margin-top: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Produk yang disembunyikan
        |--------------------------------------------------------------------------
        */

        .produk-hidden {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Pesan kosong
        |--------------------------------------------------------------------------
        */

        .empty {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        footer {
            margin-top: 50px;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive HP
        |--------------------------------------------------------------------------
        */

        @media (max-width: 576px) {

            .hero {
                padding: 28px 15px;
            }

            .hero h1 {
                font-size: 24px;
            }

            .kategori-title h3 {
                font-size: 18px;
            }

            .product-name {
                font-size: 15px;
            }

            .product-price {
                font-size: 16px;
            }

        }

    </style>

</head>

<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar py-3">

    <div class="container">

        <div class="brand">
            PRICELIST
        </div>

        <a href="admin/"
           class="btn btn-outline-primary btn-sm">
            Admin
        </a>

    </div>

</nav>


<div class="container">


<!-- =========================================================
     HERO
========================================================= -->

<div class="hero">

    <h1>
        Pricelist Produk Paket Data
    </h1>

    <p>
        Daftar harga produk terbaru
    </p>

</div>


<!-- =========================================================
     SEARCH
========================================================= -->

<div class="search-box">

    <div class="input-group">

        <span class="input-group-text">
            🔍
        </span>

        <input
            type="text"
            id="searchProduct"
            class="form-control"
            placeholder="Cari nama produk, provider, masa aktif, atau detail..."
            onkeyup="cariProduk()">

    </div>

</div>

<!-- =========================================================
     FILTER KATEGORI & MASA AKTIF
========================================================= -->

<div class="search-box">

    <div class="row g-2">

        <!-- FILTER KATEGORI -->

        <div class="col-12 col-md-6">

            <label class="form-label fw-bold mb-1">
                Kategori / Provider
            </label>

                    <select
            id="filterKategori"
            class="form-select">

            <option value="">
                Semua Kategori
            </option>

            <?php

            if (
                isset($query_kategori_filter) &&
                mysqli_num_rows($query_kategori_filter) > 0
            ) {

                while (
                    $kategori_filter =
                    mysqli_fetch_assoc($query_kategori_filter)
                ) {

                    $nama_kategori_filter =
                        trim($kategori_filter['kategori']);

            ?>

                <option
                    value="<?= htmlspecialchars(
                        strtolower($nama_kategori_filter)
                    ) ?>">

                    <?= htmlspecialchars(
                        $nama_kategori_filter
                    ) ?>

                </option>

            <?php

                }

            }

            ?>

        </select>

        </div>


        <!-- FILTER MASA AKTIF -->

        <div class="col-12 col-md-6">

            <label class="form-label fw-bold mb-1">
                Masa Aktif
            </label>

            <select
                id="filterMasaAktif"
                class="form-select">

                <option value="">
                    Semua Masa Aktif
                </option>

                <option value="harian">
                    Harian (1 - 7 Hari)
                </option>

                <option value="bulanan">
                    Bulanan (14 - 30 Hari)
                </option>

            </select>

        </div>

    </div>

</div>

<!-- =========================================================
     DAFTAR PRODUK
========================================================= -->

<div id="productContainer">

<?php

if (mysqli_num_rows($query_kategori) > 0) {

    while ($kategori = mysqli_fetch_assoc($query_kategori)) {

        $nama_kategori = $kategori['kategori'];

        $kategori_esc = mysqli_real_escape_string(
            $conn,
            $nama_kategori
        );

        /*
        | Ambil semua produk kategori
        */

        $query_produk = mysqli_query(
            $conn,
            "SELECT *
             FROM produk
             WHERE kategori='$kategori_esc'
             ORDER BY id DESC"
        );

        $jumlah_produk = mysqli_num_rows($query_produk);

?>

<!-- =========================================================
     GROUP KATEGORI
========================================================= -->

<div class="kategori-section product-group"
     data-kategori="<?= htmlspecialchars($nama_kategori) ?>">

    <!-- Judul kategori -->

    <div class="kategori-title">

        <h3>
            <?= htmlspecialchars($nama_kategori) ?>
        </h3>

        <div class="kategori-line"></div>

    </div>


    <!-- Produk -->

    <div class="row g-3">

        <?php

        $nomor_produk = 0;

        while ($produk = mysqli_fetch_assoc($query_produk)) {

            $nomor_produk++;

            /*
            | Produk ke 5 dan seterusnya disembunyikan
            */

            $class_hidden = ($nomor_produk > 4)
                ? 'produk-hidden'
                : '';

        ?>

        <div
    class="col-6 col-md-4 col-lg-3 product-item <?= $class_hidden ?>"
    data-index="<?= $nomor_produk ?>"
    data-kategori="<?= htmlspecialchars(strtolower(trim($produk['kategori']))) ?>"
    data-masa-aktif="<?= htmlspecialchars(strtolower(trim($produk['masa_aktif']))) ?>">

            <div class="card product-card">

                <div class="card-body">


                    <!-- Provider + Masa Aktif -->

                    <div class="provider-masa">

                        <?php

$provider = strtolower(trim($produk['kategori']));

$provider_class = "provider-default";

if ($provider == "indosat") {

    $provider_class = "provider-indosat";

} elseif ($provider == "xl") {

    $provider_class = "provider-xl";

} elseif ($provider == "axis") {

    $provider_class = "provider-axis";

} elseif ($provider == "smart") {

    $provider_class = "provider-smart";

} elseif (
    $provider == "telkomsel" ||
    $provider == "telkom"
) {

    $provider_class = "provider-telkomsel";

} elseif (
    $provider == "by.u" ||
    $provider == "byu"
) {

    $provider_class = "provider-byu";

} elseif ($provider == "tri") {

    $provider_class = "provider-tri";

}

?>

<span class="provider-badge <?= $provider_class ?>">

    <?= htmlspecialchars(
        strtoupper($produk['kategori'])
    ) ?>

</span>

                        <span class="masa-badge">

                            <?= htmlspecialchars(
                                $produk['masa_aktif']
                            ) ?>

                        </span>

                    </div>


                    <!-- Nama Produk -->

                    <div class="product-name">

                        <?= htmlspecialchars(
                            $produk['nama_produk']
                        ) ?>

                    </div>


                    <!-- Detail -->

                    <div class="product-detail">

                        <?= htmlspecialchars(
                            $produk['detail']
                        ) ?>

                    </div>


                    <!-- Harga -->

                    <div class="product-price">

                        Rp
                        <?= number_format(
                            $produk['harga'],
                            0,
                            ',',
                            '.'
                        ) ?>

                    </div>


                </div>

            </div>

        </div>

        <?php } ?>

    </div>


    <!-- =====================================================
         TOMBOL SELENGKAPNYA
    ====================================================== -->

    <?php if ($jumlah_produk > 4) { ?>

        <div class="text-center">

            <button
                type="button"
                class="btn btn-outline-primary btn-sm btn-selengkapnya"
                onclick="toggleProduk(this)">

                Selengkapnya

            </button>

        </div>

    <?php } ?>


</div>


<?php

    }

} else {

?>

<div class="empty">

    Belum ada produk.

</div>

<?php } ?>

</div>


<!-- =========================================================
     HASIL PENCARIAN KOSONG
========================================================= -->

<div id="noResult"
     class="empty"
     style="display:none;">

    Produk tidak ditemukan.

</div>


</div>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer>

    © 2026 Pricelist Produk Paket Data 3G

</footer>


<script>

/*
|--------------------------------------------------------------------------
| SELENGKAPNYA
|--------------------------------------------------------------------------
*/

function toggleProduk(button) {

    let group =
        button.closest(".product-group");

    let hiddenProducts =
        group.querySelectorAll(".produk-hidden");

    let sedangTerbuka =
        button.getAttribute("data-open") === "true";


    if (!sedangTerbuka) {

        /*
        | Tampilkan produk ke-5 dan seterusnya
        */

        hiddenProducts.forEach(function(product) {

            product.classList.remove("produk-hidden");

        });


        button.innerText = "Tutup";

        button.setAttribute(
            "data-open",
            "true"
        );


    } else {

        /*
        | Sembunyikan kembali produk ke-5 dan seterusnya
        */

        let allProducts =
            group.querySelectorAll(".product-item");


        allProducts.forEach(function(product, index) {

            if (index >= 4) {

                product.classList.add("produk-hidden");

            }

        });


        button.innerText = "Selengkapnya";

        button.setAttribute(
            "data-open",
            "false"
        );


        /*
        | Kembali ke bagian kategori
        */

        group.scrollIntoView({
            behavior: "smooth",
            block: "start"
        });

    }

}

/*
|--------------------------------------------------------------------------
| FILTER KATEGORI + MASA AKTIF + SEARCH
|--------------------------------------------------------------------------
*/

function filterProduk() {

    let searchInput =
        document.getElementById("searchProduct");

    let filterKategori =
        document.getElementById("filterKategori");

    let filterMasaAktif =
        document.getElementById("filterMasaAktif");


    let search =
        searchInput
            ? searchInput.value.toLowerCase().trim()
            : "";


    let kategori =
        filterKategori
            ? filterKategori.value.toLowerCase().trim()
            : "";


    let masaAktif =
        filterMasaAktif
            ? filterMasaAktif.value.toLowerCase().trim()
            : "";


    let products =
        document.querySelectorAll(".product-item");


    let groups =
        document.querySelectorAll(".product-group");


    let ditemukan = 0;


    /*
    |--------------------------------------------------------------------------
    | CEK SETIAP PRODUK
    |--------------------------------------------------------------------------
    */

    products.forEach(function(product) {

        let text =
            product.innerText.toLowerCase();


        let productKategori =
            (
                product.getAttribute("data-kategori") || ""
            ).toLowerCase().trim();


        let productMasaAktif =
            (
                product.getAttribute("data-masa-aktif") || ""
            ).toLowerCase().trim();


        /*
        |----------------------------------------------------------------------
        | Cek pencarian
        |----------------------------------------------------------------------
        */

        let cocokSearch =
            search === "" ||
            text.includes(search);


        /*
        |----------------------------------------------------------------------
        | Cek kategori
        |----------------------------------------------------------------------
        */

        let cocokKategori =
            kategori === "" ||
            productKategori === kategori;


        /*
        |----------------------------------------------------------------------
        | Cek masa aktif
        |----------------------------------------------------------------------
        */

        /*
        |----------------------------------------------------------------------
        | Filter masa aktif berdasarkan kelompok
        |----------------------------------------------------------------------
        | Harian  = 1 - 7 Hari
        | Bulanan = 14 - 30 Hari
        |----------------------------------------------------------------------
        */

        let angkaMasaAktif =
            parseInt(
                (productMasaAktif.match(/\d+/) || [0])[0],
                10
            );

        let cocokMasaAktif =
            masaAktif === "" ||
            (
                masaAktif === "harian" &&
                angkaMasaAktif >= 1 &&
                angkaMasaAktif <= 7
            ) ||
            (
                masaAktif === "bulanan" &&
                angkaMasaAktif >= 14 &&
                angkaMasaAktif <= 30
            );


        /*
        |----------------------------------------------------------------------
        | Hasil akhir
        |----------------------------------------------------------------------
        */

        if (
            cocokSearch &&
            cocokKategori &&
            cocokMasaAktif
        ) {

            product.style.display = "";

            ditemukan++;


        } else {

            product.style.display = "none";

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN / SEMBUNYIKAN KATEGORI
    |--------------------------------------------------------------------------
    */

    groups.forEach(function(group) {

        let groupProducts =
            group.querySelectorAll(".product-item");


        let visibleProducts = 0;


        groupProducts.forEach(function(product) {

            if (
                product.style.display !== "none"
            ) {

                visibleProducts++;

            }

        });


        /*
        | Jika tidak ada produk
        | sembunyikan group
        */

        if (visibleProducts === 0) {

            group.style.display = "none";


        } else {

            group.style.display = "";

        }


        /*
        |--------------------------------------------------------------------------
        | TOMBOL SELENGKAPNYA
        |--------------------------------------------------------------------------
        */

        let button =
            group.querySelector(".btn-selengkapnya");


        if (button) {

            /*
            | Jika sedang menggunakan filter
            | tampilkan semua hasil filter
            */

            if (
                search !== "" ||
                kategori !== "" ||
                masaAktif !== ""
            ) {

                groupProducts.forEach(function(product) {

                    if (
                        product.style.display !== "none"
                    ) {

                        product.classList.remove(
                            "produk-hidden"
                        );

                    }

                });


                button.style.display = "none";


            } else {

                /*
                | Jika filter kosong,
                | kembalikan tombol Selengkapnya
                */

                button.style.display = "";

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | PESAN PRODUK TIDAK DITEMUKAN
    |--------------------------------------------------------------------------
    */

    let noResult =
        document.getElementById("noResult");


    if (
        ditemukan === 0 &&
        products.length > 0
    ) {

        noResult.style.display = "block";


    } else {

        noResult.style.display = "none";

    }

}


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

function cariProduk() {

    filterProduk();

}


/*
|--------------------------------------------------------------------------
| FILTER KATEGORI
|--------------------------------------------------------------------------
*/

let filterKategori =
    document.getElementById("filterKategori");


if (filterKategori) {

    filterKategori.addEventListener(
        "change",
        function() {

            filterProduk();

        }
    );

}


/*
|--------------------------------------------------------------------------
| FILTER MASA AKTIF
|--------------------------------------------------------------------------
*/

let filterMasaAktif =
    document.getElementById("filterMasaAktif");


if (filterMasaAktif) {

    filterMasaAktif.addEventListener(
        "change",
        function() {

            filterProduk();

        }
    );

}


</script>


</body>

</html>