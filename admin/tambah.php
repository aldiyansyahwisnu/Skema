<?php
session_start();
if (!isset($_SESSION['admin'])) { 

    header("Location: login.php"); 

    exit; 

}
include "../koneksi.php";

$providers = ['Indosat','XL','Axis','Smart','Telkom','by.U','Tri'];

if (isset($_POST['simpan'])) {

    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $masa_aktif = mysqli_real_escape_string($conn, $_POST['masa_aktif']);
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);
    $harga = (int) $_POST['harga'];

    mysqli_query($conn, "INSERT INTO produk 
        (nama_produk, kategori, masa_aktif, detail, harga) VALUES 
        ('$nama_produk', '$kategori', '$masa_aktif', '$detail', '$harga')");

    header("Location: index.php"); exit;
}

?>


<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Tambah Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-4">Tambah Produk</h4>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: 20GB Unlimited" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Provider / Kategori</label>

            <select name="kategori" class="form-select" required>

                <option value="">Pilih provider</option>

                    <?php foreach ($providers as $provider) { ?>

                <option value="<?= htmlspecialchars($provider) ?>">

                            <?= htmlspecialchars($provider) ?>
                                
                            </option><?php } ?>
            </select>

    </div>

    <div class="mb-3">
        <label class="form-label">Masa Aktif</label>

            <input type="text" name="masa_aktif" class="form-control" placeholder="Contoh: 30 Hari" required></div>

                <div class="mb-3">

                    <label class="form-label">Detail</label>

                        <input type="text" name="detail" class="form-control" placeholder="Contoh: 5GB Internet 15GB Unlimited Apk">
                </div>

                <div class="mb-4">

                        <label class="form-label">Harga</label>

                            <input type="number" name="harga" class="form-control" placeholder="Contoh: 95000" min="0" required>
                </div>

                <div class="d-flex gap-2">
                    <a href="index.php" class="btn btn-secondary w-50">Kembali</a>
                        <button type="submit" name="simpan" class="btn btn-primary w-50">Simpan Produk</button>
                </div>
    </form>

    
                </div>
            </div>
        </div>
    </div>
</div>
</body></html>
