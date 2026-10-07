<?php
session_start();
include "../koneksi.php";

if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );

    $password = md5($_POST['password']);

    $query = mysqli_query(
        $conn,
        "SELECT * FROM admin
         WHERE username='$username'
         AND password='$password'
         LIMIT 1"
    );

    if (mysqli_num_rows($query) == 1) {

        $admin = mysqli_fetch_assoc($query);

        $_SESSION['admin'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];

        header("Location: index.php");
        exit;

    } else {

        $error = "Username atau password salah.";

    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

    <div class="row justify-content-center align-items-center"
         style="min-height:100vh;">

        <div class="col-11 col-sm-8 col-md-5 col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <div class="text-center mb-4">

                        <h3 class="fw-bold">
                            Login Admin
                        </h3>

                        <p class="text-muted mb-0">
                            Kelola Pricelist
                        </p>

                    </div>


                    <?php if ($error != "") { ?>

                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>

                    <?php } ?>


                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                placeholder="Masukkan username"
                                required>

                        </div>


                        <div class="mb-4">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Masukkan password"
                                required>

                        </div>


                        <button
                            type="submit"
                            name="login"
                            class="btn btn-primary w-100">

                            Login

                        </button>

                    </form>


                    <div class="text-center mt-3">

                        <a href="../index.php"
                           class="text-decoration-none">

                            ← Kembali ke Pricelist

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>