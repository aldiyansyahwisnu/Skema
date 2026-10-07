<?php
session_start();

if (!isset($_SESSION['admin'])){ 

    header("Location: login.php"); 

exit; 

}

include "../koneksi.php";

$providers = ['Indosat','XL','Axis','Smart','Telkom','by.U','Tri'];

if (!isset($_GET['id']) || empty($_GET['id'])) { 

    header("Location: index.php"); 

    exit; 
}

$id = (int) $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");

if (mysqli_num_rows($query) == 0) { 

    echo "Produk tidak ditemukan."; 
    exit; 
}

$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {

    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $masa_aktif = mysqli_real_escape_string($conn, $_POST['masa_aktif']);
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);
    $harga = (int) $_POST['harga'];
    $update = mysqli_query($conn,
        "UPDATE produk SET 
        nama_produk='$nama_produk', 
        kategori='$kategori', 
        masa_aktif='$masa_aktif', 
        detail='$detail', 
        harga='$harga' WHERE id='$id'");

    if ($update) { 

        header("Location: index.php");

    exit; 

}
    $error = "Gagal mengupdate produk: " . mysqli_error($conn);

}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light"><div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                <h3 class="mb-4">Edit Produk</h3>

                <?php if (isset($error)) { 
                ?>
             <div class="alert alert-danger"><?= htmlspecialchars($error) ?>
        
         </div>

<?php } ?>

<form method="POST">
    <div class="mb-3">

        <label class="form-label">Nama Produk</label>
    
    <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($data['nama_produk']) ?>" required>
</div>

<div class="mb-3">
    <label class="form-label">Provider / Kategori</label>

        <select name="kategori" class="form-select" required>

            <option value="">Pilih provider</option>

            <?php foreach ($providers as $provider) { ?>
                
                <option value="<?= htmlspecialchars($provider) ?>" <?= ($data['kategori'] == $provider) ? 'selected' : '' ?>><?= htmlspecialchars($provider) ?>
                    
            </option>

        <?php } ?>

        </select>

    </div>

    <div class="mb-3">

        <label class="form-label">Masa Aktif</label>

            <input type="text" name="masa_aktif" class="form-control" value="<?= htmlspecialchars($data['masa_aktif'] ?? '-') ?>" placeholder="Contoh: 30 Hari" required>

    </div>

    <div class="mb-3">

        <label class="form-label">Detail</label>

        <input type="text" name="detail" class="form-control" value="<?= htmlspecialchars($data['detail']) ?>">

    </div>
        <div class="mb-4">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= htmlspecialchars($data['harga']) ?>" min="0" required>

        </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="index.php" 
                                class="btn btn-secondary"
                                >Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
